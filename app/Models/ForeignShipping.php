<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForeignShipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'weight_kg',
        'usd_fee',
    ];

    /**
     * Get the user that owns the ForeignShipping.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
