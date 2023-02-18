<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run() :void
    {
        // Создаем по 20 записей
        BrandFactory::new()->count(20)->create();

        CategoryFactory::new()->count(10)
            ->has(Product::factory(rand(5,15)))
            ->create();

    }
}
