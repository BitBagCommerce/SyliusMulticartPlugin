<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusMultiCartPlugin\Application\src\Entity;

use BitBag\SyliusMultiCartPlugin\Entity\OrderItemInterface;
use BitBag\SyliusMultiCartPlugin\Model\OrderItemTrait;
use Sylius\Component\Order\Model\OrderItem as BaseOrderItem;

class OrderItem extends BaseOrderItem implements OrderItemInterface
{
    use OrderItemTrait;

    public function __construct(
        int $id,
        string $name,
        int $quantity,
        string $formattedUnitPrice
    ) {
        parent::__construct();
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->formattedUnitPrice = $formattedUnitPrice;
    }
}
