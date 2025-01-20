<?php

declare(strict_types=1);

namespace app\components\services;

use app\components\services\notifications\channels\NotificationChannelInterface;

final class NotificationService
{
    private array $channels;

    public function __construct(array $channels)
    {
        foreach ($channels as $name => $channel) {
            if (!$channel instanceof NotificationChannelInterface) {
                throw new \InvalidArgumentException("Channel must implement NotificationChannelInterface");
            }
            $this->channels[$name] = $channel;
        }
    }

    public function send(
        string $channelName,
        string $recipient,
        string $message,
        array $options = []
    ): void {
        if (!isset($this->channels[$channelName])) {
            throw new \InvalidArgumentException("Channel {$channelName} not found");
        }

        $this->channels[$channelName]->send($recipient, $message, $options);
    }

    public function sendBulk(
        string $channelName,
        array $recipients,
        string $message,
        array $options = []
    ): void {
        if (!isset($this->channels[$channelName])) {
            throw new \InvalidArgumentException("Channel {$channelName} not found");
        }

        $this->channels[$channelName]->sendBulk($recipients, $message, $options);
    }
}
