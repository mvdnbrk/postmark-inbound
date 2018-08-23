<?php

namespace Mvdnbrk\Postmark\Tests;

use Mvdnbrk\Postmark\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    /** @test */
    public function contact_is_constructed()
    {
        $c = new Contact('John', 'john@example.com', 'hash');
        $this->assertEquals('John', $c->name);
        $this->assertEquals('john@example.com', $c->email);
        $this->assertEquals('hash', $c->mailboxHash);
        $this->assertEquals('John <john@example.com>', $c->full);
    }

    /** @test */
    public function email_address_with_leading_or_trailing_spaces_is_trimmed()
    {
        $c = new Contact('John', '    john@example.com', 'hash');
        $this->assertEquals('john@example.com', $c->email);

        $c = new Contact('John', 'john@example.com    ', 'hash');
        $this->assertEquals('john@example.com', $c->email);

        $c = new Contact('John', '    john@example.com    ', 'hash');
        $this->assertEquals('john@example.com', $c->email);
    }

    /** @test */
    public function name_with_leading_or_trailing_spaces_is_trimmed()
    {
        $c = new Contact('    John', 'john@example.com', 'hash');
        $this->assertEquals('John', $c->name);

        $c = new Contact('John    ', 'john@example.com', 'hash');
        $this->assertEquals('John', $c->name);

        $c = new Contact('    John    ', 'john@example.com', 'hash');
        $this->assertEquals('John', $c->name);
    }

    /** @test */
    public function email_address_with_non_allowed_prefix_is_stripped_off()
    {
        $c = new Contact('John', ' ^   john@example.com', 'hash');
        $this->assertEquals('john@example.com', $c->email);
    }

    /** @test */
    public function a_quoted_name_should_be_unquoted()
    {
        $c = new Contact('"John"', 'john@example.com', 'hash');
        $this->assertEquals('John', $c->name);
        $this->assertEquals('John <john@example.com>', $c->full);

        $c = new Contact("'John'", 'john@example.com', 'hash');
        $this->assertEquals('John', $c->name);
        $this->assertEquals('John <john@example.com>', $c->full);
    }

    /** @test */
    public function hash_is_optional()
    {
        $c = new Contact('John', 'john@example.com');
        $this->assertEquals('John', $c->name);
        $this->assertEquals('john@example.com', $c->email);
        $this->assertNull($c->mailboxHash);
        $this->assertEquals('John <john@example.com>', $c->full);
    }
}
