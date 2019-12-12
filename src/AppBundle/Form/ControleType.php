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
                ->add('genre')
                ->add('categorie')
                ->add('libelle')
                ->add('code')
                ->add('type', 'choice',['choices' => ['' => 'Choisir un type', 'MESURE - INTERVALLE' => 'MESURE - INTERVALLE', 'MESURE - VALEUR' => 'MESURE - VALEUR', 'VISUEL' => 'VISUEL', 'DATE' => 'DATE' ]])
                ->add('unite')
                ->add('detail')
                ->add('actif');
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
