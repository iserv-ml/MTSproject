<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AffectationPiste;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Affectationpiste controller.
 *
 * @Route("admin/gestion/affectationpiste")
 */
class AffectationPisteController extends Controller
{
    /**
     * Lists all affectationPiste entities.
     *
     * @Route("/", name="admin_gestion_affectationpiste_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('affectationpiste/index.html.twig');
    }

    /**
     * Creates a new affectationPiste entity.
     *
     * @Route("/new", name="admin_gestion_affectationpiste_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $affectationPiste = new Affectationpiste();
        $form = $this->createForm('AppBundle\Form\AffectationPisteType', $affectationPiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $derniere = $em->getRepository('AppBundle:AffectationPiste')->derniereAffectation($affectationPiste->getAgent()->getId());
            if(is_int($derniere)){
                throw $this->createNotFoundException("Oops... Une erreur s'est produite.");
            }else if($derniere){
               $derniere->setActif(0);
            }
            /**on ne désactive plus la dernière affectation pour autoriser plusieurs controlleurs
            $dernierePiste = $em->getRepository('AppBundle:AffectationPiste')->derniereAffectationPiste($affectationPiste->getPiste()->getId());
            if(is_int($dernierePiste)){
                throw $this->createNotFoundException("Oops... Une erreur s'est produite.");
            }else if($dernierePiste){
               $dernierePiste->setActif(0);
            }*/
            $em->persist($affectationPiste);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_gestion_affectationpiste_show', array('id' => $affectationPiste->getId()));
        }

        return $this->render('affectationpiste/new.html.twig', array(
            'affectationPiste' => $affectationPiste,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a affectationPiste entity.
     *
     * @Route("/{id}", name="admin_gestion_affectationpiste_show")
     * @Method("GET")
     */
    public function showAction(AffectationPiste $affectationPiste)
    {
        if (!$affectationPiste) {
            throw $this->createNotFoundException("L'affectation demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($affectationPiste);

        return $this->render('affectationpiste/show.html.twig', array(
            'affectationPiste' => $affectationPiste,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing affectationPiste entity.
     *
     * @Route("/{id}/edit", name="admin_gestion_affectationpiste_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AffectationPiste $affectationPiste)
    {
        $deleteForm = $this->createDeleteForm($affectationPiste);
        $editForm = $this->createForm('AppBundle\Form\AffectationPisteType', $affectationPiste);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_affectationpiste_edit', array('id' => $affectationPiste->getId()));
        }

        return $this->render('affectationpiste/edit.html.twig', array(
            'affectationPiste' => $affectationPiste,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a affectationPiste entity.
     *
     * @Route("/{id}", name="admin_gestion_affectationpiste_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AffectationPiste $affectationPiste)
    {
        $form = $this->createDeleteForm($affectationPiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($affectationPiste);
            $em->flush();
        }

        return $this->redirectToRoute('admin_gestion_affectationpiste_index');
    }

    /**
     * Creates a form to delete a affectationPiste entity.
     *
     * @param AffectationPiste $affectationPiste The affectationPiste entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AffectationPiste $affectationPiste)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_affectationpiste_delete', array('id' => $affectationPiste->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Affectation entities.
     *
     * @Route("/admin/gestion/affectationpisteajax/liste", name="affectationpisteajax")
     * 
     * 
     */
    public function affectationpisteAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.numero', 'r.actif');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 5) ? intval($col) : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Piste')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Piste')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:Piste')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $controlleurs = $em->getRepository('AppBundle:AffectationPiste')->affectationsActives($aRow['id']);
            $controlleur = "";
            foreach($controlleurs as $c){
                $controlleur .= $c->getAgent()->getUsername()." ";
            }
            $output['aaData'][] = array($aRow['numero'], $controlleur, $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_gestion_affectationpiste_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        return $action;
    }
    
    /**
     * Export all Caisse entities.
     *
     * @Route("/admin/gestion/affectationpiste/download", name="affectationpiste_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:AffectationPiste')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Affectation_piste_".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'Affectation_piste_'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("PISTE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("AGENT");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("DEBUT");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("STATUT");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("FIN");$col++;
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getPiste()->getNumero());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getAgent()->getUsername());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getDateCreation());$col++;
            $tmp = $entity->getActif() ? "En cours" : "Terminée";
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($tmp);$col++;
            $tmp = $entity->getActif() ? "" : $entity->getDateModification();
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($tmp);$col++;
            $ligne++;
        }
    }
}
