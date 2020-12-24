<?php

namespace IPNJ\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use IPNJ\AdminBundle\Entity\Iglesias;
use IPNJ\AdminBundle\Form\IglesiasType; 
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;




class IglesiasController extends Controller
{

    public function homeAction()
    {
        return $this->render('IPNJAdminBundle:Iglesias:home.html.twig');
    }
    
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
            $norte = $em->getRepository('IPNJAdminBundle:Iglesias')->findBy(
    array('zona' => 1 , 'role' => 'ROLE_USER'));
           
            $centro = $em->getRepository('IPNJAdminBundle:Iglesias')->findBy(
    array('zona' => 2 , 'role' => 'ROLE_USER'));
            $sur = $em->getRepository('IPNJAdminBundle:Iglesias')->findBy(
    array('zona' => 3 , 'role' => 'ROLE_USER'));

            $role = $em->getRepository('IPNJAdminBundle:Iglesias')->findBy(
    array('role' => 'ROLE_ADMIN'));

            
            
        return $this->render('IPNJAdminBundle:Iglesias:index.html.twig', array('norte' => $norte, 'centro' => $centro, 'sur' => $sur, 'role' => $role, ));
    }
       
    public function addAction()
    {
        $iglesia = new Iglesias();
        $form = $this->createCreateForm($iglesia);
        
        return $this->render('IPNJAdminBundle:Iglesias:add.html.twig', array('form' => $form->createView()));
    }
    
    private function createCreateForm(Iglesias $entity)
    {
        $form = $this->createForm(IglesiasType::class, $entity, array(
            'action' => $this->generateUrl('ipnj_iglesias_create'),
            'method' => 'POST'
        ));
        
        return $form;
    }
    
    public function createAction(Request $request)
    {
        $iglesia = new Iglesias();
        $form = $this->createCreateForm($iglesia);
        $form->handleRequest($request);
        
       if ($form->isSubmitted() && $form->isValid())
        {
            $password = $form->get('password')->getData();

                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($iglesia, $password);
                
                $iglesia->setPassword($encoded);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($iglesia);
            $em->flush();
            
        
            $this->addFlash('mensaje', 'Se ha añadido la nueva Sede o el usuario '. $iglesia .' a la IPNJ.');
            
            return $this->redirectToRoute('ipnj_iglesias_list');
        }
        
        return $this->render('IPNJAdminBundle:Iglesias:add.html.twig', array('form' => $form->createView()));
     }

 public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $iglesia = $em->getRepository('IPNJAdminBundle:Iglesias')->find($id);
        
        if(!$iglesia)
        {
            $messageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException($messageException);
        }
        
        $form = $this->createEditForm($iglesia);
        
        return $this->render('IPNJAdminBundle:Iglesias:edit.html.twig', array('iglesia' => $iglesia, 'form' => $form->createView()));
        
    }
     
    private function createEditForm(Iglesias $entity)
    {
        $form = $this->createForm(IglesiasType::class, $entity, array('action' => $this->generateUrl('ipnj_iglesias_update', array('id' => $entity->getId())), 'method' => 'PUT'));
        
        return $form;
    }
    


    
    public function updateAction($id, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $iglesia = $em->getRepository('IPNJAdminBundle:Iglesias')->find($id);
        $form = $this->createEditForm($iglesia);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            

        $password = $form->get('password')->getData();

            if(!empty($password))
            {
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($iglesia, $password);
                $iglesia->setPassword($encoded);
            }
            else
            {
                $recoverPass = $this->recoverPass($id);
                $iglesia->setPassword($recoverPass[0]['password']);                
            }

           
            $em->persist($iglesia);
            $em->flush();
            
            
            $this->addFlash('men', 'La Iglesia o el Usuario '. $iglesia .' ha sido modificado con éxito');


            return $this->redirectToRoute('ipnj_iglesias_list');
        }

        return $this->render('IPNJAdminBundle:Iglesias:edit.html.twig', array('iglesia' => $iglesia, 'form' => $form->createView()));
    }
    
public function recoverPass($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT u.password
            FROM IPNJAdminBundle:Iglesias u
            WHERE u.id = :id')->setParameter('id', $id);
        
        $currentPass = $query->getResult();
        
        return $currentPass;
    }
    
 


public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('IPNJAdminBundle:Iglesias');
        
        $iglesia = $repository->find($id);
        
       if(!$iglesia)
        {
            $messageException = $this->get('translator')->trans('User not found.');
            throw $this->createNotFoundException($messageException);
        }
        
     $deleteForm = $this->createDeleteForm($iglesia);
        
        return $this->render('IPNJAdminBundle:Iglesias:view.html.twig', array('iglesia' => $iglesia, 'delete_form' => $deleteForm->createView()));
    }

private function createDeleteForm($iglesia)
{
    return $this->createFormBuilder()
    ->setAction($this->generateUrl('ipnj_iglesias_delete', array('id' => $iglesia->getId())))
    ->setMethod('DELETE')
    ->getForm();
}

 public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $Iglesia = $em->getRepository('IPNJAdminBundle:Iglesias')->find($id);
        

        $form = $this->createDeleteForm($Iglesia);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $em->remove($Iglesia);
            $em->flush();

            
            $this->addFlash('mensaje', 'Se ha borrado el Usuario correctamente.');

            return $this->redirectToRoute('ipnj_iglesias_list');
        }


}






/*
//ESTO COSTO MUCHO NO LO BORRE NO SEA GUANABANA!!!!


public function editAction(Request $request, $id)
{
    $post = $this->getDoctrine()->getRepository('IPNJAdminBundle:Iglesias')->find($id);
 
    if(!$post)
    {
        return $this->redirectToRoute('ipnj_iglesias_list');
    }
 
    $form = $this->createForm(\IPNJ\AdminBundle\Form\IglesiasType::class, $post);
    //$form->add('save', SubmitType::class, array('label' => 'Update Post'));
 
    $form->handleRequest($request);
 
    if ($form->isSubmitted() && $form->isValid())
    {

        $password = $form->get('password')->getData();
        
        if(!empty($password))
            {

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($post, $password);
            $post->setPassword($encoded);
        }
        else
            {
                $recoverPass = $this->recoverPass($id);
                $iglesia->setPassword($recoverPass[0]['password']);                
            }

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
        return $this->redirectToRoute('ipnj_iglesias_edit', ["id" => $id]);
    }
 
    return $this->render('IPNJAdminBundle:Iglesias:edit.html.twig', ["form" => $form->createView()]);
}






*/
}