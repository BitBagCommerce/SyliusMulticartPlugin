<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Helper;

interface ConvertAndFormatMoneyHelperInterface
{
    public function convertAndFormatMoney(int $amount): string;
}
