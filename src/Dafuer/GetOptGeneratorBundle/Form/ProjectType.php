<?php

namespace Dafuer\GetOptGeneratorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Dafuer\GetOptGeneratorBundle\Entity\Project;

class ProjectType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name',null,array(
                    'label_render' => false,
                    'attr'=>array(
                        'class'=>'input-xxlarge',
                        'placeholder'=>'Project Name',
                        'style'=>'font-size:xx-large; font-weight: bold; height:40px;margin-bottom: 0px;vertical-align:botton'
                    )
                ))
                
                ->add('description',null,array(
                    'label_render' => false,
                    'attr'=>array(
                        'class'=>'input-xlarge',
                        'placeholder'=>'Description',
                        'style'=>'font-size:x-large; font-weight: bold; height:1.2em;margin-bottom: 0px'
                    )
                ))
                ->add('languaje', 'choice', array(
                    'choices' => Project::$LANGUAJE_VALUES,
                    'label_render' => false,
                    'required'=>true,
                    'attr'=>array('class'=>'input-small',
                                //'class'=>'collection-item'
                        )
                ))                
                ->add('projectOptions','collection', array(
                            'type' => new ProjectOptionType(), 
                            'allow_add' => true,
                            'allow_delete' => true,
                            'prototype' => true,
                            'widget_add_btn' => array('label' => 'Add Option', 'attr' => array('class' => 'btn btn-primary')),
                            'options' => array( // options for collection fields
                                'widget_remove_btn' => array('label' => 'remove', 'attr' => array('class' => 'btn btn-danger')),
                                'attr' => array('class' => 'span3'),
                                'widget_addon' => array(
                                                    'type' => 'prepend',
                                                    'text' => '@',
                                                    ),
                                'widget_control_group' => true,
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
