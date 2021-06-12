# Manage group callback servers

  * [Read Clients Managing First](./Clients.md)
  * [More About](https://vk.com/dev/callback_api?f=1.3.%20Configuration%20via%20API)

## Example
```php
use VkLib\Callback\CallbackManager;
use VkLib\Callback\CallbackServer;

$groupId = 0;
$manager = new CallbackManager($groupId, "client");

$url = "https://example.com";
$title = "Example";
$secret = null;
$id = $manager->addServer($url, $title, $secret);

$manager->setServerSettings($id, [
    "api_version" => "5.103"
]);
var_dump($manager->getServerSettings($id));

$newUrl = $url;
$newTitle = "Test";
$manager->editServer($id, $newUrl, $newTitle, $secret);

# $manager->deleteServer($id);

# get all servers
/** @var CallbackServer[] $servers*/
$servers = $manager->getServers();

# get last server (Test)
/** @var CallbackServer $server*/
$server = $manager->getLastServer();

# get server by title
$server = $manager->getServer("Test");

# get server by id
$server = $manager->getServer($id);

# CallbackServer Methods
$server->setSettings([]);
$server->setApiVersion("5.130");

var_dump(
    $server->getSettings(),
    $server->getApiVersion(),
    $server->getEventSettings(),
    $server->getTitle(),
    $server->getCreator(),
    $server->getSecret(),
    $server->getStatus(),
    $server->getUrl(),
    $server->getId()
);

$server->edit($newUrl, $newTitle, $secret);
$server->delete();
```