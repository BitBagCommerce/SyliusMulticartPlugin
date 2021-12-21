<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NewCartAction
{
    private CartContextInterface $shopBasedMultiCartContext;

    private ObjectManager $em;

    private UrlGeneratorInterface $urlGenerator;

    private CustomerContextInterface $customerContext;

    public function __construct(
        CartContextInterface $shopBasedMultiCartContext,
        ObjectManager $em,
        UrlGeneratorInterface $urlGenerator,
        CustomerContextInterface $customerContext
    ) {
        $this->shopBasedMultiCartContext = $shopBasedMultiCartContext;
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
        $this->customerContext = $customerContext;
    }

    public function __invoke(string $route): Response
    {
        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            throw new CartNotFoundException('Sylius was not able to find the cart, as there is no logged in user.');
        }

        $cart = $this->shopBasedMultiCartContext->getCart();

        $this->em->persist($cart);
        $this->em->flush();

        return new RedirectResponse($this->urlGenerator->generate($route));
    }
}
