<?php

class registroController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->_usuario = $this->loadModel('usuario');
    }

    public function index() {
        $this->_view->titulo = 'Registro';
        $this->_view->renderizaLogin('registro');
    }

    public function guardarUsuario() {
        try {
            if (strtolower($this->getServer('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest') {
                $username = $this->getTexto('txtLogin');
                $pass = $this->getTexto('txtContraseña');
                $passConf = $this->getTexto('txtContraseñaConf');              
                $email = $this->getTexto('txtEmail'); 
                $nombre = $this->getTexto('txt_Nombre');
                $apellido = $this->getTexto('txt_Apellido');
                if ($this->validador($username, $pass, $passConf, $email, $nombre, $apellido) == false) {                   
                } else {
                    $sql = "INSERT INTO USUARIO VALUES(0, 2,'".$username."', '".crypt($pass,'SMSYS')."','".$nombre."', '".$apellido."', '".$email."',CURDATE()) ";
                            
                         $datos = $this->_usuario->exeSQL($sql);
                        echo 'OK';  
                }
            } else {
                throw new Exception('<div style="color:red;">Error inesperado, comuníquese con el administrador del sitio</div> ');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function validador($username, $pass, $passConf, $email, $nombre, $apellido) {
        if (!$username) {
            echo 'Ingrese un nombre de usuario';
            return false;
        } else if (!$pass) {
            echo 'Ingrese una contraseña';
            return false;
        } else if (strlen($pass) > 15) {
            echo 'La contraseña no debe pasar los 15 caracteres';
            return false;
        } else if ($pass != $passConf) {
            echo 'Debe Coincidir las Contraseñas';
            return false; 
        } else if (!$email) {
            echo 'Ingrese un E-mail';
            return false;
        } else if (!$this->getCorreo($email)) {
            echo 'Ingrese un correo válido';
            return false;
        }
        else if(!$nombre){
            echo 'Ingrese un Nombre';
            return false;
        }
        else if(!$apellido){
            echo 'Ingrese un Apellido';
            return false;
        }
        return true;
    }

}
