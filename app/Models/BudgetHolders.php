<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetHolders extends Model
{
    /** @use HasFactory<\Database\Factories\BudgetHoldersFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'tin',
        'name',
        'region',
        'district',
        'address',
        'phone',
        'responsible',
        'created_by',
        'updated_by',
    ];
}
