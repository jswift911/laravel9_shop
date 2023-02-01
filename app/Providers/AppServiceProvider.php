<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{

    public function register() : void
    {
        //
    }

    public function boot() : void
    {
        //ShouldBeStrict - выдает больше сведений об ошибках (например если нет переменной в blade-шаблоне)
        // аналог use strict в js
        Model::shouldBeStrict(!app()->isProduction());

        if (app()->isProduction()) {

            //Если долгий коннект (не сами запросы, а именно коннект), то логируем через телеграм бот. Если использовать
            // данный вариант через очереди, то будет бесконечный цикл, поэтому второй вариант listen лучше.
            // 1-ый вариант
//            DB::whenQueryingForLongerThan(CarbonInterval::seconds(5), function (Connection $connection, QueryExecuted $event) {
//                logger()
//                    ->channel('telegram')
//                    ->debug('whenQueryingForLongerThan: ' . $connection->totalQueryDuration());
//
//            });

            // Логи именно запросов
            // 2-ой вариант
            DB::listen(function ($query) {
            //Включить логи всех запросов (в т.ч. терминал) $query->sql;
            //Включить логи всех биндингов (в т.ч. терминал) $query->bindings;
            //Включить логи времени выполнения запросов (в т.ч. терминал) $query->time;

                if($query->time > 1000) {
                    logger()
                        ->channel('telegram')
                        ->debug('Query longer than 1s: ' . $query->sql, $query->bindings);

                }

            //dump($query->time);
            });

            // Большая типизация через Carbon, чем через примитив (например: int 20)
            app(Kernel::class)->whenRequestLifecycleIsLongerThan(
                CarbonInterval::seconds(4),
                function () {
                    logger()
                        ->channel('telegram')
                        ->debug('whenRequestLifecycleIsLongerThan: '. request()->url());

                }
            );
        }
    }
}
