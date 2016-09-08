<?php
/**
 * Created by PhpStorm.
 * User: Pereda
 * Date: 08/09/2016
 * Time: 20:23
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndexType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('day', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Journée' => 'full',
                    'Demi-journée' => 'half'
                ],
                'label'  => 'Type de billet'
            ])
            ->add('quantity', ChoiceType::class, [
                'label'  => 'Nombre de visiteurs',
                'choices'  => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10'
                ],
            ])
            ->add('email', EmailType::class)
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'index_form',
        ));
    }
}
