<?php

declare(strict_types=1);

namespace app\components\handlers;

use app\components\events\UserSubscribed;
use app\components\services\NotificationService;
use app\models\Author;
use app\models\User;
use Yii;

final class UserSubscribedSmsHandler
{

    public static function handle(UserSubscribed $event): void
    {
        $user = User::findOne($event->userId);
        $author = Author::findOne($event->authorId);

        $notificationService = Yii::$container->get(NotificationService::class);
        $notificationService->send(
            'sms',
            $user->phone,
            'You are subscribed to '.$author->full_name);
    }
}
