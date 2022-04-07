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
use BitBag\SyliusMulticartPlugin\Exception\UnableToDeleteCartException;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;

class CartRemover implements CartRemoverInterface
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

    public function removeCart(int $cartNumber): void
    {
        $channel = $this->channelContext->getChannel();
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            throw new CartNotFoundException(
                'Sylius was not able to find the cart, as there is no logged in user.'
            );
        }

        if ($cartNumber === $customer->getActiveCart()) {
            throw new UnableToDeleteCartException('Cant delete active cart!');
        }

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
            $cart->setCartNumber($cartNumber + $key - 1);
        }

        $this->entityManager->flush();
    }
}