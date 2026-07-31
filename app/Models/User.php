<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\NotifiableTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use NotifiableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_at',
        'last_login_ip',
        'profile_photo_path',
        'is_blocked',
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
        'last_login_at' => 'datetime',
        'is_blocked' => 'boolean',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        return $this->profile_photo_path;
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function getDefaultAddressAttribute()
    {
        return $this->addresses?->first();
    }

    public function measurements()
    {
        return $this->hasMany(CustomerMeasurement::class);
    }

    public function getDefaultMeasurementAttribute()
    {
        return $this->measurements()->where('is_default', true)->first() ?? $this->measurements?->first();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tailorAssignments()
    {
        return $this->hasMany(StitchingOrder::class, 'tailor_id');
    }

    /**
     * Alias for tailorAssignments (for reports)
     */
    public function stitchingOrders()
    {
        return $this->tailorAssignments();
    }

    public function receptionist()
    {
        return $this->hasOne(Receptionist::class);
    }

    public function tailor()
    {
        return $this->hasOne(Tailor::class);
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }

    public function isReceptionist()
    {
        return $this->hasRole('receptionist');
    }

    public function isTailor()
    {
        return $this->hasRole('tailor');
    }

    public function isCustomer()
    {
        return $this->hasRole('customer') || !$this->roles()->exists();
    }

    /**
     * Get total number of orders for this customer
     */
    public function getTotalOrdersCount()
    {
        return $this->orders()->count();
    }

    /**
     * Get total spending for this customer (only paid orders)
     */
    public function getTotalSpending()
    {
        return $this->orders()
            ->where('payment_status', 'paid')
            ->sum('total');
    }

    /**
     * Get average order value for this customer
     */
    public function getAverageOrderValue()
    {
        $totalOrders = $this->getTotalOrdersCount();
        if ($totalOrders === 0) {
            return 0;
        }
        return $this->getTotalSpending() / $totalOrders;
    }

    /**
     * Check if customer is blocked
     */
    public function isBlocked()
    {
        return $this->is_blocked === true;
    }
}
