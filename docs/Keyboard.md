# Keyboard

  * [Read Api Methods First](./Methods.md)
  * [Payload Handlers](#Payload_Handlers)
  * [Carousel](#Carousel)
  * [More About](https://vk.com/dev/bots_docs_3?f=4.%20Bot%20keyboards)

## Example
```php
use VkLib\Message\Keyboard;
use VkLib\Message\Button;
use VkLib\VkApi;

$inline = true;
$onetime = false;
$kb = new Keyboard($inline, $onetime);

# Create button
$btn = new Button();
$btn->setType(Button::TYPE_TEXT);
$btn->setColor(Button::COLOR_GREEN);
$btn->setLabel("Green Button");

$btn2 = new Button();
$btn2->setColor(Button::COLOR_RED);
$btn2->setLabel("Red Button");

$kb->addButtons($btn, $btn2);

$kb->lineBreak(); //new line

$kb->addButton(Button::create(
    Button::TYPE_TEXT,
    Button::COLOR_BLUE, 
    "Hello", //label
    [], //payload
    ["id" => "example", "handler" => function($obj) {}] //onClick params
));

$api = new VkApi("Client");
$api->messages->send([
    "random_id" => 0,
    "message" => "test",
    "peer_id" => 2000000001,
    "keyboard" => $kb->getBody()
]);
```

# Payload Handlers

[Read LongPoll Bots First](./LongPoll.md)

## Example
```php
use VkLib\Message\MessageEventAnswer;
use VkLib\LongPoll\LongPollBot;
use VkLib\Message\Keyboard;
use VkLib\Message\Button;

$lp = new LongPollBot($groupId, $client);
$lp->setHandling(true); //button payload handling

$kb = new Keyboard(true);

$btn = new Button();
$btn->setLabel("Click here");
$btn->setId("test");
$btn->onClick(function($obj) {
   echo $obj["from_id"] . " clicked button 'test'";
});

# callback button
$btn2 = new Button(Button::TYPE_CALLBACK, Button::COLOR_BLUE);
$btn2->setLabel("Click here");
$btn2->setId("testCallback");
$btn2->onClick(function(MessageEventAnswer $ev) {
    echo $ev->getUserId() . " clicked button 'testCallback'";
    
    // action 1
    $ev->showSnackbar("this is a snackbar!");
    
    // action 2
    $ev->openLink("link");
    
    // action 3 
    $ev->openApp($appId, $hash, $ownerId);
});

$kb->addButtons($btn, $btn2);

$lp->getClient()->getApi()->messages->send([
    "random_id" => 0,
    "message" => "test keyboard",
    "peer_id" => 2000000001,
    "keyboard" => $kb->getBody()
]);

$lp->connect();
$lp->start();
```

# Carousel

  * [Read Keyboard First](# Keyboard)

## Example
```php
use VkLib\Message\Carousel;
use VkLib\Message\Button;
use VkLib\VkApi;


$carousel = new Carousel();


$buttons = [Button::create(Button::TYPE_TEXT, Button::COLOR_BLUE, "Click here")];
$photoId = "460809808_457246633";
$title = "Element";
$description = "this is a element";
$link = null;
$action = Carousel::ACTION_OPEN_PHOTO;

$carousel->addElement($buttons, $photoId, $title, $description, $action, $link);

$api = new VkApi("Client");
$api->messages->send([
    "random_id" => 0,
    "message" => "test",
    "peer_id" => 2000000001,
    "template" => $carousel->getBody()
]);
```
