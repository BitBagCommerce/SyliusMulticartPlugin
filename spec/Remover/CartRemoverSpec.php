<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Remover;

use BitBag\SyliusMultiCartPlugin\Context\CookieContextInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Exception\UnableToDeleteCartException;
use BitBag\SyliusMultiCartPlugin\Remover\CartRemover;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartRemoverSpec extends ObjectBehavior
{
    function let(
        ChannelContextInterface $channelContext,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager,
        CookieContextInterface $cookieContext,
        TranslatorInterface $translator
    ): void {
        $this->beConstructedWith(
            $channelContext,
            $customerContext,
            $orderRepository,
            $entityManager,
            $cookieContext,
            $translator,
            true
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CartRemover::class);
    }

    function it_removes_cart_for_logged_in_user(
        CustomerContextInterface $customerContext,
        CustomerInterface $customer,
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        OrderRepositoryInterface $orderRepository,
        OrderInterface $activeCart,
        OrderInterface $cartToRemove,
        EntityManagerInterface $entityManager,
    ): void {
        $customerContext->getCustomer()->willReturn($customer);
        $channelContext->getChannel()->willReturn($channel);

        $orderRepository->findActiveCart($channel, $customer, null)->willReturn($activeCart);
        $activeCart->getCartNumber()->willReturn(1);

        $orderRepository->findCartsGraterOrEqualNumber($channel, $customer, 2, null)->willReturn([$cartToRemove]);
        $cartToRemove->getCartNumber()->willReturn(2);

        $entityManager->remove($cartToRemove)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $this->removeCart(2);
    }

    function it_removes_cart_for_anonymous_user_with_allow_multicart_enabled(
        CustomerContextInterface $customerContext,
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        CookieContextInterface $cookieContext,
        OrderRepositoryInterface $orderRepository,
        OrderInterface $activeCart,
        OrderInterface $cartToRemove,
        EntityManagerInterface $entityManager
    ): void {
        $customerContext->getCustomer()->willReturn(null);
        $channelContext->getChannel()->willReturn($channel);
        $cookieContext->getMachineId()->willReturn('unique-machine-id');

        $orderRepository->findActiveCart($channel, null, 'unique-machine-id')->willReturn($activeCart);
        $activeCart->getCartNumber()->willReturn(1);

        $orderRepository->findCartsGraterOrEqualNumber($channel, null, 2, 'unique-machine-id')->willReturn([$cartToRemove]);
        $cartToRemove->getCartNumber()->willReturn(2);

        $entityManager->remove($cartToRemove)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $this->removeCart(2);
    }

    function it_throws_exception_when_anonymous_user_cannot_use_multicart(
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        CustomerContextInterface $customerContext,
        CookieContextInterface $cookieContext,
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
    ): void {
        $this->beConstructedWith(
            $channelContext,
            $customerContext,
            $orderRepository,
            $entityManager,
            $cookieContext,
            $translator,
            false
        );

        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn(null);

        $this
            ->shouldThrow(CartNotFoundException::class)
            ->during('removeCart', [1]);
    }

    function it_throws_exception_when_trying_to_remove_active_cart(
        CustomerContextInterface $customerContext,
        CustomerInterface $customer,
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        OrderRepositoryInterface $orderRepository,
        OrderInterface $activeCart
    ): void {
        $customerContext->getCustomer()->willReturn($customer);
        $channelContext->getChannel()->willReturn($channel);

        $orderRepository->findActiveCart($channel, $customer, null)->willReturn($activeCart);
        $activeCart->getCartNumber()->willReturn(1);

        $this
            ->shouldThrow(UnableToDeleteCartException::class)
            ->during('removeCart', [1]);
    }
}
