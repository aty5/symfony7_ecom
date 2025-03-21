<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            // ...
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        //enlever "soucis img input demandé" lors d'un EDIT produit
        $required = true;
        if ($pageName === 'edit') {
            $required = false;
        }

        return [
            TextField::new('name')->setLabel("Nom")->setHelp("Nom du produit"),

            BooleanField::new('isHomepage')->setLabel("Produit à la une")->setHelp("Mettre le produit à la une en homepage ?"),
            //TextareaField::new('description'),
            TextEditorField::new('description')->setLabel("Description")->setHelp("Description du produit"), // editor wysiwyg


            SlugField::new('slug')->setTargetFieldName('name')->setLabel("URL")->setHelp("URL généré automatiquement SlugField"),

            ImageField::new('illustration')
                ->setLabel("Image")
                ->setHelp("Image du produit en 600x600 px")
                ->setUploadDir('/public/uploads')
                ->setBasePath('/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setRequired($required),

            NumberField::new('price')->setLabel("Prix HT")->setHelp("Prix HT du produit"),

            // TODO: surement un autre moyen de stocker ca plus proprement
            ChoiceField::new('tva')->setLabel("TVA")->setChoices([
                '5,5%' => '5.5',
                '10%' => '10',
                '20%' => '20',
            ]),

            AssociationField::new('category', 'Catégories associé')
            ];
    }

}
