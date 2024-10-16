<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CertificatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('debut')
                ->add('quantite')
                ->add('controlleur', 'entity', array('class' => 'AppBundle:Utilisateur',
                    'query_builder' => function (UtilisateurRepository $er) {
                        return $er->createQueryBuilder('u')->leftJoin('u.groupe', 'g')->where('g.name like :groupe')->orderBy('u.username', 'ASC')->setParameter('groupe', 'CONTROLLEUR');
                    },
                    'choice_label' => 'nomComplet',));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Certificat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_certificat';
    }


}
