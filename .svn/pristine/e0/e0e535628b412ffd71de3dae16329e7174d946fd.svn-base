/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function initLoad()
{
    $('#divAlertInfo').fadeIn(500);
    $('#divAlertInfo').animate({
        'display': 'block'
    });
}

function endLoad()
{
    $('#divAlertInfo').delay(100).fadeOut(500);
    $('#divAlertInfo').animate({
        'display': 'none'
    });
}

function exito(data) {//  
    endLoad();
    $('#divAlertExito').delay(1000).fadeIn(500);
    $('#divAlertExito').animate({
        'display': 'block'
    });
    $("#msjOk").html(data); 

    $('#divAlertExito').delay(1000).fadeOut(500);
    $('#divAlertExito').animate({
        'display': 'none'
    });
    
}

function noExito(data) {
    endLoad();
    
    $('#divAlertWar').delay(1000).fadeIn(500);
    $('#divAlertWar').animate({
        'display': 'block'
    });
    $("#msjWar").html(data);    

    $('#divAlertWar').delay(500).fadeOut(500);
    $('#divAlertWar').animate({
        'display': 'none'
    });
    

}



