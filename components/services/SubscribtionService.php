<?php

namespace app\components\services;

use app\components\events\UserSubscribed;
use app\models\Subscription;
use Yii;

class SubscribtionService
{
    public function subscribe(int $authorId, $phone = null): bool
    {
        $subscription = new Subscription();
        $subscription->author_id = $authorId;
        $subscription->phone = $phone ?? \Yii::$app->user->identity->phone;

        $event = new UserSubscribed();
        $event->userId = Yii::$app->user->identity->id;
        $event->authorId = $authorId;
        Yii::$app->trigger(UserSubscribed::NAME, $event);

        return $subscription->save();

    }
    public function unsubscribe(int $authorId): bool
    {
        return Subscription::deleteAll([
            'author_id' => $authorId,
            'phone' => $phone ?? \Yii::$app->user->identity->phone
        ]);
    }

    public function isSubscribed(int $authorId, string $phone): bool
    {
        return Subscription::find()
            ->where(['author_id' => $authorId, 'phone' => $phone])
            ->exists();
    }
}