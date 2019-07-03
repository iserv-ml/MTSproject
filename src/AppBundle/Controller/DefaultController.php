<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Utilisateur;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
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
}
