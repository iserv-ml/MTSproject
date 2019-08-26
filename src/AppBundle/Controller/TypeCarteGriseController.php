<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TypeCarteGrise;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Typecartegrise controller.
 *
 * @Route("admin/parametres/typecartegrise")
 */
class TypeCarteGriseController extends Controller
{
    /**
     * Lists all typeCarteGrise entities.
     *
     * @Route("/", name="admin_parametres_typecartegrise_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeCarteGrises = $em->getRepository('AppBundle:TypeCarteGrise')->findAll();

        return $this->render('typecartegrise/index.html.twig', array(
            'typeCarteGrises' => $typeCarteGrises,
        ));
    }

    /**
     * Creates a new typeCarteGrise entity.
     *
     * @Route("/new", name="admin_parametres_typecartegrise_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeCarteGrise = new Typecartegrise();
        $form = $this->createForm('AppBundle\Form\TypeCarteGriseType', $typeCarteGrise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeCarteGrise);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_typecartegrise_show', array('id' => $typeCarteGrise->getId()));
        }

        return $this->render('typecartegrise/new.html.twig', array(
            'typeCarteGrise' => $typeCarteGrise,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typeCarteGrise entity.
     *
     * @Route("/{id}", name="admin_parametres_typecartegrise_show")
     * @Method("GET")
     */
    public function showAction(TypeCarteGrise $typeCarteGrise)
    {
        if (!$typeCarteGrise) {
            throw $this->createNotFoundException("Le type de carte grise demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($typeCarteGrise);

        return $this->render('typecartegrise/show.html.twig', array(
            'typeCarteGrise' => $typeCarteGrise,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typeCarteGrise entity.
     *
     * @Route("/{id}/edit", name="admin_parametres_typecartegrise_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeCarteGrise $typeCarteGrise)
    {
        if (!$typeCarteGrise) {
            throw $this->createNotFoundException("Le type de carte grise demandé n'est pas disponible.");
        }
        $deleteForm = $this->createDeleteForm($typeCarteGrise);
        $editForm = $this->createForm('AppBundle\Form\TypeCarteGriseType', $typeCarteGrise);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Enregistrement effectué.');
            return $this->redirectToRoute('admin_parametres_typecartegrise_edit', array('id' => $typeCarteGrise->getId()));
        }

        return $this->render('typecartegrise/edit.html.twig', array(
            'typeCarteGrise' => $typeCarteGrise,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typeCarteGrise entity.
     *
     * @Route("/{id}", name="admin_parametres_typecartegrise_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeCarteGrise $typeCarteGrise)
    {
        if (!$typeCarteGrise) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }elseif ($typeCarteGrise->estSupprimable()) {
            $form = $this->createDeleteForm($typeCarteGrise);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($typeCarteGrise);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');
            }
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer  ce type de carte grise car il est utilisée.');
        }

        return $this->redirectToRoute('admin_parametres_typecartegrise_index');
    }
    
    /**
     * Deletes a typeCarteGrise entity.
     *
     * @Route("/{id}/delete", name="admin_parametres_typecartegrise_delete_a")
     * @Method("GET")
     */
    public function deleteaAction(TypeCarteGrise $typeCarteGrise)
    {
        if (!$typeCarteGrise) {
            $this->get('session')->getFlashBag()->add('error', "Oops! Une erreur s'est produite");
        }
        if($typeCarteGrise->estSupprimable()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeCarteGrise);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Suppression effectuée.');    
        }else{
            $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer ce type de carte grise car il est utilisé');
        }
        return $this->redirectToRoute('admin_parametres_typepiece_index');
    }

    /**
     * Creates a form to delete a typeCarteGrise entity.
     *
     * @param TypeCarteGrise $typeCarteGrise The typeCarteGrise entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeCarteGrise $typeCarteGrise)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametres_typecartegrise_delete', array('id' => $typeCarteGrise->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Export all TypeCarteGrise entities.
     *
     * @Route("/admin/parametres/typecartegrise/download", name="typecartegrise_export")
     * @Method("GET")
     * 
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:TypeCarteGrise')->findAll();
        $date = new \DateTime("now");
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("2SInnovation")
            ->setTitle("TYPECARTEGRISE".$date->format('Y-m-d H:i:s'));
        $this->writeRapport($phpExcelObject, $entities);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $filename = 'TYPECARTEGRISE'.$date->format('Y_m_d_H_i_s').'.xls';
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
        $objWorksheet->getCellByColumnAndRow($col, 1)->setValue("LIBELLE");
        $ligne =2;
        foreach($entities as $entity){
            $col=0;
            $objWorksheet->getCellByColumnAndRow($col, $ligne)->setValue($entity->getLibelle());
            $ligne++;
        }
    }
}
