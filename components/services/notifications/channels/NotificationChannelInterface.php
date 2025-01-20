<?php

declare(strict_types=1);

namespace app\components\services\notifications\channels;

interface NotificationChannelInterface
{
    public function send(string $recipient, string $message, array $options = []): void;
    public function sendBulk(array $recipients, string $message, array $options = []): void;
}
