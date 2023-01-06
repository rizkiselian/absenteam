<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tpp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tpp_model', 'tpp');
        $this->simpeg = 'kepegawaian';
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    function index()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_tpp",
                "submenu" => null,
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC'),
                "result_bulan" => $this->master->selectData('bulan', 'id_bulan ASC'),
                "role" => $this->role
            ];
            $this->load->view('tpp/view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_tpp",
                "submenu" => null,
                "skpd" => $this->session->userdata('sess_skpd'),
                "result_bulan" => $this->master->selectData('bulan', 'id_bulan ASC'),
                "role" => $this->role
            ];
            $this->load->view('tpp/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadTpp($id, $bulan, $tahun)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $data['result_pegawai'] = $this->tpp->getTpp($idSkpd, $bulan, $tahun);
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;
            $this->load->view('tpp/view_load', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }
}
