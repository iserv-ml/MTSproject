<?php

namespace AppBundle\Controller;

use AppBundle\Entity\VehiculeImport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Vehiculeimport controller.
 *
 * @Route("vehiculeimport")
 */
class VehiculeImportController extends Controller
{
    /**
     * Lists all vehiculeImport entities.
     *
     * @Route("/", name="vehiculeimport_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $vehiculeImports = $em->getRepository('AppBundle:VehiculeImport')->findAll();

        return $this->render('vehiculeimport/index.html.twig', array(
            'vehiculeImports' => $vehiculeImports,
        ));
    }

    /**
     * Creates a new vehiculeImport entity.
     *
     * @Route("/new", name="admin_parametres_vehicules_importer_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $vehiculeImport = new Vehiculeimport();
        $form = $this->createForm('AppBundle\Form\VehiculeImportType', $vehiculeImport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vehiculeImport);
            $em->flush();
            $this->importer($vehiculeImport->getAbsolutePath());
            return $this->redirectToRoute('vehiculeimport_show', array('id' => $vehiculeImport->getId()));
        }

        return $this->render('vehiculeimport/new.html.twig', array(
            'vehiculeImport' => $vehiculeImport,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a vehiculeImport entity.
     *
     * @Route("/{id}", name="vehiculeimport_show")
     * @Method("GET")
     */
    public function showAction(VehiculeImport $vehiculeImport)
    {
        $deleteForm = $this->createDeleteForm($vehiculeImport);

        return $this->render('vehiculeimport/show.html.twig', array(
            'vehiculeImport' => $vehiculeImport,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing vehiculeImport entity.
     *
     * @Route("/{id}/edit", name="vehiculeimport_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, VehiculeImport $vehiculeImport)
    {
        $deleteForm = $this->createDeleteForm($vehiculeImport);
        $editForm = $this->createForm('AppBundle\Form\VehiculeImportType', $vehiculeImport);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vehiculeimport_edit', array('id' => $vehiculeImport->getId()));
        }

        return $this->render('vehiculeimport/edit.html.twig', array(
            'vehiculeImport' => $vehiculeImport,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a vehiculeImport entity.
     *
     * @Route("/{id}", name="vehiculeimport_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, VehiculeImport $vehiculeImport)
    {
        $form = $this->createDeleteForm($vehiculeImport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($vehiculeImport);
            $em->flush();
        }

        return $this->redirectToRoute('vehiculeimport_index');
    }

    /**
     * Creates a form to delete a vehiculeImport entity.
     *
     * @param VehiculeImport $vehiculeImport The vehiculeImport entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(VehiculeImport $vehiculeImport)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vehiculeimport_delete', array('id' => $vehiculeImport->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    private function importer($path){
        try {
            $marquet = null;
            $modelet = null;
            $em = $this->getDoctrine()->getManager();
            $objPHPExcel = \PHPExcel_IOFactory::load($path);
            $worksheet = $objPHPExcel->getSheet(0); 
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            for ($row = 2; $row <= $highestRow; ++ $row) {
                $colonne = 0;
                $marquet = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $modelet = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $modele = $em->getRepository('AppBundle:Modele')->trouverParLibelle($modelet);
                if($modele != null) {continue;}
                $marque = $em->getRepository('AppBundle:Marque')->trouverParLibelle($marquet);
                if($marque == null){
                    $marque = new \AppBundle\Entity\Marque();
                    $marque->setCode($marquet);
                    $marque->setLibelle($marquet);
                    $em->persist($marque);
                }
                $modele = new \AppBundle\Entity\Modele();
                $modele->setCode($modelet);
                $modele->setLibelle($modelet);
                $modele->setMarque($marque);
                $em->persist($modele);       
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Chargement terminé');
        } catch(Exception $e) {
            $this->get('session')->getFlashBag()->add('notice', $e->getMessage());
        }
    }
}
