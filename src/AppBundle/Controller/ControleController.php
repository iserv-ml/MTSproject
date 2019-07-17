<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Controle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controle controller.
 *
 * @Route("admin/parametres/controle")
 */
class ControleController extends Controller
{
    /**
     * Lists all controle entities.
     *
     * @Route("/", name="admin_parametres_controle_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('controle/index.html.twig');
    }

    /**
     * Creates a new controle entity.
     *
     * @Route("/new", name="admin_parametres_controle_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $controle = new Controle();
        $form = $this->createForm('AppBundle\Form\ControleType', $controle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($controle);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_controle_show', array('id' => $controle->getId()));
        }

        return $this->render('controle/new.html.twig', array(
            'controle' => $controle,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a controle entity.
     *
     * @Route("/{id}", name="admin_parametres_controle_show")
     * @Method("GET")
     */
    public function showAction(Controle $controle)
    {
        if (!$controle) {
            throw $this->createNotFoundException("Le contrôle demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($controle);

        return $this->render('controle/show.html.twig', array(
            'controle' => $controle,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing controle entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_controle_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Controle $controle)
    {
        if (!$controle) {
            throw $this->createNotFoundException("Le contrôle demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($controle);
        $editForm = $this->createForm('AppBundle\Form\ControleType', $controle);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_controle_edit', array('id' => $controle->getId()));
        }

        return $this->render('controle/edit.html.twig', array(
            'controle' => $controle,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a controle entity.
     *
     * @Route("/{id}", name="admin_parametres_controle_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Controle $controle)
    {
        if(!$controle) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($controle->estSupprimable()) {
            $form = $this->createDeleteForm($controle);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($controle);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  ce controle car il est utilisé.');
        }

        return $this->redirectToRoute('admin_parametres_controle_index');
    }
    
    /**
     * Deletes a controle entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_controle_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(Controle $controle)
    {
        if (!$controle) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($controle->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($controle);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer ce contrôle car il est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_controle_index');
    }

    /**
     * Creates a form to delete a controle entity.
     *
     * @param Controle $controle The controle entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Controle $controle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_controle_delete', array('id' => $controle->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Controle entities.
     *
     * @Route("/admin/parametres/controlesajax/liste", name="controlesajax")
     * 
     * 
     */
    public function controlesAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.libelle', 'r.code', 'r.detail', 'r.actif');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Controle')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Controle')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $actif = $aRow['actif'] == 1 ? "Actif" : "Inactif";
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['libelle'],$aRow['code'],$aRow['type'],$actif, $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_parametres_controle_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a title='Modifier' class='btn btn-info' href='".$this->generateUrl('admin_parametres_controle_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a title='Supprimer' class='btn btn-danger' href='".$this->generateUrl('admin_parametres_controle_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Carrosserie entities.
     *
     * @Route("/admin/parametres/controle/download", name="controle_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Controle')->findAll();
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("LIBELLE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("CODE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("DETAIL");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("ACTIF");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getCode());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getDetail());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getActif());$col++;
            $ligne++;
        }
    }
}
