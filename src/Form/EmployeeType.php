<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use App\Form\StringToFileTransformer;


class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'attr' => [
                    'placeholder' => 'Introduce un nombre', 
                    'class' => 'form-control'
                ] 
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Apellido',
                'attr' => [
                    'placeholder' => 'Introduce un apellido',
                    'class' => 'form-control'
                ] 
            ])
            ->add('email' )
            ->add('identifying', TextType::class, [
                'label' => 'Nº de Identificador',
                'attr' => [
                    'placeholder' => 'Introduce número de identificador',
                    'class' => 'form-control'
                ]])
            ->add('startDate', DateType::class,[
                'label' => 'Fecha de Inicio'])
            ->add('finishDate' , DateType::class,[
                'label' => 'Fecha de Fin'])
            ->add('manager', null, [
                'label' => 'Responsable',
                'attr' => [
                    'class' => 'form-select'
                ]])
            ->add('rol', null, [
                'label' => 'Rol',
                'attr' => [
                    'class' => 'form-select'
                ]])
            ->add('team', null, [
                'label' => 'Equipo',
                'attr' => [
                    'class' => 'form-select'
                ]])
            ->add('area' , null, [
                'label' => 'Área',
                'attr' => [
                    'class' => 'form-select'
                ]])
            ->add('position', null, [
                'label' => 'Posición',
                'attr' => [
                    'class' => 'form-select'
                ]])
            ->add('period', null, [
                'label' => 'Periodo'
            ])
            ->add('typeOfContract', null, [
                'label' => 'Contrato',
                'attr' => [
                    'class' => 'form-select'
                ]])
            ->add('status', null, [
                'label' => 'Status',
                'attr' => [
                    'class' => 'form-select'
                ]])
            ->add('firstPeriod', DateType::class,[
                'label' => 'Primer Seguimiento'])
            ->add('secondPeriod', DateType::class,[
                'label' => 'Segundo Seguimiento'])
            ->add('thirdPeriod', DateType::class,[
                'label' => 'Tercer Seguimiento'])
            ->add('fourthPeriod', DateType::class,[
                'label' => 'Cuarto Seguimiento'])
            ->add('fifthPeriod', DateType::class,[
                'label' => 'Quinto Seguimiento'])
           
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
