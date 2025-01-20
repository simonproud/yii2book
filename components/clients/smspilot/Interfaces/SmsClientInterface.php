<?php

declare(strict_types=1);

namespace app\components\clients\smspilot\Interfaces;

use app\components\clients\smspilot\ValueObjects\SmsMessage;
use app\components\clients\smspilot\ValueObjects\SmsResponse;

interface SmsClientInterface
{
    public function send(SmsMessage $message): ?SmsResponse;
    public function checkStatus(array|string $messageIds): ?SmsResponse;
    public function getBalance(): ?float;
}
