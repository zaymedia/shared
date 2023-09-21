<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Pusher;

class FCMPusher
{
    private const CONNECT_TIMEOUT = 5;
    private const TIMEOUT = 1;

    public function __construct(
        private readonly string $key,
        private readonly string $host = 'https://fcm.googleapis.com/fcm/send'
    ) {}

    public function send(
        ?string $thread,
        ?string $category,
        array $tokens,
        ?string $title,
        ?string $subtitle,
        ?string $body,
        array $data = [],
        ?string $sound = null,
        ?int $badge = null
    ): void {
        if (empty($tokens)) {
            return;
        }

        if (null === $sound) {
            $sound = 'default';
        }

        $fields = [
            'registration_ids'  => $tokens,
            'data' => [
                'title'     => $title,
                'subtitle'  => $subtitle,
                'body'      => $body,
                'sound'     => $sound,
                'badge'     => $badge,
                'thread'    => $thread,
                'category'  => $category,
                'image'     => $data['attachmentUrl'] ?? null,
                'data'      => (!empty($data)) ? $data : null,
            ],
        ];

        // Firebase API Key
        $headers = [
            'Authorization:key=' . $this->key,
            'Content-Type:application/json',
        ];

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $this->host);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::CONNECT_TIMEOUT);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT);

        curl_exec($ch);
        curl_close($ch);
    }

    public function sendVoIP(
        array $tokens,
        array $data = [],
    ): void {
        if (empty($tokens)) {
            return;
        }

        $fields = [
            'registration_ids' => $tokens,
            'data' => $data,
        ];

        // Firebase API Key
        $headers = [
            'Authorization:key=' . $this->key,
            'Content-Type:application/json',
        ];

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $this->host);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::CONNECT_TIMEOUT);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT);

        curl_exec($ch);
        curl_close($ch);
    }
}
