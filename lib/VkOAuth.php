<?php

/**
 * __     ___    _     _ _     
 * \ \   / / | _| |   (_) |__  
 *  \ \ / /| |/ / |   | | '_ \ 
 *   \ V / |   <| |___| | |_) |
 *    \_/  |_|\_\_____|_|_.__/ 
 *
 * VkLib - library for simplified work with VK-API
 *
 * More about VK-API {@link https://vk.com/dev/first_guide}
 * Project Homepage {@link https://github.com/Rollylni/VkLib}
 * Author Homepage {@link https://vk.com/rollylni}
 *
 * @copyright 2019-2021 Rollylni
 * @author Faruch N. <rollyllni@gmail.com>
 * @version 0.7 beta
 * @license MIT
 */
namespace VkLib;

use VkLib\Exception\VkOAuthException;
use VkLib\Method\VkMethod;

use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\Handler\StreamHandler;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

use function http_build_query;
use function is_array;
use function implode;
use function explode;
use function preg_match;
use function str_replace;
use function count;
use function trim;

/**
 * 
 * @since 0.7.1
 * 
 * @link https://vk.com/dev/access_token
 */
class VkOAuth {
    
    public const ENDPOINT = "https://oauth.vk.com/";
    public const BLANK = "blank.html";
    
    public const TYPE_AUTHORIZE = "authorize";
    public const TYPE_ACCESS_TOKEN = "access_token";
    public const TYPE_MOBILE_TOKEN = "token";
    
    public const IMPLICIT_FLOW = "token";
    public const AUTHORIZATION_CODE_FLOW = "code";
    public const CLIENT_CREDENTIALS_FLOW = "client_credentials";
    public const MOBILE_FLOW = "password";
    
    public const DISPLAY_PAGE = "page";
    public const DISPLAY_POPUP = "popup";
    public const DSIPLAY_MOBILE = "mobile";
    
    public const RESPONSE_ACCESS_TOKEN = "access_token";
    public const RESPONSE_EXPIRES_IN = "expires_in";
    public const RESPONSE_USER_ID = "user_id";
    public const RESPONSE_GROUPS = "groups";
    public const RESPONSE_ERROR = "error";
    public const RESPONSE_ERROR_DESCRIPTION = "error_description";
    public const RESPONSE_ERROR_TYPE = "error_type";
    
    public const SCOPE_NOTIFY = "notify";
    public const SCOPE_FRIENDS = "friends";
    public const SCOPE_PHOTOS = "photos";
    public const SCOPE_AUDIO = "audio";
    public const SCOPE_VIDEO = "video";
    public const SCOPE_STORIES = "stories";
    public const SCOPE_PAGES = "pages";
    public const SCOPE_APP_LINK = 256; 
    public const SCOPE_APP_WIDGET = "app_widget";
    public const SCOPE_STATUS = "status";
    public const SCOPE_NOTES = "notes";
    public const SCOPE_MESSAGES = "messages";
    public const SCOPE_WALL = "wall";
    public const SCOPE_ADS = "ads";
    public const SCOPE_OFFLINE = "offline"; // perpetual token
    public const SCOPE_DOCS = "docs";
    public const SCOPE_GROUPS = "groups";
    public const SCOPE_NOTIFICATIONS = "notifications";
    public const SCOPE_STATS = "stats";
    public const SCOPE_EMAIL = "email";
    public const SCOPE_MARKET = "market";
    public const SCOPE_MANAGE = "manage";
    
    /** @var string[]*/
    public const USER_SCOPE = [
    //  self::SCOPE_APP_LINK,
        self::SCOPE_NOTIFY,
        self::SCOPE_FRIENDS,
        self::SCOPE_AUDIO,
        self::SCOPE_VIDEO,
        self::SCOPE_STORIES,
        self::SCOPE_PAGES, 
        self::SCOPE_STATUS,
        self::SCOPE_NOTES,
        self::SCOPE_MESSAGES,
        self::SCOPE_WALL,
        self::SCOPE_ADS,
        self::SCOPE_OFFLINE,
        self::SCOPE_DOCS,
        self::SCOPE_GROUPS,
        self::SCOPE_NOTIFICATIONS,
        self::SCOPE_STATS,
        self::SCOPE_EMAIL,
        self::SCOPE_MARKET
    ];
    
    /** @var string[]*/
    public const GROUP_SCOPE = [
    //  self::SCOPE_APP_WIDGET,
        self::SCOPE_STORIES,
        self::SCOPE_PHOTOS,
        self::SCOPE_MESSAGES,
        self::SCOPE_DOCS,
        self::SCOPE_MANAGE
    ];
    
    /**
     * 
     * @var int|null
     */
    public $clientId;
    
    /**
     * 
     * @var string|null
     */
    public $clientSecret;
    
    /**
     * 
     * @var string|null
     */
    public $redirectUri = null;
    
    /**
     * 
     * @var ClientInterface
     */
    private $httpClient = null;
    
    /**
     * 
     * @param int $appId 
     * @param string $appSecret
     */
    public function __construct(int $appId = null, string $appSecret = null) {
        $this->clientId = $appId;
        $this->clientSecret = $appSecret;
    }
    
    /**
     * 
     * @param array $params
     * @param bool  $throws
     * @param CookieJarInterface|null $cookies Cockies received upon login to vk.com
     * @throws VkOAuthException
     * @return array [access_token, expires_in, user_id]
     */
    public function getImplicitToken(array $params = [], bool $throws = true, ?CookieJarInterface $cookies = null): array {
        if ($cookies === null) {
            $cookies = $this->getHttpClient()->getConfig("cookies");
        } if (!isset($params["redirect_uri"])) {
            $params["redirect_uri"] = $this->getRedirectUri() ?? (self::ENDPOINT . self::BLANK);
        } 
        
        $params["response_type"] = self::IMPLICIT_FLOW;
        $uri = $this->getAuthorizeUri($params);
        $res = $this->getHttpClient()->get($uri, [
            "http_errors" => $throws,
            "cookies" => $cookies
        ]);
        
        if ($res->hasHeader("Location")) {
            $redirectUri = $res->getHeader("Location")[0];
        } else {
            preg_match("/location.href = \"(.*?)\"\+addr/s", $res->getBody(), $redirectUri);
            
            if (!isset($redirectUri[1]) && $throws) {
                throw new VkOAuthException("Failed to get redirect URI!");
            }
            $redirectUri = $redirectUri[1] ?? "";
        }
        
        $res = $this->getHttpClient()->get($redirectUri, [
            "http_errors" => $throws,
            "cookies" => $cookies
        ]);
        
        $uriScheme = $res->getHeader("Location")[0] ?? "";
        $uriScheme = explode('#', $uriScheme, 2);
        $uriScheme = explode('&', $uriScheme[1] ?? "");
        
        $query = [];
        foreach ($uriScheme as $v) {
            $v = explode('=', $v, 2);
            if (count($v) !== 2) {
                continue;
            }
            $query[trim($v[0])] = str_replace('+', ' ', trim($v[1]));
        }
        
        if ($query !== []) {
            if (isset($query[self::RESPONSE_ERROR]) && $throws) {
                throw new VkOAuthException(\sprintf(
                    "Failed to get Access Token: %s: %s",
                    $query[self::RESPONSE_ERROR],
                    $query[self::RESPONSE_ERROR_DESCRIPTION]
                ));
            }
        } elseif ($throws) {
            throw new VkOAuthException("Failed to get values from URI!");
        }
        return $query;
    }
    
    /**
     * 
     * @param array $params
     * @param bool  $throws
     * @throws VkOAuthException
     * @return mixed[] [access_token, user_id, expires_in]
     */
    public function getAuthorizationToken(array $params = [], bool $throws = true): array {
        if (isset($_GET[self::AUTHORIZATION_CODE_FLOW])) {
            if (!isset($params["redirect_uri"])) {
                $params["redirect_uri"] = $this->getRedirectUri();
            }
            $params[self::AUTHORIZATION_CODE_FLOW] = $_GET[self::AUTHORIZATION_CODE_FLOW];
            return $this->getAccessToken($params, $throws);
        } elseif ($throws && isset($_GET[self::RESPONSE_ERROR])) {
            throw new VkOAuthException(\sprintf(
                "Authorization code error: %s: %s",
                $_GET[self::RESPONSE_ERROR],
                $_GET[self::RESPONSE_ERROR_DESCRIPTION]
            ));
        } else {
            return [];
        }
    }
    
    /**
     * 
     * @param int $appId
     * @param string $appSecret
     * @param bool $throws
     * @throws VkOAuthException
     * @return string|null
     */
    public function getServiceToken(?int $appId = null, ?string $appSecret = null, bool $throws = true): ?string {
        if ($appId === null) {
            $appId = $this->getClientId();
        } if ($appSecret === null) {
            $appSecret = $this->getClientSecret();
        }
        
        $res = $this->getAccessToken([
            "client_id" => $appId,
            "client_secret" => $appSecret,
            "grant_type" => self::CLIENT_CREDENTIALS_FLOW,
            'v' => VkApi::CURRENT_VERSION
        ], $throws);
        return $res[self::RESPONSE_ACCESS_TOKEN] ?? null;
    }
    
    /**
     * 
     * @param array $params
     * @param bool $throws
     * @throws VkOAuthException
     * @return mixed[] [access_token, user_id, expires_in]
     */
    public function getMobileToken(array $params = [], bool $throws = true): array {
        if (isset($params["scope"]) && is_array($params["scope"])) {
            $params["scope"] = implode(',', $params["scope"]);
        } if ($throws && (!isset($params["username"]) || !isset($params["password"]))) {
            throw new VkOAuthException("Authorization failed: username and password required!");
        } 
        
        $params["grant_type"] = self::MOBILE_FLOW;
        return $this->getAccessToken($params, $throws, self::TYPE_MOBILE_TOKEN);
    }
    
    /**
     * 
     * @param array $params
     * @param bool $revoke
     * @return string
     */
    public function getAuthorizeUri(array $params = [], bool $revoke = false): string {
        if (!isset($params["client_id"])) {
            $params["client_id"] = $this->getClientId();
        } if (!isset($params["redirect_uri"])) {
            $params["redirect_uri"] = $this->getRedirectUri();
        } if (isset($params["scope"]) && is_array($params["scope"])) {
            $params["scope"] = implode(',', $params["scope"]);
        } if (isset($params["group_ids"]) && is_array($params["group_ids"])) {
            $params["group_ids"] = implode(',', $params["group_ids"]);
        } if (!isset($params['v'])) {
            $params['v'] = VkApi::CURRENT_VERSION;
        } if ($revoke) {
            $params["revoke"] = 1;
        }
        return self::ENDPOINT . self::TYPE_AUTHORIZE . '?' . http_build_query($params);
    }
    
    /**
     * 
     * @param array $params
     * @param bool $throws
     * @param string $type
     * @throws VkOAuthException
     * @return array
     */
    public function getAccessToken(array $params = [], bool $throws = true, string $type = self::TYPE_ACCESS_TOKEN): array {
        if (!isset($params["client_id"])) {
            $params["client_id"] = $this->getClientId();
        } if (!isset($params["client_secret"])) {
            $params["client_secret"] = $this->getClientSecret();
        }
        
        $url = self::ENDPOINT . $type;
        $res = VkMethod::JSON($this->getHttpClient()->get($url, [
            "query" => $params,
            "http_errors" => $throws
        ]));
        
        if (isset($res[self::RESPONSE_ERROR]) && $throws) {
            throw new VkOAuthException(\sprintf(
                "Failed to get Access Token: %s: %s",
                $res[self::RESPONSE_ERROR],
                $res[self::RESPONSE_ERROR_DESCRIPTION]
            ));
        }
        return $res;
    } 
    
    /**
     * 
     * @param string $uri
     * @return self
     */
    public function setRedirectUri(string $uri): self {
        $this->redirectUri = $uri;
        return $this;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getRedirectUri(): ?string {
        return $this->redirectUri;
    }
    
    /**
     * 
     * @param int $id
     * @return self
     */
    public function setClientId(int $id): self {
        $this->clientId = $id;
        return $this;
    }
    
    /**
     * 
     * @return int|null
     */
    public function getClientId(): ?int {
        return $this->clientId;
    }
    
    /**
     * 
     * @param string $secret
     * @return self
     */
    public function setClientSecret(string $secret): self {
        $this->clientSecret = $secret;
        return $this;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getClientSecret(): ?string {
        return $this->clientSecret;
    }
    
    /**
     * 
     * @param ClientInterface $client
     * @return self
     */
    public function setHttpClient(ClientInterface $client): self {
        $this->httpClient = $client;
        return $this;
    }
    
    /**
     * 
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface {
        if ($this->httpClient === null) {
            $this->httpClient = new Client([
                "handler" => HandlerStack::create(new StreamHandler()),
                "cookies" => true
            ]);
        }
        return $this->httpClient;
    } 
}
