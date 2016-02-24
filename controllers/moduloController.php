<?php

class moduloController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->_proyecto = $this->loadModel('proyecto');
        $this->_modulo = $this->loadModel('modulo');
        $this->_tarea = $this->loadModel('tarea');
        $this->_usuario = $this->loadModel('usuario');
        $this->_estado = $this->loadModel('estado');
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
  /* Inicio Abre modales */
    public function abreTicket($id) {
        Session::set("SESS_MODULO", $id);
        $this->_view->renderizaCenterBox('ingresoTicket');
    }  
    
    public function abreInfoTicket($id) {
        Session::set("SESS_MODULO", $id);
        //TareaDAO
        $tarea = $this->_tarea->getTareaId($id);
        if($tarea){
           $this->_view->_It_tarea = $tarea[0]->getNombre();
           $this->_view->_It_formulario = $tarea[0]->getNombre();
           $this->_view->_It_descripcion_problema = $tarea[0]->getNombre();
           $this->_view->_It_solucion_propuesta = $tarea[0]->getNombre();
           $this->_view->_It_flujo_correcto = $tarea[0]->getNombre();
           $this->_view->_It_id_estado = $tarea[0]->getId_estado();
            
        }
        //EstadoDAO
         $estado = $this->_estado->getEstado();
         $this->_view->_listEstado = $estado;
         //UsuarioDAO
        $usuario = $this->_usuario->getUsuarioInterno();
        $this->_view->_usuarioInterno = $usuario;
        $this->_view->renderizaCenterBox('informacionTicket');
    }
    
    public function abreAdmModulo(){
        $adm_modulo =  $this->_proyecto->getProductosUsuarios();
        $modulos = $this->_modulo->getModuloAdm(0);
        $this->_view->_getModuloAdm = $modulos;
        $this->_view->_getModulo = $adm_modulo;
        $this->_view->renderizarSistema('admModulo');
     }
     
    public function abreInsertModulo(){
        $adm_modulo =  $this->_proyecto->getProductosUsuarios();  
        $this->_view->_getModulo = $adm_modulo;
        $this->_view->renderizaCenterBox('admModuloInsert');
     }
     
    public function abreSolicitudTareas(){
         $adm_modulo =  $this->_proyecto->getProductosUsuarios();  
         $this->_view->_getModulo = $adm_modulo;
         $this->_view->renderizaCenterBox('solicitudTarea');
    }
    public function abreModificaModulo($id){
       $modulos = $this->_modulo->getModuloAdm($id);
       $id_hito = 0;
       if($modulos){
           $this->_view->_MmId = $modulos[0]->getIdHito();
           $this->_view->_MmNombre = $modulos[0]->getNombre();
           $this->_view->_MmIdProyecto = $modulos[0]->getIdProducto();
           $this->_view->_MmNombreProyecto = $modulos[0]->getNombreProducto();
           $this->_view->_MmEstado = $modulos[0]->getEstado();
           $id_hito = $modulos[0]->getIdHito();
       }
      
       $tarea_model = $this->_tarea->getTarea($id_hito);
       $this->_view->_tarea = $tarea_model;
       $this->_view->renderizaCenterBox('modificarModulo');
    }
    /* Fin Abre modales */

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
                    $mail->SMTPSecure = "ssl";  
                    $mail->Port = 465;
                    $mail->Host = "smtp.gmail.com";
                    $mail->Username = "jreyes@peg.cl";
                    $mail->Password = "123DnD123!";

                    $mail->From = 'jreyes@peg.com';
                    $mail->FromName = 'Solicitud de trabajo';
                    $mail->CharSet = CHARSET;
                    $mail->Subject = 'Asignación de trabajo: ';
                    $mail->Body = $descripcionProblema;

                    $mail->MsgHTML($html);

                    $mail->AddAddress('jreyes@peg.cl', "Jaime Reyes");
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
    
    public function validaInsertModulo($nombre, $id){
       if(!$nombre){echo "Ingrese un nombre para el Hito";}
       if(!$id){echo "Seleccione un proyecto";}
       
       return true;   
    }
    
    public function comboHitos($id){
    
         $adm_modulo = $this->_modulo->getModulo($id);
         $hito = "";
         foreach($adm_modulo as $mod){   
           $hito .= "<option value='".  $mod->getIdHito()."'>". utf8_encode($mod->getNombre())."</option>";
         }
         echo $hito;
         //$this->_view->renderizaCenterBox('solicitudTarea');
    }
    
    public function insertSolicitud() {
        $fechaReporte = date('Y.m.d');
        //echo $fechaReporte;
        $modulo = $this->getTexto("st_hito");;
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
                    $mail->SMTPSecure = "ssl";  
                    $mail->Port = 465;
                    $mail->Host = "smtp.gmail.com";
                    $mail->Username = "jreyes@peg.cl";
                    $mail->Password = "123DnD123!";

                    $mail->From = 'jreyes@peg.com';
                    $mail->FromName = 'Solicitud de trabajo';
                    $mail->CharSet = CHARSET;
                    $mail->Subject = 'Asignación de trabajo: ';
                    $mail->Body = $descripcionProblema;

                    $mail->MsgHTML($html);

                    $mail->AddAddress('jreyes@peg.cl', "Jaime Reyes");
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
    
    public function modificaHito(){
       $id = $this->getTexto('Mm_id_hito'); 
       $vigencia = $this->getTexto('Mm_vigencia');
       $nombre = $this->getTexto('Mm_nombre_hito'); 
       if($this->validaModificaHito($vigencia, $nombre) == false ){
       }     
       else{
       $sql = "UPDATE gestion.hito set nombre='".$nombre."' , estado='".$vigencia."' where idHito = ".$id.""; 
      
          
        if($this->_modulo->exeSQL($sql)){
        echo "OK"; 
        }
        else{

            throw new Exception("Ha ocurrido un error, favor de cominicarse con el administrador del sistema") ;    
        }
       }
      
        
    }
    public function validaModificaHito($vigencia, $nombre){
        if(!$vigencia){ echo "La vigencia no puede estar vacía"; return false; }
        if(!$nombre){ echo "El nombre no puede estar vacío"; return false; }
        
            return true;
    }
    
    public function asignarUsuarioTarea(){
     $fecha = $this->getTexto('fechaAsignacion');  
     $horas = $this->getTexto('horaAsignadas');
     $clienteInterno = $this->getTexto('usuarioAsignacion');
     
      echo 'OK';
    }
    public function validarAsignarUsuarioTarea(){
      
        return true;
    }
    public function rechazarTarea(){
       
      
      echo 'OK';
    }
}
