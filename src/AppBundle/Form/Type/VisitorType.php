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
                    'Algérie' => 'Algérie',
                    'Allemagne' => 'Allemagne',
                    'Belgique' => 'Belgique',
                    'Canada' => 'Canada',
                    'Chine' => 'Chine',
                    'Danemark' => 'Danemark',
                    'Espagne' => 'Espagne',
                    'États-Unis' => 'États-Unis',
                    'Finlande' => 'Finlande',
                    'Inde' => 'Inde',
                    'Islande' => 'Islande',
                    'Italie' => 'Italie',
                    'Japon' => 'Japon',
                    'Luxemboug' => 'Luxembourg',
                    'Maroc' => 'Maroc',
                    'Norvège' => 'Norvège',
                    'Pays-Bas' => 'Pays-Bas',
                    'Pologne' => 'Pologne',
                    'Portugal' => 'Portugal',
                    'Royaume-Uni' => 'Royaume-Uni',
                    'Russie' => 'Russie',
                    'Suède' => 'Suède',
                    'Suisse' => 'Suisse',
                    'Tunisie' => 'Tunisie',
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
