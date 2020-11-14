<?php

declare(strict_types=1);

require '../../vendor/autoload.php';

use Prooph\ServiceBus\CommandBus;
use CqrsLabs\Prooph\Command\AddBalanceOperation\AddBalanceOperation;
use CqrsLabs\Prooph\Command\AddBalanceOperation\AddBalanceOperationHandler;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Prooph\ServiceBus\Plugin\ServiceLocatorPlugin;
use League\Container\Container;

// container
$container = new Container();
$container->add(AddBalanceOperationHandler::class);

// commands
$commandBus = new CommandBus();

$commandRouter = new CommandRouter();
$commandRouter->attachToMessageBus($commandBus);

$commandHandleLocator = new ServiceLocatorPlugin($container);
$commandHandleLocator->attachToMessageBus($commandBus);

$commandRouter->route(AddBalanceOperation::class)->to(AddBalanceOperationHandler::class);

$commandBus->dispatch(
    new AddBalanceOperation(
        [
            'userId' => \CqrsLabs\Prooph\ValueObject\UserId::generate()->toString(),
            'type' => \CqrsLabs\Prooph\ValueObject\BalanceOperationType::ADD_CREDITS,
            'amount' => 10,
        ]
    )
);

