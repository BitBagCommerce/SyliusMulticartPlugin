<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Factory\AjaxPartialCartFactoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class GetActiveCartAction
{
    private CartContextInterface $cartContext;

    private SerializerInterface $serializer;

    private AjaxPartialCartFactoryInterface $ajaxPartialCartFactory;

    public function __construct(
        CartContextInterface $cartContext,
        SerializerInterface $serializer,
        AjaxPartialCartFactoryInterface $ajaxPartialCartFactory
    ) {
        $this->cartContext = $cartContext;
        $this->serializer = $serializer;
        $this->ajaxPartialCartFactory = $ajaxPartialCartFactory;
    }

    public function __invoke(): Response
    {
        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();

        $ajaxPartialCart = $this->ajaxPartialCartFactory->fromOrder($cart);

        $jsonString = $this->serializer->serialize($ajaxPartialCart, 'json');

        return new JsonResponse($jsonString, 200, [], true);
    }
}
