<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusMultiCartPlugin\Behat\Context\Api;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use BitBag\SyliusMultiCartPlugin\Controller\ChangeActiveCartAction;
use BitBag\SyliusMultiCartPlugin\Controller\DeleteCartAction;
use BitBag\SyliusMultiCartPlugin\Controller\NewCartAction;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

final class MultiCartContext extends RawMinkContext implements Context
{
    protected static string $domain;

    private RouterInterface $router;

    private OrderRepositoryInterface $orderRepository;

    private SharedStorageInterface $sharedStorage;

    private ChannelContextInterface $channelContext;

    private CustomerContextInterface $customerContext;

    private TokenStorageInterface $tokenStorage;

    private NewCartAction $newCartAction;

    private DeleteCartAction $deleteCartAction;

    private ChangeActiveCartAction $changeActiveCartAction;

    private CartContextInterface $cartContext;

    public function __construct(
        RouterInterface $router,
        OrderRepositoryInterface $orderRepository,
        SharedStorageInterface $sharedStorage,
        ChannelContextInterface $channelContext,
        CustomerContextInterface $customerContext,
        TokenStorageInterface $tokenStorage,
        NewCartAction $newCartAction,
        DeleteCartAction $deleteCartAction,
        ChangeActiveCartAction $changeActiveCartAction,
        CartContextInterface $cartContext,
    ) {
        $this->router = $router;
        $this->orderRepository = $orderRepository;
        $this->sharedStorage = $sharedStorage;
        $this->channelContext = $channelContext;
        $this->customerContext = $customerContext;
        $this->tokenStorage = $tokenStorage;
        $this->newCartAction = $newCartAction;
        $this->deleteCartAction = $deleteCartAction;
        $this->changeActiveCartAction = $changeActiveCartAction;
        $this->cartContext = $cartContext;
    }

    /**
     * @BeforeScenario
     */
    public function setupDomain(): void
    {
        $domain = (string) $this->getMinkParameter('base_url');
        self::$domain = trim($domain, '/');
    }

    /**
     * @Given User creates new cart for current locale code
     */
    public function userCreatesNewCart(): void
    {
        $this->newCartAction->__invoke();
    }

    /**
     * @Given User deletes :cartNumber cart for current locale code
     *
     * @throws \Exception
     */
    public function userDeletesCart(int $cartNumber): void
    {
        $this->deleteCartAction->__invoke($cartNumber);
    }

    /**
     * @Given User changes active cart to :cartNumber cart for current locale code
     */
    public function userChangesActiveCart(int $cartNumber): void
    {
        $this->changeActiveCartAction->__invoke($cartNumber);
    }

    /**
     * @Given User active cart number should be :cartNumber
     */
    public function userChangesActiveCartShouldBe(int $cartNumber): void
    {
        /** @var ShopUserInterface $user */
        $user = $this->sharedStorage->get('user');
        /** @var CustomerInterface $customer */
        $customer = $user->getCustomer();

        if ($cartNumber !== $customer->getActiveCart()) {
            throw new \Exception(
                sprintf('Current active cart number is not %s', $cartNumber),
            );
        }
    }

    /**
     * @Then User should have :number carts
     *
     * @throws \Exception
     */
    public function userShouldHaveAppropriateNumberOfCarts(int $number): void
    {
        $channel = $this->channelContext->getChannel();
        $customer = $this->customerContext->getCustomer();

        $countCarts = $this->orderRepository->countCarts($channel, $customer);

        if ($number !== $countCarts) {
            throw new \Exception(
                sprintf('Number of carts is not equal %s', $number),
            );
        }
    }

    /**
     * @Then Cart should have :number items
     */
    public function countItems(int $number): void
    {
        $count = $this->cartContext->getCart()->countItems();

        Assert::eq($count, $number);
    }

    /**
     * @Then total order items should be :number
     */
    public function countAllOrderItems(int $number): void
    {
        $channel = $this->channelContext->getChannel();
        $customer = $this->customerContext->getCustomer();

        $allCarts = $this->orderRepository->findCarts($channel, $customer);

        $allCartsItemsNumber = [];

        foreach ($allCarts as $specificCart) {
            $specificCart = $this->cartContext->getCart();
            $cartItems = $specificCart->countItems();

            $allCartsItemsNumber[] = $cartItems;
        }

        Assert::eq(array_sum($allCartsItemsNumber), $number);
    }

    /**
     * @Given /^I am authenticated with token "([^"]*)"$/
     */
    public function iAmAuthenticatedWithToken($token)
    {
        $this->sharedStorage->set('token', $token);
    }
}
