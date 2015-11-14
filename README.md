Active for Laravel 4/5
======
[![Build Status](https://travis-ci.org/letrunghieu/active.png?branch=master)](https://travis-ci.org/letrunghieu/active)
[![Latest Stable Version](https://poser.pugx.org/hieu-le/active/v/stable.svg)](https://packagist.org/packages/hieu-le/active)
[![Code Climate](https://codeclimate.com/github/letrunghieu/active/badges/gpa.svg)](https://codeclimate.com/github/letrunghieu/active)
[![Test Coverage](https://codeclimate.com/github/letrunghieu/active/badges/coverage.svg)](https://codeclimate.com/github/letrunghieu/active/coverage)
[![Total Downloads](https://poser.pugx.org/hieu-le/active/downloads.svg)](https://packagist.org/packages/hieu-le/active)
[![License](https://poser.pugx.org/hieu-le/active/license.svg)](https://packagist.org/packages/hieu-le/active)

The helper class for Laravel 4/5 applications to get active class base on current route.
## Installation

Add this package to your `composer.json` file and run `composer update` once.

For Laravel 5

```
"hieu-le/active": "~2.0"
```

or for Laravel 4

```
"hieu-le/active": "~1.0"
```

If you use this package in Laravel, the most suitable version will be selected base on the version of Laravel package.

Append this line to your `providers` array in `config/app.php`

```php
'HieuLe\Active\ActiveServiceProvider',
```

Append this line to your `aliases` array in `config/app.php`

```php
'Active' => 'HieuLe\Active\Facades\Active',
```

### Changes in version 1.2
Support new method `Active::routePattern`. This method will check the current **route name** with an array of patterns instead of an array of route names.

### Changes in version 2.0

* Support Laravel 5.0
* Use PSR-4 instead of PSR-0
* Support new method `Active::uri`. This method will check the current URI (**with** the leading slash `/`)

### Changes in version 2.1/1.3

* Support new method `Active::query`. This method will check whether the value of an query string parameter equals to or contains a specified value.

### Changes in version 2.2/1.4

* Support optional parameter for inactive class, thanks @lowerends

### Changes in version 2.3

* Support new method `Active::routeParam`. This method check the current route name and route parameters with some specific values.

For more details about usage see: [this page](http://www.hieule.info/products/active-class-helper-laravel-4/)
