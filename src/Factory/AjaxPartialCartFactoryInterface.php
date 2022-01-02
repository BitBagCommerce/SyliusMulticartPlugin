<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Factory;

use BitBag\SyliusMultiCartPlugin\DTO\AjaxPartialCart;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;

interface AjaxPartialCartFactoryInterface
{
    public function fromOrder(OrderInterface $order): AjaxPartialCart;
}
