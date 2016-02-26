<?php
class Correo{
   
    public function envioCorreoSolicitud($descripcionProblema){
        $html = file_get_contents(ROOT . 'views' . DS . 'sistema' . DS . 'correos' . DS . 'tarea.html');
                $reempl = array('Titulo' => 'Asunto Prueba',    
                    'descripcion proceso' => 'Un comentario para describir el proceso',
                    'Descripcion'=>'se ha asignado una tarea a Jaime Reyes Romero'
                    );

                foreach ($reempl as $nombre => $valor) {
                    $html = str_replace('{' . $nombre . '}', $valor, $html);
                }
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
                    if ($mail->Send()){ return true;}
                     else { return false;}
                        
          
                    
    }
    
}

