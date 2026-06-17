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
        // Arabic names only (no _ar columns)
        $name = fake()->unique()->randomElement([
            'خلطات الإسبريسو',
            'قهوة أحادية المصدر',
            'قهوة منكّهة',
            'قهوة منزوعة الكافيين',
            'القهوة الباردة',
        ]);

        $descriptions = [
            'خلطات الإسبريسو' => 'خلطات إسبريسو فاخرة للحصول على أفضل كوب قهوة',
            'قهوة أحادية المصدر' => 'حبوب قهوة مختارة بعناية من أفضل مناطق العالم',
            'قهوة منكّهة' => 'أنواع قهوة منكّهة لذيذة ومميزة',
            'قهوة منزوعة الكافيين' => 'قهوة لذيذة بدون كافيين',
            'القهوة الباردة' => 'قهوة باردة منعشة وناعمة',
        ];

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'description' => $descriptions[$name] ?? 'اكتشف مجموعتنا الفاخرة من القهوة',
            'image' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=400&h=400&fit=crop',
            'is_active' => true,
        ];
    }
}
