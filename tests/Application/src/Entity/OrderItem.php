<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusMultiCartPlugin\Application\src\Entity;

use BitBag\SyliusMultiCartPlugin\Entity\OrderItemInterface;
use BitBag\SyliusMultiCartPlugin\Model\OrderItemTrait;
use Sylius\Component\Core\Model\OrderItem as BaseOrderItem;

class OrderItem extends BaseOrderItem implements OrderItemInterface
{
    use OrderItemTrait;

    public function __construct(
//        int $id,
//        string $name,
//        int $quantity,
//        string $formattedUnitPrice
    ) {
        parent::__construct();
//        $this->id = $id;
//        $this->quantity = $quantity;
//        $this->name = $name;
//        $this->formattedUnitPrice = $formattedUnitPrice;
    }
}
