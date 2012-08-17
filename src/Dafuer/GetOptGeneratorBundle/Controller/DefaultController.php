<?php

namespace Dafuer\GetOptGeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DafuerGetOptGeneratorBundle:Default:index.html.twig', array());
    }
}
