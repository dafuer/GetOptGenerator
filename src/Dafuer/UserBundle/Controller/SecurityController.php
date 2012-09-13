<?php

namespace Dafuer\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController {

    /**
     * Sobreescribo este metodo para poder pasarle una variable mas por get que sea
     * si se deve visualizar completo o compacto 
     */
    public function loginAction() {
        $response = parent::loginAction();
        return $response;
    }

    /**
     * Sobreescribo este metodo para ser capaz de tener 2 plantillas para el mismo formulario
     * en funcion de si quiero una vista compacta o completa del formulario
     */
    protected function renderLogin(array $data) {
        $request = $this->container->get('request');
        
        $skin=$request->attributes->get('skin','normal');
        
        if($skin=='normal')  $template = $template = sprintf('FOSUserBundle:Security:login.html.%s', $this->container->getParameter('fos_user.template.engine'));

        if($skin=='compact') $template = 'DafuerUserBundle:Security:logincompact.html.twig';
           
        return $this->container->get('templating')->renderResponse($template, $data);
    }

}
