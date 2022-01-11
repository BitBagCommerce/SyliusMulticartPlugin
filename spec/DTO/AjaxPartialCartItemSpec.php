<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\DTO;

use BitBag\SyliusMultiCartPlugin\DTO\AjaxPartialCartItem;
use PhpSpec\ObjectBehavior;

final class AjaxPartialCartItemSpec extends ObjectBehavior
{
    function let(): void {
        $this->beConstructedWith(
            1,
            'item_name',
            1,
            'formatted_unit_price'
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AjaxPartialCartItem::class);
    }

    function it_gets_id(): void
    {
        $this->getId()->shouldReturn(1);
    }

    function it_gets_name(): void
    {
        $this->getName()->shouldReturn('item_name');
    }

    function it_gets_quantity(): void
    {
        $this->getQuantity()->shouldReturn(1);
    }

    function it_gets_formatted_unit_price(): void
    {
        $this->getFormattedUnitPrice()->shouldReturn('formatted_unit_price');
    }
}