<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Context\CookieContextInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class ShowCartsAction
{
    public function __construct(
        private readonly CustomerContextInterface $customerContext,
        private readonly ChannelContextInterface $channelContext,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CookieContextInterface $cookieContext,
        private readonly Environment $twig,
        private readonly bool $allowMulticartForAnonymous,
    ) {
    }

    public function __invoke(string $template): Response
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();

        /** @var string|null $machineId */
        $machineId = null;

        if (null === $customer && true === $this->allowMulticartForAnonymous) {
            $machineId = $this->cookieContext->getMachineId();
        }

        $carts = $this->orderRepository->findCarts($channel, $customer, $machineId);

        $counted = $this->orderRepository->countCarts($channel, $customer, $machineId);

        $content = $this->twig->render(
            $template,
            [
                'customer' => $customer,
                'carts' => $carts,
                'counted' => $counted,
                'machineId' => $machineId,
            ],
        );

        return new Response($content);
    }
}
