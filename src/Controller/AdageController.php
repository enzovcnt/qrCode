<?php

namespace App\Controller;

use App\Entity\Adage;
use App\Form\AdageForm;
use App\Repository\AdageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class AdageController extends AbstractController
{
    #[Route('/', name: 'app_adage')]
    public function index(AdageRepository $adageRepository): Response
    {
        return $this->render('adage/index.html.twig', [
            'adages' => $adageRepository->findAll(),
        ]);
    }

    #[Route('/adage/{id}', name: 'app_adage_show', priority: -1)]
    public function show(Adage $adage): Response
    {
        return $this->render('adage/show.html.twig', [
            'adage' => $adage,
        ]);
    }

    #[Route('/adage/new', name: 'app_adage_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
//        $user = $this->getUser();
//        if (!$user || !in_array("ROLE_ADMIN", $user->getRoles())) {
//            return $this->redirectToRoute('app_login');
//        }
        $adage = new Adage();
        $form = $this->createForm(AdageForm::class, $adage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($adage);
            $manager->flush();
            return $this->redirectToRoute('app_adage_show', ['id' => $adage->getId()]);
        }

        return $this->render('adage/create.html.twig', [
            'form' =>  $form->createView(),
        ]);

    }

    #[Route('/admin/adage/{id}/edit', name: 'app_adage_edit')]
    public function edit(Request $request, Adage $adage, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!$user || !in_array("ROLE_ADMIN", $user->getRoles())) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(AdageForm::class, $adage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('app_adage_show', ['id' => $adage->getId()]);
        }
        return $this->render('adage/edit.html.twig', [
            'adage' => $adage,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/adage/{id}/delete', name: 'app_adage_delete')]
    public function delete(Adage $adage, EntityManagerInterface $manager): Response
    {
        $manager->remove($adage);
        $manager->flush();
        return $this->redirectToRoute('app_adage');
    }
}
