<?php

namespace Dafuer\GetOptGeneratorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('slug')
                ->add('projectOptions', 'collection', array('type' => new ProjectOptionType(), 
                    'allow_add' => true,
                    'by_reference' => false,
                ));
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
