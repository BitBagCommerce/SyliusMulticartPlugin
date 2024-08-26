## XML-mapping entities extension 

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
                type: xml
                dir: '%kernel.project_dir%/src/Resources/config/doctrine'
```

### Extending entities:
#### Extending Customer entity:

```php
<?php

declare(strict_types=1);

namespace App\Entity;

use BitBag\SyliusMulticartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMulticartPlugin\Entity\CustomerTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

class Customer extends BaseCustomer implements CustomerInterface
{
    use CustomerTrait;
}
```


#### Extending Order entity:
```php
<?php

declare(strict_types=1);

namespace App\Entity;

use BitBag\SyliusMultiCartPlugin\Entity\OrderInterface;
use BitBag\SyliusMultiCartPlugin\Entity\OrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

class Order extends BaseOrder implements OrderInterface
{
    use OrderTrait;
}
```

#### Define new Entity mapping inside your  `src/Resources/config/doctrine` directory.

- CUSTOMER

```xml
<?xml version="1.0" encoding="UTF-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                      http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    <mapped-superclass name="App\Entity\Customer" table="sylius_customer">
        <field name="activeCart" type="integer" nullable="true" column="active_cart" />
    </mapped-superclass>
</doctrine-mapping>
```

- ORDER
```xml
<?xml version="1.0" encoding="UTF-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                      http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    <mapped-superclass name="Tests\BitBag\SyliusMultiCartPlugin\Application\src\Entity\Order" table="sylius_order">
        <field name="cartNumber" type="integer" nullable="true" column="cart_number" />
    </mapped-superclass>
</doctrine-mapping>
```

````yaml
sylius_order:
    resources:
        order:
            classes:
                model: App\Entity\Order
                repository: App\Repository\OrderRepository

sylius_customer:
    resources:
        customer:
            classes:
                model: App\Entity\Customer

````
