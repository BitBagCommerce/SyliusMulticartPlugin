<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusMultiCartPlugin\Application\src\Entity;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Model\CustomerTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

class Customer extends BaseCustomer implements CustomerInterface
{
    use CustomerTrait;
}
