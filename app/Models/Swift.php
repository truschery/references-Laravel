<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Swift extends Model
{
    /** @use HasFactory<\Database\Factories\SwiftFactory> */
    use HasFactory, HasUuids, SoftDeletes;


    protected $fillable = [
        'swift_code',
        'bank_name',
        'country',
        'city',
        'address',
        'created_by',
        'updated_by',
    ];
}
