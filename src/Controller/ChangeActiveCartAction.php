<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\Response;

final class ChangeActiveCartAction
{
    private CustomerContextInterface $customerContext;

    private EntityManagerInterface $entityManager;

    public function __construct(
        CustomerContextInterface $customerContext,
        EntityManagerInterface $entityManager
    ) {
        $this->customerContext = $customerContext;
        $this->entityManager = $entityManager;
    }

    public function __invoke(int $cartNumber): Response
    {
        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();

        if ($cartNumber !== $customer->getActiveCart()) {
            $customer->setActiveCart($cartNumber);

            $this->entityManager->flush();
        }

        return new Response();
    }
}
