<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Factory;

use BitBag\SyliusMultiCartPlugin\DTO\AjaxPartialCart;
use BitBag\SyliusMultiCartPlugin\DTO\AjaxPartialCartItem;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Factory\AjaxPartialCartFactory;
use BitBag\SyliusMultiCartPlugin\Factory\AjaxPartialCartFactoryInterface;
use BitBag\SyliusMultiCartPlugin\Factory\AjaxPartialCartItemFactoryInterface;
use BitBag\SyliusMultiCartPlugin\Helper\ConvertAndFormatMoneyHelperInterface;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\OrderItem;

final class AjaxPartialCartFactorySpec extends ObjectBehavior
{
    function let(
        ConvertAndFormatMoneyHelperInterface $convertAndFormatMoneyHelper,
        AjaxPartialCartItemFactoryInterface $ajaxPartialCartItemFactory
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
        Collection $itemsCollection,
        ConvertAndFormatMoneyHelperInterface $convertAndFormatMoneyHelper,
        OrderInterface $order,
        OrderItem $orderItem,
        AjaxPartialCartItemFactoryInterface $ajaxPartialCartItemFactory,
        AjaxPartialCartItem $ajaxPartialCartItem
    ): void {
        $order->getItems()->willReturn($itemsCollection);
        $itemsCollection->toArray()->willReturn([$orderItem]);

        $order->getCartNumber()->shouldBeCalled();
        $order->getItemsTotal()->shouldBeCalled();
        $convertAndFormatMoneyHelper->convertAndFormatMoney(Argument::type('integer'))->willReturn('converted_string');
        $order->getCurrencyCode()->shouldBeCalled();

        $ajaxPartialCartItemFactory->fromOrderItem($orderItem)->willReturn($ajaxPartialCartItem);

        $this->fromOrder($order)->shouldHaveType(AjaxPartialCart::class);
    }
}
