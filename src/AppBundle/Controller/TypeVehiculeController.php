<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TypeVehicule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Typevehicule controller.
 *
 * @Route("admin/parametres/typevehicule")
 */
class TypeVehiculeController extends Controller
{
    /**
     * Lists all typeVehicule entities.
     *
     * @Route("/", name="admin_parametres_typevehicule_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('typevehicule/index.html.twig');
    }

    /**
     * Creates a new typeVehicule entity.
     *
     * @Route("/new", name="admin_parametres_typevehicule_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeVehicule = new Typevehicule();
        $form = $this->createForm('AppBundle\Form\TypeVehiculeType', $typeVehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $typeVehicule->editerLibelle();
            $em->persist($typeVehicule);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_typevehicule_show', array('id' => $typeVehicule->getId()));
        }

        return $this->render('typevehicule/new.html.twig', array(
            'typeVehicule' => $typeVehicule,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typeVehicule entity.
     *
     * @Route("/{id}", name="admin_parametres_typevehicule_show")
     * @Method("GET")
     */
    public function showAction(TypeVehicule $typeVehicule)
    {
        if (!$typeVehicule) {
            throw $this->createNotFoundException("Le type de véhicule demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($typeVehicule);

        return $this->render('typevehicule/show.html.twig', array(
            'typeVehicule' => $typeVehicule,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typeVehicule entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_typevehicule_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeVehicule $typeVehicule)
    {
        if (!$typeVehicule) {
            throw $this->createNotFoundException("Le type de véhicule demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($typeVehicule);
        $editForm = $this->createForm('AppBundle\Form\TypeVehiculeType', $typeVehicule);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $typeVehicule->editerLibelle();
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_typevehicule_edit', array('id' => $typeVehicule->getId()));
        }

        return $this->render('typevehicule/edit.html.twig', array(
            'typeVehicule' => $typeVehicule,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typeVehicule entity.
     *
     * @Route("/{id}", name="admin_parametres_typevehicule_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeVehicule $typeVehicule)
    {
        if (!$typeVehicule) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($typeVehicule->estSupprimable()) {
            $form = $this->createDeleteForm($typeVehicule);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($typeVehicule);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  ce type de véhicule car il est utilisée.');
        }

        return $this->redirectToRoute('admin_parametres_typevehicule_index');
    }
    
    /**
     * Deletes a typeVehicule entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_typevehicule_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(TypeVehicule $typeVehicule)
    {
        if (!$typeVehicule) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($typeVehicule->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeVehicule);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer ce type de véhicule car il est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_typevehicule_index');
    }

    /**
     * Creates a form to delete a typeVehicule entity.
     *
     * @param TypeVehicule $typeVehicule The typeVehicule entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeVehicule $typeVehicule)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_typevehicule_delete', array('id' => $typeVehicule->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all TypeVehicule entities.
     *
     * @Route("/admin/parametres/typevehiculeajax/liste", name="typevehiculeajax")
     * 
     * 
     */
    public function typevehiculeAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.libelle', 'r.montantRevisite', 'r.montantVisite', 'r.delai');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:TypeVehicule')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:TypeVehicule')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['libelle'],$aRow['montantRevisite'],$aRow['montantVisite'],$aRow['delai'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a class='btn btn-success' href='".$this->generateUrl('admin_parametres_typevehicule_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a class='btn btn-info' href='".$this->generateUrl('admin_parametres_typevehicule_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a class='btn btn-danger' href='".$this->generateUrl('admin_parametres_typevehicule_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Genre entities.
     *
     * @Route("/admin/parametres/typevehicule/download", name="typevehicule_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Genre')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("TYPEVEHICULE".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'TYPEVEHICULE'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("GENRE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("CARROSSERIE");
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("USAGE");
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("MONTANT VISITE");
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("MONTANT REVISITE");
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("DELAI");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getGenre()->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getCarrosserie()->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getUsage()->getLibelle());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getMontantVisite());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getMontantRevisite());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getDelai());$col++;
            $ligne++;
        }
    }
}
