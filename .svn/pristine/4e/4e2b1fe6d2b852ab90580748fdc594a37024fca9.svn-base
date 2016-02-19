<?php
class proyectoDAO extends Model{
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
    
    public function getProductosUsuarios(){
        $sql = "select distinct c.Nombre, c.idProducto from gestion.hito_usuario A
                inner join gestion.hito B on B.idHito = A.Hito_idHito
                inner join gestion.producto C on C.idProducto = B.Producto_idProducto
                where A.Usuario_idUsuario = ".Session::get("SESS_ID_USER")."
                ";
               
        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $proyArray = $this->_db->fetchAll($datos);
            $proArray = array();

            foreach ($proyArray as $usdb) {
                 $proObj = new proyectoDTO();
                 $proObj->setNombre(trim($usdb['Nombre']));
                 $proObj->setId_producto(trim($usdb['idProducto']));
               
            $proArray[] = $proObj;                
            }

            return $proArray;
        } else {
            return false;
        }
        
        
    }
    
}
