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
use BitBag\SyliusMultiCartPlugin\View\OrderItemView;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
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
        OrderItemView $multicartOrderItem
    ): void {
        $order->getItems()->willReturn(new ArrayCollection([$orderItem->getWrappedObject()]));

        $order->getCartNumber()->willReturn(2);
        $order->getItemsTotal()->willReturn(3000);
        $order->getCurrencyCode()->willReturn('EUR');
        $convertAndFormatMoneyHelper->formatMoney(3000)->willReturn('converted');
        $ajaxPartialCartItemFactory->fromOrderItem($orderItem)->willReturn($multicartOrderItem);

        $partialCart = $this->fromOrder($order);
        $partialCart->getCurrency()->shouldReturn('EUR');
        $partialCart->getCartNumber()->shouldReturn(2);
        $partialCart->getFormattedItemsTotal()->shouldReturn('converted');
        $partialCart->getItemsCount()->shouldReturn(1);

        $this->fromOrder($order)->shouldHaveType(AjaxPartialCart::class);
    }
}
