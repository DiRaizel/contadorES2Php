<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/third_party/fpdf/fpdf.php';

class F_pdf {

//    public $param;
    public $pdf;

    public function __construct() {
        //
        $this->pdf = new FPDF();
    }

}
