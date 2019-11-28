<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('libelle')
                ->add('code')
                ->add('type', 'choice',['choices' => ['' => 'Choisir un type', 'VALEUR MAHA' => 'VALEUR MAHA', 'INTERVALE MAHA' => 'INTERVALE MAHA', 'VISUEL' => 'VISUEL' ]])
                ->add('unite')
                ->add('minimum')
                ->add('maximum')
                ->add('detail')
                ->add('actif')
                ->add('categorie');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Controle'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_controle';
    }


}
