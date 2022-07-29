<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Caisse;

/**
 * Statistique controller.
 *
 * @Route("admin/gestion/centre/statistique")
 */
class StatistiqueController extends Controller
{
    /**
     * Lists all sortieCaisse entities.
     *
     * @Route("/", name="admin_gestion_centre_statistiques_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('statistique/caisse/index.html.twig');
    }
    
    /**
     * Accueil etat journalier des caisses.
     *
     * @Route("/", name="admin_gestion_centre_statistique_caisse_index")
     * @Method("GET")
     */
    public function caisseindexAction()
    {
        return $this->render('statistique/caisse/index.html.twig');
    }
    
    /**
     * Accueil sats avancées.
     *
     * @Route("/detail", name="admin_gestion_centre_statistique_detail_index")
     * @Method({"GET", "POST"})
     */
    public function detailindexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if (!$centre) {
            throw $this->createNotFoundException("Opération interdite!");
        }
        $date = new \DateTime("now");
        $debut = \DateTime::createFromFormat( 'd-m-Y', $request->get('debut', $date->format('d-m-Y')));
        $debut->setTime(0, 0);
        $fin = \DateTime::createFromFormat( 'd-m-Y',$request->get('fin', $date->format('d-m-Y')));
        $fin->setTime(0, 0);
        $fin->add(new \DateInterval('P1D'));
        $quittances = $em->getRepository('AppBundle:Quittance')->recupererEncaisserParPeriode($debut, $fin); 
        $fin->sub (new \DateInterval('P1D'));

        return $this->render('statistique/detail/index.html.twig', array(
            'quittances' => $quittances,'debut' => $debut->format('d-m-Y'), 'fin' => $fin->format('d-m-Y'), 'libelle' =>$centre->getLibelle(),
        ));
    }
    
    /**
     * Lists all Caisse entities.
     *
     * @Route("/admin/gestion/centre/statistique/caisse/liste", name="caissepouretatajax")
     * 
     * 
     */
    public function caissepouretatAjaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'r.numero', 'r.actif');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Caisse')->trouverPourEtat($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Caisse')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:Caisse')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $ouvert = ($aRow->getOuvert() == 1) ? "O" : "F";
            $action = $this->genererCaisseAction($aRow->getId(), $aRow->getActif());
            $affectation = $aRow->getAffectationActive();
            $caissier = ($affectation) ? $affectation->getAgent()->getNom()." ".$affectation->getAgent()->getPrenom() : "";
            $output['aaData'][] = array($aRow->getNumero(), $caissier, $aRow->getSoldeInitial(), $ouvert, $aRow->getSolde(),$action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererCaisseAction($id, $etat){
        $action = "";
        if($etat){
            $action = "<a title='Etat journalier' class='btn btn-success' href='".$this->generateUrl('centre_gestion_statistiques_caisse_etat', array('id'=> $id ))."'><i class='fa fa-search-plus'></i></a>";        
        }
        return $action;
    }
    
    /**
     * Finds and displays a proprietaire entity.
     *
     * @Route("/{id}", name="centre_gestion_statistiques_caisse_etat")
     * @Method({"GET", "POST"})
     */
    public function etatAction(Caisse $caisse, Request $request)
    {
        if (!$caisse) {
            throw $this->createNotFoundException("La caisse demandée n'est pas disponible.");
        }
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime("now");
        $debut = \DateTime::createFromFormat( 'd-m-Y', $request->get('debut', $date->format('d-m-Y')));
        $debut->setTime(0, 0);
        $fin = \DateTime::createFromFormat( 'd-m-Y',$request->get('fin', $date->format('d-m-Y')));
        $fin->setTime(0, 0);
        $fin->add(new \DateInterval('P1D'));
        $resultat = array();
        $i = 0;
        $nv = 0;
        $nr = 0;
        $mv = 0;
        $mr = 0;
        $anaser = 0;
        $genres = $em->getRepository('AppBundle:Genre')->findAll();       
        
        if(count($genres)>0){
            foreach($genres as $genre){
                $ligne = array();
                $ligne[0] = $genre->getCode();
                $etat = $em->getRepository('AppBundle:EtatJournalier')->etatJournalier($genre->getCode(), $debut, $fin, $caisse->getNumero());
                if($etat && count($etat)>0){
                    $ligne[1] = \intval($etat[0][1]);
                    $ligne[2] = \intval($etat[0][2]);//$visites['nbRevisite'];
                    $ligne[3] = \intval($etat[0][3])-\intval($etat[0][5]);//$visites['mVisite'];
                    $ligne[4] = \intval($etat[0][4]);//$visites['mVisite'];
                    $ligne[5] = \intval($etat[0][5]);//$visites['anaser'];
                    $ligne[6] = \intval($etat[0][3])+\intval($etat[0][4]);//$visites['mVisite']+$visites['mRevisite']+$visites['anaser'];
                }else{
                    $ligne[1] = 0;
                    $ligne[2] = 0;
                    $ligne[3] = 0;
                    $ligne[4] = 0;
                    $ligne[5] = 0;
                    $ligne[6] = 0;
                }
                $resultat[] = $ligne;
            $nv += $ligne[1];
                $nr += $ligne[2];
                $mv += $ligne[3];
                $mr += $ligne[4];
                $anaser += $ligne[5];
            } 
        } 
       $fin->sub (new \DateInterval('P1D'));
       $resultat[] = ['TOTAL', $nv, $nr, $mv, $mr, $anaser, $mv+$mr+$anaser];
       return $this->render('statistique/caisse/etat.html.twig', array(
            'resultats' => $resultat,'caisse' => $caisse,'debut' => $debut->format('d-m-Y'), 'fin' => $fin->format('d-m-Y'),
        ));
    }
    
    /**
     * Finds and displays a proprietaire entity.
     *
     * @Route("/caisse/etat", name="centre_gestion_statistiques_caissier_etat")
     * @Method({"GET", "POST"})
     */
    public function caissierAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $affectation = $em->getRepository('AppBundle:Affectation')->derniereAffectation($user->getId());
        if (!$affectation) {
            $this->get('session')->getFlashBag()->add('error', "Vous n'êtes affecté à aucune caisse. Contacter l'administrateur.");
            return $this->redirectToRoute('homepage');
        }
        $date = new \DateTime("now");
        $date->setTime(0, 0);
        //$date =\DateTime::createFromFormat( 'd-m-Y', '01-06-2022'); //Pour tester au cas ou pas de données du jours en cours
        $debut = \DateTime::createFromFormat( 'd-m-Y', $request->get('debut', $date->format('d-m-Y')));
        $debut->setTime(0, 0);
        $fin = \DateTime::createFromFormat( 'd-m-Y',$request->get('fin', $date->format('d-m-Y')));
        $fin->setTime(0, 0);
        $fin->add(new \DateInterval('P1D'));
        $affectations = $em->getRepository('AppBundle:Affectation')->trouverParNumeroCaisseDate($affectation->getCaisse()->getNumero(), $date);
        $resultat = array();
        foreach($affectations as $atraite){
            $username = $atraite->getAgent()->getUsername();
            $nom = $atraite->getAgent()->getNomComplet();
            $genres = $em->getRepository('AppBundle:Genre')->findAll();
            $i = 0;
            $nv = 0;
            $nr = 0;
            $mv = 0;
            $mr = 0;
            $anaser = 0;
            if(count($genres)>0){
                $resultat[$username]=array();
                $resultat[$username][0] = $username;
                $resultat[$username][2] = $atraite->getActif() ? "En cours" : $atraite->getDateModification();
                $resultat[$username][3] = $atraite->getDate();
                $resultat[$username][5] = $nom;
                foreach($genres as $genre){
                    $ligne = array();
                    $ligne[0] = $genre->getCode();
                    $etat = $em->getRepository('AppBundle:EtatJournalier')->etatJournalierAgent($genre->getCode(), $debut, $fin, $affectation->getCaisse()->getNumero(), $username);
                    if($etat && count($etat)>0){
                        $ligne[1] = \intval($etat[0][1]);
                        $ligne[2] = \intval($etat[0][2]);//$visites['nbRevisite'];
                        $ligne[3] = \intval($etat[0][3])-\intval($etat[0][5]);//$visites['mVisite'];
                        $ligne[4] = \intval($etat[0][4]);//$visites['mVisite'];
                        $ligne[5] = \intval($etat[0][5]);//$visites['anaser'];
                        $ligne[6] = \intval($etat[0][3])+\intval($etat[0][4]);//$visites['mVisite']+$visites['mRevisite']+$visites['anaser'];
                    }else{
                        $ligne[1] = 0;
                        $ligne[2] = 0;
                        $ligne[3] = 0;
                        $ligne[4] = 0;
                        $ligne[5] = 0;
                        $ligne[6] = 0;
                    }
                    $resultat[$username][1][] = $ligne;
                    $nv += $ligne[1];
                    $nr += $ligne[2];
                    $mv += $ligne[3];
                    $mr += $ligne[4];
                    $anaser += $ligne[5];
                    
                }
                $resultat[$username][4] = ['TOTAL', $nv, $nr, $mv, $mr, $anaser, $mv+$mr+$anaser];
            }
        }
        //print_r($resultat);exit;
        $fin->sub (new \DateInterval('P1D'));
        $total = array();
        //$total = ['TOTAL', $nv, $nr, $mv, $mr, $anaser, $mv+$mr+$anaser]; 
        return $this->render('statistique/caisse/caissier.html.twig', array(
            'resultats' => $resultat,'caisse' => $affectation->getCaisse(), 'debut' => $debut->format('d-m-Y'), 'fin' => $fin->format('d-m-Y'),'total'=>$total,
        ));
    }
    
    /**
     * Visites échouées.
     *
     * @Route("/echec/rapport", name="admin_gestion_centre_statistique_echec_index")
     * @Method({"GET", "POST"})
     */
    public function echecindexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if (!$centre) {
            throw $this->createNotFoundException("Opération interdite!");
        }
        $date = new \DateTime("now");
        $debut = \DateTime::createFromFormat( 'd-m-Y', $request->get('debut', $date->format('d-m-Y')));
        $debut->setTime(0, 0);
        $fin = \DateTime::createFromFormat( 'd-m-Y',$request->get('fin', $date->format('d-m-Y')));
        $fin->setTime(0, 0);
        $fin->add(new \DateInterval('P1D'));
        $visites = $em->getRepository('AppBundle:Visite')->recupererEchecParPeriode($debut, $fin); 
        $fin->sub (new \DateInterval('P1D'));
        return $this->render('statistique/detail/echec.html.twig', array(
            'visites' => $visites,'debut' => $debut->format('d-m-Y'), 'fin' => $fin->format('d-m-Y'), 'libelle' =>$centre->getLibelle(),
        ));
    }
    
    /**
     * Visites Réussies.
     *
     * @Route("/reussite/rapport", name="admin_gestion_centre_statistique_reussite_index")
     * @Method({"GET", "POST"})
     */
    public function reussiteindexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if (!$centre) {
            throw $this->createNotFoundException("Opération interdite!");
        }
        $date = new \DateTime("now");
        $debut = \DateTime::createFromFormat( 'd-m-Y', $request->get('debut', $date->format('d-m-Y')));
        $debut->setTime(0, 0);
        $fin = \DateTime::createFromFormat( 'd-m-Y',$request->get('fin', $date->format('d-m-Y')));
        $fin->setTime(0, 0);
        $fin->add(new \DateInterval('P1D'));
        $visites = $em->getRepository('AppBundle:Visite')->recupererReussiteParPeriode($debut, $fin); 
        $fin->sub (new \DateInterval('P1D'));
        return $this->render('statistique/detail/reussite.html.twig', array(
            'visites' => $visites,'debut' => $debut->format('d-m-Y'), 'fin' => $fin->format('d-m-Y'), 'libelle' =>$centre->getLibelle(),
        ));
    }
    
    /**
     * Rapport du centre
     *
     * @Route("/centre/rapport", name="admin_gestion_centre_statistique_centre_index")
     * @Method({"GET", "POST"})
     */
    public function centrerapportindexAction(Request $request)
    {
        \setlocale(LC_TIME, "fr_FR", "French");
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if (!$centre) {
            throw $this->createNotFoundException("Opération interdite!");
        }
        $date = new \DateTime("now");
        $debut = \DateTime::createFromFormat( 'd-m-Y', $request->get('debut', $date->format('d-m-Y')));
        $debut->setTime(0, 0);
        $fin = \DateTime::createFromFormat( 'd-m-Y',$request->get('fin', $date->format('d-m-Y')));
        $fin->setTime(0, 0);
        $fin->add(new \DateInterval('P1D'));
        $visites = $em->getRepository('AppBundle:Visite')->recupererEchecParPeriode($debut, $fin); 
        $fin->sub (new \DateInterval('P1D'));
        return $this->render('statistique/detail/centre.html.twig', array(
            'visites' => $visites, 'debut' => $debut->format('M-Y'), 'mois' => \mb_strtoupper(\utf8_encode(\strftime("%B %Y", $debut->getTimestamp()))), 'fin' => $fin->format('M-Y'), 'libelle' =>$centre->getLibelle(),
        ));
    }
    
    /**
     * Rapport controleurs.
     *
     * @Route("/controleur/rapport", name="admin_gestion_centre_statistique_controleur_index")
     * @Method({"GET", "POST"})
     */
    public function controleurindexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if (!$centre) {
            throw $this->createNotFoundException("Opération interdite!");
        }
        $date = new \DateTime("now");
        $debut = \DateTime::createFromFormat( 'd-m-Y', $request->get('debut', $date->format('d-m-Y')));
        $debut->setTime(0, 0);
        $fin = \DateTime::createFromFormat( 'd-m-Y',$request->get('fin', $date->format('d-m-Y')));
        $fin->setTime(0, 0);
        $fin->add(new \DateInterval('P1D'));
        $liste = array();
        $controleurs = $em->getRepository('AppBundle:Visite')->recupererControleurPeriode($debut, $fin);
        foreach($controleurs as $controleur){
            $nbvisite = 0;
            $nbrevisite = 0;
            $nbrevisiteOk = 0;
            $visites = $em->getRepository('AppBundle:Visite')->recupererParPeriodeControlleur($debut, $fin, $controleur["controlleur"]);
            foreach($visites as $visite){
                switch($visite->getTypeVisite()){
                    case "Visite" : 
                        if($visite->certificatDelivre())
                            $nbvisite++;
                    break;
                    case "Revisite" : $nbrevisite++;
                        if($visite->estSucces()){
                            $nbrevisiteOk++;
                        }
                }
            }
            if(($nbvisite + $nbrevisite)>0)
                $liste[] = array('controleur'=>$controleur["controlleur"],'visite'=>$nbvisite, 'revisite'=>$nbrevisite, 'revisiteok'=>$nbrevisiteOk);
        }
        //print_r($liste);exit;
        $fin->sub (new \DateInterval('P1D'));
        return $this->render('statistique/detail/controleur.html.twig', array(
            'liste' => $liste,'debut' => $debut->format('d-m-Y'), 'fin' => $fin->format('d-m-Y'), 'libelle' =>$centre->getLibelle(),
        ));
    }
}
