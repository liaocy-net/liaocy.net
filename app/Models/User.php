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
     * Get the ForeignShippings with the user.
     */
    public function foreignShippings(): HasMany
    {
        return $this->hasMany(ForeignShipping::class);
    }

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
}
