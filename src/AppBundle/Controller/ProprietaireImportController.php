<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProprietaireImport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Proprietaireimport controller.
 *
 * @Route("admin/parametres/proprietaire/importer")
 */
class ProprietaireImportController extends Controller
{
    /**
     * Lists all proprietaireImport entities.
     *
     * @Route("/", name="admin_parametres_proprietaire_importer_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $proprietaireImports = $em->getRepository('AppBundle:ProprietaireImport')->findAll();

        return $this->render('proprietaireimport/index.html.twig', array(
            'proprietaireImports' => $proprietaireImports,
        ));
    }

    /**
     * Creates a new proprietaireImport entity.
     *
     * @Route("/new", name="admin_parametres_proprietaire_importer_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $proprietaireImport = new Proprietaireimport();
        $form = $this->createForm('AppBundle\Form\ProprietaireImportType', $proprietaireImport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($proprietaireImport);
            $em->flush();
            $this->importer($proprietaireImport->getAbsolutePath());
            return $this->redirectToRoute('proprietaire_index');
        }

        return $this->render('proprietaireimport/new.html.twig', array(
            'proprietaireImport' => $proprietaireImport,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a proprietaireImport entity.
     *
     * @Route("/{id}", name="admin_parametres_proprietaire_importer_show")
     * @Method("GET")
     */
    public function showAction(ProprietaireImport $proprietaireImport)
    {
        $deleteForm = $this->createDeleteForm($proprietaireImport);

        return $this->render('proprietaireimport/show.html.twig', array(
            'proprietaireImport' => $proprietaireImport,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing proprietaireImport entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_proprietaire_importer_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ProprietaireImport $proprietaireImport)
    {
        $deleteForm = $this->createDeleteForm($proprietaireImport);
        $editForm = $this->createForm('AppBundle\Form\ProprietaireImportType', $proprietaireImport);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametres_proprietaire_importer_edit', array('id' => $proprietaireImport->getId()));
        }

        return $this->render('proprietaireimport/edit.html.twig', array(
            'proprietaireImport' => $proprietaireImport,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a proprietaireImport entity.
     *
     * @Route("/{id}", name="admin_parametres_proprietaire_importer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ProprietaireImport $proprietaireImport)
    {
        $form = $this->createDeleteForm($proprietaireImport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($proprietaireImport);
            $em->flush();
        }

        return $this->redirectToRoute('admin_parametres_proprietaire_importer_index');
    }

    /**
     * Creates a form to delete a proprietaireImport entity.
     *
     * @param ProprietaireImport $proprietaireImport The proprietaireImport entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ProprietaireImport $proprietaireImport)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_proprietaire_importer_delete', array('id' => $proprietaireImport->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    private function importer($path){
        try {
            $nom = null;
            $prenom = null;
            $adresse = null;
            $activite = null;
            $telephone = null;
            $ottosys = null;
            $em = $this->getDoctrine()->getManager();
            $objPHPExcel = \PHPExcel_IOFactory::load($path);
            $worksheet = $objPHPExcel->getSheet(0); 
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            for ($row = 2; $row <= $highestRow; ++ $row) {
                $colonne = 0;
                $nom = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $prenom = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $adresse = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $activite = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $telephone = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $ottosys = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $proprietaire = new \AppBundle\Entity\Proprietaire();
                $proprietaire->setNom($nom);
                $proprietaire->setPrenom($prenom);
                $proprietaire->setAdresse($adresse);
                $proprietaire->setFonction($activite);
                $proprietaire->setTelephone($telephone);
                $proprietaire->setIdOttosys($ottosys);
                $proprietaire->setPersonneMorale(false);
                $em->persist($proprietaire);       
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Chargement terminé');
        } catch(Exception $e) {
            $this->get('session')->getFlashBag()->add('notice', $e->getMessage());
        }
    }
}
