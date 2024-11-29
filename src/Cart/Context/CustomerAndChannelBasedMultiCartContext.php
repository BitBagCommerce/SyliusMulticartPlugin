<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Cart\Context;

use BitBag\SyliusMultiCartPlugin\Context\CookieContextInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Sylius\Component\Order\Model\OrderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CustomerAndChannelBasedMultiCartContext implements CartContextInterface
{
    public function __construct(
        private readonly CustomerContextInterface $customerContext,
        private readonly ChannelContextInterface $channelContext,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CookieContextInterface $cookieContext,
        private readonly TranslatorInterface $translator,
        private readonly bool $allowMulticartForAnonymous,
    ) {
    }

    public function getCart(): OrderInterface
    {
        try {
            /** @var ChannelInterface $channel */
            $channel = $this->channelContext->getChannel();
        } catch (ChannelNotFoundException $exception) {
            throw new CartNotFoundException(
                $this->translator->trans('bitbag_sylius_multicart_plugin.ui.sylius_was_not_able_to_find_the_cart_as_there_is_no_current_channel'),
            );
        }

        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();

        if (null === $customer && false === $this->allowMulticartForAnonymous) {
            throw new CartNotFoundException(
                $this->translator->trans('bitbag_sylius_multicart_plugin.ui.sylius_was_not_able_to_find_the_cart_as_there_is_no_logged_in_user'),
            );
        }

        /** @var string|null $machineId */
        $machineId = null;

        if ((null === $customer && true === $this->allowMulticartForAnonymous)) {
            $machineId = $this->cookieContext->getMachineId();
        }

        $cart = $this->orderRepository->findLatestNotEmptyActiveCart($channel, $customer, $machineId);
        if (null === $cart) {
            throw new CartNotFoundException(
                $this->translator->trans('bitbag_sylius_multicart_plugin.ui.sylius_was_not_able_to_find_the_cart_for_currently_logged_in_user'),
            );
        }

        return $cart;
    }
}
