<?php

namespace Tests\Feature;

use Tests\TestCase;

class AcceptApplicationJsonTest extends TestCase
{
    public function testItFailsIfRequestDoesNotHaveApplicationJsonHeader(): void
    {
        $response = $this->get('/api/welcome');

        $response->assertStatus(406);
    }

    public function testResponseOkStatusIfRequestHaveApplicationJsonHeader(): void
    {
        $response = $this->get('/api/welcome', ['Accept' => 'application/json']);

        $response->assertStatus(200);
    }
}
