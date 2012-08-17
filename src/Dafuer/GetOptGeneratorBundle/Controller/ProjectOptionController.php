<?php

namespace Dafuer\GetOptGeneratorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Dafuer\GetOptGeneratorBundle\Entity\ProjectOption;
use Dafuer\GetOptGeneratorBundle\Form\ProjectOptionType;

/**
 * ProjectOption controller.
 *
 */
class ProjectOptionController extends Controller
{
    /**
     * Lists all ProjectOption entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DafuerGetOptGeneratorBundle:ProjectOption')->findAll();

        return $this->render('DafuerGetOptGeneratorBundle:ProjectOption:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a ProjectOption entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DafuerGetOptGeneratorBundle:ProjectOption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProjectOption entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DafuerGetOptGeneratorBundle:ProjectOption:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new ProjectOption entity.
     *
     */
    public function newAction()
    {
        $entity = new ProjectOption();
        $form   = $this->createForm(new ProjectOptionType(), $entity);

        return $this->render('DafuerGetOptGeneratorBundle:ProjectOption:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new ProjectOption entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new ProjectOption();
        $form = $this->createForm(new ProjectOptionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('projectoption_show', array('id' => $entity->getId())));
        }

        return $this->render('DafuerGetOptGeneratorBundle:ProjectOption:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ProjectOption entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DafuerGetOptGeneratorBundle:ProjectOption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProjectOption entity.');
        }

        $editForm = $this->createForm(new ProjectOptionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DafuerGetOptGeneratorBundle:ProjectOption:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing ProjectOption entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DafuerGetOptGeneratorBundle:ProjectOption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProjectOption entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ProjectOptionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('projectoption_edit', array('id' => $id)));
        }

        return $this->render('DafuerGetOptGeneratorBundle:ProjectOption:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ProjectOption entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DafuerGetOptGeneratorBundle:ProjectOption')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ProjectOption entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('projectoption'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
