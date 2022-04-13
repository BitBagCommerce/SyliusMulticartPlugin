<?php

namespace BitBag\SyliusMultiCartPlugin\Customizer;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use Sylius\Component\Core\Model\ChannelInterface;

interface CartCustomizerInterface
{
    public function setCustomerAndAddressOnCart(OrderInterface $cart, CustomerInterface $customer): void;

    public function setCartNumberOnCart(ChannelInterface $channel, CustomerInterface $customer, OrderInterface $cart): void;

}
