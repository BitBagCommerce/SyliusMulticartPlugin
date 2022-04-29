<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Entity;

use Sylius\Component\Order\Model\OrderItem as BaseOrderItem;

class OrderItem extends BaseOrderItem
{
    private string $name;

    private string $formattedUnitPrice;

    public function __construct(
        int $id,
        string $name,
        int $quantity,
        string $formattedUnitPrice
    ) {
        parent::__construct();
        $this->name = $name;
        $this->formattedUnitPrice = $formattedUnitPrice;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getFormattedUnitPrice(): string
    {
        return $this->formattedUnitPrice;
    }
}
