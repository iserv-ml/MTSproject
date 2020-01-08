<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vehicule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Vehicule controller.
 *
 * @Route("vehicule")
 */
class VehiculeController extends Controller
{
    /**
     * Lists all vehicule entities.
     *
     * @Route("/", name="vehicule_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        return $this->render('vehicule/index.html.twig', array(
            'centre' => $centre,
        ));
    }

    /**
     * Creates a new vehicule entity.
     *
     * @Route("/new", name="vehicule_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $vehicule = new Vehicule();
        $form = $this->createForm('AppBundle\Form\VehiculeType', $vehicule);
        $form->handleRequest($request);
        $proprietaire = $em->getRepository('AppBundle:Proprietaire')->find($request->get("proprietaireid", 0));
        $vehicule->setProprietaire($proprietaire);
        if ($form->isSubmitted() && $form->isValid()) {
            $field = $request->get("appbundle_vehicule");
            $proprietaire = $em->getRepository('AppBundle:Proprietaire')->find($field['proprietaireId']);
            $modele = $em->getRepository('AppBundle:Modele')->find($field['modeleId']);
            //$genre = $field['ptac'] < 3500 ? "VL" : "PL";
            //$typeVehicule = $em->getRepository('AppBundle:TypeVehicule')->trouver($genre, $field['usageId'], $field['carrosserieId']);
            if (!$proprietaire || !$modele) {
                $this->get('session')->getFlashBag()->add('error', "Merci de remplir correctement le formulaire!.");
                return $this->render('vehicule/new.html.twig', array(
            'vehicule' => $vehicule,
            'form' => $form->createView(),
        ));
            }else{
                $vehicule->setProprietaire($proprietaire); 
                $vehicule->setModele($modele);
                $em->persist($vehicule);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
                return $this->redirectToRoute('vehicule_show', array('id' => $vehicule->getId()));
            }
            
        }

        return $this->render('vehicule/new.html.twig', array(
            'vehicule' => $vehicule,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a vehicule entity.
     *
     * @Route("/{id}", name="vehicule_show")
     * @Method("GET")
     */
    public function showAction(Vehicule $vehicule)
    {
        if (!$vehicule) {
            throw $this->createNotFoundException("Le véhicule demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($vehicule);

        return $this->render('vehicule/show.html.twig', array(
            'vehicule' => $vehicule,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing vehicule entity.
     *
     * @Route("/{id}/edit", name="vehicule_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Vehicule $vehicule)
    {
        if (!$vehicule) {
            throw $this->createNotFoundException("Le véhicule demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($vehicule);
        $editForm = $this->createForm('AppBundle\Form\VehiculeType', $vehicule);
        $editForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $field = $request->get("appbundle_vehicule");
            $proprietaire = $em->getRepository('AppBundle:Proprietaire')->find($field['proprietaireId']);
            $modele = $em->getRepository('AppBundle:Modele')->find($field['modeleId']);
            //$genre = $field['ptac'] < 3500 ? "VL" : "PL";
            //$typeVehicule = $em->getRepository('AppBundle:TypeVehicule')->trouver($genre, $field['usageId'], $field['carrosserieId']);
            if($proprietaire){
                $vehicule->setProprietaire($proprietaire);
            }
            if($modele){
                $vehicule->setModele($modele);
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('vehicule_edit', array('id' => $vehicule->getId()));
        }

        return $this->render('vehicule/edit.html.twig', array(
            'vehicule' => $vehicule,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a vehicule entity.
     *
     * @Route("/{id}", name="vehicule_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Vehicule $vehicule)
    {
        if (!$vehicule) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($vehicule->estSupprimable()) {
            $form = $this->createDeleteForm($vehicule);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($vehicule);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  ce vehicule car il est utilisée.');
        }

        return $this->redirectToRoute('vehicule_index');
    }
    
    /**
     * Deletes a vehicule entity.
     *
     * @Route("/{id}/delete", name="vehicule_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(Vehicule $vehicule)
    {
        if (!$vehicule) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($vehicule->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($vehicule);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer ce véhicule car il est utilisé');
        }
        return $this->redirectToRoute('vehicule_index');
    }

    /**
     * Creates a form to delete a vehicule entity.
     *
     * @param Vehicule $vehicule The vehicule entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Vehicule $vehicule)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vehicule_delete', array('id' => $vehicule->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Vehicule entities.
     *
     * @Route("/admin/parametres/vehiculeajax/liste", name="vehiculeajax")
     * 
     * 
     */
    public function vehiculeAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.immatriculation', 'r.chassis', 'ma.libelle', 'p.nom');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Vehicule')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Vehicule')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:Vehicule')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['immatriculation'], $aRow['chassis'], $aRow['marque'].'<br/>'.$aRow['modele'],$aRow['nom'].'<br/>'.$aRow['prenom'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('vehicule_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ENREGISTREMENT')){
                $action .= " <a title='Modifier' class='btn btn-info' href='".$this->generateUrl('vehicule_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a title='Supprimer' class='btn btn-danger' href='".$this->generateUrl('vehicule_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
                $action .= " <a title='Aiguiller' class='btn btn-warning' href='".$this->generateUrl('aiguiller', array('id'=> $id, 'type'=>0 ))."' ><i class='fa fa-compass'> </i></a>";
                $action .= " <a title='Aiguiller' class='btn btn-warning' href='".$this->generateUrl('aiguiller', array('id'=> $id, 'type'=>1 ))."' ><i class='fa fa-compass'> </i></a>";
        }
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
            $action .= " <a title='Contre visite' class='btn btn-warning' href='".$this->generateUrl('contrevisite', array('id'=> $id ))."' ><i class='fa fa-check'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Vehicule entities.
     *
     * @Route("/admin/vehicule/download", name="vehicule_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Vehicule')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Vehicule".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'Vehicule'.$date->format('Y_m_d_H_i_s').'.xls';
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        return $response;        
    }

    
    private function writeRapport($phpExcelObject, $entities) {
        $phpExcelObject->setActiveSheetIndex(0);
        $col = 0;
        $objWorksheet = $phpExcelObject->getActiveSheet();
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Marque");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Modèle");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("IMMATRICULATION");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getModele()->getMarque()->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getModele()->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->Immatriculation()->getCode());$col++;
            $ligne++;
        }
    }
    
    /**
     * @Route("/admin/parametres/modelesautocomplete", name="modeles_autocomplete")
     */
    public function modelesAjaxAction(Request $request){   
        $search = $request->get('search', '');
        $maxRows = $request->get('maxRows', 15);
        $em = $this->getDoctrine()->getManager();
        $modeles = $em->getRepository('AppBundle:Modele')->findAjax($search, $maxRows);
        return new Response(json_encode($modeles));
    }
    
    /**
     * @Route("/admin/parametres/proprietairesautocomplete", name="proprietaires_autocomplete")
     */
    public function proprietairesAjaxAction(Request $request){   
        $search = $request->get('search', '');
        $maxRows = $request->get('maxRows', 15);
        $em = $this->getDoctrine()->getManager();
        $modeles = $em->getRepository('AppBundle:Proprietaire')->findAjax($search, $maxRows);
        return new Response(json_encode($modeles));
    }
    
    /**
     * @Route("/admin/parametres/usagesautocomplete", name="usages_autocomplete")
     */
    public function usagesAjaxAction(Request $request){   
        $search = $request->get('search', '');
        $maxRows = $request->get('maxRows', 15);
        $genre = $request->get('genre', '');
        $em = $this->getDoctrine()->getManager();
        $modeles = $em->getRepository('AppBundle:TypeVehicule')->findUsageAjax($search, $maxRows, $genre);
        return new Response(json_encode($modeles));
    }
    
    /**
     * @Route("/admin/parametres/carrosseriesautocomplete", name="carrosseries_autocomplete")
     */
    public function carrosseriesAjaxAction(Request $request){   
        $search = $request->get('search', '');
        $maxRows = $request->get('maxRows', 15);
        $genre = $request->get('genre', '');
        $usage = $request->get('usage', 0);
        $em = $this->getDoctrine()->getManager();
        $modeles = $em->getRepository('AppBundle:TypeVehicule')->findCarrosserieAjax($search, $maxRows, $genre, $usage);
        return new Response(json_encode($modeles));
    }
}
