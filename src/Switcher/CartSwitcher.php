<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Switcher;

use BitBag\SyliusMultiCartPlugin\Context\CookieContextInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartSwitcher implements CartSwitcherInterface
{
    public function __construct(
        private readonly CustomerContextInterface $customerContext,
        private readonly EntityManagerInterface $entityManager,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ChannelContextInterface $channelContext,
        private readonly CookieContextInterface $cookieContext,
        private readonly TranslatorInterface $translator,
        private readonly bool $allowMulticartForAnonymous,
    ) {
    }

    public function switchCart(int $cartNumber): void
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();

        if (null === $customer && false === $this->allowMulticartForAnonymous) {
            throw new CartNotFoundException(
                $this->translator->trans('bitbag_sylius_multicart_plugin.ui.sylius_was_not_able_to_find_the_cart_as_there_is_no_logged_in_user'),
            );
        }

        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();

        /** @var string|null $machineId */
        $machineId = null;

        if ((null === $customer && true === $this->allowMulticartForAnonymous)) {
            $machineId = $this->cookieContext->getMachineId();
        }

        /** @var OrderInterface $activeCart */
        $activeCart = $this->orderRepository->findActiveCart($channel, $customer, $machineId);

        /** @var OrderInterface $pickedCart */
        $pickedCart = $this->orderRepository->findPickedCart($channel, $customer, $machineId, $cartNumber);

        if ($pickedCart === $activeCart) {
            return;
        }

        $activeCart->setIsActive(false);
        $pickedCart->setIsActive(true);

        $this->entityManager->flush();
    }
}
