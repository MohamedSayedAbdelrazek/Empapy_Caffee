<?php

namespace Database\Seeders;

use App\Models\LoyaltyReward;
use App\Models\LoyaltyTier;
use App\Models\PointRule;
use Illuminate\Database\Seeder;

class LoyaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Loyalty Tiers
        $this->seedTiers();

        // Seed Point Rules
        $this->seedRules();

        // Seed Default Rewards
        $this->seedRewards();

        $this->command->info('✅ Loyalty system seeded successfully!');
    }

    /**
     * Seed default loyalty tiers
     */
    protected function seedTiers(): void
    {
        $tiers = LoyaltyTier::getDefaultTiers();

        foreach ($tiers as $tierData) {
            LoyaltyTier::updateOrCreate(
                ['slug' => $tierData['slug']],
                $tierData
            );
        }

        $this->command->info('   - Created ' . count($tiers) . ' loyalty tiers');
    }

    /**
     * Seed default point rules
     */
    protected function seedRules(): void
    {
        $rules = PointRule::getDefaultRules();

        foreach ($rules as $ruleData) {
            PointRule::updateOrCreate(
                ['slug' => $ruleData['slug']],
                $ruleData
            );
        }

        $this->command->info('   - Created ' . count($rules) . ' point rules');
    }

    /**
     * Seed default rewards
     */
    protected function seedRewards(): void
    {
        $rewards = LoyaltyReward::getDefaultRewards();

        foreach ($rewards as $rewardData) {
            LoyaltyReward::updateOrCreate(
                ['name' => $rewardData['name']],
                $rewardData
            );
        }

        $this->command->info('   - Created ' . count($rewards) . ' rewards');
    }
}
