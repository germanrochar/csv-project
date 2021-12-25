<?php

namespace Tests\Unit;

use App\Models\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_record_custom_attributes(): void
    {
        /** @var Contact $contact */
        $contact = Contact::factory()->create();

        $this->assertCount(0, $contact->customAttributes()->get());

        $contact->addCustomAttribute('test-key', '123');

        $this->assertCount(1, $contact->customAttributes()->get());

    }

}
