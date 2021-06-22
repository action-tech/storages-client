<?php

declare(strict_types=1);

namespace Action\StoragesClient\Dto;

class AccessListDto
{
    /**
     * @var AccessDto[]
     */
    public $accesses;

    /**
     * AccessListDto constructor.
     * @param AccessDto[] $accesses
     */
    public function __construct(array $accesses)
    {
        $this->accesses = $accesses;
    }
}
