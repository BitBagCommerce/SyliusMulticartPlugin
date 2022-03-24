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
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class ShowCartsAction
{
    private CustomerContextInterface $customerContext;

    private ChannelContextInterface $channelContext;

    private OrderRepositoryInterface $orderRepository;

    private Environment $twig;

    private CartContextInterface $cartContext;

    public function __construct(
        CustomerContextInterface $customerContext,
        ChannelContextInterface $channelContext,
        OrderRepositoryInterface $orderRepository,
        Environment $twig,
        CartContextInterface $cartContext
    ) {
        $this->customerContext = $customerContext;
        $this->channelContext = $channelContext;
        $this->orderRepository = $orderRepository;
        $this->twig = $twig;
        $this->cartContext = $cartContext;
    }

    public function __invoke(string $template): Response
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();

        $carts = $this->orderRepository->findCarts($channel, $customer);

        $counted = $this->orderRepository->countCarts($channel, $customer);

        $content = $this->twig->render(
            $template,
            [
                'customer' => $customer,
                'carts' => $carts,
                'counted' => $counted,
            ]
        );

        $cart = $this->cartContext->getCart();

        if ($cart->countItems() === 0) {
            return new Response($content, Response::HTTP_NO_CONTENT);
        }

        return new Response($content);
    }
}
