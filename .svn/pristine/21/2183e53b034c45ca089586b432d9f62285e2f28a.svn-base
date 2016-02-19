<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuarioDAO
 *
 * @author Jaime
 */
class usuarioDAO extends Model {

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

    public function getUsuario($user) {

        $sql = "SELECT   USERNAME
                        ,IDUSER
                        ,PASSWORD
                        ,NOMBRE
                        ,APELLIDO
                        ,EMAIL
                        ,FECHACREACION
                FROM USUARIO
                WHERE USERNAME = '" . $user . "'";
               
        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $userArray = $this->_db->fetchAll($datos);
            $usArray = array();

            foreach ($userArray as $usdb) {
                $userObj = new usuarioDTO();
                $userObj->setIduser(trim($usdb['IDUSER']));
                $userObj->setUsername(trim($usdb['USERNAME']));
                $userObj->setPassword(trim($usdb['PASSWORD']));
                $userObj->setNombre(trim($usdb['NOMBRE']));
                $userObj->setApellido(trim($usdb['APELLIDO']));
                $userObj->setEmail(trim($usdb['EMAIL']));
                $userObj->setFechacreacion(trim($usdb['FECHACREACION']));
            $usArray[] = $userObj;                
            }

            return $usArray;
        } else {
            return false;
        }
    }

}
