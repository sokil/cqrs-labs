<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\Command\AddBalanceOperation;

use CqrsLabs\Prooph\ValueObject\CreditsAmount;
use CqrsLabs\Prooph\ValueObject\BalanceOperationType;
use CqrsLabs\Prooph\ValueObject\UserId;
use Prooph\Common\Messaging\Command;
use Ramsey\Uuid\Uuid;

/**
 * @psalm-immutable
 */
class AddBalanceOperation extends Command
{
    private UserId $userId;

    private BalanceOperationType $type;

    private CreditsAmount $amount;

    /**
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->setPayload($payload);
    }

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload): void
    {
        $this->userId = new UserId($payload['userId']);
        $this->type = BalanceOperationType::byValue($payload['type']);
        $this->amount = new CreditsAmount($payload['amount']);
    }

    public function payload(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'type' => $this->type->getValue(),
            'amount' => $this->amount->getIntValue(),
        ];
    }
}