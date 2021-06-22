<?php

declare(strict_types=1);

namespace Action\StoragesClient\Dto;

class AccessAttributeListItemDto
{
    /**
     * @var string
     */
    public $key;
    /**
     * @var string
     */
    public $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}
