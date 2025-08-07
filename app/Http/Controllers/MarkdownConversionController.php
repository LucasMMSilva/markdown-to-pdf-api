<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MarkdownToHtmlService;
use App\Services\HtmlToPdfService;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MarkdownConversionController extends Controller
{
    protected MarkdownToHtmlService $markdownService;
    protected HtmlToPdfService $pdfService;

    public function __construct(
        MarkdownToHtmlService $markdownService,
        HtmlToPdfService $pdfService
    ) {
        $this->markdownService = $markdownService;
        $this->pdfService = $pdfService;
    }

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:md,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Arquivo inválido',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $markdown = file_get_contents($request->file('file')->getRealPath());
        $html = $this->markdownService->convert($markdown);
        $pdf = $this->pdfService->convert($html);

        
        $filename = $request->input('filename', 'documento') . '.pdf';

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }
}
