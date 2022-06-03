<?php

namespace BitBag\SyliusMultiCartPlugin\Entity;

trait OrderTrait
{
    protected ?int $cartNumber = 1;

    public function getCartNumber(): ?int
    {
        return $this->cartNumber;
    }

    public function setCartNumber(?int $cartNumber): void
    {
        $this->cartNumber = $cartNumber;
    }
}
