<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Entity;

use BitBag\SyliusMultiCartPlugin\Entity\Customer;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use PhpSpec\ObjectBehavior;

final class CustomerSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Customer::class);
    }

    function it_is_implementing_customer_interface(): void
    {
        $this->shouldHaveType(CustomerInterface::class);
    }

    function it_sets_and_gets_active_cart(): void
    {
        $this->setActiveCart(1);

        $this->getActiveCart()->shouldReturn(1);
    }
}
