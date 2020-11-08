<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\ValueObject;

use Ramsey\Uuid\Uuid;

class UserId
{
    private Uuid $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = Uuid::fromString($uuid);
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }
}