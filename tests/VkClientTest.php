<?php

namespace VkLib\Tests;

use PHPUnit\Framework\TestCase;

use VkLib\VkClient;
use VkLib\HttpClient\HttpClient;
use VkLib\Exception\VkClientException;
use VkLib\VkApi;

class VkClientCase extends TestCase {
    
    public function testClient() {
       new VkClient("myclient");
       $this->assertInstanceOf(VkClient::class, VkClient::getClient("myclient"));
       
       $this->assertEquals(VkClient::DEFAULT_CLIENT, VkClient::getClient());
       new VkClient();
       $this->assertInstanceOf(VkClient::class, VkClient::getClient());
    }
    
    public function testClientProperties() {
        new VkClient("myc", 5.103, "ru");
        
        $client = VkClient::getClient("myc");
        $this->assertEquals("myc", $client->getName());
        $this->assertEquals(5.103, $client->getVersion());
        $this->assertEquals("ru", $client->getLang());
        $this->assertNull($client->getToken());
        
        $this->assertInstanceOf(HttpClient::class, $client->getHttpClient());
        $this->assertInstanceof(VkApi::class, $client->getApi());
    }
    
    public function testCheckAndDelete() {
        $this->assertInstanceOf(VkClient::class, VkClient::getClient());
        VkClient::removeClient(VkClient::DEFAULT_CLIENT);
        $this->assertEquals(VkClient::DEFAULT_CLIENT, VkClient::getClient());
        
        $this->expectException(VkClientException::class);
        VkClient::checkClient();
    }
    
}