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
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Bundle\CoreBundle\SectionResolver\SectionProviderInterface;
use Sylius\Bundle\ShopBundle\EventListener\ShopCartBlamerListener as BaseShopCartBlamerListener;
use Sylius\Bundle\ShopBundle\SectionResolver\ShopSection;
use Sylius\Bundle\UserBundle\Event\UserEvent;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

final class ShopCartBlamerListener
{
    public function __construct(
        private readonly BaseShopCartBlamerListener $decoratedListener,
        private readonly CartContextInterface $cartContext,
        private readonly SectionProviderInterface $uriBasedSectionContext,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ChannelContextInterface $channelContext,
        private readonly CookieContextInterface $cookieContext,
        private readonly bool $allowMulticartForAnonymous,
    ) {
    }

    public function onImplicitLogin(UserEvent $userEvent): void
    {
        $this->decoratedListener->onImplicitLogin($userEvent);
    }

    public function onInteractiveLogin(InteractiveLoginEvent $interactiveLoginEvent): void
    {
        $section = $this->uriBasedSectionContext->getSection();
        if (!$section instanceof ShopSection) {
            return;
        }

        $user = $interactiveLoginEvent->getAuthenticationToken()->getUser();
        if (!$user instanceof ShopUserInterface) {
            return;
        }

        $this->blame($user);
    }

    private function blame(ShopUserInterface $user): void
    {
        $customer = null;

        if ($this->allowMulticartForAnonymous) {
            /** @var ChannelInterface $channel */
            $channel = $this->channelContext->getChannel();

            $machineId = $this->cookieContext->getMachineId();
            $carts = $this->orderRepository->findCarts($channel, $customer, $machineId);

            /** @var CustomerInterface|null $customer */
            $customer = $user->getCustomer();

            foreach ($carts as $cart) {
                $cart->setCustomerWithAuthorization($customer);
                $cart->setMachineId(null);
            }

            return;
        }

        $cart = $this->getCart();

        if (null === $cart || null !== $cart->getCustomer()) {
            return;
        }
        $cart->setCustomerWithAuthorization($customer);
    }

    /**
     * @throws UnexpectedTypeException
     */
    private function getCart(): ?OrderInterface
    {
        try {
            $cart = $this->cartContext->getCart();
        } catch (CartNotFoundException) {
            return null;
        }

        if (!$cart instanceof OrderInterface) {
            throw new UnexpectedTypeException($cart, OrderInterface::class);
        }

        return $cart;
    }
}
