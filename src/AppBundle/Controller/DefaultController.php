<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Visite;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
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
}
