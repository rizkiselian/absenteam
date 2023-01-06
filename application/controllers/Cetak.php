<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cetak extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Kehadiran_model', 'kehadiran');
        $this->load->model('Pegawai_model', 'pegawai');
        $this->load->model('Rekap_model', 'rekap');
        $this->load->model('Tpp_model', 'tpp');
        $this->simpeg = 'kepegawaian';
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    function kehadiran_pegawai($encript_skpd, $tgl)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($encript_skpd);
            $tgl_kehadiran = format_tanggal_database($tgl);
            $data['tgl_kehadiran'] = $tgl_kehadiran;
            $data['kehadiran'] = $this->kehadiran->getPegawaiHadir($idSkpd, $tgl_kehadiran);
            $data['tidak_hadir'] = $this->kehadiran->getPegawaiTidakHadir($idSkpd, $tgl_kehadiran);
            $data['skpd'] = $this->master->selectDataId($this->simpeg. '.skpd', ['id_skpd' => $idSkpd]);
            $this->load->view('cetak/kehadiran_pegawai', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function kehadiran_detail($encript_id, $tahun, $bulan)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idPegawai = decrypt_url($encript_id);
            $rst_pegawai = $this->pegawai->getPegawaibyid($idPegawai);
            $data['tahun'] = $tahun;
            $data['bulan'] = $bulan;
            $data['result_pegawai'] = $this->pegawai->getPegawaibyid($idPegawai);
            $data['result_kehadiran'] = $this->kehadiran->getDetailKehadiran($idPegawai, $bulan, $tahun);
            $data['skpd'] = $this->master->selectDataId($this->simpeg. '.skpd', ['id_skpd' => $rst_pegawai['id_skpd']]);
            $this->load->view('cetak/kehadiran_detail', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function kehadiran_rekap1($encript_id, $tahun, $bulan)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($encript_id);
            $data['tahun'] = $tahun;
            $data['bulan'] = $bulan;
            $data['result_pegawai'] = $this->rekap->getRekap1Pegawai($idSkpd, $bulan, $tahun);
            $data['skpd'] = $this->master->selectDataId($this->simpeg. '.skpd', ['id_skpd' => $idSkpd]);
            $this->load->view('cetak/kehadiran_rekap1', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function kehadiran_rekap2($encript_id, $tahun, $bulan)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($encript_id);
            $data['tahun'] = $tahun;
            $data['bulan'] = $bulan;
            $data['result_pegawai'] = $this->rekap->getRekap1Pegawai($idSkpd, $bulan, $tahun);
            $data['skpd'] = $this->master->selectDataId($this->simpeg. '.skpd', ['id_skpd' => $idSkpd]);
            $this->load->view('cetak/kehadiran_rekap2', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function kehadiran_tpp($encript_id, $tahun, $bulan)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($encript_id);
            $data['tahun'] = $tahun;
            $data['bulan'] = $bulan;
            $data['result_pegawai'] = $this->tpp->getTpp($idSkpd, $bulan, $tahun);
            $data['skpd'] = $this->master->selectDataId($this->simpeg. '.skpd', ['id_skpd' => $idSkpd]);
            $this->load->view('cetak/kehadiran_tpp', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }
}
