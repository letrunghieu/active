Active for Laravel 4
======
[![Build Status](https://travis-ci.org/letrunghieu/active.png?branch=master)](https://travis-ci.org/letrunghieu/active)
[![Latest Stable Version](https://poser.pugx.org/hieu-le/active/v/stable.svg)](https://packagist.org/packages/hieu-le/active) [![Total Downloads](https://poser.pugx.org/hieu-le/active/downloads.svg)](https://packagist.org/packages/hieu-le/active) [![Latest Unstable Version](https://poser.pugx.org/hieu-le/active/v/unstable.svg)](https://packagist.org/packages/hieu-le/active) [![License](https://poser.pugx.org/hieu-le/active/license.svg)](https://packagist.org/packages/hieu-le/active)

The helper class for Laravel 4 applications to get active class base on current route.
## Installation

Add this package to your `composer.json` file and run `composer update` once.

```
"hieu-le/active": "~1.0"
```

If you use this package in Laravel, the most suitable version will be selected base on the version of Laravel package.

Append this line to your `providers` array

```php
'HieuLe\Active\ActiveServiceProvider',
```

Append this line to your `aliases` array

```php
'Active' => 'HieuLe\Active\Facades\Active',
```

### Changes in version 1.2
Support new method `Active::routePattern`. This method will check the current **route name** with an array of patterns instead of an array of route names.

### Changes in version 1.3

* Support Laravel 5.0
* Use PSR-4 instead of PSR-0

For more details about usage see: [this page](http://www.hieule.info/products/active-class-helper-laravel-4/)
