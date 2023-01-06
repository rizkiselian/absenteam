<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pegawai_model', 'pegawai');
        $this->simpeg = 'kepegawaian';
        $this->role = "admin";
    }

    function index()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_dashboard",
                "submenu" => null,
                "role" => $this->role,
                "result_skpd" => $this->master->selectData($this->simpeg. '.skpd', 'nama_skpd ASC')
            ];
            $this->load->view('monitoring', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_dashboard",
                "submenu" => null,
                "role" => $this->role,
                "skpd" => $this->session->userdata('sess_skpd')
            ];
            $this->load->view('monitoring', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadDashboard()
    {
        $tgl="2022-07-14";
        $data = [
            "tanggal" => $tgl,
            "izin_pegawai" => 50,
            "tl_pegawai" => 40,
            "hadir_pegawai" => 10

        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
