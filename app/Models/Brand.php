<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'thumbnail',
    ];

    // Установка значения поля на момент создания объекта модели
    protected static function boot()
    {
        parent::boot();

        //Событие перед созданием -> если нет слага, то генерируем
        static::creating(function (Brand $brand) {
            $brand->slug = $brand->slug ?? str($brand->title)->slug();
        });
    }


    public function product() : HasMany
    {
        return $this->hasMany(Product::class);
    }
}
