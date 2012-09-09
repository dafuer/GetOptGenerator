<?php

namespace Dafuer\GetOptGeneratorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Mopa\Bundle\BootstrapBundle\Navbar\NavbarFormInterface;


class NavbarType extends AbstractType implements NavbarFormInterface{

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->setAttribute('render_fieldset', false)
            ->setAttribute('label_render', false)
            ->setAttribute('show_legend', false)
            ->setAttribute('pull', "right")
            ->add('username', 'text', array(
                'widget_control_group' => false,
                'widget_controls' => false,
                'attr' => array(
                    'placeholder' => "username",
                    'class' => "input-small search-query"
                )
            ))
            ->add('password', 'password', array(
                'widget_control_group' => false,
                'widget_controls' => false,
                'attr' => array(
                    'placeholder' => "password",
                    'class' => "input-small search-query"
                )
            ))    
            
                ;
    }

    /*public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Dafuer\GetOptGeneratorBundle\Entity\Project'
        ));
    }*/

    public function getName() {
        return 'dafuer_getoptgeneratorbundle_navbartype';
    }

    
    /**
     * To implement NavbarFormTypeInterface
     */
    public function getRoute()
    {
        return "fos_user_security_check"; # return here the name of the route the form should point to
    }    
}
