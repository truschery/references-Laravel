<?php

namespace App\Exports;

use App\Models\BudgetHolders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomQuerySize;


class BudgetHoldersExport implements FromQuery, WithHeadings
{

    use Exportable;

    public function querySize(): int{
        return 15000;
    }

    public function query()
    {
        return BudgetHolders::query()->select(['tin', 'name', 'region', 'district', 'address', 'phone', 'responsible']);
    }

    public function headings(): array
    {
        return [
            'Инн',
            'Наименование',
            'Регион',
            'Район',
            'Адрес',
            'Телефон',
            'Ответственный',
        ];
    }
}
