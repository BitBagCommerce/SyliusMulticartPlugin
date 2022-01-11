<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Cart\Context;

use BitBag\SyliusMultiCartPlugin\Cart\Context\CustomerAndChannelBasedMultiCartContext;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;

final class CustomerAndChannelBasedMultiCartContextSpec extends ObjectBehavior
{
    function let(
        CustomerContextInterface $customerContext,
        ChannelContextInterface $channelContext,
        OrderRepositoryInterface $orderRepository
    ): void {
        $this->beConstructedWith(
            $customerContext,
            $channelContext,
            $orderRepository
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CustomerAndChannelBasedMultiCartContext::class);
    }

    function it_is_implementing_interface(): void
    {
        $this->shouldHaveType(CartContextInterface::class);
    }

    function it_gets_cart(
        CustomerContextInterface $customerContext,
        ChannelContextInterface $channelContext,
        OrderRepositoryInterface $orderRepository,
        ChannelInterface $channel,
        CustomerInterface $customer,
        OrderInterface $cart
    ): void {
        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn($customer);
        $orderRepository->findLatestNotEmptyActiveCart($channel, $customer)->willReturn($cart);

        $this->getCart()->shouldHaveType(OrderInterface::class);
    }

    function it_throws_exception_for_missing_channel(
        ChannelContextInterface $channelContext,
        ChannelNotFoundException $exception
    ): void {
        $channelContext->getChannel()->willThrow($exception);

        $this->shouldThrow(CartNotFoundException::class)->during('getCart', []);
    }

    function it_throws_exception_for_missing_customer(
        CustomerContextInterface $customerContext,
        ChannelContextInterface $channelContext,
        ChannelInterface $channel
    ): void {
        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn(null);

        $this->shouldThrow(CartNotFoundException::class)->during('getCart', []);
    }

    function it_throws_exception_for_missing_cart(
        CustomerContextInterface $customerContext,
        ChannelContextInterface $channelContext,
        OrderRepositoryInterface $orderRepository,
        ChannelInterface $channel,
        CustomerInterface $customer
    ): void {
        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn($customer);
        $orderRepository->findLatestNotEmptyActiveCart($channel, $customer)->willReturn(null);

        $this->shouldThrow(CartNotFoundException::class)->during('getCart', []);
    }
}
