<?php

class tareaDAO extends Model {

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

    public function getTarea($id) {
        $sql = "SELECT A.nombre, "
                . "A.Hito_idHito, "
                . "A.idTarea, "
                . "A.Nivel_idNivel, "
                . "B.nombre as nivelnombre "
                . "FROM tarea A "
                . "inner join nivel b "
                . "on a.Nivel_idNivel = b.idNivel "
                . "where A.Hito_idHito = " . $id . " "
                . "and A.Estado_idEstado <> 5 ";
        //. "and A.Estado_idEstado = 1";

        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $modArray = $this->_db->fetchAll($datos);
            $moArray = array();

            foreach ($modArray as $moddb) {
                $modObj = new tareaDTO();
                $modObj->setNombre(trim($moddb['nombre']));
                $modObj->setId_hito(trim($moddb['Hito_idHito']));
                $modObj->setIdTarea(trim($moddb['idTarea']));
                $modObj->setId_nivel(trim($moddb['Nivel_idNivel']));
                $modObj->setDescripcionTarea(trim($moddb['nivelnombre']));

                $moArray[] = $modObj;
            }

            return $moArray;
        } else {
            return false;
        }
    }

    public function getTareaId($id) {
        $sql = "SELECT A.nombre, "
                . "A.Hito_idHito, "
                . "A.Descripcion, "
                . "A.idTarea, "
                . "A.Nivel_idNivel, "
                . "B.nombre as nivelnombre, "
                . "A.Estado_idEstado, "
                . "C.Nombre "
                . "FROM tarea A "
                . "inner join nivel b "
                . "on a.Nivel_idNivel = b.idNivel "
                . "inner join estado C "
                . "on A.Estado_idEstado = C.idEstado "
                . "where A.idTarea = " . $id . "";
        
        
      

        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $modArray = $this->_db->fetchAll($datos);
            $moArray = array();

            foreach ($modArray as $moddb) {
                $modObj = new tareaDTO();
                $modObj->setNombre(trim($moddb['nombre']));
                $modObj->setId_hito(trim($moddb['Hito_idHito']));
                $modObj->setIdTarea(trim($moddb['idTarea']));
                $modObj->setId_nivel(trim($moddb['Nivel_idNivel']));
                $modObj->setDescripcion(trim($moddb['nivelnombre']));
                $modObj->setDescripcionTarea(trim($moddb['Descripcion']));
                $modObj->setId_estado(trim($moddb['Estado_idEstado']));
                $modObj->setNombre_Estado(trim($moddb['Nombre']));

                $moArray[] = $modObj;
            }

            return $moArray;
        } else {
            return false;
        }
    }

    public function getTareasUsuario($idUsuario, $hito) {

        $sql = "select
                t.idTarea,
                t.nombre,
                t.descripcion,
                t.formulario,
                t.flujoCorrecto,
                t.fechaReporte,
                t.fechaTermino,
                t.horasEstimadas,
                t.fechaFinalizacion,
                t.solucionPropuesta,
                t.motivoRechazo,
                e.Nombre as estado,
                e.nivelAvance,
                n.nombre as nivel,
                h.nombre as modulo,
                ev.nombre as evento,
                n.idNivel

                from tarea_usuario tu

                inner join tarea t
                on tu.Tarea_idTarea = t.idTarea

                inner join estado e
                on e.idEstado = t.Estado_idEstado

                inner join nivel n
                on n.idNivel = t.Nivel_idNivel

                inner join hito h
                on h.idHito = t.Hito_idHito

                inner join evento ev
                on ev.idEvento = t.Evento_idEvento

                where e.idEstado <> 0 and e.idEstado <> 1 and e.idEstado <> 4 " . 
                      "and tu.Usuario_idUsuario = " . $idUsuario . " and h.idHito = " . $hito ."";
        
        
        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {
           //echo $sql;
            $tareaArray = $this->_db->fetchAll($datos);
            $tArray = array();

            foreach ($tareaArray as $filas) {
                
                $tareaDTO = new tareaDTO();
                $tareaDTO->setId_evento(trim($filas['idTarea']));
                $tareaDTO->setNombre(trim($filas['nombre']));
                $tareaDTO->setId_nivel(trim($filas['idNivel']));
                $tareaDTO->setDescripcionTarea(trim($filas['descripcion']));
                $tareaDTO->setNombreNivel(trim($filas['nivel']));
                $tArray[] = $tareaDTO;
            }

            return $tArray;
        } else {
            return false;
        }
        
        
    }
    


}
