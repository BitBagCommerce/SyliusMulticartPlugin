<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\DTO;

class AjaxPartialCart
{
    public ?int $cartNumber;

    public string $formattedItemsTotal;

    public ?string $currency;

    /** @var AjaxPartialCartItem[] */
    public array $items;

    public int $itemsCount = 0;

    public function __construct(?int $cartNumber, string $formattedItemsTotal, ?string $currency, array $items, int $itemsCount)
    {
        $this->cartNumber = $cartNumber;
        $this->formattedItemsTotal = $formattedItemsTotal;
        $this->currency = $currency;
        $this->items = $items;
        $this->itemsCount = $itemsCount;
    }
}
