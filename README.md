Active for Laravel
======
[![Build Status](https://travis-ci.org/letrunghieu/active.png?branch=master)](https://travis-ci.org/letrunghieu/active)
[![Latest Stable Version](https://poser.pugx.org/hieu-le/active/v/stable.svg)](https://packagist.org/packages/hieu-le/active)
[![Code Climate](https://codeclimate.com/github/letrunghieu/active/badges/gpa.svg)](https://codeclimate.com/github/letrunghieu/active)
[![Test Coverage](https://codeclimate.com/github/letrunghieu/active/badges/coverage.svg)](https://codeclimate.com/github/letrunghieu/active/coverage)
[![Total Downloads](https://poser.pugx.org/hieu-le/active/downloads.svg)](https://packagist.org/packages/hieu-le/active)
[![License](https://poser.pugx.org/hieu-le/active/license.svg)](https://packagist.org/packages/hieu-le/active)

The helper class for Laravel applications (both L4 and L5) to get active class base on current url.

This README file is written for the new `3.x` version of this package, which is compatible with the Laravel 5 only.

  * If you are using Laravel 4, see the [`1.x` versions](https://github.com/letrunghieu/active/tree/support/1.x).
  * If you are using Laravel 5 with the legacy `2.x` version of this package, you can give a try with the `3.x` version (whose API is changed totally) or continue with the [`2.x` version](https://github.com/letrunghieu/active/tree/support/2.x).

## Installation

Add this package to your `composer.json` file and run `composer update` once.

```
"hieu-le/active": "^3.0"
```

Append this line to your `providers` array in `config/app.php`

```php
HieuLe\Active\ActiveServiceProvider::class,
```

Append this line to your `aliases` array in `config/app.php`

```php
'Active' => HieuLe\Active\Facades\Active::class,
```

## Usage

See: [How to use Active](https://www.hieule.info/?p=377)
