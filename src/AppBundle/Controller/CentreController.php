<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Centre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Centre controller.
 *
 * @Route("admin/gestion/centre")
 */
class CentreController extends Controller
{
    /**
     * Lists all centre entities.
     *
     * @Route("/", name="admin_gestion_centre_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $centres = $em->getRepository('AppBundle:Centre')->findAll();
        $boutton = ($centres == null || count($centres) == 0) ? 0: 1;

        return $this->render('centre/index.html.twig', array(
            'centres' => $centres,
            'boutton' => $boutton,
        ));
    }
    
    /**
     * Confirmation de l'initialisation des caisses.
     *
     * @Route("/ouverture/confirmer", name="centre_ouverture_confirmer")
     * @Method("GET")
     */
    public function ouvertureconfirmerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActivesToutes();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre){
            throw $this->createNotFoundException("Cette opération est interdite!");
        }
        return $this->render('centre/ouverture.confirmer.html.twig', array(
            'chaines' => $chaines,'centre' => $centre,
        ));
    }
    
    /**
     * Confirmation de la fermeture du centre.
     *
     * @Route("/fermeture/confirmer", name="centre_fermeture_confirmer")
     * @Method("GET")
     */
    public function fermetureconfirmerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActivesToutes();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre){
            throw $this->createNotFoundException("Cette opération est interdite!");
        }
        return $this->render('centre/fermeture.confirmer.html.twig', array(
            'chaines' => $chaines, 'centre'=>$centre,
        ));
    }
    
    /**
     * Lists all chaine entities.
     *
     * @Route("/ouverture", name="admin_gestion_centre_ouverture")
     * @Method("GET")
     */
    public function ouvertureAction()
    {
        $em = $this->getDoctrine()->getManager();
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActivesToutes();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre){
            throw $this->createNotFoundException("Cette opération est interdite!");
        }
        return $this->render('centre/ouverture.html.twig', array(
            'chaines' => $chaines,
            'centre' => $centre,
        ));
    }

    /**
     * Creates a new centre entity.
     *
     * @Route("/new", name="admin_gestion_centre_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $centre = new Centre();
        $form = $this->createForm('AppBundle\Form\CentreType', $centre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $centreExistant = $em->getRepository('AppBundle:Centre')->recuperer();
            if($centreExistant){
                throw $this->createNotFoundException("Cette opération est interdite!");
            }
            $em->persist($centre);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_gestion_centre_show', array('id' => $centre->getId()));
        }

        return $this->render('centre/new.html.twig', array(
            'centre' => $centre,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a centre entity.
     *
     * @Route("/admin/gestion/centre/voir", name="admin_gestion_centre_show")
     * @Method("GET")
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre){
            throw $this->createNotFoundException("Cette opération est interdite!");
        }
        return $this->render('centre/show.html.twig', array(
            'centre' => $centre,
        ));
    }

    /**
     * Displays a form to edit an existing centre entity.
     *
     * @Route("/admin/gestion/centre/modifier", name="admin_gestion_centre_modifier")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre){
            throw $this->createNotFoundException("Cette opération est interdite!");
        }
        $editForm = $this->createForm('AppBundle\Form\CentreType', $centre);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $mahaOffline = $centre->getMaha() ? false : true;
            $controlesOffline = $em->getRepository('AppBundle:Controle')->recupererOffline();
            foreach($controlesOffline as $controle){
                $controle->setActif($mahaOffline);
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_gestion_centre_modifier');
        }

        return $this->render('centre/edit.html.twig', array(
            'centre' => $centre,
            'edit_form' => $editForm->createView(),
        ));
    }
    
    /**
     * Displays a form to edit les cartes for an existing centre entity.
     *
     * @Route("/admin/gestion/centre/carte", name="admin_gestion_centre_carte")
     * @Method({"GET", "POST"})
     */
    public function carteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre){
            throw $this->createNotFoundException("Cette opération est interdite!");
        }
        if($centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est déjà ouvert!');
            return $this->redirectToRoute('admin_gestion_centre_ouverture');
        }
        $editForm = $this->createForm('AppBundle\Form\CentreCarteType', $centre);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            $chaines = $em->getRepository('AppBundle:Chaine')->chainesActivesToutes();
            return $this->redirectToRoute('admin_gestion_centre_ouverture', array(
                'centre' => $centre,
                'chaines' => $chaines,
            ));
        }

        return $this->render('centre/edit.html.twig', array(
            'centre' => $centre,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a centre entity.
     *
     * @Route("/{id}", name="admin_gestion_centre_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Centre $centre)
    {
        $form = $this->createDeleteForm($centre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($centre);
            $em->flush();
        }

        return $this->redirectToRoute('admin_gestion_centre_index');
    }

    /**
     * Creates a form to delete a centre entity.
     *
     * @param Centre $centre The centre entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Centre $centre)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_centre_delete', array('id' => $centre->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Ouvre le centre.
     *
     * @Route("/ouvrir", name="admin_gestion_centre_ouvrir")
     * @Method({"GET", "POST"})
     */
    public function ouvrirAction()
    {
        $em = $this->getDoctrine()->getManager();
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActivesToutes();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre){
            throw $this->createNotFoundException("Cette opération est interdite!");
        }
        if(!$centre->getEtat()){
            $centre->setEtat(true);
            $centre->setCarteViergeOuverture($centre->getCarteVierge());
            $visitesEnCours = $em->getRepository('AppBundle:Visite')->recupererVisitesEncours();
            if($visitesEnCours != null and count($visitesEnCours) > 0){
                foreach($visitesEnCours as $visite){
                    $delai = new \DateTime();
                    $delai->sub(new \DateInterval('P'.$visite->getVehicule()->getTypeVehicule()->getDelai().'D'));
                    //$ecart = \DateTime::diff($visite->getQuittance()->getDateEncaissement(), $delai, false);
                    $ecart = $visite->getQuittance()->getDateEncaissement()->diff($delai, false);
                    if($ecart->format('%R%a') > 0){
                        $em->getRepository('AppBundle:Visite')->annuler($visite->getId());
                    }
                }
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Le centre est maintenant ouvert.');
        }else{
            $this->get('session')->getFlashBag()->add('notice', 'Le centre est déjà ouvert.');
        }
        
         return $this->render('centre/ouverture.html.twig', array(
            'chaines' => $chaines,
            'centre' => $centre,
        ));
    }
    
    /**
     * Ferme le centre.
     *
     * @Route("/fermer", name="admin_gestion_centre_fermer")
     * @Method({"GET", "POST"})
     */
    public function fermerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActivesToutes();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre){
            throw $this->createNotFoundException("Cette opération est interdite!");
        }
        if($centre->getEtat()){
            $caisses = true;
            if(count($chaines) > 0){
                foreach($chaines as $chaine){
                    if($chaine->getCaisse()->getOuvert()){
                        $caisses = false;
                        break;
                    }
                    $files = \glob($chaine->getPiste()->getRepertoire()."CG".DIRECTORY_SEPARATOR."*");
                    if($files != null && count($files) > 0){
                        foreach($files as $file) { 
                            if(\is_file($file)){  
                                try{
                                    \unlink($file);
                                }catch(Exception $e){
                                    
                                }
                            }
                        } 
                    }
                    $sortie = $centre->encaisser($chaine->getCaisse());
                    $chaine->getCaisse()->cloturer();
                    $em->persist($sortie);
                }
            }
            if($caisses){
                $centre->setEtat(false);
                $centre->setCarteViergeOuverture(0);
                $em->getRepository('AppBundle:Visite')->annulerVisitesEnAttentes();
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Le centre est maintenant fermé.');
            }else{
                $this->get('session')->getFlashBag()->add('error', 'Impossible de fermer le centre car des caisses sont encore ouvertes.');
            }
                
            
        }else{
            $this->get('session')->getFlashBag()->add('notice', 'Le centre est déjà fermé.');
        }
        
         return $this->render('centre/ouverture.html.twig', array(
            'chaines' => $chaines,
            'centre' => $centre,
        ));
    }
    
    /**
     * Rembourser une quittance par la caisse principale.
     *
     * @Route("/{id}/rembourser/principal", name="quittance_rembourser_principal")
     * @Method({"GET", "POST"})
     */
    public function rembourserprincipalAction(\AppBundle\Entity\Quittance $quittance)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$centre || !$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('caisse_quittance_index');
        }
        switch($quittance->remboursableOu()){
            case -2: $this->get('session')->getFlashBag()->add('error', "La quittance a déjà été remboursée");break;
            case -1: $this->get('session')->getFlashBag()->add('error', "La visite a déjà été effectuée");break;
            case 0: $this->get('session')->getFlashBag()->add('error', "Cette quittance n'a pas été encaissée!");break;
            case 1: $this->get('session')->getFlashBag()->add('error', "Le client doit passer à la caisse N°".$quittance->getVisite()->getChaine()->getCaisse()->getNumero()." pour se faire rembourser!");break;
            case 2: if($quittance->controleSolde($centre->getSolde())){
                        $sortie = $centre->rembourser($quittance, $user->getUsername());
                        if($quittance->getVisite()->getRevisite()){
                            $montantVisite = 0;
                            $nbVisite = 0;
                            $montantRevisite = -$quittance->getTtc();
                            $nbRevisite = -1;
                        }else{
                            $montantVisite = -$quittance->getTtc();
                            $nbVisite = -1;
                            $montantRevisite = 0;
                            $nbRevisite = 0;
                        }
                        $etat = new \AppBundle\Entity\EtatJournalier(\date('d-m-Y'), $montantVisite, $montantRevisite, $nbVisite, $nbRevisite, $quittance->getTypeVehicule(), $quittance->getUsage(), $quittance->getGenre(), $quittance->getCarrosserie(), $quittance->getCaisse(), $quittance->getImmatriculation(),  $quittance->getNumero(), $centre->getCode(), $quittance->getEncaissePar(), "Remboursement", $user->getUsername());
                        $em->persist($etat);
                        $em->persist($sortie);
                        $quittance->getVisite()->getVehicule()->setVerrou(false);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('notice', 'La quittance a été remboursée.');
                    }else{
                        $this->get('session')->getFlashBag()->add('error', "Le solde disponible ne permet pas de rembourser cette quittance!");
                    }
                break;
        }
        return $this->redirectToRoute('caisse_quittance_index');
    }
    
    /**
     * Rembourser une quittance.
     *
     * @Route("/{id}/rembourser/principal/confirmer", name="quittance_remboursement_principal_confirmer")
     * @Method({"GET", "POST"})
     */
    public function rembourserconfirmerAction(\AppBundle\Entity\Quittance $quittance)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre || !$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('caisse_quittance_index');
        }
        $today = new \DateTime();
        if($quittance->getPaye() && $quittance->getDateEncaissement()->format('Y-m-d') == $today->format('Y-m-d')){
            $style = "color:red";
            $message = "Remboursement à la caisse N°".$quittance->getVisite()->getChaine()->getCaisse()->getNumero()."!";
        }else{
            $style="";
            $message = "Cliquer sur Rembourser pour confirmer le remboursement.";
        }
        return $this->render('quittance/rembourser.confirmer.html.twig', array(
            'quittance' => $quittance,
            'style' => $style,
            'message' => $message,
        ));
    }
    
    /**
     * Recap de la centralisation des etats.
     *
     * @Route("/envoyer/etat", name="centre_envoyer_etat")
     * @Method("GET")
     */
    public function envoyeretatAction()
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre){
            return new \Symfony\Component\HttpFoundation\Response("Opération interdite."); 
        }
        
        $etats = $em->getRepository('AppBundle:EtatJournalier')->recupererEtat();
        $today = date("Y-m-dHis"); 
        $fichier = 'etat_'.$today.'.csv';
        $ids = array();
        $response ="";
        
        try{
            $fp = fopen($centre->getRepertoire().$fichier, 'w');
            $nbr = count($etats);
            if($nbr > 0){
                foreach($etats as $etat){
                    fputcsv($fp, $etat);
                    $ids[] = $etat['id'];
                }
                fclose($fp);
                $update_list =  '\'' . implode( '\', \'', $ids ) . '\'';
                $sql = 'UPDATE etat_journalier SET synchro = 1, date_synchro = now() WHERE id IN ('.$update_list.')';
                $update = $em->getRepository('AppBundle:EtatJournalier')->marquerExport($sql);
                
                //transfert ftp
                // connect to FTP server

                $ftp_conn = ftp_connect($centre->getFtpServer()) or die("Could not connect to $ftp_server");
                //login to FTP server
                $login = ftp_login($ftp_conn, $centre->getFtpUsername(), $centre->getFtpUserpass());
                ftp_pasv($ftp_conn, true);

                // upload file
                if (ftp_put($ftp_conn, $fichier, $centre->getRepertoire().$fichier, FTP_BINARY)){
                    $response = "ENVOI TERMINE DE : ".$nbr." LIGNES dans le fichier ". $centre->getRepertoire().$fichier."<br/> ";

                }else{
                    $response = "Error uploading". $centre->getRepertoire().$fichier;
                }
                // close connection
                ftp_close($ftp_conn);
                //on supprime le fichier ou pas?  On va plutot le déplacer dans un dossier

            }else{
                $response = "Pas d'enregistrement à envoyer";
            }
        }catch(Exception $e){
            //print_r($e);
            $response = "Une erreur s'est produite";
        }
                return new \Symfony\Component\HttpFoundation\Response($response); 
        }
        
        /**
     * Lists all certificat entities pour le chef de centre.
     *
     * @Route("/chefcentre/certificat", name="centre_certificat")
     * @Method({"GET", "POST"})
     */
    public function certificatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /*$date = new \DateTime("now");
        $debut = \DateTime::createFromFormat( 'd-m-Y', $request->get('debut', $date->format('d-m-Y')));
        $debut->setTime(0, 0);
        $fin = \DateTime::createFromFormat( 'd-m-Y',$request->get('fin', $date->format('d-m-Y')));
        $fin->setTime(0, 0);
        $fin->add(new \DateInterval('P1D'));
        $lots = $em->getRepository('AppBundle:Lot')->rechercher($debut, $fin);
         * */
        $lots = $em->getRepository('AppBundle:Lot')->nonEpuise();
        $resultats = array();
        foreach($lots as $lot){
            $annule = $em->getRepository('AppBundle:Certificat')->findAnnuler($lot['id']);
            $attribue = $em->getRepository('AppBundle:Certificat')->findAttribue($lot['id']);
            $disponible = $lot['quantite'] - $annule - $attribue;
            $action = $this->genererActionTwig($lot['id']);
            $resultats[] = array("id"=>$lot['id'], "serie"=>'('.$lot['annee'].')'.$lot['serie'], "quantite"=>$lot['quantite'], "dateAffectationCentre"=>$lot['dateAffectationCentre'], "attributeur"=>$lot["attributeur"], "annule"=>$annule, "attribue"=>$attribue, "disponible"=>$disponible, "action"=>$action);
        }
        $user = $this->container->get('security.context')->getToken()->getUser();
        return $this->render('certificat/centre.html.twig', array(
            'nom' => $user->getNomComplet(), 'id' => $user->getId(), "resultats"=>$resultats
        ));
        
        /*return $this->render('certificat/centre.html.twig', array(
            'nom' => $user->getNomComplet(), 'id' => $user->getId(), "resultats"=>$resultats,"debut"=>$debut->format('d-m-Y'), "fin"=>$fin->format('d-m-Y')
        ));*/
    }
    
    /**
     * Lists all Modele entities.
     *
     * @Route("/chefcentre/certificatAjax/liste", name="centrecertificatajax")
     * 
     * 
     */
    public function certificatCentreAjaxAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.serie', 'r.attributeur', 'r.cntrolleur');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Lot')->findAllCentreAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Lot')->countRows($user->getId());
        $iTotalFiltre = $em->getRepository('AppBundle:Lot')->countRowsFiltre($searchTerm, $user->getId());
	
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id']);
            $annule = $em->getRepository('AppBundle:Certificat')->findAnnuler($aRow['id']);
            $attribue = $em->getRepository('AppBundle:Certificat')->findAttribue($aRow['id']);
            $disponible = $aRow['quantite'] - $annule - $attribue;
            if($disponible > 0){
                $output['aaData'][] = array('('.$aRow['annee'].')_'.$aRow['serie'],$aRow['quantite'], $disponible, $attribue,$annule, $aRow['dateAffectationCentre'], $aRow['attributeur'], $action);
            }else{
                $iTotal--;
                $iTotalFiltre--;
            }
	}
        $output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id){
        $action = "<a title='Détail' class='btn btn-info' href='".$this->generateUrl('secretaire_certificat_lot_index', array('id'=> $id ))."'><i class='fa fa-plus'></i></a>";
        return $action;
    }
    
    private function genererActionTwig($id){
        $action = "<a title='Détail' class='btn btn-info' href='".$this->generateUrl('secretaire_certificat_lot_index', array('id'=> $id ))."'><i class='fa fa-plus'></i></a>";
        return $action;
    }
    
    
    /**
     * Lists all Modele entities.
     *
     * @Route("/chefcentre/certificatagentAjax/liste", name="centrecertificatagentajax")
     * 
     * 
     */
    public function certificatAgentCentreAjaxAction(Request $request)
    {
        $lotid = intval($request->get("lotid", 0));
        $user = $this->container->get('security.context')->getToken()->getUser();
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.serie', 'r.attribuePar', 'c.nom');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Certificat')->findAllLotAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm, $lotid);
	$iTotal = $em->getRepository('AppBundle:Certificat')->countRows($lotid);
        $iTotalFiltre = $em->getRepository('AppBundle:Certificat')->countRowsFiltre($searchTerm, $lotid);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererCertificatAction($aRow['id']);
            $statut = $this->genererStatut($aRow['annule'], $aRow['utilise']);
            $output['aaData'][] = array(\AppBundle\Utilities\Utilities::formaterSerie($aRow['serie']),$aRow['attribuePar'], $aRow['nom']." ".$aRow['prenom'], $aRow['dateAttribution'],$statut, $aRow['immatriculation'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererCertificatAction($id){
        $action = "<a title='Annuler' class='btn btn-edit' href='#' onclick='annulerCertificat(".$id.");return false;'><i class='fa fa-ban'></i></a>";
        return $action;
    }
    
    private function genererStatut($annule, $utilise){
        if($utilise) return "Utilisé";
        if($annule) return "Annulé";
        return "Actif";
    }
    
}
