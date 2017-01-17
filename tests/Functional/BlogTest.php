<?php

namespace Tests\Functional;

class BlogTest extends BaseTestCase
{
    public function testGetBlogIndex()
    {
        $response = $this->runApp('GET', '/blog');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Test Post 3', (string)$response->getBody());
    }

    public function testGetBlogPost()
    {
        $response = $this->runApp('GET', '/blog/test-post');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Test Post', (string)$response->getBody());
    }

    public function testInvalidSlugThrows404()
    {
        $response = $this->runApp('GET', '/blog/invalid-slug');
        $this->assertEquals(404, $response->getStatusCode());
    }
}
