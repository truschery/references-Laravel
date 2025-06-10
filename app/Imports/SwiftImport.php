<?php

namespace App\Imports;

use App\Models\Swift;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SwiftImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;


    }

    public function model(array $row)
    {


        return new Swift([
            'swift_code' => $row['swift_kod'],
            'bank_name' => $row['bank'],
            'country' => $row['strana'],
            'city' => $row['gorod'],
            'address' => $row['adres'],
            'created_by' => $this->userId,
            'updated_by' => $this->userId,
        ]);
    }

    public function startRow(): int
    {
        return 5;
    }


    public function chunkSize(): int
    {
        return 500;
    }

    public function rules(): array
    {
        return [
            'swift_kod'  => 'required|string|unique:swifts,swift_code',
            'bank' => 'required|string',
            'strana' => 'required|string',
            'gorod' => 'required|string',
            'adres' => 'required|string',
        ];
    }
}
