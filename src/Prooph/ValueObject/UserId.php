<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\ValueObject;

use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserId
{
    private UuidInterface $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): UserId
    {
        return new UserId(Uuid::uuid4());
    }

    public static function fromString(string $uuidString): UserId
    {
        /** @var UuidV4 $uuid */
        $uuid = UuidV4::fromString($uuidString);

        return new UserId($uuid);
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function __toString()
    {
        return $this->toString();
    }
}