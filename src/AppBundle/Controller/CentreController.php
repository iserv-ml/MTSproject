<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Centre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\SortieCaisse;

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
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActives();
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
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActives();
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
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActives();
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
            $centre = $em->getRepository('AppBundle:Centre')->recuperer();
            if(!$centre){
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
            $this->getDoctrine()->getManager()->flush();
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
            $chaines = $em->getRepository('AppBundle:Chaine')->chainesActives();
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
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActives();
        $centre = $em->getRepository('AppBundle:Centre')->recuperer();
        if(!$centre){
            throw $this->createNotFoundException("Cette opération est interdite!");
        }
        if(!$centre->getEtat()){
            $centre->setEtat(true);
            $centre->setCarteViergeOuverture($centre->getCarteVierge());
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
        $chaines = $em->getRepository('AppBundle:Chaine')->chainesActives();
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
                    $sortie = $centre->encaisser($chaine->getCaisse());
                    $chaine->getCaisse()->cloturer();
                    $em->persist($sortie);
                }
            }
            if($caisses){
                $centre->setEtat(false);
                $centre->setCarteViergeOuverture(0);
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
        if(!$centre || !$centre->getEtat()){
            $this->get('session')->getFlashBag()->add('error', 'Le centre est fermé!');
            return $this->redirectToRoute('caisse_quittance_index');
        }
        switch($quittance->remboursableOu()){
            case -1: $this->get('session')->getFlashBag()->add('error', "La visite a déjà été effectuée");break;
            case 0: $this->get('session')->getFlashBag()->add('error', "Cette quittance n'a pas été encaissée!");break;
            case 1: $this->get('session')->getFlashBag()->add('error', "Le client doit passer à la caisse N°".$quittance->getVisite()->getChaine()->getCaisse()->getNumero()." pour se faire rembourser!");break;
            case 2: if($quittance->controleSolde($centre->getSolde())){
                        $sortie = $centre->rembourser($quittance);
                        $em->persist($sortie);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('notice', 'La quittance a été remboursée.');
                    }else{
                        $this->get('session')->getFlashBag()->add('error', "Le solde disponible ne permet pas de rembourser cette quittance!");
                    }
                break;
        }
        return $this->render('caisse_quittance_index');
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
    
    
}
