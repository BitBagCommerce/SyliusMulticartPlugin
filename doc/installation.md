# Installation

## Overview:
GENERAL
- [Requirements](#requirements)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
--- 
BACKEND
- [Entities](#entities)
    - [Attribute mapping](#attribute-mapping)
    - [XML mapping](#xml-mapping)
- [Repositories](#repositories)
---
FRONTEND
- [Templates](#templates)
- [Webpack](#webpack)
---
ADDITIONAL
- [Known Issues](#known-issues)
---

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version         |
|---------------|-----------------|
| PHP           | \>=8.0          |
| sylius/sylius | 1.12.x - 1.13.x |
| MySQL         | \>= 5.7         |
| NodeJS        | \>= 18.x        |

## Composer:
```bash
composer require bitbag/multicart-plugin --no-scripts
```

## Basic configuration:
Add plugin dependencies to your `config/bundles.php` file:

```php
# config/bundles.php

return [
    ...
    BitBag\SyliusMulticartPlugin\BitBagSyliusMulticartPlugin::class => ['all' => true],
];
```

Import required config in your `config/packages/_sylius.yaml` file:

```yaml
# config/packages/_sylius.yaml

imports:
    ...
    - { resource: "@BitBagSyliusMultiCartPlugin/Resources/config/config.yml" }
```

Add routing to your `config/routes.yaml` file:
```yaml
# config/routes.yaml

bitbag_sylius_multi_cart:
    resource: "@BitBagSyliusMultiCartPlugin/Resources/config/routing.yml"
```

## Entities
You can implement entity configuration by using both xml-mapping and attribute-mapping. Depending on your preference, choose either one or the other:
### Attribute mapping
- [Attribute mapping configuration](installation/attribute-mapping.md)
### XML mapping
- [XML mapping configuration](installation/xml-mapping.md)

## Repositories
Add repository with following trait:
```php
<?php
// src/Repository/OrderRepository.php

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

Override `config/packages/_sylius.yaml` configuration:
```yaml
# config/packages/_sylius.yaml

sylius_order:
    resources:
        order:
            classes:
                ...
                repository: App\Repository\OrderRepository
```

### Update your database
First, please run legacy-versioned migrations by using command:
```bash
bin/console doctrine:migrations:migrate
```

After migration, please create a new diff migration and update database:
```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

### Clear application cache by using command:
```bash
bin/console cache:clear
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

## Templates
Copy required templates into correct directories in your project.

**ShopBundle** (`templates/bundles/SyliusShopBundle`):
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

## Webpack
### Webpack.config.js

Please setup your `webpack.config.js` file to require the plugin's webpack configuration. To do so, please put the line below somewhere on top of your webpack.config.js file:
```js
const [bitbagMulticartShop] = require('./vendor/bitbag/multicart-plugin/webpack.config')
```
As next step, please add the imported consts into final module exports:
```js
module.exports = [..., bitbagMulticartShop];
```

### Assets
Add the asset configuration into `config/packages/assets.yaml`:
```yaml
framework:
    assets:
        packages:
            ...
            multicart_shop:
                json_manifest_path: '%kernel.project_dir%/public/build/bitbag/multicart/shop/manifest.json'
```

### Webpack Encore
Add the webpack configuration into `config/packages/webpack_encore.yaml`:

```yaml
webpack_encore:
    output_path: '%kernel.project_dir%/public/build/default'
    builds:
        ...
        multicart_shop: '%kernel.project_dir%/public/build/bitbag/multicart/shop'
```

### Encore functions
Add encore functions to your templates:

SyliusShopBundle:
```php
{# @SyliusShopBundle/_scripts.html.twig #}
{{ encore_entry_script_tags('bitbag-multicart-shop', null, 'multicart_shop') }}

{# @SyliusShopBundle/_styles.html.twig #}
{{ encore_entry_link_tags('bitbag-multicart-shop', null, 'multicart_shop') }}
```

### Run commands
```bash
yarn install
yarn encore dev # or prod, depends on your environment
```

## Known issues
### Translations not displaying correctly
For incorrectly displayed translations, execute the command:
```bash
bin/console cache:clear
```
