<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\EventListener;

use BitBag\SyliusMultiCartPlugin\Context\CookieContextInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Uid\Uuid;

final class MachineIdListener
{
    public function __construct(
        private readonly CookieContextInterface $cookieContext,
    ) {
    }

    private const string MACHINE_ID = 'machine_id';

    private bool $hasMachineIdCookie = false;

    private bool $mainRequest = false;

    public function checkCookie(RequestEvent $event): void
    {
        $this->mainRequest = $event->isMainRequest();
        if (!$this->mainRequest) {
            return;
        }

        $request = $event->getRequest();
        $this->hasMachineIdCookie = $request->cookies->has(self::MACHINE_ID);

        if ($this->hasMachineIdCookie) {
            $machineId = (string) $request->cookies->get(self::MACHINE_ID);
            $this->cookieContext->setMachineId($machineId);

            return;
        }

        $machineId = $this->generateMachineId();
        $this->cookieContext->setMachineId($machineId);
    }

    public function setCookie(ResponseEvent $event): void
    {
        if (!$this->mainRequest) {
            return;
        }

        $response = $event->getResponse();

        if (!$this->hasMachineIdCookie) {
            $response->headers->setCookie(
                new Cookie(
                    self::MACHINE_ID,
                    $this->cookieContext->getMachineId(),
                    time() + 60 * 60 * 24 * 365,
                ),
            );
        }
    }

    private function generateMachineId(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}
