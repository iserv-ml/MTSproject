<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffaireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('searchclient', 'text', array('label'=>'Client'))
                ->add('clientid', 'hidden')
                ->add('searchbien', 'text', array('label'=>'Bien'))
                ->add('bienid', 'hidden')
                ->add('type', 'choice',['choices' => ['LOCATION' => 'LOCATION', 'VENTE' => 'VENTE']])
                ->add('typeLocation', 'choice',['choices' => ['CHOISIR UNE DUREE' => 'CHOISIR UNE DUREE','LONGUE DUREE' => 'LONGUE DUREE', 'JOURNALIERE' => 'JOURNALIERE']])
                ->add('dateDebut')
                ->add('dateFin')
                ->add('montant')
                ->add('loyer')
                ->add('caution')
                ->add('loyerm', 'hidden')
                ->add('loyerj', 'hidden')
                ->add('montantt', 'hidden');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Affaire'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_affaire';
    }


}
