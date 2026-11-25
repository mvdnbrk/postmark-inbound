<?php

namespace Mvdnbrk\Postmark;

class Contact
{
    /**
     * E-mail address of the contact.
     */
    public ?string $email;

    /**
     * Name of the contact.
     */
    public string $name;

    /**
     * Mailbox hash.
     */
    public ?string $mailboxHash;

    /**
     * Full address of the contact (i.e. John Doe <john@example.com>).
     */
    public string $full;

    /**
     * Create a new contact.
     */
    public function __construct(?string $name, ?string $email, ?string $mailboxHash = null)
    {
        $this->name = trim((string) $name, ' "\'');

        $this->email = collect(explode(' ', (string) $email))
            ->filter(fn (string $value): bool => str_contains($value, '@'))
            ->first();

        $this->mailboxHash = $mailboxHash;

        $this->full = $this->name.' <'.$email.'>';
    }
}
