<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Factory;

use BitBag\SyliusMultiCartPlugin\Factory\OrderItemFactory;
use BitBag\SyliusMultiCartPlugin\Factory\OrderItemFactoryInterface;
use BitBag\SyliusMultiCartPlugin\MoneyFormatter\MoneyFormatterInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\OrderItem as BaseOrderItem;
use Tests\BitBag\SyliusMultiCartPlugin\Application\src\Entity\OrderItem;

final class OrderItemFactorySpec extends ObjectBehavior
{
    function let(
        MoneyFormatterInterface $convertAndFormatMoneyHelper
    ): void {
        $this->beConstructedWith(
            $convertAndFormatMoneyHelper
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderItemFactory::class);
    }

    function it_is_implementing_interface(): void
    {
        $this->shouldHaveType(OrderItemFactoryInterface::class);
    }

    function it_creates_partial_cart_item_from_order_item(
        MoneyFormatterInterface $convertAndFormatMoneyHelper,
        BaseOrderItem               $orderItem
    ): void {
        $orderItem->getId()->willReturn(1);
        $orderItem->getProductName()->willReturn('name');
        $orderItem->getQuantity()->willReturn('1');
        $orderItem->getUnitPrice()->willReturn('100');

        $convertAndFormatMoneyHelper->formatMoney(Argument::type('integer'))->willReturn('converted_string');

        $this->fromOrderItem($orderItem)->shouldHaveType(OrderItem::class);
    }
}
