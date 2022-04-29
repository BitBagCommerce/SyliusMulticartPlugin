<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Creator\DefaultCustomerCartCreatorInterface;
use Symfony\Component\HttpFoundation\Response;

final class NewCartAction
{
    private DefaultCustomerCartCreatorInterface $cartCreator;

    public function __construct(
        DefaultCustomerCartCreatorInterface $cartCreator
    ) {
        $this->cartCreator = $cartCreator;
    }

    public function __invoke(): Response
    {
        $this->cartCreator->createNewCart();

        return new Response();
    }
}
