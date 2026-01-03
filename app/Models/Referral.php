<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_id',
        'referral_code',
        'status',
        'referrer_points_awarded',
        'referred_points_awarded',
        'completed_at',
        'rewarded_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'rewarded_at' => 'datetime',
    ];

    /**
     * The user who made the referral
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * The user who was referred
     */
    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    /**
     * Scope for pending referrals
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed referrals
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'rewarded']);
    }

    /**
     * Mark as completed (first order made)
     */
    public function markCompleted(): void
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Mark as rewarded (points awarded)
     */
    public function markRewarded(int $referrerPoints, int $referredPoints): void
    {
        $this->status = 'rewarded';
        $this->referrer_points_awarded = $referrerPoints;
        $this->referred_points_awarded = $referredPoints;
        $this->rewarded_at = now();
        $this->save();
    }

    /**
     * Get status label in Arabic
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد الانتظار',
            'completed' => 'مكتمل',
            'rewarded' => 'تم منح المكافأة',
            default => $this->status,
        };
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'completed' => 'info',
            'rewarded' => 'success',
            default => 'secondary',
        };
    }
}
