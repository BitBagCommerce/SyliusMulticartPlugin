<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Context;

final class CookieContext implements CookieContextInterface
{
    private ?string $machineId = null;

    public function getMachineId(): ?string
    {
        return $this->machineId;
    }

    public function setMachineId(?string $machineId): void
    {
        $this->machineId = $machineId;
    }
}
