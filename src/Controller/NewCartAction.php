<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Component\HttpFoundation\Response;

final class NewCartAction
{
    private CartContextInterface $shopBasedMultiCartContext;

    private EntityManagerInterface $entityManager;

    private CustomerContextInterface $customerContext;

    private OrderRepositoryInterface $orderRepository;

    private ChannelContextInterface $channelContext;

    public function __construct(
        CartContextInterface $shopBasedMultiCartContext,
        EntityManagerInterface $entityManager,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        ChannelContextInterface $channelContext
    ) {
        $this->shopBasedMultiCartContext = $shopBasedMultiCartContext;
        $this->entityManager = $entityManager;
        $this->customerContext = $customerContext;
        $this->orderRepository = $orderRepository;
        $this->channelContext = $channelContext;
    }

    public function __invoke(): Response
    {
        $channel = $this->channelContext->getChannel();

        $customer = $this->customerContext->getCustomer();

        if (null === $customer) {
            throw new CartNotFoundException(
                'Sylius was not able to find the cart, as there is no logged in user.'
            );
        }

        $carts = $this->orderRepository->countCarts($channel, $customer);

        if ($carts === 8) {
            throw new CartNotFoundException(
                'Max cart number reached'
            );
        }

        $cart = $this->shopBasedMultiCartContext->getCart();

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return new Response();
    }
}
