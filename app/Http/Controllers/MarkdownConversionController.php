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

    /**
     * @OA\Post(
     *     path="/api/convert",
     *     summary="Converte um arquivo Markdown em PDF",
     *     tags={"Conversão"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Arquivo .md a ser convertido"
     *                 ),
     *                 @OA\Property(
     *                     property="filename",
     *                     type="string",
     *                     description="Nome opcional do PDF gerado (sem .pdf)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="PDF gerado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="PDF generated successfully."),
     *             @OA\Property(property="file_url", type="string", example="http://localhost:8000/storage/converted_pdfs/meu_arquivo.pdf")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao gerar o PDF"
     *     )
     * )
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:md,txt|max:5120',
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