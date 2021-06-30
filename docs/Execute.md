# Execute
  
  * [Read Clients Managing First](./Clients.md)
  * [More About](https://vk.com/dev/execute)

## Execute code with Variables
```php
use VkLib\VkExecute;

$vkScript = new VkExecute("client");

$vkScript->initVars([
    "MESSAGE" => [
        "text" => "Hello World!",
        "peer_id" => 2e9+1,
        "random_id" => rand()
    ]
]);

$vkScript->USER_ID = 460809808;

$vkScript->setCode(
    "var userId = getopt('USER_ID');".
    "var msg = getopt("MESSAGE");".
    "var userName = API.users.get({"user_ids": userId})@.first_name;".
    
    "return API.messages.send({
        "text": userName + ", " + msg.text,
        "peer_id": msg.peer_id,
        "random_id": msg.random_id
    });"
);

/*
 * @var bool $throws = true
 * @var float $timeout = 10
 * @var callable|false $async = false
 */
$res = $vkScript->execute($throws, $timeout, $async);
```

## Execute Pool
```php
use VkLib\VkExecute;

$vkScript = new VkExecute("client");

// calling three methods in one request (maximum 25 in one request)

$parameters = [];

$vkScript->setPool(true);

$vkScript->users->get($parameters, function($res) {
    echo $res[0]["first_name"] . " " . $res[0]["last_name"];
});

$vkScript->friends->get($parameters, function($res) {
    echo implode(', ', $res["items"]) . " (". $res["count"] .")";
});

$index = $vkScript->status->get($parameters);

/*
 * @var bool $throws = true
 * @var float $timeout = 10
 */
$res = $vkScript->execute($throws, $timeout)->json();

echo $res[$index]["text"];
```