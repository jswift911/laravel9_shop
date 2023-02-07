<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    // Установка значения поля на момент создания объекта модели
    protected static function bootHasSlug()
    {
        //Событие перед созданием -> если нет слага, то генерируем
        static::creating(function (Model $item) {
            $item->slug = $item->slug
                ?? str($item->{self::slugFrom()})
                    ->append(time())
                    ->slug();
        });
    }

    public static function slugFrom() : string
    {
        return 'title';
    }
}
