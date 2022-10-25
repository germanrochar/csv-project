<?php

namespace Tests\Unit;

use App\Models\Contact;
use App\Models\CustomAttribute;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_has_many_custom_attributes_relation(): void
    {
        /** @var Contact $contact */
        $contact = Contact::factory()->create();
        $contact->addCustomAttribute('test_key', '123');

        self::assertInstanceOf(CustomAttribute::class, $contact->customAttributes()->first());
        self::assertCount(1, $contact->customAttributes()->get());
    }

    /** @test */
    public function it_can_record_custom_attributes(): void
    {
        /** @var Contact $contact */
        $contact = Contact::factory()->create();

        self::assertCount(0, $contact->customAttributes()->get());

        $contact->addCustomAttribute('test_key', '123');

        self::assertCount(1, $contact->customAttributes()->get());
    }

    /** @test */
    public function it_returns_an_allowed_list_of_columns_that_can_be_imported_into_contacts_table(): void
    {
        self::assertSame(['name', 'phone', 'email', 'sticky_phone_number_id'], Contact::getColumnsAllowedToImport());
    }
}
