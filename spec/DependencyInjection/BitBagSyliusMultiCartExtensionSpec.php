<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusMultiCartPlugin\DependencyInjection;

use BitBag\SyliusMultiCartPlugin\DependencyInjection\BitBagSyliusMultiCartExtension;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class BitBagSyliusMultiCartExtensionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(BitBagSyliusMultiCartExtension::class);
    }

    function it_is_extending_symfony_dependency_injection_extension()
    {
        $this->shouldHaveType(Extension::class);
    }
}