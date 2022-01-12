<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusMultiCartPlugin\Behat\Context\Api;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\Routing\RouterInterface;
use Tests\BitBag\SyliusMultiCartPlugin\Behat\Page\Shop\WelcomePageInterface;
use Webmozart\Assert\Assert;

final class MultiCartContext implements Context
{
    private ClientInterface $client;

    private RouterInterface $router;

    private OrderRepositoryInterface $orderRepository;


}
