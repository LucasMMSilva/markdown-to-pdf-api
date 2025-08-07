<?php

namespace Tests\Unit\Services;

use App\Services\MarkdownToHtmlService;
use PHPUnit\Framework\TestCase;

class MarkdownToHtmlServiceTest extends TestCase
{
    public function test_it_converts_markdown_to_html()
    {
        $service = new MarkdownToHtmlService();

        $markdown = "# Título\n\nTexto em **negrito**.";
        $expectedHtml = "<h1>Título</h1>\n<p>Texto em <strong>negrito</strong>.</p>\n";

        $this->assertEquals($expectedHtml, $service->convert($markdown));
    }
}
