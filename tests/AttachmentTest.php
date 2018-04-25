<?php

namespace Mvdnbrk\Postmark\Test;

use Mvdnbrk\Postmark\Attachment;
use PHPUnit\Framework\TestCase;

class AttachmentTest extends TestCase
{
    /** @test */
    public function attachment_is_constructed()
    {
        $a = new Attachment('test.txt', 'some-unqiue-id', 'plain/text', 8, 'dGVzdA==');
        $this->assertEquals('test.txt', $a->name);
        $this->assertEquals('some-unqiue-id', $a->contentId);
        $this->assertEquals('plain/text', $a->contentType);
        $this->assertEquals(8, $a->contentLength);
        $this->assertEquals('test', $a->content());
    }
}
