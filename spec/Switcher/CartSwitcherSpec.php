<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Switcher;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Switcher\CartSwitcher;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartSwitcherSpec extends ObjectBehavior
{
    function let(
        CustomerContextInterface $customerContext,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ): void {
        $this->beConstructedWith(
            $customerContext,
            $entityManager,
            $translator
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CartSwitcher::class);
    }

    function it_handles_request_and_persist_changed_active_cart_for_logged_user(
        EntityManagerInterface $entityManager,
        CustomerContextInterface $customerContext,
        CustomerInterface $customer
    ): void {
        $cartNumber = 2;
        $customerContext->getCustomer()->willReturn($customer);

        $customer->getActiveCart()->willReturn(1);
        $customer->setActiveCart($cartNumber)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $this->switchCart($cartNumber)->shouldBeNull();
    }

    function it_handles_request_and_skips_change_of_active_cart_for_logged_user(
        EntityManagerInterface $entityManager,
        CustomerContextInterface $customerContext,
        CustomerInterface $customer
    ): void {
        $cartNumber = 1;
        $customerContext->getCustomer()->willReturn($customer);

        $customer->getActiveCart()->willReturn(1);
        $customer->setActiveCart($cartNumber)->shouldNotBeCalled();
        $entityManager->flush()->shouldNotBeCalled();

        $this->switchCart($cartNumber)->shouldBeNull();
    }

    function it_throws_cart_not_found_exception_for_anonymous_user(
        CustomerContextInterface $customerContext
    ): void {
        $customerContext->getCustomer()->willReturn(null);

        $this->shouldThrow(CartNotFoundException::class)->during('switchCart', [ 1 ]);
    }
}
