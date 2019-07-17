<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\UtilisateurRepository;

class AffectationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('agent', 'entity', array('class' => 'AppBundle:Utilisateur',
                    'query_builder' => function (UtilisateurRepository $er) {
                        return $er->createQueryBuilder('u')->leftJoin('u.groupe', 'g')->where('g.name=:groupe')->orderBy('u.username', 'ASC')->setParameter('groupe', 'CAISSIER');
                    },
                    'choice_label' => 'nomComplet',))
                ->add('caisse');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Affectation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_affectation';
    }


}
