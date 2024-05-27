<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('roles', 'choice', array(
    'choices'  => array(
        'ROLE_USER' => 'ROLE_USER',
        'ROLE_CAISSIER' => 'ROLE_CAISSIER',
        'ROLE_ENREGISTREMENT' => 'ROLE_ENREGISTREMENT',
        'ROLE_AIGUILLEUR' => 'ROLE_AIGUILLEUR',
        'ROLE_SUPERVISEUR' => 'ROLE_SUPERVISEUR',
        'ROLE_CAISSIER_PRINCIPAL' => 'ROLE_CAISSIER_PRINCIPAL',
        'ROLE_DELIVRANCE' => 'ROLE_DELIVRANCE',
        'ROLE_CONTROLLEUR' => 'ROLE_CONTROLLEUR',
        'ROLE_CHEF_CENTRE' => 'ROLE_CHEF_CENTRE',
        'ROLE_SECRETAIRE' => 'ROLE_SECRETAIRE',
    ),
    // *this line is important*
    'choices_as_values' => true,
    'multiple' => true,
));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Group'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_group';
    }


}
