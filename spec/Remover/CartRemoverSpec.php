<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Remover;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Exception\UnableToDeleteCartException;
use BitBag\SyliusMultiCartPlugin\Remover\CartRemover;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tests\BitBag\SyliusMultiCartPlugin\Application\src\Repository\OrderRepositoryInterface;

class CartRemoverSpec extends ObjectBehavior
{
    function let(
        ChannelContextInterface $channelContext,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ): void {
        $this->beConstructedWith(
            $channelContext,
            $customerContext,
            $orderRepository,
            $entityManager,
            $translator
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CartRemover::class);
    }

    function it_handles_request_and_deletes_cart_for_logged_user(
        ChannelContextInterface $channelContext,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager,
        CustomerInterface $customer,
        ChannelInterface $channel,
        OrderInterface $cart
    ): void {
        $cartNumber = 1;
        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn($customer);
        $customer->getActiveCart()->willReturn(2);

        $orderRepository->findCartsGraterOrEqualNumber($channel, $customer, $cartNumber)->willReturn([$cart]);
        $cart->getCartNumber()->willReturn(1);

        $entityManager->remove($cart)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $this->removeCart($cartNumber)->shouldBeNull();
    }

    function it_stops_the_handle_request_due_to_null_cart_number(
        ChannelContextInterface $channelContext,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        CustomerInterface $customer,
        ChannelInterface $channel,
        OrderInterface $cart
    ): void {
        $cartNumber = 1;
        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn($customer);
        $customer->getActiveCart()->willReturn(2);

        $orderRepository->findCartsGraterOrEqualNumber($channel, $customer, $cartNumber)->willReturn([$cart]);
        $cart->getCartNumber()->willReturn(null);

        $this->removeCart($cartNumber)->shouldBeNull();
    }

    function it_throws_cart_not_found_exception_for_anonymous_user(
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        CustomerContextInterface $customerContext
    ): void {
        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn(null);

        $this->shouldThrow(CartNotFoundException::class)->during('removeCart', [ 1 ]);
    }

    function it_throws_exception_for_removing_active_cart(
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        CustomerContextInterface $customerContext,
        CustomerInterface $customer
    ): void {
        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn($customer);
        $customer->getActiveCart()->willReturn(1);

        $this->shouldThrow(UnableToDeleteCartException::class)->during('removeCart', [ 1 ]);
    }
}
