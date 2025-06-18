<?php

namespace App\Controller;

use App\Entity\Adage;
use Endroid\QrCode\Builder\BuilderInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PdfController extends AbstractController
{
    private Pdf $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }

    #[Route('/pdf/{id}', name: 'generate_pdf')]
    public function generatePdf(Adage $adage, BuilderInterface $defaultBuilder,): PdfResponse
    {

        $adageUrl = $this->generateUrl('app_adage_show', ['id' => $adage->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        $qrResult = $defaultBuilder->build(
            data: $adageUrl,
            size: 500,
            margin: 20
        );
        $qrCodeBase64 = $qrResult->getDataUri();

        $html = $this->renderView('pdf/index.html.twig', [
            'adage'=>$adage,
            'qrCode' => $qrCodeBase64,

        ]);

        $pdfOutput = $this->pdf->getOutputFromHtml($html);

        return new PdfResponse($pdfOutput, "qrCode.pdf");
    }
}
