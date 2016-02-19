<?php
/*
 * Clase dedicada en realizar las llamadas a los Controloadores y metodos
 * existentes en el FW. JRR-07/11/2015
 */
class Bootstrap
{
 public static function run(Request $peticion)
 {
    $controller = $peticion->getControlador() . "Controller";
    $rutaControlador  = ROOT . 'controllers'. DS . $controller . '.php';
    $metodo = $peticion->getMetodo();
    $args = $peticion ->getArgs();
    
    
    //echo $rutaControlador; exit; 
    if(is_readable($rutaControlador)){
        require_once $rutaControlador;
    
        $controller = new $controller;
        
        if(is_callable(array($controller, $metodo)))
        {
            $metodo  = $peticion->getMetodo();
        
        }
        else{
            
           $metodo= 'index';
        }
        
        if(isset($args)){
            call_user_func_array(array($controller, $metodo), $args);
            
        
        }
        else{
             call_user_func_array(array($controller, $metodo));
        }
    }
    else{
        
        throw new Exception('No encontrado');
    }
 }    

}

