<?php

namespace app\components\events;

use yii\base\Event;

class BookCreated extends Event
{
    public const string NAME = 'bookCreated';

    public int $bookId;
}