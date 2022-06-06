# BitBag SyliusMulticartPlugin
## Installation
1. *We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.*
2. Require plugin with composer:
    ```bash
    composer require bitbag/multi-cart-plugin
    ```
3. Add plugin dependencies to your `config/bundles.php` file:
    ```php
        return [
         ...
            BitBag\SyliusMulticartPlugin\BitBagSyliusMulticartPlugin::class => ['all' => true],
        ];
    ```  
4. Add traits to your Customer entity class, when You don't use annotation.
    ```php
    <?php
   
    declare(strict_types=1);
   
    namespace App\Entity\Customer;
   
    use BitBag\SyliusMulticartPlugin\Entity\CustomerInterface;
    use BitBag\SyliusMulticartPlugin\Entity\CustomerTrait;
    use Sylius\Component\Core\Model\Customer as BaseCustomer;
   
    class Customer extends BaseCustomer implements CustomerInterface
    {
        use CustomerTrait;
    }
    ```
   Or this way if you use annotations:
    ```php
    <?php
   
    declare(strict_types=1);
   
    namespace App\Entity\Customer;
   
    use Doctrine\ORM\Mapping as ORM;
    use BitBag\SyliusMulticartPlugin\Entity\CustomerInterface;
    use BitBag\SyliusMulticartPlugin\Entity\CustomerTrait;
    use Sylius\Component\Core\Model\Customer as BaseCustomer;
   
    /**
     * @ORM\Entity
     * @ORM\Table(name="sylius_customer")
     */
    class Customer extends BaseCustomer implements CustomerInterface
    {
        use CustomerTrait;
   
        /**
         * @ORM\Column(type="integer", name="active_cart", nullable=true)
         */
        protected ?int $activeCart = 1;
    }
    ```
   If you don't use annotations, define new Entity mapping inside your `src/Resources/config/doctrine` directory.
    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    
    <doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                      xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                          http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    
        <mapped-superclass name="BitBag\SyliusMultiCartPlugin\Entity\Customer" table="sylius_customer">
    
            <field name="activeCart" type="integer" nullable="true" column="active_cart"/>
    
        </mapped-superclass>
    
    </doctrine-mapping>
    ```
   For an example, check [/vendor/bitbag/multicart-plugin/src/Resources/config/doctrine/Customer.orm.xml]() file.
   Override Customer resource:

    ```yaml
    # config/packages/_sylius.yaml
    ...
    sylius_customer:
        resources:
            customer:
                classes:
                    model: App\Entity\Customer\Customer
    ```
5. Add traits to your Order entity class, when You don't use annotation.
    ```php
    <?php
   
    declare(strict_types=1);
   
    namespace App\Entity\Order;
   
    use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
    use BitBag\SyliusMultiCartPlugin\Entity\OrderTrait;
    use Sylius\Component\Core\Model\Order as BaseOrder;
    use Sylius\Component\Core\Model\Order as BaseOrder;
   
    class Order extends BaseOrder implements OrderInterface
    {
        use AbandonedEmailOrderTrait;
    }
    ```
   Or this way if you use annotations:

    ```php
    <?php
   
    declare(strict_types=1);
   
    namespace App\Entity\Order;
   
    use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
    use BitBag\SyliusMultiCartPlugin\Entity\OrderTrait;
    use Doctrine\ORM\Mapping as ORM;
    use Sylius\Component\Core\Model\Order as BaseOrder;
   
    /**
     * @ORM\Entity
     * @ORM\Table(name="sylius_order")
     */
    class Order extends BaseOrder implements OrderInterface
    {
        use OrderTrait;
   
        /**
         * @ORM\Column(type="integer", name="cart_number", nullable=true)
         */
        protected ?int $cartNumber = 1;
    }
    ```
   If you don't use annotations, define new Entity mapping inside your src/Resources/config/doctrine directory.
    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    <doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                    xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                        http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
        <mapped-superclass name="BitBag\SyliusMultiCartPlugin\Entity\Order" table="sylius_order">
            <field name="cartNumber" type="integer" nullable="true" column="cart_number"/>
        </mapped-superclass>
    </doctrine-mapping>
    ```
   Override Order resource:
    ```yaml
    # config/packages/_sylius.yaml
    ...
    sylius_order:
        resources:
            order:
                classes:
                    model: App\Entity\Order\Order
    ```
6. Add traits to your OrderItem entity class, when You don't use annotation.
    ```php
    <?php
   
    declare(strict_types=1);
   
    namespace App\Entity\Order;
   
    use BitBag\SyliusMultiCartPlugin\Entity\OrderItemInterface;
    use BitBag\SyliusMultiCartPlugin\Entity\OrderItemTrait;
    use Sylius\Component\Core\Model\OrderItem as BaseOrderItem;
   
    class OrderItem extends BaseOrderItem implements OrderItemInterface
    {
        use OrderItemTrait;
   
        public function __construct(
            int $id,
            string $name,
            int $quantity,
            string $formattedUnitPrice
        ) {
            parent::__construct();
            $this->id = $id;
            $this->quantity = $quantity;
            $this->name = $name;
            $this->formattedUnitPrice = $formattedUnitPrice;
        }
    }
    ```
   Or this way if you use annotations:
    ```php
    <?php
   
    declare(strict_types=1);
   
    namespace App\Entity\Order;
   
    use BitBag\SyliusMultiCartPlugin\Entity\OrderItemInterface;
    use BitBag\SyliusMultiCartPlugin\Entity\OrderItemTrait;
    use Doctrine\ORM\Mapping as ORM;
    use Sylius\Component\Core\Model\OrderItem as BaseOrderItem;
   
    /**
     * @ORM\Entity
     * @ORM\Table(name="sylius_order_item")
     */
    class OrderItem extends BaseOrderItem implements OrderItemInterface
    {
        use OrderItemTrait;
        
        /**
         * @ORM\Column(type="string", name="name")
         */
        private $name;
        /**
         * @ORM\Column(type="string", name="formatted_unit_price")
         */
        private string $formattedUnitPrice;
        
        public function __construct(
            int $id,
            string $name,
            int $quantity,
            string $formattedUnitPrice
        ) {
            parent::__construct();
            $this->id = $id;
            $this->quantity = $quantity;
            $this->name = $name;
            $this->formattedUnitPrice = $formattedUnitPrice;
        }
    }
    }
    ```
   If you don't use annotations, define new Entity mapping inside your src/Resources/config/doctrine directory.
    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    <doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                    xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                        http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
        <mapped-superclass name="BitBag\SyliusMultiCartPlugin\Entity\OrderItem" table="sylius_order_item">
            <field name="name" type="string" nullable="false" column="name"/>
            <field name="formattedUnitPrice" type="string" nullable="false" column="formatted_unit_price"/>
        </mapped-superclass>
    </doctrine-mapping>
    ```
   Override OrderItem resource:
    ```yaml
    # config/packages/_sylius.yaml
    ...
    sylius_order:
        resources:
            order_item:
                classes:
                    model: App\Entity\Order\OrderItem
    ```
7. Add an import to Sylius configuration:
    ```yaml
    # config/packages/_sylius.yaml
   @@ -29,7 +303,7 @@
    # ...
      - { resource: "@BitBagSyliusMultiCartPlugin/Resources/config/config.yml" }
    ```
8. Import routing in your `config/routes.yaml` file:
    ```yaml
    # config/routes.yaml
   @@ -38,13 +312,13 @@
        resource: "@BitBagSyliusMultiCartPlugin/Resources/config/routing.yml"
    ```
9. Update your database
    ```bash
    $ bin/console doctrine:migrations:migrate
    ```
10. Add plugin assets to your project
    We recommend you to use Webpack (Encore), for which we have prepared four different instructions on how to add this plugin's assets to your project:
- [Import webpack config](./01.1-webpack-config.md)*
- [Add entry to existing config](./01.2-webpack-entry.md)
- [Import entries in your entry.js files](./01.3-import-entry.md)
- [Your own custom config](./01.4-custom-solution.md)
  <small>

Default option for plugin development</small>
  
   
   However, if you are not using Webpack, here are instructions on how to add optimized and compressed assets directly to your project templates:
- [Non webpack solution](./01.5-non-webpack.md)
