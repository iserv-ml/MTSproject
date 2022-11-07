<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParametreCertificatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('largeurVolet1')
                ->add('hauteurVolet1')
                ->add('vcertificat1')
                ->add('hcertificat1')
                ->add('vnom1')
                ->add('hnom1')
                ->add('wnom1')
                ->add('vadresse1')
                ->add('hadresse1')
                ->add('wadresse1')
                ->add('vimmattriculation1')
                ->add('himmattriculation1')
                ->add('wimmattriculation1')
                ->add('vserie1')
                ->add('hserie1')
                ->add('wserie1')
                ->add('largeurVolet2')
                ->add('hauteurVolet2')
                ->add('vcertificat2')
                ->add('hcertificat2')
                ->add('vnom2')
                ->add('hnom2')
                ->add('wnom2')
                ->add('vadresse2')
                ->add('hadresse2')
                ->add('wadresse2')
                ->add('vimmattriculation2')
                ->add('himmattriculation2')
                ->add('wimmattriculation2')
                ->add('vserie2')
                ->add('hserie2')
                ->add('wserie2')
                ->add('largeurVolet3')
                ->add('hauteurVolet3')
                ->add('vcertificat3')
                ->add('hcertificat3')
                ->add('vimmattriculation3')
                ->add('himmattriculation3')
                ->add('vvalidite3')
                ->add('hvalidite3')
               ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ParametreCertificat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_parametrecertificat';
    }


}
