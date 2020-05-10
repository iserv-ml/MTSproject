<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TypeVehiculeImport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Typevehiculeimport controller.
 *
 * @Route("admin/parametres/typevehicule/importer")
 */
class TypeVehiculeImportController extends Controller
{
    /**
     * Lists all typeVehiculeImport entities.
     *
     * @Route("/", name="admin_parametres_typevehicule_importer_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeVehiculeImports = $em->getRepository('AppBundle:TypeVehiculeImport')->findAll();

        return $this->render('typevehiculeimport/index.html.twig', array(
            'typeVehiculeImports' => $typeVehiculeImports,
        ));
    }

    /**
     * Creates a new typeVehiculeImport entity.
     *
     * @Route("/new", name="admin_parametres_typevehicule_importer_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeVehiculeImport = new Typevehiculeimport();
        $form = $this->createForm('AppBundle\Form\TypeVehiculeImportType', $typeVehiculeImport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeVehiculeImport);
            $em->flush();
            $this->importer($typeVehiculeImport->getAbsolutePath());
            return $this->redirectToRoute('admin_parametres_typevehicule_index');
        }

        return $this->render('typevehiculeimport/new.html.twig', array(
            'typeVehiculeImport' => $typeVehiculeImport,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typeVehiculeImport entity.
     *
     * @Route("/{id}", name="admin_parametres_typevehicule_importer_show")
     * @Method("GET")
     */
    public function showAction(TypeVehiculeImport $typeVehiculeImport)
    {
        $deleteForm = $this->createDeleteForm($typeVehiculeImport);

        return $this->render('typevehiculeimport/show.html.twig', array(
            'typeVehiculeImport' => $typeVehiculeImport,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typeVehiculeImport entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_typevehicule_importer_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeVehiculeImport $typeVehiculeImport)
    {
        $deleteForm = $this->createDeleteForm($typeVehiculeImport);
        $editForm = $this->createForm('AppBundle\Form\TypeVehiculeImportType', $typeVehiculeImport);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametres_typevehicule_importer_edit', array('id' => $typeVehiculeImport->getId()));
        }

        return $this->render('typevehiculeimport/edit.html.twig', array(
            'typeVehiculeImport' => $typeVehiculeImport,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typeVehiculeImport entity.
     *
     * @Route("/{id}", name="admin_parametres_typevehicule_importer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeVehiculeImport $typeVehiculeImport)
    {
        $form = $this->createDeleteForm($typeVehiculeImport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeVehiculeImport);
            $em->flush();
        }

        return $this->redirectToRoute('admin_parametres_typevehicule_importer_index');
    }

    /**
     * Creates a form to delete a typeVehiculeImport entity.
     *
     * @param TypeVehiculeImport $typeVehiculeImport The typeVehiculeImport entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeVehiculeImport $typeVehiculeImport)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_typevehicule_importer_delete', array('id' => $typeVehiculeImport->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    private function importer($path){
        try {
            $genret = null;
            $carrosseriet = null;
            $usaget = null;
            $em = $this->getDoctrine()->getManager();
            $objPHPExcel = \PHPExcel_IOFactory::load($path);
            $worksheet = $objPHPExcel->getSheet(0); 
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            for ($row = 2; $row <= $highestRow; ++ $row) {
                $colonne = 0;
                $genret = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $carrosseriet = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $usaget = $worksheet->getCellByColumnAndRow($colonne, $row)->getValue();$colonne++;
                $type = $em->getRepository('AppBundle:TypeVehicule')->trouverLibelle($genret, $usaget, $carrosseriet);
                if($type != null) {continue;}
                $genre = $em->getRepository('AppBundle:Genre')->trouverParLibelle($genret);
                if($genre == null){
                    $genre = new \AppBundle\Entity\Genre();
                    $genre->setCode($genret);
                    $genre->setLibelle($genret);
                    $genre->setDelaiPremiereVisite(0);
                    $genre->setPtacMax(1500);
                    $genre->setPtacMin(0);
                    $em->persist($genre);
                }
                $carrosserie = $em->getRepository('AppBundle:Carrosserie')->trouverParLibelle($carrosseriet);
                if($carrosserie == null){
                    $carrosserie = new \AppBundle\Entity\Carrosserie();
                    $carrosserie->setCode($carrosseriet);
                    $carrosserie->setLibelle($carrosseriet);
                    $em->persist($carrosserie);
                }
                $usage = $em->getRepository('AppBundle:Usage')->trouverParLibelle($usaget);
                if($usage == null){
                    $usage = new \AppBundle\Entity\Usage();
                    $usage->setCode($usaget);
                    $usage->setLibelle($usaget);
                    $em->persist($usage);
                }
                $type = new \AppBundle\Entity\TypeVehicule();
                $type->setLibelle($genret."_".$carrosseriet."_".$usaget);
                $type->setGenre($genre);
                $type->setUsage($usage);
                $type->setCarrosserie($carrosserie);
                $type->setDelai(0);
                $type->setMontantRevisite(1500);
                $type->setMontantVisite(5000);
                $type->setTimbre(0);
                $type->setValidite(30);
                $em->persist($type);       
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Chargement terminé');
        } catch(Exception $e) {
            $this->get('session')->getFlashBag()->add('notice', $e->getMessage());
        }
    }
}
