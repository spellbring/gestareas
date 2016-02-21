<?php

class moduloController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->_proyecto = $this->loadModel('proyecto');
        $this->_modulo = $this->loadModel('modulo');
        $this->_tarea = $this->loadModel('tarea');
    }

    public function index() {
        $this->_view->titulo = 'Administrador de Módulos';
        // echo Session::get("SESS_CLAVE");
        if (Session::get("SESS_CLAVE") != null) {
            $modulo_model = $this->_modulo->getModulo(Session::get("SESS_CLAVE"));
            $this->_view->_moduloProyecto = $modulo_model;
            $tarea_model = $this->_tarea;
            $this->_view->_tarea = $tarea_model;
            $this->_view->renderizarSistema('modulo');
        } else {

            header('Location: ' . BASE_URL . 'sistema');
        }
        //Session::destroy();
    }

    public function abreTicket($id) {
        Session::set("SESS_MODULO", $id);
        $this->_view->renderizaCenterBox('ingresoTicket');
    }
    
     public function abreInfoTicket($id) {
        Session::set("SESS_MODULO", $id);
        $tarea = $this->_tarea->getTarea($id);
        $this->_view->_mostrarTarea = $tarea;
        $this->_view->renderizaCenterBox('informacionTicket');
    }
    public function abreAdmModulo(){
        $adm_modulo =  $this->_proyecto->getProductosUsuarios();
        $this->_view->_getModulo = $adm_modulo;
        $this->_view->renderizarSistema('admModulo');
     }

    public function insertTicket() {
        $fechaReporte = date('Y.m.d');
        //echo $fechaReporte;
        $modulo = Session::get("SESS_MODULO");
        $nombreTicket = $this->getTexto("it_txtNombreTicket");
        $formularioObjetivo = $this->getTexto("it_txtFormularioObjetivo");
        $descripcionProblema = $this->getTexto("it_txtaDescripcionProblema");
        $solucionPropuesta = $this->getTexto("it_txtaSolucionPropuesta");
        $flujoCorrecto = $this->getTexto("it_txtaFlujoCorrecto");
        $tipologia = $this->getTexto("rdbtnTipoologia");
        $prioridad = $this->getTexto("rdbtnPrioridad");
        $sql = "INSERT INTO `gestion`.`tarea`(`nombre`,`descripcion`,`formulario`,`flujoCorrecto`,`fechaReporte`,`solucionPropuesta`,
                `Estado_idEstado`,`Nivel_idNivel`,`Hito_idHito`,`Evento_idEvento`)
                VALUES('" . $nombreTicket . "',
                '". $descripcionProblema ."','". $formularioObjetivo ."',
                '" . $flujoCorrecto . "','" . $fechaReporte . "',
                '" . $solucionPropuesta . "',1," . $prioridad . "," . $modulo . "," . $tipologia .");";
        $consulta = $this->_tarea->exeSQL($sql);
        if ($consulta) {
           $html = file_get_contents(ROOT . 'views' . DS . 'sistema' . DS . 'correos' . DS . 'tarea.html');
                $reempl = array('Titulo' => 'Asunto Prueba',    
                    'descripcion proceso' => 'Un comentario para describir el proceso',
                    'Descripcion'=>'se ha asignado una tarea a Jaime Reyes Romero'
                    );

                foreach ($reempl as $nombre => $valor) {
                    $html = str_replace('{' . $nombre . '}', $valor, $html);
                }

                if (!empty($descripcionProblema)) {
                    //--------------------------Configuracion Correo---------------------  
                    $this->getLibrary('PHPMailerAutoload');
                    $mail = new PHPMailer();
                    $mail->IsSMTP();
                    $mail->SMTPAuth = true;

                    $mail->Port = 587;
                    $mail->Host = "smtp.gmail.com";
                    $mail->Username = "jjreyes.romero88@gmail.com";
                    $mail->Password = "123DnD123!";

                    $mail->From = 'jjreyes.romero88@gmail.com';
                    $mail->FromName = 'Solicitud de trabajo';
                    $mail->CharSet = CHARSET;
                    $mail->Subject = 'Asignación de trabajo: ';
                    $mail->Body = $descripcionProblema;

                    $mail->MsgHTML($html);

                    $mail->AddAddress('jjreyes.romero88@gmail.com', "Jaime Reyes");
                    if ($mail->Send()){ 
                    echo 'OK';}
                     else {
                     echo 'Error al enviar el Correo';}
                        
                } else {
                    echo 'Escriba un mensaje';
                } 
            
        }
        else{
            echo 'No se pudo insertar la tarea';
        }
        //echoS 'OK';
    }
    
    public function insertModulo() {
        $nombre = $this->getTexto("inputName");
        $id = $this->getTexto("selectProducto");
        
        $sql = "INSERT INTO gestion.hito(nombre, Producto_idProducto) values ('".$nombre."', ".$id.");";
        $consulta = $this->_tarea->exeSQL($sql);
        if ($consulta) {
            echo "OK";
        }
        else{
            echo 'No se pudo insertar la el Módulo';
        }
        //echoS 'OK';
    }
 
}
