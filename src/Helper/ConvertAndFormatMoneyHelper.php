<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Helper;

use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Core\Context\ShopperContext;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Currency\Converter\CurrencyConverterInterface;

class ConvertAndFormatMoneyHelper implements ConvertAndFormatMoneyHelperInterface
{
    private ShopperContext $shopperContext;

    private CurrencyConverterInterface $currencyConverter;

    private MoneyFormatterInterface $moneyFormatter;

    public function __construct(
        ShopperContext $shopperContext,
        CurrencyConverterInterface $currencyConverter,
        MoneyFormatterInterface $moneyFormatter
    ) {
        $this->shopperContext = $shopperContext;
        $this->currencyConverter = $currencyConverter;
        $this->moneyFormatter = $moneyFormatter;
    }

    public function convertAndFormatMoney(int $amount): string
    {
        /** @var ChannelInterface $channel */
        $channel = $this->shopperContext->getChannel();
        $baseCurrency = $channel->getBaseCurrency()->getCode();
        $currencyCode = $this->shopperContext->getCurrencyCode();

        $convertedAmount = $this->currencyConverter->convert($amount, $baseCurrency, $currencyCode);

        $formattedAmount = $this->moneyFormatter->format(
            $convertedAmount,
            $currencyCode
        );

        return $formattedAmount;
    }
}
