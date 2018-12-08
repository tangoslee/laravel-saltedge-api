# laravel-saltedge-api

Laravel specific composer package for SaltEdge API integration.

## Installation

Add new git repository to the `composer.json` configuration:

```json
{
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/TendoPayPlugins/laravel-saltedge-api.git"
    }
  ]
 }
```

Next run following command:
 
`composer require tendopay/laravel-dragonpay-api`

or add the dependency in your `composer.json` and update composer dependencies:

```json
{
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/TendoPayPlugins/laravel-saltedge-api.git"
    }
  ],
  "require": {
    "tendopay/laravel-saltedge-api": "0.*"
  }
}
```

`composer update` or `composer install`.

## Configuration

In laravel's .env file add following keys with proper values, e.g.:

```$json
SALTEDGE_URL=https://www.saltedge.com/api/v4/
SALTEDGE_APP_ID=WM75_qN1N3KJYCl6IjhZgqGtRAY-i614fuAjV4UnGw9
SALTEDGE_SECRET=5o12Ea41LHXN5ioWSOdaAvL8Q-EHtoW2Z7GQz2T1zjp
```

## What's included

The package provides 4 new services that fetch data from SaltEdge's API:

* `AccountService` that provides integration with selected [accounts endpoints](https://docs.saltedge.com/reference/#accounts) of the API
* `CategoryService` that provides integration with selected [categories endpoint](https://docs.saltedge.com/reference/#categories) of the API
* `CustomerService` that provides integration with selected [customers endpoint](https://docs.saltedge.com/reference/#customers) of the API
* `ProviderService` that provides integration with selected [providers endpoint](https://docs.saltedge.com/reference/#providers) of the API

The package registers its own `SaltEdgeServiceProvider`, so all the above services will be available right after installing the composer package.