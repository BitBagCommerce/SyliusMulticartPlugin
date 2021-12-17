<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class ChangeActiveCartController extends AbstractController
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(CustomerInterface $customer, OrderInterface $order, string $route): Response
    {
        $cartNUmber = $order->getCartNumber();
        $customer->setActiveCart($cartNUmber);

        $this->em->persist($customer);
        $this->em->flush();

        return $this->render('@SyliusShop/Homepage/index.html.twig', []);
    }
}
