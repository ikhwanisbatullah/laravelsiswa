<?php

namespace App\Exports;

use App\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;


class SiswaExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Siswa::all();
    }
    public function map($siswa): array
    {
        // This example will return 3 rows.
        // First row will have 2 column, the next 2 will have 1 column
        return [
            [
                $siswa->nama_lengkap(),
                $siswa->jenis_kelamin,
                $siswa->agama,
                $siswa->rataRataNilai()
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Jenis Kelamin',
            'Agama',
            'Rata-Rata Nilai',
        ];
    }
}
