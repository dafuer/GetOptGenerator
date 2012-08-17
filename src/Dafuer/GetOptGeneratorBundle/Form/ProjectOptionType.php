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
            ->add('shortName')
            ->add('longName')
            ->add('description')
            ->add('project')
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
