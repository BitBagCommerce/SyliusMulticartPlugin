<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Cart\Context;

use BitBag\SyliusMultiCartPlugin\Customizer\CartCustomizerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Currency\Context\CurrencyNotFoundException;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Webmozart\Assert\Assert;

final class ShopBasedMultiCartContext implements CartContextInterface
{
    private CartContextInterface $cartContext;

    private ShopperContextInterface $shopperContext;

    private CartCustomizerInterface $cartCustomizer;

    private ?OrderInterface $cart = null;

    public function __construct(
        CartContextInterface $cartContext,
        ShopperContextInterface $shopperContext,
        CartCustomizerInterface $cartCustomizer
    ) {
        $this->cartContext = $cartContext;
        $this->shopperContext = $shopperContext;
        $this->cartCustomizer = $cartCustomizer;
    }

    public function getCart(): BaseOrderInterface
    {
        if (null !== $this->cart) {
            return $this->cart;
        }

        $cart = $this->cartContext->getCart();
        /** @var OrderInterface $cart */
        Assert::isInstanceOf($cart, OrderInterface::class);

        try {
            /** @var ChannelInterface $channel */
            $channel = $this->shopperContext->getChannel();

            $cart->setChannel($channel);
            $cart->setCurrencyCode($channel->getBaseCurrency()->getCode());
            $cart->setLocaleCode($this->shopperContext->getLocaleCode());
        } catch (ChannelNotFoundException | CurrencyNotFoundException | LocaleNotFoundException $exception) {
            throw new CartNotFoundException('Sylius was not able to prepare the cart.', $exception);
        }

        /** @var CustomerInterface|null $customer */
        $customer = $this->shopperContext->getCustomer();

        if (null !== $customer) {
            $this->cartCustomizer->setCustomerAndAddressOnCart($cart, $customer);
            $this->cartCustomizer->setCartNumberOnCart($channel, $customer, $cart);
        }

        $this->cart = $cart;

        return $cart;
    }

    public function reset(): void
    {
        $this->cart = null;
    }
}
