<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class ShowCartsController extends AbstractController
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

    public function __invoke(): Response
    {
        $channel = $this->channelContext->getChannel();
        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();
        $carts = $this->orderRepository->findCarts($channel, $customer);

        return $this->render(
            '@BitBagSyliusMultiCartPlugin/Shop/_show_carts.html.twig',
            [
                'customer' => $customer,
                'carts' => $carts,
            ]
        );
    }
}
