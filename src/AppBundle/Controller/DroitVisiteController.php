<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DroitVisite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Droitvisite controller.
 *
 * @Route("admin/parametres/droitvisite")
 */
class DroitVisiteController extends Controller
{
    /**
     * Lists all droitVisite entities.
     *
     * @Route("/", name="admin_parametres_droitvisite_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('droitVisite/index.html.twig');
    }

    /**
     * Creates a new droitVisite entity.
     *
     * @Route("/new", name="admin_parametres_droitvisite_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $droitVisite = new Droitvisite();
        $form = $this->createForm('AppBundle\Form\DroitVisiteType', $droitVisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($droitVisite);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_droitvisite_show', array('id' => $droitVisite->getId()));
        }

        return $this->render('droitvisite/new.html.twig', array(
            'droitVisite' => $droitVisite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a droitVisite entity.
     *
     * @Route("/{id}", name="admin_parametres_droitvisite_show")
     * @Method("GET")
     */
    public function showAction(DroitVisite $droitVisite)
    {
        if (!$droitVisite) {
            throw $this->createNotFoundException("Le droit de visite demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($droitVisite);

        return $this->render('droitvisite/show.html.twig', array(
            'droitVisite' => $droitVisite,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing droitVisite entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_droitvisite_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, DroitVisite $droitVisite)
    {
        if (!$droitVisite) {
            throw $this->createNotFoundException("Le droit de visite demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($droitVisite);
        $editForm = $this->createForm('AppBundle\Form\DroitVisiteType', $droitVisite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_droitvisite_edit', array('id' => $droitVisite->getId()));
        }

        return $this->render('droitvisite/edit.html.twig', array(
            'droitVisite' => $droitVisite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a droitVisite entity.
     *
     * @Route("/{id}", name="admin_parametres_droitvisite_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, DroitVisite $droitVisite)
    {
        if (!$droitVisite) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($droitVisite->estSupprimable()) {
            $form = $this->createDeleteForm($droitVisite);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($droitVisite);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  ce droit de visite car il est utilisé.');
        }
        return $this->redirectToRoute('admin_parametres_droitvisite_index');
    }
    
    /**
     * Deletes a droitVisite entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_droitvisite_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(DroitVisite $droitVisite)
    {
        if (!$droitVisite) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($droitVisite->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($droitVisite);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  ce droit de visite car il est utilisé.');
        }
        return $this->redirectToRoute('admin_parametres_droitvisite_index');
    }

    /**
     * Creates a form to delete a droitVisite entity.
     *
     * @param DroitVisite $droitVisite The droitVisite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DroitVisite $droitVisite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_droitvisite_delete', array('id' => $droitVisite->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all DroitVisite entities.
     *
     * @Route("/admin/parametres/droitvisiteajax/liste", name="droitvisiteajax")
     * 
     * 
     */
    public function droitvisiteAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.ptacMin', 'r.ptacMax', 'r.montant', 'r.timbre', 'r.anasser', 'r.carrosserie.libelle', 'r.genre.libelle', 'r.usage.libelle');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:DroitVisite')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:DroitVisite')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:DroitVisite')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['ptacMin'],$aRow['ptacMax'],$aRow['montant'],$aRow['timbre'],$aRow['anasser'],$aRow['carrosserie'],$aRow['genre'],$aRow['usage'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a class='btn btn-success' href='".$this->generateUrl('admin_parametres_droitvisite_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a class='btn btn-info' href='".$this->generateUrl('admin_parametres_droitvisite_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a class='btn btn-danger' href='".$this->generateUrl('admin_parametres_droitvisite_delete', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all DroitVisite entities.
     *
     * @Route("/admin/parametres/droitvisite/download", name="droitvisite_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:DroitVisite')->findAll();
        $date = new \DateTime("now");
       $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       $phpExcelObject->getProperties()->setCreator("2SInnovation")
           ->setTitle("DROITVISITE_".$date->format('Y-m-d H:i:s'));
       $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'DROITVISITE'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("PTACMIN");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("PTACMAX");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("MONTANT");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("TIMBRE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("ANASSER");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("CARROSSERIE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("GENRE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("USAGE");$col++;
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getPtacMin());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getPtacMax());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getMontant());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getTimbre());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getAnasser());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getCarrosserie()->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getGenre()->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getUsage()->getLibelle());$col++;
            $ligne++;
        }
    }
}
