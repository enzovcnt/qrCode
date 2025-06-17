<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdageController extends AbstractController
{
    #[Route('/adage', name: 'app_adage')]
    public function index(): Response
    {
        return $this->render('adage/index.html.twig', [
            'controller_name' => 'AdageController',
        ]);
    }
}
