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
        $em = $this->getDoctrine()->getManager();

        $quittances = $em->getRepository('AppBundle:Quittance')->findAll();

        return $this->render('quittance/index.html.twig', array(
            'quittances' => $quittances,
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
        $deleteForm = $this->createDeleteForm($quittance);

        return $this->render('quittance/show.html.twig', array(
            'quittance' => $quittance,
            'delete_form' => $deleteForm->createView(),
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
    public function encaisserAction(Request $request, Quittance $quittance)
    {
        if ($quittance->getPaye()) {
            $this->get('session')->getFlashBag()->add('notice', 'Cette quittance a déjà été encaissé.');
        }else{
            $em = $this->getDoctrine()->getManager();
            $quittance->setPaye(1);
            $quittance->getVisite()->setStatut(1);
            $caisse = $quittance->getVisite()->getChaine()->getCaisse();
            $caisse->encaisser($quittance->getMontantVisite());
            $this->get('session')->getFlashBag()->add('notice', 'Quittance encaissée.');
            $em->flush();
        }
        return $this->render('quittance/show.html.twig', array(
            'quittance' => $quittance,
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
        $visite = $em->getRepository('AppBundle:Visite')->find($request->get('id'));
        $quittance = $em->getRepository('AppBundle:Quittance')->trouverQuittanceParVisite($request->get('id'));
        if($quittance){
            $this->get('session')->getFlashBag()->add('notice', 'La quittance existe déjà.');
            return $this->render('quittance/show.html.twig', array(
            'quittance' => $quittance,
            ));
        }
        $quittance = new Quittance();
        $quittance->setVisite($visite);
        $derniereVisite = $em->getRepository('AppBundle:Visite')->derniereVisite($visite->getVehicule()->getId(), $visite->getId());
        $montant = $quittance->calculerMontant();
        $retard = $quittance->calculerRetard($derniereVisite);
        $penalite = $em->getRepository('AppBundle:Penalite')->trouverParNbJours($retard);
        $quittance->generer($montant, $penalite, $retard);
        $em->persist($quittance);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'Quittance générée avec succès.');
        return $this->render('quittance/show.html.twig', array(
            'quittance' => $quittance,
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
        $quittance = $em->getRepository('AppBundle:Quittance')->trouverQuittanceParVisite($request->get('id'));
        if(!$quittance){
            $quittance = new Quittance();
            $quittance->setVisite($visite);
            $derniereVisite = $em->getRepository('AppBundle:Visite')->derniereVisite($visite->getVehicule()->getId(), $visite->getId());
            $montant = $quittance->calculerMontant();
            $retard = $quittance->calculerRetard($derniereVisite);
            $penalite = $em->getRepository('AppBundle:Penalite')->trouverParNbJours($retard);
            $quittance->generer($montant, $penalite, $retard);
        }
        return $this->render('quittance/confirmer.html.twig', array(
            'quittance' => $quittance,
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
        $numero = $quittance->getNumero();
        $chemin = __DIR__.'/../../../web/quittances/quittance_'.$numero.'.pdf';
        if (file_exists($chemin)) {
            unlink($chemin);
        }
        

        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'quittance/imprim.html.twig',
                array(
                    'quittance'  => $quittance
                )
            ),
            $chemin
        );
        
        $response = new BinaryFileResponse($chemin);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        return $response;
    }
}
