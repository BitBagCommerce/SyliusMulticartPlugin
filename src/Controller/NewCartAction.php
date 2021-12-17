<?php

declare(strict_types=1);

namespace BitBag\SyliusMultiCartPlugin\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NewCartAction extends AbstractController
{
    private CartContextInterface $shopBasedMultiCartContext;

    private ObjectManager $em;

    /**
     * @param CartContextInterface $shopBasedMultiCartContext
     * @param ObjectManager        $em
     */
    public function __construct(CartContextInterface $shopBasedMultiCartContext, ObjectManager $em)
    {
        $this->shopBasedMultiCartContext = $shopBasedMultiCartContext;
        $this->em = $em;
    }


    public function newAction(string $route): Response
    {
//        dd($route);
        $cart = $this->shopBasedMultiCartContext->getCart();

//        $em = $this->container->get('doctrine.orm.entity_manager');
//        $em->persist($cart);
//        $em->flush();

        $this->em->persist($cart);
        $this->em->flush();

        return $this->redirectToRoute($route);
    }
}
