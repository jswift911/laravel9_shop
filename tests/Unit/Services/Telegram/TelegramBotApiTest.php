<?php

namespace Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\TelegramBotApi;
use Tests\TestCase;

class TelegramBotApiTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_send_message_success() : void
    {
        //Метод для тестирования реальных Http запросов, не фейковых
        //Http::allowStrayRequests();

        //Метод для тестирования фейковых Http запросов
        Http::fake([
            //Иммитация успешного запроса
            TelegramBotApi::HOST . '*' => Http::response(['ok' => true]),
        ]);

        $result = TelegramBotApi::sendMessage('',1,'Testing');

        $this->assertTrue($result);
    }
}
