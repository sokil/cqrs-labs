<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\Aggregate;

use CqrsLabs\Prooph\AbstractTestCase;
use CqrsLabs\Prooph\Event\UserRegistered;
use CqrsLabs\Prooph\ValueObject\BalanceOperationId;
use CqrsLabs\Prooph\ValueObject\BalanceOperationType;
use CqrsLabs\Prooph\ValueObject\CreditsAmount;
use CqrsLabs\Prooph\ValueObject\UserId;
use Prooph\EventSourcing\AggregateChanged;

class BalanceTest extends AbstractTestCase
{
    public function testInitBalance()
    {
        $userId = UserId::generate();

        $balance = Balance::initialiseBalance($userId);

        $this->assertSame($userId->toString(), $balance->getUserId()->toString());
        $this->assertSame(0, $balance->getAmount()->getIntValue());

        /** @var AggregateChanged[] $events */
        $events = $this->popRecordedEvents($balance);

        $this->assertCount(1, $events);

        /** @var UserRegistered $event */
        $event = $events[0];

        $this->assertSame($userId->toString(), $event->getUserId()->toString());
    }

    public function testChangeBalance()
    {
        $userId = UserId::generate();
        $creditsAmountToAdd = new CreditsAmount(5);

        $balance = Balance::initialiseBalance($userId);

        $balance->addOperation(
            new BalanceOperationId(1),
            BalanceOperationType::ADD_CREDITS(),
            new CreditsAmount(5)
        );

        $this->assertSame($creditsAmountToAdd->getIntValue(), $balance->getAmount()->getIntValue());
    }
}