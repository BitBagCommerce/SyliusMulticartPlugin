<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\MoneyFormatter;

use BitBag\SyliusMultiCartPlugin\MoneyConverter\MoneyConverterInterface;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface as BaseMoneyFormatterInterface;
use Sylius\Component\Core\Context\ShopperContext;

class MoneyFormatter implements MoneyFormatterInterface
{
    private BaseMoneyFormatterInterface $moneyFormatter;

    private MoneyConverterInterface $currencyConverter;

    private ShopperContext $shopperContext;

    public function __construct(
        BaseMoneyFormatterInterface $moneyFormatter,
        ShopperContext $shopperContext,
        MoneyConverterInterface $currencyConverter
    ) {
        $this->moneyFormatter = $moneyFormatter;
        $this->shopperContext = $shopperContext;
        $this->currencyConverter = $currencyConverter;
    }

    public function formatMoney(int $amount): string
    {
        $convertedAmount = $this->currencyConverter->convertMoney($amount);
        $currencyCode = $this->shopperContext->getCurrencyCode();


        return $this->moneyFormatter->format(
            $convertedAmount,
            $currencyCode
        );
    }
}
