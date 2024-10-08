<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class CartContentAction
{
    private CartContextInterface $cartContext;

    private Environment $twig;

    public function __construct(CartContextInterface $cartContext, Environment $twig)
    {
        $this->cartContext = $cartContext;
        $this->twig = $twig;
    }

    public function __invoke(): Response
    {
        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();

        $templates = [
            'ajaxButton' => '@SyliusShop/Cart/Widget/_button.html.twig',
            'popupCarts' => '@SyliusShop/Cart/Widget/_popup_carts.html.twig',
            'popupItems' => '@SyliusShop/Cart/Widget/_popup_items.html.twig',
            'summaryTotals' => '@SyliusShop/Cart/Summary/_totals.html.twig',
        ];

        $content = $this->prepareContentArray($templates, $cart);

        return new JsonResponse($content);
    }

    private function prepareContentArray(array $templates, OrderInterface $cart): array
    {
        $array = [];

        foreach ($templates as $key => $template) {
            $array[$key] = $this->prepareContent($template, $cart);
        }

        return $array;
    }

    private function prepareContent(string $template, OrderInterface $cart): string
    {
        return $this->twig->render(
            $template,
            [
                'cart' => $cart,
            ],
        );
    }
}
