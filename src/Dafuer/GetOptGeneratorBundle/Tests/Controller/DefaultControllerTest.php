<?php

namespace Dafuer\GetOptGeneratorBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testBasicAccessDefaultController()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('span[data-test-status=ok]')->count() > 0);
        $this->assertFalse($crawler->filter('span[data-test-status=error]')->count() > 0);
        $this->assertTrue($crawler->filter('span[data-test-route=DafuerGetOptGeneratorBundle_homepage]')->count() > 0);
        
        $crawler = $client->request('GET', '/start');

        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('span[data-test-status=ok]')->count() > 0);
        $this->assertFalse($crawler->filter('span[data-test-status=error]')->count() > 0);
        $this->assertTrue($crawler->filter('span[data-test-route=DafuerGetOptGeneratorBundle_start]')->count() > 0);
        
        $crawler = $client->request('GET', '/more-information');

        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('span[data-test-status=ok]')->count() > 0);
        $this->assertFalse($crawler->filter('span[data-test-status=error]')->count() > 0);      
        $this->assertTrue($crawler->filter('span[data-test-route=DafuerGetOptGeneratorBundle_moreinformation]')->count() > 0);
           
    }
}
