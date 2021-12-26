<?php

namespace Tests\Unit;

use App\Mappings;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MappingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created_from_two_arrays(): void
    {
        $keys = ['orange', 'apple', 'banana'];
        $values = ['12dlls', '4dlls', '10dlls'];

        $mappings = new Mappings($keys, $values);

        $this->assertCount(3, $mappings->getAll());
        $this->assertEquals('12dlls', $mappings->get('orange'));
        $this->assertTrue($mappings->has('apple'));
    }

    /** @test */
    public function it_cannot_be_initialized_if_arrays_dont_have_same_length(): void
    {
        $keys = ['orange', 'apple'];
        $values = ['12dlls', '4dlls', '10dlls'];

        $this->expectException(\LogicException::class);
        new Mappings($keys, $values);
    }
}
