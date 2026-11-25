<?php

namespace Mvdnbrk\Postmark\Tests;

use Mvdnbrk\Postmark\InboundMessage;
use Mvdnbrk\Postmark\Support\PostmarkDate;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class InboundMessageTest extends TestCase
{
    private InboundMessage $message;

    private function validJson(array $overrides = []): string
    {
        return json_encode(
            array_merge(
                json_decode(file_get_contents(__DIR__.'/fixtures/inbound.json'), true),
                $overrides
            )
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->message = new InboundMessage($this->validJson());
    }

    #[Test]
    public function a_valid_json_source_is_required(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new InboundMessage('not-a-valid-json-resource');
    }

    #[Test]
    public function getting_unknown_getter_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->message->doesNotExist;
    }

    #[Test]
    public function message_has_a_date(): void
    {
        $this->assertInstanceOf(PostmarkDate::class, $this->message->date);
        $this->assertEquals('2017-09-06 19:11:00 +0200', $this->message->date->format('Y-m-d H:i:s O'));
    }

    #[Test]
    public function can_get_the_original_date_from_the_message(): void
    {
        $this->assertEquals('Wed, 6 Sep 2017 19:11:00 +0200', $this->message->originalDate);
    }

    #[Test]
    public function message_has_a_timezone(): void
    {
        $this->assertEquals('+02:00', $this->message->timezone);
    }

    #[Test]
    public function message_has_a_sender(): void
    {
        $this->assertEquals('john@example.com', $this->message->from->email);
        $this->assertEquals('John Doe', $this->message->from->name);
        $this->assertEquals('John Doe <john@example.com>', $this->message->from->full);
    }

    #[Test]
    public function message_has_to_recpients(): void
    {
        $this->assertEquals(1, $this->message->to->count());
        $this->assertEquals('jane+ahoy@inbound.postmarkapp.com', $this->message->to->first()->email);
        $this->assertEquals('Jane Doe', $this->message->to->first()->name);
    }

    #[Test]
    public function message_has_cc_recpients(): void
    {
        $this->assertEquals(2, $this->message->cc->count());
        $this->assertEquals('sample.cc@example.com', $this->message->cc->first()->email);
        $this->assertEquals('Full name Cc', $this->message->cc->first()->name);
        $this->assertEquals('another.cc@example.com', $this->message->cc->last()->email);
        $this->assertEquals('Another Cc', $this->message->cc->last()->name);
    }

    #[Test]
    public function message_has_bcc_recpients(): void
    {
        $this->assertEquals(1, $this->message->bcc->count());
        $this->assertEquals('sample.bcc+ahoy@inbound.postmarkapp.com', $this->message->bcc->first()->email);
        $this->assertEquals('Full name Bcc', $this->message->bcc->first()->name);
        $this->assertEquals('ahoy', $this->message->bcc->first()->mailboxHash);
    }

    #[Test]
    public function message_has_a_subject(): void
    {
        $this->assertEquals('Postmark inbound message test', $this->message->subject);
    }

    #[Test]
    public function message_can_have_an_empty_subject(): void
    {
        $this->message = new InboundMessage($this->validJson([
            'Subject' => '',
        ]));

        $this->assertEmpty($this->message->subject);
    }

    #[Test]
    public function incorrect_date_formats_posted_by_postmark_should_pass(): void
    {
        $this->message = new InboundMessage($this->validJson([
            'Date' => 'Fri, 27 Apr 2018 19:00:00 +0200 (CEST)',
        ]));
        $this->assertEquals('2018-04-27 19:00:00', $this->message->date->format('Y-m-d H:i:s'));

        $this->message = new InboundMessage($this->validJson([
            'Date' => 'Fri, 27 Apr 2018 19:00:00 +0100 (West-Europe (stand',
        ]));
        $this->assertEquals('2018-04-27 19:00:00', $this->message->date->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function message_has_an_id(): void
    {
        $this->assertEquals('a123456-b1234-c123456-d1234', $this->message->messageId);
    }

    #[Test]
    public function message_has_a_tag(): void
    {
        $this->assertEquals('test-tag', $this->message->tag);
    }

    #[Test]
    public function message_has_an_original_recipient(): void
    {
        $this->assertEquals('1234+ahoy@inbound.postmarkapp.com', $this->message->originalRecipient);
    }

    #[Test]
    public function message_has_a_text_body(): void
    {
        $this->assertEquals('[ASCII]', $this->message->textBody);
    }

    #[Test]
    public function message_has_a_html_body(): void
    {
        $this->assertEquals('<html></html>', $this->message->htmlBody);
    }

    #[Test]
    public function message_has_stripped_text_reply(): void
    {
        $this->assertEquals('Okay, thank you for testing this inbound message!', $this->message->strippedTextReply);
    }

    #[Test]
    public function message_has_a_reply_to_address(): void
    {
        $this->assertEquals('reply-to@example.com', $this->message->replyTo);
    }

    #[Test]
    public function message_has_headers(): void
    {
        $this->assertEquals(8, $this->message->headers->count());
        $this->assertEquals('<test-message-id@mail.example.com>', $this->message->headers->get('Message-ID'));
        $this->assertEquals('1.0', $this->message->headers->get('MIME-Version'));
        $this->assertEquals('Pass (sender SPF authorized)', $this->message->headers->get('Received-SPF'));
        $this->assertEquals('-0.1', $this->message->headers->get('X-Spam-Score'));
        $this->assertEquals('No', $this->message->headers->get('X-Spam-Status'));
        $this->assertEquals('DKIM_SIGNED,DKIM_VALID,SPF_PASS', $this->message->headers->get('X-Spam-Tests'));
        $this->assertEquals('SpamAssassin 3.3.1', $this->message->headers->get('X-Spam-Checker-Version'));
    }

    #[Test]
    public function message_has_attachments(): void
    {
        $this->assertEquals(2, $this->message->attachments->count());

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

    #[Test]
    public function message_has_no_attachments_when_not_present_in_json_payload(): void
    {
        $this->message = new InboundMessage('{}');
        $this->assertEquals(0, $this->message->attachments->count());

        $this->message = new InboundMessage($this->validJson([
            'Attachments' => [],
        ]));
        $this->assertEquals(0, $this->message->attachments->count());
    }

    #[Test]
    public function get_message_id_from_headers(): void
    {
        $this->message = new InboundMessage($this->validJson([
            'Headers' => [[
                'Name' => 'Message-Id',
                'Value' => '<message-id-from-headers>',
            ]],
        ]));

        $this->assertEquals('<message-id-from-headers>', $this->message->messageIdFromHeaders);
    }

    #[Test]
    public function can_retrieve_spam_score_from_message(): void
    {
        $this->message = new InboundMessage($this->validJson([
            'Headers' => [],
        ]));
        $this->assertSame(0.0, $this->message->spamScore);

        $this->message = new InboundMessage($this->validJson([
            'Headers' => [[
                'Name' => 'X-Spam-Score',
                'Value' => '1.0',
            ]],
        ]));

        $this->assertSame(1.0, $this->message->spamScore);

        $this->message = new InboundMessage($this->validJson([
            'Headers' => [[
                'Name' => 'X-Spam-Score',
                'Value' => '-1.0',
            ]],
        ]));

        $this->assertSame(-1.0, $this->message->spamScore);
    }

    #[Test]
    public function can_retrieve_spam_status_value(): void
    {
        $this->message = new InboundMessage($this->validJson([
            'Headers' => [],
        ]));
        $this->assertEquals('No', $this->message->spamStatus);

        $this->message = new InboundMessage($this->validJson([
            'Headers' => [[
                'Name' => 'X-Spam-Status',
                'Value' => 'Yes',
            ]],
        ]));

        $this->assertEquals('Yes', $this->message->spamStatus);
    }

    #[Test]
    public function can_determine_if_message_is_spam(): void
    {
        $this->message = new InboundMessage($this->validJson([
            'Headers' => [],
        ]));
        $this->assertFalse($this->message->isSpam);

        $this->message = new InboundMessage($this->validJson([
            'Headers' => [[
                'Name' => 'X-Spam-Status',
                'Value' => 'Yes',
            ]],
        ]));
        $this->assertTrue($this->message->isSpam);

        $this->message = new InboundMessage($this->validJson([
            'Headers' => [[
                'Name' => 'X-Spam-Status',
                'Value' => 'yes',
            ]],
        ]));
        $this->assertTrue($this->message->isSpam);
    }

    #[Test]
    public function trying_to_access_the_headers_attribute_should_return_empty_collection_when_not_present(): void
    {
        $this->message = new InboundMessage('{}');

        $this->assertSame([], $this->message->headers->toArray());
    }
}
