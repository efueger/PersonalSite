<?php

namespace Tests\Functional;

class PortfolioTest extends BaseTestCase
{
    public function testGetPortfolioIndex()
    {
        $response = $this->runApp('GET', '/portfolio');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('URLs', (string)$response->getBody());
    }

    public function testGetPortfolioProject()
    {
        $response = $this->runApp('GET', '/portfolio/we-are-social');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Final project', (string)$response->getBody());
    }
}
