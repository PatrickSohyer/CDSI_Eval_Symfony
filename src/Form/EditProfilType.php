<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EditProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'utilisateur',
                'attr' => [
                    'placeholder' => 'Entrez le nom de l\'élève / formateur'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom de l\'utilisateur',
                'attr' => [
                    'placeholder' => 'Entrez le prénom de l\'élève / formateur'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email de l\'utilisateur',
                'attr' => [
                    'placeholder' => 'Email de l\'élève / formateur'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Role de l\'utilisateur',
                'choices' => [
                    'ROLE_ETUDIANT' => 'ROLE_ETUDIANT',
                    'ROLE_FORMATEUR' => 'ROLE_FORMATEUR',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'required' => true,
            ])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true
            ]);
    }
}
