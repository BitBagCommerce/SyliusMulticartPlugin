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

4. Add an import to Sylius configuration:

    ```yaml
    # config/packages/_sylius.yaml
    
    imports:
    # ...
      - { resource: "@BitBagSyliusMultiCartPlugin/Resources/config/config.yml" }
    ```
5. Import routing in your `config/routes.yaml` file:

    ```yaml
    # config/routes.yaml

    bitbag_sylius_multicart_plugin:
        resource: "@BitBagSyliusMultiCartPlugin/Resources/config/routing.yml"
    ```

6. Update your database

    ```bash
    $ bin/console doctrine:migrations:migrate
    ```

7. Add plugin assets to your project

We recommend you to use Webpack (Encore), for which we have prepared four different instructions on how to add this plugin's assets to your project:

- [Import webpack config](./01.1-webpack-config.md)*
- [Add entry to existing config](./01.2-webpack-entry.md))
- [Import entries in your entry.js files](./01.3-import-entry.md))
- [Your own custom config](./01.4-custom-solution.md))

<small>* Default option for plugin development</small>


However, if you are not using Webpack, here are instructions on how to add optimized and compressed assets directly to your project templates:

- [Non webpack solution](./01.5-non-webpack.md)
