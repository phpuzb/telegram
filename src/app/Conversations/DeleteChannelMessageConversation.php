<?php

namespace App\Conversations;

use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class DeleteChannelMessageConversation extends Conversation
{
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot): void
    {
        $from_channel = $bot->message()?->from?->username;

        if (isset($from_channel) && $from_channel === "Channel_Bot") {
            $bot->deleteMessage($bot->chatId(), $bot->messageId());
        }
        $this->end();
    }
}