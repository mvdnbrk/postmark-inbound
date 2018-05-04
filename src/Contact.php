<?php

namespace Mvdnbrk\Postmark;

class Contact
{
    /**
     * E-mail address of the contact.
     *
     * @var string
     */
    public $email;

    /**
     * Name of the contact,
     *
     * @var string
     */
    public $name;

    /**
     * Mailbox hash.
     *
     * @var string
     */
    public $mailboxHash;

    /**
     * Full address of the contact (i.e. John Doe <john@example.com>).
     *
     * @var string
     */
    public $full;

    /**
     * Create a new contact.
     *
     * @param string $name
     * @param string $email
     * @param string|null $mailboxHash
     */
    public function __construct($name, $email, $mailboxHash = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->mailboxHash = $mailboxHash;
        $this->full = $name . ' <' . $email . '>';
    }
}
