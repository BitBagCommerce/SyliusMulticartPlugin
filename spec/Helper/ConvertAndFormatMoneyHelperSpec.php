<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Helper;

use BitBag\SyliusMultiCartPlugin\Helper\ConvertAndFormatMoneyHelper;
use BitBag\SyliusMultiCartPlugin\Helper\ConvertAndFormatMoneyHelperInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Core\Context\ShopperContext;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Currency\Converter\CurrencyConverterInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;

final class ConvertAndFormatMoneyHelperSpec extends ObjectBehavior
{
    function let(
        ShopperContext $shopperContext,
        CurrencyConverterInterface $currencyConverter,
        MoneyFormatterInterface $moneyFormatter
    ): void {
        $this->beConstructedWith(
            $shopperContext,
            $currencyConverter,
            $moneyFormatter
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ConvertAndFormatMoneyHelper::class);
    }

    function it_is_implementing_interface(): void
    {
        $this->shouldHaveType(ConvertAndFormatMoneyHelperInterface::class);
    }

    function it_converts_and_formats_money(
        ShopperContext $shopperContext,
        ChannelInterface $channel,
        CurrencyInterface $currency,
        CurrencyConverterInterface $currencyConverter,
        MoneyFormatterInterface $moneyFormatter
    ): void {
        $shopperContext->getChannel()->willReturn($channel);
        $channel->getBaseCurrency()->willReturn($currency);
        $currency->getCode()->willReturn('code');
        $shopperContext->getCurrencyCode()->willReturn('code');

        $currencyConverter->convert(
            Argument::type('integer'),
            Argument::type('string'),
            Argument::type('string')
        )->willReturn(100);

        $moneyFormatter->format(
            Argument::type('integer'),
            Argument::type('string'),
        )->willReturn('formatted_amount');

        $this->convertAndFormatMoney(100)->shouldReturn('formatted_amount');
    }
}
