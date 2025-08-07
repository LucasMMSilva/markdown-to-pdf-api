<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class HtmlToPdfService
{
    protected Dompdf $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Arial');

        $this->dompdf = new Dompdf($options);
    }

    /**
     * Converte HTML em PDF e retorna o conteúdo do arquivo PDF.
     *
     * @param string $html
     * @return string (PDF binary)
     */
    public function convert(string $html): string
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();

        return $this->dompdf->output();
    }
}
