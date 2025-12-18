<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Espresso Blends',
            'Single Origin',
            'Flavored Coffee',
            'Decaf Coffee',
            'Cold Brew',
        ]);

        $nameAr = match ($name) {
            'Espresso Blends' => 'خلطات الإسبريسو',
            'Single Origin' => 'قهوة أحادية المصدر',
            'Flavored Coffee' => 'قهوة منكّهة',
            'Decaf Coffee' => 'قهوة منزوعة الكافيين',
            'Cold Brew' => 'القهوة الباردة',
            default => $name,
        };

        return [
            'name' => $name,
            'name_ar' => $nameAr,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'description_ar' => 'اكتشف مجموعتنا الفاخرة من ' . $nameAr . ' المصنوعة بعناية فائقة من أجود حبوب البن المختارة.',
            'image' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=400&h=400&fit=crop',
            'is_active' => true,
        ];
    }
}
