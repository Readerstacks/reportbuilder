<p align="center">
    <a href="https://github.com/readerstacks" target="_blank">
        <img src="https://i0.wp.com/readerstacks.com/wp-content/uploads/2021/10/Screenshot_2021-10-30_at_11.21.33_AM-removebg-preview-5-1.png?w=500&ssl=1" height="100px">
    </a>
    <h1 align="center">Laravel Report Generator as Metabase By readerstacks.com</h1>
    <br>
</p>

Create Any report easily with laravel report builder same as metabase.

 
 
For license information check the [LICENSE](LICENSE.md)-file.

Features
--------

- Generate any report in chart, table format easily like metabase.


Installation
------------

### 1 - Dependency

The first step is using composer to install the package and automatically update your `composer.json` file, you can do this by running:

```shell
composer require readerstacks/reportmanager
```

> **Note**: If you are using Laravel 5.5, the steps 2  for providers and aliases, are unnecessaries. QieryMigrations supports Laravel new [Package Discovery](https://laravel.com/docs/5.5/packages#package-discovery).

### 2 - Provider

You need to update your application configuration in order to register the package so it can be loaded by Laravel, just update your `config/app.php` file adding the following code at the end of your `'providers'` section:

> `config/app.php`

```php
<?php

return [
    // ...
    'providers' => [
        Aman5537jains\ReportBuilder\ReportBuilderServiceProvider::class,
        // ...
    ],
    // ...
];
```

#### Lumen

Go to `/bootstrap/app.php` file and add this line:

```php
<?php
// ...

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

// ...

$app->register(Aman5537jains\ReportBuilder\ReportBuilderServiceProvider::class);

// ...

return $app;
```

 

### 3 Configuration

#### Publish config

In your terminal type

```shell
php artisan vendor:publish --provider="Aman5537jains\ReportBuilder\ReportBuilderServiceProvider"
```

#### Run Migration

In your terminal type

```shell
php artisan migrate
```


  
Usage
-----

### Laravel Usage

Access directly 
http://localhost/report-manager/builder

if you want to use in code anywhere then 

```php

  (new \Aman5537jains\ReportBuilder\ReportGenerator())->render();

```
 
