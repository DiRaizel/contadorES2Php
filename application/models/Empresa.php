<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//
date_default_timezone_set('America/Bogota');

class Empresa extends CI_Model {

    //
    function login() {
        //
        $correo = $this->input->post("correo");
        $password = $this->input->post("password");
        //
        $query = $this->db->query("select e.emp_id, e.emp_nombre, e.emp_nit, "
                . "e.emp_correo, e.emp_estado, e.ciu_id, e.emp_estado_password,"
                . " e.sec_id, s.sed_id, c.dep_id from empresa e join sede s on "
                . "e.emp_id = s.emp_id join ciudad c on e.ciu_id = c.ciu_id  "
                . "where e.emp_correo = '$correo' and e.emp_password ='"
                . base64_encode($password) . "'");
        //
        $datos = array();
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                $datos = array(
                    'estado' => 'Entra',
                    'rol' => 'Empresa',
                    'idUsu' => $row->emp_id,
                    'nombre' => $row->emp_nombre,
                    'nit' => $row->emp_nit,
                    'correo' => $row->emp_correo,
                    'estadoU' => $row->emp_estado,
                    'estadoP' => $row->emp_estado_password,
                    'idCiu' => $row->ciu_id,
                    'idDep' => $row->dep_id,
                    'sector' => $row->sec_id,
                    'password' => $password,
                    'idSed' => $row->sed_id
                );
            }
            //
            return $datos;
        } else {
            //
            return $datos['estado'] = 'Error';
        }
    }

    //
    function recuperarPass() {
        //
        $correo = $this->input->post("correo");
        $codigo = time();
        //
        $datos = array(
            'emp_password' => base64_encode($codigo),
            'emp_estado_password' => 'Recuperando',
        );
        //
        $this->db->where('emp_correo', $correo);
        //
        if ($this->db->update('empresa', $datos)) {
            //
            $this->load->library('email');
            //
            $this->email->from('contadorES@admin', 'Admin');
            $this->email->to($correo);
            //
            $this->email->subject('Código de activación');
            $this->email->message($codigo);
            //
            if ($this->email->send()) {
                //
                return 'Enviado';
            } else {
                //
                return 'Error';
            }
        }
    }

    //
    function actualizarPass() {
        //
        $idUsu = $this->input->post("idUsu");
        $pass = $this->input->post("pass");
        //
        $datos = array(
            'emp_password' => base64_encode($pass),
            'emp_estado_password' => 'Normal',
        );
        //
        $this->db->where('emp_id', $idUsu);
        //
        if ($this->db->update('empresa', $datos)) {
            //
            return 'Actualizada';
        } else {
            //
            return 'Error';
        }
    }

    //
    function registro() {
        //capturar Valores enviados por post
        $nombre = $this->input->post("nombre");
        $nit = $this->input->post("nit");
        $correo = $this->input->post("correo");
        $sector = $this->input->post("sector");
        $ciudad = $this->input->post("ciudad");
        $password = base64_encode($this->input->post("password"));
        //
        $datos = array(
            'emp_nombre' => $nombre,
            'emp_nit' => $nit,
            'emp_password' => $password,
            'emp_correo' => $correo,
            'emp_estado' => 'Activa',
            'emp_estado_password' => 'Normal',
            'ciu_id' => $ciudad,
            'sec_id' => $sector
        );
        //
        $query = $this->db->query("SELECT emp_id FROM empresa where emp_correo = '$correo'");
        //
        if (count($query->result()) > 0) {
            //
            return array('estado' => 'existe');
        } else {
            //
            if ($this->db->insert('empresa', $datos)) {
                //
                $datos2 = array(
                    'sed_nombre' => 'Principal',
                    'sed_estado' => 'Activa',
                    'ciu_id' => $ciudad,
                    'emp_id' => $this->db->insert_id()
                );
                //
                if ($this->db->insert('sede', $datos2)) {
                    //
                    return array('estado' => 'registrada');
                } else {
                    //
                    return array('estado' => 'error2');
                }
            } else {
                //
                return array('estado' => 'error');
            }
        }
    }

    //
    function editarEmpresa() {
        //
        $idEmp = $this->input->post("idEmp");
        $correoA = $this->input->post("correoA");
        $nombre = $this->input->post("nombre");
        $nit = $this->input->post("nit");
        $correo = $this->input->post("correo");
        $sector = $this->input->post("sector");
        $ciudad = $this->input->post("ciudad");
        $password = $this->input->post("password");
        //
        $datos = array(
            'emp_nombre' => $nombre,
            'emp_nit' => $nit,
            'emp_password' => base64_encode($password),
            'emp_correo' => $correo,
            'ciu_id' => $ciudad,
            'sec_id' => $sector
        );
        //
        $controlC = true;
        //
        if ($correoA !== $correo) {
            //
            $query = $this->db->query("SELECT emp_id FROM empresa "
                    . "where emp_correo = '$correo'");
            //
            if (count($query->result()) > 0) {
                //
                $controlC = false;
            }
        }
        //
        if ($controlC) {
            //
            $this->db->where('emp_id', $idEmp);
            //
            if ($this->db->update('empresa', $datos)) {
                //
                return 'editado';
            } else {
                //
                return 'error';
            }
            //
        } else {
            //
            return 'existe';
        }
    }

    //
    function cargarSectores() {
        //
        $query = $this->db->query('SELECT sec_id, sec_nombre FROM sector order by sec_nombre asc');
        //
        $datos = array();
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                $datosTemp = array(
                    'idSec' => $row->sec_id,
                    'nombre' => $row->sec_nombre
                );
                //
                array_push($datos, $datosTemp);
            }
        }
        //
        return $datos;
    }

    //
    function cargarDepartamentos() {
        //
        $query = $this->db->query('SELECT dep_id, dep_nombre FROM departamento order by dep_nombre asc');
        //
        $datos = array();
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                $datosTemp = array(
                    'cod' => $row->dep_id,
                    'departamento' => $row->dep_nombre
                );
                //
                array_push($datos, $datosTemp);
            }
        }
        //
        return $datos;
    }

    //
    function cargarCiudades() {
        //
        $codDep = $this->input->post("codDep");
        //
        $query = $this->db->query("SELECT ciu_id , ciu_nombre FROM ciudad "
                . "where dep_id  = $codDep order by ciu_nombre asc");
        //
        $datos = array();
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                $datosTemp = array(
                    'cod' => $row->ciu_id,
                    'ciudad' => $row->ciu_nombre,
                );
                //
                array_push($datos, $datosTemp);
            }
        }
        //
        return $datos;
    }

    //
    function cargarSlider() {
        //
        $sector = $this->input->post("sector");
        //
        $query = $this->db->query("SELECT sli_id, sli_imagen, sli_url FROM "
                . "slider where sli_sector = $sector and sli_estado = "
                . "'Activa' order by sli_id asc");
        //
        $datos = array();
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                array_push($datos, array(
                    'imagen' => $row->sli_imagen,
                    'idSli' => $row->sli_id,
                    'url' => $row->sli_url
                ));
            }
        }
        //
        return $datos;
    }

    //
    function actualizarPersonas() {
        //
        $idEmp = $this->input->post("idEmp");
        $idSed = $this->input->post("idSed");
        //
        $query = $this->db->query("SELECT ensa_poblacion from entrada_salida "
                . "where emp_id = $idEmp and sed_id = $idSed");
        //
        $datos = array();
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                array_push($datos, array(
                    'poblacion' => $row->ensa_poblacion
                ));
            }
        }
        //
        return $datos;
    }

    //
    function traerInfo() {
        //
        $documento = $this->input->post("documento");
        //
        $database2 = $this->load->database('poblacion', TRUE);
//        $this->db->db_select('poblacion');
        //
        $query = $database2->query("SELECT per_id, per_nombres, per_apellidos, "
                . "per_telefono, per_direccion FROM persona where per_documento"
                . "  = '$documento'");
        //
        $datos = array();
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                array_push($datos, array(
                    'idPer' => $row->per_id,
                    'nombres' => $row->per_nombres,
                    'apellidos' => $row->per_apellidos,
                    'telefono' => $row->per_telefono,
                    'direccion' => $row->per_direccion
                ));
            }
        }
        //
        return $datos;
    }

    //
    function guardarEntrada() {
        //
        $database2 = $this->load->database('poblacion', TRUE);
        //capturar Valores enviados por post
        $idEmp = (int) $this->input->post("idEmp");
        $idSed = (int) $this->input->post("idSed");
        $idPer = (int) $this->input->post("idPer");
        $documento = $this->input->post("documento");
        $nombres = $this->input->post("nombres");
        $apellidos = $this->input->post("apellidos");
        $telefono = $this->input->post("telefono");
        $direccion = $this->input->post("direccion");
        $temperatura = $this->input->post("temperatura");
        //
        $datos = array(
            'per_nombres' => $nombres,
            'per_apellidos' => $apellidos,
            'per_documento' => $documento,
            'per_telefono' => $telefono,
            'per_direccion' => $direccion,
            'per_fecha' => date("Y-m-d")
        );
        //
        $control = true;
        //
        if ($idPer === 0) {
            //
            if ($database2->insert('persona', $datos)) {
                //
                $idPer = $database2->insert_id();
                //
                $this->db->insert('cliente', array(
                    'cli_id ' => $idPer,
                    'cli_nombres' => $nombres,
                    'cli_apellidos' => $apellidos,
                    'cli_documento' => $documento,
                    'cli_telefono' => $telefono,
                    'cli_direccion' => $direccion,
                    'cli_fecha' => date("Y-m-d")
                ));
            } else {
                //
                $control = false;
            }
        } else {
            //
            $query2 = $this->db->query("SELECT cli_nombres FROM cliente where "
                    . "cli_id = $idPer");
            //
            if (count($query2->result()) === 0) {
                //
                $this->db->insert('cliente', array(
                    'cli_id ' => $idPer,
                    'cli_nombres' => $nombres,
                    'cli_apellidos' => $apellidos,
                    'cli_documento' => $documento,
                    'cli_telefono' => $telefono,
                    'cli_direccion' => $direccion,
                    'cli_fecha' => date("Y-m-d")
                ));
            }
            //
            $query = $this->db->query("SELECT reg_tipo FROM registro_entrada_"
                    . "salida where cli_id = $idPer order by reg_id desc limit 1");
            //
            if (count($query->result()) > 0) {
                //
                foreach ($query->result() as $row) {
                    //
                    if ($row->reg_tipo == 'Entrada') {
                        //
                        $control = false;
                        return array('estado' => 'dentro');
                    }
                }
            }
        }
        //
        if ($control) {
            //
            $datos2 = array(
                'emp_id' => $idEmp,
                'sed_id' => $idSed,
                'cli_id' => $idPer,
                'reg_temperatura' => $temperatura,
                'reg_tipo' => 'Entrada',
                'reg_hora' => date("H:i:s"),
                'reg_fecha' => date("Y-m-d")
            );
            //
            if ($this->db->insert('registro_entrada_salida', $datos2)) {
                //
                $query = $this->db->query("SELECT ensa_id, ensa_poblacion FROM"
                        . " entrada_salida where emp_id = $idEmp and sed_id = $idSed");
                //
                if (count($query->result()) > 0) {
                    //
                    foreach ($query->result() as $row) {
                        //
                        $query2 = $this->db->query("update entrada_salida set "
                                . "ensa_poblacion = ensa_poblacion + 1 where "
                                . "ensa_id = $row->ensa_id");
                        //
                        if ($query2) {
                            //
                            return array('estado' => 'registrado');
                        } else {
                            //
                            return array('estado' => 'error2');
                        }
                    }
                } else {
                    //
                    if ($this->db->insert('entrada_salida', array(
                                'ensa_poblacion' => 1,
                                'emp_id' => $idEmp,
                                'sed_id' => $idSed,
                            ))) {
                        //
                        return array('estado' => 'registrado');
                    } else {
                        //
                        return array('estado' => 'error3');
                    }
                }
            } else {
                //
                return array('estado' => 'error');
            }
        }
    }

    //
    function guardarSalida() {
        //
        $idPer = $this->input->post("idPer");
        $idEmp = $this->input->post("idEmp");
        $idSed = $this->input->post("idSed");
        //
        $query = $this->db->query("SELECT reg_tipo FROM registro_entrada_"
                . "salida where cli_id = $idPer order by reg_id desc limit 1");
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                if ($row->reg_tipo == 'Entrada') {
                    //
                    $datos = array(
                        'emp_id' => $idEmp,
                        'sed_id' => $idSed,
                        'cli_id' => $idPer,
                        'reg_temperatura' => 0,
                        'reg_tipo' => 'Salida',
                        'reg_hora' => date("H:i:s"),
                        'reg_fecha' => date("Y-m-d")
                    );
                    //
                    if ($this->db->insert('registro_entrada_salida', $datos)) {
                        //
                        $query2 = $this->db->query("update entrada_salida set "
                                . "ensa_poblacion = ensa_poblacion - 1 where "
                                . "emp_id = $idEmp and sed_id = $idSed");
                        //
                        if ($query2) {
                            //
                            return array('estado' => 'registrado');
                        } else {
                            //
                            return array('estado' => 'error2');
                        }
                    } else {
                        //
                        return array('estado' => 'error');
                    }
                } else {
                    //
                    return array('estado' => 'no dentro');
                }
            }
        }
    }

    //
    function generarPdf() {
        //
        $idEmp = $this->input->post("idEmp");
        $idSed = $this->input->post("idSed");
        $fecha = date("Y-m-d");
        $fechaI = date("Y-m-d", strtotime($fecha . "- 15 days"));
        //
        $query = $this->db->query("SELECT emp_nombre, emp_nit from empresa "
                . "where emp_id = $idEmp");
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                $query2 = $this->db->query("SELECT count(reg_id) as c, reg_"
                        . "fecha from registro_entrada_salida where emp_id = "
                        . "$idEmp and sed_id = $idSed and reg_tipo = 'Entrada' "
                        . "and reg_fecha between '$fechaI' and '$fecha' group "
                        . "by reg_fecha");
                //
                if (count($query2->result()) > 0) {
                    //
                    $this->load->library('F_pdf');
                    //
                    $this->f_pdf->pdf->AddPage();
                    //
                    $this->f_pdf->pdf->SetFont('Arial', 'B', 14);
                    //
                    $this->f_pdf->pdf->Cell(80, 10, $row->emp_nombre, 0, 0, 'C');
                    $this->f_pdf->pdf->SetXY(10, 20);
                    $this->f_pdf->pdf->Cell(80, 10, "Nit: $row->emp_nit", 0, 0, 'C');
                    //
                    $this->f_pdf->pdf->SetXY(10, 30);
                    $this->f_pdf->pdf->Cell(40, 10, '# Entradas', 1, 0, 'C');
                    $this->f_pdf->pdf->SetXY(50, 30);
                    $this->f_pdf->pdf->Cell(40, 10, 'Fecha', 1, 0, 'C');
                    //
                    $this->f_pdf->pdf->SetFont('Arial', '', 12);
                    $x = 10;
                    $y = 40;
                    //
                    foreach ($query2->result() as $row2) {
                        //
                        $this->f_pdf->pdf->SetXY($x, $y);
                        $this->f_pdf->pdf->Cell(40, 10, utf8_decode($row2->c), 1, 0, 'C');
                        $x += 40;
                        $this->f_pdf->pdf->SetXY($x, $y);
                        $this->f_pdf->pdf->Cell(40, 10, utf8_decode($row2->reg_fecha), 1, 0, 'C');
                        //
                        $x = 10;
                        $y += 10;
                    }
                    //
                    $this->f_pdf->pdf->Output('F', "./reportes/reporte-$idEmp.pdf");
                    return array('estado' => "reporte-$idEmp.pdf");
                }else{
                    //
                    return array('estado' => "error2");
                }
            }
        }else{
            //
            return array('estado' => "error");
        }
    }

}
