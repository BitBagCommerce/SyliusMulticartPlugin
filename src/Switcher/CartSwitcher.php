<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Switcher;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartSwitcher implements CartSwitcherInterface
{
    private CustomerContextInterface $customerContext;

    private EntityManagerInterface $entityManager;

    private TranslatorInterface $translator;

    public function __construct(
        CustomerContextInterface $customerContext,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
    ) {
        $this->customerContext = $customerContext;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public function switchCart(int $cartNumber): void
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            throw new CartNotFoundException(
                $this->translator->trans('bitbag_sylius_multicart_plugin.ui.sylius_was_not_able_to_find_the_cart_as_there_is_no_logged_in_user'),
            );
        }

        if ($cartNumber !== $customer->getActiveCart()) {
            $customer->setActiveCart($cartNumber);

            $this->entityManager->flush();
        }
    }
}
