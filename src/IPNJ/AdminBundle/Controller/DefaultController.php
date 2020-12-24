<?php

namespace IPNJ\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('IPNJAdminBundle:Default:index.html.twig');
    }

   public function calculatorAction(Request $request)
    {

    	$form = $this->createFormBuilder()
        ->add('diezmo', TextType::class)
        ->add('local', TextType::class)
        ->add('cuota', TextType::class)
        ->add('send', SubmitType::class)   
        ->getForm();
 
    $form->handleRequest($request);
 
    if ($form->isSubmitted() && $form->isValid()) { 


$diezmo = $form->get("diezmo")->getData();
$local = $form->get("local")->getData();
$cuota = $form->get("cuota")->getData();

 $a= "10";
 $b="100";
 $d= "5";
   

 $dDiezmo = intval($diezmo * $a / $b);
 $c = ($diezmo - $dDiezmo);
 $aporteA = intval($c * $d / $b);
 $e = ($c - $aporteA);
 $solidario = intval($e * $a / $b);
 $f = ($e - $solidario);
 $fLocal= intval($f * $local / $b);
 $g = ($f - $fLocal);
 

$totalNacional = ($dDiezmo + $solidario + $cuota);
$h = ($totalNacional + $aporteA);
$totalPastor = ($diezmo - $h);


return $this->render('IPNJAdminBundle:Default:result.html.twig', array('cuota' => $cuota, 'diezmo' => $diezmo, 'dDiezmo' => $dDiezmo, 'aporteA' => $aporteA, 'solidario' => $solidario, 'fLocal' => $fLocal, 'totalPastor' => $totalPastor, 'totalNacional' => $totalNacional));


    }
    return $this->render('IPNJAdminBundle:Default:calculator.html.twig', array('form' => $form->createView()));
}

}
