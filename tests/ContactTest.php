<?php

namespace Mvdnbrk\Postmark\Tests;

use Mvdnbrk\Postmark\Contact;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    #[Test]
    public function contact_is_constructed(): void
    {
        $c = new Contact('John', 'john@example.com', 'hash');
        $this->assertEquals('John', $c->name);
        $this->assertEquals('john@example.com', $c->email);
        $this->assertEquals('hash', $c->mailboxHash);
        $this->assertEquals('John <john@example.com>', $c->full);
    }

    #[Test]
    public function email_address_with_leading_or_trailing_spaces_is_trimmed(): void
    {
        $c = new Contact('John', '    john@example.com', 'hash');
        $this->assertEquals('john@example.com', $c->email);

        $c = new Contact('John', 'john@example.com    ', 'hash');
        $this->assertEquals('john@example.com', $c->email);

        $c = new Contact('John', '    john@example.com    ', 'hash');
        $this->assertEquals('john@example.com', $c->email);
    }

    #[Test]
    public function name_with_leading_or_trailing_spaces_is_trimmed(): void
    {
        $c = new Contact('    John', 'john@example.com', 'hash');
        $this->assertEquals('John', $c->name);

        $c = new Contact('John    ', 'john@example.com', 'hash');
        $this->assertEquals('John', $c->name);

        $c = new Contact('    John    ', 'john@example.com', 'hash');
        $this->assertEquals('John', $c->name);
    }

    #[Test]
    public function email_address_with_non_allowed_prefix_is_stripped_off(): void
    {
        $c = new Contact('John', ' ^   john@example.com', 'hash');
        $this->assertEquals('john@example.com', $c->email);
    }

    #[Test]
    public function a_quoted_name_should_be_unquoted(): void
    {
        $c = new Contact('"John"', 'john@example.com', 'hash');
        $this->assertEquals('John', $c->name);
        $this->assertEquals('John <john@example.com>', $c->full);

        $c = new Contact("'John'", 'john@example.com', 'hash');
        $this->assertEquals('John', $c->name);
        $this->assertEquals('John <john@example.com>', $c->full);
    }

    #[Test]
    public function hash_is_optional(): void
    {
        $c = new Contact('John', 'john@example.com');
        $this->assertEquals('John', $c->name);
        $this->assertEquals('john@example.com', $c->email);
        $this->assertNull($c->mailboxHash);
        $this->assertEquals('John <john@example.com>', $c->full);
    }
}
