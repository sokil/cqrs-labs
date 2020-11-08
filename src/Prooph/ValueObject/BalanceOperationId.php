<?php

declare(strict_types=1);

namespace CqrsLabs\Prooph\ValueObject;

class BalanceOperationId
{
    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('Id must be positive int');
        }

        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIntValue(): int
    {
        return $this->id;
    }
}