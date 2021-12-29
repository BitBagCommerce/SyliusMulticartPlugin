<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class GetActiveCartAction
{
    private ChannelContextInterface $channelContext;

    private CustomerContextInterface $customerContext;

    private OrderRepositoryInterface $orderRepository;

    private SerializerInterface $serializer;

    public function __construct(
        ChannelContextInterface $channelContext,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        SerializerInterface $serializer
    ) {
        $this->channelContext = $channelContext;
        $this->customerContext = $customerContext;
        $this->orderRepository = $orderRepository;
        $this->serializer = $serializer;
    }

    public function __invoke(): Response
    {
        $channel = $this->channelContext->getChannel();
        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();

        $cart = $this->orderRepository->findLatestNotEmptyActiveCartByChannelAndCustomer($channel, $customer);

        $jsonString = $this->serializer->serialize($cart, 'json');

        dd($jsonString);

        return JsonResponse::fromJsonString($jsonString);
    }
}
