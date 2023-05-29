<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForeignShipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'weight_kg',
        'usd_fee',
    ];
}
