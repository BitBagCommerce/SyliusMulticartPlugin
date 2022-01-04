<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class NewCartAction
{
    private CartContextInterface $shopBasedMultiCartContext;

    private ObjectManager $em;

    private CustomerContextInterface $customerContext;

    public function __construct(
        CartContextInterface $shopBasedMultiCartContext,
        ObjectManager $em,
        CustomerContextInterface $customerContext
    ) {
        $this->shopBasedMultiCartContext = $shopBasedMultiCartContext;
        $this->em = $em;
        $this->customerContext = $customerContext;
    }

    public function __invoke(): Response
    {
        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            throw new CartNotFoundException('Sylius was not able to find the cart, as there is no logged in user.');
        }

        $cart = $this->shopBasedMultiCartContext->getCart();

        $this->em->persist($cart);
        $this->em->flush();

        return new Response();
    }
}
