<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Factory;

use BitBag\SyliusMultiCartPlugin\Helper\ConvertAndFormatMoneyHelperInterface;

abstract class AbstractAjaxPartialFactory
{
    private ConvertAndFormatMoneyHelperInterface $convertAndFormatMoneyHelper;

    public function __construct(ConvertAndFormatMoneyHelperInterface $convertAndFormatMoneyHelper)
    {
        $this->convertAndFormatMoneyHelper = $convertAndFormatMoneyHelper;
    }
}
