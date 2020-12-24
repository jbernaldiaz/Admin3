<?php

namespace IPNJ\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormError;
use IPNJ\AdminBundle\Entity\Iglesias;
use IPNJ\AdminBundle\Entity\Zonas;
use IPNJ\AdminBundle\Entity\Asistencia;
use IPNJ\AdminBundle\Form\AsistenciaType; 
use IPNJ\AdminBundle\Form\EnviosEditType; 
use IPNJ\AdminBundle\Form\AsistenciaEditType; 
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Symfony\Component\Form\Extension\Core\Type\DateType;




class AsistenciaController extends Controller
{


    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT u FROM IPNJAdminBundle:Asistencia u ORDER BY u.id DESC";
        $envio = $em->createQuery($dql); 

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $envio, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        5
        /*limit per page*/
    );


        return $this->render('IPNJAdminBundle:Asistencia:index.html.twig', array('pagination' => $pagination));


    }
    

public function customAction(Request $request)
    {
       $idUser = $this->get('security.token_storage')->getToken()->getUser()->getId();
      $user = $this->getUser()->getIglesia();

        $em = $this->getDoctrine()->getManager();
        $envio = $em->getRepository('IPNJAdminBundle:Asistencia')->findBy(array('iglesia' => $idUser), array('createAt' => 'DESC')); 

        

        return $this->render('IPNJAdminBundle:Asistencia:custom.html.twig', array('user' => $user,'envio' => $envio));
    }



public function zonaAction(Request $request)
    {
        $zonaUser = $this->get('security.token_storage')->getToken()->getUser()->getZona();

        $user = $this->getUser()->getIglesia();

        $em = $this->getDoctrine()->getManager();
        $envio = $em->getRepository('IPNJAdminBundle:Asistencia')->findBy(array('zona' => $zonaUser), array('createAt' => 'DESC')); 

        

        return $this->render('IPNJAdminBundle:Asistencia:zona.html.twig', array('zonaUser' => $zonaUser, 'user' => $user,'envio' => $envio));
    }


   
 private function getYears($min, $max='current')
    {
         $years = range($min, ($max === 'current' ? date('Y') : $max));

         return array_combine($years, $years);
    }

public function reportAction(Request $request)
{


    $form = $this->createFormBuilder()
        ->add('aporte', ChoiceType::class, array('choices' => array(
            'Aporte Voluntario 1'     => 'aporte_a' , 
            'Aportes Voluntario 2'   => 'aporte_b'
            )))
    
/*->add('anio', EntityType::class, array(
    'class' => 'IPNJAdminBundle:Asistencia',
    'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('u')
            ->orderBy('u.iglesia', 'ASC');
    },
    'choice_label' => 'iglesia',
))*/

        ->add('anio', ChoiceType::class, array('choices' => $this->getYears(2018, 2025)))
        ->add('save', SubmitType::class)   
        ->getForm();
 
    $form->handleRequest($request);
 
    if ($form->isSubmitted() && $form->isValid()) { 


$aporte = $form->get("aporte")->getData();
$anio = $form->get("anio")->getData();

$em = $this->getDoctrine()->getManager();
    $db = $em->getConnection();

$concat = "GROUP_CONCAT(if(mes = 'Enero'," . $aporte . ", NULL)) as 'a',
    GROUP_CONCAT(if(mes = 'Febrero', " . $aporte . ", NULL)) as 'b', 
    GROUP_CONCAT(if(mes = 'Marzo'," . $aporte . ", NULL)) as 'c',
    GROUP_CONCAT(if(mes = 'Abril'," . $aporte . ", NULL)) as 'd',
    GROUP_CONCAT(if(mes = 'Mayo'," . $aporte . ", NULL)) as 'e',
    GROUP_CONCAT(if(mes = 'Junio'," . $aporte . ", NULL)) as 'f',
    GROUP_CONCAT(if(mes = 'Julio'," . $aporte . ", NULL)) as 'g',
    GROUP_CONCAT(if(mes = 'Agosto'," . $aporte . ", NULL)) as 'h',
    GROUP_CONCAT(if(mes = 'Septiembre'," . $aporte . ", NULL)) as 'i',
    GROUP_CONCAT(if(mes = 'Octubre'," . $aporte . ", NULL)) as 'j',
    GROUP_CONCAT(if(mes = 'Noviembre'," . $aporte . ", NULL)) as 'k',
    GROUP_CONCAT(if(mes = 'Diciembre'," . $aporte . ", NULL)) as 'l'";

    $query = "SELECT I.iglesia, " .$concat. "
    FROM asistencia E INNER JOIN iglesias I ON I.id = E.iglesia_id
    WHERE E.anio_at = " . $anio . " AND E.zona_id = '1'
    GROUP BY E.iglesia_id";
    $stmt = $db->prepare($query);
    $params = array();
    $stmt->execute($params);

    $queryCentro = "SELECT I.iglesia," .$concat. "
    FROM asistencia E INNER JOIN iglesias I ON I.id = E.iglesia_id
    WHERE E.anio_at = " . $anio . " AND E.zona_id = '2'
    GROUP BY E.iglesia_id";
    $stmtCentro = $db->prepare($queryCentro);
    $paramsCentro = array();
    $stmtCentro->execute($paramsCentro);
    
    $querySur = "SELECT I.iglesia, " .$concat. "
    FROM asistencia E INNER JOIN iglesias I ON I.id = E.iglesia_id
    WHERE E.anio_at = " . $anio . " AND E.zona_id = '3'
    GROUP BY E.iglesia_id";
    $stmtSur = $db->prepare($querySur);
    $paramsSur = array();
    $stmtSur->execute($paramsSur);

    
if($aporte === 'aporte_a'){
    
    $aportes = 'Aporte Voluntario 1';
    $enviosUnoNorte = $stmt->fetchAll();
    $enviosNorte = $enviosUnoNorte;
    $enviosUnoCentro = $stmtCentro->fetchAll();
    $enviosCentro = $enviosUnoCentro;  
    $enviosUnoSur = $stmtSur->fetchAll();
    $enviosSur = $enviosUnoSur;
}
if($aporte === 'aporte_b'){
    
    $aportes = 'Aporte Voluntario 2';
    $enviosDosNorte = $stmt->fetchAll();
    $enviosNorte = $enviosDosNorte;
    $enviosDosCentro = $stmtCentro->fetchAll();
    $enviosCentro = $enviosDosCentro;
    $enviosDosSur = $stmtSur->fetchAll();
    $enviosSur = $enviosDosSur;
} 



    
 return $this->render('IPNJAdminBundle:Asistencia:reporte.html.twig', array('aportes' => $aportes, 'aporte' => $aporte, 'anio' => $anio, 'enviosNorte' => $enviosNorte,'enviosCentro' => $enviosCentro, 'enviosSur' => $enviosSur));
    
}
 
     return $this->render('IPNJAdminBundle:Asistencia:report.html.twig', array('form' => $form->createView()));
}



    public function addAction()
    {
      $envio = new Asistencia();
      $form = $this->createCreateForm($envio);

      return $this->render('IPNJAdminBundle:Asistencia:add.html.twig', array('form' => $form->createView()));
    }



    private function createCreateForm(Asistencia $entity)
    {
      $form = $this->createForm(AsistenciaType::class, $entity, array(
        'userLogged' => $this->getUser()->getId(),
        'zone' => $this->getUser()->getZona(), 
        'action' => $this->generateUrl('ipnj_asistencia_create'),
        'method' => 'POST'));

      
      return $form;
    }
 
    public function createAction(Request $request)
    {
      $envio = new Asistencia();
      $form = $this->createCreateForm($envio);
      $form->handleRequest($request);


      if ($form->isSubmitted() && $form->isValid())
      {
         
        $em = $this->getDoctrine()->getManager();
          $em->persist($envio);
          $em->flush();
             
          
                
          $this->addFlash('msnEnvios', 'Se ha enviado los detalles con exito.');

          $repository = $this->getDoctrine()->getRepository('IPNJAdminBundle:Asistencia');
            
          $envio = $repository->find($envio);
          $id = $repository->findById($envio);
            
        return $this->render('IPNJAdminBundle:Asistencia:view.html.twig', array('id' => $id, 'envio' => $envio));
               
 
      }else{

        return $this->render('IPNJAdminBundle:Asistencia:add.html.twig', array('form' => $form->createView()));
       }
    }



    public function viewAction($id)
        {
            $repository = $this->getDoctrine()->getRepository('IPNJAdminBundle:Asistencia');
            
            $envio = $repository->find($id);
            
            
            return $this->render('IPNJAdminBundle:Asistencia:view.html.twig', array('id' => $id, 'envio' => $envio));
        }

    



public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $envio = $em->getRepository('IPNJAdminBundle:Asistencia')->find($id);
        
        
        $form = $this->createAsistenciaEditForm($envio);
        
        return $this->render('IPNJAdminBundle:Asistencia:edit.html.twig', array('envio' => $envio, 'form' => $form->createView()));
        
    }
     
    private function createAsistenciaEditForm(Asistencia $entity)
    {


        $form = $this->createForm(AsistenciaEditType::class, $entity, array(
            'userLogged' => $entity->getIglesia(),
            'zone' => $entity->getZona(),
            'action' => $this->generateUrl('ipnj_asistencia_update', array('id' => $entity->getId())), 
            'method' => 'PUT'));
        
        return $form;
    }
    


    
    public function updateAction($id, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $envio = $em->getRepository('IPNJAdminBundle:Asistencia')->find($id);
        $form = $this->createAsistenciaEditForm($envio);
        $form->handleRequest($request);
        
          

        if($form->isSubmitted() && $form->isValid())
        {           
            $em->persist($envio);
            $em->flush();
            
            
            $this->addFlash('editarEnvio', 'El envio numero ' .$id. ' ha sido modificado con éxito');

            return $this->redirectToRoute('ipnj_asistencia_list');
        }

        return $this->render('IPNJAdminBundle:Asistencia:edit.html.twig', array('envio' => $envio, 'form' => $form->createView()));
    }







}
