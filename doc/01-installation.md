# BitBag SyliusMulticartPlugin
## Installation
1. *We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.*
2. Require plugin with composer:
    ```bash
    composer require bitbag/multicart-plugin --no-scripts
    ```
3. Add plugin dependencies to your `config/bundles.php` file:
    ```php
        return [
         ...
   
            BitBag\SyliusMulticartPlugin\BitBagSyliusMulticartPlugin::class => ['all' => true],
        ];
    ```  
4. Import required config in your `config/packages/_sylius.yaml` file:
    ```yaml
   # config/packages/_sylius.yaml
        ...
   
        - { resource: "@BitBagSyliusMultiCartPlugin/Resources/config/config.yml" }
    ```


5. Import routing in your `config/routes.yaml` file:

    ```yaml
    # config/routes.yaml
    ...
   
    bitbag_sylius_multi_cart:
        resource: "@BitBagSyliusMultiCartPlugin/Resources/config/routing.yml"
    ```

6. Depending on whether you use doctrine attributes or xml mapping:

   - [ATTRIBUTES](01_attribute_mapping.md)
   - [XML](01_xml_mapping.md)


7. Add OrderRepository:
```php
<?php

declare(strict_types=1);

namespace App\Repository;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Repository\OrderRepositoryInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository as BaseOrderRepository;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;

class OrderRepository extends BaseOrderRepository implements OrderRepositoryInterface
{
    public function findCarts(ChannelInterface $channel, ?CustomerInterface $customer): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.customer = :customer')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('customer', $customer)
            ->addOrderBy('o.cartNumber', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findCartsGraterOrEqualNumber(
        ChannelInterface $channel,
        CustomerInterface $customer,
        int $cartNumber,
    ): array {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.customer = :customer')
            ->andWhere('o.cartNumber >= :cartNumber')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('customer', $customer)
            ->setParameter('cartNumber', $cartNumber)
            ->addOrderBy('o.cartNumber', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBiggestCartNumber(
        ChannelInterface $channel,
        CustomerInterface $customer,
    ): int {
        return (int) $this->createQueryBuilder('o')
            ->select('MAX(o.cartNumber)')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.customer = :customer')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('customer', $customer)
            ->addOrderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function countCarts(ChannelInterface $channel, ?CustomerInterface $customer): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.customer = :customer')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('customer', $customer)
            ->addOrderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findLatestNotEmptyActiveCart(
        ChannelInterface $channel,
        CustomerInterface $customer,
    ): ?OrderInterface {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.customer = :customer')
            ->andWhere('o.cartNumber = :activeCart')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('channel', $channel)
            ->setParameter('customer', $customer)
            ->setParameter('activeCart', $customer->getActiveCart())
            ->addOrderBy('o.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
```

8. Update your database
    ```bash
    $ bin/console cache:clear
    $ bin/console doctrine:migrations:diff
    $ bin/console doctrine:migrations:migrate
    ```

9. Copy templates files from:
```
vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/summary.html.twig

vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Summary/_header.html.twig
vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Summary/_items.html.twig
vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Summary/_totals.html.twig

vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Widget/_button.html.twig
vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Widget/_popup.html.twig
vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Widget/_popup_carts.html.twig
vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Widget/_popup_items.html.twig
```

to:
```
templates/bundles/SyliusShopBundle/Cart/summary.html.twig

templates/bundles/SyliusShopBundle/Cart/Summary/_header.html.twig
templates/bundles/SyliusShopBundle/Cart/Summary/_items.html.twig
templates/bundles/SyliusShopBundle/Cart/Summary/_totals.html.twig

templates/bundles/SyliusShopBundle/Cart/Widget/_button.html.twig
templates/bundles/SyliusShopBundle/Cart/Widget/_popup.html.twig
templates/bundles/SyliusShopBundle/Cart/Widget/_popup_carts.html.twig
templates/bundles/SyliusShopBundle/Cart/Widget/_popup_items.html.twig
```   

or by Unix commands:
```
mkdir -p templates/bundles/SyliusShopBundle/Cart
mkdir -p templates/bundles/SyliusShopBundle/Cart/Summary
mkdir -p templates/bundles/SyliusShopBundle/Cart/Widget

cp vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/summary.html.twig templates/bundles/SyliusShopBundle/Cart/summary.html.twig

cp vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Summary/_header.html.twig templates/bundles/SyliusShopBundle/Cart/Summary/_header.html.twig
cp vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Summary/_items.html.twig templates/bundles/SyliusShopBundle/Cart/Summary/_items.html.twig
cp vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Summary/_totals.html.twig templates/bundles/SyliusShopBundle/Cart/Summary/_totals.html.twig

cp vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Widget/_button.html.twig templates/bundles/SyliusShopBundle/Cart/Widget/_button.html.twig
cp vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Widget/_popup.html.twig templates/bundles/SyliusShopBundle/Cart/Widget/_popup.html.twig
cp vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Widget/_popup_carts.html.twig templates/bundles/SyliusShopBundle/Cart/Widget/_popup_carts.html.twig
cp vendor/bitbag/multicart-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Widget/_popup_items.html.twig templates/bundles/SyliusShopBundle/Cart/Widget/_popup_items.html.twig
```  

10. Add plugin assets to your project
- [Import webpack config](./01.1-webpack-config.md)

