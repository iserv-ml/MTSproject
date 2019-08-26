<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Visite;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use AppBundle\Entity\Stat;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nbVehicule = $em->getRepository('AppBundle:Vehicule')->countRows();
        $enCours = 0;
        $succes = 0;
        $echec = 0;
        $visites = $em->getRepository('AppBundle:Visite')->nbVisitesParStatut();
        if(count($visites) > 0){
            foreach($visites as $visite){
                switch($visite['statut']){
                    case 0 : $enCours += $visite[1];break;
                    case 1 : $enCours += $visite[1];break;
                    case 2 : $succes += $visite[1];break;
                    case 3 : $echec += $visite[1];break;
                    case 4 : $succes += $visite[1];break;
                }
            }
        }
        return $this->render('default/index.html.twig', array(
            "nbVehicule"=>$nbVehicule, 
            "enCours"=>$enCours,
            "succes"=>$succes,
            "echec"=>$echec,));
    }
    
    /**
     * @Route("/admin/parametres/", name="parametre_index")
     */
    public function parametresAction(Request $request)
    {
        return $this->render('parametres/index.html.twig');
    }
    
    /**
     * @Route("/utilisateur/profil", name="utilisateur_profil")
     */
    public function profilAction(Request $request)
    {
        $utilisateur = $this->container->get('security.context')->getToken()->getUser(); 
        return $this->render('utilisateur/show.html.twig', array('utilisateur' => $utilisateur));
    }
    
    /**
     * Imprimer un rapport de visite.
     *
     * @Route("/{id}/rapport/imprimer", name="rapport_imprimer")
     * @Method("GET")
     */
    public function rapportAction(Visite $visite)
    {
        if(!$visite){
            throw $this->createNotFoundException("La visite demandée n'est pas disponible.");
        }
        $em = $this->getDoctrine()->getManager();
        $quittance = $em->getRepository('AppBundle:Quittance')->trouverQuittanceParVisite($visite->getId());
        $numero = $visite->getNumeroCertificat();
        $chemin = __DIR__.'/../../../web/visites/rapports/rapport_'.$numero.'.pdf';
        if (file_exists($chemin)) {
            unlink($chemin);
        }      
        
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'default/rapport.html.twig',
                array(
                    'visite'  => $visite, 'quittance' => $quittance,
                )
            ),
            $chemin
        );
        
        $response = new BinaryFileResponse($chemin);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        return $response;
    }
    
    /**
     * Imprimer un certificat de visite.
     *
     * @Route("/{id}/certificat/volet1", name="certificat_imprimer_1")
     * @Method("GET")
     */
    public function certificatAction(Visite $visite)
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre){
            throw $this->createNotFoundException("Cette opération est interdite!");
        }
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_delivrance');
        }
        if(!$visite){
            throw $this->createNotFoundException("La visite demandée n'est pas disponible.");
        }
        $chemin = __DIR__.'/../../../web/visites/certificats/certificat_'.$visite->getNumeroCertificat().'.pdf';
        if (file_exists($chemin)) {
            //unlink($chemin);
        }else{
            $visite->setStatut(4);
            $centre->decrementerCarteVierge();
            $em->flush();
            $this->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView(
                    'default/certificat.html.twig',
                    array(
                        'visite'  => $visite,
                    )
                ),
                $chemin
            );
        }     
        
        $response = new BinaryFileResponse($chemin);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        return $response;
    }
    
    /**
     * Imprimer un certificat de visite.
     *
     * @Route("/{id}/certificat/volet2", name="certificat_imprimer_2")
     * @Method("GET")
     */
    public function certificatvoletAction(Visite $visite)
    {
        if(!$visite){
            throw $this->createNotFoundException("La visite demandée n'est pas disponible.");
        }
        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('visite_delivrance');
        }
        $chemin = __DIR__.'/../../../web/visites/certificats/volet2_'.$visite->getNumeroCertificat().'.pdf';
        if (file_exists($chemin)) {
            unlink($chemin);
        }else{
            $visite->setStatut(4);
            $em->flush();
        }     
        
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'default/volet2.html.twig',
                array(
                    'visite'  => $visite,
                )
            ),
            $chemin
        );
        
        $response = new BinaryFileResponse($chemin);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        return $response;
    }
}
