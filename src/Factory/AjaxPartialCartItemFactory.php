<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Factory;

use BitBag\SyliusMultiCartPlugin\DTO\AjaxPartialCartItem;
use BitBag\SyliusMultiCartPlugin\Helper\ConvertAndFormatMoneyHelperInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

class AjaxPartialCartItemFactory implements AjaxPartialCartItemFactoryInterface
{
    private ConvertAndFormatMoneyHelperInterface $convertAndFormatMoneyHelper;

    public function __construct(ConvertAndFormatMoneyHelperInterface $convertAndFormatMoneyHelper)
    {
        $this->convertAndFormatMoneyHelper = $convertAndFormatMoneyHelper;
    }

    public function fromOrderItem(OrderItemInterface $orderItem): AjaxPartialCartItem
    {
        return new AjaxPartialCartItem(
            $orderItem->getId(),
            $orderItem->getProductName(),
            $orderItem->getQuantity(),
            $this->convertAndFormatMoneyHelper->convertAndFormatMoney($orderItem->getUnitPrice()),
        );
    }
}
