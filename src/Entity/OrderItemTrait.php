<?php

namespace BitBag\SyliusMultiCartPlugin\Entity;

trait OrderItemTrait
{
    private string $name;

    private string $formattedUnitPrice;

    public function getName(): string
    {
        return $this->name;
    }

    public function getFormattedUnitPrice(): string
    {
        return $this->formattedUnitPrice;
    }
}
