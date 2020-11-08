<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\Event;

use CqrsLabs\Prooph\ValueObject\CreditsAmount;
use CqrsLabs\Prooph\ValueObject\BalanceOperationId;
use CqrsLabs\Prooph\ValueObject\BalanceOperationType;
use CqrsLabs\Prooph\ValueObject\UserId;
use Prooph\EventSourcing\AggregateChanged;

class BalanceOperationAdded extends AggregateChanged
{
    private UserId $userId;

    private BalanceOperationId $operationId;

    private BalanceOperationType $type;

    private CreditsAmount $amount;

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload): void
    {
        parent::setPayload($payload);

        $this->userId = new UserId($payload['userId']);
        $this->operationId = new BalanceOperationId($payload['operationId']);
        $this->type = BalanceOperationType::byValue($payload['type']);
        $this->amount = new CreditsAmount($payload['amount']);
    }

    public function payload(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'operationId' => $this->operationId->getIntValue(),
            'type' => $this->type->getValue(),
            'amount' => $this->amount->getIntValue(),
        ];
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return BalanceOperationId
     */
    public function getOperationId(): BalanceOperationId
    {
        return $this->operationId;
    }

    /**
     * @return BalanceOperationType
     */
    public function getType(): BalanceOperationType
    {
        return $this->type;
    }

    /**
     * @return CreditsAmount
     */
    public function getAmount(): CreditsAmount
    {
        return $this->amount;
    }
}