<?php
header("Access-Control-Allow-Origin: *");
/**
 * controller.php
 * Controller
 *
 * @author     Carlos Guzmán
 * @copyright  Privatour
 * @version    1.0
 * @example    http://url/something.php?op=1
 */

/* Para utilizar POST, quitar si utilizarás GET */
$opcion = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : 'none';

if ($opcion != 'none') {
    switch ($opcion) {
        case 'getTours':
            require('app.model.php');
            $app = new App();
            $app->getTours($lat,$lon);
            break;
        case 'setTour':
            require('app.model.php');
            $app = new App();
            $nombre = (isset($_REQUEST['nombre'])) ? $_REQUEST['nombre'] : 'none';
            $descripcion_corta = (isset($_REQUEST['descripcion_corta'])) ? $_REQUEST['descripcion_corta'] : 'none';
            $descripcion_corta_ingles = (isset($_REQUEST['descripcion_corta_ingles'])) ? $_REQUEST['descripcion_corta_ingles'] : 'none';
            $corta_portugues = (isset($_REQUEST['corta_portugues'])) ? $_REQUEST['corta_portugues'] : 'none';
            $larga = (isset($_REQUEST['larga'])) ? $_REQUEST['larga'] : 'none';
            $larga_ingles = (isset($_REQUEST['larga_ingles'])) ? $_REQUEST['larga_ingles'] : 'none';
            $larga_portugues = (isset($_REQUEST['larga_portugues'])) ? $_REQUEST['larga_portugues'] : 'none';
            $duracion = (isset($_REQUEST['duracion'])) ? $_REQUEST['duracion'] : 'none';
            $cantidad_maxima = (isset($_REQUEST['cantidad_maxima'])) ? $_REQUEST['cantidad_maxima'] : 'none';
            $precio = (isset($_REQUEST['precio'])) ? $_REQUEST['precio'] : 'none';
            $app->setTour($nombre, $descripcion_corta, $descripcion_corta_ingles, $corta_portugues, $larga, $larga_ingles, $larga_portugues, $duracion, $cantidad_maxima, $precio);
            break;
        default:
            $datos = array();
            $dato['success'] = false;
            $dato['desc'] = 'Wrong choice';
            array_push($datos, $dato);
            echo json_encode($datos);
            break;
    }
} else {
    $datos = array();
    $dato['success'] = false;
    $dato['desc'] = 'Request unspecified';
    array_push($datos, $dato);
    echo json_encode($datos);
}
?>