<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chaine;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Chaine controller.
 *
 * @Route("admin/parametres/chaine")
 */
class ChaineController extends Controller
{
    /**
     * Lists all chaine entities.
     *
     * @Route("/", name="admin_parametres_chaine_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('chaine/index.html.twig');
    }

    /**
     * Creates a new chaine entity.
     *
     * @Route("/new", name="admin_parametres_chaine_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $chaine = new Chaine();
        $form = $this->createForm('AppBundle\Form\ChaineType', $chaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($chaine);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_chaine_show', array('id' => $chaine->getId()));
        }

        return $this->render('chaine/new.html.twig', array(
            'chaine' => $chaine,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a chaine entity.
     *
     * @Route("/{id}", name="admin_parametres_chaine_show")
     * @Method("GET")
     */
    public function showAction(Chaine $chaine)
    {
        if (!$chaine) {
            throw $this->createNotFoundException("La chaine demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($chaine);

        return $this->render('chaine/show.html.twig', array(
            'chaine' => $chaine,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing chaine entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_chaine_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Chaine $chaine)
    {
        if (!$chaine) {
            throw $this->createNotFoundException("La chaine demandée n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($chaine);
        $editForm = $this->createForm('AppBundle\Form\ChaineType', $chaine);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_chaine_edit', array('id' => $chaine->getId()));
        }

        return $this->render('chaine/edit.html.twig', array(
            'chaine' => $chaine,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a chaine entity.
     *
     * @Route("/{id}", name="admin_parametres_chaine_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Chaine $chaine)
    {
        if(!$chaine) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($chaine->estSupprimable()) {
            $form = $this->createDeleteForm($chaine);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($chaine);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  cette chaine car elle est utilisée.');
        }

        return $this->redirectToRoute('admin_parametres_chaine_index');
    }
    
    /**
     * Deletes a chaine entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_chaine_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(Carrosserie $chaine)
    {
        if (!$chaine) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($chaine->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($chaine);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer cette chaine car elle est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_chaine_index');
    }

    /**
     * Creates a form to delete a chaine entity.
     *
     * @param Chaine $chaine The chaine entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Chaine $chaine)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_chaine_delete', array('id' => $chaine->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Chaine entities.
     *
     * @Route("/admin/parametres/chaineajax/liste", name="chaineajax")
     * 
     * 
     */
    public function chaineAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'c.numero','p.numero', 'r.actif', 'r.surRendezVous');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Chaine')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Chaine')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:Chaine')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $actif = $aRow['actif'] ? 'Active' : 'Inactive';
            $rdv = $aRow['surRendezVous'] ? 'Oui' : 'Non';
            $output['aaData'][] = array($aRow['caisse'],$aRow['piste'],$actif,$rdv, $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_parametres_chaine_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CAISSIER_PRINCIPAL')){
                $action .= " <a title='Modifier' class='btn btn-info' href='".$this->generateUrl('admin_parametres_chaine_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a title='Supprimer' class='btn btn-danger' href='".$this->generateUrl('admin_parametres_chaine_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Chaine entities.
     *
     * @Route("/admin/parametres/chaine/download", name="chaine_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Chaine')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Chaine".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'Chaine'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("CAISSE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("PISTE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("ACTIF");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("SUR RENDEZ VOUS");$col++;
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getCaisse()->getNumero());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getPiste()->getNumero());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getActif());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getSurRendezVous());$col++;
            $ligne++;
        }
    }
}
