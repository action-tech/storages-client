<?php

declare(strict_types=1);

namespace Action\StoragesClient\Dto;

class AddAccessDto
{
    /**
     * @var int
     */
    public $productVersion;
    /**
     * @var string
     */
    public $startDate;
    /**
     * @var string
     */
    public $endDate;
    /**
     * @var int
     */
    public $userCount;
    /**
     * @var AccessInfoDto
     */
    public $accessInfo;
    /**
     * @var UtmDto
     */
    public $utm;

    public function __construct(int $productVersion, string $startDate, string $endDate, int $userCount)
    {
        $this->productVersion = $productVersion;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->userCount = $userCount;
    }
}
