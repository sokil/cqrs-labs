<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\Aggregate;

use CqrsLabs\Prooph\Event\BalanceOperationAdded;
use CqrsLabs\Prooph\Event\UserRegistered;
use CqrsLabs\Prooph\ValueObject\BalanceOperationId;
use CqrsLabs\Prooph\ValueObject\BalanceOperationType;
use CqrsLabs\Prooph\ValueObject\CreditsAmount;
use CqrsLabs\Prooph\ValueObject\UserId;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class Balance extends AggregateRoot
{
    private UserId $userId;

    private CreditsAmount $amount;

    /**
     * @var BalanceOperationId[]
     */
    private $operationIds = [];

    public static function initialiseBalance(UserId $userId): Balance
    {
        $balance = new static();

        $balance->recordThat(
            UserRegistered::occur(
                $userId->toString(),
                [
                    'userId' => $userId->toString(),
                ]
            )
        );

        return $balance;
    }

    public function addOperation(
        BalanceOperationId $operationId,
        BalanceOperationType $operationType,
        CreditsAmount $amount
    ): void {
        $this->recordThat(
            BalanceOperationAdded::occur(
                (string) $operationId->getIntValue(),
                [
                    'userId' => $this->userId->toString(),
                    'operationId' => $operationId->getIntValue(),
                    'type' => $operationType->getValue(),
                    'amount' => $amount->getIntValue(),
                ]
            )
        );
    }

    protected function aggregateId(): string
    {
        return $this->userId->toString();
    }

    protected function apply(AggregateChanged $event): void
    {
        if ($event instanceof UserRegistered) {
            $this->userId = $event->getUserId();
            $this->amount = new CreditsAmount(0);
        } elseif ($event instanceof BalanceOperationAdded) {
            // check that balance is initialised
            if (empty($this->userId)) {
                throw new \Logicexception('Balance is not initialised');
            }

            // check that operation already applied
            if (!empty($this->operationIds[$event->getOperationId()->getIntValue()])) {
                throw new \LogicException('Operation added twice');
            }


            // apply operation
            if ($event->getType()->getValue() === BalanceOperationType::REMOVE_CREDITS) {
                // check than credits present on balance
                if ($this->amount->getIntValue() < $event->getAmount()->getIntValue()) {
                    throw new \LogicException('Not enough credits on balance to decrease');
                }

                // decrease credits
                $this->amount = $this->amount->subtract($event->getAmount());
            } elseif ($event->getType()->getValue() === BalanceOperationType::ADD_CREDITS) {
                $this->amount = $this->amount->add($event->getAmount());
            } else {
                throw new \LogicException(sprintf('Unknown operation type %s', $event->getType()->getName()));
            }

            // mark that operation already applied
            $this->operationIds[$event->getOperationId()->getIntValue()] = true;
        } else {
            throw new \InvalidArgumentException('Event not acceptable');
        }
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return CreditsAmount
     */
    public function getAmount(): CreditsAmount
    {
        return $this->amount;
    }
}