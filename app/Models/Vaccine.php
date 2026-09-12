<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[WithoutTimestamps]
class Vaccine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'description',
    ];
}
