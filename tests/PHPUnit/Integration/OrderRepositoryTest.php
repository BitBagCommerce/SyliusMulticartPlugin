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

    private array $fixtures;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = $this->getContainer()->get('sylius.repository.order');
    }

    public function test_it_finds_carts():void
    {
        $this->fixtures = $this->loadFixturesFromFiles(['OrderRepositoryTest/test_it_finds_carts.yaml']);

        $carts = $this->orderRepository->findCarts(
            $this->fixtures['channel_us'],
            $this->fixtures['customer_1']
        );

        $this->assertCount(2, $carts);
    }

    public function test_it_does_not_finds_carts():void
    {
        $this->fixtures = $this->loadFixturesFromFiles(['OrderRepositoryTest/test_it_do_not_finds_carts.yaml']);

        $carts = $this->orderRepository->findCarts(
            $this->fixtures['channel_us'],
            $this->fixtures['customer_2']
        );

        $this->assertCount(0, $carts);
    }

    public function test_it_finds_carts_greater_or_equal_number_for_channel_us(): void
    {
        $this->fixtures = $this->loadFixturesFromFiles(['OrderRepositoryTest/test_it_finds_carts_greater_or_equal_number_for_channel_us.yaml']);

        $carts = $this->orderRepository->findCartsGraterOrEqualNumber(
            $this->fixtures['channel_us'],
            $this->fixtures['customer_1'],
            1
        );

        $this->assertCount(3, $carts);
    }

    public function test_it_finds_carts_greater_or_equal_number_for_channel_de(): void
    {
        $this->fixtures = $this->loadFixturesFromFiles(['OrderRepositoryTest/test_it_finds_carts_greater_or_equal_number_for_channel_de.yaml']);

        $carts = $this->orderRepository->findCartsGraterOrEqualNumber(
            $this->fixtures['channel_de'],
            $this->fixtures['customer_1'],
            1
        );

        $this->assertCount(1, $carts);
    }

    public function test_it_do_not_finds_carts_greater_or_equal_number(): void
    {
        $this->fixtures = $this->loadFixturesFromFiles(['OrderRepositoryTest/test_it_do_not_finds_carts_greater_or_equal_number.yaml']);

        $carts = $this->orderRepository->findCartsGraterOrEqualNumber(
            $this->fixtures['channel_us'],
            $this->fixtures['customer_2'],
            1
        );

        $this->assertCount(0, $carts);
    }

    public function test_it_find_latest_not_empty_active_cart(): void
    {
        $this->fixtures = $this->loadFixturesFromFiles(['OrderRepositoryTest/test_it_find_latest_not_empty_active_cart.yaml']);

        $carts = $this->orderRepository->countCarts(
            $this->fixtures['channel_us'],
            $this->fixtures['customer_1']
        );

        $this->assertEquals(3, $carts);
    }
}
