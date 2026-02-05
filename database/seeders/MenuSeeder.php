<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder adds menu categories and products with variants from Empapy Caffe menu.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Menu Seeder with Variants...');

        // First, clean up old products from this seeder (optional - comment out if you want to keep old data)
        $this->command->warn('🗑️ Cleaning up old menu items...');

        $defaultImage = '/uploads/products/default.png';

        // Define categories with their products and options
        $menu = [
            // ============================================
            // 1. المخبوزات - Bakeries
            // ============================================
            [
                'category' => [
                    'name' => 'المخبوزات',
                    'slug' => 'bakeries',
                    'description' => 'كرواسون ودونتس طازجة يومياً بنكهات متنوعة ولذيذة',
                    'image' => 'uploads/categories/bakeries.png',
                ],
                'products' => [
                    [
                        'name' => 'كرواسون',
                        'description' => 'كرواسون طازج بالزبدة الفرنسية بنكهات متنوعة',
                        'price' => 20, // Base price (cheapest option)
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Flavor',
                                'name_ar' => 'النكهة',
                                'values' => [
                                    ['value' => 'سادة', 'price' => 20, 'default' => true],
                                    ['value' => 'جبنة', 'price' => 25],
                                    ['value' => 'أوريو', 'price' => 30],
                                    ['value' => 'نوتيلا', 'price' => 30],
                                    ['value' => 'لوتس', 'price' => 35],
                                    ['value' => 'بستاشيو', 'price' => 40],
                                    ['value' => 'إمبابي', 'price' => 50],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'دونتس',
                        'description' => 'دونتس طازجة بنكهات متنوعة',
                        'price' => 30,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Flavor',
                                'name_ar' => 'النكهة',
                                'values' => [
                                    ['value' => 'شوكليت', 'price' => 30, 'default' => true],
                                    ['value' => 'لوتس', 'price' => 35],
                                    ['value' => 'فراولة', 'price' => 30],
                                    ['value' => 'بلوبيري', 'price' => 30],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // ============================================
            // 2. مشروبات القهوة - Coffee Drinks
            // ============================================
            [
                'category' => [
                    'name' => 'مشروبات القهوة',
                    'slug' => 'coffee-drinks',
                    'description' => 'قهوة تركي، فرنساوي، وإسبريسو بأجود أنواع البن',
                    'image' => 'uploads/categories/coffee_drinks.png',
                ],
                'products' => [
                    [
                        'name' => 'قهوة تركي',
                        'description' => 'قهوة تركي أصلية بأحجام مختلفة',
                        'price' => 15,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_WEIGHT,
                                'name' => 'Size',
                                'name_ar' => 'الحجم',
                                'values' => [
                                    ['value' => 'صغير (S)', 'price' => 15, 'default' => true],
                                    ['value' => 'كبير (D)', 'price' => 25],
                                    ['value' => 'مخصوص صغير', 'price' => 25],
                                    ['value' => 'مخصوص كبير', 'price' => 35],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'قهوة فرنساوي',
                        'description' => 'قهوة فرنسية كلاسيكية',
                        'price' => 30,
                    ],
                    [
                        'name' => 'قهوة بندق',
                        'description' => 'قهوة بنكهة البندق المميزة',
                        'price' => 30,
                    ],
                    [
                        'name' => 'إسبريسو',
                        'description' => 'إسبريسو مركز',
                        'price' => 30,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_WEIGHT,
                                'name' => 'Size',
                                'name_ar' => 'الحجم',
                                'values' => [
                                    ['value' => 'سينجل (S)', 'price' => 30, 'default' => true],
                                    ['value' => 'دبل (D)', 'price' => 50],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'ماكياتو',
                        'description' => 'إسبريسو مع رغوة الحليب',
                        'price' => 50,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Type',
                                'name_ar' => 'النوع',
                                'values' => [
                                    ['value' => 'إسبريسو ماكياتو', 'price' => 50, 'default' => true],
                                    ['value' => 'كراميل ماكياتو', 'price' => 60],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'لاتيه',
                        'description' => 'إسبريسو مع الحليب المخفوق',
                        'price' => 45,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Type',
                                'name_ar' => 'النوع',
                                'values' => [
                                    ['value' => 'لاتيه عادي', 'price' => 45, 'default' => true],
                                    ['value' => 'سبانش لاتيه', 'price' => 60],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'موكا',
                        'description' => 'إسبريسو مع الشوكولاتة والحليب',
                        'price' => 55,
                    ],
                    [
                        'name' => 'كابتشينو',
                        'description' => 'إسبريسو مع رغوة الحليب الكثيفة',
                        'price' => 55,
                    ],
                    [
                        'name' => 'فلات وايت',
                        'description' => 'إسبريسو مزدوج مع حليب ناعم',
                        'price' => 65,
                    ],
                    [
                        'name' => 'كورتو',
                        'description' => 'إسبريسو مركز قصير',
                        'price' => 60,
                    ],
                    [
                        'name' => 'نوتيلا كوفي',
                        'description' => 'قهوة بالنوتيلا اللذيذة',
                        'price' => 50,
                    ],
                ],
            ],

            // ============================================
            // 3. الحلويات - Desserts
            // ============================================
            [
                'category' => [
                    'name' => 'الحلويات',
                    'slug' => 'desserts',
                    'description' => 'وافل وبراونيز وبوكسات حلويات مميزة',
                    'image' => 'uploads/categories/desserts.png',
                ],
                'products' => [
                    [
                        'name' => 'وافل',
                        'description' => 'وافل بلجيكي بنكهات متنوعة',
                        'price' => 40,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Flavor',
                                'name_ar' => 'النكهة',
                                'values' => [
                                    ['value' => 'نوتيلا', 'price' => 40, 'default' => true],
                                    ['value' => 'لوتس', 'price' => 45],
                                    ['value' => 'بستاشيو', 'price' => 60],
                                    ['value' => '2x1', 'price' => 50],
                                    ['value' => 'إمبابي', 'price' => 60],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'براونيز',
                        'description' => 'براونيز شوكولاتة غنية',
                        'price' => 45,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Type',
                                'name_ar' => 'النوع',
                                'values' => [
                                    ['value' => 'براونيز عادي', 'price' => 45, 'default' => true],
                                    ['value' => 'براونيز آيس كريم', 'price' => 55],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'بوكس إمبابي',
                        'description' => 'بوكس حلويات إمبابي الخاص',
                        'price' => 80,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Edition',
                                'name_ar' => 'الإصدار',
                                'values' => [
                                    ['value' => 'بي إس جي (PSG)', 'price' => 80, 'default' => true],
                                    ['value' => 'ريال مدريد', 'price' => 90],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // ============================================
            // 4. القهوة الباردة - Ice Coffee
            // ============================================
            [
                'category' => [
                    'name' => 'القهوة الباردة',
                    'slug' => 'ice-coffee',
                    'description' => 'مشروبات القهوة المثلجة والفرابيه المنعشة',
                    'image' => 'uploads/categories/ice_coffee.png',
                ],
                'products' => [
                    [
                        'name' => 'آيس كوفي',
                        'description' => 'قهوة مثلجة منعشة بأنواع مختلفة',
                        'price' => 45,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Type',
                                'name_ar' => 'النوع',
                                'values' => [
                                    ['value' => 'آيس لاتيه', 'price' => 45, 'default' => true],
                                    ['value' => 'آيس كابتشينو', 'price' => 60],
                                    ['value' => 'آيس موكا', 'price' => 60],
                                    ['value' => 'آيس سبانش لاتيه', 'price' => 60],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'فرابيه',
                        'description' => 'فرابيه قهوة منعش بنكهات متنوعة',
                        'price' => 50,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Flavor',
                                'name_ar' => 'النكهة',
                                'values' => [
                                    ['value' => 'فرابيه عادي', 'price' => 50, 'default' => true],
                                    ['value' => 'شوكليت', 'price' => 55],
                                    ['value' => 'لوتس', 'price' => 60],
                                    ['value' => 'كراميل', 'price' => 55],
                                    ['value' => 'جوز هند', 'price' => 55],
                                    ['value' => 'موكا', 'price' => 60],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // ============================================
            // 5. الزبادي - Yogurt
            // ============================================
            [
                'category' => [
                    'name' => 'الزبادي',
                    'slug' => 'yogurt',
                    'description' => 'زبادي طبيعي طازج بنكهات متنوعة',
                    'image' => 'uploads/categories/yogurt.png',
                ],
                'products' => [
                    [
                        'name' => 'زبادي',
                        'description' => 'زبادي طبيعي طازج بإضافات متنوعة',
                        'price' => 45,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Flavor',
                                'name_ar' => 'النكهة',
                                'values' => [
                                    ['value' => 'بالعسل', 'price' => 45, 'default' => true],
                                    ['value' => 'فليفر', 'price' => 55],
                                    ['value' => 'فواكه', 'price' => 65],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // ============================================
            // 6. المشروبات الساخنة - Hot Drinks
            // ============================================
            [
                'category' => [
                    'name' => 'المشروبات الساخنة',
                    'slug' => 'hot-drinks',
                    'description' => 'شاي، سحلب، هوت شوكليت ومشروبات دافئة متنوعة',
                    'image' => 'uploads/categories/hot_drinks.png',
                ],
                'products' => [
                    [
                        'name' => 'شاي',
                        'description' => 'شاي بأنواع مختلفة',
                        'price' => 10,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Type',
                                'name_ar' => 'النوع',
                                'values' => [
                                    ['value' => 'شاي عادي', 'price' => 10, 'default' => true],
                                    ['value' => 'شاي فتلة', 'price' => 15],
                                    ['value' => 'شاي بالحليب', 'price' => 30],
                                    ['value' => 'شاي أخضر', 'price' => 20],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'أعشاب',
                        'description' => 'مشروبات أعشاب طبيعية',
                        'price' => 15,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Type',
                                'name_ar' => 'النوع',
                                'values' => [
                                    ['value' => 'ينسون', 'price' => 15, 'default' => true],
                                    ['value' => 'نعناع', 'price' => 15],
                                    ['value' => 'كركديه', 'price' => 15],
                                    ['value' => 'كوكتيل أعشاب', 'price' => 35],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'قرفة / جنزبيل',
                        'description' => 'مشروب القرفة أو الجنزبيل الدافئ',
                        'price' => 20,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Type',
                                'name_ar' => 'النوع',
                                'values' => [
                                    ['value' => 'عادي', 'price' => 20, 'default' => true],
                                    ['value' => 'بالحليب', 'price' => 30],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'ليمون ساخن',
                        'description' => 'ليمون ساخن منعش',
                        'price' => 20,
                    ],
                    [
                        'name' => 'هوت سيدر',
                        'description' => 'عصير التفاح الساخن',
                        'price' => 40,
                    ],
                    [
                        'name' => 'كوفي بريك',
                        'description' => 'قهوة سريعة للاستراحة',
                        'price' => 20,
                    ],
                    [
                        'name' => 'نسكافيه',
                        'description' => 'نسكافيه كلاسيكي',
                        'price' => 40,
                    ],
                    [
                        'name' => 'سحلب',
                        'description' => 'سحلب ساخن تقليدي بنكهات متنوعة',
                        'price' => 40,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Flavor',
                                'name_ar' => 'النكهة',
                                'values' => [
                                    ['value' => 'سحلب عادي', 'price' => 40, 'default' => true],
                                    ['value' => 'مكسرات', 'price' => 50],
                                    ['value' => 'أوريو', 'price' => 55],
                                    ['value' => 'لوتس', 'price' => 55],
                                    ['value' => 'فواكه', 'price' => 60],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'هوت شوكليت',
                        'description' => 'شوكولاتة ساخنة غنية',
                        'price' => 40,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Type',
                                'name_ar' => 'النوع',
                                'values' => [
                                    ['value' => 'هوت شوكليت عادي', 'price' => 40, 'default' => true],
                                    ['value' => 'مارشميلو', 'price' => 50],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'هوت أوريو',
                        'description' => 'مشروب ساخن بنكهة الأوريو',
                        'price' => 45,
                    ],
                    [
                        'name' => 'هوت لوتس',
                        'description' => 'مشروب ساخن بنكهة اللوتس',
                        'price' => 45,
                    ],
                ],
            ],
            [
                'category' => [
                    'name' => 'سموزي',
                    'slug' => 'smoothies',
                    'description' => 'مشروبات سموزي مثلجة ومنعشة',
                    'image' => 'uploads/categories/smoothies.png',
                ],
                'products' => [
                    [
                        'name' => 'سموزي',
                        'description' => 'سموزي منعش بنكهات طبيعية متنوعة',
                        'price' => 50,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Flavor',
                                'name_ar' => 'النكهة',
                                'values' => [
                                    ['value' => 'ليمون', 'price' => 50, 'default' => true],
                                    ['value' => 'فراولة', 'price' => 55],
                                    ['value' => 'برتقال', 'price' => 55],
                                    ['value' => 'ليمون نعناع', 'price' => 55],
                                    ['value' => 'كيوي', 'price' => 55],
                                    ['value' => 'خوخ', 'price' => 55],
                                    ['value' => 'اناناس', 'price' => 55],
                                    ['value' => 'بلوبيري', 'price' => 55],
                                    ['value' => 'مانجو', 'price' => 60],
                                    ['value' => 'بطيخ', 'price' => 60],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'category' => [
                    'name' => 'ميلك شيك',
                    'slug' => 'milkshakes',
                    'description' => 'ميلك شيك غني وكريمي',
                    'image' => 'uploads/categories/milkshakes.png',
                ],
                'products' => [
                    [
                        'name' => 'ميلك شيك',
                        'description' => 'ميلك شيك غني وكريمي بنكهات مميزة',
                        'price' => 65,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Flavor',
                                'name_ar' => 'النكهة',
                                'values' => [
                                    ['value' => 'فانيلا', 'price' => 65, 'default' => true],
                                    ['value' => 'شوكليت', 'price' => 65],
                                    ['value' => 'كراميل', 'price' => 65],
                                    ['value' => 'مانجو', 'price' => 65],
                                    ['value' => 'فراولة', 'price' => 65],
                                    ['value' => 'بلوبيري', 'price' => 65],
                                    ['value' => 'لوتس', 'price' => 70],
                                    ['value' => 'أوريو', 'price' => 70],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'category' => [
                    'name' => 'الموكتيلات',
                    'slug' => 'mocktails',
                    'description' => 'كوكتيلات صودا منعشة بدون كحول',
                    'image' => 'uploads/categories/mocktails.png',
                ],
                'products' => [
                    ['name' => 'موهيتو', 'description' => 'ليمون، نعناع، صودا', 'price' => 50],
                    ['name' => 'موهيتو فليفر', 'description' => 'موهيتو بنكهات مختلفة', 'price' => 60],
                    ['name' => 'بلو هاواي', 'description' => 'بلوكوراساو، شرائح ليمون، نعناع فريش، عصير اناناس، صودا', 'price' => 60],
                    ['name' => 'صن شاين', 'description' => 'رمان، شرائح ليمون، نعناع فريش، صودا', 'price' => 55],
                    ['name' => 'سكوتش منت', 'description' => 'نعناع، شرائح ليمون، نعناع فريش، صودا', 'price' => 55],
                    ['name' => 'بلو سكاي', 'description' => 'بلو كوراساو، شرائح ليمون، نعناع فريش، جوز هند، صودا', 'price' => 60],
                    ['name' => 'ريد اليكتريك', 'description' => 'فراولة، شرائح ليمون، نعناع فريش، بلو كوراساو، صودا', 'price' => 70],
                ],
            ],
            [
                'category' => [
                    'name' => 'عصائر',
                    'slug' => 'juices',
                    'description' => 'عصائر طبيعية طازجة',
                    'image' => 'uploads/categories/juices.png',
                ],
                'products' => [
                    [
                        'name' => 'عصائر فريش',
                        'description' => 'عصائر طبيعية طازجة',
                        'price' => 45,
                        'options' => [
                            [
                                'type' => ProductOption::TYPE_FLAVOR,
                                'name' => 'Flavor',
                                'name_ar' => 'النكهة',
                                'values' => [
                                    ['value' => 'ليمون', 'price' => 40, 'default' => true],
                                    ['value' => 'برتقال', 'price' => 45],
                                    ['value' => 'فراولة', 'price' => 45],
                                    ['value' => 'جوافة', 'price' => 45],
                                    ['value' => 'مانجو', 'price' => 50],
                                    ['value' => 'ليمون نعناع', 'price' => 50],
                                    ['value' => 'بطيخ', 'price' => 50],
                                    ['value' => 'موز بالحليب', 'price' => 50],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'category' => [
                    'name' => 'كوكتيلات',
                    'slug' => 'cocktails',
                    'description' => 'كوكتيلات فواكه مميزة',
                    'image' => 'uploads/categories/cocktails.png',
                ],
                'products' => [
                    ['name' => 'فلوريدا', 'description' => 'مانجو، فراولة، جوافة', 'price' => 70],
                    ['name' => 'مانجو كيوي', 'description' => 'كوكتيل مانجو وكيوي', 'price' => 70],
                    ['name' => 'مانجو خوخ', 'description' => 'كوكتيل مانجو وخوخ', 'price' => 65],
                    ['name' => 'بينا كولادا', 'description' => 'ايس كريم فانيلا، عصير اناناس، جوز هند', 'price' => 70],
                    ['name' => 'بنانا شوكليت', 'description' => 'كوكتيل موز وشوكولاتة', 'price' => 65],
                    ['name' => 'فراولة كيوي', 'description' => 'كوكتيل فراولة وكيوي', 'price' => 65],
                    ['name' => 'إمبابي كوكتيل', 'description' => 'كوكتيل إمبابي المميز', 'price' => 80],
                ],
            ],
            [
                'category' => [
                    'name' => 'المشروبات الباردة',
                    'slug' => 'cold-drinks',
                    'description' => 'مشروبات غازية ومياه',
                    'image' => 'uploads/categories/cold_drinks.png',
                ],
                'products' => [
                    ['name' => 'في كولا', 'description' => 'مشروب غازي كولا', 'price' => 25],
                    ['name' => 'تويست', 'description' => 'مشروب غازي ليمون', 'price' => 25],
                    ['name' => 'ماكسي', 'description' => 'مشروب غازي', 'price' => 15],
                    ['name' => 'مياة معدنية', 'description' => 'مياة معدنية صغيرة', 'price' => 10],
                ],
            ],
        ];

        $totalProducts = 0;
        $totalOptions = 0;
        $totalOptionValues = 0;

        foreach ($menu as $section) {
            // Create or update category
            $category = Category::updateOrCreate(
                ['slug' => $section['category']['slug']],
                array_merge($section['category'], ['is_active' => true])
            );

            // CLEANUP: Delete old products in this category that match the CURRENT SEEDER logic but are not in the new list.
            // This prevents duplicates when product names change (e.g. from "Mango Smoothie" to just "Smoothie").
            $newNames = array_column($section['products'], 'name');
            Product::where('category_id', $category->id)->whereNotIn('name', $newNames)->delete();

            $this->command->info("✅ Category: {$category->name}");

            // Create products
            foreach ($section['products'] as $productData) {
                $options = $productData['options'] ?? [];
                unset($productData['options']);

                // Check if has options that set the price (flavor/weight)
                $hasFlavorOptions = false;
                $hasWeightOptions = false;
                foreach ($options as $opt) {
                    if ($opt['type'] === ProductOption::TYPE_FLAVOR) $hasFlavorOptions = true;
                    if ($opt['type'] === ProductOption::TYPE_WEIGHT) $hasWeightOptions = true;
                }

                $product = Product::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'name' => $productData['name'],
                    ],
                    [
                        'slug' => Str::slug($productData['name']),
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'image' => $defaultImage,
                        'is_active' => true,
                        'is_featured' => false,
                        'has_weight_options' => $hasWeightOptions,
                        'has_roast_options' => false,
                        'has_additive_options' => false,
                        'has_flavor_options' => $hasFlavorOptions,
                    ]
                );

                $totalProducts++;

                // Create options and values
                if (!empty($options)) {
                    // Delete old options for this product first
                    ProductOption::where('product_id', $product->id)->delete();

                    foreach ($options as $sortOrder => $optionData) {
                        $option = ProductOption::create([
                            'product_id' => $product->id,
                            'type' => $optionData['type'],
                            'name' => $optionData['name'],
                            'name_ar' => $optionData['name_ar'],
                            'sort_order' => $sortOrder,
                        ]);

                        $totalOptions++;

                        foreach ($optionData['values'] as $valueSortOrder => $valueData) {
                            ProductOptionValue::create([
                                'product_option_id' => $option->id,
                                'value' => $valueData['value'],
                                'price_modifier' => $valueData['price'],
                                'is_default' => $valueData['default'] ?? false,
                                'sort_order' => $valueSortOrder,
                            ]);

                            $totalOptionValues++;
                        }

                        $this->command->line("   → {$product->name} [{$option->name_ar}]: " . count($optionData['values']) . " خيارات");
                    }
                } else {
                    $this->command->line("   → {$product->name} - {$product->price} ج.م");
                }
            }
        }

        $this->command->newLine();
        $this->command->info("🎉 Done!");
        $this->command->table(
            ['Item', 'Count'],
            [
                ['Categories', count($menu)],
                ['Products', $totalProducts],
                ['Options', $totalOptions],
                ['Option Values', $totalOptionValues],
            ]
        );
    }
}
