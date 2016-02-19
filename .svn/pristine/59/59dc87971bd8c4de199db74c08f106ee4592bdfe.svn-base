<?php
abstract class Controller
{
 //Obliga que a todos los que hereden de controlador se tengan que implementar
 // este metodo index();
   protected $_view;
   // se realiza un llamado a la vista desde el controlador principal
   public function __construct() {
       $this->_view = new View(new Request);
   }
   abstract public function index();
   
   
     protected function loadModel($class) {// se carga el modelo que se ocupara.
        $dao = $class . 'DAO';
        $dto = $class . 'DTO';
        $rutaDAO = ROOT . 'models' . DS . 'dao' . DS . $dao . '.php';// se concatenan los modelos para hacer la 
        $rutaDTO = ROOT . 'models' . DS . 'dto' . DS . $dto . '.php';
        $rutaDetalleDTO = ROOT . 'models' . DS . 'dto' . DS . 'detalle' . $dto . '.php';//?
        if (is_readable($rutaDAO)) {// si se pueden leer DAO
            if (is_readable($rutaDTO)) {//si se puede leer DTO
                require_once $rutaDAO; // se carga en la pagina tanto DTO Y DAO.
                require_once $rutaDTO;
                if (is_readable($rutaDetalleDTO)) {
                    require_once $rutaDetalleDTO;
                }
                $dao = new $dao;
                return $dao; //retorna la instancia del modelo
            } else {
                throw new Exception('Error al cargar el DTO: ' . $rutaDTO);
            }
        } else {
            throw new Exception('Error al cargar el DAO: ' . $rutaDAO);
        }
    }
    protected function getTexto($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave]))
        {
            $_POST[$clave]= htmlspecialchars($_POST[$clave], ENT_QUOTES);
            return trim($_POST[$clave]);
        }
    }
    
    protected function getInt($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave]))
        {
            $_POST[$clave]= filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
            return trim($_POST[$clave]);
        }
        
        return 0;
    }
    
    
    protected function redireccionar($ruta = false)
    {
        if($ruta){
            header('location:' . BASE_URL . $ruta);
            exit;
        }
        else{
            header('location:' . BASE_URL);
            exit;
        }
    }
    
    
    protected function getSql($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = strip_tags($_POST[$clave]);
            
            if(!get_magic_quotes_gpc()){
                $_POST[$clave] = mysql_escape_string($_POST[$clave]);
            }
            
            return trim($_POST[$clave]);
        }
    }
    
    protected function getAlphaNum($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_POST[$clave]);
            return trim($_POST[$clave]);
        }
        
    }
    
    protected function getLibrary($libreria)
    {
        $rutaLibreria = ROOT . 'libs' . DS . $libreria . '.php';
        
        if(is_readable($rutaLibreria)){
            require_once $rutaLibreria;
        }
        else{
            throw new Exception('Error de libreria');
        }
    }
    
    protected function filtrarInt($int)
    {
        $int = (int) $int;
        
        if(is_int($int)){
            return $int;
        }
        else{
            return 0;
        }
    }
    
    protected function getServer($clave)
    {
        if(!empty($_SERVER[$clave]))
        {
            return $_SERVER[$clave];
        }
    }
    public function getCorreo($correo){
        if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
       
        
    }
}

