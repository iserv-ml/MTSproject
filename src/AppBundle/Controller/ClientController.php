<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\ClientType;

/**
 * Client controller.
 *
 * @Route("client")
 */
class ClientController extends Controller
{
    /**
     * Lists all client entities.
     *
     * @Route("/", name="client_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('client/index.html.twig');
    }

    /**
     * Creates a new client entity.
     *
     * @Route("/new", name="client_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $client = new Client();
        $form = $this->createCreateForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('client_show', array('id' => $client->getId()));
        }

        return $this->render('client/new.html.twig', array(
            'client' => $client,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Creates a form to create a Client entity.
     *
     * @param Client $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Client $entity)
    {
        $form = $this->createForm(new ClientType(), $entity, array(
            'action' => $this->generateUrl('client_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Finds and displays a client entity.
     *
     * @Route("/{id}", name="client_show")
     * @Method("GET")
     */
    public function showAction(Client $client)
    {
        if (!$client) {
            throw $this->createNotFoundException("Le client demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($client);

        return $this->render('client/show.html.twig', array(
            'client' => $client,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     * @Route("/{id}/edit", name="client_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Client $client)
    {
        if (!$client) {
            throw $this->createNotFoundException("Le client demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($client);
        $editForm = $this->createEditForm($client);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('client_edit', array('id' => $client->getId()));
        }

        return $this->render('client/edit.html.twig', array(
            'client' => $client,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a client entity.
     *
     * @Route("/{id}", name="client_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Client $client)
    {
        $form = $this->createDeleteForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush();
        }

        return $this->redirectToRoute('client_index');
    }

    /**
     * Creates a form to delete a client entity.
     *
     * @param Client $client The client entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Client $client)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_delete', array('id' => $client->getId())))
            ->setMethod('DELETE')->add('submit', 'submit', array('label' => 'SUPPRIMER'))
            ->getForm()
        ;
    }
    
    /**
     * Lists all Client entities.
     *
     * @Route("/clientsajax/liste", name="clientsajax")
     * @Method("GET")
     * 
     */
    public function clientsAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.id', 'r.numpiece', 'r.nom', 'r.prenom', 'r.telephone' , 'r.adresse', 'r.email', 'r.typePersonne', 'r.dateCreation');
        $start = $request->get('iDisplayStart');
        $start = ($start != NULL && intval($start) > 0) ? intval($start) : 0;
        $end = $request->get('iDisplayLength');
        $end = ($end != NULL && intval($end) > 50) ? intval($end) : 50;
        $sCol = $request->get('iSortCol_0');
        $sCol = ($sCol != NULL && intval($sCol) > 0 && intval($sCol) < 4) ? intval($sCol)-1 : 0;
        $sdir = $request->get('sSortDir_0');
        $sdir = ($sdir!=NULL && $sdir=='asc') ? 'asc' : 'desc';
	$searchTerm = $request->get('sSearch');
        $searchTerm = ($searchTerm!=NULL && $searchTerm!= '') ? $searchTerm : NULL;
        
        $rResult = $em->getRepository('AppBundle:Client')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Client')->countRows();
	
	$output = array(
                "sEcho" => intval($request->get('sEcho')),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iTotal,
		"aaData" => array()
	);
        $i = 1;
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $output['aaData'][] = array($i,$aRow['id'],$aRow['numpiece'],$aRow['nom'],$aRow['prenom'],$aRow['telephone'],$aRow['adresse'],$aRow['email'],$aRow['typePersonne'],$aRow['dateCreation'], $action);
            $i++;
	}
	return new Response(json_encode( $output )); 
    }
    
    private function genererAction($id){
        $action = "<a href='".$this->generateUrl('client_show', array('id'=> $id ))."'>Détail</a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= "<br/><a href='".$this->generateUrl('client_edit', array('id'=> $id ))."'>Modifier</a>";
                $action .= "<br/><a href='".$this->generateUrl('client_delete', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'>Supprimer</a>";
        }
        return $action;
    }
    
    /**
     * Export all TypePiece entities.
     *
     * @Route("/export/download", name="client_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $incidents = $em->getRepository('AppBundle:Client')->findAll();
        $date = new \DateTime("now");
        // ask the service for a Excel5
       $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       $phpExcelObject->getProperties()->setCreator("2SInnovation")
           ->setTitle("CLIENT_".$date->format('Y-m-d H:i:s'));
       $this->writeRapport($phpExcelObject, $incidents);
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $filename = 'CLIENT_'.$date->format('Y_m_d_H_i_s').'.xls';
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;        
    }

    
    private function writeRapport($phpExcelObject, $incidents) {
        $phpExcelObject->setActiveSheetIndex(0);
        $i = 0;
        $objWorksheet = $phpExcelObject->getActiveSheet();
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("ID");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("NOM");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("PRENOM");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("NUMPIECE");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("TYPE PIECE");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("TELEPHONE");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("ADRESSE");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("EMAIL");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("TYPE");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("DERNIERE MODIFICATION");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("CREE PAR");$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("MODIFIE PAR");$i++;$i++;
        $objWorksheet->getCellByColumnAndRow($i, 1)->setValue("DATE DE SUPRESSION");
        $i =2;

        foreach($incidents as $incident){
            $j=0;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getId());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getNom());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getPrenom());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getNumpiece());$j++;
            if($incident->getTypePiece() != NULL)
                $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getTypePiece()->getLibelle());
            $j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getTelephone());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getAdresse());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getEmail());$j++;
            $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($incident->getType());$j++;
            if($incident->getDateCreation()!=null)
                $objWorksheet->getCellByColumnAndRow(3, $i)->setValue($incident->getDateCreation()->format('Y-m-d H:i:s'));
            if($incident->getDateModification()!=null)
                $objWorksheet->getCellByColumnAndRow(4, $i)->setValue($incident->getDateModification()->format('Y-m-d H:i:s'));
            $objWorksheet->getCellByColumnAndRow(5, $i)->setValue($incident->getCreePar());
            $objWorksheet->getCellByColumnAndRow(6, $i)->setValue($incident->getModifierPar());
            if($incident->getDeletedAt()!=null)
                $objWorksheet->getCellByColumnAndRow(7, $i)->setValue($incident->getDeletedAt()->format('Y-m-d H:i:s'));
            
            $i++;
        }
    }
    
    /**
    * Creates a form to edit a Client entity.
    *
    * @param Client $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Client $entity)
    {
        $form = $this->createForm(new ClientType(), $entity, array(
            'action' => $this->generateUrl('client_edit', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));

        return $form;
    }
    
    /**
     * @Route("/ajax/search", name="clients_ajax")
     */
    public function proprietairesAjaxAction(Request $request){   
        $search = $request->get('search', '');
        $maxRows = $request->get('maxRows', 15);
        $em = $this->getDoctrine()->getManager();
        $clients = $em->getRepository('AppBundle:Client')->findAjax($search, $maxRows);
        return new Response(json_encode($clients));
    }
}
