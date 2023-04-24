<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Contraseña actual',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor ingrese su contraseña actual']),
                ],
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'Nueva contraseña',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor ingrese su nueva contraseña']),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmar contraseña',
                'constraints' => [
                    new NotBlank(['message' => 'Por favor confirme su nueva contraseña']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
