<?php

namespace App\Console\Commands;

use App\Models\LoyaltyPoint;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetBirthdayBonuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loyalty:reset-birthday-bonuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset birthday bonus flags for new year (run on January 1st)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("Resetting birthday bonus flags for new year...");

        $updated = LoyaltyPoint::where('birthday_bonus_claimed', true)
            ->update(['birthday_bonus_claimed' => false]);

        $this->info("Reset {$updated} users' birthday bonus flags.");
        Log::info("Birthday bonus flags reset for {$updated} users for new year");

        return Command::SUCCESS;
    }
}
