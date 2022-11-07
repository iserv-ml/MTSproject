<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ParametreCertificat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Parametrecertificat controller.
 *
 * @Route("admin/parametre/vehicule/certificat")
 */
class ParametreCertificatController extends Controller
{
    /**
     * Lists all parametreCertificat entities.
     *
     * @Route("/", name="admin_parametre_vehicule_certificat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $parametreCertificats = $em->getRepository('AppBundle:ParametreCertificat')->findAll();
        $boutton = ($parametreCertificats == null || count($parametreCertificats) == 0) ? 0: 1;
        return $this->render('parametrecertificat/index.html.twig', array(
            'parametreCertificats' => $parametreCertificats,'boutton' => $boutton,
        ));
    }

    /**
     * Creates a new parametreCertificat entity.
     *
     * @Route("/new", name="admin_parametre_vehicule_certificat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $parametreCertificat = new Parametrecertificat();
        $form = $this->createForm('AppBundle\Form\ParametreCertificatType', $parametreCertificat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $paramExistant = $em->getRepository('AppBundle:ParametreCertificat')->recuperer();
            if($paramExistant){
                throw $this->createNotFoundException("Cette opération est interdite!");
            }
            $em->persist($parametreCertificat);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_vehicule_certificat_show', array('id' => $parametreCertificat->getId()));
        }

        return $this->render('parametrecertificat/new.html.twig', array(
            'parametreCertificat' => $parametreCertificat,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a parametreCertificat entity.
     *
     * @Route("/{id}", name="admin_parametre_vehicule_certificat_show")
     * @Method("GET")
     */
    public function showAction(ParametreCertificat $parametreCertificat)
    {
        $deleteForm = $this->createDeleteForm($parametreCertificat);

        return $this->render('parametrecertificat/show.html.twig', array(
            'parametreCertificat' => $parametreCertificat,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Displays a form to edit an existing centre entity.
     *
     * @Route("/admin/parametre/vehicule/certificat/modifier", name="admin_parametre_vehicule_certificat_modifier")
     * @Method({"GET", "POST"})
     */
    public function modifierAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $parametreCertificat = $em->getRepository('AppBundle:ParametreCertificat')->recuperer();
        if(!$parametreCertificat){
            throw $this->createNotFoundException("Cette opération est interdite!");
        }
        $editForm = $this->createForm('AppBundle\Form\ParametreCertificatType', $parametreCertificat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametre_vehicule_certificat_index');
        }

        return $this->render('parametrecertificat/edit.html.twig', array(
            'parametreCertificat' => $parametreCertificat,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing parametreCertificat entity.
     *
     * @Route("/{id}/edit", name="admin_parametre_vehicule_certificat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ParametreCertificat $parametreCertificat)
    {
        $deleteForm = $this->createDeleteForm($parametreCertificat);
        $editForm = $this->createForm('AppBundle\Form\ParametreCertificatType', $parametreCertificat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametre_vehicule_certificat_edit', array('id' => $parametreCertificat->getId()));
        }

        return $this->render('parametrecertificat/edit.html.twig', array(
            'parametreCertificat' => $parametreCertificat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a parametreCertificat entity.
     *
     * @Route("/{id}", name="admin_parametre_vehicule_certificat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ParametreCertificat $parametreCertificat)
    {
        $form = $this->createDeleteForm($parametreCertificat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($parametreCertificat);
            $em->flush();
        }

        return $this->redirectToRoute('admin_parametre_vehicule_certificat_index');
    }

    /**
     * Creates a form to delete a parametreCertificat entity.
     *
     * @param ParametreCertificat $parametreCertificat The parametreCertificat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ParametreCertificat $parametreCertificat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_vehicule_certificat_delete', array('id' => $parametreCertificat->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
