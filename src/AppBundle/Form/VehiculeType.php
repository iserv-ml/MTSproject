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
                ->add('proprietaireAjax', 'text', array('label'  => 'Propriétaire', 'required'  => true, "mapped" => false))
                ->add('proprietaireId', 'hidden', array('required'  => true, "mapped" => false))
                ->add('immatriculation')
                ->add('chassis')
                ->add('modeleAjax', 'text', array('label'  => 'Modèle', 'required'  => true, "mapped" => false))
                ->add('modeleId', 'hidden', array('required'  => true, "mapped" => false))
                ->add('typeChassis')
                ->add('carrosserieAjax', 'text', array('label'  => 'Carrosserie', 'required'  => true, "mapped" => false))
                ->add('carrosserieId', 'hidden', array('required'  => true, "mapped" => false))
                ->add('usageAjax', 'text', array('label'  => 'Usage', 'required'  => true, "mapped" => false))
                ->add('usageId', 'hidden', array('required'  => true, "mapped" => false))
                ->add('genre', 'text', array('label'  => 'Genre', 'required'  => false, "mapped" => false))
                ->add('carteGrise')
                ->add('dateCarteGrise', 'text')
                ->add('dateMiseCirculation', 'text')
                ->add('ptac')
                ->add('place')
                ->add('puissance')
                ->add('kilometrage')
                ->add('couleur')                           
                ->add('typeImmatriculation')
                ->add('dateValidite')
                ->add('energie')
                ->add('pv')
                ->add('cu')
                ->add('puissanceReelle')
                ->add('capacite')
                ->add('moteur')
                ->add('immatriculationPrecedent')
                ->add('dateImmatriculationPrecedent')
                ->add('typeCarteGrise')
                ->add('commentaire')
                ->add('file', 'file', array('label'=>'Image 1 (limite 10M)', 'required'=>false))
                ;
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
