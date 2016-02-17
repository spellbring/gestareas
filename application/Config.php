<?php

define('DEFAULT_CONTROLLER', 'index');//en el caso de que no se envie el controlador en la url se llama al index
define('DEFAULT_LAYOUT', 'default');
define('BASE_URL', 'http://192.168.1.33/GestionTareas/');

define('APP_NAME','Gestión de Tareas');
define('APP_SLOGAN','Plataforma Estratégica Global');
define('APP_COMPANY','www.peg.cl');
define('COMPANY', 'Fresno');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smandb');
define('CHARSET','utf-8');

define("CAN_REGISTER", "any");
define("DEFAULT_ROLE", "member");
 
define("SECURE", FALSE);// solo para desarrollar