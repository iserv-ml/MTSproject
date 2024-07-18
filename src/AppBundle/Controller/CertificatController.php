<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Certificat;
use AppBundle\Entity\Lot;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Certificat controller.
 *
 * @Route("certificat")
 */
class CertificatController extends Controller
{
    /**
     * Lists all certificat entities.
     *
     * @Route("/", name="secretaire_certificat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        throw $this->createNotFoundException("Opération interdite");
    }
    
    /**
     * Lists all certificat entities.
     *
     * @Route("/secretaire", name="secretaire_certificat_index")
     * @Method("GET")
     */
    public function secretaireAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lots = $em->getRepository('AppBundle:Lot')->findAll();

        return $this->render('certificat/index.html.twig', array(
            'lots' => $lots,
        ));
    }
    
    /**
     * Lists all certificat from lot entities.
     *
     * @Route("/chefcentre/detail/{id}", name="secretaire_certificat_lot_index")
     * @Method("GET")
     */
    public function indexLotAction(Lot $lot)
    {

        $certificats = $lot->getCertificats();

        return $this->render('certificat/indexAgent.html.twig', array(
            'certificats' => $certificats, 'lot'=>$lot->getId()
        ));
    }

    /**
     * Creates a new certificat entity.
     *
     * @Route("/new", name="secretaire_certificat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $lot = new Lot();
       
        $form = $this->createForm('AppBundle\Form\LotType', $lot);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $quantite = intval($lot->getQuantite());
            $debut = intval($lot->getDebut());
            if($debut > 0 && $quantite > 0){
                $user = $this->container->get('security.context')->getToken()->getUser();
                $fin = $debut+$quantite-1;
                $serie = $debut."-".$fin;
                $em = $this->getDoctrine()->getManager();
                $lot->setSerie($serie);
                $lot->setDateAffectationCentre(new \DateTime("now"));
                $lot->setAttributeur($user->getNomComplet());
                $em->persist($lot);
                while($quantite > 0){
                    $tmp = new Certificat();
                    $tmp->setSerie($debut);
                    $tmp->setLot($lot);
                    //$tmp->setControlleur($certificat->getControlleur());
                    $em->persist($tmp);
                    $debut++;
                    $quantite--;
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
                return $this->redirectToRoute('secretaire_certificat_index');
            }else{
                $this->get('session')->getFlashBag()->add('error', 'Merci de renseigner les champs "début" et "quantité".');
            }
        }

       

        return $this->render('certificat/new.html.twig', array(
            'lot' => $lot,
            'form' => $form->createView(),
        ));
    }
    

    /**
     * Finds and displays a certificat entity.
     *
     * @Route("/{id}", name="secretaire_certificat_show")
     * @Method("GET")
     */
    public function showAction(Certificat $certificat)
    {
        $deleteForm = $this->createDeleteForm($certificat);

        return $this->render('certificat/show.html.twig', array(
            'certificat' => $certificat,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing certificat entity.
     *
     * @Route("/{id}/edit", name="secretaire_certificat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Certificat $certificat)
    {
        $deleteForm = $this->createDeleteForm($certificat);
        $editForm = $this->createForm('AppBundle\Form\CertificatType', $certificat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('secretaire_certificat_edit', array('id' => $certificat->getId()));
        }

        return $this->render('certificat/edit.html.twig', array(
            'certificat' => $certificat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Cancel an existing certificat entity.
     *
     * @Route("/{id}/annuler", name="secretaire_certificat_annuler")
     * @Method({"GET", "POST"})
     */
    public function annulerAction(Request $request, Certificat $certificat)
    {

        if (!$certificat) {
            throw $this->createNotFoundException("Le certificat demandé n'est pas disponible.");
        }else
        if($certificat->getUtilise()){
            $this->get('session')->getFlashBag()->add('error', "Impossible d'annuler. Ce certificati a déjà été utilisé");
            return $this->redirectToRoute('secretaire_certificat_index');
        }
        if($certificat->getAnnule()){
            $this->get('session')->getFlashBag()->add('error', "Ce certificati a déjà été annulé");
            return $this->redirectToRoute('secretaire_certificat_index');
        }
        $certificat->setAnnule(true);
        $this->getDoctrine()->getManager()->flush();
        $this->get('session')->getFlashBag()->add('notice', 'Annulation effectuée.');
        return $this->redirectToRoute('centre_certificat');
    }
    
    /**
     * Delete an existing lot entity.
     *
     * @Route("/{id}/supprimer", name="secretaire_lot_supprimer")
     * @Method({"GET", "POST"})
     */
    public function supprimerAction(Request $request, Lot $lot)
    {

        if (!$lot) {
            throw $this->createNotFoundException("Le lot demandé n'est pas disponible.");
        }else
        if(!$lot->estSupprimable()){
            $this->get('session')->getFlashBag()->add('error', "Impossible d'annuler. Ce lot contient un certificat déjà utilisé");
            return $this->redirectToRoute('secretaire_certificat_index');
        }
        
        $em = $this->getDoctrine()->getManager();
        foreach($lot->getCertificats() as $certificat){
            $em->remove($certificat);
        }
        $em->remove($lot);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
        return $this->redirectToRoute('secretaire_certificat_index');
    }

    /**
     * Deletes a certificat entity.
     *
     * @Route("/{id}", name="secretaire_certificat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Certificat $certificat)
    {
        $form = $this->createDeleteForm($certificat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($certificat);
            $em->flush();
        }

        return $this->redirectToRoute('secretaire_certificat_index');
    }

    /**
     * Creates a form to delete a certificat entity.
     *
     * @param Certificat $certificat The certificat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Certificat $certificat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('secretaire_certificat_delete', array('id' => $certificat->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
     /**
     * Creates a new certificat entity.
     *
     * @Route("/centreaffecter/{id}", name="centre_certificat_affecter")
     * @Method({"GET", "POST"})
     */
    public function affecterAction(Request $request, Lot $lot)
    {       
        $form = $this->createForm('AppBundle\Form\LotAffecterType', $lot);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $quantite = intval($lot->getQuantiteF());
            if($quantite > 0){
                $user = $this->container->get('security.context')->getToken()->getUser();
                $em = $this->getDoctrine()->getManager();
                foreach($lot->getCertificats() as $certificat){
                    if($certificat->getUtilise() || $certificat->getAnnule() || $certificat->getControlleur()!=null) continue;
                    $certificat->setControlleur($lot->getControlleur());
                    $certificat->setAttribuePar($user->getNomComplet());
                    $em->flush();
                    $quantite--;
                    if($quantite == 0) break;
                }

                $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
                return $this->redirectToRoute('centre_certificat');
            }else{
                $this->get('session')->getFlashBag()->add('error', 'Merci de renseigner les champs "début" et "quantité".');
            }
        }

       

        return $this->render('certificat/affecter.html.twig', array(
            'lot' => $lot,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Vérifier si le certificat peut être supprimer avant confi an existing certificat entity.
     *
     * @Route("/{id}/confirmer/annuler", name="centre_certificat_annuler_confirmer")
     * @Method({"GET", "POST"})
     */
    public function confirmerannulerAction(Request $request, Certificat $certificat)
    {
        $message = "Vous ête sur le point d'annuler le certificat ".$certificat->getSerie();
        if (!$certificat) {
            throw $this->createNotFoundException("Le certificat demandé n'est pas disponible.");
        }else
        if($certificat->getUtilise()){
            $message = "Le certificat ".$certificat->getSerie()." ne peut pas être annulé. Il a déjà été utilisé pour une visite";
        }
        if($certificat->getAnnule()){
            $message = "Le certificat ".$certificat->getSerie()." a déjà été annuler";
        }
       
        return $this->render('certificat/annuler.html.twig', array(
            'message' => $message,
        ));
    }
}
