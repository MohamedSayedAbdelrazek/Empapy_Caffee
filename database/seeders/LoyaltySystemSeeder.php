<?php

namespace Database\Seeders;

use App\Models\PointRule;
use App\Models\LoyaltyReward;
use App\Models\LoyaltyTier;
use Illuminate\Database\Seeder;

class LoyaltySystemSeeder extends Seeder
{
    /**
     * Seed the loyalty system data (safe for production)
     * Uses firstOrCreate to avoid duplicates
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Loyalty System Seeding...');

        // 1. Seed Point Rules
        $this->seedPointRules();

        // 2. Seed Loyalty Tiers
        $this->seedLoyaltyTiers();

        // 3. Seed Loyalty Rewards
        $this->seedLoyaltyRewards();

        $this->command->info('✅ Loyalty System seeded successfully!');
    }

    /**
     * Seed Point Rules
     */
    private function seedPointRules(): void
    {
        $rules = PointRule::getDefaultRules();
        $count = 0;

        foreach ($rules as $rule) {
            $created = PointRule::firstOrCreate(
                ['slug' => $rule['slug']],
                $rule
            );

            if ($created->wasRecentlyCreated) {
                $count++;
            }
        }

        $this->command->info("📊 Point Rules: {$count} new rules created (total: " . count($rules) . ")");
    }

    /**
     * Seed Loyalty Tiers
     */
    private function seedLoyaltyTiers(): void
    {
        $tiers = LoyaltyTier::getDefaultTiers();
        $count = 0;

        foreach ($tiers as $tier) {
            $created = LoyaltyTier::firstOrCreate(
                ['slug' => $tier['slug']],
                $tier
            );

            if ($created->wasRecentlyCreated) {
                $count++;
            }
        }

        $this->command->info("🏆 Loyalty Tiers: {$count} new tiers created (total: " . count($tiers) . ")");
    }

    /**
     * Seed Loyalty Rewards
     */
    private function seedLoyaltyRewards(): void
    {
        $rewards = LoyaltyReward::getDefaultRewards();
        $count = 0;

        foreach ($rewards as $index => $reward) {
            // Use name as unique identifier since rewards don't have slugs
            $created = LoyaltyReward::firstOrCreate(
                [
                    'name' => $reward['name'],
                    'points_required' => $reward['points_required'],
                ],
                $reward
            );

            if ($created->wasRecentlyCreated) {
                $count++;
            }
        }

        $this->command->info("🎁 Loyalty Rewards: {$count} new rewards created (total: " . count($rewards) . ")");
    }
}
