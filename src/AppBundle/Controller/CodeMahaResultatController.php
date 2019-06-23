<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CodeMahaResultat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Codemaharesultat controller.
 *
 * @Route("admin/parametres/codemaharesultat")
 */
class CodeMahaResultatController extends Controller
{
    /**
     * Lists all codeMahaResultat entities.
     *
     * @Route("/", name="admin_parametres_codemaharesultat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('codemaharesultat/index.html.twig');
    }

    /**
     * Creates a new codeMahaResultat entity.
     *
     * @Route("/new", name="admin_parametres_codemaharesultat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $codeMahaResultat = new Codemaharesultat();
        $form = $this->createForm('AppBundle\Form\CodeMahaResultatType', $codeMahaResultat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($codeMahaResultat);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_codemaharesultat_show', array('id' => $codeMahaResultat->getId()));
        }

        return $this->render('codemaharesultat/new.html.twig', array(
            'codeMahaResultat' => $codeMahaResultat,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a codeMahaResultat entity.
     *
     * @Route("/{id}", name="admin_parametres_codemaharesultat_show")
     * @Method("GET")
     */
    public function showAction(CodeMahaResultat $codeMahaResultat)
    {
        if (!$codeMahaResultat) {
            throw $this->createNotFoundException("Le contrôle demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($codeMahaResultat);

        return $this->render('codemaharesultat/show.html.twig', array(
            'codeMahaResultat' => $codeMahaResultat,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing codeMahaResultat entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_codemaharesultat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CodeMahaResultat $codeMahaResultat)
    {
        if (!$codeMahaResultat) {
            throw $this->createNotFoundException("Le contrôle demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($codeMahaResultat);
        $editForm = $this->createForm('AppBundle\Form\CodeMahaResultatType', $codeMahaResultat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_codemaharesultat_edit', array('id' => $codeMahaResultat->getId()));
        }

        return $this->render('codemaharesultat/edit.html.twig', array(
            'codeMahaResultat' => $codeMahaResultat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a codeMahaResultat entity.
     *
     * @Route("/{id}", name="admin_parametres_codemaharesultat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CodeMahaResultat $codeMahaResultat)
    {
        if(!$codeMahaResultat) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($codeMahaResultat->estSupprimable()) {
            $form = $this->createDeleteForm($codeMahaResultat);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($codeMahaResultat);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  ce controle car il est utilisé.');
        }

        return $this->redirectToRoute('admin_parametres_codemaharesultat_index');
    }
    
    /**
     * Deletes a controle entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_codemaharesultat_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(CodeMahaResultat $codeMahaResultat)
    {
        if (!$codeMahaResultat) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($codeMahaResultat->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($codeMahaResultat);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer ce contrôle car il est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_codemaharesultat_index');
    }

    /**
     * Creates a form to delete a codeMahaResultat entity.
     *
     * @param CodeMahaResultat $codeMahaResultat The codeMahaResultat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CodeMahaResultat $codeMahaResultat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_codemaharesultat_delete', array('id' => $codeMahaResultat->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all CodeResultatMaha entities.
     *
     * @Route("/admin/parametres/coderesultatmahaajax/liste", name="codemahaajax")
     * 
     * 
     */
    public function coderesultatmahaAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'c.libelle','r.libelle', 'r.code', 'r.actif');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:CodeMahaResultat')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:CodeMahaResultat')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $actif = $aRow['reussite'] ? 'SUCCES' : 'ECHEC';
            $output['aaData'][] = array($aRow['controle'],$aRow['libelle'],$aRow['code'],$actif, $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a class='btn btn-success' href='".$this->generateUrl('admin_parametres_codemaharesultat_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a class='btn btn-info' href='".$this->generateUrl('admin_parametres_codemaharesultat_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a class='btn btn-danger' href='".$this->generateUrl('admin_parametres_codemaharesultat_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Carrosserie entities.
     *
     * @Route("/admin/parametres/codemaharesultat/download", name="codemaharesultat_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:CodeMahaResultat')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Controle".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'Controle'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("CONTROLE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("LIBELLE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("CODE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("DETAIL");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("ACTIF");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getControle()->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getCode());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getDetail());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getActif());$col++;
            $ligne++;
        }
    }
}
