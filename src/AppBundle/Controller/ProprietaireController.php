<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Proprietaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Proprietaire controller.
 *
 * @Route("proprietaire")
 */
class ProprietaireController extends Controller
{
    /**
     * Lists all proprietaire entities.
     *
     * @Route("/", name="proprietaire_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('proprietaire/index.html.twig');
    }

    /**
     * Creates a new proprietaire entity.
     *
     * @Route("/new", name="proprietaire_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $proprietaire = new Proprietaire();
        $form = $this->createForm('AppBundle\Form\ProprietaireType', $proprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($proprietaire);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('proprietaire_show', array('id' => $proprietaire->getId()));
        }

        return $this->render('proprietaire/new.html.twig', array(
            'proprietaire' => $proprietaire,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a proprietaire entity.
     *
     * @Route("/{id}", name="proprietaire_show")
     * @Method("GET")
     */
    public function showAction(Proprietaire $proprietaire)
    {
        if (!$proprietaire) {
            throw $this->createNotFoundException("Le propriétaire demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($proprietaire);

        return $this->render('proprietaire/show.html.twig', array(
            'proprietaire' => $proprietaire,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing proprietaire entity.
     *
     * @Route("/{id}/edit", name="proprietaire_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Proprietaire $proprietaire)
    {
        if (!$proprietaire) {
            throw $this->createNotFoundException("Le propriétaire demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($proprietaire);
        $editForm = $this->createForm('AppBundle\Form\ProprietaireType', $proprietaire);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('proprietaire_edit', array('id' => $proprietaire->getId()));
        }

        return $this->render('proprietaire/edit.html.twig', array(
            'proprietaire' => $proprietaire,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Displays a simple form to edit an existing proprietaire entity.
     *
     * @Route("/{id}/modal/edit", name="proprietaire_modal_edit")
     * @Method({"GET", "POST"})
     */
    public function modalEditAction(Request $request, Proprietaire $proprietaire)
    {
        if (!$proprietaire) {
            throw $this->createNotFoundException("Le propriétaire demandé n'est pas disponible.");
        }
        $vehicule_id = $request->get("vehicule_id", 0);
        $deleteForm = $this->createDeleteForm($proprietaire);
        $editForm = $this->createForm('AppBundle\Form\ProprietaireType', $proprietaire);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('vehicule_edit', array('id' => $vehicule_id));
        }

        return $this->render('proprietaire/edit_modal.html.twig', array(
            'proprietaire' => $proprietaire,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'vehicule_id' => $vehicule_id
        ));
    }

    /**
     * Deletes a proprietaire entity.
     *
     * @Route("/{id}", name="proprietaire_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Proprietaire $proprietaire)
    {
        if(!$proprietaire) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($proprietaire->estSupprimable()) {
            $form = $this->createDeleteForm($proprietaire);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($proprietaire);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  ce propriétaire car il est utilisé.');
        }
        return $this->redirectToRoute('proprietaire_index');
    }
    
    /**
     * Deletes a proprietaire entity.
     *
     * @Route("/{id}/delete", name="proprietaire_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(Proprietaire $proprietaire)
    {
        if (!$proprietaire) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($proprietaire->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($proprietaire);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer ce proprietaire car il est utilisé');
        }
        return $this->redirectToRoute('proprietaire_index');
    }

    /**
     * Creates a form to delete a proprietaire entity.
     *
     * @param Proprietaire $proprietaire The proprietaire entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Proprietaire $proprietaire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('proprietaire_delete', array('id' => $proprietaire->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Proprietaire entities.
     *
     * @Route("/admin/parametres/proprietaireajax/liste", name="proprietaireajax")
     * 
     * 
     */
    public function proprietaireAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.numpiece', 'r.nom', 'r.telephone', 'r.adresse');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Proprietaire')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Proprietaire')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:Proprietaire')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['numpiece'],$aRow['nom']." ".$aRow['prenom'],$aRow['telephone'],$aRow['adresse'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('proprietaire_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ENREGISTREMENT')){
                $action .= " <a title='Modifier' class='btn btn-info' href='".$this->generateUrl('proprietaire_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a title='Supprimer' class='btn btn-danger' href='".$this->generateUrl('proprietaire_delete_a', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
                //$action .= " <a title='Enregistrer un véhicule' class='btn btn-succes' href='".$this->generateUrl('vehicule_new')."' ><i class='fa fa-car'> </i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Proprietaire entities.
     *
     * @Route("/admin/parametres/proprietaire/download", name="proprietaire_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Proprietaire')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Proprietaire".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'Proprietaire'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("N° pièce");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Nom");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Prénom");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Téléphone");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Autre Téléphone");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Email");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Adresse");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Type");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getNumpiece());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getNom());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getPrenom());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getTelephone());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getAutreTelephone());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getEmail());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getAdresse());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getPersonneMorale());$col++;
            $ligne++;
        }
    }
}
