<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Allow from any origin
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

class Create extends CI_Controller {

    public function __construct() {
        //
        parent::__construct();
        //
        $this->load->model('Empresa');
        $this->load->model('Local');
        $this->load->model('Sede');
    }

//----------------------------------Empresa-------------------------------------
//
    //
    function registro() {
        //
        $rsp = $this->Empresa->registro();
        //
        echo json_encode($rsp);
    }
    
    //
    function guardarEntrada() {
        //
        $rsp = $this->Empresa->guardarEntrada();
        //
        echo json_encode($rsp);
    }
    
    //
//    function registro2() {
//        //
//        $rsp = $this->Local->registro();
//        //
//        echo json_encode($rsp);
//    }

//------------------------------------Home--------------------------------------
//
    //
//    function guardarSede() {
//        //
//        $rsp = $this->Sede->guardarSede();
//        //
//        echo json_encode($rsp);
//    }

//------------------------------------Sede--------------------------------------
//
    //
//    function agregarUsuarioLocal() {
//        //
//        $rsp = $this->Local->agregarUsuarioLocal();
//        //
//        echo json_encode($rsp);
//    }
}
