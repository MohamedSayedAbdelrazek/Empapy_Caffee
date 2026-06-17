<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User. Role is not mass-assignable (SEC-07), so set it
        // explicitly via direct assignment before saving.
        $admin = new User([
            'name' => 'أحمد صلاح',
            'email' => 'admin@empapy.com',
            'password' => bcrypt('ahmedsalah123'),
            'phone' => '+201151579225',
        ]);
        $admin->role = 'admin';
        $admin->save();

        
        // Create Categories (Arabic only - no name_ar column)
        $categories = [
            [
                'name' => 'خلطات الإسبريسو',
                'slug' => 'espresso-blends',
                'description' => 'خلطات إسبريسو فاخرة للحصول على أفضل كوب قهوة',
                'image' => 'https://images.unsplash.com/photo-1610889556528-9a770e32642f?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'قهوة أحادية المصدر',
                'slug' => 'single-origin',
                'description' => 'حبوب قهوة مختارة بعناية من أفضل مناطق العالم',
                'image' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'قهوة منكّهة',
                'slug' => 'flavored-coffee',
                'description' => 'أنواع قهوة منكّهة لذيذة ومميزة',
                'image' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'القهوة الباردة',
                'slug' => 'cold-brew',
                'description' => 'قهوة باردة منعشة وناعمة',
                'image' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=400&h=400&fit=crop',
            ],
            [
                'name' => 'القهوة العربية',
                'slug' => 'arabic-coffee',
                'description' => 'قهوة عربية تقليدية بالهيل',
                'image' => 'https://images.unsplash.com/photo-1578374173705-969cbe6f2d6b?w=400&h=400&fit=crop',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Products with Options/Variants (Arabic only - no _ar columns)
        $products = [
            // Product 1: Ethiopian Yirgacheffe with full options
            [
                'category_id' => 2,
                'name' => 'إثيوبي يرغاشيفي',
                'slug' => 'ethiopian-yirgacheffe',
                'description' => 'قهوة مشرقة وفاكهية مع نكهات التوت الأزرق والحمضيات والزهور. من مسقط رأس القهوة.',
                'price' => 150.00,
                'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400&h=400&fit=crop',
                'is_featured' => true,
                'has_weight_options' => true,
                'has_roast_options' => true,
                'has_additive_options' => true,
                'options' => [
                    ['type' => 'weight', 'name' => 'الوزن', 'values' => [
                        ['value' => '125 جم', 'price_modifier' => 150.00, 'is_default' => true],
                        ['value' => '250 جم', 'price_modifier' => 280.00, 'is_default' => false],
                        ['value' => '500 جم', 'price_modifier' => 520.00, 'is_default' => false],
                        ['value' => '1 كجم', 'price_modifier' => 980.00, 'is_default' => false],
                    ]],
                    ['type' => 'roast', 'name' => 'درجة التحميص', 'values' => [
                        ['value' => 'فاتح', 'price_modifier' => 0, 'is_default' => true],
                        ['value' => 'متوسط', 'price_modifier' => 0, 'is_default' => false],
                        ['value' => 'متوسط داكن', 'price_modifier' => 0, 'is_default' => false],
                    ]],
                    ['type' => 'additive', 'name' => 'الإضافات', 'values' => [
                        ['value' => 'عادية', 'price_modifier' => 0, 'is_default' => true],
                        ['value' => 'بالهيل', 'price_modifier' => 15.00, 'is_default' => false],
                        ['value' => 'هيل زيادة', 'price_modifier' => 25.00, 'is_default' => false],
                    ]],
                ],
            ],

            // Product 2: Colombian Supremo with weight only
            [
                'category_id' => 2,
                'name' => 'كولومبي سوبريمو',
                'slug' => 'colombian-supremo',
                'description' => 'قهوة ناعمة ومتوازنة مع حلاوة الكراميل ونكهات المكسرات.',
                'price' => 120.00,
                'sale_price' => 99.00,
                'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&h=400&fit=crop',
                'is_featured' => true,
                'has_weight_options' => true,
                'options' => [
                    ['type' => 'weight', 'name' => 'الوزن', 'values' => [
                        ['value' => '250 جم', 'price_modifier' => 220.00, 'is_default' => true],
                        ['value' => '500 جم', 'price_modifier' => 380.00, 'is_default' => false],
                        ['value' => '1 كجم', 'price_modifier' => 720.00, 'is_default' => false],
                    ]],
                ],
            ],

            // Product 3: Arabic Cardamom Coffee with roast and additive options
            [
                'category_id' => 5,
                'name' => 'قهوة عربية بالهيل',
                'slug' => 'arabic-cardamom-coffee',
                'description' => 'قهوة عربية تقليدية مع الهيل الفاخر. طعم الضيافة العربية.',
                'price' => 300.00,
                'image' => 'https://images.unsplash.com/photo-1578374173705-969cbe6f2d6b?w=400&h=400&fit=crop',
                'is_featured' => true,
                'has_weight_options' => true,
                'has_roast_options' => true,
                'has_additive_options' => true,
                'options' => [
                    ['type' => 'weight', 'name' => 'الوزن', 'values' => [
                        ['value' => '250 جم', 'price_modifier' => 300.00, 'is_default' => true],
                        ['value' => '500 جم', 'price_modifier' => 550.00, 'is_default' => false],
                    ]],
                    ['type' => 'roast', 'name' => 'درجة التحميص', 'values' => [
                        ['value' => 'فاتح', 'price_modifier' => 0, 'is_default' => true],
                        ['value' => 'متوسط', 'price_modifier' => 0, 'is_default' => false],
                    ]],
                    ['type' => 'additive', 'name' => 'مستوى الهيل', 'values' => [
                        ['value' => 'هيل عادي', 'price_modifier' => 0, 'is_default' => true],
                        ['value' => 'هيل زيادة', 'price_modifier' => 20.00, 'is_default' => false],
                        ['value' => 'مع الزعفران', 'price_modifier' => 50.00, 'is_default' => false],
                        ['value' => 'مع المستكة', 'price_modifier' => 30.00, 'is_default' => false],
                    ]],
                ],
            ],

            // Product 4: Italian Espresso - Weight + Roast
            [
                'category_id' => 1,
                'name' => 'خلطة إسبريسو إيطالية',
                'slug' => 'italian-espresso-blend',
                'description' => 'إسبريسو إيطالي غني ومكثف مع نكهات الشوكولاتة الداكنة والكراميل.',
                'price' => 350.00,
                'sale_price' => 299.00,
                'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=400&h=400&fit=crop',
                'is_featured' => true,
                'has_weight_options' => true,
                'has_roast_options' => true,
                'options' => [
                    ['type' => 'weight', 'name' => 'الوزن', 'values' => [
                        ['value' => '250 جم', 'price_modifier' => 350.00, 'is_default' => true],
                        ['value' => '500 جم', 'price_modifier' => 650.00, 'is_default' => false],
                    ]],
                    ['type' => 'roast', 'name' => 'درجة التحميص', 'values' => [
                        ['value' => 'متوسط', 'price_modifier' => 0, 'is_default' => false],
                        ['value' => 'داكن', 'price_modifier' => 0, 'is_default' => true],
                        ['value' => 'داكن جداً', 'price_modifier' => 10.00, 'is_default' => false],
                    ]],
                ],
            ],

            // Product 5: Brazilian Santos - No options (regular product)
            [
                'category_id' => 2,
                'name' => 'برازيلي سانتوس',
                'slug' => 'brazilian-santos',
                'description' => 'نكهة المكسرات والشوكولاتة مع حموضة منخفضة. مثالية للشرب اليومي.',
                'price' => 290.00,
                'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=400&h=400&fit=crop',
                'weight' => '500g',
                'roast_level' => 'medium',
            ],

            // Product 6: Turkish Coffee - Weight + Additive
            [
                'category_id' => 5,
                'name' => 'مزيج القهوة التركية',
                'slug' => 'turkish-coffee-blend',
                'description' => 'مطحونة ناعماً للتحضير التركي التقليدي. غنية وعطرية.',
                'price' => 240.00,
                'image' => 'https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=400&h=400&fit=crop',
                'has_weight_options' => true,
                'has_additive_options' => true,
                'options' => [
                    ['type' => 'weight', 'name' => 'الوزن', 'values' => [
                        ['value' => '200 جم', 'price_modifier' => 240.00, 'is_default' => true],
                        ['value' => '500 جم', 'price_modifier' => 550.00, 'is_default' => false],
                    ]],
                    ['type' => 'additive', 'name' => 'التوابل', 'values' => [
                        ['value' => 'عادي', 'price_modifier' => 0, 'is_default' => true],
                        ['value' => 'مع الهيل', 'price_modifier' => 10.00, 'is_default' => false],
                        ['value' => 'مع المستكة', 'price_modifier' => 15.00, 'is_default' => false],
                    ]],
                ],
            ],
        ];

        foreach ($products as $productData) {
            // Extract options if they exist
            $options = $productData['options'] ?? [];
            unset($productData['options']);

            // Create the product
            $product = Product::create($productData);

            // Create options if they exist
            if (!empty($options)) {
                foreach ($options as $optionData) {
                    $values = $optionData['values'];
                    unset($optionData['values']);

                    // Create the option
                    $option = $product->options()->create($optionData);
                    // Create option values
                    foreach ($values as $valueData) {
                        $option->values()->create($valueData);
                    }
                }
            }
        }

        // Seed Permissions
        $this->call(PermissionSeeder::class);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📧 Admin: admin@empapy.com / password');
        $this->command->info('📧 Customer: customer@empapy.com / password');
    }
}
