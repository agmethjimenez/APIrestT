<?php
Class Ventas extends Database{
    public function Getventas(){
        $conexion = parent::Conectar();

        $sql = "SELECT p.cod_producto,p.nombre, p.precio, p.fecha_ingreso, p.cantidad, v.vendidos, v.fecha_venta, p.cantidad - v.vendidos AS restantes FROM productos AS p
        INNER JOIN ventas AS v ON p.cod_producto = v.cod_producto";
        $resultados = array();

        if ($resultado = $conexion->query($sql)) {
            while ($fila = $resultado->fetch_assoc()) {
                $resultados[] = $fila;
            }
            $resultado->free();
        }
        return $resultados;
        
    }
    public function POSTventas($codproducto,$nombre,$price,$fecha,$stock,$codventa,$vendidos,$fecha_venta){
        $conexion = parent::Conectar();

        $conexion->begin_transaction();

        $sql = "INSERT INTO productos VALUES(?,?,?,?,?)";
        $bin = $conexion->prepare($sql);
        $bin->bind_param("sssss",$codproducto,$nombre,$price,$fecha,$stock);
        $bin->execute();

        $cod_pro = $bin->insert_id;

        $sql2 = "INSERT INTO ventas VALUES (?,?,?,?)";
        $bin2 = $conexion->prepare($sql2);
        $bin2->bind_param("ssss",$codventa,$codproducto,$vendidos,$fecha_venta);
        $bin2->execute();

        $conexion->commit();
    }
    public function PUTventas($codproducto){
        $conexion = parent::Conectar();
        $sql = "UPDATE productos p
        SET p.cantidad = p.cantidad - (SELECT v.vendidos FROM ventas v WHERE v.cod_producto = p.cod_producto)
        WHERE p.cod_producto = ?";
        $bin = $conexion->prepare($sql);
        $bin->bind_param("s", $codproducto);
        $bin->execute();
    }
    public function PUTprice($codproducto, $name, $precio, $fecha, $cantidad){
        $conexion = parent::Conectar();
        $sql = "UPDATE productos SET nombre = ?, precio = ?, fecha_ingreso = ?, cantidad = ? WHERE cod_producto = ?";
        $bin = $conexion->prepare($sql);
        $bin->bind_param("ssssi", $name, $precio, $fecha, $cantidad, $codproducto);
        $bin->execute();
    }
    
    public function DELventa($cod){
        $conexion = parent::Conectar();
        $conexion->begin_transaction();

        $sql = "DELETE FROM ventas WHERE cod_producto = ?";
        $bin = $conexion->prepare($sql);
        $bin->bind_param("s",$cod);
        $bin->execute();


        $sql2 = "DELETE FROM productos WHERE cod_producto = ?";
        $bin2 = $conexion->prepare($sql2);
        $bin2->bind_param("s",$cod);
        $bin2->execute();

        $conexion->commit();

    }

}