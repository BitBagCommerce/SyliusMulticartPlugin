<?php

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
