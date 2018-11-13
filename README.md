## mazentouati/simple-2way-config

[![GitHub (pre-)release](https://img.shields.io/github/release-pre/mazentouati/simple-2way-config.svg)](https://github.com/mazentouati/simple-2way-config/releases/tag/0.1.0)
[![Build Status](https://travis-ci.org/mazentouati/simple-2way-config.svg?branch=master)](https://travis-ci.org/mazentouati/simple-2way-config)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mazentouati/simple-2way-config/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mazentouati/simple-2way-config/?branch=master)
[![Codecov branch](https://img.shields.io/codecov/c/github/mazentouati/simple-2way-config/master.svg?style=flat-square)](https://codecov.io/gh/mazentouati/simple-2way-config)
[![StyleCI](https://styleci.io/repos/157292080/shield)](https://styleci.io/repos/157292080)
[![Maintainability](https://api.codeclimate.com/v1/badges/8f71ba0353635c7f4350/maintainability)](https://codeclimate.com/github/mazentouati/simple-2way-config/maintainability)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](./LICENSE)

Simple 2 way configuration is a php-based read and write configuration library. It's suitable for applications that require the use of file system to store preferences or configuration.

## Installation
we recommend installing this package through  [composer](http://getcomposer.org/) :

```bash
composer require mazentouati/simple-2way-config
```

## Usage
The simplest way to use it is through the package's factory. The factory's required parameter is the path of the directory that holds your config files.

```php
use MazenTouati\Simple2wayConfig\S2WConfigFactory;

$config = S2WConfigFactory::create( __DIR__ . '/demo' );
```

Now you can access to a config value using dot notation '{filename}.path.to.value'
```php
$host = $config->get('database.drivers.mysql.host');
```
*Note: your config file should be an array-based configuration, check this [example](#configuration-file-formats)*

## API

<!-- DOCS START -->

the config API implements the `S2WConfigInterface`.

the examples shown below will assume that you already assigned your config to a variable called `$config`

### get(string $path, mixed $default = null)
Get a value using dot-notation path using this convention `{filename}.path.to.value`
```php
$config->get('database.drivers.mysql.host');
```
optionally you can pass a default value to return when it find nothing, by default it returns `null`
```php
echo $config->get('somewhere.where.are.you', 'here');
> here
```

### set(string $path, mixed $value)
Update a value in the runtime configuration.

*Note: if it's unable to find the config's filename it will create a new key for that filename in the runtime instance. The same for values, if there's any missing part in the dot path it will automatically create it in the runtime instance.*

```php
$config->set('database.drivers.mysql.host', '127.0.0.1');
```

### sync(mixed $specificConfiguration = false)
Syncs the runtime configuration with the source file
```php
$config->sync();
```
by default it will sync all files, though you can pass a specific file to sync

```php
$config->sync('database');
```
using `sync` will create a backup file to stay safe if something wrong happen.
the backup will create alongside the original file holding this name `{original_file_name}.backup.php`.
if the backup fails due to a lack of permission ( it uses PHP `copy` function) it will throw an exception `S2WConfigException`.
To avoid any ugly expections errors you can use `sync` this way

```php
use MazenTouati\Simple2wayConfig\S2WConfigException;
...
try {
    $config->sync();
} catch (S2WConfigException $e) {
    die($e->getMessage());
}
```
in case of expection this code will print something like

`Configuration sync is unable to save a backup for { path_to_directory\database.php }, please check your permissions`

*Note: this method will sync any news values or updated values you made using the `set` method. Even if you set inexistent config file into the runtime configuration, using `set`, this method will create that file for you. Use it with caution if you don't want any unwanted behavior*
<!-- DOCS END -->
# Configuration File Formats
The configuration file must be a valid php file and return a valid array.

```php
<?php
return [
    'driver' => 'mysql',
    'drivers' => [
        'mysql' => [
            'host' => 'your_host',
            'dbname' => 'your_database',
            'user' => 'your_user',
            'password' => 'your_password',
        ],
    ],
];
```
## Contributing

Please check [the guide](./CONTRIBUTING.md)

## LICENSE

> &copy; [MIT](./LICENSE) | 2018, mazentouati/simple-2way-config
