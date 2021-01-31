<?php

namespace VkLib\Tests;

use PHPUnit\Framework\TestCase;

use VkLib\VkClient;
use VkLib\Method\VkMethod;
use VkLib\Method\Response;

class VkMethodTest extends TestCase {
    
    public function testMethod() {
        $client = new VkClient("UserClient");
        $client->setToken("");
        $method = new VkMethod("users.get");
        
        $this->assertEmpty($method->getParameters());
        $method->setAccessToken($client);
        $this->assertArrayHasKey("access_token", $method->getParameters());
        
        return $method;
    }
    
    /**
     * 
     * @depends testMethod
     */
    public function testCalling(VkMethod $method) {
        $res = $method->call();
        $this->assertInstanceOf(Response::class, $res);
        
        return $res;
    }
    
    /**
     * 
     * @depends testCalling
     */
    public function testResponse(Response $res) {
        $this->assertIsString($res->getFirstName(0));
        $this->assertIsString($res->getLastName(0));
    }
}
