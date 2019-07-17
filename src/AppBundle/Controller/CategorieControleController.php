<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CategorieControle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Categoriecontrole controller.
 *
 * @Route("admin/parametres/categoriecontrole")
 */
class CategorieControleController extends Controller
{
    /**
     * Lists all categorieControle entities.
     *
     * @Route("/", name="admin_parametres_categoriecontrole_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('categoriecontrole/index.html.twig');
    }

    /**
     * Creates a new categorieControle entity.
     *
     * @Route("/new", name="admin_parametres_categoriecontrole_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $categorieControle = new Categoriecontrole();
        $form = $this->createForm('AppBundle\Form\CategorieControleType', $categorieControle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorieControle);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_categoriecontrole_show', array('id' => $categorieControle->getId()));
        }

        return $this->render('categoriecontrole/new.html.twig', array(
            'categorieControle' => $categorieControle,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a categorieControle entity.
     *
     * @Route("/{id}", name="admin_parametres_categoriecontrole_show")
     * @Method("GET")
     */
    public function showAction(CategorieControle $categorieControle)
    {
        if (!$categorieControle) {
            throw $this->createNotFoundException("La catégorie demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($categorieControle);

        return $this->render('categoriecontrole/show.html.twig', array(
            'categorieControle' => $categorieControle,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing categorieControle entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_categoriecontrole_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CategorieControle $categorieControle)
    {
        if (!$categorieControle) {
            throw $this->createNotFoundException("La catégorie demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($categorieControle);
        $editForm = $this->createForm('AppBundle\Form\CategorieControleType', $categorieControle);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_categoriecontrole_edit', array('id' => $categorieControle->getId()));
        }

        return $this->render('categoriecontrole/edit.html.twig', array(
            'categorieControle' => $categorieControle,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a categorieControle entity.
     *
     * @Route("/{id}", name="admin_parametres_categoriecontrole_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CategorieControle $categorieControle)
    {
        if(!$categorieControle) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($categorieControle->estSupprimable()) {
            $form = $this->createDeleteForm($categorieControle);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($categorieControle);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  cette catégorie car elle est utilisée.');
        }

        return $this->redirectToRoute('admin_parametres_categoriecontrole_index');
    }
    
    /**
     * Deletes a categorieControle entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_categoriecontrole_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(CategorieControle $categorieControle)
    {
        if (!$categorieControle) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($categorieControle->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorieControle);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer cette catégorie car elle est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_categoriecontrole_index');
    }

    /**
     * Creates a form to delete a categorieControle entity.
     *
     * @param CategorieControle $categorieControle The categorieControle entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CategorieControle $categorieControle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_categoriecontrole_delete', array('id' => $categorieControle->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all CategorieControle entities.
     *
     * @Route("/admin/parametres/categoriecontroleajax/liste", name="categoriecontroleajax")
     * 
     * 
     */
    public function categoriecontroleAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.libelle', 'r.code');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:CategorieControle')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:CategorieControle')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['libelle'],$aRow['code'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_parametres_categoriecontrole_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a title='Modifier' class='btn btn-info' href='".$this->generateUrl('admin_parametres_categoriecontrole_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a title='Supprimer' class='btn btn-danger' href='".$this->generateUrl('admin_parametres_categoriecontrole_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all CategorieControle entities.
     *
     * @Route("/admin/parametres/categoriecontrole/download", name="categoriecontrole_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:CategorieControle')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("CategorieControle".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'CategorieControle'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("CODE");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getCode());$col++;
            $ligne++;
        }
    }
}
