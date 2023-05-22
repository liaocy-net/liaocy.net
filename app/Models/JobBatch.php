<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'total_jobs',
        'pending_jobs',
        'failed_jobs',
        'failed_jobs_ids',
        'options',
        'cancelled_at',
        'created_at',
        'finished_at',
    ];

}
