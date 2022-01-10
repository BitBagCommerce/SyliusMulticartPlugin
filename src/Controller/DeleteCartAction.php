<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\Response;

final class DeleteCartAction
{
    private ChannelContextInterface $channelContext;

    private CustomerContextInterface $customerContext;

    private OrderRepositoryInterface $orderRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        ChannelContextInterface $channelContext,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->channelContext = $channelContext;
        $this->customerContext = $customerContext;
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(int $cartNumber): Response
    {
        $channel = $this->channelContext->getChannel();
        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();

        if ($cartNumber === $customer->getActiveCart()) {
            throw new \Exception('Cant delete active cart!');
        }

        $carts =  $this->orderRepository->findCartsGraterOrEqualNumber(
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
            $cart->setCartNumber($cartNumber + $key - 1);
        }

        $this->entityManager->flush();

        return new Response();
    }
}
