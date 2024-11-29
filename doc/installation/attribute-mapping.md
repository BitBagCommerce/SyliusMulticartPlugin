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

- `Order` entity:

```php
<?php
// src/Entity/Order/Order.php

declare(strict_types=1);

namespace App\Entity\Order;

use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Model\OrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

class Order extends BaseOrder implements OrderInterface
{
    use OrderTrait;

    #[ORM\Column(name: "cart_number", type: "integer", nullable: true)]
    protected ?int $cartNumber = 1;
}
```
