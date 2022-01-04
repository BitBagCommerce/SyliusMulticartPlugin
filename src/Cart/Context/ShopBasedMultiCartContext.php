<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Cart\Context;

use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
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

    private OrderRepositoryInterface $orderRepository;

    private ?OrderInterface $cart = null;

    public function __construct(
        CartContextInterface $cartContext,
        ShopperContextInterface $shopperContext,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->cartContext = $cartContext;
        $this->shopperContext = $shopperContext;
        $this->orderRepository = $orderRepository;
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
            $this->setCustomerAndAddressOnCart($cart, $customer);
            $this->setCartNumberOnCart($channel, $customer, $cart);
        }

        $this->cart = $cart;

        return $cart;
    }

    private function setCustomerAndAddressOnCart(OrderInterface $cart, CustomerInterface $customer): void
    {
        $cart->setCustomer($customer);

        $defaultAddress = $customer->getDefaultAddress();
        if (null !== $defaultAddress) {
            $clonedAddress = clone $defaultAddress;
            $clonedAddress->setCustomer(null);
            $cart->setBillingAddress($clonedAddress);
        }
    }

    private function setCartNumberOnCart(ChannelInterface $channel,CustomerInterface $customer, OrderInterface $cart): void
    {
        $counter = $this->orderRepository->countCarts($channel, $customer);
        $cart->setCartNumber($counter + 1);
    }

    public function reset(): void
    {
        $this->cart = null;
    }
}
