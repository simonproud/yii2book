<?php

declare(strict_types=1);

namespace app\components\handlers;

use app\components\events\BookCreated;
use app\components\jobs\SendSmsJobBulk;
use app\models\Book;
use app\models\Subscription;
use Yii;

final class BookCreatedSmsHandler
{
    public static function handle(BookCreated $event): void
    {
        $book = Book::findOne($event->bookId);
        $subscriptions = Subscription::findAll(['author_id' => $book->authorIds]);
        $phones = array_column($subscriptions, 'phone');
        Yii::$app->queue->push(new SendSmsJobBulk([
            'phones' => $phones,
            'message' => 'New book has been published by your subscribed author! '
                . implode(',', $book->authorFios)
        ]));
    }
}
