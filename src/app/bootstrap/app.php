<?php

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;

// Initialize bot
try {
    $bot = new Nutgram(env("TELEGRAM_TOKEN"));
} catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
    new RuntimeException($e->getMessage());
}

require_once __DIR__ . '/../Telegram/telegram.php';

// if bot is on production it will use webhook,
// so we need to set the running on webhook mode
if (env("APP_ENV", "local") !== 'local') {
    $bot->setRunningMode(Webhook::class);
} else {
    // When running locally with long polling, ensure any existing webhook is removed
    // to avoid 409 Conflict from Telegram when calling getUpdates
    $bot->deleteWebhook(true);
}

/** @var Nutgram $bot [Defining variable name for IDEs] */
return $bot;
