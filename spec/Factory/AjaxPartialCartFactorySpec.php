<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Factory;

use BitBag\SyliusMultiCartPlugin\DTO\AjaxPartialCart;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Factory\AjaxPartialCartFactory;
use BitBag\SyliusMultiCartPlugin\Factory\AjaxPartialCartFactoryInterface;
use BitBag\SyliusMultiCartPlugin\Factory\OrderItemFactoryInterface;
use BitBag\SyliusMultiCartPlugin\MoneyFormatter\MoneyFormatterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\OrderItemInterface as BaseOrderItemInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderItem;
use Sylius\Component\Core\Model\OrderItemInterface;

final class AjaxPartialCartFactorySpec extends ObjectBehavior
{
    function let(
        MoneyFormatterInterface $convertAndFormatMoneyHelper,
        OrderItemFactoryInterface $ajaxPartialCartItemFactory
    ): void {
        $this->beConstructedWith(
            $convertAndFormatMoneyHelper,
            $ajaxPartialCartItemFactory
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AjaxPartialCartFactory::class);
    }

    function it_is_implementing_interface(): void
    {
        $this->shouldHaveType(AjaxPartialCartFactoryInterface::class);
    }

    function it_creates_partial_cart_from_order(
        OrderInterface $order,
        OrderItemInterface $orderItem,
        MoneyFormatterInterface $convertAndFormatMoneyHelper,
        OrderItemFactoryInterface $ajaxPartialCartItemFactory,
        OrderItem $multicartOrderItem
    ): void {
        $order->getItems()->willReturn(new ArrayCollection([$orderItem->getWrappedObject()]));

        $order->getCartNumber()->willReturn(2);
        $order->getItemsTotal()->willReturn(3000);
        $order->getCurrencyCode()->willReturn('EUR');
        $convertAndFormatMoneyHelper->formatMoney(3000)->willReturn('converted');
        $ajaxPartialCartItemFactory->fromOrderItem($orderItem)->willReturn($multicartOrderItem);

        $partialCart = $this->fromOrder($order)->getCurrency()->shouldReturn('EUR');
        $partialCart = $this->fromOrder($order)->getCartNumber()->shouldReturn(2);
        $partialCart = $this->fromOrder($order)->getFormattedItemsTotal()->shouldReturn('converted');
        $partialCart = $this->fromOrder($order)->getItems()->shouldReturn([]);
        $partialCart = $this->fromOrder($order)->getItemsCount()->shouldReturn(1);

        $this->fromOrder($order)->shouldHaveType(AjaxPartialCart::class);
    }
}
