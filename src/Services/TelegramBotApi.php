<?php

declare(strict_types=1);

namespace Services;

use Illuminate\Support\Facades\Http;
use Services\Exceptions\TelegramBotApiException;
use Services\Telegram\TelegramBotApiContract;
use Services\Telegram\TelegramBotApiFake;
use Throwable;

final class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';

    public static function fake(): TelegramBotApiFake
    {
        return app()->instance(
            TelegramBotApiContract::class,
            new TelegramBotApiFake()
        );
    }

    public static function sendMessage(string $token, int $chatId, string $text) : bool
    {
        try {
            $response = Http::get(self::HOST . $token . '/sendMessage', [
                'chat_id' => $chatId,
                'text' => $text,
            ])->throw()->json();

            return $response['ok'] ?? false;

            //Throwable включает в себя и Exception (пользовательские ошибки) и Error (синтаксические ошибки)
        } catch (Throwable $e) {
            // Отправка ошибок в sentry, telescope и подобные
            report(new TelegramBotApiException($e->getMessage()));
            return false;
        }
    }
}
