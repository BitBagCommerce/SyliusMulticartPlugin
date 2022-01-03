## dev usage

1. Run `composer install` from root directory.

2. From the plugin skeleton root directory, run the following commands:

    ```bash
    $ (composer install)
    $ (cd tests/Application && yarn install)
    $ (cd tests/Application && yarn build)
    $ (cd tests/Application && APP_ENV=dev bin/console assets:install public)
    $ (APP_ENV=dev symfony server:start --port=8080 --dir=tests/Application/public --daemon)
    $ (cd tests/Application && yarn watch)
    
    $ (cd tests/Application && APP_ENV=dev bin/console doctrine:database:create)
    $ (cd tests/Application && APP_ENV=dev bin/console doctrine:schema:create)
    $ (cd tests/Application && APP_ENV=dev bin/console doctrine:migration:migrate)
    $ (cd tests/Application && APP_ENV=dev bin/console sylius:fixtures:load)
    ```

To be able to setup a plugin's database, remember to configure you database credentials in `tests/Application/.env` and `tests/Application/.env.test`.

## Usage

