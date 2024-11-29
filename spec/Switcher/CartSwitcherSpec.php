<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Switcher;

use BitBag\SyliusMultiCartPlugin\Context\CookieContextInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use BitBag\SyliusMultiCartPlugin\Switcher\CartSwitcher;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartSwitcherSpec extends ObjectBehavior
{
    function let(
        CustomerContextInterface $customerContext,
        EntityManagerInterface $entityManager,
        OrderRepositoryInterface $orderRepository,
        ChannelContextInterface $channelContext,
        CookieContextInterface $cookieContext,
        TranslatorInterface $translator,
    ): void {
        $this->beConstructedWith(
            $customerContext,
            $entityManager,
            $orderRepository,
            $channelContext,
            $cookieContext,
            $translator,
            true,
        );
    }

    function it_throws_exception_when_anonymous_user_cannot_switch_cart(
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        CustomerContextInterface $customerContext,
        CookieContextInterface $cookieContext,
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ): void {
        $this->beConstructedWith(
            $customerContext,
            $entityManager,
            $orderRepository,
            $channelContext,
            $cookieContext,
            $translator,
            false
        );

        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn(null);

        $translator->trans('bitbag_sylius_multicart_plugin.ui.sylius_was_not_able_to_find_the_cart_as_there_is_no_logged_in_user')->willReturn('No user logged in');

        $this
            ->shouldThrow(CartNotFoundException::class)
            ->during('switchCart', [1]);
    }

    function it_switches_to_another_cart_for_logged_in_user(
        ChannelContextInterface $channelContext,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager,
        OrderInterface $activeCart,
        OrderInterface $pickedCart,
        CustomerInterface $customer,
        ChannelInterface $channel
    ): void {
        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn($customer);

        // Załóżmy, że metoda findActiveCart zwróci aktywny koszyk
        $orderRepository->findActiveCart($channel, $customer, null)->willReturn($activeCart);

        // Załóżmy, że metoda findPickedCart zwróci koszyk, na który próbujemy przejść
        $orderRepository->findPickedCart($channel, $customer, null, 2)->willReturn($pickedCart);

        // Zakładając, że aktywny koszyk i koszyk do przełączenia są różne
        $activeCart->setIsActive(false)->shouldBeCalled();
        $pickedCart->setIsActive(true)->shouldBeCalled();

        $entityManager->flush()->shouldBeCalled();

        // Wywołanie metody
        $this->switchCart(2);
    }

    function it_does_not_switch_cart_if_active_and_picked_cart_are_the_same(
        ChannelContextInterface $channelContext,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager,
        OrderInterface $activeCart,
        CustomerInterface $customer,
        ChannelInterface $channel
    ): void {
        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn($customer);

        // Załóżmy, że oba koszyki są tym samym obiektem
        $orderRepository->findActiveCart($channel, $customer, null)->willReturn($activeCart);
        $orderRepository->findPickedCart($channel, $customer, null, 1)->willReturn($activeCart);

        // Metoda nie powinna wywołać setIsActive, ponieważ koszyk już jest aktywny
        $activeCart->setIsActive(false)->shouldNotBeCalled();
        $activeCart->setIsActive(true)->shouldNotBeCalled();

        $entityManager->flush()->shouldNotBeCalled();

        // Wywołanie metody
        $this->switchCart(1);
    }
}
