<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\Command\AddBalanceOperation;

class AddBalanceOperationHandler
{
    public function __invoke(AddBalanceOperation $addBalanceOperation): void
    {
        echo $addBalanceOperation->getUserId();
    }
}