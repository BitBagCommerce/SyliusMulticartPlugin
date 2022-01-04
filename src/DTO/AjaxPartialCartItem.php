<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\DTO;

class AjaxPartialCartItem
{
    private int $id;

    private string $name;

    private int $quantity;

    private string $formattedUnitPrice;

    public function __construct(int $id, string $name, int $quantity, string $formattedUnitPrice)
    {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->formattedUnitPrice = $formattedUnitPrice;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getFormattedUnitPrice(): string
    {
        return $this->formattedUnitPrice;
    }
}
