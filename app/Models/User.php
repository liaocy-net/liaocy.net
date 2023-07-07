<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'last_name',
        'first_name',
        'role',
        'email_verified_at',
        'password',
        'yahoo_store_account',
        'yahoo_client_id',
        'yahoo_secret',
        'yahoo_access_token',
        'yahoo_access_token_expires_in',
        'yahoo_refresh_token',
        'yahoo_refresh_token_expires_in',
        'yahoo_min_profit',
        'yahoo_profit_rate',
        'yahoo_using_profit',
        'yahoo_using_sale_commission',
        'yahoo_stock',
        'yahoo_category',
        'amazon_jp_refresh_token',
        'amazon_jp_access_token',
        'amazon_jp_access_token_expires_in',
        'amazon_us_refresh_token',
        'amazon_us_access_token',
        'amazon_us_access_token_expires_in',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(User::class, 'foreign_key', 'owner_key');
    }

    /**
     * Get the WhiteLists with the user.
     */
    public function whiteLists(): HasMany
    {
        return $this->hasMany(WhiteList::class);
    }

    /**
     * Get the BlackLists with the user.
     */
    public function blackLists(): HasMany
    {
        return $this->hasMany(BlackList::class);
    }

    /**
     * Get the ProductBatches with the user.
     */
    public function productBatches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }

    /**
     * Get the Products with the user.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get my job suffix
     */
    public function getJobSuffix(): string
    {
        return str_pad($this->id % 10, 3, "0", STR_PAD_LEFT);
    }
}
