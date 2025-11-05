<?php

namespace App\Middlewares;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ChatType;

class GroupMiddleware
{
    public function __invoke(Nutgram $bot, $next): void
    {
        // checking if it's a group chat
        $chat = $bot->message()->chat->type ?? $bot->callbackQuery()->message->chat->type ?? null;

        if ($chat === ChatType::GROUP || $chat === ChatType::SUPERGROUP) {
            $next($bot);
        }
    }
}