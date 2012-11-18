<?php

namespace Dafuer\GetOptGeneratorBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dafuer\GetOptGeneratorBundle\Entity\User;
use Dafuer\GetOptGeneratorBundle\Entity\Project;
use Dafuer\GetOptGeneratorBundle\Form\ProjectType;

/**
 * Project controller.
 *
 */
class ProjectController extends Controller
{
    /**
     * Lists all Project entities.
     * (securized)
     */
    public function indexAction()
    {
        if(!$this->get('security.context')->isGranted('ROLE_USER')){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DafuerGetOptGeneratorBundle:Project')->findByUser($this->get('security.context')->getToken()->getUser());

        
        return $this->render('DafuerGetOptGeneratorBundle:Project:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Project entity.
     * (securized)
     */
    public function showAction($id=-1)
    {
        
        $session = $this->getRequest()->getSession();
        $entity=null;        
        
        if($id==-1){
            $entity=$session->get('project');
        }else{
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DafuerGetOptGeneratorBundle:Project')->find($id);

            if($entity && $entity->getUser()->getId()!=$this->get('security.context')->getToken()->getUser()->getId()){
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }          
        }
        
           
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DafuerGetOptGeneratorBundle:Project:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Project entity.
     *
     */
    public function newAction()
    {
        $entity = new Project();
        $form   = $this->createForm(new ProjectType(), $entity);

        return $this->render('DafuerGetOptGeneratorBundle:Project:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Project entity.
     *
     */
    public function createAction(Request $request)
    {
        $user=$this->get('security.context')->getToken()->getUser();
        
        $formOptions=array();
        
        if($user=="anon."){
            $user=new User();
            $formOptions['csrf_protection']=false;
        }           
        
        $entity  = new Project();
        
        $form = $this->createForm(new ProjectType(), $entity, $formOptions);
        $form->bind($request);   
        
        $entity->setUser($user);
       
        foreach ($entity->getProjectOptions() as $option){
            $option->setProject($entity);
        }
        
       
        if ($form->isValid()) {
            if($user->getId()!=null){
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                
                return $this->redirect($this->generateUrl('DafuerGetOptGeneratorBundle_project_show', array('id' => $entity->getId())));
            }else{
                $session = $this->getRequest()->getSession();
                $session->set('project', $entity);
                return $this->redirect($this->generateUrl('DafuerGetOptGeneratorBundle_project_show_session'));
            }
        }
        //echo $form->getErrorsAsString();

        return $this->render('DafuerGetOptGeneratorBundle:Project:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Project entity.
     * (securized)
     */
    public function editAction($id=-1)
    {
        /*$em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DafuerGetOptGeneratorBundle:Project')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }*/
        $session = $this->getRequest()->getSession();
        $entity=null;        
        
        if($id==-1){
            $entity=$session->get('project');
        }else{
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DafuerGetOptGeneratorBundle:Project')->find($id);

            if($entity && $entity->getUser()->getId()!=$this->get('security.context')->getToken()->getUser()->getId()){
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }          
        }
        
           
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }        

        $editForm = $this->createForm(new ProjectType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DafuerGetOptGeneratorBundle:Project:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Edits an existing Project entity.
     *
     */
    public function updateAction(Request $request, $id=-1)
    {
        $user=$this->get('security.context')->getToken()->getUser();
       
        if ($user == "anon.") {
            $user = new User();
        }  
        
       // $em = $this->getDoctrine()->getManager();

        //$entity = $em->getRepository('DafuerGetOptGeneratorBundle:Project')->find($id);
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $entity=null;        
        
        if($id==-1){
            $entity=$session->get('project');
        }else{
            
            $entity = $em->getRepository('DafuerGetOptGeneratorBundle:Project')->find($id);

            if($entity && $entity->getUser()->getId()!=$user->getId()){
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }          
        }        
        /*if($id!=-1){
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Project entity.');


                if($entity && $entity->getUser()->getId()!=$this->get('security.context')->getToken()->getUser()->getId()){
                    throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
                }              
            }
        }else{
            $session = $this->getRequest()->getSession();
            $session->set('project', $entity);
        }*/

        $old_project_options=$entity->getProjectOptions();
        
        // Remove old options
        
        foreach ($entity->getProjectOptions() as $option){
            $em->remove($option);
        }
        
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ProjectType(), $entity);
        $editForm->bind($request);

        foreach ($entity->getProjectOptions() as $option){
            $option->setProject($entity);
        }         
        
        if ($editForm->isValid()) {
            if($user->getId()!=null){
                $em->flush();
                $em->persist($entity);
                foreach ($entity->getProjectOptions() as $option){
                    $em->persist($option);
                }   
                $em->flush();
            }else{
                $session->set('project', $entity);
                return $this->redirect($this->generateUrl('DafuerGetOptGeneratorBundle_project_show_session'));                
            }

            return $this->redirect($this->generateUrl('DafuerGetOptGeneratorBundle_project_show', array('id' => $entity->getId())));
        }

        return $this->render('DafuerGetOptGeneratorBundle:Project:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Project entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DafuerGetOptGeneratorBundle:Project')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Project entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('DafuerGetOptGeneratorBundle_project'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
