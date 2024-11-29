<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Cart\Context;

use BitBag\SyliusMultiCartPlugin\Context\CookieContextInterface;
use BitBag\SyliusMultiCartPlugin\Customizer\CartCustomizerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Currency\Context\CurrencyNotFoundException;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

final class ShopBasedMultiCartContext implements CartContextInterface
{
    private ?OrderInterface $cart = null;

    public function __construct(
        private readonly CartContextInterface $cartContext,
        private readonly ShopperContextInterface $shopperContext,
        private readonly CartCustomizerInterface $cartCustomizer,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CookieContextInterface $cookieContext,
        private readonly TranslatorInterface $translator,
        private readonly bool $allowMulticartForAnonymous,
    ) {
    }

    public function getCart(): BaseOrderInterface
    {
        if (null !== $this->cart) {
            return $this->cart;
        }

        /** @var OrderInterface|null $cart */
        $cart = $this->cartContext->getCart();

        Assert::isInstanceOf($cart, OrderInterface::class);

        try {
            /** @var ChannelInterface $channel */
            $channel = $this->shopperContext->getChannel();

            $cart->setChannel($channel);
            /** @var CurrencyInterface $currency */
            $currency = $channel->getBaseCurrency();
            $cart->setCurrencyCode($currency->getCode());
            $cart->setLocaleCode($this->shopperContext->getLocaleCode());
        } catch (ChannelNotFoundException | CurrencyNotFoundException | LocaleNotFoundException $exception) {
            throw new CartNotFoundException(
                $this->translator->trans('bitbag_sylius_multicart_plugin.ui.sylius_was_not_able_to_prepare_the_cart'),
                $exception
            );
        }

        /** @var CustomerInterface|null $customer */
        $customer = $this->shopperContext->getCustomer();

        /** @var string|null $machineId */
        $machineId = null;

        if (null !== $customer) {
            $this->cartCustomizer->copyDefaultToBillingAddress($cart, $customer);
            $cart->setMachineId($machineId);
        }

        if (null === $customer && true === $this->allowMulticartForAnonymous) {
            $machineId = $this->cookieContext->getMachineId();
            $cart->setMachineId($machineId);
        }

        /** @var OrderInterface|null $activeCart */
        $activeCart = $this->orderRepository->findActiveCart($channel, $customer, $machineId);
        if (null !== $activeCart) {
            $activeCart->setIsActive(false);
        }

        $this->cartCustomizer->increaseCartNumberOnCart($channel, $customer, $cart, $machineId);
        $cart->setIsActive(true);
        $this->cart = $cart;

        return $cart;
    }

    public function reset(): void
    {
        $this->cart = null;
    }
}
