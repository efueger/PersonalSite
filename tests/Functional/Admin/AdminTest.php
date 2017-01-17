<?php

namespace Tests\Functional\Admin;

use Tests\Functional\BaseTestCase;

class AdminTest extends BaseTestCase
{
    public function testAdminURLRedirectsToLoginIfNotAuthenticated()
    {
        $response = $this->runApp('GET', '/admin');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/login', $response->getHeaders()['Location'][0]);
    }
}