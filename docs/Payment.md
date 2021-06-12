# Payments handling

  * [More About](https://vk.com/dev/payments_callbacks)

## Example
```php
use VkLib\Payment\Notification\GetItemNotification;
use VkLib\Payment\Notification\GetSubscriptionNotification;
use VkLib\Payment\Notification\OrderStatusChangeNotification;
use VkLib\Payment\Notification\SubscriptionStatusChangeNotification;
use VkLib\Payment\Notification\PaymentNotification;
use VkLib\Payment\PaymentResponse;
use VkLib\Payment\PaymentHandler;
use VkLib\Payment\PaymentError;
use VkLib\Payment\PaymentLang;

$secretKey = "";

new class ($secretKey) extends PaymentHandler {

    /**
     * 
     * @param PaymentNotification $nf
     * @return PaymentResponse|array
     */
    public function handleTest(PaymentNotification $nf) {
        if ($nf instanceof GetItemNotification) {
            # $this->getItemHandle();
        } elseif ($nf instanceof GetSubscriptionNotification) {
            # $this->getSubscriptionHandle();
        } elseif ($nf instanceof OrderStatusChangeNotification) {
            # $this->orderStatusChangeHandle();
        } elseif ($nf instanceof SubscriptionStatusChangeNotification) {
            # $this->subscriptionStatusChangeHandle();
        }
    }
    
    /**
     * 
     * @param GetItemNotification $nf
     * @return PaymentResponse|array
     */
    public function get_item(GetItemNotification $nf) {
        $item = $nf->getItem();
        $lang = $nf->getLang();
        
        if ($lang === PaymentLang::RUSSIAN) {
            $name = "Золотых монет";
        } else { // english or other
            $name = "Coins";
        }
        
        if ($item === "item1") {
            $nf->setItemId(25);
            $nf->setTitle("300 $name");
            $nf->setPhotoUrl("http://somesite/images/coin.jpg");
            $nf->setPrice(5);
        } elseif ($item === "item2") {
            $nf->setItemId(27);
            $nf->setTitle("500 $name");
            $nf->setPhotoUrl("http://somesite/images/coin.jpg");
            $nf->setPrice(10);
        } else {
            return new PaymentError(PaymentError::PRODUCT_NOT_EXIST, "Item not found!", true);
        }
        return $nf;
    }
    
    /**
     * 
     * @param GetSubscriptionNotification $nf
     * @return PaymentResponse|array
     */
    public function get_subscription(GetSubscriptionNotification $nf) {
        // TODO
        return $nf;
    }
    
    /**
     * 
     * @param OrderStatusChangeNotification $nf
     * @return PaymentResponse|array
     */
    public function order_status_change(OrderStatusChangeNotification $nf) {
        // TODO
        return $nf;
    }
    
    /**
     * 
     * @param SubscriptionStatusChangeNotification $nf
     * @return PaymentResponse|array
     */
    public function subscription_status_change(SubscriptionStatusChangeNotification $nf) {
        // TODO
        return $nf;
    }
};
```