<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Quittance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Quittance controller.
 *
 * @Route("caisse/quittance")
 */
class QuittanceController extends Controller
{
    /**
     * Lists all quittance entities.
     *
     * @Route("/", name="caisse_quittance_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('quittance/index.html.twig');
    }
    
    /**
     * Rechercher encaissements et remboursements
     *
     * @Route("/historique", name="admin_gestion_quittance_historique")
     * @Method({"GET", "POST"})
     */
    public function historiqueAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $immatriculation = \trim($request->get('immatriculation', ''));
        $quittance = \trim($request->get('quittance', ''));
        $date = new \DateTime("now");
        $debut = ($request->get('debut') != null && $request->get('debut') != "") ? \DateTime::createFromFormat( 'd-m-Y', $request->get('debut')) : $date;
        $debut->setTime(0, 0);
        $fin = ($request->get('fin') != null && $request->get('fin') != "") ? \DateTime::createFromFormat( 'd-m-Y', $request->get('fin')) : $date;
        
        $fin->setTime(0, 0);
        $quittances = $em->getRepository('AppBundle:Quittance')->historiqueTableEtatJournalier($debut, $fin, $immatriculation, $quittance);
        return $this->render('quittance/historique.html.twig', array(
            'quittances'=>$quittances, 'immatriculation'=>$immatriculation, 'quittance'=>$quittance, 'debut'=>$debut->format('d-m-Y'), 'fin'=>$fin->format('d-m-Y')
        ));
    }

    /**
     * Creates a new quittance entity.
     *
     * @Route("/new", name="caisse_quittance_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $quittance = new Quittance();
        $form = $this->createForm('AppBundle\Form\QuittanceType', $quittance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quittance);
            $em->flush();

            return $this->redirectToRoute('caisse_quittance_show', array('id' => $quittance->getId()));
        }

        return $this->render('quittance/new.html.twig', array(
            'quittance' => $quittance,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a quittance entity.
     *
     * @Route("/{id}", name="caisse_quittance_show")
     * @Method("GET")
     */
    public function showAction(Quittance $quittance)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_controle');
        }
        $deleteForm = $this->createDeleteForm($quittance);

        return $this->render('quittance/show.html.twig', array(
            'quittance' => $quittance,
            'delete_form' => $deleteForm->createView(),
            'libelle' => $centre->getLibelle(),
            'type' => 0,
            
        ));
    }
    
    /**
     * Finds and displays a quittance entity.
     *
     * @Route("/principal/{id}", name="caisse_quittance_show_principal")
     * @Method("GET")
     */
    public function showprincipalAction(Quittance $quittance)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_controle');
        }
        $deleteForm = $this->createDeleteForm($quittance);

        return $this->render('quittance/show.principal.html.twig', array(
            'quittance' => $quittance,
            'delete_form' => $deleteForm->createView(),
            'libelle' => $centre->getLibelle(),
            'type' => 1,
        ));
    }

    /**
     * Displays a form to edit an existing quittance entity.
     *
     * @Route("/{id}/edit", name="caisse_quittance_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Quittance $quittance)
    {
        $deleteForm = $this->createDeleteForm($quittance);
        $editForm = $this->createForm('AppBundle\Form\QuittanceType', $quittance);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('caisse_quittance_edit', array('id' => $quittance->getId()));
        }

        return $this->render('quittance/edit.html.twig', array(
            'quittance' => $quittance,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Encaisser une quittance.
     *
     * @Route("/{id}/encaisser", name="caisse_quittance_encaisser")
     * @Method({"GET", "POST"})
     */
    public function encaisserAction(Quittance $quittance)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$quittance->getVisite()->getChaine()->getCaisse()->getOuvert()){
            $this->get('session')->getFlashBag()->add('error', 'La caisse est fermée!');
            return $this->redirectToRoute('visite_quittance');
        }
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_controle');
        }
        if ($quittance->getPaye()) {
            $this->get('session')->getFlashBag()->add('notice', 'Cette quittance a déjà été encaissé.');
        }else{
            $visite = $quittance->getVisite();  
            $quittance->encaisser($user->getUsername());
            $caisse = $visite->getChaine()->getCaisse();
            $caisse->encaisserQuittance($quittance);
            if($visite->getRevisite()){
                $montantVisite = 0;
                $nbVisite = 0;
                $montantRevisite = $quittance->getTtc();
                $nbRevisite = 1;
            }else{
                $montantVisite = $quittance->getTtc();
                $nbVisite = 1;
                $montantRevisite = 0;
                $nbRevisite = 0;
            }
            
            $message = $visite->getContreVisiteVisuelle() ? "Quittance encaissée." : $visite->genererFichierMaha();
            $etat = new \AppBundle\Entity\EtatJournalier(\date('d-m-Y'), $montantVisite, $montantRevisite, $nbVisite, $nbRevisite, $quittance->getVisite()->getVehicule()->getTypeVehicule()->getLibelle(), $quittance->getVisite()->getVehicule()->getTypeVehicule()->getUsage()->getLibelle(), $quittance->getVisite()->getVehicule()->getTypeVehicule()->getGenre()->getLibelle(), $quittance->getVisite()->getVehicule()->getTypeVehicule()->getCarrosserie()->getLibelle(), $quittance->getVisite()->getChaine()->getCaisse()->getNumero(), $quittance->getVisite()->getVehicule()->getImmatriculation(), $quittance->getNumero(), $quittance->getAnaser(), $centre->getCode(), $user->getUsername(), 'Encaissement');
            $this->get('session')->getFlashBag()->add('notice', $message);
            $em->persist($etat);
            $em->flush();
        }
        return $this->render('quittance/show.html.twig', array(
            'quittance' => $quittance,
            'libelle' => $centre->getLibelle(),
        ));
    }
    
    /**
     * Rembourser une quittance.
     *
     * @Route("/{id}/rembourser", name="quittance_rembourser")
     * @Method({"GET", "POST"})
     */
    public function rembourserAction(Quittance $quittance)
    {
        
        if(!$quittance->getVisite()->getChaine()->getCaisse()->getOuvert()){
            $this->get('session')->getFlashBag()->add('error', 'La caisse est fermée!');
            return $this->redirectToRoute('visite_quittance');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_controle');
        }
        $today = new \DateTime();
        if($quittance->getRembourse()){
            $this->get('session')->getFlashBag()->add('error', "Cette quittance a déjà été remboursée!");
        }else if($quittance->getVisite()->getStatut() == 5){
           $this->get('session')->getFlashBag()->add('error', "Impossible de rembourser cette quittance car la visite est déjà annulée!"); 
        }else if($quittance->getVisite()->getStatut() >= 2){
           $this->get('session')->getFlashBag()->add('error', "Impossible de rembourser cette quittance car la visite est déjà effectuée!"); 
        }
        else if($quittance->getPaye() && $quittance->getDateEncaissement()->format('Y-m-d') < $today->format('Y-m-d')){
            $this->get('session')->getFlashBag()->add('error', "Le client doit passer à la caisse principale pour se faire rembourser!");
        }else{
            $quittance->rembourser($user->getUsername());
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
            $anaser = -$quittance->getAnaser();
            $encaisse = $em->getRepository('AppBundle:EtatJournalier')->recupererTypeEncaisse($quittance->getNumero(), $quittance->getVisite()->getImmatriculation_v());
            $etat = new \AppBundle\Entity\EtatJournalier(\date('d-m-Y'), $montantVisite, $montantRevisite, $nbVisite, $nbRevisite, $encaisse->getTypeVehicule(), $encaisse->getUsage(), $encaisse->getGenre(), $encaisse->getCarrosserie(), $quittance->getVisite()->getChaine()->getCaisse()->getNumero(), $quittance->getVisite()->getVehicule()->getImmatriculation(), $quittance->getNumero(), $anaser, $centre->getCode(), $quittance->getEncaissePar(), 'Remboursement', $user->getUsername());
            $em->persist($etat);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'La quittance a été remboursée.');
        }
        return $this->render('quittance/show.html.twig', array(
            'quittance' => $quittance,
            'libelle' => $centre->getLibelle(),
        ));
    }
    
    /**
     * Rembourser une quittance.
     *
     * @Route("/{id}/rembourser/confirmer", name="quittance_remboursement_confirmer")
     * @Method({"GET", "POST"})
     */
    public function rembourserconfirmerAction(Quittance $quittance)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_controle');
        }
        if(!$quittance->getVisite()->getChaine()->getCaisse()->getOuvert()){
            $this->get('session')->getFlashBag()->add('error', 'La caisse est fermée!');
            return $this->redirectToRoute('visite_quittance');
        }
        $today = new \DateTime();
        if($quittance->getPaye() && $quittance->getDateEncaissement()->format('Y-m-d') < $today->format('Y-m-d')){
            $style = "color:red";
            $message = "Remboursement à la caisse principal.";
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
     * Displays a form to edit an existing quittance entity.
     *
     * @Route("/{id}/creer", name="caisse_quittance_creer")
     * @Method({"GET", "POST"})
     */
    public function quittancetAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_controle');
        }
        $visite = $em->getRepository('AppBundle:Visite')->find($request->get('id'));
        if(!$visite->getChaine()->getCaisse()->getOuvert()){
            $this->get('session')->getFlashBag()->add('error', 'La caisse est fermée!.');
            return $this->redirectToRoute('visite_quittance');
        }
        $quittance = $em->getRepository('AppBundle:Quittance')->trouverQuittanceParVisite($request->get('id'));
        if($quittance){
            $this->get('session')->getFlashBag()->add('notice', 'La quittance existe déjà.');
            return $this->render('quittance/show.html.twig', array(
            'quittance' => $quittance,
                'libelle' => $centre->getLibelle(),
            ));
        }
        $quittance = new Quittance();
        $quittance->setVisite($visite);
        $derniereVisite = $em->getRepository('AppBundle:Visite')->derniereVisite($visite->getVehicule()->getId(), $visite->getId());
        $montant = $quittance->calculerMontant($derniereVisite);
        if($montant == -9999999999999999){
            $quittance->getVisite()->setRevisite(0);
            $montant = $quittance->getVisite()->getVehicule()->getTypeVehicule()->getMontantVisite();
            $retard = 0;
            $penalite = 0;
            $quittance->getVisite()->getVehicule()->setCompteurRevisite(0);
        }else{
            $retard = $quittance->calculerRetard($derniereVisite);
            $penalite = $em->getRepository('AppBundle:Penalite')->trouverParNbJours($retard);
        }
        $quittance->generer($montant, $penalite, $retard, $centre->getAnaser(), $centre->getCode(), $visite->getChaine()->getCaisse()->getNumero());
        $em->persist($quittance);
        $visite->setQuittance($quittance);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'Quittance générée avec succès.');
        return $this->render('quittance/show.html.twig', array(
            'quittance' => $quittance, 'libelle' => $centre->getLibelle(),
        ));
    }
    
    /**
     * Displays a form to edit an existing quittance entity.
     *
     * @Route("/{id}/confirmer", name="quittance_confirmer")
     * @Method({"GET", "POST"})
     */
    public function quittanceconfirmertAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $visite = $em->getRepository('AppBundle:Visite')->find($request->get('id'));
        if(!$visite->getChaine()->getCaisse()->getOuvert()){
            $this->get('session')->getFlashBag()->add('error', 'La caisse est fermée!.');
            return $this->redirectToRoute('visite_quittance');
        }
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_controle');
        }
        $quittance = $em->getRepository('AppBundle:Quittance')->trouverQuittanceParVisite($request->get('id'));
        if(!$quittance){
            $quittance = new Quittance();
            $quittance->setVisite($visite);
            $derniereVisite = $em->getRepository('AppBundle:Visite')->derniereVisite($visite->getVehicule()->getId(), $visite->getId());
            $montant = $quittance->calculerMontant($derniereVisite);
            $retard = $quittance->calculerRetard($derniereVisite);
            $penalite = $em->getRepository('AppBundle:Penalite')->trouverParNbJours($retard);
            $quittance->generer($montant, $penalite, $retard, $centre->getAnaser(), $centre->getCode());
        }
        return $this->render('quittance/confirmer.html.twig', array(
            'quittance' => $quittance,
            'libelle' => $centre->getLibelle()
        ));
    }


    /**
     * Deletes a quittance entity.
     *
     * @Route("/{id}", name="caisse_quittance_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Quittance $quittance)
    {
        $form = $this->createDeleteForm($quittance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($quittance);
            $em->flush();
        }

        return $this->redirectToRoute('caisse_quittance_index');
    }

    /**
     * Creates a form to delete a quittance entity.
     *
     * @param Quittance $quittance The quittance entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Quittance $quittance)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('caisse_quittance_delete', array('id' => $quittance->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Finds and displays a quittance entity.
     *
     * @Route("/{id}/imprimer", name="quittance_imprim")
     * @Method("GET")
     */
    public function imprimAction(Quittance $quittance)
    {
        if(!$quittance){
            throw $this->createNotFoundException("La quittance demandée n'est pas disponible.");
        }
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_controle');
        }
        $numero = $quittance->getNumero();
        $chemin = __DIR__.'/../../../web/quittances/quittance_'.$numero.'.pdf';
        if (file_exists($chemin)) {
            unlink($chemin);
        }

        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'quittance/imprim.html.twig',
                array(
                    'quittance'  => $quittance, 'libelle' => $centre->getLibelle()
                )
            ),
            $chemin,
            array('encoding' => 'utf-8',)
        );
        
        $response = new BinaryFileResponse($chemin);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        return $response;
    }
    
    /**
     * Lists all Quittance entities.
     *
     * @Route("/admin/gestion/centre/quittance/liste", name="quittanceprincipaleajax")
     * 
     * 
     */
    public function quittanceajaxAction(Request $request)
    {
        $search = $request->get('search')['value'];
        $col = $request->get('order')[0]['column'];
        $dir = $request->get('order')[0]['dir'];
        $em = $this->getDoctrine()->getManager();
	$aColumns = array( 'vh.immatriculation', 'p.nom', 'ca.numero', 'v.montantVisite', 'r.numero');
        $start = ($request->get('start') != NULL && intval($request->get('start')) > 0) ? intval($request->get('start')) : 0;
        $end = ($request->get('length') != NULL && intval($request->get('length')) > 50) ? intval($request->get('length')) : 50;
        $sCol = (intval($col) > 0 && intval($col) < 3) ? intval($col)-1 : 0;
        $sdir = ($dir =='asc') ? 'asc' : 'desc';
        $searchTerm = ($search != '') ? $search : NULL;
        $rResult = $em->getRepository('AppBundle:Quittance')->findAllAjax($start, $end, $aColumns[$sCol], $sdir, $searchTerm);
	$iTotal = $em->getRepository('AppBundle:Quittance')->countRows();
        $iTotalFiltre = $em->getRepository('AppBundle:Quittance')->countRowsFiltre($searchTerm);
	$output = array("sEcho" => intval($request->get('sEcho')), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iTotalFiltre, "aaData" => array());
	foreach ( $rResult as  $aRow )
	{
            $action = $this->genererAction($aRow['id'], $aRow['statut']);
            $montant = $aRow['montantVisite'] > 0 ? \ceil($aRow['montantVisite']+$aRow['tva']+$aRow['timbre']+$aRow['anaser']) : 0;
            $immatriculation = $aRow['immatriculation_v'] != null ? $aRow['immatriculation_v'] : $aRow['immatriculation'];
            $output['aaData'][] = array($immatriculation, $aRow['nom']." ".$aRow['prenom'], $aRow['caisse'], \number_format($montant, 0, ',', ' '), $aRow['numero'], $action);
	}
	return new Response(json_encode( $output ));    
    }
    
    private function genererAction($id, $statut){
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERVISEUR')){
            switch($statut){
                case 0 : case 1 : 
                    $action = " <a title='Rembourser' onclick='rembourser(".$id.")' class='btn btn-info' href='#'><i class='fa fa-edit' ></i></a>";
                    $action .= "<br/><a title='Quittance' class='btn btn-success' href='".$this->generateUrl('caisse_quittance_show_principal', array('id'=> $id))."'><i class='fa fa-credit-card' ></i> Voir</a>";
                    break;
                case 2 : case 3 : case 4: 
                    $action = "Déjà contrôlé";
                    $action .= "<br/><a title='Quittance' class='btn btn-success' href='".$this->generateUrl('caisse_quittance_show_principal', array('id'=> $id))."'><i class='fa fa-credit-card' ></i> Voir</a>";
                    break;
                case 5 : $action = "Déjà annulée";$action .= "<br/><a title='Quittance' class='btn btn-success' href='".$this->generateUrl('caisse_quittance_show_principal', array('id'=> $id))."'><i class='fa fa-credit-card' ></i> Voir</a>";
            }
        }
        return $action;
    }
    
    /**
     * Export all SortieCaisse entities.
     *
     * @Route("/admin/gestion/centre/sorties/download", name="sortiecaisse_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:SortieCaisse')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("SortieCaisse".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'SortieCaisse'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("TYPE");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("MONTANT");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("DATE");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getType());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getTtc());$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getDateCreation());
            $ligne++;
        }
    }
    
    /**
     * Annuler une quittance.
     *
     * @Route("/annuler/{id}", name="caisse_quittance_annuler")
     * @Method({"GET", "POST"})
     */
    public function annulerAction(Quittance $quittance)
    {
        $em = $this->getDoctrine()->getManager();
        if(!$quittance->getVisite()->getChaine()->getCaisse()->getOuvert()){
            $this->get('session')->getFlashBag()->add('error', 'La caisse est fermée!');
            return $this->redirectToRoute('visite_quittance');
        }
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_quittance');
        }
        if ($quittance->getPaye()) {
            $this->get('session')->getFlashBag()->add('notice', 'Cette quittance a déjà été encaissé.');
        }else{
            $visite = $quittance->getVisite();
            if($visite != null && $visite->getStatut() >= 1 && $visite->getStatut()<5){
                $this->get('session')->getFlashBag()->add('error', "Quittance déjà encaissée.");
            }else{
                $visite = $quittance->getVisite();
                $visite->setQuittance(null);
                $quittance->setVisite(null);
                $em->flush();
                $em->remove($quittance);
                $this->get('session')->getFlashBag()->add('notice', 'La quittance a été annulée');
            }
            $em->flush();
        }
        return $this->redirectToRoute('visite_quittance');
    }
    
    /**
     * Exporter encaissements et remboursements
     *
     * @Route("/historique/exporter", name="admin_gestion_quittance_historique_exporter")
     * @Method({"GET", "POST"})
     */
    public function historiqueexporterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $immatriculation = \trim($request->get('immatriculation', ''));
        $quittance = \trim($request->get('quittance', ''));
        $date = new \DateTime("now");
        $debut = ($request->get('debut') != null && $request->get('debut') != "") ? \DateTime::createFromFormat( 'd-m-Y', $request->get('debut')) : $date->format('d-m-Y');
        $debut->setTime(0, 0);
        $fin = \DateTime::createFromFormat( 'd-m-Y',$request->get('fin', $date->format('d-m-Y')));
        $fin->add(new \DateInterval('P1D'));
        $fin->setTime(0, 0);
        $quittances = $em->getRepository('AppBundle:Quittance')->historiqueTableEtatJournalier($debut, $fin, $immatriculation, $quittance);
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("Historique des quittances".$debut->format('d-m-Y')."_".$fin->format('d-m-Y'));
        $this->writeExport($phpExcelObject, $quittances);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = "Historique des quittances".$debut->format('d-m-Y')."_".$fin->format('d-m-Y').".xls";
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        return $response; 
    }
    
    private function writeExport($phpExcelObject, $entities) {
        $phpExcelObject->setActiveSheetIndex(0);
        $col = 0;
        $objWorksheet = $phpExcelObject->getActiveSheet();
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Immatriculation");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Caisse");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Montant");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Quittance");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Statut");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Date");$col++;
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("Agent");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $statut = $entity['montantVisite'] >= 0 ? "Encaissement" : "Remboursement";
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity['immatriculation']);$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity['caisse']);$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity['montantVisite']);$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity['quittance']);$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($statut);$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity['dateCreation']);$col++;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity['creePar']);$col++;
            $ligne++;
        }
    }
}
