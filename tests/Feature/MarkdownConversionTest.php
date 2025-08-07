<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MarkdownConversionTest extends TestCase
{
    public function test_it_converts_markdown_file_to_pdf()
    {
        Storage::fake('local');

        $markdownContent = "# Teste\nEste é um arquivo **Markdown** de teste.";

        $file = UploadedFile::fake()->createWithContent('teste.md', $markdownContent);

        $response = $this->postJson('/api/convert-markdown', [
            'file' => $file,
        ]);

        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('Content-Disposition', 'attachment; filename="documento.pdf"');

        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_it_rejects_invalid_file()
    {
        $file = UploadedFile::fake()->create('teste.exe', 10);

        $response = $this->postJson('/api/convert-markdown', [
            'file' => $file,
        ]);

        $response->assertStatus(422);
    }

    public function test_it_rejects_when_no_file_is_sent()
    {
        $response = $this->postJson('/api/convert-markdown');

        $response->assertStatus(422);
    }
}
