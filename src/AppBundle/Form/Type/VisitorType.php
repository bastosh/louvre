<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('country', ChoiceType::class, [
                'label'  => 'Pays',
                'choices'  => [
                    'France' => 'France',
                    'Allemagne' => 'Allemagne',
                    'Belgique' => 'Belgique',
                    'Canada' => 'Canada',
                    'Chine' => 'Chine',
                    'Espagne' => 'Espagne',
                    'États-Unis' => 'États-Unis',
                    'Italie' => 'Italie',
                    'Japon' => 'Japon',
                    'Pays-Bas' => 'Pays-Bas',
                    'Portugal' => 'Portugal',
                    'Royaume-Uni' => 'Royaume-Uni',
                    'Russie' => 'Russie',
                    'Autre' => 'Autre',
                ]])
            ->add('birthday', TextType::class)
            ->add('Valider', SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Visitor'
        ));
    }
}
