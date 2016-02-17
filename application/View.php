<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class View
{
    private $_controlador;
    public function __construct(Request $peticion) {
        $this->_controlador = $peticion->getControlador();
    }
    
    
    public function renderizaLogin($vista, $item=false){
        
        $_layoutParams = array(
            'ruta_css' => BASE_URL . 'views/layout/'.DEFAULT_LAYOUT.'/css/',
            'ruta_img' => BASE_URL . 'views/layout/'.DEFAULT_LAYOUT.'/img/',
            'ruta_js' => BASE_URL . 'views/layout/'.DEFAULT_LAYOUT.'/js/',
            'ruta_public_js' =>BASE_URL .'public/js/',
            'ruta_template' =>BASE_URL . 'views/layout/'.DEFAULT_LAYOUT.'/template/'
        );
        
        $rutaView = ROOT . 'views' . DS . $this->_controlador . DS . $vista . '.phtml';
        if(is_readable($rutaView)){
            //include_once ROOT . 'views' .  DS . 'layout' . DS . DEFAULT_LAYOUT .DS .'header.php';
            include_once $rutaView;
            //include_once ROOT . 'views' .  DS . 'layout' . DS . DEFAULT_LAYOUT .DS .'footer.php';
        }
        else{
            throw new Exception('Error de vista');
        }
    }
    
    public function renderizarSistema($vista, $item=false){
        
        $_layoutParams = array(
            'ruta_css' => BASE_URL . 'views/layout/'.DEFAULT_LAYOUT.'/css/',
            'ruta_img' => BASE_URL . 'views/layout/'.DEFAULT_LAYOUT.'/img/',
            'ruta_js' => BASE_URL . 'views/layout/'.DEFAULT_LAYOUT.'/js/',
            'ruta_public_js' =>BASE_URL .'public/js/',         
            'ruta_template' =>BASE_URL . 'views/layout/'.DEFAULT_LAYOUT.'/template/'
        );
        
        $rutaView = ROOT . 'views' . DS . $this->_controlador . DS . $vista . '.phtml';
        if(is_readable($rutaView)){
            include_once ROOT . 'views' .  DS . 'layout' . DS . DEFAULT_LAYOUT .DS .'header.php';
            include_once $rutaView;
            include_once ROOT . 'views' .  DS . 'layout' . DS . DEFAULT_LAYOUT .DS .'footer.php';
        }
        else{
            throw new Exception('Error de vista');
        }
    }
    
    public function renderizaCenterBox($vista, $item=false)
    {
        $_layoutParams = array(
            'ruta_public_js' =>BASE_URL .'public/js/',
            'ruta_css' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/css/', 
            'ruta_img' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/img/', 
            'ruta_js' => BASE_URL . 'views/layout/' . DEFAULT_LAYOUT . '/js/',
            'ruta_template' =>BASE_URL . 'views/layout/'.DEFAULT_LAYOUT.'/template/'
            
        );
        $rutaView= ROOT . 'views' . DS . $this->_controlador . DS . 'centerBox' . DS . $vista . '.phtml';
        if(is_readable($rutaView))
        {
            include_once $rutaView;
        }
        else
        {
            throw new Exception('Error de vista AJAX');
        }
    }
}

