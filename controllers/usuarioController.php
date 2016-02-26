<?php

class usuarioController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->_modelo = $this->loadModel('usuario');
    }

    public function index() {
     if(Session::get("SESS_ID_USER") != null){
         if(Session::get("SESS_ID_TIPO_USER") != 3){
        $this->_view->_titulo = "Administraci&oacute;n de Usuarios";
        $this->_view->_tituloPanel = "Usuarios";
        
        $pagina = filter_input(INPUT_GET, 'pag', FILTER_SANITIZE_SPECIAL_CHARS);
        
        if (empty($pagina)) {
            $pagina = 1;
        }
        
        $this->paginacion($pagina);
     }
     else{
          header('Location: ' . BASE_URL . 'avance');
     }
     }
     else{
         header('Location: ' . BASE_URL . 'login');
     }
    }
    
    function paginacion($pag) {
        
        //Mostrar sólo los primeros X registros
        $LIMITE = 5;

        if (empty($pag)) {
            
            //Valores por defecto - Inicio de página
            $inicio = 0;
            $pagina = 1;
            
        } else {
            
            $pagina = $pag;
            $inicio = ($pagina - 1) * $LIMITE;
        }
       
        //Traemos todos los clientes para ver el total de registros
        $usuarios = $this->_modelo->getUsuario(null);
        
        //Filas totales
        $rows = sizeof($usuarios);
        
        //Paginas
        $pags = ceil($rows / $LIMITE);
        
        $usuariosFiltados = $this->_modelo->pagUsuarios($inicio, $LIMITE);
        
        $this->_view->_paginas = $pags;
        $this->_view->_paginaSeleccionada = $pag;
        $this->_view->_usuarios = $usuariosFiltados;
        $this->_view->renderizarSistema('usuario');
        
    }
    
    function nuevo() {
        
        $usuarios = $this->_modelo->getUsuario(null);
        $this->_view->_usuarios = $usuarios;
        $this->_view->renderizaCenterBox('nuevoUsuario');
    
    }
}