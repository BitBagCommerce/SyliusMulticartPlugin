<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Factory;

use BitBag\SyliusMultiCartPlugin\DTO\AjaxPartialCartItem;
use Sylius\Component\Core\Model\OrderItemInterface;

interface AjaxPartialCartItemFactoryInterface
{
    public function fromOrderItem(OrderItemInterface $orderItem): AjaxPartialCartItem;
}
