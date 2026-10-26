<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Jus Buah',
                'description' => 'Aneka jus buah segar pilihan',
                'sort_order' => 1,
                'color' => '#FF6B6B',
            ],
            [
                'name' => 'Es Jeruk',
                'description' => 'Minuman jeruk segar',
                'sort_order' => 2,
                'color' => '#FFA500',
            ],
            [
                'name' => 'Capcin',
                'description' => 'Minuman cappuccino cincau',
                'sort_order' => 3,
                'color' => '#8B4513',
            ],
            [
                'name' => 'Pop Ice',
                'description' => 'Minuman Pop Ice aneka rasa',
                'sort_order' => 4,
                'color' => '#9370DB',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                array_merge($category, ['is_active' => true])
            );
        }
    }
}