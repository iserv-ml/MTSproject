<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ModeleImport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Modeleimport controller.
 *
 * @Route("admin/parametres/modeles/importer")
 */
class ModeleImportController extends Controller
{
    /**
     * Lists all modeleImport entities.
     *
     * @Route("/", name="admin_parametres_modeles_importer_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $modeleImports = $em->getRepository('AppBundle:ModeleImport')->findAll();

        return $this->render('modeleimport/index.html.twig', array(
            'modeleImports' => $modeleImports,
        ));
    }

    /**
     * Creates a new modeleImport entity.
     *
     * @Route("/new", name="admin_parametres_modeles_importer_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $modeleImport = new Modeleimport();
        $form = $this->createForm('AppBundle\Form\ModeleImportType', $modeleImport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($modeleImport);
            $em->flush();
            $this->importer($modeleImport->getAbsolutePath());
            return $this->redirectToRoute('admin_parametres_modele_index');
        }

        return $this->render('modeleimport/new.html.twig', array(
            'modeleImport' => $modeleImport,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Creates a new modeleImport entity.
     *
     * @Route("/new/ottosys", name="admin_parametres_modeles_importer_ottosys")
     * @Method({"GET", "POST"})
     */
    public function ottosysAction(Request $request)
    {
        $modeleImport = new Modeleimport();
        $form = $this->createForm('AppBundle\Form\ModeleImportType', $modeleImport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($modeleImport);
            $em->flush();
            $this->importerOtossy($modeleImport->getAbsolutePath());
            return $this->redirectToRoute('admin_parametres_modele_index');
        }

        return $this->render('modeleimport/new.html.twig', array(
            'modeleImport' => $modeleImport,
            'form' => $form->createView(),
        ));
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

    /**
     * Finds and displays a modeleImport entity.
     *
     * @Route("/{id}", name="admin_parametres_modeles_importer_show")
     * @Method("GET")
     */
    public function showAction(ModeleImport $modeleImport)
    {
        $deleteForm = $this->createDeleteForm($modeleImport);

        return $this->render('modeleimport/show.html.twig', array(
            'modeleImport' => $modeleImport,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing modeleImport entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_modeles_importer_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ModeleImport $modeleImport)
    {
        $deleteForm = $this->createDeleteForm($modeleImport);
        $editForm = $this->createForm('AppBundle\Form\ModeleImportType', $modeleImport);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametres_modeles_importer_edit', array('id' => $modeleImport->getId()));
        }

        return $this->render('modeleimport/edit.html.twig', array(
            'modeleImport' => $modeleImport,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a modeleImport entity.
     *
     * @Route("/{id}", name="admin_parametres_modeles_importer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ModeleImport $modeleImport)
    {
        $form = $this->createDeleteForm($modeleImport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($modeleImport);
            $em->flush();
        }

        return $this->redirectToRoute('admin_parametres_modeles_importer_index');
    }

    /**
     * Creates a form to delete a modeleImport entity.
     *
     * @param ModeleImport $modeleImport The modeleImport entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ModeleImport $modeleImport)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_modeles_importer_delete', array('id' => $modeleImport->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    private function importerOtossy($path){
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
                    $marque->setAncienneBase(true);
                    $em->persist($marque);
                }
                $modele = new \AppBundle\Entity\Modele();
                $modele->setCode($modelet);
                $modele->setLibelle($modelet);
                $modele->setMarque($marque);
                $modele->setAncienneBase(true);
                $em->persist($modele);       
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Chargement terminé');
        } catch(Exception $e) {
            $this->get('session')->getFlashBag()->add('notice', $e->getMessage());
        }
    }
}
