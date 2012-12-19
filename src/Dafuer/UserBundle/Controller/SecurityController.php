<?php

namespace Dafuer\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController {

    /**
     * Override this method because it has the hability to obtain more get variable.
     * This variable allows display compact or complete form
     */
    public function loginAction() {
        $response = parent::loginAction();
        return $response;
    }

    /**
     * Override this method to allowing have two different templates for 
     * same form, compact view or complete view
     */
    protected function renderLogin(array $data) {
        $request = $this->container->get('request');
        
        $skin=$request->attributes->get('skin','normal');
        
        if($skin=='normal')  $template = $template = sprintf('FOSUserBundle:Security:login.html.%s', $this->container->getParameter('fos_user.template.engine'));

        if($skin=='compact') $template = 'DafuerUserBundle:Security:logincompact.html.twig';
           
        return $this->container->get('templating')->renderResponse($template, $data);
    }

}
