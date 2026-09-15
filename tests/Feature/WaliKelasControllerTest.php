<?php

namespace Tests\Feature;

use Tests\TestCase;

class WaliKelasControllerTest extends TestCase
{
    public function test_unauthenticated_root_request_redirects_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }
}
