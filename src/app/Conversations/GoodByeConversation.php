<?php

namespace App\Conversations;

use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class GoodByeConversation extends Conversation
{
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot): void
    {
        $text = <<<HTML
        <b>Xayr!</b>
        HTML;

        $bot->sendMessage(
            text: $text,
            parse_mode: ParseMode::HTML,
            reply_to_message_id: $bot->messageId()
        );

        $this->end();
    }
}