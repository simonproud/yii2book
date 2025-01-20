<?php

declare(strict_types=1);

namespace app\components\clients\smspilot;

use app\components\clients\smspilot\Interfaces\SmsClientInterface;
use app\components\clients\smspilot\ValueObjects\SmsMessage;
use app\components\clients\smspilot\ValueObjects\SmsResponse;
use GuzzleHttp\ClientInterface;

final class Client implements SmsClientInterface
{
    private const API_URL = 'http://smspilot.ru/api.php';

    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly string $apiKey,
        private readonly string $charset = 'UTF-8',
    ) {}

    public function send(SmsMessage $message): ?SmsResponse
    {
        $text = $this->charset !== 'UTF-8'
            ? mb_convert_encoding($message->text, 'utf-8', $this->charset)
            : $message->text;

        $recipients = is_array($message->recipients)
            ? implode(',', $message->recipients)
            : $message->recipients;

        $response = $this->httpClient->request('GET', self::API_URL, [
            'query' => [
                'send' => $text,
                'to' => $recipients,
                'from' => $message->sender,
                'send_datetime' => $message->sendDateTime,
                'apikey' => $this->apiKey,
            ]
        ]);

        return $this->processResponse($response->getBody()->getContents());
    }

    public function checkStatus(array|string $messageIds): ?SmsResponse
    {
        $ids = is_array($messageIds) ? implode(',', $messageIds) : $messageIds;

        $response = $this->httpClient->post(self::API_URL, [
            'check' => $ids,
            'apikey' => $this->apiKey
        ]);

        return $this->processResponse($response->getBody()->getContents());
    }

    public function getBalance(): ?float
    {
        $response = $this->httpClient->post(self::API_URL, [
            'balance' => 'rur',
            'apikey' => $this->apiKey
        ]);

        if (!$response || str_starts_with($response->getBody()->getContents(), 'ERROR=')) {
            return null;
        }

        return (float)$response;
    }

    private function processResponse(?string $response): ?SmsResponse
    {
        if (!$response || str_starts_with($response, 'ERROR=')) {
            return null;
        }

        if (!str_starts_with($response, 'SUCCESS=')) {
            return null;
        }

        $p = strpos($response, "\n");
        $successLine = substr($response, 8, $p - 8);

        preg_match('~([0-9.]+)/([0-9.]+)~', $successLine, $matches);
        $cost = $matches ? (float)$matches[1] : null;
        $balance = $matches ? (float)$matches[2] : null;

        $status = $this->parseStatusCsv(substr($response, $p + 1));

        return new SmsResponse($status, $cost, $balance);
    }

    private function parseStatusCsv(string $csv): array
    {
        return array_filter(array_map(
            fn($line) => match(true) {
                str_contains($line, ',') => [
                    'id' => explode(',', $line)[0],
                    'phone' => explode(',', $line)[1],
                    'price' => explode(',', $line)[2],
                    'status' => explode(',', $line)[3]
                ],
                default => null
            },
            explode("\n", $csv)
        ));
    }
}
