<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ModelTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_models(): void
    {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->get('api/assistant/models');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'type',
                ],
            ],
        ]);
    }
}
