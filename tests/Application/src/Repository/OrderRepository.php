<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusMultiCartPlugin\Application\src\Repository;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository as BaseOrderRepository;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;

/** @phpstan-ignore-next-line - extends generic class */
class OrderRepository extends BaseOrderRepository implements OrderRepositoryInterface
{
    public function findCarts(ChannelInterface $channel, ?CustomerInterface $customer): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.customer = :customer')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('customer', $customer)
            ->addOrderBy('o.cartNumber', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findCartsGraterOrEqualNumber(
        ChannelInterface $channel,
        CustomerInterface $customer,
        int $cartNumber,
    ): array {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.customer = :customer')
            ->andWhere('o.cartNumber >= :cartNumber')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('customer', $customer)
            ->setParameter('cartNumber', $cartNumber)
            ->addOrderBy('o.cartNumber', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBiggestCartNumber(
        ChannelInterface $channel,
        CustomerInterface $customer,
    ): int {
        return (int) $this->createQueryBuilder('o')
            ->select('MAX(o.cartNumber)')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.customer = :customer')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('customer', $customer)
            ->addOrderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function countCarts(ChannelInterface $channel, ?CustomerInterface $customer): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.customer = :customer')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('customer', $customer)
            ->addOrderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findLatestNotEmptyActiveCart(
        ChannelInterface $channel,
        CustomerInterface $customer,
    ): ?OrderInterface {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.customer = :customer')
            ->andWhere('o.cartNumber = :activeCart')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('customer', $customer)
            ->setParameter('activeCart', $customer->getActiveCart())
            ->addOrderBy('o.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
