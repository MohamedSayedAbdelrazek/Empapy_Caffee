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
        // Create Admin User
        User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@empapy.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '+20 100 123 4567',
        ]);

        // Create Test Customer
        User::create([
            'name' => 'أحمد محمد',
            'email' => 'customer@empapy.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '+20 100 987 6543',
            'address' => '15 شارع التحرير، وسط البلد',
            'city' => 'القاهرة',
            'governorate' => 'القاهرة',
        ]);

        // Create Categories
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

        // Create Products with Options/Variants
        $products = [
            // Product 1: Ethiopian Yirgacheffe with full options
            [
                'category_id' => 2,
                'name' => 'إثيوبي يرغاشيفي',
                'slug' => 'ethiopian-yirgacheffe',
                'description' => 'قهوة مشرقة وفاكهية مع نكهات التوت الأزرق والحمضيات والزهور. من مسقط رأس القهوة.',
                'price' => 150.00,
                'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400&h=400&fit=crop',
                'stock' => 9999,
                'is_featured' => true,
                'has_weight_options' => true,
                'has_roast_options' => true,
                'has_additive_options' => true,
                'options' => [
                    // Weight options (absolute prices)
                    ['type' => 'weight', 'name' => 'Weight', 'name_ar' => 'الوزن', 'values' => [
                        ['value' => '125g', 'value_ar' => '125 جم', 'price_modifier' => 150.00, 'is_default' => true],
                        ['value' => '250g', 'value_ar' => '250 جم', 'price_modifier' => 280.00, 'is_default' => false],
                        ['value' => '500g', 'value_ar' => '500 جم', 'price_modifier' => 520.00, 'is_default' => false],
                        ['value' => '1kg', 'value_ar' => '1 كجم', 'price_modifier' => 980.00, 'is_default' => false],
                    ]],
                    // Roast options (modifiers)
                    ['type' => 'roast', 'name' => 'Roast Level', 'name_ar' => 'درجة التحميص', 'values' => [
                        ['value' => 'Light', 'value_ar' => 'فاتح', 'price_modifier' => 0, 'is_default' => true],
                        ['value' => 'Medium', 'value_ar' => 'متوسط', 'price_modifier' => 0, 'is_default' => false],
                        ['value' => 'Medium-Dark', 'value_ar' => 'متوسط داكن', 'price_modifier' => 0, 'is_default' => false],
                    ]],
                    // Additive options (modifiers)
                    ['type' => 'additive', 'name' => 'Additions', 'name_ar' => 'الإضافات', 'values' => [
                        ['value' => 'Plain', 'value_ar' => 'عادية', 'price_modifier' => 0, 'is_default' => true],
                        ['value' => 'With Cardamom', 'value_ar' => 'بالهيل', 'price_modifier' => 15.00, 'is_default' => false],
                        ['value' => 'Extra Cardamom', 'value_ar' => 'هيل زيادة', 'price_modifier' => 25.00, 'is_default' => false],
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
                'stock' => 9999,
                'is_featured' => true,
                'has_weight_options' => true,
                'options' => [
                    ['type' => 'weight', 'name' => 'Weight', 'name_ar' => 'الوزن', 'values' => [
                        ['value' => '250g', 'value_ar' => '250 جم', 'price_modifier' => 220.00, 'is_default' => true],
                        ['value' => '500g', 'value_ar' => '500 جم', 'price_modifier' => 380.00, 'is_default' => false],
                        ['value' => '1kg', 'value_ar' => '1 كجم', 'price_modifier' => 720.00, 'is_default' => false],
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
                'stock' => 9999,
                'is_featured' => true,
                'has_weight_options' => true,
                'has_roast_options' => true,
                'has_additive_options' => true,
                'options' => [
                    ['type' => 'weight', 'name' => 'Weight', 'name_ar' => 'الوزن', 'values' => [
                        ['value' => '250g', 'value_ar' => '250 جم', 'price_modifier' => 300.00, 'is_default' => true],
                        ['value' => '500g', 'value_ar' => '500 جم', 'price_modifier' => 550.00, 'is_default' => false],
                    ]],
                    ['type' => 'roast', 'name' => 'Roast Level', 'name_ar' => 'درجة التحميص', 'values' => [
                        ['value' => 'Light', 'value_ar' => 'فاتح', 'price_modifier' => 0, 'is_default' => true],
                        ['value' => 'Medium', 'value_ar' => 'متوسط', 'price_modifier' => 0, 'is_default' => false],
                    ]],
                    ['type' => 'additive', 'name' => 'Cardamom Level', 'name_ar' => 'مستوى الهيل', 'values' => [
                        ['value' => 'Regular Cardamom', 'value_ar' => 'هيل عادي', 'price_modifier' => 0, 'is_default' => true],
                        ['value' => 'Extra Cardamom', 'value_ar' => 'هيل زيادة', 'price_modifier' => 20.00, 'is_default' => false],
                        ['value' => 'With Saffron', 'value_ar' => 'مع الزعفران', 'price_modifier' => 50.00, 'is_default' => false],
                        ['value' => 'With Mastic', 'value_ar' => 'مع المستكة', 'price_modifier' => 30.00, 'is_default' => false],
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
                'stock' => 9999,
                'is_featured' => true,
                'has_weight_options' => true,
                'has_roast_options' => true,
                'options' => [
                    ['type' => 'weight', 'name' => 'Weight', 'name_ar' => 'الوزن', 'values' => [
                        ['value' => '250g', 'value_ar' => '250 جم', 'price_modifier' => 350.00, 'is_default' => true],
                        ['value' => '500g', 'value_ar' => '500 جم', 'price_modifier' => 650.00, 'is_default' => false],
                    ]],
                    ['type' => 'roast', 'name' => 'Roast Level', 'name_ar' => 'درجة التحميص', 'values' => [
                        ['value' => 'Medium', 'value_ar' => 'متوسط', 'price_modifier' => 0, 'is_default' => false],
                        ['value' => 'Dark', 'value_ar' => 'داكن', 'price_modifier' => 0, 'is_default' => true],
                        ['value' => 'Extra Dark', 'value_ar' => 'داكن جداً', 'price_modifier' => 10.00, 'is_default' => false],
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
                'stock' => 9999,
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
                'stock' => 9999,
                'has_weight_options' => true,
                'has_additive_options' => true,
                'options' => [
                    ['type' => 'weight', 'name' => 'Weight', 'name_ar' => 'الوزن', 'values' => [
                        ['value' => '200g', 'value_ar' => '200 جم', 'price_modifier' => 240.00, 'is_default' => true],
                        ['value' => '500g', 'value_ar' => '500 جم', 'price_modifier' => 550.00, 'is_default' => false],
                    ]],
                    ['type' => 'additive', 'name' => 'Spices', 'name_ar' => 'التوابل', 'values' => [
                        ['value' => 'Plain', 'value_ar' => 'عادي', 'price_modifier' => 0, 'is_default' => true],
                        ['value' => 'With Cardamom', 'value_ar' => 'مع الهيل', 'price_modifier' => 10.00, 'is_default' => false],
                        ['value' => 'With Mastic', 'value_ar' => 'مع المستكة', 'price_modifier' => 15.00, 'is_default' => false],
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

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📧 Admin: admin@empapy.com / password');
        $this->command->info('📧 Customer: customer@empapy.com / password');
    }
}
