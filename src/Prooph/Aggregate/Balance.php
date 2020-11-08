<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\Aggregate;

use CqrsLabs\Prooph\Event\BalanceOperationAdded;
use CqrsLabs\Prooph\Event\UserRegistered;
use CqrsLabs\Prooph\ValueObject\CreditsAmount;
use CqrsLabs\Prooph\ValueObject\UserId;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class Balance extends AggregateRoot
{
    private UserId $userId;

    private CreditsAmount $amount;

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

    protected function aggregateId(): string
    {
        return $this->userId->toString();
    }

    protected function apply(AggregateChanged $event): void
    {
        if ($event instanceof UserRegistered) {
            $this->userId = $event->getUserId();
        } elseif ($event instanceof BalanceOperationAdded) {
            $this->amount = $this->amount->add($event->getAmount());
        } else {
            throw new \InvalidArgumentException('Event not acceptable');
        }
    }
}