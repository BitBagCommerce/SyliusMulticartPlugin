<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusMultiCartPlugin\Behat\Context\Api;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use GuzzleHttp\ClientInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class MultiCartContext extends RawMinkContext implements Context
{
    protected static string $domain;

    private ClientInterface $client;

    private RouterInterface $router;

    private OrderRepositoryInterface $orderRepository;

    private SharedStorageInterface $sharedStorage;

    private CustomerContextInterface $customerContext;

    private TokenStorageInterface $tokenStorage;

    private SessionInterface $session;


    public function __construct(
        ClientInterface $client,
        RouterInterface $router,
        OrderRepositoryInterface $orderRepository,
        SharedStorageInterface $sharedStorage,
        CustomerContextInterface $customerContext,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ) {
        $this->client = $client;
        $this->router = $router;
        $this->orderRepository = $orderRepository;
        $this->sharedStorage = $sharedStorage;
        $this->customerContext = $customerContext;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }


    /**
     * @Given User creates new cart for :localeCode locale code
     */
    public function userCreatesNewCart(string $localeCode): void
    {
        /** @var ChannelInterface $channel */
        $channel = $this->sharedStorage->get('channel');

        $serializedToken = $this->session->get('_security_shop');
//        dd(unserialize($serializedToken));

        $this->tokenStorage->setToken(unserialize($serializedToken));

//        dd($this->tokenStorage);
//        dd($this->customerContext->getCustomer());


        $defaultLocale = $channel->getDefaultLocale();

        //token storage has to have TokenInterface
//        $token = $this->sharedStorage->get('token');

//        dump($token);

        $uri = $this->router->generate(
        'bit_bag_sylius_multi_cart_plugin_new_cart',
            [
                '_locale' => $defaultLocale->getCode()
            ]
        );

        $response = $this->client->request(
            HttpRequest::METHOD_POST,
            sprintf('%s%s', self::$domain, $uri),
            [
//                'headers' => [
//                    'Authorization' => sprintf('Bearer %s', $token)
//                ]
            ]
        );

//        dump($response);

    }

    /**
     * @BeforeScenario
     */
    public function setupDomain(): void
    {
        $domain = (string) $this->getMinkParameter('base_url');
        self::$domain = trim($domain, '/');
    }
}
