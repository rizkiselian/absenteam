<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tenaga_kontrak extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tenaga_kontrak_model', 'tenaga_kontrak');
        $this->simpeg = 'kepegawaian';
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    
    function index()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_tenaga_kontrak",
                "submenu" => null,
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC'),
                "role" => $this->role
            ];
            $this->load->view('tenaga_kontrak/view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_tenaga_kontrak",
                "submenu" => null,
                "skpd" => $this->session->userdata('sess_skpd'),
                "role" => $this->role
            ];
            $this->load->view('tenaga_kontrak/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadTenagaKontrak($id)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $result = $this->tenaga_kontrak->getTenagaKontrak($idSkpd);
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $idPegawai = encrypt_url($r['id_pegawai']);
                $namaPegawai = "<a href='" . site_url('detail-kehadiran-pegawai/' . $idPegawai) . "'>" . format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']) . "</a>";
                $no++;
                $row = [
                    'no' => $no,
                    'nama' => $namaPegawai,
                    'jabatan' => $r['jabatan_honor']
                ];
                $data[] = $row;
            }
            $output['data'] = $data;
            echo json_encode($output);
        } else {
            redirect(site_url('blocked'));
        }
    }
}
