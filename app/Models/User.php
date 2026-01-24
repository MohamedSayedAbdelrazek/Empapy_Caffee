<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'city',
        'governorate',
        'avatar',
        'referred_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Boot the model
     */
    protected static function booted(): void
    {
        static::created(function (User $user) {
            // Create loyalty points record for new users
            $user->loyaltyPoints()->create([
                'referral_code' => LoyaltyPoint::generateReferralCode($user),
            ]);
        });
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Check if user is a cashier
     */
    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    /**
     * Check if user is staff (admin or cashier - has access to admin panel)
     */
    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'cashier']);
    }

    // ========================================
    // PERMISSIONS
    // ========================================

    /**
     * Get all permissions for this user
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Admins have all permissions
        if ($this->role === 'admin') {
            return true;
        }

        return $this->permissions()->where('name', $permission)->exists();
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        // Admins have all permissions
        if ($this->role === 'admin') {
            return true;
        }

        return $this->permissions()->whereIn('name', $permissions)->exists();
    }

    /**
     * Give permission(s) to user
     */
    public function givePermissionTo(string|array $permissions): void
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];

        $permissionIds = Permission::whereIn('name', $permissions)->pluck('id');

        $this->permissions()->syncWithoutDetaching($permissionIds);
    }

    /**
     * Remove permission(s) from user
     */
    public function revokePermissionFrom(string|array $permissions): void
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];

        $permissionIds = Permission::whereIn('name', $permissions)->pluck('id');

        $this->permissions()->detach($permissionIds);
    }

    /**
     * Sync user permissions (replaces all existing permissions)
     */
    public function syncPermissions(array $permissions): void
    {
        $permissionIds = Permission::whereIn('id', $permissions)->pluck('id');

        $this->permissions()->sync($permissionIds);
    }

    /**
     * Get all orders for this user
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all devices for this user (for push notifications)
     */
    public function devices(): HasMany
    {
        return $this->hasMany(UserDevice::class);
    }

    // ========================================
    // LOYALTY RELATIONSHIPS
    // ========================================

    /**
     * Get user's loyalty points record
     */
    public function loyaltyPoints(): HasOne
    {
        return $this->hasOne(LoyaltyPoint::class);
    }

    /**
     * Get all point transactions
     */
    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    /**
     * Get all reward redemptions
     */
    public function rewardRedemptions(): HasMany
    {
        return $this->hasMany(RewardRedemption::class);
    }

    /**
     * Get referrals made by this user
     */
    public function referralsMade(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /**
     * Get the referral record for this user (if referred)
     */
    public function referralReceived(): HasOne
    {
        return $this->hasOne(Referral::class, 'referred_id');
    }

    // ========================================
    // LOYALTY HELPERS
    // ========================================

    /**
     * Get user's available points
     */
    public function getPointsAttribute(): int
    {
        return $this->loyaltyPoints?->available_points ?? 0;
    }

    /**
     * Get user's current tier
     */
    public function getTierAttribute(): ?LoyaltyTier
    {
        $tierSlug = $this->loyaltyPoints?->current_tier;
        return $tierSlug ? LoyaltyTier::where('slug', $tierSlug)->first() : null;
    }

    /**
     * Get user's referral code
     */
    public function getReferralCodeAttribute(): ?string
    {
        return $this->loyaltyPoints?->referral_code;
    }

    /**
     * Get referral link
     */
    public function getReferralLinkAttribute(): string
    {
        return route('register', ['ref' => $this->referral_code]);
    }

    /**
     * Get available rewards for this user
     */
    public function getAvailableRewards()
    {
        return LoyaltyReward::active()
            ->inStock()
            ->affordable($this->points)
            ->orderBy('points_required')
            ->get();
    }

    /**
     * Get pending reward redemptions
     */
    public function getPendingRedemptions()
    {
        return $this->rewardRedemptions()
            ->valid()
            ->with('reward')
            ->get();
    }

    /**
     * Check if user has completed first order
     */
    public function hasCompletedFirstOrder(): bool
    {
        return $this->orders()->where('status', 'delivered')->exists();
    }

    /**
     * Get count of successful referrals
     */
    public function getSuccessfulReferralsCountAttribute(): int
    {
        return $this->referralsMade()->completed()->count();
    }
}
