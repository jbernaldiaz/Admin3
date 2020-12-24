<?php

namespace IPNJ\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class ExcelController extends Controller
{



public function ExcelAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('IPNJAdminBundle:Envios');
        
        $envio = $repository->findById($id);


        // solicitamos el servicio 'phpexcel' y creamos el objeto vacío...
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        // ...y le asignamos una serie de propiedades
        $phpExcelObject->getProperties()
            ->setCreator("Vabadus")
            ->setLastModifiedBy("Vabadus")
            ->setTitle("Ejemplo de exportación")
            ->setSubject("Ejemplo")
            ->setDescription("Listado de ejemplo.")
            ->setKeywords("vabadus exportar excel ejemplo");

        // establecemos como hoja activa la primera, y le asignamos un título
        $phpExcelObject->setActiveSheetIndex();
        $phpExcelObject->getActiveSheet()->setTitle('Tesoreria Nacional');
        
        // escribimos en distintas celdas del documento el título de los campos que vamos a exportar
        $phpExcelObject->setActiveSheetIndex()
            ->setCellValue('B2', 'Informacion de la transacción')
            ->setCellValue('D2', 'Detalles del Envio')
            ->setCellValue('B3', 'Iglesia')
            ->setCellValue('B4', 'Zona')
            ->setCellValue('B5', 'Año')
            ->setCellValue('B6', 'Mes')
            ->setCellValue('B7', 'Fecha')
            ->setCellValue('B8', 'Operacion')
            ->setCellValue('B9', 'Cajero')
            ->setCellValue('D3', 'Diezmo de Diezmo')
            ->setCellValue('D4', 'Fondo Solidario')
            ->setCellValue('D5', 'Cuota')
            ->setCellValue('D6', 'Diezmo Personal')
            ->setCellValue('D7', 'O. Misionera')
            ->setCellValue('D8', 'O. Rayos')
            ->setCellValue('D9', 'O. Gavillas')
            ->setCellValue('D10', 'FMN')
            ->setCellValue('D11', 'Total')
            ;


// fijamos el Estilo a columnas y celdas
$style = array('font' => array('size' => 14,'bold' => true,'color' => array('rgb' => 'FFFFFF')));
$font = array('font' => array('size' => 13));
$backgrund =array('type' => \PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => '0A5E7C'));
$left = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
$center = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$right = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));   



        
$phpExcelObject->setActiveSheetIndex()->getDefaultStyle()->applyFromArray($font);
$phpExcelObject->setActiveSheetIndex()->getStyle('B2:E2')->applyFromArray($style);
$phpExcelObject->setActiveSheetIndex()->getStyle('B2:E2')->getFill()->applyFromArray($backgrund);
$phpExcelObject->setActiveSheetIndex()->getStyle('C3:C11')->applyFromArray($right);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getStyle('B2:E2')
    ->getBorders()
    ->getTop()
    ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getStyle('B11:E11')
    ->getBorders()
    ->getBottom()
    ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getStyle('B2:B11')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getStyle('E2:E11')
    ->getBorders()
    ->getRight()
    ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getColumnDimension('B')
    ->setWidth(12);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getColumnDimension('C')
    ->setWidth(15);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getColumnDimension('D')
    ->setWidth(20);
    
$phpExcelObject
    ->setActiveSheetIndex()
    ->getColumnDimension('E')
    ->setWidth(10);

        // recorremos los registros obtenidos de la consulta a base de datos escribiéndolos en las celdas correspondientes
        
        foreach ($envio as $valor) {
            $phpExcelObject->setActiveSheetIndex()
                ->setCellValue('C3', $valor->getIglesia())
                ->setCellValue('C4', $valor->getZona())
                ->setCellValue('C5', $valor->getAnio()->format('Y'))
                ->setCellValue('C6', $valor->getMes())
                ->setCellValue('C7', $valor->getFechaAt()->format("Y-m-d"))
                ->setCellValue('C8', $valor->getOperacion())
                ->setCellValue('C9', $valor->getCajero())

                ->setCellValue('E3', $valor->getDDiezmo())
                ->setCellValue('E4', $valor->getFSolidario())
                ->setCellValue('E5', $valor->getCuota())
                ->setCellValue('E6', $valor->getDPersonal())
                ->setCellValue('E7', $valor->getMisionera())
                ->setCellValue('E8', $valor->getRayos())
                ->setCellValue('E9', $valor->getGavillas()) 
                ->setCellValue('E10', $valor->getFmn())     
                ->setCellValue('E11', $valor->getTotal())          
                ;

            
        }

        // se crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // se crea el response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // y por último se añaden las cabeceras
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Envio ' .$valor->getIglesia(). ' ' .$valor->getMes(). ' ' .$valor->getAnio()->format('Y'). '.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }



public function reportExcelAction($ofrenda, $anio)
    {
    

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
    WHERE E.anio_at = ". $anio ." AND E.zona_id = '1'
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


$queryCentro = "SELECT I.iglesia, " .$concat. "

    FROM envios E INNER JOIN iglesias I ON I.id = E.iglesia_id
    WHERE E.anio_at = ". $anio ." AND E.zona_id = '2'
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

    $querySur = "SELECT I.iglesia, " .$concat. "

    FROM envios E INNER JOIN iglesias I ON I.id = E.iglesia_id
    WHERE E.anio_at = ". $anio ." AND E.zona_id = '3'
    GROUP BY E.iglesia_id";
    $stmtSur = $db->prepare($querySur);
    $paramsSur = array();
    $stmtSur->execute($paramsSur);

if($ofrenda === 'misionera'){

    $enviosMisioSur = $stmtSur->fetchAll();
    $enviosSur = $enviosMisioSur;
}
if($ofrenda === 'gavillas'){

    $enviosGavillasSur = $stmtSur->fetchAll();
    $enviosSur = $enviosGavillasSur;

} 
if($ofrenda === 'rayos'){

    $enviosRayosSur = $stmtSur->fetchAll();
    $enviosSur = $enviosRayosSur;
} 

    

        // solicitamos el servicio 'phpexcel' y creamos el objeto vacío...
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        // ...y le asignamos una serie de propiedades
        $phpExcelObject->getProperties()
            ->setCreator("Vabadus")
            ->setLastModifiedBy("Vabadus")
            ->setTitle("Ejemplo de exportación")
            ->setSubject("Ejemplo")
            ->setDescription("Listado de ejemplo.")
            ->setKeywords("vabadus exportar excel ejemplo");

        // establecemos como hoja activa la primera, y le asignamos un título
        $phpExcelObject->setActiveSheetIndex();
        $phpExcelObject->getActiveSheet()->setTitle('Tesoreria Nacional');
        
        if($ofrenda === 'misionera')
        {
            $ofrendas = 'Misionera';
        }
        if($ofrenda === 'gavillas')
        {
            $ofrendas = 'Gavillas';
        } 
        if($ofrenda === 'rayos')
        {
            $ofrendas = 'Rayos';
        } 
        // escribimos en distintas celdas del documento el título de los campos que vamos a exportar
        $phpExcelObject->setActiveSheetIndex()
            
            ->setCellValue('A1', 'IGLESIA')
            ->setCellValue('B1', 'Ene')
            ->setCellValue('C1', 'Feb')
            ->setCellValue('D1', 'Mar')
            ->setCellValue('E1', 'Abr')
            ->setCellValue('F1', 'May')
            ->setCellValue('G1', 'Jun')
            ->setCellValue('H1', 'Jul')
            ->setCellValue('I1', 'Ago')
            ->setCellValue('J1', 'Sep')
            ->setCellValue('K1', 'Oct')
            ->setCellValue('L1', 'Nov')
            ->setCellValue('M1', 'Dic')
            ->setCellValue('N1', 'Total')
            ->setCellValue('A2', 'Ofrenda ' .$ofrendas. ' Zona Norte')
            ;


// fijamos el Estilo a columnas y celdas
$style = array('font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF')));
$font = array('font' => array('size' => 13));
$backgrund =array('type' => \PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => '0A5E7C'));
$left = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
$center = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$right = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));   



$phpExcelObject->setActiveSheetIndex()->mergeCells('A2:N2');
$phpExcelObject->setActiveSheetIndex()->getDefaultStyle()->applyFromArray($font);
$phpExcelObject->setActiveSheetIndex()->getStyle('A1:N1')->applyFromArray($style);
$phpExcelObject->setActiveSheetIndex()->getStyle('A1:N1')->applyFromArray($center);
$phpExcelObject->setActiveSheetIndex()->getStyle('A2:N2')->applyFromArray($center);
$phpExcelObject->setActiveSheetIndex()->getStyle('A1:N1')->getFill()->applyFromArray($backgrund);
$phpExcelObject->setActiveSheetIndex()->getStyle('C3:C11')->applyFromArray($right);


$phpExcelObject
    ->setActiveSheetIndex()
    ->getColumnDimension('A')
    ->setWidth(20);


        // recorremos los registros obtenidos de la consulta a base de datos escribiéndolos en las celdas correspondientes
        $i = 3;
        $a = 3;
        $b = 3;
        $c = 3;
        $d = 3;
        $e = 3;
        $f = 3;
        $g = 3;
        $h = 3;
        $is = 3;
        $j = 3;
        $k = 3;
        $l = 3;
        $m = 3;



        foreach ($enviosNorte as $valor) 
        {
            $totalNorte = $valor['a']+$valor['b']+$valor['c']+$valor['d']+$valor['e']+$valor['f']+$valor['g']+$valor['h']+$valor['i']+$valor['j']+$valor['k']+$valor['l'];

            $phpExcelObject->setActiveSheetIndex()->setCellValue('A'.$i++, $valor['iglesia']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('B'.$a++, $valor['a']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('C'.$b++, $valor['b']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('D'.$c++, $valor['c']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('E'.$d++, $valor['d']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('F'.$e++, $valor['e']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('G'.$f++, $valor['f']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('H'.$g++, $valor['g']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('I'.$h++, $valor['h']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('J'.$is++, $valor['i']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('K'.$j++, $valor['j']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('L'.$k++, $valor['k']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('M'.$l++, $valor['l']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('N'.$m++, $totalNorte);
            
        }

        
        foreach ($enviosCentro as $valor) 
        {   
            $totalCentro = $valor['a']+$valor['b']+$valor['c']+$valor['d']+$valor['e']+$valor['f']+$valor['g']+$valor['h']+$valor['i']+$valor['j']+$valor['k']+$valor['l'];

            $phpExcelObject->setActiveSheetIndex()->setCellValue('A'.$i++, $valor['iglesia']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('B'.$a++, $valor['a']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('C'.$b++, $valor['b']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('D'.$c++, $valor['c']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('E'.$d++, $valor['d']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('F'.$e++, $valor['e']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('G'.$f++, $valor['f']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('H'.$g++, $valor['g']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('I'.$h++, $valor['h']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('J'.$is++, $valor['i']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('K'.$j++, $valor['j']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('L'.$k++, $valor['k']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('M'.$l++, $valor['l']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('N'.$m++, $totalCentro);
        }

        foreach ($enviosSur as $valor) 
        {


            $totalSur = $valor['a']+$valor['b']+$valor['c']+$valor['d']+$valor['e']+$valor['f']+$valor['g']+$valor['h']+$valor['i']+$valor['j']+$valor['k']+$valor['l'];
            $phpExcelObject->setActiveSheetIndex()->setCellValue('A'.$i++, $valor['iglesia']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('B'.$a++, $valor['a']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('C'.$b++, $valor['b']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('D'.$c++, $valor['c']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('E'.$d++, $valor['d']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('F'.$e++, $valor['e']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('G'.$f++, $valor['f']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('H'.$g++, $valor['g']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('I'.$h++, $valor['h']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('J'.$is++, $valor['i']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('K'.$j++, $valor['j']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('L'.$k++, $valor['k']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('M'.$l++, $valor['l']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('N'.$m++, $totalSur);
        }
        


        // se crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // se crea el response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // y por último se añaden las cabeceras
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Reporte Ofreda ' .$ofrenda. ' del '.$anio.'.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }


/**
 * @return \TFox\MpdfPortBundle\Service\PDFService
 */
private function getMpdfService()
{
  return $this->get('t_fox_mpdf_port.pdf');
}




public function reportPdfAction($ofrenda, $anio)
    { 

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
    WHERE E.anio_at = ". $anio ." AND E.zona_id = '1'
    GROUP BY E.iglesia_id";
    $stmt = $db->prepare($query);
    $params = array();
    $stmt->execute($params);


$queryCentro = "SELECT I.iglesia, " .$concat. "

    FROM envios E INNER JOIN iglesias I ON I.id = E.iglesia_id
    WHERE E.anio_at = ". $anio ." AND E.zona_id = '2'
    GROUP BY E.iglesia_id";
    $stmtCentro = $db->prepare($queryCentro);
    $paramsCentro = array();
    $stmtCentro->execute($paramsCentro);


    $querySur = "SELECT I.iglesia, " .$concat. "

    FROM envios E INNER JOIN iglesias I ON I.id = E.iglesia_id
    WHERE E.anio_at = ". $anio ." AND E.zona_id = '3'
    GROUP BY E.iglesia_id";
    $stmtSur = $db->prepare($querySur);
    $paramsSur = array();
    $stmtSur->execute($paramsSur);

if($ofrenda === 'misionera'){

    $enviosMisioSur = $stmtSur->fetchAll();
    $enviosSur = $enviosMisioSur;
    $enviosMisioNorte = $stmt->fetchAll();
    $enviosNorte = $enviosMisioNorte;
    $enviosMisioCentro = $stmtCentro->fetchAll();
    $enviosCentro = $enviosMisioCentro;
    $ofrendas = 'Misionera';
}
if($ofrenda === 'gavillas'){

    $enviosGavillasSur = $stmtSur->fetchAll();
    $enviosSur = $enviosGavillasSur;
    $enviosGavillasNorte = $stmt->fetchAll();
    $enviosNorte = $enviosGavillasNorte;
    $enviosGavillasCentro = $stmtCentro->fetchAll();
    $enviosCentro = $enviosGavillasCentro;
    $ofrendas = 'Gavillas';

} 
if($ofrenda === 'rayos'){

    $enviosRayosSur = $stmtSur->fetchAll();
    $enviosSur = $enviosRayosSur;
    $enviosRayosCentro = $stmtCentro->fetchAll();
    $enviosCentro = $enviosRayosCentro;
    $enviosRayosNorte = $stmt->fetchAll();
    $enviosNorte = $enviosRayosNorte;
    $ofrendas = 'Rayos';
} 


    $response = $this->render('IPNJAdminBundle:Envios:reportePdf.html.twig', array('ofrendas' => $ofrendas, 'ofrenda' => $ofrenda, 'anio' => $anio, 'enviosNorte' => $enviosNorte,'enviosCentro' => $enviosCentro, 'enviosSur' => $enviosSur));


        return new \TFox\MpdfPortBundle\Response\PDFResponse($this->getMpdfService()->generatePdf($response, array('format' => 'A4-L')));
    }

   public function reciboPdfAction($id)
        {
            $repository = $this->getDoctrine()->getRepository('IPNJAdminBundle:Envios');
            
            $envio = $repository->find($id);
            
            
            $response = $this->render('IPNJAdminBundle:Envios:reciboPdf.html.twig', array('envio' => $envio));


        return new \TFox\MpdfPortBundle\Response\PDFResponse($this->getMpdfService()->generatePdf($response));
        }











public function AsistenciaExcelAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('IPNJAdminBundle:Asistencia');
        
        $envio = $repository->findById($id);


        // solicitamos el servicio 'phpexcel' y creamos el objeto vacío...
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        // ...y le asignamos una serie de propiedades
        $phpExcelObject->getProperties()
            ->setCreator("Vabadus")
            ->setLastModifiedBy("Vabadus")
            ->setTitle("Ejemplo de exportación")
            ->setSubject("Ejemplo")
            ->setDescription("Listado de ejemplo.")
            ->setKeywords("vabadus exportar excel ejemplo");

        // establecemos como hoja activa la primera, y le asignamos un título
        $phpExcelObject->setActiveSheetIndex();
        $phpExcelObject->getActiveSheet()->setTitle('Tesoreria Nacional');
        
        // escribimos en distintas celdas del documento el título de los campos que vamos a exportar
        $phpExcelObject->setActiveSheetIndex()
            ->setCellValue('B2', 'Informacion de la transacción')
            ->setCellValue('D2', 'Detalles del Envio')
            ->setCellValue('B3', 'Iglesia')
            ->setCellValue('B4', 'Zona')
            ->setCellValue('B5', 'Año')
            ->setCellValue('B6', 'Mes')
            ->setCellValue('B7', 'Fecha')
            ->setCellValue('B8', 'Operacion')
            ->setCellValue('B9', 'Cajero')
            ->setCellValue('D3', 'A. Voluntario 1')
            ->setCellValue('D4', 'A. Voluntario 2')
            ->setCellValue('D5', 'Total')

            ;


// fijamos el Estilo a columnas y celdas
$style = array('font' => array('size' => 14,'bold' => true,'color' => array('rgb' => 'FFFFFF')));
$font = array('font' => array('size' => 13));
$backgrund =array('type' => \PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => '0A5E7C'));
$left = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
$center = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$right = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));   



        
$phpExcelObject->setActiveSheetIndex()->getDefaultStyle()->applyFromArray($font);
$phpExcelObject->setActiveSheetIndex()->getStyle('B2:E2')->applyFromArray($style);
$phpExcelObject->setActiveSheetIndex()->getStyle('B2:E2')->getFill()->applyFromArray($backgrund);
$phpExcelObject->setActiveSheetIndex()->getStyle('C3:C11')->applyFromArray($right);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getStyle('B2:E2')
    ->getBorders()
    ->getTop()
    ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getStyle('B11:E11')
    ->getBorders()
    ->getBottom()
    ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getStyle('B2:B11')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getStyle('E2:E11')
    ->getBorders()
    ->getRight()
    ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getColumnDimension('B')
    ->setWidth(12);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getColumnDimension('C')
    ->setWidth(15);

$phpExcelObject
    ->setActiveSheetIndex()
    ->getColumnDimension('D')
    ->setWidth(20);
    
$phpExcelObject
    ->setActiveSheetIndex()
    ->getColumnDimension('E')
    ->setWidth(10);

        // recorremos los registros obtenidos de la consulta a base de datos escribiéndolos en las celdas correspondientes
        
        foreach ($envio as $valor) {
            $phpExcelObject->setActiveSheetIndex()
                ->setCellValue('C3', $valor->getIglesia())
                ->setCellValue('C4', $valor->getZona())
                ->setCellValue('C5', $valor->getAnio()->format('Y'))
                ->setCellValue('C6', $valor->getMes())
                ->setCellValue('C7', $valor->getFechaAt()->format("Y-m-d"))
                ->setCellValue('C8', $valor->getOperacion())
                ->setCellValue('C9', $valor->getCajero())

                ->setCellValue('E3', $valor->getAporteA())
                ->setCellValue('E4', $valor->getAporteB())
                ->setCellValue('E5', $valor->getTotal())
         
                ;

            
        }

        // se crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // se crea el response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // y por último se añaden las cabeceras
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Envio ' .$valor->getIglesia(). ' ' .$valor->getMes(). ' ' .$valor->getAnio()->format('Y'). '.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }



public function AsistenciaReportExcelAction($aporte, $anio)
    {
    

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


        // solicitamos el servicio 'phpexcel' y creamos el objeto vacío...
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        // ...y le asignamos una serie de propiedades
        $phpExcelObject->getProperties()
            ->setCreator("Vabadus")
            ->setLastModifiedBy("Vabadus")
            ->setTitle("Ejemplo de exportación")
            ->setSubject("Ejemplo")
            ->setDescription("Listado de ejemplo.")
            ->setKeywords("vabadus exportar excel ejemplo");

        // establecemos como hoja activa la primera, y le asignamos un título
        $phpExcelObject->setActiveSheetIndex();
        $phpExcelObject->getActiveSheet()->setTitle('Tesoreria Nacional');
        

        // escribimos en distintas celdas del documento el título de los campos que vamos a exportar
        $phpExcelObject->setActiveSheetIndex()
            
            ->setCellValue('A1', 'IGLESIA')
            ->setCellValue('B1', 'Ene')
            ->setCellValue('C1', 'Feb')
            ->setCellValue('D1', 'Mar')
            ->setCellValue('E1', 'Abr')
            ->setCellValue('F1', 'May')
            ->setCellValue('G1', 'Jun')
            ->setCellValue('H1', 'Jul')
            ->setCellValue('I1', 'Ago')
            ->setCellValue('J1', 'Sep')
            ->setCellValue('K1', 'Oct')
            ->setCellValue('L1', 'Nov')
            ->setCellValue('M1', 'Dic')
            ->setCellValue('N1', 'Total')
            ;


// fijamos el Estilo a columnas y celdas
$style = array('font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF')));
$font = array('font' => array('size' => 13));
$backgrund =array('type' => \PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => '0A5E7C'));
$left = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
$center = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$right = array('alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));   



$phpExcelObject->setActiveSheetIndex()->mergeCells('A2:N2');
$phpExcelObject->setActiveSheetIndex()->getDefaultStyle()->applyFromArray($font);
$phpExcelObject->setActiveSheetIndex()->getStyle('A1:N1')->applyFromArray($style);
$phpExcelObject->setActiveSheetIndex()->getStyle('A1:N1')->applyFromArray($center);
$phpExcelObject->setActiveSheetIndex()->getStyle('A2:N2')->applyFromArray($center);
$phpExcelObject->setActiveSheetIndex()->getStyle('A1:N1')->getFill()->applyFromArray($backgrund);
$phpExcelObject->setActiveSheetIndex()->getStyle('C3:C11')->applyFromArray($right);


$phpExcelObject
    ->setActiveSheetIndex()
    ->getColumnDimension('A')
    ->setWidth(20);


        // recorremos los registros obtenidos de la consulta a base de datos escribiéndolos en las celdas correspondientes
        $i = 3;
        $a = 3;
        $b = 3;
        $c = 3;
        $d = 3;
        $e = 3;
        $f = 3;
        $g = 3;
        $h = 3;
        $is = 3;
        $j = 3;
        $k = 3;
        $l = 3;
        $m = 3;



        foreach ($enviosNorte as $valor) 
        {
            $totalNorte = $valor['a']+$valor['b']+$valor['c']+$valor['d']+$valor['e']+$valor['f']+$valor['g']+$valor['h']+$valor['i']+$valor['j']+$valor['k']+$valor['l'];

            $phpExcelObject->setActiveSheetIndex()->setCellValue('A'.$i++, $valor['iglesia']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('B'.$a++, $valor['a']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('C'.$b++, $valor['b']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('D'.$c++, $valor['c']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('E'.$d++, $valor['d']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('F'.$e++, $valor['e']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('G'.$f++, $valor['f']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('H'.$g++, $valor['g']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('I'.$h++, $valor['h']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('J'.$is++, $valor['i']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('K'.$j++, $valor['j']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('L'.$k++, $valor['k']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('M'.$l++, $valor['l']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('N'.$m++, $totalNorte);
            
        }

        
        foreach ($enviosCentro as $valor) 
        {   
            $totalCentro = $valor['a']+$valor['b']+$valor['c']+$valor['d']+$valor['e']+$valor['f']+$valor['g']+$valor['h']+$valor['i']+$valor['j']+$valor['k']+$valor['l'];

            $phpExcelObject->setActiveSheetIndex()->setCellValue('A'.$i++, $valor['iglesia']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('B'.$a++, $valor['a']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('C'.$b++, $valor['b']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('D'.$c++, $valor['c']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('E'.$d++, $valor['d']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('F'.$e++, $valor['e']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('G'.$f++, $valor['f']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('H'.$g++, $valor['g']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('I'.$h++, $valor['h']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('J'.$is++, $valor['i']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('K'.$j++, $valor['j']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('L'.$k++, $valor['k']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('M'.$l++, $valor['l']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('N'.$m++, $totalCentro);
        }

        foreach ($enviosSur as $valor) 
        {


            $totalSur = $valor['a']+$valor['b']+$valor['c']+$valor['d']+$valor['e']+$valor['f']+$valor['g']+$valor['h']+$valor['i']+$valor['j']+$valor['k']+$valor['l'];
            $phpExcelObject->setActiveSheetIndex()->setCellValue('A'.$i++, $valor['iglesia']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('B'.$a++, $valor['a']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('C'.$b++, $valor['b']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('D'.$c++, $valor['c']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('E'.$d++, $valor['d']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('F'.$e++, $valor['e']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('G'.$f++, $valor['f']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('H'.$g++, $valor['g']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('I'.$h++, $valor['h']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('J'.$is++, $valor['i']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('K'.$j++, $valor['j']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('L'.$k++, $valor['k']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('M'.$l++, $valor['l']);
            $phpExcelObject->setActiveSheetIndex()->setCellValue('N'.$m++, $totalSur);
        }
        


        // se crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // se crea el response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // y por último se añaden las cabeceras
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Reporte del ' .$aportes. ' del '.$anio.'.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }




public function AsistenciaReportPdfAction($aporte, $anio)
    { 

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
    $response = $this->render('IPNJAdminBundle:Asistencia:reportePdf.html.twig', array('aportes' => $aportes, 'aporte' => $aporte, 'anio' => $anio, 'enviosNorte' => $enviosNorte,'enviosCentro' => $enviosCentro, 'enviosSur' => $enviosSur));


        return new \TFox\MpdfPortBundle\Response\PDFResponse($this->getMpdfService()->generatePdf($response, array('format' => 'A4-L')));
    }
    
   public function AsistenciaReciboPdfAction($id)
        {
            $repository = $this->getDoctrine()->getRepository('IPNJAdminBundle:Asistencia');
            
            $envio = $repository->find($id);
            
            
            $response = $this->render('IPNJAdminBundle:Asistencia:reciboPdf.html.twig', array('envio' => $envio));


        return new \TFox\MpdfPortBundle\Response\PDFResponse($this->getMpdfService()->generatePdf($response));
        }


}