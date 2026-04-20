<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    const NOMINATIM_API = "https://nominatim.openstreetmap.org/search";
    const USER_AGENT = "GISLansia/1.0 (gislansia@localhost)";

    /**
     * Hit Nominatim dengan parameter bebas.
     * Selalu sertakan User-Agent (wajib sesuai ToS Nominatim).
     */
    private static function nominatim(array $params): ?array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    "User-Agent" => self::USER_AGENT,
                    "Accept-Language" => "id,en;q=0.8",
                ])
                ->get(
                    self::NOMINATIM_API,
                    array_merge(
                        [
                            "format" => "json",
                            "limit" => 1,
                            "countrycodes" => "id",
                            "addressdetails" => 1,
                        ],
                        $params,
                    ),
                );

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data)) {
                    $r = $data[0];
                    return [
                        "latitude" => floatval($r["lat"]),
                        "longitude" => floatval($r["lon"]),
                        "display_name" => $r["display_name"] ?? null,
                        "type" => $r["type"] ?? null,
                        "address" => $r["address"] ?? [],
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning(
                "GeocodingService Nominatim error: " . $e->getMessage(),
            );
        }

        return null;
    }

    /**
     * Cek apakah nama desa muncul di display_name hasil Nominatim.
     * Pakai perbandingan case-insensitive dan toleransi spasi.
     */
    private static function desaFoundInResult(string $desa, array $result): bool
    {
        $displayName = mb_strtolower($result["display_name"] ?? "");
        $desaLower = mb_strtolower(trim($desa));

        if (empty($desaLower) || empty($displayName)) {
            return false;
        }

        // Cek langsung nama desa ada di display_name
        if (str_contains($displayName, $desaLower)) {
            return true;
        }

        // Cek di address details (village / suburb / city_district / town / hamlet)
        $address = $result["address"] ?? [];
        $desaFields = [
            "village",
            "suburb",
            "hamlet",
            "town",
            "city_district",
            "neighbourhood",
            "quarter",
        ];
        foreach ($desaFields as $field) {
            if (isset($address[$field])) {
                $fieldVal = mb_strtolower($address[$field]);
                // Cocokkan: nama desa ada di field, atau field ada di nama desa
                if (
                    str_contains($fieldVal, $desaLower) ||
                    str_contains($desaLower, $fieldVal)
                ) {
                    return true;
                }
            }
        }

        // Toleransi: cek kata per kata (minimal salah satu kata unik desa cocok)
        $desaWords = array_filter(
            explode(" ", $desaLower),
            fn($w) => mb_strlen($w) > 2,
        );
        foreach ($desaWords as $word) {
            if (str_contains($displayName, $word)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Geocode free-text sederhana (helper publik).
     */
    public static function geocodeAddress(string $address): ?array
    {
        if (empty(trim($address))) {
            return null;
        }
        return self::nominatim(["q" => $address]);
    }

    /**
     * Geocode dengan prioritas DESA — multi-strategy bertahap + validasi.
     *
     * Setiap strategi yang mengklaim menemukan desa WAJIB divalidasi:
     * nama desa harus ada di display_name / address fields — kalau tidak, lanjut ke strategi berikutnya.
     *
     * Urutan:
     *  1. Structured: village = desa               → validasi desa
     *  2. Structured: village = "Desa {desa}"      → validasi desa
     *  3. Free-text : "Desa X, Kec. Y, Kab. Z"    → validasi desa
     *  4. Free-text : "{desa}, {kecamatan}, {kab}" → validasi desa
     *  5. Structured: city = desa                  → validasi desa
     *  6. Structured: town = desa                  → validasi desa
     *  7. Kecamatan saja                           → tanpa validasi desa (fallback level kecamatan)
     *  8. Kabupaten saja                           → tanpa validasi desa (last resort)
     *
     * @return array|null ['latitude', 'longitude', 'display_name', 'strategy']
     */
    public static function geocodeWithLocality(
        string $desa,
        string $kecamatan,
        string $kabupaten,
        string $provinsi = "Nusa Tenggara Barat",
    ): ?array {
        $desa = trim($desa);
        $kecamatan = trim($kecamatan);
        $kabupaten = trim($kabupaten);
        $provinsi = trim($provinsi) ?: "Nusa Tenggara Barat";

        // ── Strategi 1 ────────────────────────────────────────────────────────
        // Nominatim structured: village = nama desa
        $result = self::nominatim([
            "village" => $desa,
            "county" => $kabupaten,
            "state" => $provinsi,
            "country" => "Indonesia",
        ]);
        if ($result && self::desaFoundInResult($desa, $result)) {
            return array_merge($result, ["strategy" => "structured-village"]);
        }

        // ── Strategi 2 ────────────────────────────────────────────────────────
        // village = "Desa {desa}" — beberapa node OSM pakai prefix "Desa"
        $result = self::nominatim([
            "village" => "Desa " . $desa,
            "county" => $kabupaten,
            "state" => $provinsi,
            "country" => "Indonesia",
        ]);
        if ($result && self::desaFoundInResult($desa, $result)) {
            return array_merge($result, [
                "strategy" => "structured-village-prefix",
            ]);
        }

        // ── Strategi 3 ────────────────────────────────────────────────────────
        // Free-text dengan label "Desa" dan "Kec." agar Nominatim lebih mudah parse
        $result = self::nominatim([
            "q" => "Desa {$desa}, Kec. {$kecamatan}, Kab. {$kabupaten}, {$provinsi}, Indonesia",
        ]);
        if ($result && self::desaFoundInResult($desa, $result)) {
            return array_merge($result, [
                "strategy" => "freetext-full-labeled",
            ]);
        }

        // ── Strategi 4 ────────────────────────────────────────────────────────
        // Free-text ringkas tanpa label
        $result = self::nominatim([
            "q" => "{$desa}, {$kecamatan}, {$kabupaten}, {$provinsi}, Indonesia",
        ]);
        if ($result && self::desaFoundInResult($desa, $result)) {
            return array_merge($result, ["strategy" => "freetext-full"]);
        }

        // ── Strategi 5 ────────────────────────────────────────────────────────
        // Structured: city = desa (beberapa desa OSM dikategorikan city/town/suburb)
        $result = self::nominatim([
            "city" => $desa,
            "county" => $kabupaten,
            "state" => $provinsi,
            "country" => "Indonesia",
        ]);
        if ($result && self::desaFoundInResult($desa, $result)) {
            return array_merge($result, [
                "strategy" => "structured-city-fallback",
            ]);
        }

        // ── Strategi 6 ────────────────────────────────────────────────────────
        // Structured: town = desa
        $result = self::nominatim([
            "town" => $desa,
            "county" => $kabupaten,
            "state" => $provinsi,
            "country" => "Indonesia",
        ]);
        if ($result && self::desaFoundInResult($desa, $result)) {
            return array_merge($result, [
                "strategy" => "structured-town-fallback",
            ]);
        }

        // ── Strategi 7 ────────────────────────────────────────────────────────
        // Turun ke level KECAMATAN — desa tidak ditemukan/tidak ada di OSM
        // Tidak perlu validasi desa karena memang sudah fallback
        $result = self::nominatim([
            "q" => "Kecamatan {$kecamatan}, {$kabupaten}, {$provinsi}, Indonesia",
        ]);
        if ($result) {
            return array_merge($result, ["strategy" => "kecamatan-fallback"]);
        }

        // ── Strategi 8 ────────────────────────────────────────────────────────
        // Last resort: hanya kabupaten
        $result = self::nominatim([
            "county" => $kabupaten,
            "state" => $provinsi,
            "country" => "Indonesia",
        ]);
        if ($result) {
            return array_merge($result, ["strategy" => "kabupaten-only"]);
        }

        return null;
    }
}
