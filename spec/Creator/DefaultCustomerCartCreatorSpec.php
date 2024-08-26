<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Creator;

use BitBag\SyliusMultiCartPlugin\Creator\DefaultCustomerCartCreator;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultCustomerCartCreatorSpec extends ObjectBehavior
{
    function let(
        CartContextInterface $shopBasedMultiCartContext,
        EntityManagerInterface $entityManager,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        ChannelContextInterface $channelContext,
        TranslatorInterface $translator
    ): void {
        $this->beConstructedWith(
            $shopBasedMultiCartContext,
            $entityManager,
            $customerContext,
            $orderRepository,
            $channelContext,
            $translator
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(DefaultCustomerCartCreator::class);
    }

    function it_handles_request_and_persist_new_cart_for_logged_user(
        ChannelContextInterface $channelContext,
        EntityManagerInterface $entityManager,
        CustomerContextInterface $customerContext,
        CustomerInterface $customer,
        ChannelInterface $channel,
        OrderRepositoryInterface $orderRepository,
        CartContextInterface $shopBasedMultiCartContext,
        OrderInterface $order
    ): void {
        $customerContext->getCustomer()->willReturn($customer);
        $channelContext->getChannel()->willReturn($channel);

        $orderRepository->countCarts($channel, $customer)->willReturn(1);

        $shopBasedMultiCartContext->getCart()->willReturn($order);

        $entityManager->persist($order)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $this->createNewCart()->shouldBeNull();
    }

    function it_throws_max_cart_number_has_been_reached_when_there_are_8_carts(
        CustomerContextInterface $customerContext,
        CustomerInterface $customer,
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        OrderRepositoryInterface $orderRepository
    ): void {
        $customerContext->getCustomer()->willReturn($customer);
        $channelContext->getChannel()->willReturn($channel);

        $orderRepository->countCarts($channel, $customer)->willThrow(new CartNotFoundException);

        $this->shouldThrow(CartNotFoundException::class)->during('createNewCart', []);
    }
}
