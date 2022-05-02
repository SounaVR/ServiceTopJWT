<?php

namespace App\Controller;

use App\Entity\Services;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Services::class);

        $services = $repository->findAll();

        return $this->render('accueil/index.html.twig', [
            'services' => $services
        ]);
    }

    #[Route('/services/{id}', name: 'contact_service')]
    public function contactService(): Response
    {
        $user = $this->getUser();

        $serviceForm = $this->createForm();

        return $this->renderForm('accueil/contact.html.twig', [
            'servicesForm' => $builder
        ]);
    }
}
