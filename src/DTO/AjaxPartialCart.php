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
