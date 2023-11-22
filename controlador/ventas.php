<?php
header('Content-Type: application/json');
require_once("../conexiion/conexion.php");
require_once("../modelo/class.php");
$venta = new Ventas();

$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["op"]) {
  case 'get':
    $datos = $venta->Getventas();
    echo json_encode($datos);
    break;
  case 'post':
    $datos = $venta->POSTventas($body['codigo'],$body['nombre'],$body['precio'],$body['fechaingreso'],$body['stock']);
    echo 'INSERTADOOO';
    break;
  case 'ventas':
    $datos = $venta->ventas($body['codigo'],$body['vendidos']);
    echo 'Registrados';
    break;
  case 'ingeso':
    $datos = $venta->ingresos($body['codigo'],$body['ingresado']);
    echo 'productos';
    break;
  case 'getv':
    $datos = $venta->getv();
    echo json_encode($datos);
    break;
  case 'geti':
      $datos = $venta->geti();
      echo json_encode($datos);
      break;
  case 'del':
    $datos = $venta->DELventa($body['codigo_producto']);
    echo 'Eliminado corredctamente';
    break;
  case 'putv':
    $datos = $venta->PUTven($body['codigo'],$body['cantidad']);
    print 'actualizado';
    break;
}