<?php

namespace Tests\Unit\Services;

use App\Services\HtmlToPdfService;
use PHPUnit\Framework\TestCase;

class HtmlToPdfServiceTest extends TestCase
{
    public function test_it_converts_html_to_pdf()
    {
        $service = new HtmlToPdfService();

        $html = "<h1>Título</h1><p>Conteúdo do PDF</p>";
        $pdfOutput = $service->convert($html);

        // Verifica se retornou conteúdo binário começando com %PDF
        $this->assertStringStartsWith('%PDF', $pdfOutput);
    }
}
