<?php

namespace App\Imports;

use App\Models\Lansia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class LansiaImport implements ToModel, WithHeadings, SkipsEmptyRows
{
    private $row = 0;

    public function headings(): array
    {
        return [
            'No.',
            'Nama',
            'NIK',
            'Tanggal Lahir',
            'Umur',
            'Kondisi',
            'Alamat',
            'Desa',
            'Kecamatan',
            'Kabupaten',
            'Provinsi',
            'RT',
            'RW',
            'Latitude',
            'Longitude',
            'Status',
            'Tanggal Pendataan',
        ];
    }

    public function model(array $row)
    {
        $this->row++;

        // Skip header row
        if ($this->row === 1) {
            return null;
        }

        return new Lansia([
            'nama'          => $row[1] ?? null,
            'nik'           => $row[2] ?? null,
            'tanggal_lahir' => $row[3] ?? null,
            'umur'          => $row[4] ?? null,
            'note'          => $row[5] ?? null,
            'alamat'        => $row[6] ?? null,
            'desa'          => $row[7] ?? null,
            'kecamatan'     => $row[8] ?? null,
            'kabupaten'     => $row[9] ?? null,
            'provinsi'      => $row[10] ?? 'Nusa Tenggara Barat',
            'rt'            => $row[11] ?? null,
            'rw'            => $row[12] ?? null,
            'latitude'      => $row[13] ?? null,
            'longitude'     => $row[14] ?? null,
            'status'        => $row[15] ?? 'pending',
            'pendata'       => auth()->user()->name ?? null,
            'user_id'       => auth()->id(),
        ]);
    }
}
