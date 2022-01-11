<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\DTO;

use BitBag\SyliusMultiCartPlugin\DTO\AjaxPartialCart;
use BitBag\SyliusMultiCartPlugin\DTO\AjaxPartialCartItem;
use PhpSpec\ObjectBehavior;

final class AjaxPartialCartSpec extends ObjectBehavior
{
    function let(
        AjaxPartialCartItem $ajaxPartialCartItem
    ): void {
        $this->beConstructedWith(
            1,
            'formatted_items_total',
            'EUR',
            [
                $ajaxPartialCartItem
            ],
            1
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AjaxPartialCart::class);
    }

    function it_gets_cart_number(): void
    {
        $this->getCartNumber()->shouldReturn(1);
    }

    function it_gets_formatted_items_total(): void
    {
        $this->getFormattedItemsTotal()->shouldReturn('formatted_items_total');
    }

    function it_gets_currency(): void
    {
        $this->getCurrency()->shouldReturn('EUR');
    }

    function it_gets_items(AjaxPartialCartItem $ajaxPartialCartItem): void
    {
        $this->getItems()->shouldReturn(
            [
                $ajaxPartialCartItem
            ]
        );
    }

    function it_gets_items_count(): void
    {
        $this->getItemsCount()->shouldReturn(1);
    }
}