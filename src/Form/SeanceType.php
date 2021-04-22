<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Seance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateSeance', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('duree', NumberType::class, [
                'label' => 'Durée de la séance',
                'attr' => [
                    'placeholder' => 'Durée de la séance'
                ]
            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre de la séance',
                'attr' => [
                    'placeholder' => 'Titre de la séance '
                ]
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu de la séance', 
                'attr' => [
                    'placeholder' => 'Contenu de la séance'
                ]
            ])
            ->add('fichier', FileType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Ajouter un support de cours',
                "constraints" => [
                    new Image(["maxSize" => "2048k"])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
