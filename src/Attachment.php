<?php

namespace Mvdnbrk\Postmark;

class Attachment
{
    /**
     * Name of the attachment.
     */
    public ?string $name;

    /**
     * The content id of the attachment.
     */
    public ?string $contentId;

    /**
     * The content type of the attachment.
     */
    public ?string $contentType;

    /**
     * The content length of the attachment.
     */
    public ?int $contentLength;

    /**
     * Base64 encoded content of the attachment.
     */
    protected ?string $content;

    /**
     * Create a new attachment.
     */
    public function __construct(?string $name, ?string $contentId, ?string $contentType, ?int $contentLength, ?string $content)
    {
        $this->name = $name;
        $this->contentId = $contentId;
        $this->contentType = $contentType;
        $this->contentLength = $contentLength;
        $this->content = $content;
    }

    /**
     * Base64 decoded content of the attachment.
     */
    public function content(): string
    {
        return (string) base64_decode(chunk_split((string) $this->content));
    }
}
