<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Creator;

use BitBag\SyliusMultiCartPlugin\Context\CookieContextInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

final class DefaultCustomerCartCreator implements DefaultCustomerCartCreatorInterface
{
    private const int MAX_CART_COUNT = 8;

    public function __construct(
        private readonly CartContextInterface $shopBasedMultiCartContext,
        private readonly EntityManagerInterface $entityManager,
        private readonly CustomerContextInterface $customerContext,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ChannelContextInterface $channelContext,
        private readonly CookieContextInterface $cookieContext,
        private readonly TranslatorInterface $translator,
        private readonly bool $allowMulticartForAnonymous,
    ) {
    }

    public function createNewCart(): void
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();

        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();

        /** @var string|null $machineId */
        $machineId = null;

        if ((null === $customer && true === $this->allowMulticartForAnonymous)) {
            $machineId = $this->cookieContext->getMachineId();
        }

        $carts = $this->orderRepository->countCarts($channel, $customer, $machineId);

        if (self::MAX_CART_COUNT === $carts) {
            throw new CartNotFoundException(
                $this->translator->trans('bitbag_sylius_multicart_plugin.ui.max_cart_number_reached'),
            );
        }

        $cart = $this->shopBasedMultiCartContext->getCart();

        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }
}
