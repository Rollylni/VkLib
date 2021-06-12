# Clients Managing

in this library you can create an unlimited number of clients for different tasks

## Creating your Client and using
```php
use VkLib\VkClient;
use VkLib\VkApi;

# create the first client to call the users.get method
$vkApiVersion = 5.130; //default
$vkApiLang = "en"; //default
$myClient = new VkClient("client1", $vkApiVersion, $vkApiLang);
$myClient->setToken("VkAccessToken");

# pass the client object itself
$api = new VkApi($myClient);

# or by client name
$api = new VkApi("client1");

echo $api->users->get()->getResponse();

# create the second client to get Long Poll server
$client2 = new VkClient("client2");
$client2->setToken("");

$response = $client2->getApi()->groups->getLongPollServer()->json();
```

## Deleting a client
```php
$client = new VkClient("test");
$client->remove();

# or by client name
VkClient::removeClient("test");

# throws VkClientException
$api = new VkApi("test");
$api->users->get();
```