<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\DTO;

class AjaxPartialCartItem
{
    public int $id;

    public string $name;

    public int $quantity;

    public string $formattedUnitPrice;

    public function __construct(int $id, string $name, int $quantity, string $formattedUnitPrice)
    {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->formattedUnitPrice = $formattedUnitPrice;
    }
}
