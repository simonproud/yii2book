<?php

namespace app\components\events;

use yii\base\Event;

class UserSubscribed extends Event
{
    public const string NAME = 'userSubscribed';
    public int $userId;
    public int $authorId;
}