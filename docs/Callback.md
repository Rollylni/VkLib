# Callback bots

  * [More About](https://vk.com/dev/callback_api)

## Example
```php
use VkLib\Callback\CallbackHandler;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    return;
}

new class extends CallbackHandler {
    public $token = "Confirmation Key";
    
    /**
     *
     * # public function {"event"}($obj, $gid) {}
     *
     * @param array $object Event Object
     * @param int   $groupId ID of the group in which the event occurred
     * @param string|null $secretKey
     */
    public function message_new($object, $groupId, $secretKey) {
        //
    }
    
    /**
     * 
     * @param string   $type
     * @param mixed[]  $object
     * @param integer  $groupId
     * @param string   $secretKey
     */
    public function onHandle(string $type, array $object, int $groupId, ?string $secretKey) {
        //
    }
};
```