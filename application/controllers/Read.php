<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//// Allow from any origin
//if (isset($_SERVER['HTTP_ORIGIN'])) {
//    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
//    header('Access-Control-Allow-Credentials: true');
//    header('Access-Control-Max-Age: 86400');    // cache for 1 day
//}
//// Access-Control headers are received during OPTIONS requests
//if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
//        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
//    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
//        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
//    exit(0);
//}

class Read extends CI_Controller {

    public function __construct() {
        //
        parent::__construct();
        //
        $this->load->model('Empresa');
        $this->load->model('Local');
        $this->load->model('Sede');
    }

//----------------------------------Index---------------------------------------
//
    //
    function login() {
        //
        $rsp = $this->Empresa->login();
        //
        echo json_encode($rsp);
    }

    //
//    function login2() {
//        //
//        $rsp = $this->Local->login();
//        //
//        echo json_encode($rsp);
//    }

    //
    function recuperarPass() {
        //
        $rsp = $this->Empresa->recuperarPass();
        //
        echo $rsp;
    }
    
    //
    function cargarDepartamentos() {
        //
        $rsp = $this->Empresa->cargarDepartamentos();
        //
        echo json_encode($rsp);
    }

    //
    function cargarCiudades() {
        //
        $rsp = $this->Empresa->cargarCiudades();
        //
        echo json_encode($rsp);
    }

    //
    function cargarSectores() {
        //
        $rsp = $this->Empresa->cargarSectores();
        //
        echo json_encode($rsp);
    }
    
//---------------------------------Home-----------------------------------------
//
    //
//    function cargarSedes() {
//        //
//        $rsp = $this->Sede->cargarSedes();
//        //
//        echo json_encode($rsp);
//    }
    
    //
    function cargarSlider() {
        //
        $rsp = $this->Empresa->cargarSlider();
        //
        echo json_encode($rsp);
    }
    
    //
    function actualizarPersonas() {
        //
        $rsp = $this->Empresa->actualizarPersonas();
        //
        echo json_encode($rsp);
    }
    
    //
    function generarPdf() {
        //
        $rsp = $this->Empresa->generarPdf2();
        //
        echo json_encode($rsp);
    }
    
    //
    function consultarLimitePersonas() {
        //
        $rsp = $this->Empresa->consultarLimitePersonas();
        //
        echo json_encode($rsp);
    }
    
//---------------------------------Entrada--------------------------------------
//
    //
    function traerInfo() {
        //
        $rsp = $this->Empresa->traerInfo();
        //
        echo json_encode($rsp);
    }
    
//---------------------------------Local----------------------------------------
//
    //
//    function cargarLocalesSede() {
//        //
//        $rsp = $this->Local->cargarLocalesSede();
//        //
//        echo json_encode($rsp);
//    }
//    
//    //
//    function cargarSede() {
//        //
//        $rsp = $this->Sede->cargarSede();
//        //
//        echo json_encode($rsp);
//    }
}
