<?php

namespace Dafuer\GetOptGeneratorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shortName', null, array( 
                'label_render' => false,
                'attr'=>array('placeholder'=>'Short Name',
                            'class'=>'collection-item input-small'
                    )
            ))
            ->add('longName', null, array( 
                'label_render' => false,
                'attr'=>array('placeholder'=>'Long Name',
                            //'class'=>'collection-item'
                    )
            ))
            ->add('arguments', null, array( 
                'label_render' => false,
                'required'=>false,
                'attr'=>array(
                            'style'=>'margin-top:9px'
                            //'class'=>'collection-item'
                    )
            )) 
            ->add('description', null, array( 
                'label_render' => false,
                'required'=>false,
                'attr'=>array('placeholder'=>'Description',
                            //'class'=>'collection-item'
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
