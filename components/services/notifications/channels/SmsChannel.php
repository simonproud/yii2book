<?php

declare(strict_types=1);

namespace app\components\services\notifications\channels;

use app\components\jobs\SendSmsJob;
use app\components\services\SmsPilotService;
use Yii;

final class SmsChannel implements NotificationChannelInterface
{
    public function send(string $recipient, string $message, array $options = []): void
    {
        Yii::$app->queue->push(new SendSmsJob([
            'phone' => $recipient,
            'message' => $message,
        ]));
    }

    public function sendBulk(array $recipients, string $message, array $options = []): void
    {
        foreach ($recipients as $recipient) {
            $this->send($recipient, $message, $options);
        }
    }
}
