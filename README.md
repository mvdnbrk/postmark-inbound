# postmark-inbound

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Simple API to process Postmark Inbound Webhooks.

## Install

Via Composer

``` bash
$ composer require heyhoo/postmark-inbound
```

## Usage

``` php
$inbound = new \Heyhoo\Postmark\InboundMessage(file_get_contents('php://input'));

$inbound->from->name;               // John Doe
$inbound->from->email;              // john@example.com
$inbound->from->full;               // John Doe <john@example.com>

$inbound->tag;
$inbound->replyTo;
$inbound->textBody;
$inbound->htmlBody;
$inbound->messageId;                // MessageID assigned by Postmark.
$inbound->messageIdFromHeaders;     // Message-ID value from headers.
$inbound->strippedTextReply;
$inbound->originalRecipient;

$inbound->date;                     // Wed, 6 Sep 2017 19:11:00 +0200
$inbound->subject;                  // Subject of the message

$inbound->to->count()               // Recipient count
$inbound->cc->count()
$inbound->bcc->count()

$inbound->attachments->count()      // Attachment count

$inbound->headers->count()          // Header count
```

### Recipients

```
$inbound->to->each(function($contact) {
    $contact->name;
    $contact->email;
    $contact->full;
    $contact->mailboxHash;
});

$inbound->cc->each(function($contact) {
    $contact->name;
    ...
});

$inbound->bcc->each(function($contact) {
    $contact->name;
    ...
});
```

Get the first recipient:
```
$inbound->to->first();
```

### Attachments

```
$inbound->attachments->each(function($attachment) {
    $attachment->name;
    $attachment->contentType;
    $attachment->contentLength;
    $attachment->content();         // Base64 decoded data
});
```

Get the first attachment:
```
$inbound->attachments->first();
```

Get the last attachment:
```
$inbound->attachments->last();
```

### Headers

```
$this->message->headers->each(function($value, $key) {
    ...
});

$inbound->headers->get('Message-ID');
$inbound->headers->get('MIME-Version');
$inbound->headers->get('Received-SPF');
$inbound->headers->get('X-Spam-Score');
$inbound->headers->get('X-Spam-Status');
...
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Mark van den Broek][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/heyhoo/postmark-inbound.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/heyhoo/postmark-inbound/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/heyhoo/postmark-inbound.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/heyhoo/postmark-inbound.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/heyhoo/postmark-inbound.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/heyhoo/postmark-inbound
[link-travis]: https://travis-ci.org/heyhoo/postmark-inbound
[link-scrutinizer]: https://scrutinizer-ci.com/g/heyhoo/postmark-inbound/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/heyhoo/postmark-inbound
[link-downloads]: https://packagist.org/packages/heyhoo/postmark-inbound
[link-author]: https://github.com/heyhoo
[link-contributors]: ../../contributors
