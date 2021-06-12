# Calling Api Methods

  * [Read Clients Managing First](./Clients.md)
  * [More About](https://vk.com/dev/api_requests)
 
## Example 
```php
use GuzzleHttp\Promise\PromiseInterface;
use VkLib\Exception\VkMethodException;
use VkLib\Method\Response;
use VkLib\VkApi;

$api = new VkApi("client");

# $params = ["key1" => "val1", "key2" => "val2"];
# $throws = true;
# $api->{"section"}->{"method"}($params, $throws);

$params = [];
$throws = true;
$timeout = 0; //infinity
$api->reset()->execute($params, $throws, $timeout);

# Handling Api Errors

try {
    $api->users->get();
} catch (VkMethodException $ex) {
    $apiError = $ex->getError();
    echo $apiError->getMessage() ." (".$apiError->getCode().")";
}

# call asynchronous

/** @var PromiseInterface $promise*/
$promise = $api->users->get([], $throws, $timeout, function(Response $res) {
    var_dump($res->json());
});

$promise->wait();

# Response

$resp = $api->users->get()->getResponse();

# to json

$json = $api->users->get()->json();

# Get value

$itemIndex = 0; 
$response = $api->users->get(["fields" => ["about", "sex"]]);

# Response->{"get" . "ResponseValue"}();
$name = $response->getFirstName($itemIndex);
$lname = $response->getLastName($itemIndex);
$about = $response->getAbout($itemIndex);
```

## Captcha processing
```php
use VkLib\Method\Captcha;

Captcha::setHandler(function(Captcha $cap) {
    $cap->saveImage("./Captcha.jpg");
    $cap->input("")->call();
    return true;
});
```