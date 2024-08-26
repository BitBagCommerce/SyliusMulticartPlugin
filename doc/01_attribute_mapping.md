## Attribute-mapping entities extension

### Configuration
Remember to mark it appropriately in the config/doctrine.yaml configuration file.
```
doctrine:
    ...
    orm:
        ...
        mappings:
            App:
                ...
                type: attribute
                ...
```

### Extending entities:
#### Extending Customer entity:

```php
<?php

declare(strict_types=1);

namespace App\Entity\Customer;

use BitBag\SyliusMulticartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMulticartPlugin\Entity\CustomerTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

class Customer extends BaseCustomer implements CustomerInterface
{
    #[ORM\Column(name: "active_cart", type: "integer", nullable: true)]
    protected ?int $activeCart = 1;

    public function getActiveCart(): ?int
    {
        return $this->activeCart;
    }

    public function setActiveCart(?int $activeCart): void
    {
        $this->activeCart = $activeCart;
    }
}
```


#### Extending Order entity:
```php
<?php

declare(strict_types=1);

namespace App\Entity\Order;

use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

class Order extends BaseOrder implements OrderInterface
{
    #[ORM\Column(name: "cart_number", type: "integer", nullable: true)]
    protected ?int $cartNumber = 1;

    #[ORM\Column(type: 'integer', nullable: true)]
    protected ?int $bonusPoints = null;
    
    public function getCartNumber(): ?int
    {
        return $this->cartNumber;
    }

    public function setCartNumber(?int $cartNumber): void
    {
        $this->cartNumber = $cartNumber;
    }
}
```

config/packages/_sylius.yaml (only adding repository)

````yaml
sylius_order:
    resources:
        order:
            classes:
                model: App\Entity\Order\Order
                repository: App\Repository\OrderRepository
````
