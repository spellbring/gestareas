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
        
        $sql = "SELECT   idUsuario
                        ,nombreUsuario
                        ,pass
                        ,fechaRegistro
                        ,tipoUsuario
                        ,EMAIL
                        ,nombres
                        ,apellidos
                FROM USUARIO ";
        
        if(!empty($user)){
            $sql .= "WHERE nombreUsuario = '" . $user . "'";
        }
                
               
        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $userArray = $this->_db->fetchAll($datos);
            $usArray = array();

            foreach ($userArray as $usdb) {
                $userObj = new usuarioDTO();
                $userObj->setIduser(trim($usdb['idUsuario']));
                $userObj->setUsername(trim($usdb['nombreUsuario']));
                $userObj->setPassword(trim($usdb['pass'])); 
                $userObj->setEmail(trim($usdb['EMAIL']));
                $userObj->setFechacreacion(trim($usdb['fechaRegistro']));
                $userObj->setPerfil_idperfil(trim($usdb['tipoUsuario']));
                $userObj->setNombre(trim($usdb['nombres']));
                $userObj->setApellido(trim($usdb['apellidos']));
                
            $usArray[] = $userObj;                
            }

            return $usArray;
        } else {
            return false;
        }
    }
    public function getUsuarioTarea($user) {
        
        $sql = "SELECT b.nombres, b.apellidos, b.email FROM tarea a 
                inner join usuario b on b.idUsuario = a.id_usuario_crea_tarea
                where a.idTarea = ".$user."";
                
               
        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $userArray = $this->_db->fetchAll($datos);
            $usArray = array();

            foreach ($userArray as $usdb) {
                $userObj = new usuarioDTO();
                $userObj->setEmail(trim($usdb['email']));
                $userObj->setNombre(trim($usdb['nombres']));
                $userObj->setApellido(trim($usdb['apellidos']));
                
            $usArray[] = $userObj;                
            }

            return $usArray;
        } else {
            return false;
        }
    }
    
     public function getUsuarioId($user) {
        
        $sql = "SELECT   idUsuario
                        ,nombreUsuario
                        ,pass
                        ,fechaRegistro
                        ,tipoUsuario
                        ,EMAIL
                        ,nombres
                        ,apellidos
                FROM USUARIO ";
        
        if(!empty($user)){
            $sql .= "WHERE idUsuario = '" . $user . "'";
        }
                
               
        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $userArray = $this->_db->fetchAll($datos);
            $usArray = array();

            foreach ($userArray as $usdb) {
                $userObj = new usuarioDTO();
                $userObj->setIduser(trim($usdb['idUsuario']));
                $userObj->setUsername(trim($usdb['nombreUsuario']));
                $userObj->setPassword(trim($usdb['pass'])); 
                $userObj->setEmail(trim($usdb['EMAIL']));
                $userObj->setFechacreacion(trim($usdb['fechaRegistro']));
                $userObj->setPerfil_idperfil(trim($usdb['tipoUsuario']));
                $userObj->setNombre(trim($usdb['nombres']));
                $userObj->setApellido(trim($usdb['apellidos']));
                
            $usArray[] = $userObj;                
            }

            return $usArray;
        } else {
            return false;
        }
    }
    
    public function pagUsuarios($inicio, $fin) {    

        $sql = "select u.idUsuario, u.nombreUsuario, u.pass, u.fechaRegistro, u.tipoUsuario, u.email from usuario u"
                . " limit " . $inicio . ", " . $fin;

        try {

            $datos = $this->_db->consulta($sql);
            if ($datos->num_rows > 0) {

                $usuarioArray = $this->_db->fetchAll($datos);
                $uArray = array();

                foreach ($usuarioArray as $filas) {
                    $usuarioDTO = new usuarioDTO();
                    $usuarioDTO->setIduser($filas['idUsuario']);
                    $usuarioDTO->setUsername($filas['nombreUsuario']);
                    $usuarioDTO->setPassword($filas['pass']);
                    $usuarioDTO->setFechacreacion($filas['fechaRegistro']);
                    $usuarioDTO->setPerfil_idperfil($filas['tipoUsuario']);
                    $usuarioDTO->setEmail($filas['email']);

                    $uArray[] = $usuarioDTO;
                }

                return $uArray;
            }
        } catch (Exception $exc) {

            echo 'Error en la consulta';
        } finally {
            $this->_db->closeConex();
        }
    }
    
    public function getUsuarioInterno() {   
        $sql = "SELECT idUsuario, Nombres, Apellidos FROM gestion.usuario where tipoUsuario = 2";
               
        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $userArray = $this->_db->fetchAll($datos);
            $usArray = array();

            foreach ($userArray as $usdb) {
                $userObj = new usuarioDTO();
                $userObj->setIduser(trim($usdb['idUsuario']));
                $userObj->setNombre(trim($usdb['Nombres']));
                $userObj->setApellido(trim($usdb['Apellidos'])); 
              
            $usArray[] = $userObj;                
            }

            return $usArray;
        } else {
            return false;
        }
    }
    
    public function getUsuarioPorTipo($tipo) {   
        $sql = "SELECT idUsuario, Nombres, Apellidos FROM gestion.usuario where tipoUsuario = " . $tipo;
               
        $datos = $this->_db->consulta($sql);
        if ($this->_db->numRows($datos) > 0) {

            $userArray = $this->_db->fetchAll($datos);
            $usArray = array();

            foreach ($userArray as $usdb) {
                $userObj = new usuarioDTO();
                $userObj->setIduser(trim($usdb['idUsuario']));
                $userObj->setNombre(trim($usdb['Nombres']));
                $userObj->setApellido(trim($usdb['Apellidos'])); 
              
            $usArray[] = $userObj;                
            }

            return $usArray;
        } else {
            return false;
        }
    }
    

}
