<?php

namespace app\components\services;

interface SmsServiceInterface
{
    public function send(string $phone, string $message): bool;
}
