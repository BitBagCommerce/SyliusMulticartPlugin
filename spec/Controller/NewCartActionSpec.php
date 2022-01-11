<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Controller\NewCartAction;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Symfony\Component\HttpFoundation\Response;

final class NewCartActionSpec extends ObjectBehavior
{
    function let(
        CartContextInterface $shopBasedMultiCartContext,
        EntityManagerInterface $entityManager,
        CustomerContextInterface $customerContext
    ): void {
        $this->beConstructedWith(
            $shopBasedMultiCartContext,
            $entityManager,
            $customerContext
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(NewCartAction::class);
    }

    function it_handles_request_and_persist_new_cart_for_logged_user(
        CartContextInterface $shopBasedMultiCartContext,
        EntityManagerInterface $entityManager,
        CustomerContextInterface $customerContext,
        CustomerInterface $customer,
        OrderInterface $order
    ): void {
        $customerContext->getCustomer()->willReturn($customer);
        $shopBasedMultiCartContext->getCart()->willReturn($order);

        $entityManager->persist($order)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $this->__invoke()->shouldHaveType(Response::class);
    }

    function it_throws_cart_not_found_exception_for_anonymous_user(
        CustomerContextInterface $customerContext
    ): void {
        $customerContext->getCustomer()->willReturn(null);

        $this->shouldThrow(CartNotFoundException::class)->during('__invoke', []);
    }
}