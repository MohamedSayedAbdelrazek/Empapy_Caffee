<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class LoyaltyPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_earned',
        'total_redeemed',
        'available_points',
        'current_tier',
        'tier_points',
        'referral_code',
        'birthday',
        'birthday_bonus_claimed',
        'last_activity_at',
    ];

    protected $casts = [
        'birthday' => 'date',
        'birthday_bonus_claimed' => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    /**
     * The user who owns these loyalty points
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the current tier model
     */
    public function tier(): BelongsTo
    {
        return $this->belongsTo(LoyaltyTier::class, 'current_tier', 'slug');
    }

    /**
     * Get all point transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class, 'user_id', 'user_id');
    }

    /**
     * Generate a unique referral code
     */
    public static function generateReferralCode(User $user): string
    {
        $base = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $user->name), 0, 4));
        $base = $base ?: 'USER';

        do {
            $code = $base . rand(1000, 9999);
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Add points to user
     */
    public function addPoints(int $points, string $source, string $description, string $descriptionAr, $reference = null, $adminId = null): PointTransaction
    {
        $this->available_points += $points;
        $this->total_earned += $points;
        $this->tier_points += $points;
        $this->last_activity_at = now();
        $this->save();

        return PointTransaction::create([
            'user_id' => $this->user_id,
            'points' => $points,
            'balance_after' => $this->available_points,
            'type' => $points > 0 ? 'earned' : 'adjustment',
            'source' => $source,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference?->id,
            'description' => $description,
            'description_ar' => $descriptionAr,
            'admin_id' => $adminId,
        ]);
    }

    /**
     * Deduct points from user
     */
    public function deductPoints(int $points, string $source, string $description, string $descriptionAr, $reference = null): PointTransaction
    {
        if ($this->available_points < $points) {
            throw new \Exception('Insufficient points balance');
        }

        $this->available_points -= $points;
        $this->total_redeemed += $points;
        $this->last_activity_at = now();
        $this->save();

        return PointTransaction::create([
            'user_id' => $this->user_id,
            'points' => -$points,
            'balance_after' => $this->available_points,
            'type' => 'redeemed',
            'source' => $source,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference?->id,
            'description' => $description,
            'description_ar' => $descriptionAr,
        ]);
    }

    /**
     * Get progress to next tier (0-100)
     */
    public function getProgressToNextTierAttribute(): int
    {
        $currentTier = LoyaltyTier::where('slug', $this->current_tier)->first();
        $nextTier = LoyaltyTier::where('min_points', '>', $currentTier?->min_points ?? 0)
            ->orderBy('min_points')
            ->first();

        if (!$nextTier) {
            return 100; // Already at max tier
        }

        $currentMin = $currentTier?->min_points ?? 0;
        $pointsInTier = $this->tier_points - $currentMin;
        $pointsNeeded = $nextTier->min_points - $currentMin;

        return min(100, (int) (($pointsInTier / $pointsNeeded) * 100));
    }

    /**
     * Get points needed for next tier
     */
    public function getPointsToNextTierAttribute(): int
    {
        $nextTier = LoyaltyTier::where('min_points', '>', $this->tier_points)
            ->orderBy('min_points')
            ->first();

        return $nextTier ? max(0, $nextTier->min_points - $this->tier_points) : 0;
    }

    /**
     * Check and update tier if needed
     */
    public function updateTier(): bool
    {
        $newTier = LoyaltyTier::where('min_points', '<=', $this->tier_points)
            ->where('is_active', true)
            ->orderByDesc('min_points')
            ->first();

        if ($newTier && $newTier->slug !== $this->current_tier) {
            $oldTier = $this->current_tier;
            $this->current_tier = $newTier->slug;
            $this->save();

            return true; // Tier changed
        }

        return false;
    }

    /**
     * Get formatted points display
     */
    public function getFormattedPointsAttribute(): string
    {
        return number_format($this->available_points);
    }
}
