<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Remover;

use BitBag\SyliusMultiCartPlugin\Context\CookieContextInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Exception\UnableToDeleteCartException;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartRemover implements CartRemoverInterface
{
    public function __construct(
        private readonly ChannelContextInterface $channelContext,
        private readonly CustomerContextInterface $customerContext,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly CookieContextInterface $cookieContext,
        private readonly TranslatorInterface $translator,
        private readonly bool $allowMulticartForAnonymous,
    ) {
    }

    public function removeCart(int $cartNumber): void
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();

        if (!$this->allowMulticartForAnonymous) {
            $this->validateCustomerIsNotNull($customer);
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

        $activeCartNumber = $activeCart->getCartNumber();
        $this->validateRemovableCart($cartNumber, $activeCartNumber);

        $carts = $this->orderRepository->findCartsGraterOrEqualNumber(
            $channel,
            $customer,
            $cartNumber,
            $machineId,
        );

        /**
         * @var int $key
         * @var OrderInterface $cart
         */
        foreach ($carts as $key => $cart) {
            if ($cartNumber === $cart->getCartNumber()) {
                $this->entityManager->remove($cart);
            }
        }

        $this->entityManager->flush();
    }

    private function validateCustomerIsNotNull(?CustomerInterface $customer): void
    {
        if (null === $customer) {
            throw new CartNotFoundException(
                $this->translator->trans('bitbag_sylius_multicart_plugin.ui.sylius_was_not_able_to_find_the_cart_as_there_is_no_logged_in_user'),
            );
        }
    }

    private function validateRemovableCart(int $cartNumber, ?int $activeCartNumber): void
    {
        if ($cartNumber === $activeCartNumber) {
            throw new UnableToDeleteCartException(
                $this->translator->trans('bitbag_sylius_multicart_plugin.ui.cant_delete_active_cart'),
            );
        }
    }
}
