# Attribute-mapping

Check the mapping settings in `config/packages/doctrine.yaml` and, if necessary, change them accordingly.
```yaml
doctrine:
    ...
    orm:
        ...
        mappings:
            App:
                ...
                type: attribute
```

Extend entities with parameters and methods using attributes and traits:

- `Customer` entity:

```php
<?php
// src/Entity/Customer/Customer.php

declare(strict_types=1);

namespace App\Entity\Customer;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

class Customer extends BaseCustomer implements CustomerInterface
{
    use CustomerTrait;

    #[ORM\Column(name: "active_cart", type: "integer", nullable: true)]
    protected ?int $activeCart = 1;
}
```

- `Order` entity:

```php
<?php
// src/Entity/Order/Order.php

declare(strict_types=1);

namespace App\Entity\Order;

use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

class Order extends BaseOrder implements OrderInterface
{
    use OrderTrait;

    #[ORM\Column(name: "cart_number", type: "integer", nullable: true)]
    protected ?int $cartNumber = 1;
}
```
