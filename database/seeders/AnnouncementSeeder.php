<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcements = [
            [
                'message_ar' => '🚚 توصيل مجاني للطلبات أكثر من 500 ج.م',
                'icon' => 'bi-star-fill',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'message_ar' => '🎁 خصم 15% على طلبك الأول - كود: WELCOME15',
                'icon' => 'bi-star-fill',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'message_ar' => '⭐ قهوة طازجة محمصة يومياً',
                'icon' => 'bi-star-fill',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'message_ar' => '⏰ توصيل سريع خلال 24 ساعة',
                'icon' => 'bi-star-fill',
                'is_active' => true,
                'order' => 4,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
