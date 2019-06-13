<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('chassis')
                ->add('carteGrise')
                ->add('dateCarteGrise')
                ->add('dateMiseCirculation')
                ->add('immatriculation')
                ->add('typeImmatriculation')
                ->add('ptac')
                ->add('place')
                ->add('puissance')
                ->add('kilometrage')
                ->add('couleur')
                ->add('modele')
                ->add('proprietaire')
                ->add('typeVehicule');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Vehicule'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_vehicule';
    }


}
