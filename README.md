# VkLib, PHP SDK

[![Version](https://img.shields.io/packagist/v/rollylni/vklib?style=plastic)](https://packagist.org/packages/rollylni/vklib)
[![Downloads](https://img.shields.io/packagist/dt/rollylni/vklib?style=plastic)](https://packagist.org/packages/rollylni/vklib)
[![License](https://img.shields.io/packagist/l/rollylni/vklib?style=plastic)](https://en.wikipedia.org/wiki/MIT_License)
[![PHPVersion](https://img.shields.io/packagist/php-v/rollylni/vklib?style=plastic)](https://packagist.org/packages/rollylni/vklib)

VkLib is a library for simplified work with the VK API in the OOP style.

full documentation coming soon...

## Installation
```bash
composer require rollylni/vklib
```

## Capabilities
  * Ability to create many clients
  * Ability to change HTTP Client
  * Сonvenient call API requests
  * Captcha handling capability
  * Keyboard support for bots
  * Callback buttons supported
  * Button click handlers for bots
  * Carousel supported
  * Bots Long Poll
  * User Long Poll
  * Callback Bots
  * Manage group callback servers
  * The ability to process payments
  * Uploading files to the VK server
  * Widgets
  
## Example Usage
```php
require "vendor/autoload.php";

$client = new \VkLib\VkClient("id", 5.126, "en");
$client->setToken(" ");
$api = new \VkLib\VkApi($client);
var_dump($api->users->get()->json());
$client->removeClient($client);
```
