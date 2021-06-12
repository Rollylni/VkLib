# VkLib, PHP SDK

[![Version](https://img.shields.io/packagist/v/rollylni/vklib?style=plastic)](https://packagist.org/packages/rollylni/vklib)
[![Downloads](https://img.shields.io/packagist/dt/rollylni/vklib?style=plastic)](https://packagist.org/packages/rollylni/vklib)
[![License](https://img.shields.io/packagist/l/rollylni/vklib?style=plastic)](https://en.wikipedia.org/wiki/MIT_License)
[![PHPVersion](https://img.shields.io/packagist/php-v/rollylni/vklib?style=plastic)](https://packagist.org/packages/rollylni/vklib)

VkLib is a library for simplified work with the VK API in the OO approach.

## Installation
```bash
composer require rollylni/vklib
```

## Capabilities
  * Ability to create many clients [(Example)](./docs/Clients.md)
  * Сonvenient call API requests [(Example)](./docs/Methods.md)
  * Captcha handling capability [(Example)](.docs/Methods.md)
  * Keyboard support for bots [(Example)](./docs/Keyboard.md)
  * Callback buttons supported [(Example)](./docs/Keyboard.md)
  * Button click handlers for bots [(Example)](./docs/Keyboard.md)
  * Carousel supported [(Example)](./docs/Keyboard.md)
  * Bots Long Poll [(Example)](./docs/LongPoll.md)
  * User Long Poll [(Example)](./docs/LongPoll.md)
  * Callback Bots [(Example)](./docs/Callback.md)
  * Manage group callback servers [(Example)](./docs/CallbackManager.md)
  * The ability to process payments [(Example)](./docs/Payment.md)
  * Uploading files [(Example)](./docs/Upload.md)
  * Streaming [(Example)](./docs/Streaming.md)
  * Widgets [(Example)](./docs/Widgets.md)
  * OAuth [(Example)](./docs/OAuth.md)
  
## Example Usage
```php
require "vendor/autoload.php";

$client = new \VkLib\VkClient("ClientName", 5.126, "en");
$client->setToken("AccessToken");

$api = new \VkLib\VkApi($client);
var_dump($api->users->get()->json());
$client->remove();
```
