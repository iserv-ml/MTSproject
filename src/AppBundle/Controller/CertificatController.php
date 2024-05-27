<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Certificat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Certificat controller.
 *
 * @Route("secretaire/certificat")
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
        $em = $this->getDoctrine()->getManager();

        $certificats = $em->getRepository('AppBundle:Certificat')->findAll();

        return $this->render('certificat/index.html.twig', array(
            'certificats' => $certificats,
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
        $certificat = new Certificat();
        $form = $this->createForm('AppBundle\Form\CertificatType', $certificat);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $quantite = $certificat->getQuantite();
            $debut = $certificat->getDebut();
            if($debut > 0 && $quantite > 0){
                $em = $this->getDoctrine()->getManager();
                
                while($quantite > 0){
                    $tmp = new Certificat();
                    $tmp->setSerie($debut);
                    $tmp->setControlleur($certificat->getControlleur());
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
            'certificat' => $certificat,
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
}
