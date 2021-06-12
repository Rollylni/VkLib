# OAuth

  * [More About](https://vk.com/dev/access_token)

## Authorization Code Flow
```php
use VkLib\VkOAuth;

session_start();

$clientId = "";
$clientSecret = "";
$redirectUri = "https://example.com/oauth.php";

$scope = ["offline", "friends"];
# or all permissions:
$scope = VkOAuth::USER_SCOPE;

$oauth = new VkOAuth($clientId, $clientSecret);
$oauth->setRedirectUri($redirectUri);

$_SESSION["OAUTH"] = $oauth;

$params = [
    "response_type" => VkOAuth::AUTHORIZATION_CODE_FLOW,
    "scope" => $scope
];

$uri = $oauth->getAuthorizeUri($params);

echo '<a href="$uri">Login VK</a>';
```

**/oauth.php**:
```php
$oauth = $_SESSION["OAUTH"];
$response = $oauth->getAuthorizationCode();

if (isset($response["access_token"])) {
    $_SESSION["token"] = $response["access_token"];
}
```

## Implicit Flow
```php
use VkLib\VkOAuth;

$clientId = "";
$oauth = new VkOAuth($clientId);

// log in by specifying form parameters
// for receiving cookies to get access token
$oauth->getHttpClient()->post("https://login.vk.com/", [
    "form_params" => []
]);

// get access token
$response = $oauth->getImplicitToken();
$access_token = $response["access_token"] ?? null;

echo $access_token;
```

## Client Credentials Flow
```php
use VkLib\VkOAuth;

$clientId = "";
$clientSecret = "";

$oauth = new VkOAuth();

$access_token = $oauth->getServiceToken($clientId, $clientSecret);

echo $access_token;
```