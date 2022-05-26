<?php

namespace BitBag\SyliusMultiCartPlugin\Switcher;

interface CartSwitcherInterface
{
    public function switchCart(int $cartNumber): void;
}
