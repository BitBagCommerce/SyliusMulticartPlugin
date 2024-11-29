<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusMultiCartPlugin\Application\src\Repository;

use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository as BaseOrderRepository;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;

/** @phpstan-ignore-next-line - extends generic class */
class OrderRepository extends BaseOrderRepository implements OrderRepositoryInterface
{
    public function findCarts(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        ?string $machineId,
    ): array {
        $queryBuilder = $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
        ;

        if (null !== $customer) {
            $queryBuilder
                ->andWhere('o.customer = :customer')
                ->setParameter('customer', $customer)
            ;
        }

        if (null === $customer && null !== $machineId) {
            $queryBuilder->andWhere('o.machineId = :machineId')
                ->setParameter('machineId', $machineId)
            ;
        }

        return $queryBuilder
            ->addOrderBy('o.cartNumber', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findCartsGraterOrEqualNumber(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        int $cartNumber,
        ?string $machineId,
    ): array {
        $queryBuilder = $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.cartNumber >= :cartNumber')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('cartNumber', $cartNumber)
        ;

        if (null !== $customer) {
            $queryBuilder
                ->andWhere('o.customer = :customer')
                ->setParameter('customer', $customer)
            ;
        }

        if (null  === $customer && null !== $machineId) {
            $queryBuilder
                ->andWhere('o.machineId = :machineId')
                ->setParameter('machineId', $machineId)
            ;
        }

        return $queryBuilder
            ->addOrderBy('o.cartNumber', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBiggestCartNumber(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        ?string $machineId,
    ): int {
        $queryBuilder = $this->createQueryBuilder('o')
            ->select('MAX(o.cartNumber)')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
        ;

        if (null !== $customer) {
            $queryBuilder
                ->andWhere('o.customer = :customer')
                ->setParameter('customer', $customer)
            ;
        }

        if (null === $customer && null !== $machineId) {
            $queryBuilder
                ->andWhere('o.machineId = :machineId')
                ->setParameter('machineId', $machineId)
            ;
        }

        return (int) $queryBuilder
            ->addOrderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function countCarts(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        ?string $machineId,
    ): int {
        $queryBuilder = $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
        ;

        if (null !== $customer) {
            $queryBuilder
                ->andWhere('o.customer = :customer')
                ->setParameter('customer', $customer)
            ;
        }

        if (null === $customer && null !== $machineId) {
            $queryBuilder
                ->andWhere('o.machineId = :machineId')
                ->setParameter('machineId', $machineId)
            ;
        }

        return (int) $queryBuilder
            ->addOrderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findLatestNotEmptyActiveCart(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        ?string $machineId,
    ): ?OrderInterface {
        $queryBuilder = $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.isActive = :isActive')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('isActive', true)
        ;

        if (null !== $customer) {
            $queryBuilder
                ->andWhere('o.customer = :customer')
                ->setParameter('customer', $customer)
            ;
        }

        if (null  === $customer && null !== $machineId) {
            $queryBuilder
                ->andWhere('o.machineId = :machineId')
                ->setParameter('machineId', $machineId)
            ;
        }

        return $queryBuilder
            ->addOrderBy('o.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findActiveCart(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        ?string $machineId,
    ): ?OrderInterface {
        $queryBuilder = $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.isActive = :isActive')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('isActive', true)
        ;

        if (null !== $customer) {
            $queryBuilder
                ->andWhere('o.customer = :customer')
                ->setParameter('customer', $customer)
            ;
        }

        if (null === $customer && null !== $machineId) {
            $queryBuilder->andWhere('o.machineId = :machineId')
                ->setParameter('machineId', $machineId)
            ;
        }

        return $queryBuilder
            ->addOrderBy('o.cartNumber', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findPickedCart(
        ChannelInterface $channel,
        ?CustomerInterface $customer,
        ?string $machineId,
        int $cartNumber,
    ): ?OrderInterface {
        $queryBuilder = $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.cartNumber = :cartNumber')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('cartNumber', $cartNumber)
        ;

        if (null !== $customer) {
            $queryBuilder
                ->andWhere('o.customer = :customer')
                ->setParameter('customer', $customer)
            ;
        }

        if (null === $customer && null !== $machineId) {
            $queryBuilder->andWhere('o.machineId = :machineId')
                ->setParameter('machineId', $machineId)
            ;
        }

        return $queryBuilder
            ->addOrderBy('o.cartNumber', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
