<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Factory;

use BitBag\SyliusMultiCartPlugin\DTO\AjaxPartialCartItem;
use BitBag\SyliusMultiCartPlugin\Factory\AjaxPartialCartItemFactory;
use BitBag\SyliusMultiCartPlugin\Factory\AjaxPartialCartItemFactoryInterface;
use BitBag\SyliusMultiCartPlugin\Transformer\FormatMoneyTransformerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\OrderItem;

final class AjaxPartialCartItemFactorySpec extends ObjectBehavior
{
    function let(
        FormatMoneyTransformerInterface $convertAndFormatMoneyHelper
    ): void {
        $this->beConstructedWith(
            $convertAndFormatMoneyHelper
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AjaxPartialCartItemFactory::class);
    }

    function it_is_implementing_interface(): void
    {
        $this->shouldHaveType(AjaxPartialCartItemFactoryInterface::class);
    }

    function it_creates_partial_cart_item_from_order_item(
        FormatMoneyTransformerInterface $convertAndFormatMoneyHelper,
        OrderItem                       $orderItem
    ): void {
        $orderItem->getId()->willReturn(1);
        $orderItem->getProductName()->willReturn('name');
        $orderItem->getQuantity()->willReturn('1');
        $orderItem->getUnitPrice()->willReturn('100');

        $convertAndFormatMoneyHelper->formatMoney(Argument::type('integer'))->willReturn('converted_string');

        $this->fromOrderItem($orderItem)->shouldHaveType(AjaxPartialCartItem::class);
    }
}
