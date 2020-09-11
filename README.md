Active for Laravel
======
[![Build Status](https://travis-ci.org/letrunghieu/active.png?branch=master)](https://travis-ci.org/letrunghieu/active)
[![Latest Stable Version](https://poser.pugx.org/hieu-le/active/v/stable.svg)](https://packagist.org/packages/hieu-le/active)
[![Code Climate](https://codeclimate.com/github/letrunghieu/active/badges/gpa.svg)](https://codeclimate.com/github/letrunghieu/active)
[![Test Coverage](https://codeclimate.com/github/letrunghieu/active/badges/coverage.svg)](https://codeclimate.com/github/letrunghieu/active/coverage)
[![Total Downloads](https://poser.pugx.org/hieu-le/active/downloads.svg)](https://packagist.org/packages/hieu-le/active)
[![License](https://poser.pugx.org/hieu-le/active/license.svg)](https://packagist.org/packages/hieu-le/active)

The helper class for Laravel applications to get active class base on current url.

Since version 7.0, the major version of this library will match the major version of Laravel.

| Laravel version | Active library version  |
| --------------- | ----------------------- |
| >= 7.x          | >= 7.x                  |
| 6.x             | 4.x                     |
| 5.x             | 3.x                     |
| 4.x             | 1.x                     |

## Installation

Require this package as your dependencies:

```
composer require hieu-le/active
```
> If you are using Laravel 5.5+, you do not need to manually register the ServiceProvider and Alias.

Append this line to your `providers` array in `config/app.php`

```php
HieuLe\Active\ActiveServiceProvider::class,
```

Append this line to your `aliases` array in `config/app.php`

```php
'Active' => HieuLe\Active\Facades\Active::class,
```

## Usage

See: [How to use Active](https://www.hieule.info/tag/laravel-active/)

## Changelog:

* v7.0: support Laravel 7 and start using the same marjor version with Laravel
* v4.0: support Laravel 6 and PHPUnit 8
* v3.5: support Laravel 5.5.x and PHPUnit 6
* v3.4: support Laravel 5.4.x
* v3.3: support Laravel 5.3.x
* v3.2: allows first parameter of `if_*` functions to be string or array
* v3.1: support both Laravel 5.2.x and 5.1.x
* v3.0: new API
