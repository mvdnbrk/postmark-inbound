<?php

namespace Heyhoo\Postmark;

use Heyhoo\Postmark\Contact;
use Heyhoo\Postmark\Support\Collection;

class InboundMessage
{
    /**
     * Collection of the json data.
     *
     * @var \Heyhoo\Postmark\Support\Collection
     */
    protected $data;

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
    }

    /**
     * Retrieve the collecion of attachments.
     *
     * @return \Heyhoo\Postmark\Support\Collection
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
     * @return \Heyhoo\Postmark\Support\Collection
     */
    public function getBccAttribute()
    {
        return $this->parseContacts($this->data->get('BccFull'));
    }

    /**
     * Retrieve the collecion of cc recipients.
     *
     * @return \Heyhoo\Postmark\Support\Collection
     */
    public function getCcAttribute()
    {
        return $this->parseContacts($this->data->get('CcFull'));
    }

    /**
     * Retrieve the from contact.
     *
     * @return \Heyhoo\Postmark\Contact
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
     * @return \Heyhoo\Postmark\Support\Collection
     */
    public function getToAttribute()
    {
        return $this->parseContacts($this->data->get('ToFull'));
    }

    /**
     * Retrieve the collecion of headers.
     *
     * @return \Heyhoo\Postmark\Support\Collection
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
     * @return \Heyhoo\Postmark\Support\Collection
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
