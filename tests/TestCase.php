<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    //Установки для всех тестов
    protected function setUp(): void
    {
        parent::setUp();

        //Предотвращение любых случайных исходящих HTTP-запросов при тестировании
        Http::preventStrayRequests();
    }
}
