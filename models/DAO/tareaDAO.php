<?php
class tareaDAO extends Model{
    
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
    
  
        
        
        public function getTarea($id){
        $sql = "SELECT A.nombre, "
                . "A.Hito_idHito, "
                . "A.idTarea, "
                . "A.Nivel_idNivel, "
                . "B.nombre as nivelnombre "
                . "FROM tarea A "
                . "inner join nivel b "
                . "on a.Nivel_idNivel = b.idNivel "
                . "where A.Hito_idHito = ".$id."";
               
        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $modArray = $this->_db->fetchAll($datos);
            $moArray = array();

            foreach ($modArray as $moddb) {
                 $modObj = new tareaDTO();
                 $modObj->setNombre (trim($moddb['nombre']));
                 $modObj->setId_hito(trim($moddb['Hito_idHito']));
                 $modObj->setIdTarea(trim($moddb['idTarea']));
                 $modObj->setId_nivel(trim($moddb['Nivel_idNivel']));
                 $modObj->setDescripcion(trim($moddb['nivelnombre']));
               
            $moArray[] = $modObj;                
            }

            return $moArray;
        } else {
            return false;
        }
        
        
    
    }
     public function getTareaId($id){
        $sql = "SELECT A.nombre, "
                . "A.Hito_idHito, "
                . "A.idTarea, "
                . "A.Nivel_idNivel, "
                . "B.nombre as nivelnombre "
                . "FROM tarea A "
                . "inner join nivel b "
                . "on a.Nivel_idNivel = b.idNivel "
                . "where A.idTarea = ".$id."";
               
        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $modArray = $this->_db->fetchAll($datos);
            $moArray = array();

            foreach ($modArray as $moddb) {
                 $modObj = new tareaDTO();
                 $modObj->setNombre (trim($moddb['nombre']));
                 $modObj->setId_hito(trim($moddb['Hito_idHito']));
                 $modObj->setIdTarea(trim($moddb['idTarea']));
                 $modObj->setId_nivel(trim($moddb['Nivel_idNivel']));
                 $modObj->setDescripcion(trim($moddb['nivelnombre']));
               
            $moArray[] = $modObj;                
            }

            return $moArray;
        } else {
            return false;
        }
        
        
    
    }
    
    
    
    
    
    
}