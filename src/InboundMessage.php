<?php

namespace Heyhoo\Postmark;

use Heyhoo\Postmark\Contact;
use Heyhoo\Postmark\Support\Collection;

class InboundMessage
{
    protected $data;

    /**
     * Create a new InboundMessage instance
     *
     * @param mixed $data JSON
     */
    public function __construct($json = null)
    {
        //$this->data = json_decode($json, true);
        $this->data = new Collection(json_decode($json, true));

        if ((json_last_error() !== JSON_ERROR_NONE)) {
            throw new \InvalidArgumentException('You must provide a valid JSON source.');
        }
    }

    public function getAttachmentsAttribute()
    {
        return new Collection($this->data->get('Attachments'));
    }

    public function getBccAttribute()
    {
        return $this->parseContacts($this->data->get('BccFull'));
    }

    public function getCcAttribute()
    {
        return $this->parseContacts($this->data->get('CcFull'));
    }

    public function getFromAttribute()
    {
        return $this->parseContacts([
            $this->data->get('FromFull')
        ])->first();
    }

    public function getToAttribute()
    {
        return $this->parseContacts($this->data->get('ToFull'));
    }

    public function getHeadersAttribute()
    {
        return Collection::make($this->data->get('Headers'))
            ->mapWithKeys(function ($header) {
                return [$header['Name'] => $header['Value']];
            });
    }

    public function getMessageIdAttribute()
    {
        return $this->data->get('MessageID');
    }

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
