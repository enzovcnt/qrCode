<?php

namespace App\Controller;

use App\Entity\Adage;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PdfController extends AbstractController
{
    private Pdf $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }

    #[Route('/pdf/{id}', name: 'generate_pdf')]
    public function generatePdf(Adage $adage): PdfResponse
    {

        $html = $this->renderView('pdf/index.html.twig', [
            'adage'=>$adage,

        ]);

        $pdfOutput = $this->pdf->getOutputFromHtml($html);

        return new PdfResponse($pdfOutput, "recap.pdf");
    }
}
