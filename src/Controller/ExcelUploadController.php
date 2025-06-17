<?php

// src/Controller/ExcelImportController.php
namespace App\Controller;

use App\Entity\Adage;
use App\Form\ExcelUploadForm;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExcelUploadController extends AbstractController
{
    #[Route('/import/excel', name: 'import_excel')]
    public function import(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ExcelUploadForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('excel')->getData();
            $inputFileName = $file->getPathname();
            $inputFileType = IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            foreach ($rows as $index => $row) {
                if ($index === 0) continue;

                $author = $row[0] ?? null;
                $content = $row[1] ?? null;

                if ($author && $content) {
                    $adage = new Adage();
                    $adage->setAuthor($author);
                    $adage->setContent($content);
                    $em->persist($adage);
                }
            }

            $em->flush();
            $this->addFlash('success', 'Fichier importé avec succès !');
            return $this->redirectToRoute('app_adage');
        }

        return $this->render('excel_upload/index.html.twig', [
            'form' => $form,
        ]);
    }
}

