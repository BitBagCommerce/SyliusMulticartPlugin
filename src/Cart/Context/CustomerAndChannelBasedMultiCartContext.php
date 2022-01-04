<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Cart\Context;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Sylius\Component\Order\Model\OrderInterface;

final class CustomerAndChannelBasedMultiCartContext implements CartContextInterface
{
    private CustomerContextInterface $customerContext;

    private ChannelContextInterface $channelContext;

    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        CustomerContextInterface $customerContext,
        ChannelContextInterface $channelContext,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->customerContext = $customerContext;
        $this->channelContext = $channelContext;
        $this->orderRepository = $orderRepository;
    }

    public function getCart(): OrderInterface
    {
        try {
            $channel = $this->channelContext->getChannel();
        } catch (ChannelNotFoundException $exception) {
            throw new CartNotFoundException('Sylius was not able to find the cart, as there is no current channel.');
        }

        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            throw new CartNotFoundException('Sylius was not able to find the cart, as there is no logged in user.');
        }
        $cart = $this->orderRepository->findLatestNotEmptyActiveCart($channel, $customer);
        if (null === $cart) {
            throw new CartNotFoundException('Sylius was not able to find the cart for currently logged in user.');
        }

        return $cart;
    }
}
