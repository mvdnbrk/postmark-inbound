<?php

namespace Mvdnbrk\Postmark;

use DateTime;
use DateTimeZone;
use Mvdnbrk\Postmark\Contact;
use Mvdnbrk\Postmark\Support\Collection;

class InboundMessage
{
    /**
     * Collection of the json data.
     *
     * @var \Mvdnbrk\Postmark\Support\Collection
     */
    protected $data;

    /*
     * DateTime when the message was reveived,
     *
     * @var DateTime
     */
    protected $datetime;

    /**
     * Create a new InboundMessage instance.
     *
     * @param mixed $json
     */
    public function __construct($json = null)
    {
        $this->data = new Collection(json_decode($json, true));

        if ((json_last_error() !== JSON_ERROR_NONE)) {
            throw new \InvalidArgumentException('You must provide a valid JSON source.');
        }

        $this->datetime = DateTime::createFromFormat(
            DateTime::RFC2822,
            $this->date
        );

        if ($this->datetime === false) {
            throw new \InvalidArgumentException('Date: ' . $this->date . ' is not a valid value.');
        }
    }

    /**
     * Retrieve the collecion of attachments.
     *
     * @return \Mvdnbrk\Postmark\Support\Collection
     */
    public function getAttachmentsAttribute()
    {
        $attachments = new Collection($this->data->get('Attachments', []));

        return $attachments->map(function ($data) {
            $attachment = new Collection($data);

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
     * Retrieve the collecion of bcc recipients.
     *
     * @return \Mvdnbrk\Postmark\Support\Collection
     */
    public function getBccAttribute()
    {
        return $this->parseContacts($this->data->get('BccFull'));
    }

    /**
     * Retrieve the collecion of cc recipients.
     *
     * @return \Mvdnbrk\Postmark\Support\Collection
     */
    public function getCcAttribute()
    {
        return $this->parseContacts($this->data->get('CcFull'));
    }

    /**
     * Retrieve the from contact.
     *
     * @return \Mvdnbrk\Postmark\Contact
     */
    public function getFromAttribute()
    {
        return $this->parseContacts([
            $this->data->get('FromFull')
        ])->first();
    }

    /**
     * Retrieve the collecion of recipient contacts.
     *
     * @return \Mvdnbrk\Postmark\Support\Collection
     */
    public function getToAttribute()
    {
        return $this->parseContacts($this->data->get('ToFull'));
    }

    /**
     * Retrieve the timezone from the message.
     *
     * @return string
     */
    public function getTimezoneAttribute()
    {
        return $this->datetime->getTimezone()->getName();
    }

    /**
     * Retrieve the UTC date from the message.
     *
     * @return string
     */
    public function getUtcDateAttribute()
    {
        return (clone $this->datetime)->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        ;
    }

    /**
     * Retrieve the collecion of headers.
     *
     * @return \Mvdnbrk\Postmark\Support\Collection
     */
    public function getHeadersAttribute()
    {
        return Collection::make($this->data->get('Headers'))
            ->mapWithKeys(function ($header) {
                return [$header['Name'] => $header['Value']];
            });
    }

    /**
     * Retrieve the MessageID.
     *
     * @return string
     */
    public function getMessageIdAttribute()
    {
        return $this->data->get('MessageID');
    }

    /**
     * Retrieve the Message-ID value from the headers.
     *
     * @return string
     */
    public function getMessageIdFromHeadersAttribute()
    {
        return $this->headers->changeKeyCase()->get('message-id');
    }

    /**
     * Parse contacts and return a collection of contacts.
     *
     * @param  array  $contacts
     * @return \Mvdnbrk\Postmark\Support\Collection
     */
    protected function parseContacts($contacts = [])
    {
        return Collection::make($contacts)
            ->map(function ($contact) {
                $contact = Collection::make($contact);
                return new Contact($contact->get('Name'), $contact->get('Email'), $contact->get('MailboxHash'));
            });
    }

    /**
     * Dynamically retrieve attributes on the data model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        $key = ucfirst($key);

        if (method_exists($this, 'get'.$key.'Attribute')) {
            return $this->{'get'.$key.'Attribute'}();
        }

        if ($this->data->get($key)) {
            return $this->data->get($key);
        }
    }
}
