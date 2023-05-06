<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlackList extends Model
{
    use HasFactory;

    
    protected $fillable = [
        "user_id",
        "platform",
        "on",
        "value",
    ];

    /**
     * Get the user that owns the ForeignShipping.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
