<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\UtilisateurRepository;

class AffectationPisteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('agent', 'entity', array('class' => 'AppBundle:Utilisateur',
                    'query_builder' => function (UtilisateurRepository $er) {
                        return $er->createQueryBuilder('u')->leftJoin('u.groupe', 'g')->where('g.name like :groupe')->orderBy('u.username', 'ASC')->setParameter('groupe', 'CONTROLLEUR');
                    },
                    'choice_label' => 'nomComplet',))
                ->add('piste');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AffectationPiste'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_affectationpiste';
    }


}
