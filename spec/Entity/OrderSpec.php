<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Entity;

use BitBag\SyliusMultiCartPlugin\Entity\Order;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use PhpSpec\ObjectBehavior;

final class OrderSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Order::class);
    }

    function it_is_implementing_order_interface(): void
    {
        $this->shouldHaveType(OrderInterface::class);
    }

    function it_sets_and_gets_cart_number(): void
    {
        $this->setCartNumber(1);

        $this->getCartNumber()->shouldReturn(1);
    }
}
