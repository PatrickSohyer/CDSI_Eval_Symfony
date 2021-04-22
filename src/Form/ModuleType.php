<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Module;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du module',
                'attr' => [
                    'placeholder' => 'Nom du module'
                ]
            ])
            ->add('nbHeures', NumberType::class, [
                'label' => 'Nombre d\'heures',
                'attr' => [
                    'placeholder' => 'Nombre d\'heures'
                ]
            ])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'nom',                
                'multiple' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
        ]);
    }
}
