<?php

namespace IPNJ\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormError;
use IPNJ\AdminBundle\Entity\Iglesias;
use IPNJ\AdminBundle\Entity\Zonas;
use IPNJ\AdminBundle\Entity\Envios;
use IPNJ\AdminBundle\Form\EnviosType; 
use IPNJ\AdminBundle\Form\EnviosEditType; 
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Symfony\Component\Form\Extension\Core\Type\DateType;




class EnviosController extends Controller
{


    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT u FROM IPNJAdminBundle:Envios u ORDER BY u.id DESC";
        $envio = $em->createQuery($dql); 

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $envio, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        5
        /*limit per page*/
    );


        return $this->render('IPNJAdminBundle:Envios:index.html.twig', array('pagination' => $pagination));


    }
    

public function customAction(Request $request)
    {
       $idUser = $this->get('security.token_storage')->getToken()->getUser()->getId();
      $user = $this->getUser()->getIglesia();

        $em = $this->getDoctrine()->getManager();
        $envio = $em->getRepository('IPNJAdminBundle:Envios')->findBy(array('iglesia' => $idUser), array('createAt' => 'DESC')); 

        

        return $this->render('IPNJAdminBundle:Envios:custom.html.twig', array('user' => $user,'envio' => $envio));
    }



public function zonaAction(Request $request)
    {
        $zonaUser = $this->get('security.token_storage')->getToken()->getUser()->getZona();

        $user = $this->getUser()->getIglesia();

        $em = $this->getDoctrine()->getManager();
        $envio = $em->getRepository('IPNJAdminBundle:Envios')->findBy(array('zona' => $zonaUser), array('createAt' => 'DESC')); 

        

        return $this->render('IPNJAdminBundle:Envios:zona.html.twig', array('zonaUser' => $zonaUser, 'user' => $user,'envio' => $envio));
    }


   
 private function getYears($min, $max='current')
    {
         $years = range($min, ($max === 'current' ? date('Y') : $max));

         return array_combine($years, $years);
    }

public function reportAction(Request $request)
{
    



    $form = $this->createFormBuilder()
        ->add('ofrenda', ChoiceType::class, array('choices' => array(
            'Misionera'     => 'misionera' , 
            'Gavillas'   => 'gavillas', 
            'Rayos'     => 'rayos',
            'FMN' => 'fmn'
            )))
    
/*->add('anio', EntityType::class, array(
    'class' => 'IPNJAdminBundle:Envios',
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


$ofrenda = $form->get("ofrenda")->getData();
$anio = $form->get("anio")->getData();

$em = $this->getDoctrine()->getManager();
    $db = $em->getConnection();

$concat = "GROUP_CONCAT(if(mes = 'Enero'," . $ofrenda . ", NULL)) as 'a',
    GROUP_CONCAT(if(mes = 'Febrero', " . $ofrenda . ", NULL)) as 'b', 
    GROUP_CONCAT(if(mes = 'Marzo'," . $ofrenda . ", NULL)) as 'c',
    GROUP_CONCAT(if(mes = 'Abril'," . $ofrenda . ", NULL)) as 'd',
    GROUP_CONCAT(if(mes = 'Mayo'," . $ofrenda . ", NULL)) as 'e',
    GROUP_CONCAT(if(mes = 'Junio'," . $ofrenda . ", NULL)) as 'f',
    GROUP_CONCAT(if(mes = 'Julio'," . $ofrenda . ", NULL)) as 'g',
    GROUP_CONCAT(if(mes = 'Agosto'," . $ofrenda . ", NULL)) as 'h',
    GROUP_CONCAT(if(mes = 'Septiembre'," . $ofrenda . ", NULL)) as 'i',
    GROUP_CONCAT(if(mes = 'Octubre'," . $ofrenda . ", NULL)) as 'j',
    GROUP_CONCAT(if(mes = 'Noviembre'," . $ofrenda . ", NULL)) as 'k',
    GROUP_CONCAT(if(mes = 'Diciembre'," . $ofrenda . ", NULL)) as 'l'";

    $query = "SELECT I.iglesia, " .$concat. "
    

    FROM envios E INNER JOIN iglesias I ON I.id = E.iglesia_id
    WHERE E.anio_at = " . $anio . " AND E.zona_id = '1'
    GROUP BY E.iglesia_id";
    $stmt = $db->prepare($query);
    $params = array();
    $stmt->execute($params);

if($ofrenda === 'misionera'){

    $enviosMisioNorte = $stmt->fetchAll();
    $enviosNorte = $enviosMisioNorte;

}
if($ofrenda === 'gavillas'){

    $enviosGavillasNorte = $stmt->fetchAll();
    $enviosNorte = $enviosGavillasNorte;

} 
if($ofrenda === 'rayos'){

    $enviosRayosNorte = $stmt->fetchAll();
        $enviosNorte = $enviosRayosNorte;

}     
if($ofrenda === 'fmn'){

    $enviosFmnNorte = $stmt->fetchAll();
        $enviosNorte = $enviosFmnNorte;

}   

$queryCentro = "SELECT I.iglesia," .$concat. "
    FROM envios E INNER JOIN iglesias I ON I.id = E.iglesia_id
    WHERE E.anio_at = " . $anio . " AND E.zona_id = '2'
    GROUP BY E.iglesia_id";
    $stmtCentro = $db->prepare($queryCentro);
    $paramsCentro = array();
    $stmtCentro->execute($paramsCentro);


if($ofrenda === 'misionera'){

    $enviosMisioCentro = $stmtCentro->fetchAll();
    $enviosCentro = $enviosMisioCentro;
}
if($ofrenda === 'gavillas'){

    $enviosGavillasCentro = $stmtCentro->fetchAll();
    $enviosCentro = $enviosGavillasCentro;
} 
if($ofrenda === 'rayos'){

    $enviosRayosCentro = $stmtCentro->fetchAll();
    $enviosCentro = $enviosRayosCentro;

} 
if($ofrenda === 'fmn'){

    $enviosFmnCentro = $stmtCentro->fetchAll();
        $enviosCentro = $enviosFmnCentro;

}       
    
    $querySur = "SELECT I.iglesia, " .$concat. "
    FROM envios E INNER JOIN iglesias I ON I.id = E.iglesia_id
    WHERE E.anio_at = " . $anio . " AND E.zona_id = '3'
    GROUP BY E.iglesia_id";
    $stmtSur = $db->prepare($querySur);
    $paramsSur = array();
    $stmtSur->execute($paramsSur);

if($ofrenda === 'misionera'){

    $enviosMisioSur = $stmtSur->fetchAll();
    $enviosSur = $enviosMisioSur;
    $ofrendas = 'Misionera';
}
if($ofrenda === 'gavillas'){

    $enviosGavillasSur = $stmtSur->fetchAll();
    $enviosSur = $enviosGavillasSur;
    $ofrendas = 'Gavillas';

} 
if($ofrenda === 'rayos'){

    $enviosRayosSur = $stmtSur->fetchAll();
    $enviosSur = $enviosRayosSur;
    $ofrendas = 'Rayos';
} 
if($ofrenda === 'fmn'){

    $enviosFmnSur = $stmtSur->fetchAll();
        $enviosSur = $enviosFmnSur;
        $ofrendas = 'Fmn';

}
    
    
    
 return $this->render('IPNJAdminBundle:Envios:reporte.html.twig', array('ofrendas' => $ofrendas, 'ofrenda' => $ofrenda, 'anio' => $anio, 'enviosNorte' => $enviosNorte,'enviosCentro' => $enviosCentro, 'enviosSur' => $enviosSur));
    
}
 
     return $this->render('IPNJAdminBundle:Envios:report.html.twig', array('form' => $form->createView()));
}



    public function addAction()
    {
      $envio = new Envios();
      $form = $this->createCreateForm($envio);

      return $this->render('IPNJAdminBundle:Envios:add.html.twig', array('form' => $form->createView()));
    }



    private function createCreateForm(Envios $entity)
    {
      $form = $this->createForm(EnviosType::class, $entity, array(
        'userLogged' => $this->getUser()->getId(),
        'zone' => $this->getUser()->getZona(), 
        'action' => $this->generateUrl('ipnj_envios_create'),
        'method' => 'POST'));

      
      return $form;
    }
 
    public function createAction(Request $request)
    {
      $envio = new Envios();
      $form = $this->createCreateForm($envio);
      $form->handleRequest($request);


      if ($form->isSubmitted() && $form->isValid())
      {
         
        $em = $this->getDoctrine()->getManager();
          $em->persist($envio);
          $em->flush();
             
          
                
          $this->addFlash('msnEnvios', 'Se ha enviado los detalles con exito.');

          $repository = $this->getDoctrine()->getRepository('IPNJAdminBundle:Envios');
            
          $envio = $repository->find($envio);
          $id = $repository->findById($envio);
            
        return $this->render('IPNJAdminBundle:Envios:view.html.twig', array('id' => $id, 'envio' => $envio));
               
 
      }else{

        return $this->render('IPNJAdminBundle:Envios:add.html.twig', array('form' => $form->createView()));
       }
    }



    public function viewAction($id)
        {
            $repository = $this->getDoctrine()->getRepository('IPNJAdminBundle:Envios');
            
            $envio = $repository->find($id);
            
            
            return $this->render('IPNJAdminBundle:Envios:view.html.twig', array('id' => $id, 'envio' => $envio));
        }

    



public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $envio = $em->getRepository('IPNJAdminBundle:Envios')->find($id);
        
        
        $form = $this->createEditForm($envio);
        
        return $this->render('IPNJAdminBundle:Envios:edit.html.twig', array('envio' => $envio, 'form' => $form->createView()));
        
    }
     
    private function createEditForm(Envios $entity)
    {


        $form = $this->createForm(EnviosEditType::class, $entity, array(
            'userLogged' => $entity->getIglesia(),
            'zone' => $entity->getZona(),
            'action' => $this->generateUrl('ipnj_envios_update', array('id' => $entity->getId())), 
            'method' => 'PUT'));
        
        return $form;
    }
    


    
    public function updateAction($id, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $envio = $em->getRepository('IPNJAdminBundle:Envios')->find($id);
        $form = $this->createEditForm($envio);
        $form->handleRequest($request);
        
          

        if($form->isSubmitted() && $form->isValid())
        {           
            $em->persist($envio);
            $em->flush();
            
            
            $this->addFlash('editarEnvio', 'El envio numero ' .$id. ' ha sido modificado con éxito');

            return $this->redirectToRoute('ipnj_envios_list');
        }

        return $this->render('IPNJAdminBundle:Envios:edit.html.twig', array('envio' => $envio, 'form' => $form->createView()));
    }







}
