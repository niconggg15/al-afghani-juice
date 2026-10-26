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

        $images = [
            'Jus Alpukat' => 'images/1793005642_Kawaii Strawberry & Avocado Drink Stickers _ Cute Beverage Art.jpg',
            'Jus Buah Naga' => 'images/1793006403_Gemini_Generated_Image_38p5eq38p5eq38p5.jpg',
            'Jus Mangga' => 'images/1793005807_Cute Mango Smoothie Sticker - Vibrant Summer Vibes.jpg',
            'Jus Strawberry' => 'images/1793006486_Gemini_Generated_Image_asm7spasm7spasm7.jpg',
            'Jus Apel' => 'images/1793006725_Gemini_Generated_Image_sv5wfsv5wfsv5wfs.jpg',
            'Jus Jambu' => 'images/1793006770_Gemini_Generated_Image_b1d0ubb1d0ubb1d0.jpg',
            'Jus Jeruk' => 'images/1793006827_Gemini_Generated_Image_btwioabtwioabtwi.jpg',
            'Jus Melon' => 'images/1793006905_Gemini_Generated_Image_zh1honzh1honzh1h.jpg',
            'Jus Nanas' => 'images/1793008432_Gemini_Generated_Image_uwh982uwh982uwh9.jpg',
            'Jus Semangka' => 'images/1793007857_Gemini_Generated_Image_j1cpigj1cpigj1cp.jpg',
            'Capcin' => 'images/1793008136_Gemini_Generated_Image_19svgs19svgs19sv.jpg',
            'Es Jeruk' => 'images/1793006667_Gemini_Generated_Image_qv9bocqv9bocqv9b.jpg',
            'Pop Ice Alpukat' => 'images/1793007960_Gemini_Generated_Image_vl6t6evl6t6evl6t.jpg',
            'Pop Ice Anggur' => 'images/1793008050_Gemini_Generated_Image_eyyvp6eyyvp6eyyv.jpg',
            'Pop Ice Cappuccino' => 'images/1793006558_Gemini_Generated_Image_k2m8wok2m8wok2m8.jpg',
            'Pop Ice Coklat' => 'images/1793009017_Gemini_Generated_Image_oww3showw3showw3.jpg',
            'Pop Ice Durian' => 'storage/products/pop-ice-durian.svg',
            'Pop Ice Mangga' => 'images/1793008603_Gemini_Generated_Image_12sg3l12sg3l12sg.jpg',
            'Pop Ice Melon' => 'images/1793007756_Gemini_Generated_Image_zh1honzh1honzh1h.jpg',
            'Pop Ice Permen Karet' => 'images/1793008877_Gemini_Generated_Image_x2dcfcx2dcfcx2dc.jpg',
            'Pop Ice Strawberry' => 'images/1793009140_Gemini_Generated_Image_fqirfxfqirfxfqir.jpg',
            'Pop Ice Taro' => 'images/1793009232_Gemini_Generated_Image_o8mutto8mutto8mu.jpg',
            'Pop Ice Vanilla Blue' => 'images/1793009314_Gemini_Generated_Image_xdvt1jxdvt1jxdvt.jpg',
        ];

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
            Product::updateOrCreate(
                ['name' => $p['name']],
                array_merge($p, ['stock' => 20, 'image' => $images[$p['name']]])
            );
        }
    }
}