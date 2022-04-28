<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\MoneyConverter;

use BitBag\SyliusMultiCartPlugin\MoneyConverter\MoneyConverter;
use BitBag\SyliusMultiCartPlugin\MoneyConverter\MoneyConverterInterface;
use BitBag\SyliusMultiCartPlugin\MoneyFormatter\MoneyFormatter;
use BitBag\SyliusMultiCartPlugin\MoneyFormatter\MoneyFormatterInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface as BaseMoneyFormatterInterface;
use Sylius\Component\Core\Context\ShopperContext;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Currency\Converter\CurrencyConverterInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;

class MoneyConverterSpec extends ObjectBehavior
{
    function let(
        ShopperContext $shopperContext,
        CurrencyConverterInterface $currencyConverter
    ): void {
        $this->beConstructedWith(
            $shopperContext,
            $currencyConverter
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(MoneyConverter::class);
    }

    function it_is_implementing_interface(): void
    {
        $this->shouldHaveType(MoneyConverterInterface::class);
    }

    function it_converts_money(
        ShopperContext $shopperContext,
        ChannelInterface $channel,
        CurrencyInterface $currency,
        CurrencyConverterInterface $currencyConverter
    ): void {
        $shopperContext->getChannel()->willReturn($channel);
        $channel->getBaseCurrency()->willReturn($currency);
        $currency->getCode()->willReturn('code');
        $shopperContext->getCurrencyCode()->willReturn('code');

        $currencyConverter->convert(
            100,
            Argument::type('string'),
            Argument::type('string')
        )->willReturn(400);

        $this->convertMoney(100)->shouldReturn(400);
    }
}
