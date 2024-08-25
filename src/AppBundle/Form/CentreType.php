<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CentreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('code')
                ->add('libelle')
                ->add('description')
                ->add('adresse')
                ->add('telephone')
                ->add('maha')
                ->add('periodeCertificat')
                ->add('anaser')
                ->add('carteVierge')
                ->add('repertoire')
                ->add('ftpServer')
                ->add('ftpUsername')
                ->add('ftpUserpass');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Centre'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_centre';
    }


}
