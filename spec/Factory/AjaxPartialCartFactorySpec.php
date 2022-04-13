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
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\OrderItem as BaseOrderItem;
use BitBag\SyliusMultiCartPlugin\Entity\OrderItem;

final class AjaxPartialCartFactorySpec extends ObjectBehavior
{
    function let(
        MoneyFormatterInterface             $convertAndFormatMoneyHelper,
        OrderItemFactoryInterface $orderItemFactory
    ): void {
        $this->beConstructedWith(
            $convertAndFormatMoneyHelper,
            $orderItemFactory
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
        Collection                $itemsCollection,
        MoneyFormatterInterface   $convertAndFormatMoneyHelper,
        OrderInterface            $order,
        BaseOrderItem             $baseOrderItem,
        OrderItemFactoryInterface $orderItemFactory,
        OrderItem       $orderItem
    ): void {
        $order->getItems()->willReturn($itemsCollection);
        $itemsCollection->toArray()->willReturn([$baseOrderItem]);

        $order->getCartNumber()->shouldBeCalled();
        $order->getItemsTotal()->shouldBeCalled();
        $convertAndFormatMoneyHelper->formatMoney(Argument::type('integer'))->willReturn('converted_string');
        $order->getCurrencyCode()->shouldBeCalled();

        $orderItemFactory->fromOrderItem($baseOrderItem)->willReturn($orderItem);

        $this->fromOrder($order)->shouldHaveType(AjaxPartialCart::class);
    }
}
