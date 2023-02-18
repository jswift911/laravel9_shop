<?php

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up() : void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('thumbnail')->nullable();

            //Только положительный int, по умолчанию 0
            $table->unsignedInteger('price')->default(0);

            //constrained - внешний ключ в БД для контроля, если будем обновлять связанные(используемые)
            // записи, нам этого БД не позволит. Также если удалим бренд, то у всех товаров проставится null.
            $table->foreignIdFor(Brand::class)
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Category::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Product::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }


    public function down() :void
    {
        // Если локальная разработка, удалять таблицу
        if (app()->isLocal()) {
            // Таблицу со связанными данными удаляем первой
            Schema::dropIfExists('category_product');
            Schema::dropIfExists('products');
        }
    }
};
