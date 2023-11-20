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
    $datos = $venta->POSTventas($body['codigo'],$body['nombre'],$body['precio'],$body['fechaingreso'],$body['stock'],$body['codventa'],$body['vendidos'],$body['fechaventa']);
    echo 'INSERTADOOO';
    break;
  case 'putv':
    $datos = $venta->PUTventas($body['codigo_producto']);
    echo 'Actualizadooo';
    break;
  case 'putp':
    $datos = $venta->PUTprice($body['codigo'],$body['nombre'],$body['precio'],$body['fecha'],$body['cantidad']);
    echo 'actualizadoo3';
    break;
  case 'del':
    $datos = $venta->DELventa($body['codigo_producto']);
    echo 'Eliminado corredctamente';
}