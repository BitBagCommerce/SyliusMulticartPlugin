<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Component\HttpFoundation\Response;

final class NewCartAction
{
    private CartContextInterface $shopBasedMultiCartContext;

    private EntityManagerInterface $entityManager;

    private CustomerContextInterface $customerContext;

    public function __construct(
        CartContextInterface $shopBasedMultiCartContext,
        EntityManagerInterface $entityManager,
        CustomerContextInterface $customerContext
    ) {
        $this->shopBasedMultiCartContext = $shopBasedMultiCartContext;
        $this->entityManager = $entityManager;
        $this->customerContext = $customerContext;
    }

    public function __invoke(): Response
    {
        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            throw new CartNotFoundException(
                'Sylius was not able to find the cart, as there is no logged in user.'
            );
        }

        $cart = $this->shopBasedMultiCartContext->getCart();

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return new Response();
    }
}
