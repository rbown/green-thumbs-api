<?php

namespace Tests\Feature;

use App\Models\Plant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PlantTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_validates_the_required_fields()
    {
        $this->json('POST', 'api/plants')
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => ['The name field is required.'],
                    'species' => ['The species field is required.'],
                ]
            ]);
    }

    /** @test */
    public function it_stores_a_new_plant_in_the_database()
    {
        $plant = Plant::factory()->make();

        $this->json('POST', 'api/plants', $plant->toArray())
            ->assertStatus(200);

        $this->assertDatabaseHas('plants', [
            'name' => $plant->name,
            'species' => $plant->species,
            'watering_instructions' => $plant->watering_instructions
        ]);
    }

    /** @test */
    public function it_deletes_a_plant_from_the_database()
    {
        $plant = Plant::factory()->create();

        $this->assertDatabaseHas('plants', [
            'id' => $plant->id
        ]);

        $this->delete('/api/plants/'.$plant->id);

        $this->assertDatabaseMissing('plants', [
            'id' => $plant->id
        ]);
    }

    /** @test */
    public function it_updates_a_plant_in_the_database()
    {
        $plant = Plant::factory()->create();

        $plant->name = 'My New Name';

        $this->put('/api/plants/'.$plant->id, $plant->toArray())
            ->assertJson([
                'data' => [
                    'name' => 'My New Name'
                ]
            ]);
    }
}
