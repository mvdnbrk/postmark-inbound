<?php

namespace Heyhoo\Postmark\Test;

use Heyhoo\Postmark\Attachment;
use PHPUnit\Framework\TestCase;

class AttachmentTest extends TestCase
{
    /** @test */
    public function attachment_is_constructed()
    {
        $a = new Attachment('test.txt', 'some-unqiue-id', 'plain/text', 8, 'dGVzdA==');
        $this->assertEquals($a->name, 'test.txt');
        $this->assertEquals($a->contentId, 'some-unqiue-id');
        $this->assertEquals($a->contentType, 'plain/text');
        $this->assertEquals($a->contentLength, 8);
        $this->assertEquals($a->content(), 'test');
    }
}
