<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhiteList extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "brand"
    ];

    /**
     * Get the user that owns the ForeignShipping.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
