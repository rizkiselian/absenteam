<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Rekap_model', 'rekap');
        $this->simpeg = 'kepegawaian';
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    function rekap1Pegawai()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_rekap1",
                "submenu" => "submenu_rekap1_kehadiran_pegawai",
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC'),
                "result_bulan" => $this->master->selectData('bulan', 'id_bulan ASC'),
                "role" => $this->role
            ];
            $this->load->view('rekap/rekap1_pegawai_view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_rekap1",
                "submenu" => "submenu_rekap1_kehadiran_pegawai",
                "skpd" => $this->session->userdata('sess_skpd'),
                "result_bulan" => $this->master->selectData('bulan', 'id_bulan ASC'),
                "role" => $this->role
            ];
            $this->load->view('rekap/rekap1_pegawai_view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadRekap1Pegawai($id, $bulan, $tahun)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $data['result_pegawai'] = $this->rekap->getRekap1Pegawai($idSkpd, $bulan, $tahun);
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;
            $this->load->view('rekap/rekap1_pegawai_load', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    
    // ======================================== TENAGA KONTRAK ===================================
    function rekap1TenagaKontrak()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_rekap1",
                "submenu" => "submenu_rekap1_kehadiran_tenaga_kontrak",
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC'),
                "result_bulan" => $this->master->selectData('bulan', 'id_bulan ASC'),
                "role" => $this->role
            ];
            $this->load->view('rekap/rekap1_tenaga_kontrak_view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_rekap1",
                "submenu" => "submenu_rekap1_kehadiran_tenaga_kontrak",
                "skpd" => $this->session->userdata('sess_skpd'),
                "result_bulan" => $this->master->selectData('bulan', 'id_bulan ASC'),
                "role" => $this->role
            ];
            $this->load->view('rekap/rekap1_tenaga_kontrak_view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadRekap1TenagaKontrak($id, $bulan, $tahun)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $data['result_pegawai'] = $this->rekap->getRekap1TenagaKontrak($idSkpd, $bulan, $tahun);
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;
            $this->load->view('rekap/rekap1_tenaga_kontrak_load', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    // ==================================================================================================
    //                                  REKAP KEHADIRAN 2
    // ==================================================================================================
    function rekap2Pegawai()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_rekap2",
                "submenu" => "submenu_rekap2_kehadiran_pegawai",
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC'),
                "result_bulan" => $this->master->selectData('bulan', 'id_bulan ASC'),
                "role" => $this->role
            ];
            $this->load->view('rekap/rekap2_pegawai_view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_rekap2",
                "submenu" => "submenu_rekap2_kehadiran_pegawai",
                "skpd" => $this->session->userdata('sess_skpd'),
                "result_bulan" => $this->master->selectData('bulan', 'id_bulan ASC'),
                "role" => $this->role
            ];
            $this->load->view('rekap/rekap2_pegawai_view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadRekap2Pegawai($id, $bulan, $tahun)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $data['result_pegawai'] = $this->rekap->getRekap1Pegawai($idSkpd, $bulan, $tahun);
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;
            $this->load->view('rekap/rekap2_pegawai_load', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    // ======================================== TENAGA KONTRAK ===================================
    function rekap2TenagaKontrak()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_rekap2",
                "submenu" => "submenu_rekap2_kehadiran_tenaga_kontrak",
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC'),
                "result_bulan" => $this->master->selectData('bulan', 'id_bulan ASC'),
                "role" => $this->role
            ];
            $this->load->view('rekap/rekap2_pegawai_view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_rekap2",
                "submenu" => "submenu_rekap2_kehadiran_tenaga_kontrak",
                "skpd" => $this->session->userdata('sess_skpd'),
                "result_bulan" => $this->master->selectData('bulan', 'id_bulan ASC'),
                "role" => $this->role
            ];
            $this->load->view('rekap/rekap2_pegawai_view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadRekap2TenagaKontrak($id, $bulan, $tahun)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $data['result_pegawai'] = $this->rekap->getRekap1TenagaKontrak($idSkpd, $bulan, $tahun);
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;
            $this->load->view('rekap/rekap2_tenaga_kontrak_load', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }
}
