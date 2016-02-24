<?php
class moduloDAO extends Model{
    
   public function __construct() {
        parent::__construct();
    }
    
     public function exeSQL($sql) {
        try {
            $this->_db->consulta($sql);
            return 'OK';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
  
        
        
        public function getModulo($id){
        $sql = "select A.nombre, A.idHito, A.Producto_idProducto, a.estado FROM gestion.hito A where A.producto_idproducto = ".$id." and estado = 'V'";
        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $modArray = $this->_db->fetchAll($datos);
            $moArray = array();

            foreach ($modArray as $moddb) {
                 $modObj = new moduloDTO();
                 $modObj->setIdHito(trim($moddb['idHito']));
                 $modObj->setNombre(trim($moddb['nombre']));
                 $modObj->setEstado(trim($moddb['estado']));
                 $modObj->setIdProducto(trim($moddb['Producto_idProducto']));
               
            $moArray[] = $modObj;                
            }

            return $moArray;
        } else {
            return false;
        }
        
        
    
    }
     public function getModuloAdm($id){
        $sql = "select A.nombre, A.idHito, A.Producto_idProducto, B.nombre as nombreproducto, A.estado FROM gestion.hito A"
                . " inner join gestion.producto B"
                . " on A.Producto_idProducto = B.idProducto";
        if($id != 0){
            
        $sql .= " where A.idHito = ".$id."";
        }
        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $modArray = $this->_db->fetchAll($datos);
            $moArray = array();

            foreach ($modArray as $moddb) {
                 $modObj = new moduloDTO();
                 $modObj->setIdHito(trim($moddb['idHito']));
                 $modObj->setNombre(trim($moddb['nombre']));
                 $modObj->setIdProducto(trim($moddb['Producto_idProducto']));
                 $modObj->setNombreProducto(trim($moddb['nombreproducto']));
                 $modObj->setEstado(trim($moddb['estado']));
            $moArray[] = $modObj;                
            }

            return $moArray;
        } else {
            return false;
        }
        
        
    
    }
    
    
    
    
    
    
    
}

