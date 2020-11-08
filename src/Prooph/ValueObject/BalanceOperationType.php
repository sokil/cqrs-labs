<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\ValueObject;

use MabeEnum\Enum;

class BalanceOperationType extends Enum
{
    public const ADD_CREDITS = 1;
    public const REMOVE_CREDITS = 2;
}