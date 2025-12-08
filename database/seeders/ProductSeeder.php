<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {

        $sepakBola = Category::where('name', 'Sepak Bola')->first();
        $basket = Category::where('name', 'Basket')->first();
        $badminton = Category::where('name', 'Badminton')->first();

        $products = [
            // Basket
            ['name' => 'Jersey Basket Lakers - Home Edition', 'category_id' => $basket->id, 'image' => 'basket1.png', 'price' => 290000, 'discount_price' => 220000],
            ['name' => 'Sepatu Basket Jordan Zoom Elevate', 'category_id' => $basket->id, 'image' => 'basket2.png', 'price' => 899000],
            ['name' => 'Bola Basket Spalding Street NBA Size 7', 'category_id' => $basket->id, 'image' => 'basket3.png', 'price' => 450000],
            ['name' => 'Headband + Wristband Nike Set', 'category_id' => $basket->id, 'image' => 'basket4.png', 'price' => 129000],
            ['name' => 'Tas Basket Under Armour Duffel XL', 'category_id' => $basket->id, 'image' => 'basket5.png', 'price' => 579000],

            // Sepak Bola
            ['name' => 'Sepatu Bola Nike Mercurial Vapor 15', 'category_id' => $sepakBola->id, 'image' => 'sepakbola1.png', 'price' => 1890000, 'discount_price' => 1820000],
            ['name' => 'Sarung Tangan Kiper Adidas Predator Pro', 'category_id' => $sepakBola->id, 'image' => 'sepakbola2.png', 'price' => 1299000],
            ['name' => 'Bola Adidas Al Rihla FIFA World Cup Size 5', 'category_id' => $sepakBola->id, 'image' => 'sepakbola3.png', 'price' => 749000],
            ['name' => 'Jersey Timnas Indonesia Home 2024', 'category_id' => $sepakBola->id, 'image' => 'sepakbola4.png', 'price' => 499000],
            ['name' => 'Tas Puma TeamGOAL 23 Duffel Bag', 'category_id' => $sepakBola->id, 'image' => 'sepakbola5.png', 'price' => 429000],

            // Badminton
            ['name' => 'Raket Badminton Yonex Astrox 99 Pro', 'category_id' => $badminton->id, 'image' => 'badminton1.png', 'price' => 2499000],
            ['name' => 'Sepatu Badminton Yonex Power Cushion 65Z3', 'category_id' => $badminton->id, 'image' => 'badminton2.png', 'price' => 1450000],
            ['name' => 'Kaos Badminton Lining AAPM387', 'category_id' => $badminton->id, 'image' => 'badminton3.png', 'price' => 399000],
            ['name' => 'Shuttlecock Yonex AS-50 (Isi 12)', 'category_id' => $badminton->id, 'image' => 'badminton4.png', 'price' => 560000],
            ['name' => 'Tas Badminton Lining ABJT079-1 (6 Raket)', 'category_id' => $badminton->id, 'image' => 'badminton5.png', 'price' => 730000],
        ];

        // Tentukan index produk yang akan ditandai
        $dealIndex = 14; // misalnya "Tas Raket Yonex"
        $bestSellerIndexes = [1, 2, 5, 6, 7, 9, 10, 11, 13, 14]; // total 10 produk

        foreach ($products as $i => $product) {
            $isDeal = $i === $dealIndex;

            Product::create(array_merge([
                'is_new' => true,
                'is_best_seller' => in_array($i, $bestSellerIndexes),
                'is_deal' => $isDeal,
                'deal_end_date' => $isDeal ? now()->addDays(7) : null
            ], $product));
        }
    }
}
