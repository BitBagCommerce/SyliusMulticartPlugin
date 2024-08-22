<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Remover;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Exception\UnableToDeleteCartException;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tests\BitBag\SyliusMultiCartPlugin\Application\src\Repository\OrderRepositoryInterface;

class CartRemover implements CartRemoverInterface
{
    private ChannelContextInterface $channelContext;

    private CustomerContextInterface $customerContext;

    private OrderRepositoryInterface $orderRepository;

    private EntityManagerInterface $entityManager;

    private TranslatorInterface $translator;

    public function __construct(
        ChannelContextInterface $channelContext,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
    ) {
        $this->channelContext = $channelContext;
        $this->customerContext = $customerContext;
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public function removeCart(int $cartNumber): void
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();
        $this->validateCustomerIsNotNull($customer);
        $this->validateRemovableCart($cartNumber, $customer);

        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();

        $carts = $this->orderRepository->findCartsGraterOrEqualNumber(
            $channel,
            $customer,
            $cartNumber,
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

    private function validateRemovableCart(int $cartNumber, CustomerInterface $customer): void
    {
        if ($cartNumber === $customer->getActiveCart()) {
            throw new UnableToDeleteCartException('bitbag_sylius_multicart_plugin.ui.cant_delete_active_cart');
        }
    }
}
