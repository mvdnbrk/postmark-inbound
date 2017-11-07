<?php

namespace Mvdnbrk\Postmark\Test;

use Mvdnbrk\Postmark\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    /** @test */
    public function contact_is_constructed()
    {
        $c = new Contact('John', 'john@example.com', 'hash');
        $this->assertEquals($c->name, 'John');
        $this->assertEquals($c->email, 'john@example.com');
        $this->assertEquals($c->mailboxHash, 'hash');
        $this->assertEquals($c->full, 'John <john@example.com>');
    }
}
