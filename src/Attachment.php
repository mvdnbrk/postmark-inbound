<?php

namespace Heyhoo\Postmark;

class Attachment
{
    public $name;
    public $contentType;
    public $contentLength;
    protected $content;

    public function __construct($name, $contentType, $contentLength, $content)
    {
        $this->name = $name;
        $this->contentType = $contentType;
        $this->contentLength = $contentLength;
        $this->content = $content;
    }

    public function content()
    {
        return base64_decode(chunk_split($this->content));
    }
}
