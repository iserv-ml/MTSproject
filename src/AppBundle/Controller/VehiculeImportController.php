<?php

namespace AppBundle\Controller;

use AppBundle\Entity\VehiculeImport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Vehicule;

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
            return $this->redirectToRoute('vehicule_index');
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
            
            $immatricultion = null;
            $chassis = null;
            $modelet = null;
            $genre = null;
            $carrosserie = null;
            $usage = null;
            $typeChassis = null;
            //Comment gérer typeVehicule?
            $ptac = null;
            $place = null;
            $puissance = null;
            $dateMiseCirculation = null;
            $carteGrise = null;
            $dateCarteGrise = null;
            $kilometrage = null;
            $couleur = null;
            $typeImmatriculation = null;
            $typeImmatriculationt = null;
            $dateValidite = null;
            $energie = null;
            $pv = null;
            $cu = null;
            $puissanceReelle = null;
            $capacite = null;
            $moteur = null;
            $immatricultionPrecedent = null;
            $dateImmatricultionPrecedent = null;
            $typeCarteGrise = null;
            $alimentation = null;
            $potCatalityque = null;
            $dateProchaineVisite = null;
            $em = $this->getDoctrine()->getManager();
            $objPHPExcel = \PHPExcel_IOFactory::load($path);
            $worksheet = $objPHPExcel->getSheet(0); 
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            for ($row = 2; $row <= $highestRow; ++ $row) {
                $colonne = 0;
                $immatricultion = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $chassis = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $modelet = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $modele = $em->getRepository('AppBundle:Modele')->trouverParLibelle($modelet);
                if($modele == null) {
                    continue;  
                }
                $genre = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $carrosserie = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $usage = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $typeVehicule = $em->getRepository('AppBundle:TypeVehicule')->trouverLibelle($genre, $usage, $carrosserie);
                if($typeVehicule == null) {
                    continue;  
                }
                $typeChassis = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                //Comment gérer typeVehicule?
                $ptac = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $place = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $puissance = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $dateMiseCirculation = \trim($worksheet->getCellByColumnAndRow($colonne, $row)->getValue());$colonne++;
                if($dateMiseCirculation != null && $dateMiseCirculation != "" && !$this->verifierFormatDate($dateMiseCirculation)){
                    continue;
                }
                $carteGrise = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $dateCarteGrise = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $kilometrage = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $couleur = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $typeImmatriculationt = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $typeImmatriculation = $em->getRepository('AppBundle:TypeImmatriculation')->trouverParLibelle($typeImmatriculationt);
                $dateValidite = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $energie = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $pv = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $cu = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $puissanceReelle = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $capacite = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $moteur = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $immatricultionPrecedent = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $dateImmatricultionPrecedent = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $typeCarteGrise = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $alimentation = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $potCatalityque = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $dateProchaineVisite = \trim($worksheet->getCellByColumnAndRow($colonne, $row)->getValue());$colonne++;
                if($dateProchaineVisite != null && $dateProchaineVisite != "" && !$this->verifierFormatDate($dateProchaineVisite)){
                    continue;
                }
                $vehicule = new Vehicule();
                $vehicule->initialiser($immatricultion, $chassis, $modele, $typeVehicule, $typeChassis, $ptac, $place, $puissance, $dateMiseCirculation, $carteGrise, $dateCarteGrise, $kilometrage, $couleur, $typeImmatriculation, $dateValidite, $energie, $pv, $cu, $puissanceReelle, $capacite, $moteur, $immatricultionPrecedent, $dateImmatricultionPrecedent, $alimentation, $potCatalityque,$dateProchaineVisite);
                $em->persist($vehicule);
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Chargement terminé');
        } catch(Exception $e) {
            $this->get('session')->getFlashBag()->add('notice', $e->getMessage());
        }
    }
    
    private function verifierFormatDate($date){
        return (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date));
    }
}
