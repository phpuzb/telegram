<?php
/** @var Nutgram $bot */

use App\Commands\AboutCommand;
use App\Commands\RoadmapCommand;
use App\Commands\RulesCommand;
use App\Commands\StartCommand;
use App\Conversations\GroupConversation;
use App\Conversations\UsefulCommandConversation;
use App\Conversations\VersionConversation;
use SergiX44\Nutgram\Nutgram;

// commands
$bot->onCommand('start', StartCommand::class);
$bot->onCommand('rules', RulesCommand::class);
$bot->onCommand('about', AboutCommand::class);
$bot->onCommand('roadmap', RoadmapCommand::class);
// conversations
$bot->onCommand('group', GroupConversation::class);
$bot->onCommand('useful', UsefulCommandConversation::class);
$bot->onCommand('version', VersionConversation::class);

// callback queries
$bot->onCallbackQueryData("page {page}", VersionConversation::class);

$bot->onNewChatMembers(function (Nutgram $bot) {
    $message = $bot->message();
    if ($message === null) {
        return;
    }

    $chatType = $message->chat->type->name ?? null;
    if (!in_array($chatType, ['GROUP', 'SUPERGROUP'], true)) {
        return;
    }

    $names = [];
    foreach ($message->new_chat_members as $user) {
        if (!empty($user->is_bot) && $user->is_bot) {
            continue;
        }
        $names[] = $user->first_name;
    }

    if (empty($names)) {
        return;
    }

    $text = '👋 Assalomu alaykum, ' . implode(', ', $names) . "!";
    $text .= "\nPHP dasturchilar hamjamiyatiga xush kelibsiz!";
    $text .= "\n📜 Qoidalar: /rules";
    $text .= "\n🔗 Foydali resurslar: /useful";
    $text .= "\n🗺️ Yo'l xaritasi: /roadmap";
    $text .= "\n Boshqa buyruqlarni @phpuz_bot ga yuboring.";


    $bot->sendMessage($text, reply_to_message_id: $message->message_id);
});
