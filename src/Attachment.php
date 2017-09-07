<?php

namespace Heyhoo\Postmark;

class Attachment
{
    /**
     * Name of the attachment.
     *
     * @var string
     */
    public $name;

    /**
     * The content type of the attachment.
     *
     * @var string
     */
    public $contentType;

    /**
     * The content length of the attachment.
     *
     * @var int
     */
    public $contentLength;

    /**
     * Base64 encoded content of the attachment.
     *
     * @var mixed
     */
    protected $content;

    /**
     * Create a new attachment.
     *
     * @param string $name
     * @param string $contentType
     * @param int $contentLength
     * @param mixed $content
     */
    public function __construct($name, $contentType, $contentLength, $content)
    {
        $this->name = $name;
        $this->contentType = $contentType;
        $this->contentLength = $contentLength;
        $this->content = $content;
    }

    /**
     * base64 decoded content of the attachment.
     *
     * @return mixed
     */
    public function content()
    {
        return base64_decode(chunk_split($this->content));
    }
}
