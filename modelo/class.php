<?php
Class Ventas extends Database{
    public function Getventas(){
        $conexion = parent::Conectar();
        $sql = "SELECT p.nombre, COALESCE(SUM(i.ingresado), 0) AS total_ingresos, COALESCE(SUM(v.total_vendidos), 0) AS total_vendidos FROM productos AS p 
        LEFT JOIN ( SELECT cod_producto, SUM(ingresado) AS ingresado FROM ingresos GROUP BY cod_producto ) AS i ON p.cod_producto = i.cod_producto
        LEFT JOIN ( SELECT cod_producto, SUM(vendidos) AS total_vendidos FROM ventas GROUP BY cod_producto ) AS v ON p.cod_producto = v.cod_producto GROUP BY p.nombre";
        $resultados = array();

        if ($resultado = $conexion->query($sql)) {
            while ($fila = $resultado->fetch_assoc()) {
                $resultados[] = $fila;
            }
            $resultado->free();
        }
        return $resultados;  
    }
    public function POSTventas($codproducto,$nombre,$price,$fecha,$stock){
        $conexion = parent::Conectar();
        $conexion->begin_transaction();
        $sql = "INSERT INTO productos VALUES(?,?,?,?,?)";
        $bin = $conexion->prepare($sql);
        $bin->bind_param("sssss",$codproducto,$nombre,$price,$fecha,$stock);
        $bin->execute();
        $conexion->commit();
    }
   
    public function PUTven($id,$can){
        $conexion = parent::Conectar();
        $sql = "UPDATE ventas SET vendidos = ? WHERE cod_producto = ?";
        $bin = $conexion->prepare($sql);
        $bin->bind_param("ss", $can, $id);
        $bin->execute(); 
    }
    
    public function DELventa($cod){
        $conexion = parent::Conectar();
        $conexion->begin_transaction();

        $sql = "DELETE FROM ventas WHERE cod_producto = ?";
        $bin = $conexion->prepare($sql);
        $bin->bind_param("s",$cod);
        $bin->execute();

        $sql3 = "DELETE FROM ingresos WHERE cod_producto = ?";
        $bin3 = $conexion->prepare($sql3);
        $bin3->bind_param("s",$cod);
        $bin3->execute();


        $sql2 = "DELETE FROM productos WHERE cod_producto = ?";
        $bin2 = $conexion->prepare($sql2);
        $bin2->bind_param("s",$cod);
        $bin2->execute();



        $conexion->commit();

    }
    public function ventas($cod,$ventas){
        $conexion = parent::Conectar();
        
        $sql = "INSERT INTO ventas VALUES (NULL,?,?,CURDATE())";
        $bin = $conexion->prepare($sql);
        $bin->bind_param("ss",$cod, $ventas);
        $bin->execute();
    }
    public function ingresos($cod,$ingresos){
        $conexion = parent::Conectar();
        
        $sql = "INSERT INTO ingresos VALUES (NULL,?,CURDATE(),?)";
        $bin = $conexion->prepare($sql);
        $bin->bind_param("ss",$cod, $ingresos);
        $bin->execute();
    }
    public function getv(){
        $conexion = parent::Conectar();

        $sql = "SELECT p.nombre, sum(i.ingresado) AS total_ingresos FROM ingresos as i
        INNER JOIN productos AS p ON p.cod_producto = i.cod_producto
        group by p.nombre;";
        $resultados = array();

        if ($resultado = $conexion->query($sql)) {
            while ($fila = $resultado->fetch_assoc()) {
                $resultados[] = $fila;
            }
            $resultado->free();
        }
        return $resultados;
    }
    public function geti(){
        $conexion = parent::Conectar();

        $sql = "SELECT p.nombre, sum(v.vendidos) AS total_ingresos FROM ventas as v
        INNER JOIN productos AS p ON p.cod_producto = v.cod_producto
        group by p.nombre;";
        $resultados = array();

        if ($resultado = $conexion->query($sql)) {
            while ($fila = $resultado->fetch_assoc()) {
                $resultados[] = $fila;
            }
            $resultado->free();
        }
        return $resultados;
    }

}