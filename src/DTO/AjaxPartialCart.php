<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\DTO;

class AjaxPartialCart
{
    private ?int $cartNumber;

    private string $formattedItemsTotal;

    private ?string $currency;

    /** @var AjaxPartialCartItem[] */
    private array $items;

    private int $itemsCount = 0;

    public function __construct(
        ?int $cartNumber,
        string $formattedItemsTotal,
        ?string $currency,
        array $items,
        int $itemsCount
    ) {
        $this->cartNumber = $cartNumber;
        $this->formattedItemsTotal = $formattedItemsTotal;
        $this->currency = $currency;
        $this->items = $items;
        $this->itemsCount = $itemsCount;
    }

    public function getCartNumber(): ?int
    {
        return $this->cartNumber;
    }

    public function getFormattedItemsTotal(): string
    {
        return $this->formattedItemsTotal;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /** @return AjaxPartialCartItem[] */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getItemsCount(): int
    {
        return $this->itemsCount;
    }
}
