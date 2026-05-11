<?php

namespace App\Exports;

use App\Models\Lansia;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LansiaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Lansia::all();
    }

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

    public function map($lansia): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $lansia->nama,
            $lansia->nik,
            $lansia->tanggal_lahir,
            $lansia->umur,
            $lansia->note,
            $lansia->alamat,
            $lansia->desa,
            $lansia->kecamatan,
            $lansia->kabupaten,
            $lansia->provinsi,
            $lansia->rt,
            $lansia->rw,
            $lansia->latitude,
            $lansia->longitude,
            $lansia->status,
            $lansia->created_at,
        ];
    }
}
