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
                ->add('typeVehicule')
                //->add('carrosserieAjax', 'text', array('label'  => 'Carrosserie', 'required'  => true, "mapped" => false))
                //->add('carrosserieId', 'hidden', array('required'  => true, "mapped" => false))
                //->add('usageAjax', 'text', array('label'  => 'Usage', 'required'  => true, "mapped" => false))
                //->add('usageId', 'hidden', array('required'  => true, "mapped" => false))
                //->add('genre', 'text', array('label'  => 'Genre', 'required'  => false, "mapped" => false))
                ->add('ptac', 'number', array('label' => 'Ptac (KG)', 'required'  => true))
                ->add('place')
                ->add('puissance')
                ->add('dateMiseCirculation')
                ->add('dateProchaineVisite')
                ->add('carteGrise')
                ->add('dateCarteGrise') 
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
                ->add('alimentation', 'choice',['choices' => [0 => 'Atmo', 1 => 'Turbo' ]])   
                ->add('potCatalytique', 'choice',['choices' => [0 => 'Non', 1 => 'Oui' ]])      
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
