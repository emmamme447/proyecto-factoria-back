<?php

namespace App\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Calendar1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titulo',
                'attr' => [
                'class' => 'form-control'
            ]])
            ->add('startDate', DateType::class,[
                'label' => 'Fecha de inicio'])
            ->add('finishDate', DateType::class,[
                'label' => 'Fecha fin'])
            ->add('recipient',TextType::class, [
                'label' => 'Responsable',
                'attr' => [
                'class' => 'form-control'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
