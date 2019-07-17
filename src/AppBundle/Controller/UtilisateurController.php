<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Utilisateur controller.
 *
 * @Route("admin/gestion/utilisateur")
 */
class UtilisateurController extends Controller
{
    /**
     * Lists all utilisateur entities.
     *
     * @Route("/", name="admin_gestion_utilisateur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('utilisateur/index.html.twig');
    }

    /**
     * Creates a new utilisateur entity.
     *
     * @Route("/new", name="admin_gestion_utilisateur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $utilisateur = $userManager->createUser();
        $form = $this->createForm('AppBundle\Form\UserCreateType', $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setPlainPassword($utilisateur->getPassword());
            $utilisateur->setEnabled(true);
            $userManager->updateUser($utilisateur, true);
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_gestion_utilisateur_show', array('id' => $utilisateur->getId()));
        }

        return $this->render('utilisateur/new.html.twig', array(
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a utilisateur entity.
     *
     * @Route("/{id}", name="admin_gestion_utilisateur_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $utilisateur = $userManager->findUserBy(array('id' => $id));
        if (!$utilisateur) {
            throw $this->createNotFoundException("Opération interdite.");
        }
        $deleteForm = $this->createDeleteForm($utilisateur);

        return $this->render('utilisateur/show.html.twig', array(
            'utilisateur' => $utilisateur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing utilisateur entity.
     *
     * @Route("/{id}/edit", name="admin_gestion_utilisateur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $utilisateur = $userManager->findUserBy(array('id' => $id));
        if (!$utilisateur) {
            throw $this->createNotFoundException("Opération interdite.");
        }
        $deleteForm = $this->createDeleteForm($utilisateur);
        $editForm = $this->createForm('AppBundle\Form\UserType', $utilisateur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $groupe = $utilisateur->getGroupe();
            if(!$groupe){
                $em = $this->getDoctrine()->getManager();
                $groupe = $em->getRepository('AppBundle:Group')->findOneByName("ROLE_USER");
                $utilisateur->setGroupe($groupe);
            } 
            $userManager->updateUser($utilisateur, true);
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');

            return $this->redirectToRoute('admin_gestion_utilisateur_edit', array('id' => $utilisateur->getId()));
        }

        return $this->render('utilisateur/edit.html.twig', array(
            'utilisateur' => $utilisateur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Displays a form to edit an existing utilisateur entity.
     *
     * @Route("/{id}/password", name="admin_gestion_utilisateur_password")
     * @Method({"GET", "POST"})
     */
    public function passwordAction(Request $request, $id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $utilisateur = $userManager->findUserBy(array('id' => $id));
        if (!$utilisateur) {
            throw $this->createNotFoundException("Opération interdite.");
        }
        $editForm = $this->createForm('AppBundle\Form\UtilisateurPasswordType', $utilisateur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $utilisateur->setPlainPassword($utilisateur->getPassword());
            $utilisateur->setEnabled(true);
            $userManager->updateUser($utilisateur, true);
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_gestion_utilisateur_show', array('id' => $utilisateur->getId()));
        }

        return $this->render('utilisateur/password.html.twig', array(
            'utilisateur' => $utilisateur,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a utilisateur entity.
     *
     * @Route("/{id}", name="admin_gestion_utilisateur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Utilisateur $utilisateur)
    {
        $form = $this->createDeleteForm($utilisateur);
        $form->handleRequest($request);

        if (!$utilisateur) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($utilisateur->estSupprimable()) {
            $form = $this->createDeleteForm($utilisateur);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($utilisateur);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  cet utilisateur car il est utilisé.');
        }

        return $this->redirectToRoute('admin_gestion_utilisateur_index');
    }
    
    /**
     * Deletes a utilisateur entity.
     *
     * @Route("/{id}/delete", name="admin_gestion_utilisateur_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(Utilisateur $utilisateur)
    {
        if (!$utilisateur) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($utilisateur->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateur);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  cet utilisateur car il est utilisé.');
        }
        return $this->redirectToRoute('admin_gestion_utilisateur_index');
    }

    /**
     * Creates a form to delete a utilisateur entity.
     *
     * @param Utilisateur $utilisateur The utilisateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Utilisateur $utilisateur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_utilisateur_delete', array('id' => $utilisateur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Lists all Utilisateur entities.
     *
     * @Route("/admin/gestion/utilisateurajax/liste", name="utilisateurajax")
     * 
     * 
     */
    public function utilisateurAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.username', 'r.nom', 'r.prenom');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Utilisateur')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Utilisateur')->countRows();
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => count($rResult), "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($aRow['username'],$aRow['nom'].' '.$aRow['prenom'],$aRow['groupe'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-success' href='".$this->generateUrl('admin_gestion_utilisateur_show', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= " <a title='Modifier' class='btn btn-info' href='".$this->generateUrl('admin_gestion_utilisateur_edit', array('id'=> $id ))."'><i class='fa fa-edit' ></i></a>";
                $action .= " <a title='Supprimer' class='btn btn-danger' href='".$this->generateUrl('admin_gestion_utilisateur_delete', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'><i class='fa fa-trash-o'> </i></a>";
                $action .= " <a title='Changer le mot de passe' class='btn btn-info' href='".$this->generateUrl('admin_gestion_utilisateur_password', array('id'=> $id ))."'><i class='fa fa-key' ></i></a>";
        }
        return $action;
    }
    
    /**
     * Export all Utilisateur entities.
     *
     * @Route("/admin/gestion/utilisateur/download", name="utilisateur_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Utilisateur')->findAll();
        $date = new \DateTime("now");
       $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       $phpExcelObject->getProperties()->setCreator("2SInnovation")
           ->setTitle("UTILISATEUR".$date->format('Y-m-d H:i:s'));
       $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'UTILISATEUR'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("LOGIN");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("NOM");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("PRENOM");$col++;
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getUsername());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getNom());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getPrenom());$col++;
            $ligne++;
        }
    }
}
