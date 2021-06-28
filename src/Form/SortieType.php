<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{

    private $em;


    /**
     * SortieType constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom :'
            ])
            ->add('dateHeureDebut', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Date et heure de la sortie :'
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Date limite d\'inscription :'
            ])
            ->add('nbInscriptionsMax', NumberType::class, [
                'label' => 'Nombre de places :'
            ])
            ->add('duree', NumberType::class, [
                'label' => 'Durée :'
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos :'
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'label' => 'Campus :',
                'attr' => [
                    'disabled' => 'disabled',
                ]
            ])
           ->add('enregistrer', SubmitType::class, [
               'attr' => ['class' => 'btn btn-primary']
           ])
           ->add('publier', SubmitType::class, [
               'attr' => ['class' => 'btn btn-primary']
           ])
       ;

       $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
       $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElements(FormInterface $form, Ville $ville = null) {
        $form->add('ville', EntityType::class, array(
            'mapped' => false,
            'required' => true,
            'data' => $ville,
            'class' => Ville::class,
            'choice_label' => 'nom',
            'placeholder' => 'Choisissez une ville'
        ));

        $lieux = $ville ? $ville->getLieux() : [];

        $form->add('lieu', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Sélectionnez d\'abord une ville',
            'class' => Lieu::class,
            'choice_label' => 'nom',
            'choices' => $lieux
        ));
    }
    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        /**
         * @var Ville $ville
         */
        $ville = $this->em->getRepository(Ville::class)->find($data['ville']);
        $this->addElements($form, $ville);
    }

    function onPreSetData(FormEvent $event) {
        $sortie = $event->getData();
        $form = $event->getForm();
        $ville = $sortie->getLieu() ? $sortie->getLieu()->getVille() : null;
        $this->addElements($form, $ville);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_sortie';
    }
}
