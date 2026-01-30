<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorates = [
            ['name' => 'القاهرة', 'fee' => 50],
            ['name' => 'الجيزة', 'fee' => 50],
            ['name' => 'الإسكندرية', 'fee' => 60],
            ['name' => 'القليوبية', 'fee' => 55],
            ['name' => 'الدقهلية', 'fee' => 70],
            ['name' => 'الشرقية', 'fee' => 70],
            ['name' => 'الغربية', 'fee' => 70],
            ['name' => 'المنوفية', 'fee' => 70],
            ['name' => 'البحيرة', 'fee' => 70],
            ['name' => 'كفر الشيخ', 'fee' => 75],
            ['name' => 'دمياط', 'fee' => 75],
            ['name' => 'بورسعيد', 'fee' => 75],
            ['name' => 'الإسماعيلية', 'fee' => 75],
            ['name' => 'السويس', 'fee' => 75],
            ['name' => 'الفيوم', 'fee' => 80],
            ['name' => 'بني سويف', 'fee' => 80],
            ['name' => 'المنيا', 'fee' => 90],
            ['name' => 'أسيوط', 'fee' => 90],
            ['name' => 'سوهاج', 'fee' => 100],
            ['name' => 'قنا', 'fee' => 100],
            ['name' => 'الأقصر', 'fee' => 100],
            ['name' => 'أسوان', 'fee' => 100],
            ['name' => 'البحر الأحمر', 'fee' => 120],
            ['name' => 'الوادي الجديد', 'fee' => 150],
            ['name' => 'مطروح', 'fee' => 120],
            ['name' => 'شمال سيناء', 'fee' => 150],
            ['name' => 'جنوب سيناء', 'fee' => 150],
        ];

        foreach ($governorates as $zone) {
            \App\Models\ShippingZone::updateOrCreate(
                ['name' => $zone['name']],
                ['fee' => $zone['fee'], 'is_active' => true]
            );
        }
    }
}
