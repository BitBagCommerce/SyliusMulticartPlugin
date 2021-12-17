<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Cart\Context;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
//use Sylius\Component\Core\Repository\OrderRepositoryInterface;
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
        $cart = $this->orderRepository->findLatestNotEmptyActiveCartByChannelAndCustomer($channel, $customer);
        if (null === $cart) {
            throw new CartNotFoundException('Sylius was not able to find the cart for currently logged in user.');
        }
//        dump('customer and channel');
        return $cart;
    }
}
