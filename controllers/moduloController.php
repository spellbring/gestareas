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
            if(Session::get("SESS_ID_TIPO_USER") != 3){
                $modulo_model = $this->_modulo->getModuloSist(Session::get("SESS_CLAVE"));
                $this->_view->_moduloProyecto = $modulo_model;
                $tarea_model = $this->_tarea;
                $this->_view->_tarea = $tarea_model;
                $this->_view->renderizarSistema('modulo');
            }
        } else {

            header('Location: ' . BASE_URL . 'avance');
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
        $this->_view->_It_id_tarea = $id;
        if ($tarea) {
            $this->_view->_It_tarea = $tarea[0]->getNombre();
            $this->_view->_It_formulario = $tarea[0]->getFormulario();
            $this->_view->_It_descripcion_problema = $tarea[0]->getDescripcionTarea();
            $this->_view->_It_solucion_propuesta = $tarea[0]->getSolucionPropuesta();
            $this->_view->_It_flujo_correcto = $tarea[0]->getFlujoCorrecto();
            $this->_view->_It_id_estado = $tarea[0]->getId_estado();
            $this->_view->_It_nombre_estado = $tarea[0]->getNombre_estado();
            $this->_view->_It_id_hito = $tarea[0]->getId_hito();
        }
        //EstadoDAO
        $estado = $this->_estado->getEstado();
        $this->_view->_listEstado = $estado;
        //UsuarioDAO
        $usuario = $this->_usuario->getUsuarioInterno();
        $this->_view->_usuarioInterno = $usuario;
        $this->_view->renderizaCenterBox('informacionTicket');
    }

    public function abreAdmModulo() {
        $adm_modulo = $this->_proyecto->getProductosUsuarios();
        $modulos = $this->_modulo->getModuloAdm(0);
        $this->_view->_getModuloAdm = $modulos;
        $this->_view->_getModulo = $adm_modulo;
        $this->_view->renderizarSistema('admModulo');
    }

    public function abreInsertModulo() {
        $adm_modulo = $this->_proyecto->getProductosUsuarios();
        $this->_view->_getModulo = $adm_modulo;
        $this->_view->renderizaCenterBox('admModuloInsert');
    }

    public function abreSolicitudTareas() {
        $adm_modulo = $this->_proyecto->getProductosUsuarios();
        $this->_view->_getModulo = $adm_modulo;
        $this->_view->renderizaCenterBox('solicitudTarea');
    }

    public function abreModificaModulo($id) {
        $modulos = $this->_modulo->getModuloAdm($id);
        $id_hito = 0;
        if ($modulos) {
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
                `Estado_idEstado`,`Nivel_idNivel`,`Hito_idHito`,`Evento_idEvento`,id_usuario_crea_tarea)
                VALUES('" . $nombreTicket . "',
                '" . $descripcionProblema . "','" . $formularioObjetivo . "',
                '" . $flujoCorrecto . "','" . $fechaReporte . "',
                '" . $solucionPropuesta . "',1," . $prioridad . "," . $modulo . "," . $tipologia . ",".Session::get('SESS_ID_USER').");";
        $consulta = $this->_tarea->exeSQL($sql);
        if ($consulta) {
           
                if($this->correo($nombreTicket, $descripcionProblema, $fechaReporte, 'Solicitud de tarea', Session::get('SESS_NOMBRE'), 'jreyes@peg.cl' )){
                    echo "OK";
                    
                }
                else{
                     echo "No se pudo enviar el correo";
                }
            
        } else {
            echo 'No se pudo insertar la tarea';
        }
        //echoS 'OK';
    }

    public function insertModulo() {
        $nombre = $this->getTexto("inputName");
        $id = $this->getTexto("selectProducto");

        $sql = "INSERT INTO gestion.hito(nombre, Producto_idProducto) values ('" . $nombre . "', " . $id . ");";
        $consulta = $this->_tarea->exeSQL($sql);
        if ($consulta) {
            echo "OK";
        } else {
            echo 'No se pudo insertar la el Módulo';
        }
        //echoS 'OK';
    }

    public function validaInsertModulo($nombre, $id) {
        if (!$nombre) {
            echo "Ingrese un nombre para el Hito";
        }
        if (!$id) {
            echo "Seleccione un proyecto";
        }

        return true;
    }

    public function comboHitos($id) {

        $adm_modulo = $this->_modulo->getModuloSist($id);
        $hito = "";
        foreach ($adm_modulo as $mod) {
            $hito .= "<option value='" . $mod->getIdHito() . "'>" . utf8_encode($mod->getNombre()) . "</option>";
        }
        echo $hito;
        //$this->_view->renderizaCenterBox('solicitudTarea');
    }

    public function correo($nombreTarea, $descripcionTarea, $fechaReporte, $tipo, $usuarioNombre, $correo){
        $html = file_get_contents(ROOT . 'views' . DS . 'sistema' . DS . 'correos' . DS . 'tarea.html');
            $reempl = array('Titulo' => $nombreTarea,
                'descripcionTarea' => $descripcionTarea,
                'FechaReporte' => $fechaReporte,
                'Tipo' => $tipo,
                'NombreApellidoUser' => $usuarioNombre
            );

            foreach ($reempl as $nombre => $valor) {
                $html = str_replace('{' . $nombre . '}', $valor, $html);
            }

            if (!empty($nombreTarea)) {
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
                $mail->Body = "";

                $mail->MsgHTML($html);

                $mail->AddAddress($correo, $usuarioNombre);
                if ($mail->Send()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
    }
    
    public function correoIniciar($nombreTarea, $fechaInicio, $fechaTermino,$fechaAsignacion, $fechaFinalizacion, $usuarioNombre, $horas, $correo, $correoSession, $nombreUsuario){
        $html = file_get_contents(ROOT . 'views' . DS . 'sistema' . DS . 'correos' . DS . 'tareaIniciada.html');
            $reempl = array('Titulo' => $nombreTarea,
                'nombreUsuario' => $usuarioNombre,
                'FechaInicio' => $fechaInicio,
                'FechaTermino' => $fechaTermino,
                'FechaAsignacion' => $fechaAsignacion,
                'FechaFinalizacion' => $fechaFinalizacion,
                'Horas' => $horas,
                'Tipo' => 'Inicio de Tarea'
                
            );

            foreach ($reempl as $nombre => $valor) {
                $html = str_replace('{' . $nombre . '}', $valor, $html);
            }

            if (!empty($nombreTarea)) {
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
                $mail->FromName = 'Asignación de trabajo';
                $mail->CharSet = CHARSET;
                $mail->Subject = 'Asignación de trabajo: ';
                $mail->Body = "";

                $mail->MsgHTML($html);

                $mail->AddAddress($correo, $usuarioNombre);
                $mail->AddAddress($correoSession,$nombreUsuario);
                if ($mail->Send()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
    }
    public function correoFinalizar($nombreTarea, $fechaFinalizacion, $usuarioNombre, $correo, $correoSession, $nombreSession){
        $html = file_get_contents(ROOT . 'views' . DS . 'sistema' . DS . 'correos' . DS . 'tareaFinalizada.html');
            $reempl = array('Titulo' => $nombreTarea,
                'nombreUsuario' => $usuarioNombre,
                'FechaFinalizacion' => $fechaFinalizacion,
                'Tipo' => 'Finalizaci&oacute;n de tarea'
                
            );

            foreach ($reempl as $nombre => $valor) {
                $html = str_replace('{' . $nombre . '}', $valor, $html);
            }

            if (!empty($nombreTarea)) {
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
                $mail->FromName = 'Finalización de trabajo';
                $mail->CharSet = CHARSET;
                $mail->Subject = 'Finalización de trabajo: ';
                $mail->Body = "";

                $mail->MsgHTML($html);

                $mail->AddAddress($correo, $usuarioNombre);
                $mail->AddAddress($correoSession, $nombreSession);
                if ($mail->Send()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
    }
    
    public function insertSolicitud() {
        $fechaReporte = date('Y.m.d');
        //echo $fechaReporte;
        $modulo = $this->getTexto("st_hito");
        
        $nombreTicket = $this->getTexto("it_txtNombreTicket");
        $formularioObjetivo = $this->getTexto("it_txtFormularioObjetivo");
        $descripcionProblema = $this->getTexto("it_txtaDescripcionProblema");
        $solucionPropuesta = $this->getTexto("it_txtaSolucionPropuesta");
        $flujoCorrecto = $this->getTexto("it_txtaFlujoCorrecto");
        $tipologia = $this->getTexto("rdbtnTipoologia");
        $prioridad = $this->getTexto("rdbtnPrioridad");
        $sql = "INSERT INTO `gestion`.`tarea`(`nombre`,`descripcion`,`formulario`,`flujoCorrecto`,`fechaReporte`,`solucionPropuesta`,
                `Estado_idEstado`,`Nivel_idNivel`,`Hito_idHito`,`Evento_idEvento`,id_usuario_crea_tarea)
                VALUES('" . $nombreTicket . "',
                '" . $descripcionProblema . "','" . $formularioObjetivo . "',
                '" . $flujoCorrecto . "','" . $fechaReporte . "',
                '" . $solucionPropuesta . "',1," . $prioridad . "," . $modulo . "," . $tipologia . ",". Session::get('SESS_ID_USER').");";
        $consulta = $this->_tarea->exeSQL($sql);
        if ($consulta) {
            if($this->correo($nombreTicket,$descripcionProblema, $fechaReporte, 'Solicitud de tarea', Session::get('SESS_NOMBRE'), 'jreyes@peg.cl' )){
                echo "OK";
            }
            else{
                echo "No se pudo enviar el correo";
            }
        } else {
            echo 'No se pudo insertar la tarea';
        }
        //echoS 'OK';
    }

    public function modificaHito() {
        $id = $this->getTexto('Mm_id_hito');
        $vigencia = $this->getTexto('Mm_vigencia');
        $nombre = $this->getTexto('Mm_nombre_hito');
        if ($this->validaModificaHito($vigencia, $nombre) == false) {
            
        } else {
            $sql = "UPDATE gestion.hito set nombre='" . $nombre . "' , estado='" . $vigencia . "' where idHito = " . $id . "";


            if ($this->_modulo->exeSQL($sql)) {
                echo "OK";
            } else {

                throw new Exception("Ha ocurrido un error, favor de cominicarse con el administrador del sistema");
            }
        }
    }

    public function validaModificaHito($vigencia, $nombre) {
        if (!$vigencia) {
            echo "La vigencia no puede estar vacía";
            return false;
        }
        if (!$nombre) {
            echo "El nombre no puede estar vacío";
            return false;
        }

        return true;
    }

    

    public function rechazarTarea() {
        $decripcionMotivo = $this->getTexto('it_descripcion_tarea_rechazo');
        $estado = 5;
        $idTarea = $this->getTexto('it_id_tarea_rechazo');
        $sql = "update gestion.tarea set motivoRechazo = '" . $decripcionMotivo . "' , Estado_idEstado = " . $estado . " where idTarea = " . $idTarea . "";
        if ($this->_modulo->exeSQL($sql)) {

            echo 'OK';
        } else {
            echo 'No se ha podido rechazar la tarea, comuníquese con el administrador';
        }
    }

    public function iniciarTarea() {
        //Variables a utilizar
        $fechaInicio = $this->getTexto("fechaIncioInicio");
        $fechaTermino = $this->getTexto("fechaTerminoInicio");
        $id_tarea = $this->getTexto("it_id_tarea_iniciar");
        $sql = "UPDATE gestion.tarea set fechaInicio = '" . $fechaInicio . "', fechaTermino = '" . $fechaTermino . "', Estado_idEstado = 2 where idTarea = " . $id_tarea . "";
        $id_tarea1 = $this->getTexto("it_id_tarea_iniciar");
        $id_usuario = $this->getTexto("usuarioAsignacion");
        $fechaAsignacion = $this->getTexto("fechaAsignacion");
        $fechaFinalizacion = $this->getTexto("fechaTermino");
        $horasDias = $this->getTexto("horaAsignadas");
        $nombreTarea = $this->getTexto("it_nombre_tarea");
        $nombreUsuario = "";
        //$correo = "";
        
        //Hito
        $hito = $this->getTexto("it_id_hito_iniciar");
        $sql1 = "INSERT INTO gestion.tarea_usuario(Tarea_idTarea, Usuario_idUsuario, fechaAsignacion, horasDia, fechaTerminoActividad) VALUES (" . $id_tarea1 . "," . $id_usuario . ", '" . $fechaAsignacion . "'," . $horasDias . ", '" . $fechaFinalizacion . "')";
        $cantidadRegistros = $this->_modulo->cantidadHitosUsuarios($hito, $id_usuario);
        //echo $id_usuario;
        //Usuarios para obtener el nombre, este tiene que ser mostrado en el correo electrónico
        $usuarios1 = $this->_usuario->getUsuarioId($id_usuario);  
        //if($usuarios){
        //var_dump($usuarios1);
        $nombreUsuario = $usuarios1[0]->getNombre().' '.$usuarios1[0]->getApellido();
        $correo = $usuarios1[0]->getEmail();
        //}
                
        //Logica del método
        if($cantidadRegistros){
            if($cantidadRegistros[0]->getIdHito() < 1){
                $sql3 = "INSERT INTO gestion.hito_usuario (Hito_idHito, Usuario_idUsuario) VALUES (".$hito.",".$id_usuario.") ";
                $this->_modulo->exeSQL($sql3);
            }
        }
        if ($this->_modulo->exeSQL($sql)) {
            if ($this->_modulo->exeSQL($sql1)) {
                    if($this->correoIniciar($nombreTarea, $fechaInicio, $fechaTermino, $fechaAsignacion,  $fechaFinalizacion, $nombreUsuario, $horasDias, $correo, Session::get('SESS_CORREO'), Session::get('SESS_NOMBRE')) == true){
                         echo "OK"; 
                    }
                    else{
                        echo "No se pudo enviar el correo electrónico al destinatario"; 
                    }
                   
            }
            else{
                  echo 'No se ha podido iniciar la tarea, comuníquese con el administrador';
            }
           
        } else {
            echo 'No se ha podido iniciar la tarea, comuníquese con el administrador';
        }
    }

    public function iniciarProceso() {
        $id_tarea = $this->getTexto("it_id_tarea_proceso");
        $id_usuario = Session::get("SESS_ID_USER");
        $sql = "UPDATE gestion.tarea_usuario set fechaInicio = current_timestamp() where Tarea_idTarea = " . $id_tarea . " and Usuario_idUsuario = " . $id_usuario . ";";
        $sql2 = "UPDATE gestion.tarea set Estado_idEstado = 3 where idTarea = " . $id_tarea . "";
        if ($this->_modulo->exeSQL($sql)) {
            if ($this->_modulo->exeSQL($sql2)) {
                echo "OK";
            } else {
                echo 'No se ha podido dejar en estado en proceso, comuníquese con el administrador';
            }
        } else {
            echo 'No se ha podido dejar en estado en proceso, comuníquese con el administrador';
        }
    }

    public function finalizarProceso() {
        $fechaReporte = date('Y.m.d H:i:s');
        $nombre_tarea = $this->getTexto("it_nombre_tarea_finalizado");
        $id_tarea = $this->getTexto("it_id_tarea_finalizado");
        $id_usuario = Session::get("SESS_ID_USER");
        $nombre_usuario = Session::get("SESS_NOMBRE");
        //$correo = Session::get("SESS_CORREO");
        $sql = "UPDATE gestion.tarea_usuario set fechaFinalizacion = current_timestamp() where Tarea_idTarea = " . $id_tarea . " and Usuario_idUsuario = " . $id_usuario . ";";
        $sql2 = "UPDATE gestion.tarea set Estado_idEstado = 4 where idTarea = " . $id_tarea . "";
        
        $usuarioTarea = $this->_usuario->getUsuarioTarea($id_tarea);
        
        $nombre = $usuarioTarea[0]->getNombre()." ".$usuarioTarea[0]->getApellido();
        $correo = $usuarioTarea[0]->getEmail();
        if ($this->_modulo->exeSQL($sql)) {
            if ($this->_modulo->exeSQL($sql2)) {
                if($this->correoFinalizar($nombre_tarea, $fechaReporte, $nombre, $correo, 'jreyes@peg.cl', $nombre_usuario)){
                     echo "OK";
                }
                else{
                    echo "No se pudo enviar el correo";
                }
               
            } else {
                
                echo 'No se ha podido dejar en estado en proceso, comuníquese con el administrador';
            }
        } else {
            echo 'No se ha podido dejar en estado en proceso, comuníquese con el administrador';
        }
    }

    public function abreAsignaUsuarioProducto(){
        $usuario = $this->_usuario->getUsuario("");
        $proyecto = $this->_proyecto->getTodosProyectos();
        
        $this->_view->proyectos = $proyecto;
        $this->_view->usuarios = $usuario;
        $this->_view->renderizaCenterBox('asignaUsuarioProducto');
    }
    
    public function insertUsuarioProyecto(){
        $usuario = $this->getTexto('sel_usuario');
        $proyecto = $this->getTexto('sel_proyecto');
        $consultaProyectoUsuario = $this->_proyecto->getProyectoUsuario($proyecto, $usuario);
       
            if($consultaProyectoUsuario[0]->getId_producto()){
                   echo "No se puede"; 
            }
            else{
               $sql = "INSERT INTO gestion.producto_usuario (producto_idProducto, usuario_idUsuario) VALUES (".$proyecto.", ".$usuario.")";
                    if($this->_modulo->exeSQL($sql)){
                       echo "OK"; 
                    }
                    else{
                        echo "No se ha podido asignar un proyecto al usuario especificado"; 
                    } 

            }
        }
       
        
        
        
        
               
        
    

}
