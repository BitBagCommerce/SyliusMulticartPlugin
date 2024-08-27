# XML-mapping

Check the mapping settings in `config/packages/doctrine.yaml` and, if necessary, change them accordingly.
```yaml
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

Extend entities with parameters and methods using attributes and traits:

- `Customer` entity:

```php
<?php
// src/Entity/Customer.php

declare(strict_types=1);

namespace App\Entity;

use BitBag\SyliusMultiCartPlugin\Entity\CustomerInterface;
use BitBag\SyliusMultiCartPlugin\Entity\CustomerTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

class Customer extends BaseCustomer implements CustomerInterface
{
    use CustomerTrait;
}
```

- `Order` entity:

```php
<?php
// src/Entity/Order.php

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

Define new Entity mapping inside `src/Resources/config/doctrine` directory.

- `Customer` entity:

`src/Resources/config/doctrine/Customer.orm.xml`

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

- `Order` entity:

`src/Resources/config/doctrine/Order.orm.xml`

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

Override `config/packages/_sylius.yaml` configuration:
```yaml
# config/_sylius.yaml

sylius_order:
    resources:
        order:
            classes:
                model: App\Entity\Order

sylius_customer:
    resources:
        customer:
            classes:
                model: App\Entity\Customer
```
