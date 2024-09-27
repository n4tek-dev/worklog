<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateEmployee()
    {
        $response = $this->postJson('/api/employees', [
            'first_name' => 'Karol',
            'last_name' => 'Szabat'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'response' => [
                         'id'
                     ]
                 ]);

        $this->assertDatabaseHas('employees', [
            'first_name' => 'Karol',
            'last_name' => 'Szabat'
        ]);
    }
}