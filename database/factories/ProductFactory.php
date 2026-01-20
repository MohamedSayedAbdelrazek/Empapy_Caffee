<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Coffee product data for realistic seeding (Arabic only - no _ar columns)
     */
    protected array $coffeeProducts = [
        [
            'name' => 'إثيوبي يرغاشيفي',
            'origin' => 'إثيوبيا',
            'roast' => 'light',
            'description' => 'قهوة مشرقة وفاكهية مع نكهات التوت الأزرق والحمضيات',
        ],
        [
            'name' => 'كولومبي سوبريمو',
            'origin' => 'كولومبيا',
            'roast' => 'medium',
            'description' => 'قهوة ناعمة ومتوازنة مع حلاوة الكراميل',
        ],
        [
            'name' => 'سومطرة ماندلينج',
            'origin' => 'إندونيسيا',
            'roast' => 'dark',
            'description' => 'قهوة كاملة القوام مع نكهات ترابية وعشبية',
        ],
        [
            'name' => 'برازيلي سانتوس',
            'origin' => 'البرازيل',
            'roast' => 'medium',
            'description' => 'نكهة المكسرات والشوكولاتة مع حموضة منخفضة',
        ],
        [
            'name' => 'كيني AA',
            'origin' => 'كينيا',
            'roast' => 'medium',
            'description' => 'حموضة تشبه النبيذ مع نكهات الكشمش الأسود',
        ],
        [
            'name' => 'جبل جامايكا الأزرق',
            'origin' => 'جامايكا',
            'roast' => 'medium',
            'description' => 'قهوة ناعمة استثنائياً بدون مرارة',
        ],
        [
            'name' => 'كوستاريكا تارازو',
            'origin' => 'كوستاريكا',
            'roast' => 'light',
            'description' => 'حموضة مشرقة مع نكهات العسل والحمضيات',
        ],
        [
            'name' => 'غواتيمالا أنتيغوا',
            'origin' => 'غواتيمالا',
            'roast' => 'medium',
            'description' => 'معقدة مع نكهات الشوكولاتة والتوابل',
        ],
        [
            'name' => 'خلطة إسبريسو إيطالية',
            'origin' => 'مزيج',
            'roast' => 'dark',
            'description' => 'غنية ومكثفة، مثالية للإسبريسو',
        ],
        [
            'name' => 'تحميص فرنسي',
            'origin' => 'مزيج',
            'roast' => 'dark',
            'description' => 'مدخنة وجريئة مع نكهات الشوكولاتة الداكنة',
        ],
        [
            'name' => 'كريمة البندق',
            'origin' => 'منكّهة',
            'roast' => 'medium',
            'description' => 'قهوة ناعمة مع نكهة البندق الغنية',
        ],
        [
            'name' => 'فانيليا كراميل',
            'origin' => 'منكّهة',
            'roast' => 'medium',
            'description' => 'نكهات الفانيليا والكراميل الحلوة',
        ],
        [
            'name' => 'منزوعة الكافيين السويسرية',
            'origin' => 'كولومبيا',
            'roast' => 'medium',
            'description' => 'نكهة كاملة بدون كافيين',
        ],
        [
            'name' => 'مركز القهوة الباردة',
            'origin' => 'مزيج',
            'roast' => 'medium',
            'description' => 'قهوة باردة ناعمة وحلوة طبيعياً',
        ],
        [
            'name' => 'موكا جافا',
            'origin' => 'اليمن/إندونيسيا',
            'roast' => 'medium',
            'description' => 'المزيج الأصلي للقهوة مع نكهات معقدة',
        ],
        [
            'name' => 'روبوستا فيتنامية',
            'origin' => 'فيتنام',
            'roast' => 'dark',
            'description' => 'قوية وجريئة، مثالية للقهوة المثلجة',
        ],
        [
            'name' => 'بنما جيشا',
            'origin' => 'بنما',
            'roast' => 'light',
            'description' => 'نادرة وغريبة مع الياسمين والبرغموت',
        ],
        [
            'name' => 'إسبريسو مزدوج',
            'origin' => 'مزيج',
            'roast' => 'dark',
            'description' => 'إسبريسو قوي جداً لعشاق القهوة',
        ],
        [
            'name' => 'مزيج القهوة التركية',
            'origin' => 'مزيج',
            'roast' => 'medium',
            'description' => 'مطحونة ناعماً للتحضير التركي التقليدي',
        ],
        [
            'name' => 'قهوة عربية بالهيل',
            'origin' => 'عربية',
            'roast' => 'light',
            'description' => 'قهوة عربية تقليدية مع الهيل',
        ],
    ];

    /**
     * Coffee images from Unsplash
     */
    protected array $coffeeImages = [
        'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=400&h=400&fit=crop',
        'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400&h=400&fit=crop',
        'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=400&h=400&fit=crop',
        'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&h=400&fit=crop',
        'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=400&h=400&fit=crop',
        'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=400&h=400&fit=crop',
        'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400&h=400&fit=crop',
        'https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=400&h=400&fit=crop',
        'https://images.unsplash.com/photo-1504630083234-14187a9df0f5?w=400&h=400&fit=crop',
        'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=400&h=400&fit=crop',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $coffee = fake()->randomElement($this->coffeeProducts);
        $price = fake()->randomFloat(2, 150, 800);

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'name' => $coffee['name'],
            'slug' => Str::slug($coffee['name']) . '-' . fake()->unique()->numberBetween(1, 9999),
            'description' => $coffee['description'],
            'price' => $price,
            'sale_price' => fake()->boolean(30) ? round($price * 0.85, 2) : null,
            'image' => fake()->randomElement($this->coffeeImages),
            'gallery' => null,
            'weight' => fake()->randomElement(['250g', '500g', '1kg']),
            'roast_level' => $coffee['roast'],
            'origin' => $coffee['origin'],
            'is_featured' => fake()->boolean(20),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the product is on sale.
     */
    public function onSale(): static
    {
        return $this->state(fn(array $attributes) => [
            'sale_price' => $attributes['price'] * 0.8,
        ]);
    }
}
