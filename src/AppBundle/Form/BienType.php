<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class BienType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('typeBien')
                ->add('searchparent', 'text', array('label'=>'Immeuble'))
                ->add('parentid', 'hidden')
                ->add('libelle')
                ->add('description')
                ->add('region', 'entity', array('class' => 'AppBundle:Region', 'choice_label' => 'libelle', 'placeholder' => 'Choisir une région'))
                ->add('cercle', 'entity', array('class' => 'AppBundle:Cercle', 'choice_label' => 'libelle', 'placeholder' => 'Choisir un cercle'))
                ->add('commune', 'entity', array('class' => 'AppBundle:Commune', 'choice_label' => 'libelle', 'placeholder' => 'Choisir une commune'))
                ->add('quartier')
                ->add('adresse')
                ->add('typeAffaire', 'choice',['choices' => ['VENTE' => 'VENTE', 'LOCATION' => 'LOCATION', 'VENTE ET LOCATION' => 'VENTE ET LOCATION' ]])                
                ->add('montant', 'number', array('label'=>'Prix de vente', 'grouping'=> true))
                ->add('loyer', 'number', array('label'=>'Prix du loyer mensuel', 'grouping'=> true))
                ->add('loyerJournalier', 'number', array('label'=>'Prix du loyer journalier', 'grouping'=> true))
                ->add('chambre')
                ->add('salon')
                ->add('cuisine')
                ->add('toilette')
                ->add('magasin')
                ->add('annexe')
                ->add('meuble')
                ->add('superficie')
                ->add('searchproprio', 'text', array('label'=>'Propriétaire'))
                ->add('proprietaireId', 'hidden')
                ->add('partProprio')
                ;
        
            $formModifier = function (FormInterface $form, \AppBundle\Entity\Quartier $quatier = null) {
              
            $commune = null === $quatier ? null : $quatier->getCommune();
            $cercle = null === $commune ? null : $commune->getCercle();
            $region = null === $cercle ? null : $cercle->getRegion();
            $form->get('commune')->setData($commune);
            $form->get('cercle')->setData($cercle);
            $form->get('region')->setData($region);
           
        };

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
                $formModifier($event->getForm(), $data->getQuartier());
            }
        );
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Bien'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_bien';
    }


}
