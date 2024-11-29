<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Cart\Context;

use BitBag\SyliusMultiCartPlugin\Cart\Context\ShopBasedMultiCartContext;
use BitBag\SyliusMultiCartPlugin\Context\CookieContextInterface;
use BitBag\SyliusMultiCartPlugin\Customizer\CartCustomizerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Currency\Context\CurrencyNotFoundException;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;

final class ShopBasedMultiCartContextSpec extends ObjectBehavior
{
    function let(
        CartContextInterface $cartContext,
        ShopperContextInterface $shopperContext,
        CartCustomizerInterface $cartCustomizer,
        OrderRepositoryInterface $orderRepository,
        CookieContextInterface $cookieContext,
    ): void {
        $this->beConstructedWith(
            $cartContext,
            $shopperContext,
            $cartCustomizer,
            $orderRepository,
            $cookieContext,
            true,
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ShopBasedMultiCartContext::class);
    }

    function it_is_implementing_interface(): void
    {
        $this->shouldHaveType(CartContextInterface::class);
    }

    function it_gets_cart_with_logged_in_customer(
        CartContextInterface $cartContext,
        ShopperContextInterface $shopperContext,
        CartCustomizerInterface $cartCustomizer,
        OrderRepositoryInterface $orderRepository,
        OrderInterface $cart,
        ChannelInterface $channel,
        CustomerInterface $customer,
        CurrencyInterface $currency
    ): void {
        $cartContext->getCart()->willReturn($cart);
        $shopperContext->getChannel()->willReturn($channel);
        $channel->getBaseCurrency()->willReturn($currency);
        $currency->getCode()->willReturn('USD');
        $shopperContext->getLocaleCode()->willReturn('en_US');
        $shopperContext->getCustomer()->willReturn($customer);

        $cartCustomizer->copyDefaultToBillingAddress($cart, $customer)->shouldBeCalled();
        $orderRepository->findActiveCart($channel, $customer, null)->willReturn($cart);
        $cartCustomizer->increaseCartNumberOnCart($channel, $customer, $cart, null)->shouldBeCalled();

        $this->getCart()->shouldReturn($cart);
    }

    function it_gets_cart_for_anonymous_user_with_machine_id(
        CartContextInterface $cartContext,
        ShopperContextInterface $shopperContext,
        CartCustomizerInterface $cartCustomizer,
        OrderRepositoryInterface $orderRepository,
        CookieContextInterface $cookieContext,
        OrderInterface $cart,
        ChannelInterface $channel,
        CurrencyInterface $currency
    ): void {
        $cartContext->getCart()->willReturn($cart);
        $shopperContext->getChannel()->willReturn($channel);
        $channel->getBaseCurrency()->willReturn($currency);
        $currency->getCode()->willReturn('USD');
        $shopperContext->getLocaleCode()->willReturn('en_US');
        $shopperContext->getCustomer()->willReturn(null);
        $cookieContext->getMachineId()->willReturn('machine-id');

        $orderRepository->findActiveCart($channel, null, 'machine-id')->willReturn($cart);
        $cartCustomizer->increaseCartNumberOnCart($channel, null, $cart, 'machine-id')->shouldBeCalled();

        $this->getCart()->shouldReturn($cart);
    }

    function it_throws_exception_when_cart_is_null(
        CartContextInterface $cartContext
    ): void {
        $cartContext->getCart()->willThrow(new CartNotFoundException);

        $this->shouldThrow(CartNotFoundException::class)->during('getCart', []);
    }

    function it_throws_exception_when_cart_is_not_instance_of_OrderInterface(
        CartContextInterface $cartContext,
        $cart
    ): void {
        $cartContext->getCart()->willReturn($cart)->willThrow(new CartNotFoundException);

        $this->shouldThrow(CartNotFoundException::class)->during('getCart', []);
    }

    function it_throws_exception_when_channel_is_not_found(
        CartContextInterface $cartContext,
        ShopperContextInterface $shopperContext,
        OrderInterface $cart
    ): void {
        $cartContext->getCart()->willReturn($cart);
        $shopperContext->getChannel()->willThrow(new ChannelNotFoundException);

        $this->shouldThrow(CartNotFoundException::class)->during('getCart', []);
    }

    function it_throws_exception_when_currency_is_not_found(
        CartContextInterface $cartContext,
        ShopperContextInterface $shopperContext,
        ChannelInterface $channel,
        OrderInterface $cart
    ): void {
        $cartContext->getCart()->willReturn($cart);
        $shopperContext->getChannel()->willReturn($channel);
        $channel->getBaseCurrency()->willThrow(new CurrencyNotFoundException);

        $this->shouldThrow(CartNotFoundException::class)->during('getCart', []);
    }

    function it_throws_exception_when_locale_is_not_found(
        CartContextInterface $cartContext,
        ShopperContextInterface $shopperContext,
        ChannelInterface $channel,
        OrderInterface $cart
    ): void {
        $cartContext->getCart()->willReturn($cart);
        $shopperContext->getChannel()->willReturn($channel);
        $channel->getBaseCurrency()->willThrow(new CurrencyNotFoundException);
        $shopperContext->getLocaleCode()->willThrow(new LocaleNotFoundException());

        $this->shouldThrow(CartNotFoundException::class)->during('getCart', []);
    }
}
