<?php

namespace VkLib\Tests;

use PHPUnit\Framework\TestCase;

use VkLib\VkClient;
use VkLib\VkApi;
use VkLib\Exception\VkMethodException;
use VkLib\Method\Error;

class VkApiTest extends TestCase {
    
    public function testRequest() {
        $cl = new VkClient("methods");
        
        try {
            $cl->getApi()->users()->get();
        } catch(VkMethodException $ex) {
            $this->assertInstanceOf(Error::class, $ex->getError());
            $this->assertEquals(5, $ex->getError()->getCode());
        }
        
        try {
            $api = new VkApi("methods");
            $api->users()->get();
        } catch(VkMethodException $ex) {
            $this->assertInstanceOf(Error::class, $ex->getError());
            $this->assertEquals(5, $ex->getError()->getCode());
        }
    }
}