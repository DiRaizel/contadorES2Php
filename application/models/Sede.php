<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//
date_default_timezone_set('America/Bogota');

class Sede extends CI_Model {

    //
    function guardarSede() {
        //capturar Valores enviados por post
        $empresa = $this->input->post("empresa");
        $nombre = $this->input->post("nombre");
        $ciudad = $this->input->post("ciudad");
        //
        $datos = array(
            'sed_nombre' => $nombre,
            'sed_estado' => 'Activa',
            'ciu_id' => $ciudad,
            'emp_id' => $empresa
        );
        //
        if ($this->db->insert('sede', $datos)) {
            //
            return array('estado' => 'guardada');
        } else {
            //
            return array('estado' => 'error');
        }
    }

    //
    function cargarSedes() {
        //
        $empresa = $this->input->post("empresa");
        //
        $query = $this->db->query("SELECT s.sed_id , s.sed_nombre, s.sed_estado"
                . ", c.ciu_nombre FROM sede s join ciudad c on s.ciu_id = "
                . "c.ciu_id where s.emp_id = $empresa order by s.sed_nombre asc");
        //
        $datos = array();
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                array_push($datos, array(
                    'idSed' => $row->sed_id,
                    'nombre' => $row->sed_nombre,
                    'estado' => $row->sed_estado,
                    'ciudad' => $row->ciu_nombre
                ));
            }
        }
        //
        return $datos;
    }

    //
    function cargarSede() {
        //
        $idSed = $this->input->post("idSed");
        //
        $query = $this->db->query("SELECT sed_nombre , sed_estado, ciu_id FROM "
                . "sede where sed_id = $idSed");
        //
        $datos = array();
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                $queryD = $this->db->query("SELECT dep_id, dep_nombre FROM "
                        . "departamento order by dep_nombre asc");
                //
                $departamentos = array();
                //
                if (count($queryD->result()) > 0) {
                    //
                    foreach ($queryD->result() as $rowD) {
                        //
                        array_push($departamentos, array(
                            'cod' => $rowD->dep_id,
                            'departamento' => $rowD->dep_nombre
                        ));
                    }
                }
                //
                $queryC = $this->db->query("SELECT dep_id FROM ciudad where "
                        . "ciu_id = $row->ciu_id");
                //
                $departamento = 0;
                $ciudades = array();
                //
                if (count($queryC->result()) > 0) {
                    //
                    foreach ($queryC->result() as $rowC) {
                        //
                        $departamento = $rowC->dep_id;
                        //
                        $queryC2 = $this->db->query("SELECT ciu_id, ciu_nombre "
                                . "FROM ciudad where dep_id = $rowC->dep_id "
                                . "order by ciu_nombre asc");
                        //
                        if (count($queryC2->result()) > 0) {
                            //
                            foreach ($queryC2->result() as $rowC2) {
                                //
                                array_push($ciudades, array(
                                    'cod' => $rowC2->ciu_id,
                                    'ciudad' => $rowC2->ciu_nombre
                                ));
                            }
                        }
                    }
                }
                //
                array_push($datos, array(
                    'nombre' => $row->sed_nombre,
                    'estado' => $row->sed_estado,
                    'ciudad' => $row->ciu_id,
                    'departamento' => $departamento,
                    'ciudades' => $ciudades,
                    'departamentos' => $departamentos
                ));
            }
        }
        //
        return $datos;
    }

}
