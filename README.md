Active for Laravel 4
======
[![Build Status](https://travis-ci.org/letrunghieu/active.png?branch=master)](https://travis-ci.org/letrunghieu/active)

The helper class for Laravel 4 applications to get active class base on current route. I was inspired from [dwightwatson/active](https://github.com/dwightwatson/active).

## Installation

Add this package to your `composer.json` file and run `composer update` once.

```
"hieu-le/active": "dev-master"
```

Append this line to your `providers` array

```
'HieuLe\Active\ActiveServiceProvider',
```

Append this line to your `aliases` array

```
'Active' => 'HieuLe\Active\Facades\Active',
```

For more details about usage see: [this page](http://www.hieule.info/products/active-class-helper-laravel-4/)