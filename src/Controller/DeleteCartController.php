<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class DeleteCartController extends AbstractController
{
    private CartContextInterface $shopBasedCartContext;

    private OrderRepositoryInterface $orderRepository;

    /**
     * @param CartContextInterface     $shopBasedCartContext
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(CartContextInterface $shopBasedCartContext, OrderRepositoryInterface $orderRepository)
    {
        $this->shopBasedCartContext = $shopBasedCartContext;
        $this->orderRepository = $orderRepository;
    }

    public function widgetAction(): Response
    {
        $cart = $this->createCart();

        return $this->render('@SyliusShop/Homepage/index.html.twig', []);
    }

    public function summaryAction(): Response
    {
        $cart = $this->createCart();

        return $this->render('@SyliusShop/Homepage/index.html.twig', []);
    }

    private function createCart()
    {
        /** @var OrderInterface $cart */
        $cart = $this->shopBasedCartContext->getCart();

        $cart->setCartNumber();

        return $cart;
    }


}
