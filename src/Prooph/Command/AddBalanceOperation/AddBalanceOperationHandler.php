<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\Command\AddBalanceOperation;

use CqrsLabs\Prooph\Aggregate\Balance;
use CqrsLabs\Prooph\ValueObject\BalanceOperationId;
use CqrsLabs\Prooph\ValueObject\BalanceOperationType;
use CqrsLabs\Prooph\ValueObject\CreditsAmount;

class AddBalanceOperationHandler
{
    public function __invoke(AddBalanceOperation $addBalanceOperation): void
    {
        $balance = Balance::initialiseBalance($addBalanceOperation->getUserId());

        $balance->addOperation(
            new BalanceOperationId(1),
            BalanceOperationType::ADD_CREDITS(),
            new CreditsAmount(10)
        );

        echo 'Actual balance: ' . $balance->getAmount()->getIntValue() . PHP_EOL;
    }
}