<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Creator\CartCreatorInterface;
use Symfony\Component\HttpFoundation\Response;

final class NewCartAction
{
    private CartCreatorInterface $cartCreator;

    public function __construct(
        CartCreatorInterface $cartCreator
    ) {
        $this->cartCreator = $cartCreator;
    }

    public function __invoke(): Response
    {
        $this->cartCreator->createNewCart();

        return new Response();
    }
}
