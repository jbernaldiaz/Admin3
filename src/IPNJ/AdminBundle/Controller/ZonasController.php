<?php

namespace IPNJ\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use IPNJ\AdminBundle\Entity\Zonas;
use IPNJ\AdminBundle\Form\ZonasType; 
use Symfony\Component\HttpFoundation\ResponseHeaderBag;




class ZonasController extends Controller
{
    public function indexAction()
    {


          $em = $this->getDoctrine()->getManager();
        $zona = $em->getRepository('IPNJAdminBundle:Zonas')->findAll();
        
        return $this->render('IPNJAdminBundle:Zonas:index.html.twig', array('zona' => $zona));
    }
	

public function addAction()
    {
        $zona = new Zonas();
        $form = $this->createCreateForm($zona);
        
        return $this->render('IPNJAdminBundle:Zonas:add.html.twig', array('form' => $form->createView()));
    }
    
    private function createCreateForm(Zonas $entity)
    {
        $form = $this->createForm(ZonasType::class, $entity, array(
            'action' => $this->generateUrl('ipnj_zonas_create'),
            'method' => 'POST'
        ));
        
        return $form;
    }
    
    public function createAction(Request $request)
    {
        $zona = new Zonas();
        $form = $this->createCreateForm($zona);
        $form->handleRequest($request);
        
       if ($form->isSubmitted() && $form->isValid())
        {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($zona);
            $em->flush();
            
           
           return $this->redirectToRoute('ipnj_zonas_list');
        }
        
        return $this->render('IPNJAdminBundle:Zonas:add.html.twig', array('form' => $form->createView()));
    }

}