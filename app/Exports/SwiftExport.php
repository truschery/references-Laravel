<?php

namespace App\Exports;

use App\Models\Swift;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomQuerySize;


class SwiftExport implements FromQuery, WithHeadings, ShouldQueue
{

    use Exportable;

    public function querySize(): int{
        return 15000;
    }

    public function query()
    {
        return Swift::query()->select(['swift_code', 'bank_name', 'country', 'city', 'address']);
    }

    public function headings(): array
    {
        return [
            'Swift код',
            'Банк',
            'Страна',
            'Город',
            'Адрес',
        ];
    }

//     public function chunkSize(): int
//     {
//         // Размер чанка для обработки больших данных
//         return 1000;
//     }

//     public function map($swift): array
//     {
//         return [
//             $swift->swift_code,
//             $swift->bank_name,
//             $swift->country,
//             $swift->city,
//             $swift->address,
//         ];
//     }
}
