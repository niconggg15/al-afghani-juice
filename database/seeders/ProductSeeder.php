<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $jus = Category::where('name', 'Jus Buah')->first()?->id ?? 1;
        $jeruk = Category::where('name', 'Es Jeruk')->first()?->id ?? 2;
        $capcin = Category::where('name', 'Capcin')->first()?->id ?? 3;
        $popice = Category::where('name', 'Pop Ice')->first()?->id ?? 4;

        $products = [
            // Jus Buah
            ['name' => 'Jus Alpukat', 'category_id' => $jus, 'price' => 7000, 'description' => 'Jus alpukat segar dengan susu kental manis', 'is_featured' => 1],
            ['name' => 'Jus Buah Naga', 'category_id' => $jus, 'price' => 7000, 'description' => 'Jus buah naga segar', 'is_featured' => 1],
            ['name' => 'Jus Mangga', 'category_id' => $jus, 'price' => 7000, 'description' => 'Jus mangga harum manis segar', 'is_featured' => 1],
            ['name' => 'Jus Strawberry', 'category_id' => $jus, 'price' => 7000, 'description' => 'Jus strawberry manis segar', 'is_featured' => 1],
            ['name' => 'Jus Apel', 'category_id' => $jus, 'price' => 7000, 'description' => 'Jus apel segar manis', 'is_featured' => 0],
            ['name' => 'Jus Jambu', 'category_id' => $jus, 'price' => 7000, 'description' => 'Jus jambu merah segar', 'is_featured' => 0],
            ['name' => 'Jus Jeruk', 'category_id' => $jus, 'price' => 5000, 'description' => 'Jus jeruk peras segar', 'is_featured' => 0],
            ['name' => 'Jus Melon', 'category_id' => $jus, 'price' => 7000, 'description' => 'Jus melon segar manis', 'is_featured' => 0],
            ['name' => 'Jus Nanas', 'category_id' => $jus, 'price' => 7000, 'description' => 'Jus nanas segar asam manis', 'is_featured' => 0],
            ['name' => 'Jus Semangka', 'category_id' => $jus, 'price' => 7000, 'description' => 'Jus semangka manis segar', 'is_featured' => 0],

            // Capcin & Es Jeruk
            ['name' => 'Capcin', 'category_id' => $capcin, 'price' => 6000, 'description' => 'Capcin khas manis creamy', 'is_featured' => 0],
            ['name' => 'Es Jeruk', 'category_id' => $jeruk, 'price' => 5000, 'description' => 'Es jeruk peras segar manis', 'is_featured' => 0],

            // Pop Ice
            ['name' => 'Pop Ice Alpukat', 'category_id' => $popice, 'price' => 3000, 'description' => 'Pop ice rasa alpukat creamy', 'is_featured' => 0],
            ['name' => 'Pop Ice Anggur', 'category_id' => $popice, 'price' => 3000, 'description' => 'Pop ice rasa anggur segar', 'is_featured' => 0],
            ['name' => 'Pop Ice Cappuccino', 'category_id' => $popice, 'price' => 3000, 'description' => 'Pop ice rasa cappuccino aroma kopi', 'is_featured' => 0],
            ['name' => 'Pop Ice Coklat', 'category_id' => $popice, 'price' => 3000, 'description' => 'Pop ice rasa coklat kental', 'is_featured' => 0],
            ['name' => 'Pop Ice Durian', 'category_id' => $popice, 'price' => 3000, 'description' => 'Pop ice rasa durian kental', 'is_featured' => 0],
            ['name' => 'Pop Ice Mangga', 'category_id' => $popice, 'price' => 3000, 'description' => 'Pop ice rasa mangga harum', 'is_featured' => 0],
            ['name' => 'Pop Ice Melon', 'category_id' => $popice, 'price' => 3000, 'description' => 'Pop ice rasa melon segar', 'is_featured' => 0],
            ['name' => 'Pop Ice Permen Karet', 'category_id' => $popice, 'price' => 3000, 'description' => 'Pop ice rasa permen karet nostalgia', 'is_featured' => 0],
            ['name' => 'Pop Ice Strawberry', 'category_id' => $popice, 'price' => 3000, 'description' => 'Pop ice rasa strawberry manis', 'is_featured' => 0],
            ['name' => 'Pop Ice Taro', 'category_id' => $popice, 'price' => 3000, 'description' => 'Pop ice rasa taro ungu manis', 'is_featured' => 0],
            ['name' => 'Pop Ice Vanilla Blue', 'category_id' => $popice, 'price' => 3000, 'description' => 'Pop ice rasa vanilla biru khas', 'is_featured' => 0],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['name' => $p['name']], array_merge($p, ['stock' => 20]));
        }
    }
}