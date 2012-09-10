<?php

namespace Dafuer\GetOptGeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DafuerGetOptGeneratorBundle:Default:index.html.twig', array());
    }
    
    public function moreinformationAction()
    {
        return $this->render('DafuerGetOptGeneratorBundle:Default:moreinformation.html.twig', array());
    }
    
    public function startAction()
    {
        if($this->get('security.context')->isGranted('ROLE_USER')){
            return $this->redirect($this->generateUrl('DafuerGetOptGeneratorBundle_project'));
        }else{
            return $this->render('DafuerGetOptGeneratorBundle:Default:start.html.twig', array());
        }
    }
    
   /* public function compactloginAction(){
         $response= $this->forward('FOSUserBundle:Security:login');
         
         
        return $response;
       // return $this->render('DafuerGetOptGeneratorBundle:Default:moreinformation.html.twig');
    }*/
    
}
