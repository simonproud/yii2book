<?php

declare(strict_types=1);

namespace app\components\jobs;

use app\components\services\SmsPilotService;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

final class SendSmsJob extends BaseObject implements JobInterface
{
    public string $phone;
    public string $message;

    public function execute($queue): bool
    {
        /** @var SmsPilotService $smsService */
        $smsService = Yii::$container->get(SmsPilotService::class);
        return $smsService->send($this->phone, $this->message);
    }
}
