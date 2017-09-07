<?php

namespace Heyhoo\Postmark;

class Contact
{
    public $email;
    public $name;
    public $mailboxHash;
    public $full;

    public function __construct($name, $email, $mailboxHash)
    {
        $this->name = $name;
        $this->email = $email;
        $this->mailboxHash = $mailboxHash;
        $this->full = $name . ' <' . $email . '>';
    }
}
