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
 *
 * @copyright 2019-2020 Rollylni
 * @author Faruch N. <rollyllni@gmail.com>
 * @version 0.7 beta
 * @license MIT
 */
namespace VkLib\Callback;

use function method_exists;
use function json_decode;
use function file_get_contents;

/**
 * 
 * @link https://vk.com/dev/callback_api
 */
abstract class CallbackHandler {
    
    /** @var string*/
    public $token = "";
    
    /**
     * CallbackServer constructor.
     */
    public function __construct() {
        $input = $this->readData();
        if ($input) {
            $this->handle($input);
        }
    }
    
    /**
     * send token for confirmation
     */
    public function confirmation() {
        print $this->token;
    }


    /**
     * 
     * @param array $data
     */
    public function handle(array $data) {
        $type = $data["type"] ?? null;
        $obj = $data["object"] ?? null;
        $gid = $data["group_id"] ?? null;
        
        if ($type === "confirmation") {
            $this->confirmation($gid);
            return;
        }
        
        if (method_exists($this, $type)) {
            $this->{$type}($obj, $gid);
        }
        print "ok";
    }
    
    /**
     * Read Post Data
     * 
     * @return array|null
     */
    public static function readData(): ?array {
        return json_decode(file_get_contents("php://input"), true);
    }
}