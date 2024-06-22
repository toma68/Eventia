<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Regex;


class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Ancien mot de passe',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre ancien mot de passe']),
                    new UserPassword(['message' => 'Mot de passe incorrect']),
                ],
                'mapped' => false,
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un nouveau mot de passe']),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le nouveau mot de passe doit contenir au moins {{ limit }} caractères',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-zA-Z])(?=.*\d).+$/',
                        'message' => 'Le nouveau mot de passe doit contenir à la fois des lettres et des chiffres',
                    ]),
                ],
                'mapped' => false,
            ])
            ->add('newPasswordVerif', PasswordType::class, [
                'label' => 'Vérifiez le nouveau mot de passe',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez vérifier votre nouveau mot de passe']),
                ],
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
