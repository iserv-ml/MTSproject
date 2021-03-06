<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserCreateType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array('required'=> true))
            ->add('email', 'email', array('required'=> true))
            ->add('password',
                'repeated', array(
           'first_name' => 'Mot_de_passe',
           'second_name' => 'Confirmer_le_mot_de_passe',
           'type' => 'password'
        ), array('required'=> true))
            ->add('nom')
            ->add('prenom')
            ->add('telephone')
            ->add('groupe')
            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Utilisateur'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'form_edit_user';
    }
}
