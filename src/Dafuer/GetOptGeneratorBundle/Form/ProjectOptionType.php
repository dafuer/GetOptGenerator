<?php

namespace Dafuer\GetOptGeneratorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Dafuer\GetOptGeneratorBundle\Entity\ProjectOption;

class ProjectOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shortName', null, array( 
                'label_render' => false,
                'required' => false,
                'attr'=>array('placeholder'=>'Short Name',
                            'class'=>'collection-item input-small'
                    )
            ))
            ->add('longName', null, array( 
                'label_render' => false,
                'required' => false,
                'attr'=>array('placeholder'=>'Long Name',
                            'class'=>'input-medium'
                    )
            ))
            ->add('arguments', null, array( 
                'label_render' => false,
                'required'=>false,
                'attr'=>array(
                            'style'=>'margin-top:9px',
                    
                    'onChange'=>'changeArgs($(this).attr("id"))'
                    )
            )) 
            ->add('mandatory', null, array( 
                'label_render' => false,
                'required'=>false,
                'attr'=>array(
                            'style'=>'margin-top:9px'
                    )
            ))  
            ->add('type', 'choice', array(
                'choices' => ProjectOption::$TYPE_VALUES,
                'label_render' => false,
                'empty_value'=>false,
                'required'=>false,
                'attr'=>array('class'=>'input-small',
                    //'disabled'=>'disabled'
                    )
            ))                
            ->add('description', null, array( 
                'label_render' => false,
                'required'=>false,
                'attr'=>array('placeholder'=>'Description',
                    )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dafuer\GetOptGeneratorBundle\Entity\ProjectOption'
        ));
    }

    public function getName()
    {
        return 'dafuer_getoptgeneratorbundle_projectoptiontype';
    }
}
