# XCP Core

This is a Laravel package that takes Counterparty federated node API data and imports it to a local database, so that it can be used in a more familiar manner and we can introduce novel queries.

## Install

In your project, update your ```composer.json``` to reference the ```droplister/xcp-core``` repository and require it, like so...

> ...
>    "repositories": [
>      {
>        "type": "git",
>        "url": "https://github.com/droplister/xcp-core.git"
>      }
>    ],
>    "require": {
>        "php": "^7.1.3",
>        "fideloper/proxy": "^4.0",
>        "laravel/framework": "5.6.*",
>        "laravel/tinker": "^1.0",
>        "laravel/horizon": "^1.3",
>        "droplister/xcp-core": "dev-master"
>    },
> ...

Now, you can run ```composer update``` to install these dependencies. Don't forget to use ```php artisan vendor:publish``` to publish the ```xcp-core.php``` configuration file.

## Setup

Before using in production, it's necessary to sync your local database to be caught up with the federated node API's latest block. We can do that with console commands.

1. Run ```php artisan migrate``` to build the tables neccessary.

2. Run ```php artisan db:seed --class=Droplister\\XcpCore\\Database\\Seeds\\DatabaseSeeder``` to seed.

3. Run ```update:blocks```. It will begin a sync and should run until completed. This takes 1-2 days.

4. Once caught up, in your application, schedule ```update:index``` to be called ```everyMinute()```.