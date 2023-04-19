<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use App\Form\StringToFileTransformer;


class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('lastname')
            ->add('email')
            ->add('identifying')
            ->add('startDate')
            ->add('finishDate')
            ->add('manager')
            ->add('rol')
            ->add('team')
            ->add('area')
            ->add('position')
            ->add('period')
            ->add('manager')
            ->add('firstPeriod')
            ->add('secondPeriod')
            ->add('thirdPeriod')
            ->add('fourthPeriod')
            ->add('fifthPeriod')
            ->add('typeOfContract')
            ->add('status')
            ->add('photo', FileType::class, [
                'label' => 'Seleccione una imagen',
                'data_class' => null,           
                // unmapped means that this field is not associated to any entity property
                'mapped' => true,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Por favor, sube un archivo de imagen válido (JPEG, PNG)',
                    ])
                ],
            ]) ;

    //         $builder->get('photo')->addViewTransformer(new StringToFileTransformer());
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,

        ]);
    }
}
