<?php

declare(strict_types=1);

namespace Action\StoragesClient\ValueObject;

use Action\StoragesClient\Exception\ValidationException;

class UserId
{
    /**
     * @var int
     */
    private $value;

    /**
     * UserId constructor.
     * @param int $value
     * @throws ValidationException
     */
    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new ValidationException('User id is invalid');
        }

        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->getValue();
    }
}
