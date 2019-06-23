<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisiteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('version')->add('observations')->add('statut')->add('numeroCertificat')->add('date')->add('dateValidite')->add('revisite')->add('creePar')->add('modifierPar')->add('deletedAt')->add('dateCreation')->add('dateModification')->add('visiteParent')->add('vehicule')->add('penalite')->add('chaine')->add('quittance');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Visite'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_visite';
    }


}
