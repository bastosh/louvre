<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class VisitorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom du visiteur',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom du visiteur'
            ])
            ->add('country', CountryType::class, [
                'label'  => 'Pays',
                'placeholder' => 'Choisissez un pays',
            ])
            ->add('birthday', BirthdayType::class, [
                'label' => 'Date de naissance',
                'format' => 'dd-MM-yyyy'
                ])
            ->add('reduced', CheckboxType::class, [
                'label' => 'Je bénéficie du tarif réduit',
                'required' => false
            ])
        ;
    }
}
