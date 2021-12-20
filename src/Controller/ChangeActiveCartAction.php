<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ChangeActiveCartAction
{
    private CustomerContextInterface $customerContext;

    private ObjectManager $em;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        CustomerContextInterface $customerContext,
        ObjectManager $em,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->customerContext = $customerContext;
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(string $route, int $cartNumber): Response
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();
        $customer->setActiveCart($cartNumber);

        $this->em->persist($customer);
        $this->em->flush();

        return new RedirectResponse($this->urlGenerator->generate($route));
    }
}
