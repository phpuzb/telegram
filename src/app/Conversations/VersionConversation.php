<?php

namespace App\Conversations;

use App\Buttons\InlineButtons\VersionKeyboards;
use App\Utils\Traits\GeneralTrait;
use App\Utils\Traits\VersionTrait;
use JsonException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Message\LinkPreviewOptions;

class VersionConversation extends Conversation
{
    use VersionKeyboards, VersionTrait, GeneralTrait;

    protected string $message = <<<HTML
        <b>PHP</b> <a href="https://github.com/php/php-src">manbaasining</a> so'nggi relizlari 
    HTML;

    /**
     * @throws JsonException
     */
    public function start(Nutgram $bot, ...$parameters): void
    {
        $page = implode(" ", $parameters);
        if (is_numeric($page) && $page > 0) {
            $bot->editMessageText(
                text: $this->message,
                parse_mode: ParseMode::HTML,
                disable_web_page_preview: true,
                link_preview_options: new LinkPreviewOptions(true),
                reply_markup: $this->versionButtons($this->parseTageName(), $page)
            );
        } else {
            $bot->sendMessage(
                text: $this->message,
                parse_mode: ParseMode::HTML,
                disable_web_page_preview: true,
                link_preview_options: new LinkPreviewOptions(true),
                reply_markup: $this->versionButtons($this->parseTageName())
            );
        }
    }
}