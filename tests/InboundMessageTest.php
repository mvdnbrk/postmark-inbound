<?php

namespace Mvdnbrk\Postmark\Test;

use Mvdnbrk\Postmark\InboundMessage;
use PHPUnit\Framework\TestCase;

class InboundMessageTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->message = new InboundMessage(file_get_contents('./tests/fixtures/inbound.json'));
    }

    /** @test */
    public function a_valid_json_source_is_required()
    {
        $this->expectException(\InvalidArgumentException::class);
        new InboundMessage('not-a-valid-json-source');
    }

    /** @test */
    public function message_has_a_date()
    {
        $this->assertEquals($this->message->date, 'Wed, 6 Sep 2017 19:11:00 +0200');
    }

    /** @test */
    public function message_has_a_timezone()
    {
        $this->assertEquals($this->message->timezone, '+02:00');
    }

    /** @test */
    public function message_has_utc_date()
    {
        $this->assertEquals($this->message->utcDate, '2017-09-06 17:11:00');
        $this->assertEquals($this->message->timezone, '+02:00');
    }

    /** @test */
    public function message_has_a_sender()
    {
        $this->assertEquals($this->message->from->email, 'john@example.com');
        $this->assertEquals($this->message->from->name, 'John Doe');
        $this->assertEquals($this->message->from->full, 'John Doe <john@example.com>');
    }

    /** @test */
    public function message_has_to_recpients()
    {
        $this->assertEquals($this->message->to->count(), 1);
        $this->assertEquals($this->message->to->first()->email, 'jane+ahoy@inbound.postmarkapp.com');
        $this->assertEquals($this->message->to->first()->name, 'Jane Doe');
    }

    /** @test */
    public function message_has_cc_recpients()
    {
        $this->assertEquals($this->message->cc->count(), 2);
        $this->assertEquals($this->message->cc->first()->email, 'sample.cc@example.com');
        $this->assertEquals($this->message->cc->first()->name, 'Full name Cc');
        $this->assertEquals($this->message->cc->last()->email, 'another.cc@example.com');
        $this->assertEquals($this->message->cc->last()->name, 'Another Cc');
    }

    /** @test */
    public function message_has_bcc_recpients()
    {
        $this->assertEquals($this->message->bcc->count(), 1);
        $this->assertEquals($this->message->bcc->first()->email, 'sample.bcc+ahoy@inbound.postmarkapp.com');
        $this->assertEquals($this->message->bcc->first()->name, 'Full name Bcc');
        $this->assertEquals($this->message->bcc->first()->mailboxHash, 'ahoy');
    }

    /** @test */
    public function message_has_a_subject()
    {
        $this->assertEquals($this->message->subject, 'Postmark inbound message test');
    }

    /** @test */
    public function message_has_an_id()
    {
        $this->assertEquals($this->message->messageId, 'a123456-b1234-c123456-d1234');
    }

    /** @test */
    public function message_has_a_tag()
    {
        $this->assertEquals($this->message->tag, 'test-tag');
    }

    /** @test */
    public function message_has_an_original_recipient()
    {
        $this->assertEquals($this->message->originalRecipient, '1234+ahoy@inbound.postmarkapp.com');
    }

    /** @test */
    public function message_has_a_text_body()
    {
        $this->assertEquals($this->message->textBody, '[ASCII]');
    }

    /** @test */
    public function message_has_a_html_body()
    {
        $this->assertEquals($this->message->htmlBody, '<html></html>');
    }

    /** @test */
    public function message_has_stripped_text_reply()
    {
        $this->assertEquals($this->message->strippedTextReply, 'Okay, thank you for testing this inbound message!');
    }

    /** @test */
    public function message_has_a_reply_to_address()
    {
        $this->assertEquals($this->message->replyTo, 'reply-to@example.com');
    }

    /** @test */
    public function message_has_headers()
    {
        $this->assertEquals($this->message->headers->count(), 8);
        $this->assertEquals($this->message->headers->get('Message-ID'), '<test-messag-id@mail.example.com>');
        $this->assertEquals($this->message->headers->get('MIME-Version'), '1.0');
        $this->assertEquals($this->message->headers->get('Received-SPF'), 'Pass (sender SPF authorized)');
        $this->assertEquals($this->message->headers->get('X-Spam-Score'), '-0.1');
        $this->assertEquals($this->message->headers->get('X-Spam-Status'), 'No');
        $this->assertEquals($this->message->headers->get('X-Spam-Tests'), 'DKIM_SIGNED,DKIM_VALID,SPF_PASS');
        $this->assertEquals($this->message->headers->get('X-Spam-Checker-Version'), 'SpamAssassin 3.3.1');
    }

    /** @test */
    public function message_has_attachments()
    {
        $this->assertEquals($this->message->attachments->count(), 2);

        $attachment = $this->message->attachments->first();
        $this->assertEquals('myimage.png', $attachment->name);
        $this->assertEquals('image/png', $attachment->contentType);
        $this->assertEquals(4096, $attachment->contentLength);
        $this->assertEquals('myimage.png@01CE7342.75E71F80', $attachment->contentId);

        $attachment = $this->message->attachments->last();
        $this->assertEquals('test.txt', $attachment->name);
        $this->assertEquals('plain/text', $attachment->contentType);
        $this->assertEquals(8, $attachment->contentLength);
        $this->assertNull($attachment->contentId);
        $this->assertEquals('test', $attachment->content());
    }

    /** @test */
    public function message_has_no_attachments_when_not_present_in_json_payload()
    {
        $this->message = new InboundMessage(file_get_contents('./tests/fixtures/inbound-camelcase-message-id.json'));
        $this->assertEquals($this->message->attachments->count(), 0);
    }

    /** @test */
    public function get_message_id_from_headers()
    {
        $this->assertEquals('<test-messag-id@mail.example.com>', $this->message->messageIdFromHeaders);

        $this->message = new InboundMessage(file_get_contents('./tests/fixtures/inbound-camelcase-message-id.json'));
        $this->assertEquals('<test-messag-id@mail.example.com>', $this->message->messageIdFromHeaders);
    }
}
