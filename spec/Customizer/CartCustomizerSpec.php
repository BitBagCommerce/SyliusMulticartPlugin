<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\Customizer;

use BitBag\SyliusMultiCartPlugin\Customizer\CartCustomizer;
use BitBag\SyliusMultiCartPlugin\Customizer\CartCustomizerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\ChannelInterface;

class CartCustomizerSpec extends ObjectBehavior
{
    function let(
        OrderRepositoryInterface $orderRepository
    ): void {
        $this->beConstructedWith(
            $orderRepository
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CartCustomizer::class);
    }

    function it_is_implementing_interface(): void
    {
        $this->shouldHaveType(CartCustomizerInterface::class);
    }

    function it_sets_customer_and_address_on_cart(
        ChannelInterface $channel,
        CustomerInterface $customer,
        OrderInterface $cart,
        AddressInterface $defaultAddress
    ): void {
        $cart->setCustomer($customer)->shouldBeCalled();
        $customer->getDefaultAddress()->willReturn($defaultAddress);

        $cart->setBillingAddress($defaultAddress)->shouldBeCalled();
        $this->setCustomerAndAddressOnCart($cart, $customer)->shouldBeNull();
    }

    function it_sets_customer_without_address_on_cart(
        ChannelInterface $channel,
        CustomerInterface $customer,
        OrderInterface $cart,
        AddressInterface $defaultAddress
    ): void {
        $cart->setCustomer($customer)->shouldBeCalled();
        $customer->getDefaultAddress()->willReturn(null);

        $this->setCustomerAndAddressOnCart($cart, $customer)->shouldBeNull();
    }

    function it_sets_cart_number_on_cart(
        ChannelInterface $channel,
        CustomerInterface $customer,
        OrderInterface $cart,
        OrderRepositoryInterface $orderRepository
    ): void {
        $orderRepository->findBiggestCartNumber($channel, $customer)->willReturn(3);
        $cart->setCartNumber(4)->shouldBeCalled();

        $this->setCartNumberOnCart($channel, $customer, $cart)->shouldBeNull();
    }
}
