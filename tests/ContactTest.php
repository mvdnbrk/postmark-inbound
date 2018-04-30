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
}
