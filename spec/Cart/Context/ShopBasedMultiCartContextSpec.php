<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Cart\Context;

use BitBag\SyliusMultiCartPlugin\Cart\Context\ShopBasedMultiCartContext;
use BitBag\SyliusMultiCartPlugin\Customizer\CartCustomizerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
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
        CartCustomizerInterface $cartCustomizer
    ): void {
        $this->beConstructedWith(
            $cartContext,
            $shopperContext,
            $cartCustomizer
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

    function it_gets_cart(
        CartContextInterface $cartContext,
        ShopperContextInterface $shopperContext,
        OrderRepositoryInterface $orderRepository,
        ChannelInterface $channel,
        CustomerInterface $customer,
        OrderInterface $cart,
        CurrencyInterface $currency
    ): void {
        $cartContext->getCart()->willReturn($cart);
        $shopperContext->getChannel()->willReturn($channel);
        $channel->getBaseCurrency()->willReturn($currency);
        $currency->getCode()->willReturn('code');
        $shopperContext->getLocaleCode()->willReturn('locale_code');
        $shopperContext->getCustomer()->willReturn($customer);

        $orderRepository->countCarts($channel, $customer)->willReturn(1);

        $this->getCart()->shouldHaveType(OrderInterface::class);
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

    function it_gets_cart_with_null_customer(
        CartContextInterface $cartContext,
        ShopperContextInterface $shopperContext,
        OrderRepositoryInterface $orderRepository,
        ChannelInterface $channel,
        OrderInterface $cart,
        CurrencyInterface $currency
    ): void {
        $cartContext->getCart()->willReturn($cart);
        $shopperContext->getChannel()->willReturn($channel);
        $channel->getBaseCurrency()->willReturn($currency);
        $currency->getCode()->willReturn('code');
        $shopperContext->getLocaleCode()->willReturn('locale_code');
        $shopperContext->getCustomer()->willReturn(null);

        $orderRepository->countCarts($channel, null)->willReturn(1);

        $this->getCart()->shouldHaveType(OrderInterface::class);
    }
}
