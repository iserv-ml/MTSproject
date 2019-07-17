<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affectation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Affectation controller.
 *
 * @Route("admin/gestion/affectation")
 */
class AffectationController extends Controller
{
    /**
     * Lists all affectation entities.
     *
     * @Route("/", name="admin_gestion_affectation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('affectation/index.html.twig');
    }

    /**
     * Creates a new affectation entity.
     *
     * @Route("/new", name="admin_gestion_affectation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $affectation = new Affectation();
        $form = $this->createForm('AppBundle\Form\AffectationType', $affectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $derniere = $em->getRepository('AppBundle:Affectation')->derniereAffectation($affectation->getAgent()->getId());
            if(is_int($derniere)){
                throw $this->createNotFoundException("Oops... Une erreur s'est produite.");
            }else if($derniere){
               $derniere->setActif(0);
            }
            $derniereCaisse = $em->getRepository('AppBundle:Affectation')->derniereAffectationCaisse($affectation->getCaisse()->getId());
            if(is_int($derniereCaisse)){
                throw $this->createNotFoundException("Oops... Une erreur s'est produite.");
            }else if($derniereCaisse){
               $derniereCaisse->setActif(0);
            }
            $em->persist($affectation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_gestion_affectation_show', array('id' => $affectation->getId()));
        }

        return $this->render('affectation/new.html.twig', array(
            'affectation' => $affectation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a affectation entity.
     *
     * @Route("/{id}", name="admin_gestion_affectation_show")
     * @Method("GET")
     */
    public function showAction(Affectation $affectation)
    {
        if (!$affectation) {
            throw $this->createNotFoundException("L'affectation demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($affectation);

        return $this->render('affectation/show.html.twig', array(
            'affectation' => $affectation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing affectation entity.
     *
     * @Route("/{id}/edit", name="admin_gestion_affectation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Affectation $affectation)
    {
        $deleteForm = $this->createDeleteForm($affectation);
        $editForm = $this->createForm('AppBundle\Form\AffectationType', $affectation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_affectation_edit', array('id' => $affectation->getId()));
        }

        return $this->render('affectation/edit.html.twig', array(
            'affectation' => $affectation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a affectation entity.
     *
     * @Route("/{id}", name="admin_gestion_affectation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Affectation $affectation)
    {
        $form = $this->createDeleteForm($affectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($affectation);
            $em->flush();
        }

        return $this->redirectToRoute('admin_gestion_affectation_index');
    }

    /**
     * Creates a form to delete a affectation entity.
     *
     * @param Affectation $affectation The affectation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Affectation $affectation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_affectation_delete', array('id' => $affectation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Affectation entities.
     *
     * @Route("/admin/gestion/affectationajax/liste", name="affectationajax")
     * 
     * 
     */
    public function affectationAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'a.username', 'c.numero', 'r.actif', 'r.date');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Affectation')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Affectation')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $actif = $aRow['actif'] ? "Active" : "Inactive";
            $output['aaData'][] = array($aRow['username'],$aRow['numero'],$actif,$aRow['date'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_gestion_affectation_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        return $action;
    }
    
    /**
     * Export all Affectation entities.
     *
     * @Route("/admin/gestion/affectation/download", name="affectation_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Affectation')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Affectation".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'Affectation'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("AGENT");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("PISTE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("STATUT");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getAgent()->getUsername());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getCaisse()->getNumero());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getActif());$col++;
            $ligne++;
        }
    }
}
