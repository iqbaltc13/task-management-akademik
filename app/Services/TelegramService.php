<?php

namespace App\Services;

use Telegram\Bot\Api;

class TelegramService
{
    protected $telegram;
    protected $chatId;

    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
        $this->chatId = config('telegram.chat_id');
    }

    public function sendFile($filePath, $caption = '📄 Here is your file!')
    {
        return $this->telegram->sendDocument([
            'chat_id' => $this->chatId,
            'document' => fopen($filePath, 'r'),
            'caption' => $caption,
        ]);
    }
}
