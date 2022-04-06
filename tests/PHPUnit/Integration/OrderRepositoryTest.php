<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusMultiCartPlugin\PHPUnit\Integration;

use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;

class OrderRepositoryTest extends IntegrationTestCase
{
    private OrderRepositoryInterface $orderRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = $this->getContainer()->get('sylius.repository.order');
    }

    public function test_that_finds_carts():void
    {
        $fixtures = $this->loadFixturesFromFiles(['OrderRepositoryTest/test_that_finds_carts.yaml']);

        $carts = $this->orderRepository->findCarts($fixtures['channel_us'], $fixtures['customer']);

        $this->assertCount(2, $carts);
    }

    public function test_that_finds_carts_greater_or_equal_number(): void
    {
        $fixtures = $this->loadFixturesFromFiles(['OrderRepositoryTest/test_that_finds_carts_greater_or_equal_number.yaml']);

        $carts = $this->orderRepository->findCartsGraterOrEqualNumber($fixtures['channel_us'], $fixtures['customer'], 2);

        $this->assertCount(3, $carts);
    }

    public function test_that_counts_carts():void
    {
        $fixtures = $this->loadFixturesFromFiles(['OrderRepositoryTest/test_that_counts_carts.yaml']);

        $carts = $this->orderRepository->countCarts($fixtures['channel_us'], $fixtures['customer']);

        $this->assertEquals(3, $carts);
    }

    public function test_that_find_latest_not_empty_active_cart(): void
    {
        $fixtures = $this->loadFixturesFromFiles(['OrderRepositoryTest/test_that_find_latest_not_empty_active_cart.yaml']);

        $carts = $this->orderRepository->countCarts($fixtures['channel_us'], $fixtures['customer']);

        $this->assertEquals(4, $carts);
    }
}
