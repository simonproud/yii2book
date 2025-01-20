<?php

declare(strict_types=1);

namespace app\components\clients\smspilot\ValueObjects;

readonly class SmsResponse
{
    public function __construct(
        public array $status,
        public ?float $cost,
        public ?float $balance,
    ) {}
}
