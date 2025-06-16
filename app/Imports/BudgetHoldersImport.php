<?php

namespace App\Imports;

use App\Models\BudgetHolders;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BudgetHoldersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, ShouldQueue
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


            return new BudgetHolders([
                'tin' => $row['inn'],
                'name' => $row['naimenovanie'],
                'region' => $row['region'],
                'district' => $row['raion'],
                'address' => $row['adres'],
                'phone' => $row['telefon'],
                'responsible' => $row['otvetstvennyi'],
                'created_by' => $this->userId,
                'updated_by' => $this->userId,
            ]);
        }

        public function chunkSize(): int
        {
            return 500;
        }

        public function rules(): array
        {
            return [
               'inn' => 'required|unique:budget_holders,tin',
               'naimenovanie' => 'required',
               'region' => 'required',
               'raion' => 'required',
               'adres' => 'required',
               'telefon' => 'required',
               'otvetstvennyi' => 'required',
            ];
        }
}
