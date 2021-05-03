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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UtilisateurType extends AbstractType
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
                'required' => true
            ])
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Le mot de passe et sa confirmation doivent être identiques',
                    'label' => 'Votre mot de passe',
                    'required' => true,
                    'first_options' => [
                        'label' => 'Mot de passe',
                        'attr' => [
                            'placeholder' => 'Entrez le mot de passe'
                        ]
                    ],
                    'second_options' => [
                        'label' => 'Confirmez le mot de passe',
                        'attr' => [
                            'placeholder' => 'Confirmez le mot de passe'
                        ]
                    ],
                ]
            )
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'attr' => [
                    'class' => 'chzn-select',
                    'placeholder' => 'Choisir la / les formations'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
