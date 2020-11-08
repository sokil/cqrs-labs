<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\ValueObject;

class CreditsAmount
{
    private int $amount;

    public function __construct(int $amount)
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Id must be positive int');
        }

        $this->amount = $amount;
    }

    public function getIntValue(): int
    {
        return $this->amount;
    }

    public function add(CreditsAmount $amount): CreditsAmount
    {
        return new CreditsAmount($this->getIntValue() + $amount->getIntValue());
    }
}