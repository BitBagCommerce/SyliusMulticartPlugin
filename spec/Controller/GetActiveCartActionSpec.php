<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Controller\DeleteCartAction;
use BitBag\SyliusMultiCartPlugin\Controller\GetActiveCartAction;
use BitBag\SyliusMultiCartPlugin\DTO\AjaxPartialCart;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Factory\AjaxPartialCartFactoryInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

final class GetActiveCartActionSpec extends ObjectBehavior
{
    function let(
        CartContextInterface $cartContext,
        SerializerInterface $serializer,
        AjaxPartialCartFactoryInterface $ajaxPartialCartFactory
    ): void {
        $this->beConstructedWith(
            $cartContext,
            $serializer,
            $ajaxPartialCartFactory
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(GetActiveCartAction::class);
    }

    function it_handles_request_and_gets_json_partial_cart_for_logged_user(
        CartContextInterface $cartContext,
        OrderInterface $cart,
        AjaxPartialCartFactoryInterface $ajaxPartialCartFactory,
        SerializerInterface $serializer,
        AjaxPartialCart $ajaxPartialCart
    ): void {
        $cartContext->getCart()->willReturn($cart);
        $ajaxPartialCartFactory->fromOrder($cart)->willReturn($ajaxPartialCart);
        $cart->countItems()->willReturn(2);

        $serializer->serialize($ajaxPartialCart, 'json')->willReturn('{ "test": "string" }');

        $this->__invoke()->shouldHaveType(JsonResponse::class);
    }
}
