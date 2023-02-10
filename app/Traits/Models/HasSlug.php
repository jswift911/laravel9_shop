<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    // Установка значения поля на момент создания объекта модели, генерация слага через timestamp
//    protected static function bootHasSlug()
//    {
//        static::creating(function (Model $item) {
//            $item->slug = $item->slug
//                ?? str($item->{self::slugFrom()})
//                    ->append(time())
//                    ->slug();
//        });
//    }

    protected static function bootHasSlug() : void
    {
        //Событие перед созданием -> если нет слага, то генерируем
        static::creating(function (Model $item) {
            $item->makeSlug();
        });
    }

    protected function makeSlug() : void
    {
            $slug = $this->slugUnique(
                str($this->{$this->slugFrom()})
                    ->slug()
                    ->value(),
            );

            $this->{$this->slugColumn()} = $this->{$this->slugColumn()} ?? $slug;
            //$this->{$this->slugColumn()} =  $slug;
    }

    protected function slugColumn() : string
    {
        return 'slug';
    }

    public static function slugFrom() : string
    {
        return 'title';
    }

    private function slugUnique(string $slug) :string
    {
        $originalSlug = $slug;
        $i = 0;

        while ($this->isSlugExists($slug)) {
            $i++;
            $slug = $originalSlug . '-' . $i;

        }

        return $slug;
    }

    private function isSlugExists(string $slug) : bool
    {
        $query = $this->newQuery()
            ->where(self::slugColumn(), $slug)
            ->where($this->getKeyName(),'!=',$this->getKey())
            ->withoutGlobalScopes();

        return $query->exists();
    }
}
