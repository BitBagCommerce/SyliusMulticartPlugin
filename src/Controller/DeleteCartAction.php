<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Remover\CartRemoverInterface;
use Symfony\Component\HttpFoundation\Response;

final class DeleteCartAction
{
    private CartRemoverInterface $remover;

    public function __construct(
        CartRemoverInterface $remover,
    ) {
        $this->remover = $remover;
    }

    public function __invoke(int $cartNumber): Response
    {
        $this->remover->removeCart($cartNumber);

        return new Response();
    }
}
