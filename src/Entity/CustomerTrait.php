<?php

namespace BitBag\SyliusMultiCartPlugin\Entity;

trait CustomerTrait
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
