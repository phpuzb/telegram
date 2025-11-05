<?php

namespace App\Conversations;

use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class WelcomeToGroupConversation extends Conversation
{
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot): void
    {
        $text_group = <<<HTML
        <b>Salom!</b>
        
        Sizlarni bu guruhda ko'rib turganimizdan mamnunmiz.
        Bu guruh PHP dasturlash tiliga qaratilgan bo'lib, bu yerda
        ushbu til haqida gaplashish, savollar berish yoki o'z fikrlaringiz
        bilan bo'lishishingiz mumkin. 
        HTML;

        $bot->sendMessage(
            text: $text_group,
            parse_mode: ParseMode::HTML,
            reply_to_message_id: $bot->messageId()
        );

        // sending other info in private chat
        $text_private = <<<HTML
        <b>Hey bo'lajak PHPchi!</b>
        
        Guruhimizga qo'shilganingiz uchun tashakkur!
        Iltimos, guruh qoidalarini hurmat qiling va PHP dasturlash tilidan chetga chiqmaslikka harakat qiling.
        Omad tilaymiz!

        Roadmap: /roadmap
        Qoidalar: /rules
        Foydali: /useful
        HTML;

        $bot->sendMessage(
            text: $text_private,
            chat_id: $bot->userId(),
            parse_mode: ParseMode::HTML,
        );

        $this->end();
    }
}