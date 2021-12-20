<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Repository;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface as BaseOrderRepositoryInterface;

interface OrderRepositoryInterface extends BaseOrderRepositoryInterface
{
    public function findCartsByChannelAndCustomer(ChannelInterface $channel, CustomerInterface $customer): array;

    public function findCartByChannelAndCustomerAndCartNumber(
        ChannelInterface $channel,
        CustomerInterface $customer,
        int $cartNumber
    ): ?OrderInterface;

    public function countCartsByChannelAndCustomer(ChannelInterface $channel, CustomerInterface $customer): int;

    public function findLatestNotEmptyActiveCartByChannelAndCustomer(ChannelInterface $channel, CustomerInterface $customer): ?OrderInterface;
}