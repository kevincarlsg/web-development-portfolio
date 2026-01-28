<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use App\Models\Color;
use App\Models\Image;
use App\Models\MetaTag;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::truncate();

        Image::where('model_type', 'App\Models\Product')->delete();

        $categories = Category::pluck('id', 'name');
        $departments = Department::pluck('id', 'name');
        $colors = Color::pluck('id', 'name');

        // ==============================
        // 🔥 TOMAR SOLO 500 PRODUCTOS
        // ==============================
        $products = collect(Storage::json(DatabaseSeeder::getPathProductJson()))
                        ->take(500);

        // Si estás en testing, puedes seguir limitando a 20
        if (config('app.env') == 'testing') {
            $products = $products->take(20);
        }

        $products_array = [];
        $products_variant_array = [];
        $images_array = [];
        $meta_array = [];

        $product_count = 1;

        foreach ($products as $product) {

            $this->command->info($product_count . ' - ' . $product['ref']);

            $product_base = [
                'name' => $product['name'],
                'slug' => Str::slug($product['name']) . "-" . $product['id'],
                'entry' => $product['entry'],
                'description' => fake()->text(800),
                'max_quantity' => rand(1, 100),
                'featured' => boolval(rand(0, 1000)),
                'department_id' => $departments[$product['department']],
                'category_id' => $categories[$product['category']],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $products_array[] = [
                'id' => $product['id'],
                ...$product_base
            ];

            foreach ($product['variants'] as $variant) {

                $color_id = $colors[$variant['color']['name']];

                $time_fake = fake()->dateTime()->format('hi');
                $ref = str_pad($product['id'], 4, "0", STR_PAD_LEFT) . '-' . str_pad($time_fake, 3, "0", STR_PAD_LEFT);

                if (rand(0, 10)) {
                    $old_price = $product['price'];
                    $offer = fake()->randomElement([10, 20, 30, 40, 50]);
                    $price = $old_price - ($old_price * ($offer / 100));
                } else {
                    $old_price = null;
                    $offer = null;
                    $price = $product['price'];
                }

                $products_variant_array[] = [
                    ...$product_base,
                    'id' => $variant['id'],
                    'ref' => $ref,
                    'old_price' => $old_price,
                    'offer' => $offer,
                    'price' => $price,
                    'img' => $variant['img'],
                    'thumb' => $variant['thumb'],
                    'color_id' => $color_id,
                    'parent_id' => $product['id'],
                    'created_at' => fake()->dateTimeBetween('-2 days', 'now'),
                    'updated_at' => fake()->dateTimeBetween('-2 days', 'now'),
                ];

                foreach ($variant['images'] as $key => $image) {
                    $images_array[] = [
                        'img' => $image,
                        'title' => $product['name'],
                        'alt' => $product['name'],
                        'sort' => $key + 1,
                        'model_type' => 'App\Models\Product',
                        'model_id' => $variant['id'],
                    ];
                }

                $meta_array[] = [
                    'meta_title' => $product['name'],
                    'meta_description' => fake()->sentence(),
                    'model_type' => 'App\Models\Product',
                    'model_id' => $variant['id'],
                ];
            }

            // Inserciones por bloques
            if (count($products_variant_array) > 50) {
                Product::insert($products_array);
                Product::insert($products_variant_array);
                Image::insert($images_array);
                MetaTag::insert($meta_array);

                $products_array = [];
                $products_variant_array = [];
                $images_array = [];
                $meta_array = [];
            }

            $product_count++;
        }

        // Inserta los últimos registros
        Product::insert($products_array);
        Product::insert($products_variant_array);
        Image::insert($images_array);
        MetaTag::insert($meta_array);
    }
}
