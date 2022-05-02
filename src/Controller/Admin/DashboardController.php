<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Services;
use App\Controller\Admin\UserCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user->getIs_verified()) {
            $this->addFlash('warning', 'Vous devez activer votre compte');
            return $this->redirectToRoute('app_accueil');
        }
        
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ServiceTop')
            //->setTitle('<img src="..."> ACME <span class="text-small">Corp.</span>'
            ->setFaviconPath('favicon.svg')
            ->setTranslationDomain('my-custom-domain')
            ->setTextDirection('ltr')
            ->renderContentMaximized()
            ->disableUrlSignatures()
            ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToRoute('Aller sur le site', 'fa fa-undo', 'app_accueil'),

            MenuItem::subMenu('Utilisateurs', 'fa fa-user')->setSubItems([
                MenuItem::linkToCrud('Liste des utilisateurs', 'fa fa-user', User::class),
                MenuItem::linkToCrud('Ajouter un utilisateur', 'fa fa-plus', User::class)->setAction(Crud::PAGE_NEW),
            ]),

            MenuItem::subMenu('Services', 'fa fa-newspaper')->setSubItems([
                MenuItem::linkToCrud('Liste des services', 'fa fa-newspaper', Services::class),
                MenuItem::linkToCrud('Ajouter un service', 'fa fa-plus', Services::class)->setAction(Crud::PAGE_NEW),
            ]),
        ];
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getName())
            ->displayUserAvatar(false)

            ->addMenuItems([
                MenuItem::linkToRoute('Reset Password', 'fa fa-key', 'reset_password', ['id' => $user->getId()]),
            ]);
    }
}
