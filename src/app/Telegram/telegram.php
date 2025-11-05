<?php
/** @var Nutgram $bot */

use App\Commands\AboutCommand;
use App\Commands\RoadmapCommand;
use App\Commands\RulesCommand;
use App\Commands\StartCommand;
use App\Conversations\DeleteChannelMessageConversation;
use App\Conversations\GoodByeConversation;
use App\Conversations\GroupConversation;
use App\Conversations\UsefulCommandConversation;
use App\Conversations\VersionConversation;
use App\Conversations\WelcomeToGroupConversation;
use App\Middlewares\GroupMiddleware;
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

// group messages
$bot->onNewChatMembers(WelcomeToGroupConversation::class)
    ->middleware(GroupMiddleware::class);
$bot->onLeftChatMember(GoodByeConversation::class)
    ->middleware(GroupMiddleware::class);
$bot->onMessage(DeleteChannelMessageConversation::class)
    ->middleware(GroupMiddleware::class);

// callback queries
$bot->onCallbackQueryData("page {page}", VersionConversation::class);