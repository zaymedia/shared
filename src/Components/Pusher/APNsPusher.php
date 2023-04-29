<?php

declare(strict_types=1);

namespace ZayMedia\Shared\Components\Pusher;

use Apnpush\AuthProvider\Token;
use Apnpush\Client;
use Apnpush\InvalidPayloadException;
use Apnpush\Notification;
use Apnpush\Payload;
use Apnpush\Payload\Alert;
use DateTime;
use Exception;

class APNsPusher
{
    public function __construct(
        private readonly string $keyId,
        private readonly string $teamId,
        private readonly string $appBundleId,
        private readonly string $privateKeyPath,
        private readonly string $privateKeySecret,
        private readonly string $productionBundleId
    ) {
    }

    /**
     * @param string[] $tokens
     * @throws Exception|InvalidPayloadException
     */
    public function send(
        ?string $bundleId,
        ?string $thread,
        ?string $category,
        array $tokens,
        ?string $title,
        ?string $subtitle,
        ?string $body,
        array $data = [],
        ?string $sound = null,
        ?int $badge = null,
    ): void {
        $bundleId ??= $this->appBundleId;

        $authProvider = Token::create([
            'key_id' => $this->keyId,
            'team_id' => $this->teamId,
            'app_bundle_id' => $bundleId,
            'private_key_path' => $this->privateKeyPath,
            'private_key_secret' => $this->privateKeySecret,
        ]);

        $payload = Payload::create();

        if (null !== $title && null !== $body) {
            $alert = Alert::create()
                ->setTitle($title)
                ->setBody($body);

            if (null !== $subtitle) {
                $alert->setSubtitle($subtitle);
            }

            $payload->setAlert($alert);
        }

        if (null !== $category) {
            $payload->setCategory($category);
        }

        if (null !== $thread) {
            $payload->setThreadId($thread);
        }

        if (null !== $sound) {
            $payload->setSound($sound);
        }

        if (null !== $badge) {
            $payload->setBadge($badge);
        }

        $payload->setMutableContent(true);

        $payload->setCustomValue('data', $data);

        $notifications = [];
        foreach ($tokens as $token) {
            $notification = new Notification($payload, $token);

            if (isset($data['id'])) {
                $notification->setCollapseId((string)$data['id']);
            }

            $notifications[] = $notification;
        }

        $client = new Client($authProvider, $this->productionBundleId === $bundleId);
        $client->addNotifications($notifications);

        $client->push();
    }

    /**
     * @param string[] $tokens
     * @throws Exception|InvalidPayloadException
     */
    public function sendVoIP(
        ?string $bundleId,
        array $tokens,
        array $data = [],
    ): void {
        $bundleId ??= $this->appBundleId;

        $authProvider = Token::create([
            'key_id' => $this->keyId,
            'team_id' => $this->teamId,
            'app_bundle_id' => $bundleId,
            'private_key_path' => $this->privateKeyPath,
            'private_key_secret' => $this->privateKeySecret,
        ]);

        $payload = Payload::create()
            ->setPushType('voip')
            ->setContentAvailability(true);

        $payload->setCustomValue('data', $data);

        $notifications = [];
        foreach ($tokens as $token) {
            $notification = new Notification($payload, $token);
            $notification->setExpirationAt(new DateTime('+30 seconds'));

            $notifications[] = $notification;
        }

        $client = new Client($authProvider, $this->productionBundleId === $bundleId);
        $client->addNotifications($notifications);

        $client->push();
    }
}
