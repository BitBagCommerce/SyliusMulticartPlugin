<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Entity;

use Sylius\Component\Core\Model\Customer as BaseCustomer;

class Customer extends BaseCustomer implements CustomerInterface
{
    protected ?int $activeCart = 1;

    public function getActiveCart(): ?int
    {
        return $this->activeCart;
    }

    public function setActiveCart(?int $activeCart): void
    {
        $this->activeCart = $activeCart;
    }
}
