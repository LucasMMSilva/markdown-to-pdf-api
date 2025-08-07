<?php

namespace App\Services;

use League\CommonMark\CommonMarkConverter;

class MarkdownToHtmlService
{
    protected CommonMarkConverter $converter;

    public function __construct()
    {
        $this->converter = new CommonMarkConverter();
    }

    /**
     * Converte Markdown para HTML.
     *
     * @param string $markdown
     * @return string
     */
    public function convert(string $markdown): string
    {
        return $this->converter->convertToHtml($markdown);
    }
}
