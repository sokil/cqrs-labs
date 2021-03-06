<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\Event;

use CqrsLabs\Prooph\ValueObject\UserId;
use Prooph\EventSourcing\AggregateChanged;

class UserRegistered extends AggregateChanged
{
    private UserId $userId;

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload): void
    {
        parent::setPayload($payload);

        $this->userId = UserId::fromString($payload['userId']);
    }

    public function payload(): array
    {
        return [
            'userId' => $this->userId->toString(),
        ];
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }
}