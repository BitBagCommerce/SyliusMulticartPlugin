<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Factory;

use BitBag\SyliusMultiCartPlugin\DTO\AjaxPartialCart;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\MoneyFormatter\MoneyFormatterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class AjaxPartialCartFactory implements AjaxPartialCartFactoryInterface
{
    private MoneyFormatterInterface $moneyFormatter;

    private OrderItemFactoryInterface $ajaxPartialCartItemFactory;

    public function __construct(
        MoneyFormatterInterface $moneyFormatter,
        OrderItemFactoryInterface $ajaxPartialCartItemFactory
    ) {
        $this->moneyFormatter = $moneyFormatter;
        $this->ajaxPartialCartItemFactory = $ajaxPartialCartItemFactory;
    }

    public function fromOrder(OrderInterface $order): AjaxPartialCart
    {
        $orderItems = $order->getItems();

        return new AjaxPartialCart(
            $order->getCartNumber(),
            $this->moneyFormatter->formatMoney($order->getItemsTotal()),
            $order->getCurrencyCode(),
            $this->createCartItems($orderItems)->toArray(),
            $orderItems->count()
        );
    }

    private function createCartItems(Collection $orderItems): Collection
    {
        /** @var Collection $cartItems */
        $cartItems = new ArrayCollection();
        foreach ($orderItems as $orderItem) {
            $orderItems = $this->ajaxPartialCartItemFactory->fromOrderItem($orderItem);
            $cartItems->add($orderItems);
        }

        return $cartItems;
    }
}
