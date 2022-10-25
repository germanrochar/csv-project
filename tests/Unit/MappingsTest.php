<?php

namespace Tests\Unit;

use App\Mappings;
use LogicException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MappingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created_from_two_arrays(): void
    {
        $keys = ['name', 'phone', 'email'];
        $values = ['full_name', 'phone_number', 'my_email'];

        $mappings = new Mappings($keys, $values);

        self::assertCount(3, $mappings->getAll());
        self::assertSame('phone_number', $mappings->get('phone'));
        self::assertTrue($mappings->has('name'));
    }

    /** @test */
    public function it_cannot_be_initialized_if_arrays_dont_have_same_length(): void
    {
        $keys = ['name', 'phone'];
        $values = ['full_name', 'phone_number', 'my_email'];

        self::expectException(LogicException::class);
        new Mappings($keys, $values);
    }

    /** @test */
    public function it_can_return_a_list_of_custom_mappings(): void
    {
        $keys = ['name', 'phone', 'email', 'custom_key'];
        $values = ['full_name', 'phone_number', 'my_email', 'custom_value'];

        $mappings = new Mappings($keys, $values);

        $customMappings = $mappings->getCustomMappings();

        self::assertCount(1, $customMappings);
        self::assertEquals(['custom_key' => 'custom_value'], $customMappings);
    }

    /** @test */
    public function it_can_return_all_mappings(): void
    {
        $keys = ['name', 'phone', 'email'];
        $values = ['full_name', 'phone_number', 'my_email'];

        $mappings = new Mappings($keys, $values);

        self::assertCount(3, $mappings->getAll());
        self::assertSame([
            'name' => 'full_name',
            'phone' => 'phone_number',
            'email' => 'my_email'
        ], $mappings->getAll());
    }

    /** @test */
    public function it_knows_if_a_mapping_exists(): void
    {
        $keys = ['name', 'phone', 'email'];
        $values = ['full_name', 'phone_number', 'my_email'];

        $mappings = new Mappings($keys, $values);

        self::assertTrue($mappings->has('name'));
        self::assertTrue($mappings->has('phone'));
        self::assertTrue($mappings->has('email'));
        self::assertFalse($mappings->has('another_key'));
    }

    /** @test */
    public function it_returns_a_value_if_mapping_key_is_provided(): void
    {
        $keys = ['name', 'phone', 'email'];
        $values = ['full_name', 'phone_number', 'my_email'];

        $mappings = new Mappings($keys, $values);

        self::assertSame('full_name', $mappings->get('name'));
    }

    /** @test */
    public function it_returns_null_when_fetching_a_mapping_value_if_mapping_key_does_not_exist(): void
    {
        $keys = ['name', 'phone', 'email'];
        $values = ['full_name', 'phone_number', 'my_email'];

        $mappings = new Mappings($keys, $values);

        self::assertNull($mappings->get('my_key'));
    }
}
