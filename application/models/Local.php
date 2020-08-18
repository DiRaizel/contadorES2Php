<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//
date_default_timezone_set('America/Bogota');

class Local extends CI_Model {

    //
    function login() {
        //
        $codigo = $this->input->post("codigo");
        //
        $query = $this->db->query("select loc_id, loc_estado from local where "
                . "loc_codigo = $codigo");
        //
        $datos = array();
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                $query2 = $this->db->query("select r.sed_id from relacion_emp_"
                        . "sed_loc r join local l on r.loc_id = l.loc_id "
                        . "where l.loc_codigo = $codigo");
                //
                $idSede = 0;
                //
                if (count($query2->result()) > 0) {
                    //
                    foreach ($query2->result() as $row2) {
                        //
                        $idSede = $row2->sed_id;
                    }
                }
                //
                $datos = array(
                    'estado' => 'Entra',
                    'rol' => 'Local',
                    'idUsu' => $row->loc_id,
                    'estadoU' => $row->loc_estado,
                    'idSed' => $idSede,
                    'codigo' => $codigo
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
    function registro() {
        //
        $codigo = time();
        //
        $datos = array(
            'loc_codigo' => $codigo,
            'loc_estado' => 'Activo'
        );
        //
        $datos2 = array();
        //
        if ($this->db->insert('local', $datos)) {
            //
            $datos2 = array(
                'estado' => 'Entra',
                'rol' => 'Local',
                'idUsu' => $this->db->insert_id(),
                'estadoU' => 'Activo',
                'idSed' => 0,
                'codigo' => $codigo
            );
            //
            return $datos2;
        } else {
            //
            return $datos2['estado'] = 'Error';
        }
    }

    //
    function cargarLocalesSede() {
        //
        $idSed = $this->input->post("idSed");
        //
        $query = $this->db->query("SELECT l.loc_codigo , l.loc_id, l.loc_estado"
                . " FROM local l join relacion_emp_sed_loc r on l.loc_id = "
                . "r.loc_id where r.sed_id = $idSed order by l.loc_codigo asc");
        //
        $datos = array();
        //
        if (count($query->result()) > 0) {
            //
            foreach ($query->result() as $row) {
                //
                array_push($datos, array(
                    'idLoc' => $row->loc_id,
                    'codigo' => $row->loc_codigo,
                    'estado' => $row->loc_estado
                ));
            }
        }
        //
        return $datos;
    }

    //
    function agregarUsuarioLocal() {
        //
        $idEmp = $this->input->post("idEmp");
        $sede = $this->input->post("sede");
        $codigo = $this->input->post("codigo");
        //
        $query = $this->db->query("SELECT r.rel_id from relacion_emp_sed_loc r "
                . "join local l on r.loc_id = l.loc_id where l.loc_codigo = $codigo");
        //
        if (count($query->result()) > 0) {
            //
            return $datos2['estado'] = 'Existe';
        } else {
            //
            $queryL = $this->db->query("SELECT loc_id from local where "
                    . "loc_codigo = $codigo");
            //
            if (count($queryL->result()) > 0) {
                //
                foreach ($queryL->result() as $row) {
                    //
                    $datos = array(
                        'emp_id' => $idEmp,
                        'sed_id' => $sede,
                        'loc_id' => $row->loc_id
                    );
                    //
                    if ($this->db->insert('relacion_emp_sed_loc', $datos)) {
                        //
                         return $datos2['estado'] = 'Guardado';
                    } else {
                        //
                        return $datos2['estado'] = 'Error';
                    }
                }
            } else {
                //
                return $datos2['estado'] = 'No existe';
            }
        }
    }

}
