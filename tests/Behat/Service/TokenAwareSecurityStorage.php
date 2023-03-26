<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusMultiCartPlugin\Behat\Service;

use Sylius\Behat\Service\SecurityServiceInterface;
use Sylius\Behat\Service\Setter\CookieSetterInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

final class TokenAwareSecurityStorage implements SecurityServiceInterface
{
    private RequestStack $requestStack;

    private CookieSetterInterface $cookieSetter;

    private TokenStorageInterface $tokenStorage;

    private string $sessionTokenVariable;

    private string $firewallContextName;

    public function __construct(
        RequestStack $requestStack,
        CookieSetterInterface $cookieSetter,
        TokenStorageInterface $tokenStorage,
        string $firewallContextName) {
        $this->requestStack = $requestStack;
        $this->cookieSetter = $cookieSetter;
        $this->tokenStorage = $tokenStorage;
        $this->sessionTokenVariable = sprintf('_security_%s', $firewallContextName);
        $this->firewallContextName = $firewallContextName;
    }

    public function logIn(UserInterface $user): void
    {
        /** @deprecated parameter credential was deprecated in Symfony 5.4, so in Sylius 1.11 too, in Sylius 2.0 providing 4 arguments will be prohibited. */
        if (3 === (new \ReflectionClass(UsernamePasswordToken::class))->getConstructor()->getNumberOfParameters()) {
            $token = new UsernamePasswordToken($user, $this->firewallContextName, $user->getRoles());
        } else {
            $token = new UsernamePasswordToken($user, $user->getPassword(), $this->firewallContextName, $user->getRoles());
        }

        $this->setToken($token);
    }

    public function logOut(): void
    {
        $this->requestStack->getSession()->set($this->sessionTokenVariable, null);
        $this->requestStack->getSession()->save();

        $this->cookieSetter->setCookie($this->requestStack->getSession()->getName(), $this->requestStack->getSession()->getId());
    }

    public function getCurrentToken(): TokenInterface
    {
        $serializedToken = $this->requestStack->getSession()->get($this->sessionTokenVariable);

        if (null === $serializedToken) {
            throw new TokenNotFoundException();
        }

        return unserialize($serializedToken);
    }

    public function restoreToken(TokenInterface $token): void
    {
        $this->setToken($token);
    }

    private function setToken(TokenInterface $token)
    {
        $serializedToken = serialize($token);
        $this->requestStack->getSession()->set($this->sessionTokenVariable, $serializedToken);
        $this->requestStack->getSession()->save();
        $this->cookieSetter->setCookie($this->requestStack->getSession()->getName(), $this->requestStack->getSession()->getId());
        $this->tokenStorage->setToken($token);
    }
}
