<?php

declare(strict_types=1);

namespace app\components\services;

use app\components\clients\smspilot\Client;
use app\components\clients\smspilot\Interfaces\SmsClientInterface;
use app\components\clients\smspilot\ValueObjects\SmsMessage;
use Yii;

final class SmsPilotService implements SmsServiceInterface
{
    public function send(string|array $phone, string $message): bool
    {
        $smsMessage = new SmsMessage(
            text: $message,
            recipients: $phone,
            sender: Yii::$app->params['sms']['sender']
        );
        /** @var Client $smsPilotClient */
        $smsPilotClient = Yii::$container->get(SmsClientInterface::class);

        $response = $smsPilotClient->send($smsMessage);

        return $response !== null;
    }

    public function checkBalance(): ?float
    {
        return Yii::$app->smsClient->getBalance();
    }
}
