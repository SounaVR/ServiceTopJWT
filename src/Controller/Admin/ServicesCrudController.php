<?php
namespace App\Controller\Admin;

use DateTime;
use App\Entity\Services;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ServicesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Services::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextareaField::new('description')->renderAsHtml(),
            Field::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex()->hideOnDetail(),
            ImageField::new('image')->setBasePath('/images/services')->hideWhenCreating()->hideWhenUpdating(),
            DateTimeField::new('createdAt')->hideOnForm()->setLabel('Créé le'),
            DateTimeField::new('updatedAt')->hideOnForm()->setLabel('Modifié le'),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $services = new Services();
        $services->setCreatedAt(new DateTime());
        $services->setUpdatedAt(new DateTime());

        return $services;
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        $entityInstance->setUpdatedAt(new DateTime());

        parent::updateEntity($em, $entityInstance);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
