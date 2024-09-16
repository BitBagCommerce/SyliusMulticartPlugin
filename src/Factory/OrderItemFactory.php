<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Factory;

use BitBag\SyliusMultiCartPlugin\MoneyFormatter\MoneyFormatterInterface;
use BitBag\SyliusMultiCartPlugin\View\OrderItemView;
use Sylius\Component\Core\Model\OrderItemInterface;

class OrderItemFactory implements OrderItemFactoryInterface
{
    private MoneyFormatterInterface $convertAndFormatMoneyHelper;

    public function __construct(MoneyFormatterInterface $convertAndFormatMoneyHelper)
    {
        $this->convertAndFormatMoneyHelper = $convertAndFormatMoneyHelper;
    }

    public function fromOrderItem(OrderItemInterface $orderItem): OrderItemView
    {
        /** @var string $productName */
        $productName = $orderItem->getProductName();

        return new OrderItemView(
            $orderItem->getId(),
            $productName,
            $orderItem->getQuantity(),
            $this->convertAndFormatMoneyHelper->formatMoney($orderItem->getUnitPrice()),
        );
    }
}
