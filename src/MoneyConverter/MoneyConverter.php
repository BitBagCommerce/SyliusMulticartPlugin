<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\MoneyConverter;

use Sylius\Component\Core\Context\ShopperContext;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Currency\Converter\CurrencyConverterInterface;

class MoneyConverter implements MoneyConverterInterface
{
    private ShopperContext $shopperContext;

    private CurrencyConverterInterface $currencyConverter;

    public function __construct(
        ShopperContext $shopperContext,
        CurrencyConverterInterface $currencyConverter
    ) {
        $this->shopperContext = $shopperContext;
        $this->currencyConverter = $currencyConverter;
    }

    public function convertMoney(int $amount): int
    {
        /** @var ChannelInterface $channel */
        $channel = $this->shopperContext->getChannel();
        $baseCurrency = $channel->getBaseCurrency()->getCode();
        $currencyCode = $this->shopperContext->getCurrencyCode();

        return $this->currencyConverter->convert(
            $amount,
            $baseCurrency,
            $currencyCode
        );
    }
}
