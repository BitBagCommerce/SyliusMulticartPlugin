<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\Response;

final class ChangeActiveCartAction
{
    private CustomerContextInterface $customerContext;

    private ObjectManager $em;

    public function __construct(
        CustomerContextInterface $customerContext,
        ObjectManager $em
    ) {
        $this->customerContext = $customerContext;
        $this->em = $em;
    }

    public function __invoke(int $cartNumber): Response
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();

        if ($cartNumber !== $customer->getActiveCart()) {
            $customer->setActiveCart($cartNumber);

            $this->em->persist($customer);
            $this->em->flush();
        }

        return new Response();
    }
}
