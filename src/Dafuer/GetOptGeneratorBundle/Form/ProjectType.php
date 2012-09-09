<?php

namespace Dafuer\GetOptGeneratorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('description')
                //->add('slug')
//                ->add('projectOptions', 'collection', array('type' => new ProjectOptionType(), 
//                    'allow_add' => true,
//                    'by_reference' => false,
//                ));
           ->add('projectOptions','collection', array(
                 'type' => new ProjectOptionType(), 
                 'allow_add' => true,
                 'allow_delete' => true,
                 'prototype' => true,
                  'widget_add_btn' => array('label' => 'Add Option', 'attr' => array('class' => 'btn btn-primary')),
                        'options' => array( // options for collection fields
                        'widget_remove_btn' => array('label' => 'remove', 'attr' => array('class' => 'btn btn-primary')),
                        'attr' => array('class' => 'span3'),
                        'widget_addon' => array(
                'type' => 'prepend',
                'text' => '@',
            ),
            'widget_control_group' => false,
        )
    ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Dafuer\GetOptGeneratorBundle\Entity\Project'
        ));
    }

    public function getName() {
        return 'dafuer_getoptgeneratorbundle_projecttype';
    }

}
