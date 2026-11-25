<?php

namespace Mvdnbrk\Postmark;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use Mvdnbrk\Postmark\Support\PostmarkDate;

/**
 * API to process Postmark Inbound Webhooks.
 *
 * @property-read \Mvdnbrk\Postmark\Contact $from
 * @property-read \Illuminate\Support\Collection<int, \Mvdnbrk\Postmark\Attachment> $attachments
 * @property-read \Illuminate\Support\Collection<int, \Mvdnbrk\Postmark\Contact> $bcc
 * @property-read \Illuminate\Support\Collection<int, \Mvdnbrk\Postmark\Contact> $cc
 * @property-read \Illuminate\Support\Collection<string, string> $headers
 * @property-read \Illuminate\Support\Collection<int, \Mvdnbrk\Postmark\Contact> $to
 * @property-read \Mvdnbrk\Postmark\Support\PostmarkDate $date
 * @property-read bool $isSpam
 * @property-read string $htmlBody
 * @property-read string $mailboxHash
 * @property-read string $messageId MessageID assigned by Postmark.
 * @property-read string|null $messageIdFromHeaders Message-ID value from headers.
 * @property-read string $messageStream
 * @property-read string|null $originalDate
 * @property-read string $originalRecipient
 * @property-read string $replyTo
 * @property-read float $spamScore
 * @property-read string $spamStatus
 * @property-read string $strippedTextReply
 * @property-read string $subject
 * @property-read string $tag
 * @property-read string $textBody
 * @property-read string $timezone
 */
class InboundMessage
{
    /**
     * Collection of the json data.
     */
    protected Collection $data;

    /**
     * DateTime when the message was received.
     */
    protected PostmarkDate $datetime;

    /**
     * Create a new InboundMessage instance.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(?string $json = null)
    {
        $this->data = collect(json_decode((string) $json, true));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('You must provide a valid JSON source.');
        }

        $this->datetime = PostmarkDate::parse($this->data->get('Date'));
    }

    /**
     * Retrieve the collection of attachments.
     *
     * @return \Illuminate\Support\Collection<int, \Mvdnbrk\Postmark\Attachment>
     */
    public function getAttachmentsAttribute(): Collection
    {
        return collect($this->data->get('Attachments', []))
            ->map(function ($data) {
                $attachment = collect($data);

                return new Attachment(
                    $attachment->get('Name'),
                    $attachment->get('ContentID'),
                    $attachment->get('ContentType'),
                    $attachment->get('ContentLength'),
                    $attachment->get('Content')
                );
            });
    }

    /**
     * Retrieve the collection of bcc recipients.
     *
     * @return \Illuminate\Support\Collection<int, \Mvdnbrk\Postmark\Contact>
     */
    public function getBccAttribute(): Collection
    {
        return $this->parseContacts($this->data->get('BccFull'));
    }

    /**
     * Retrieve the collection of cc recipients.
     *
     * @return \Illuminate\Support\Collection<int, \Mvdnbrk\Postmark\Contact>
     */
    public function getCcAttribute(): Collection
    {
        return $this->parseContacts($this->data->get('CcFull'));
    }

    /**
     * Retrieve the from contact.
     */
    public function getFromAttribute(): Contact
    {
        return $this->parseContacts([
            $this->data->get('FromFull'),
        ])->first();
    }

    /**
     * Retrieve the collection of recipient contacts.
     *
     * @return \Illuminate\Support\Collection<int, \Mvdnbrk\Postmark\Contact>
     */
    public function getToAttribute(): Collection
    {
        return $this->parseContacts($this->data->get('ToFull'));
    }

    /**
     * Retrieve the timezone from the message.
     */
    public function getTimezoneAttribute(): string
    {
        return $this->datetime->getTimezone()->getName();
    }

    /**
     * Retrieve the date from the message.
     */
    public function getDateAttribute(): PostmarkDate
    {
        return $this->datetime;
    }

    /**
     * Determines if the message is to be considered as spam.
     */
    public function getIsSpamAttribute(): bool
    {
        return ucfirst($this->spamStatus) === 'Yes';
    }

    /**
     * Retrieve the collection of headers.
     *
     * @return \Illuminate\Support\Collection<string, string>
     */
    public function getHeadersAttribute(): Collection
    {
        return collect($this->data->get('Headers', []))
            ->mapWithKeys(fn (array $header): array => [$header['Name'] => $header['Value']]);
    }

    /**
     * Retrieve the MessageID assigned by Postmark.
     */
    public function getMessageIdAttribute(): ?string
    {
        return $this->data->get('MessageID');
    }

    /**
     * Retrieve the Message-ID value from the headers.
     */
    public function getMessageIdFromHeadersAttribute(): ?string
    {
        return $this->headers
            ->mapWithKeys(fn ($item, $key): array => [strtolower($key) => $item])
            ->get('message-id');
    }

    /**
     * Retrieve the original date value from the message.
     */
    public function getOriginalDateAttribute(): ?string
    {
        return $this->data->get('Date');
    }

    /**
     * Retrieve the spam score value from the message.
     */
    public function getSpamScoreAttribute(): float
    {
        return (float) $this->headers->get('X-Spam-Score', '0.0');
    }

    /**
     * Retrieve the spam status value from the message.
     */
    public function getSpamStatusAttribute(): string
    {
        return $this->headers->get('X-Spam-Status', 'No');
    }

    /**
     * Parse contacts and return a collection of contacts.
     *
     * @return \Illuminate\Support\Collection<int, \Mvdnbrk\Postmark\Contact>
     */
    protected function parseContacts(?array $contacts = []): Collection
    {
        return collect($contacts)
            ->map(function ($contact) {
                $contact = collect($contact);

                return new Contact($contact->get('Name'), $contact->get('Email'), $contact->get('MailboxHash'));
            });
    }

    /**
     * Dynamically retrieve attributes on the data model.
     *
     * @throws \InvalidArgumentException
     */
    public function __get(string $key): mixed
    {
        $key = ucfirst($key);

        if (method_exists($this, 'get'.$key.'Attribute')) {
            return $this->{'get'.$key.'Attribute'}();
        }

        if ($this->data->has($key)) {
            return $this->data->get($key);
        }

        throw new InvalidArgumentException(sprintf("Unknown getter '%s'", $key));
    }
}
