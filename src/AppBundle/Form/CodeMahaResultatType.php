<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CodeMahaResultatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('controle')
                ->add('libelle')
                ->add('type', 'choice',['choices' => ['' => 'Choisir un type', 'VALEUR' => 'VALEUR', 'INTERVALLE' => 'INTERVALLE' ]])
                ->add('valeur')
                ->add('minimum')
                ->add('maximum')
                ->add('detail')
                ->add('actif')
                ->add('reussite');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\CodeMahaResultat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_codemaharesultat';
    }


}
