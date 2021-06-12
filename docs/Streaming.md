# Streaming

  * [Read Clients Managing First](./Clients.md)
  * [More About](https://vk.com/dev/streaming_api_docs)

## Example
```php
use Workerman\Connection\ConnectionInterface;
use VkLib\VkStreaming;

$stream = new VkStreaming("client");

/**
 *
 * @var string $tag
 * @var string $value
 * @var string $word
 *
 * @var string[][] $rules
 * @var string[] $settings
 * @var mixed[] $stats
 * @var string $stem
 */
$rules = $stream->getRules();
$settings = $stream->getSettings();
$stats = $stream->getStats();
$stem = $stream->getStem($word);

$stream->addRule($tag, $value);
$stream->setSettings($settings);
$stream->deleteRule($tag);

// Events
$handler = $stream->getHandler();

$handler->add(VkStreaming::ON_CONNECT, function(ConnectionInterface $conn) {
    echo "[Streaming] connection successfully!";
});

$handler->add(VkStreaming::ON_ERROR, function(ConnectionInterface $conn) {
    echo "[Streaming] connection failed!";
});

$handler->add(VkStreaming::ON_CLOSE, function(ConnectionInterface $conn) {
    echo "[Streaming] connection closed!";
});

$handler->add(VkStreaming::ON_MESSAGE, function(ConnectionInterface $conn, array $data) {
    echo "[Streaming] new message!";
    var_dump($data);
});

$handler->add(VkStreaming::ON_SERVICE_MESSAGE, function(ConnectionInterface $conn, array $data) {
    echo "[Streaming] Service message: {$data['message']} ({$data['service_code']})";
});

$handler->add(VkStreaming::ON_EVENT, function(ConnectionInterface $conn, array $data) {
    echo "[Streaming] New {$data['event_type']}: {$data['event_url']}";
});

// SSL Context
$ctx = [
    "ssl" => [
        "local_cert"  => "/your/path/of/server.pem",
        "local_pk"    => "/your/path/of/server.key",
        "verify_peer" => false
    ]
];

$stream->authorization();
$stream->start($ctx);
```