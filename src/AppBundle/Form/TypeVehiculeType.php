<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeVehiculeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('genre')
                ->add('carrosserie')
                ->add('usage')
                ->add('montantRevisite')
                ->add('montantVisite')
                ->add('timbre')
                ->add('delai', 'integer', array('label'=>'Délai pour revisite (en jours)', 'required'=>true))
                ->add('validite', 'integer', array('label'=> 'Durée de validitée des visites (en mois)', 'required'=>true));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TypeVehicule'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_typevehicule';
    }


}
