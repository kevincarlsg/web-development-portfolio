<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Blog;
use App\Models\Category;
use App\Models\MetaTag;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpia solo los blogs
        Blog::truncate();

        // Limpia autores para crearlos de nuevo
        Author::truncate();

        /**
         * AUTORES FIJOS
         */
        $authors = collect([
            Author::create([
                'name' => 'Kevin Sánchez',
                'email' => 'kevin@example.com',
                'position' => 'Redactor',
                'bio' => 'Autor principal del sitio.',
                'img' => 'default.jpg',
                'social1' => '#',
                'social2' => '#',
            ]),
            Author::create([
                'name' => 'David González',
                'email' => 'david@example.com',
                'position' => 'Editor',
                'bio' => 'Redactor de contenido especializado.',
                'img' => 'default.jpg',
                'social1' => '#',
                'social2' => '#',
            ]),
            Author::create([
                'name' => 'Ámbar Carbajal',
                'email' => 'ambar@example.com',
                'position' => 'Colaboradora',
                'bio' => 'Creadora de contenido y colaboradora frecuente.',
                'img' => 'default.jpg',
                'social1' => '#',
                'social2' => '#',
            ]),
        ]);

        /**
         * 4 AUTORES ADICIONALES CREADOS POR FACTORY
         */
        $extraAuthors = Author::factory()->count(4)->create();

        // Unimos ambos grupos
        $allAuthors = $authors->merge($extraAuthors);

        /**
         * CATEGORÍAS
         */
        $categories = Category::where('type', 'blog')->select('id', 'name')->get();

        /**
         * GENERACIÓN DE 6 BLOGS
         */
        Blog::factory()->count(6)
            ->has(MetaTag::factory())
            ->state(function () use ($allAuthors, $categories) {

                $category = $categories->random();

                return [
                    'title' => $category->name . " " . fake()->sentence(),
                    'author_id' => $allAuthors->random()->id,
                    'category_id' => $category->id,
                ];
            })
            ->create();
    }
}
