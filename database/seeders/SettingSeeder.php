<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Shipping Settings
            [
                'key' => 'shipping_free_threshold',
                'value' => '500',
                'type' => 'number',
                'group' => 'shipping',
            ],
            [
                'key' => 'shipping_fee',
                'value' => '50',
                'type' => 'number',
                'group' => 'shipping',
            ],

            // General Settings
            [
                'key' => 'site_name',
                'value' => 'إمبابي كافيه',
                'type' => 'string',
                'group' => 'general',
            ],
            [
                'key' => 'currency',
                'value' => 'EGP',
                'type' => 'string',
                'group' => 'general',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
