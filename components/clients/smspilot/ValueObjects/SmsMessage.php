<?php

declare(strict_types=1);

namespace app\components\clients\smspilot\ValueObjects;

readonly class SmsMessage
{
    public function __construct(
        public string $text,
        public string|array $recipients,
        public string $sender,
        public ?string $sendDateTime = null,
    ) {}
}
