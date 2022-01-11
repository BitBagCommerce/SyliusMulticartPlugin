<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Controller\ShowCartsAction;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class ShowCartsActionSpec extends ObjectBehavior
{
    function let(
        CustomerContextInterface $customerContext,
        ChannelContextInterface $channelContext,
        OrderRepositoryInterface $orderRepository,
        Environment $twig
    ): void {
        $this->beConstructedWith(
            $customerContext,
            $channelContext,
            $orderRepository,
            $twig
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ShowCartsAction::class);
    }

    function it_renders_carts_selection_for_summary_for_logged_user(
        CustomerContextInterface $customerContext,
        ChannelContextInterface $channelContext,
        OrderRepositoryInterface $orderRepository,
        Environment $twig,
        ChannelInterface $channel,
        CustomerInterface $customer,
        OrderInterface $cart
    ): void {
        $template = '@BitBagSyliusMultiCartPlugin/Shop/Cart/Summary/_carts.html.twig';
        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn($customer);

        $orderRepository->findCarts($channel, $customer)->willReturn([$cart]);

        $twig->render(
            $template,
            [
                'customer' => $customer,
                'carts' => [ $cart ],
            ]
        )->willReturn('template');

        $this->__invoke($template)->shouldHaveType(Response::class);
    }

    function it_renders_carts_selection_for_widget_for_logged_user(
        CustomerContextInterface $customerContext,
        ChannelContextInterface $channelContext,
        OrderRepositoryInterface $orderRepository,
        Environment $twig,
        ChannelInterface $channel,
        CustomerInterface $customer,
        OrderInterface $cart
    ): void {
        $template = '@BitBagSyliusMultiCartPlugin/Shop/Cart/Widget/_carts.html.twig';
        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn($customer);

        $orderRepository->findCarts($channel, $customer)->willReturn([$cart]);

        $twig->render(
            $template,
            [
                'customer' => $customer,
                'carts' => [ $cart ],
            ]
        )->willReturn('template');

        $this->__invoke($template)->shouldHaveType(Response::class);
    }

    function it_throws_cart_not_found_exception_for_anonymous_user(
        ChannelContextInterface $channelContext,
        ChannelInterface $channel,
        CustomerContextInterface $customerContext
    ): void {
        $channelContext->getChannel()->willReturn($channel);
        $customerContext->getCustomer()->willReturn(null);

        $this->shouldThrow(CartNotFoundException::class)->during('__invoke', [ 'template' ]);
    }
}
