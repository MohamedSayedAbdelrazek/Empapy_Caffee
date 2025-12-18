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
     * Coffee product data for realistic seeding
     */
    protected array $coffeeProducts = [
        [
            'name' => 'Ethiopian Yirgacheffe',
            'name_ar' => 'إثيوبي يرغاشيفي',
            'origin' => 'Ethiopia',
            'origin_ar' => 'إثيوبيا',
            'roast' => 'light',
            'description' => 'Bright and fruity with notes of blueberry and citrus',
            'description_ar' => 'قهوة مشرقة وفاكهية مع نكهات التوت الأزرق والحمضيات',
        ],
        [
            'name' => 'Colombian Supremo',
            'name_ar' => 'كولومبي سوبريمو',
            'origin' => 'Colombia',
            'origin_ar' => 'كولومبيا',
            'roast' => 'medium',
            'description' => 'Smooth and balanced with caramel sweetness',
            'description_ar' => 'قهوة ناعمة ومتوازنة مع حلاوة الكراميل',
        ],
        [
            'name' => 'Sumatra Mandheling',
            'name_ar' => 'سومطرة ماندلينج',
            'origin' => 'Indonesia',
            'origin_ar' => 'إندونيسيا',
            'roast' => 'dark',
            'description' => 'Full-bodied with earthy, herbal notes',
            'description_ar' => 'قهوة كاملة القوام مع نكهات ترابية وعشبية',
        ],
        [
            'name' => 'Brazilian Santos',
            'name_ar' => 'برازيلي سانتوس',
            'origin' => 'Brazil',
            'origin_ar' => 'البرازيل',
            'roast' => 'medium',
            'description' => 'Nutty and chocolatey with low acidity',
            'description_ar' => 'نكهة المكسرات والشوكولاتة مع حموضة منخفضة',
        ],
        [
            'name' => 'Kenyan AA',
            'name_ar' => 'كيني AA',
            'origin' => 'Kenya',
            'origin_ar' => 'كينيا',
            'roast' => 'medium',
            'description' => 'Wine-like acidity with blackcurrant notes',
            'description_ar' => 'حموضة تشبه النبيذ مع نكهات الكشمش الأسود',
        ],
        [
            'name' => 'Jamaican Blue Mountain',
            'name_ar' => 'جبل جامايكا الأزرق',
            'origin' => 'Jamaica',
            'origin_ar' => 'جامايكا',
            'roast' => 'medium',
            'description' => 'Exceptionally smooth with no bitterness',
            'description_ar' => 'قهوة ناعمة استثنائياً بدون مرارة',
        ],
        [
            'name' => 'Costa Rican Tarrazu',
            'name_ar' => 'كوستاريكا تارازو',
            'origin' => 'Costa Rica',
            'origin_ar' => 'كوستاريكا',
            'roast' => 'light',
            'description' => 'Bright acidity with honey and citrus notes',
            'description_ar' => 'حموضة مشرقة مع نكهات العسل والحمضيات',
        ],
        [
            'name' => 'Guatemalan Antigua',
            'name_ar' => 'غواتيمالا أنتيغوا',
            'origin' => 'Guatemala',
            'origin_ar' => 'غواتيمالا',
            'roast' => 'medium',
            'description' => 'Complex with chocolate and spice notes',
            'description_ar' => 'معقدة مع نكهات الشوكولاتة والتوابل',
        ],
        [
            'name' => 'Italian Espresso Blend',
            'name_ar' => 'خلطة إسبريسو إيطالية',
            'origin' => 'Blend',
            'origin_ar' => 'مزيج',
            'roast' => 'dark',
            'description' => 'Rich and intense, perfect for espresso',
            'description_ar' => 'غنية ومكثفة، مثالية للإسبريسو',
        ],
        [
            'name' => 'French Roast',
            'name_ar' => 'تحميص فرنسي',
            'origin' => 'Blend',
            'origin_ar' => 'مزيج',
            'roast' => 'dark',
            'description' => 'Smoky and bold with dark chocolate notes',
            'description_ar' => 'مدخنة وجريئة مع نكهات الشوكولاتة الداكنة',
        ],
        [
            'name' => 'Hazelnut Cream',
            'name_ar' => 'كريمة البندق',
            'origin' => 'Flavored',
            'origin_ar' => 'منكّهة',
            'roast' => 'medium',
            'description' => 'Smooth coffee with rich hazelnut flavor',
            'description_ar' => 'قهوة ناعمة مع نكهة البندق الغنية',
        ],
        [
            'name' => 'Vanilla Caramel',
            'name_ar' => 'فانيليا كراميل',
            'origin' => 'Flavored',
            'origin_ar' => 'منكّهة',
            'roast' => 'medium',
            'description' => 'Sweet vanilla and caramel notes',
            'description_ar' => 'نكهات الفانيليا والكراميل الحلوة',
        ],
        [
            'name' => 'Swiss Water Decaf',
            'name_ar' => 'منزوعة الكافيين السويسرية',
            'origin' => 'Colombia',
            'origin_ar' => 'كولومبيا',
            'roast' => 'medium',
            'description' => 'Full flavor without the caffeine',
            'description_ar' => 'نكهة كاملة بدون كافيين',
        ],
        [
            'name' => 'Cold Brew Concentrate',
            'name_ar' => 'مركز القهوة الباردة',
            'origin' => 'Blend',
            'origin_ar' => 'مزيج',
            'roast' => 'medium',
            'description' => 'Smooth and naturally sweet cold brew',
            'description_ar' => 'قهوة باردة ناعمة وحلوة طبيعياً',
        ],
        [
            'name' => 'Mocha Java',
            'name_ar' => 'موكا جافا',
            'origin' => 'Yemen/Indonesia',
            'origin_ar' => 'اليمن/إندونيسيا',
            'roast' => 'medium',
            'description' => 'The original coffee blend with complex flavors',
            'description_ar' => 'المزيج الأصلي للقهوة مع نكهات معقدة',
        ],
        [
            'name' => 'Vietnamese Robusta',
            'name_ar' => 'روبوستا فيتنامية',
            'origin' => 'Vietnam',
            'origin_ar' => 'فيتنام',
            'roast' => 'dark',
            'description' => 'Strong and bold, perfect for iced coffee',
            'description_ar' => 'قوية وجريئة، مثالية للقهوة المثلجة',
        ],
        [
            'name' => 'Panama Geisha',
            'name_ar' => 'بنما جيشا',
            'origin' => 'Panama',
            'origin_ar' => 'بنما',
            'roast' => 'light',
            'description' => 'Rare and exotic with jasmine and bergamot',
            'description_ar' => 'نادرة وغريبة مع الياسمين والبرغموت',
        ],
        [
            'name' => 'Espresso Double Shot',
            'name_ar' => 'إسبريسو مزدوج',
            'origin' => 'Blend',
            'origin_ar' => 'مزيج',
            'roast' => 'dark',
            'description' => 'Extra strong espresso for coffee lovers',
            'description_ar' => 'إسبريسو قوي جداً لعشاق القهوة',
        ],
        [
            'name' => 'Turkish Coffee Blend',
            'name_ar' => 'مزيج القهوة التركية',
            'origin' => 'Blend',
            'origin_ar' => 'مزيج',
            'roast' => 'medium',
            'description' => 'Finely ground for traditional Turkish preparation',
            'description_ar' => 'مطحونة ناعماً للتحضير التركي التقليدي',
        ],
        [
            'name' => 'Arabic Cardamom Coffee',
            'name_ar' => 'قهوة عربية بالهيل',
            'origin' => 'Arabia',
            'origin_ar' => 'عربية',
            'roast' => 'light',
            'description' => 'Traditional Arabic coffee with cardamom',
            'description_ar' => 'قهوة عربية تقليدية مع الهيل',
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
            'name_ar' => $coffee['name_ar'],
            'slug' => Str::slug($coffee['name']) . '-' . fake()->unique()->numberBetween(1, 9999),
            'description' => $coffee['description'],
            'description_ar' => $coffee['description_ar'],
            'price' => $price,
            'sale_price' => fake()->boolean(30) ? round($price * 0.85, 2) : null,
            'image' => fake()->randomElement($this->coffeeImages),
            'gallery' => null,
            'stock' => fake()->numberBetween(10, 100),
            'weight' => fake()->randomElement(['250g', '500g', '1kg']),
            'roast_level' => $coffee['roast'],
            'origin' => $coffee['origin'],
            'origin_ar' => $coffee['origin_ar'],
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
