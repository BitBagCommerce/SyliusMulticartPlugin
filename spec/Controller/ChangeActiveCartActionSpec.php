<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Controller\ChangeActiveCartAction;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Component\HttpFoundation\Response;

final class ChangeActiveCartActionSpec extends ObjectBehavior
{
    function let(
        CustomerContextInterface $customerContext,
        EntityManagerInterface $entityManager
    ): void {
        $this->beConstructedWith(
            $customerContext,
            $entityManager
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ChangeActiveCartAction::class);
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

        $this->__invoke($cartNumber)->shouldHaveType(Response::class);
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

        $this->__invoke($cartNumber)->shouldHaveType(Response::class);
    }

    function it_throws_cart_not_found_exception_for_anonymous_user(
        CustomerContextInterface $customerContext
    ): void {
        $customerContext->getCustomer()->willReturn(null);

        $this->shouldThrow(CartNotFoundException::class)->during('__invoke', [ 1 ]);
    }
}