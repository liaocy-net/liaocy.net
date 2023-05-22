<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_batch_id',
        'filename',
        'action',
        'is_exhibit_to_amazon',
        'is_exhibit_to_yahoo',
        'exhibit_yahoo_category',
    ];

    /**
     * Get the ProductBatches with the user.
     */
    // public function productBatches(): HasMany
    // {
    //     return $this->hasMany(ProductBatch::class);
    // }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobBatch() : HasOne
    {
        return $this->HasOne(JobBatch::class);
    }
}
