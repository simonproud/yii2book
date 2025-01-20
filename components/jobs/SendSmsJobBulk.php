<?php

declare(strict_types=1);

namespace app\components\jobs;

use app\components\services\SmsPilotService;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

final class SendSmsJobBulk extends BaseObject implements JobInterface
{
    public array $phones;
    public string $message;

    public function execute($queue): bool
    {
        /** @var SmsPilotService $smsService */
        $smsService = Yii::$container->get(SmsPilotService::class);
        return $smsService->send($this->phones, $this->message);
    }
}
