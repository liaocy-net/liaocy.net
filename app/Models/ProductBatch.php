<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_batch_id',
        'filename',
        'action',
    ];

    /**
     * Get the ProductBatches with the user.
     */
    public function productBatches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
