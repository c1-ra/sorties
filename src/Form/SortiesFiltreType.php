<?php

namespace App\Form;

use App\ClassPHP\SortiesFiltreForm;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortiesFiltreType extends AbstractType
{
    CONST FILTRES = [
        'Sorties dont je suis l\'organisateur/trice' => "orga",
        'Sorties auxquelles je suis inscrit(e)' => "inscrit",
        'Sorties auxquelles je ne suis pas inscrit/e' => "nonInscrit",
        'Sorties passées' => "passees"
        ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'label' => 'Campus ',
                'placeholder' => 'Tous les campus',
                'required' => false
            ])
            ->add('rechercheNom',  TextType::class, [
                'label' => 'Le nom de la sortie contient : ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ],
            ])
            ->add('dateMin', DateType::class, [
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Entre'
            ])
            ->add('dateMax', DateType::class, [
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
                'label' => ' et '
            ])
            ->add('orga', CheckboxType::class, [
                'required' => false,
                'label' => "Sorties dont je suis l'organisateur/trice",
            ])
            ->add('inscrit', CheckboxType::class, [
                'required' => false,
                'label' => "Sorties auxquelles je suis inscrit(e)"
            ])
            ->add('nonInscrit', CheckboxType::class, [
                'required' => false,
                'label' => "Sorties auxquelles je ne suis pas inscrit/e"
            ])
            ->add('passees', CheckboxType::class, [
                'required' => false,
                'label' => "Sorties passées"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SortiesFiltreForm::class,
        ]);
    }
}
