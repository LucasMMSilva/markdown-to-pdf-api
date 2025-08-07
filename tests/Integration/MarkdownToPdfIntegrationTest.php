<?php

namespace Tests\Integration;

use App\Services\MarkdownToHtmlService;
use App\Services\HtmlToPdfService;
use PHPUnit\Framework\TestCase;

class MarkdownToPdfIntegrationTest extends TestCase
{
    public function test_markdown_is_converted_to_pdf_through_both_services()
    {
        $markdown = "# Integração\nConteúdo com **ênfase** e _itálico_.";

        $markdownService = new MarkdownToHtmlService();
        $html = $markdownService->convert($markdown);

        $this->assertStringContainsString('<h1>Integração</h1>', $html);
        $this->assertStringContainsString('<strong>ênfase</strong>', $html);

        $pdfService = new HtmlToPdfService();
        $pdf = $pdfService->convert($html);

        $this->assertNotEmpty($pdf);
        $this->assertStringStartsWith('%PDF', $pdf);
    }
}
