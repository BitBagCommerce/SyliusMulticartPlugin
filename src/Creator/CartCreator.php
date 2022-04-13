<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Creator;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CartCreator implements CartCreatorInterface
{
    private CartContextInterface $shopBasedMultiCartContext;

    private EntityManagerInterface $entityManager;

    private CustomerContextInterface $customerContext;

    private OrderRepositoryInterface $orderRepository;

    private ChannelContextInterface $channelContext;

    private TranslatorInterface $translator;

    public function __construct(
        CartContextInterface $shopBasedMultiCartContext,
        EntityManagerInterface $entityManager,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        ChannelContextInterface $channelContext,
        TranslatorInterface $translator
    ) {
        $this->shopBasedMultiCartContext = $shopBasedMultiCartContext;
        $this->entityManager = $entityManager;
        $this->customerContext = $customerContext;
        $this->orderRepository = $orderRepository;
        $this->channelContext = $channelContext;
        $this->translator = $translator;
    }

    public function createNewCart(): void
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();

        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();

        $carts = $this->orderRepository->countCarts($channel, $customer);

        if ($carts === 8) {
            throw new CartNotFoundException(
                $this->translator->trans('bitbag_sylius_multicart_plugin.ui.max_cart_number_reached')
            );
        }

        $cart = $this->shopBasedMultiCartContext->getCart();

        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }
}
