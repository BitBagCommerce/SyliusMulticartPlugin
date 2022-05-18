<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Customizer;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class CartCustomizer implements CartCustomizerInterface
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
    }

    public function setCustomerAndAddressOnCart(OrderInterface $cart, CustomerInterface $customer): void
    {
        $cart->setCustomer($customer);

        $defaultAddress = $customer->getDefaultAddress();
        if (null !== $defaultAddress) {
            $clonedAddress = clone $defaultAddress;
            $clonedAddress->setCustomer(null);
            $cart->setBillingAddress($clonedAddress);
        }
    }

    public function setCartNumberOnCart(
        ChannelInterface $channel,
        CustomerInterface $customer,
        OrderInterface $cart
    ): void {
        $counter = $this->orderRepository->findBiggestCartNumber($channel, $customer);

        $cart->setCartNumber($counter + 1);
    }
}
