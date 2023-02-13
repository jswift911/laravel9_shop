<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    //Установки для всех тестов
    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        //Предотвращение любых случайных исходящих HTTP-запросов при тестировании
        Http::preventStrayRequests();
    }
}
