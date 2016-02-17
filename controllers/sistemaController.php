<?php
class sistemaController extends Controller{
    public function __construct() {
        parent::__construct();
         $this->_organizacion = $this->loadModel('organizacion');
          
    }
    public function index() 
    {
       
       $this->_view->titulo = 'Login';
       if(Session::get('SESS_USER') != null){
        $org_model = $this->_organizacion->getOrganizacionUsuario();
         $this->_view->_organizacion = $org_model;
         $this->_view->renderizarSistema('sistema');
       }
       else
         header('Location: ' . BASE_URL . 'login');
       
    }
    public function abreOrganizacion(){
        
     
     $this->_view->renderizaCenterBox('agregarOrganizacion');
    }
    public function insertOrganizacion(){
        
     $org_descripcion = $this->getTexto('txt_nombreOrganizcion');
     
     
     
     $sql = "INSERT INTO smandb.organizacion(IDORGANIZACION, DESCRIPCION, FECHA, USUARIO_IDUSER)"
             . "VALUES(0, '".$org_descripcion."', CURDATE(), '".Session::get('SESS_ID_USER')."')";   
     $org_model = $this->_organizacion->exeSQL($sql);
     if($org_model){
         
        echo $sql;
     
     }
     else{
         
       throw new Exception('No se ha podido guardar, comuniquese con nuestro staff') ;
        
     }
     // $this->_view->renderizarSistema('sistema');
     
        
    
    }
    public function getOrganizaciones(){
  
     $this->_view->renderizaSistema('sistema');   
    }
    
 
  

}
