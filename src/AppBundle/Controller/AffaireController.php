<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Affaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\AffaireType;

/**
 * Affaire controller.
 *
 * @Route("affaire")
 */
class AffaireController extends Controller
{
    /**
     * Lists all affaire entities.
     *
     * @Route("/", name="affaire_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('affaire/index.html.twig');
    }

    /**
     * Creates a new affaire entity.
     *
     * @Route("/new", name="affaire_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $affaire = new Affaire();
        $form = $this->createCreateForm($affaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $client = $em->getRepository('AppBundle:Client')->find($affaire->getClientid());
            if(!$client){
                throw $this->createNotFoundException("Oopps. Veuillez choisir le client avec le champ autocoplete");
            }
            $bien = $em->getRepository('AppBundle:Bien')->find($affaire->getBienid());
            if(!$bien){
                throw $this->createNotFoundException("Oopps. Veuillez choisir le bien avec le champ autocoplete");
            }
            $affaire->setClient($client);
            $affaire->setBien($bien);
            $affaire->setStatut("EN COURS");
            switch($affaire->getType()){
                case "VENTE":
                    $facture = \AppBundle\Utilities\Utilities::createFacture($affaire, "VENTE", false);break;
                case "LOCATION":
                    $facture = \AppBundle\Utilities\Utilities::createFacture($affaire, $affaire->getTypeLocation(), false);break;
                default: throw $this->createNotFoundException("Oopps. Veuillez utilisez le formulaire");
            }
            $facture->setAffaire($affaire);
            $em->persist($facture);
            $em->persist($affaire);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('affaire_show', array('id' => $affaire->getId()));
        }

        return $this->render('affaire/new.html.twig', array(
            'affaire' => $affaire,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Creates a form to create a Affaire entity.
     *
     * @param Affaire $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Affaire $entity)
    {
        $form = $this->createForm(new AffaireType(), $entity, array(
            'action' => $this->generateUrl('affaire_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));
        if($entity->getBien() != null){
            $form->get("montant")->setData($entity->getBien()->getMontant());
            $form->get("loyer")->setData($entity->getBien()->getLoyer());
        }

        return $form;
    }

    /**
     * Finds and displays a affaire entity.
     *
     * @Route("/{id}", name="affaire_show")
     * @Method("GET")
     */
    public function showAction(Affaire $affaire)
    {
        if(!$affaire) {
            throw $this->createNotFoundException("L'affaire demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($affaire);

        return $this->render('affaire/show.html.twig', array(
            'affaire' => $affaire,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing affaire entity.
     *
     * @Route("/{id}/edit", name="affaire_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Affaire $affaire)
    {
        if(!$affaire) {
            throw $this->createNotFoundException("L'affaire demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($affaire);
        $editForm = $this->createEditForm($affaire);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $client = $em->getRepository('AppBundle:Client')->find($affaire->getClientid());
            if(!$client){
                throw $this->createNotFoundException("Oopps. Veuillez choisir le client avec le champ autocoplete");
            }
            $bien = $em->getRepository('AppBundle:Bien')->find($affaire->getBienid());
            if(!$bien){
                throw $this->createNotFoundException("Oopps. Veuillez choisir le bien avec le champ autocoplete");
            }
            $affaire->setClient($client);
            $affaire->setBien($bien);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('affaire_edit', array('id' => $affaire->getId()));
        }

        return $this->render('affaire/edit.html.twig', array(
            'affaire' => $affaire,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a affaire entity.
     *
     * @Route("/{id}", name="affaire_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Affaire $affaire)
    {
        $form = $this->createDeleteForm($affaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($affaire);
            $em->flush();
        }

        return $this->redirectToRoute('affaire_index');
    }

    /**
     * Creates a form to delete a affaire entity.
     *
     * @param Affaire $affaire The affaire entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Affaire $affaire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('affaire_delete', array('id' => $affaire->getId())))
            ->setMethod('DELETE')->add('submit', 'submit', array('label' => 'SUPPRIMER'))
            ->getForm()
        ;
    }
    
    /**
     * Lists all Affaire entities.
     *
     * @Route("/affairesajax/liste", name="affairesajax")
     * @Method("GET")
     * 
     */
    public function affaireAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.id', 'r.type', 'r.client.nom', 'r.bien.libelle', 'r.bien.proprietaire.nom', 'r.dateDebut', 'r.dateFin', 'r.montant' , 'r.dateCreation');
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
        
        $rResult = $em->getRepository('AppBundle:Affaire')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Affaire')->countRows();
	
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
            $output['aaData'][] = array($i,$aRow['id'],$aRow['type'],$aRow['cnom'],$aRow['libelle'],$aRow['pnom'], $aRow['dateDebut'], $aRow['dateFin'],$aRow['statut'],$aRow['montant']."/".$aRow['loyer'],$aRow['dateCreation'],  $action);
            $i++;
	}
	return new Response(json_encode( $output )); 
    }
    
    private function genererAction($id){
        $action = "<a href='".$this->generateUrl('affaire_show', array('id'=> $id ))."'>Détail</a>";
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
                $action .= "<br/><a href='".$this->generateUrl('affaire_edit', array('id'=> $id ))."'>Modifier</a>";
                $action .= "<br/><a href='".$this->generateUrl('affaire_delete', array('id'=> $id ))."' onclick='return confirm(\"Confirmer la suppression?\")'>Supprimer</a>";
        }
        return $action;
    }
    
    /**
     * Export all Affaire entities.
     *
     * @Route("/export/download", name="affaire_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $incidents = $em->getRepository('AppBundle:Bien')->findAll();
        $date = new \DateTime("now");
        // ask the service for a Excel5
       $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       $phpExcelObject->getProperties()->setCreator("2SInnovation")
           ->setTitle("BIEN_".$date->format('Y-m-d H:i:s'));
       $this->writeRapport($phpExcelObject, $incidents);
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $filename = 'BIEN_'.$date->format('Y_m_d_H_i_s').'.xls';
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
    * Creates a form to edit a Affaire entity.
    *
    * @param Affaire $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Affaire $entity)
    {
        $form = $this->createForm(new AffaireType(), $entity, array(
            'action' => $this->generateUrl('affaire_edit', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));
        if($entity->getClient() != null){
            $form->get("searchclient")->setData($entity->getClient()->getNomComplet());
            $form->get("clientid")->setData($entity->getClient()->getId());
        }
        if($entity->getBien() != null){
            $form->get("searchbien")->setData($entity->getBien()->getLibelle());
            $form->get("bienid")->setData($entity->getBien()->getId());
        }
        return $form;
    }
    
    /**
     * @Route("/ajax/search", name="affaires_ajax")
     */
    public function biensAjaxAction(Request $request){   
        $search = $request->get('search', '');
        $maxRows = $request->get('maxRows', 15);
        $em = $this->getDoctrine()->getManager();
        $clients = $em->getRepository('AppBundle:Affaire')->findAjax($search, $maxRows);
        return new Response(json_encode($clients));
    }
}
