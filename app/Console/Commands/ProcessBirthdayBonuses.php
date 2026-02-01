<?php

namespace App\Console\Commands;

use App\Models\LoyaltyPoint;
use App\Services\FirebaseNotificationService;
use App\Services\LoyaltyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessBirthdayBonuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loyalty:birthday-bonuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process birthday bonuses for users whose birthday is today';

    /**
     * Execute the console command.
     */
    public function handle(LoyaltyService $loyaltyService): int
    {
        $today = now()->format('m-d');

        $this->info("Processing birthday bonuses for date: {$today}");

        // Find users with birthday today who haven't received bonus this year
        $birthdayUsers = LoyaltyPoint::whereNotNull('birthday')
            ->whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$today])
            ->where('birthday_bonus_claimed', false)
            ->with('user')
            ->get();

        $processedCount = 0;

        foreach ($birthdayUsers as $loyalty) {
            if (!$loyalty->user) {
                continue;
            }

            $transaction = $loyaltyService->processBirthdayBonus($loyalty->user);

            if ($transaction) {
                // Mark birthday bonus as claimed
                $loyalty->update(['birthday_bonus_claimed' => true]);

                // Send birthday notification
                $this->sendBirthdayNotification($loyalty->user, $transaction->points);

                $processedCount++;
                $this->info("✓ Birthday bonus sent to: {$loyalty->user->name} ({$transaction->points} points)");
            }
        }

        $this->info("Completed! Processed {$processedCount} birthday bonuses.");

        return Command::SUCCESS;
    }

    /**
     * Send birthday notification to user
     */
    protected function sendBirthdayNotification($user, int $points): void
    {
        try {
            $firebaseService = app(FirebaseNotificationService::class);
            $firebaseService->sendToUsers(
                [$user->id],
                '🎂 عيد ميلاد سعيد!',
                "كل سنة وأنت طيب! حصلت على {$points} نقطة هدية عيد ميلاد من إمبابي كافيه 🎁",
                [
                    'type' => 'birthday_bonus',
                    'points' => (string) $points,
                    'click_action' => '/loyalty',
                ]
            );

            Log::info("Birthday notification sent to user {$user->id}: {$points} points");
        } catch (\Exception $e) {
            Log::warning('Failed to send birthday notification: ' . $e->getMessage());
        }
    }
}
