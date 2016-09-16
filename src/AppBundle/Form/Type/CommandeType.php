<?php

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitors', CollectionType::class, [
                'entry_type'   => VisitorType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'label'        => ' ',
                'by_reference' => false
            ])
            ->add('amount', HiddenType::class)
            ->add('Valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-danger btn-lg']
            ])
        ;
    }
}
