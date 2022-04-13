<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\MoneyFormatter;

use BitBag\SyliusMultiCartPlugin\MoneyFormatter\MoneyFormatter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use BitBag\SyliusMultiCartPlugin\MoneyFormatter\MoneyFormatterInterface;
use Sylius\Component\Core\Context\ShopperContext;
use Sylius\Component\Core\Model\ChannelInterface;
use BitBag\SyliusMultiCartPlugin\MoneyConverter\MoneyConverterInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface as BaseMoneyFormatterInterface;

final class MoneyFormatterSpec extends ObjectBehavior
{
    function let(
        ShopperContext $shopperContext,
        MoneyConverterInterface $currencyConverter,
        BaseMoneyFormatterInterface $moneyFormatter
    ): void {
        $this->beConstructedWith(
            $moneyFormatter,
            $shopperContext,
            $currencyConverter
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(MoneyFormatter::class);
    }

    function it_is_implementing_interface(): void
    {
        $this->shouldHaveType(MoneyFormatterInterface::class);
    }

    function it_formats_money(
        ShopperContext $shopperContext,
        MoneyConverterInterface $currencyConverter,
        MoneyFormatterInterface $moneyFormatter
    ): void {
        $shopperContext->getCurrencyCode()->willReturn('code');

        $currencyConverter->convertMoney(Argument::type('integer'))->willReturn(100);

        $moneyFormatter->format(
            100,
            'code'
        )->willReturn('formatted_amount');

        $this->formatMoney(100)->shouldReturn('formatted_amount');
    }
}
