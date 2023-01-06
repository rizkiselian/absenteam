<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pegawai_model', 'pegawai');
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    function index()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_pegawai",
                "submenu" => null,
                "result_skpd" => $this->master->selectData('skpd', 'nama_skpd ASC'),
                "role" => $this->role
            ];
            $this->load->view('pegawai/view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_pegawai",
                "submenu" => null,
                "skpd" => $this->session->userdata('sess_skpd'),
                "role" => $this->role
            ];
            $this->load->view('pegawai/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadPegawai($id)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $result = $this->pegawai->getPegawai($idSkpd);
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $idPegawai = encrypt_url($r['id_pegawai']);
                $namaPegawai = "<a href='" . site_url('detail-kehadiran-pegawai/' . $idPegawai) . "'>" . format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']) . "</a>";
                $no++;
                $row = [
                    'no' => $no,
                    'nama' => $namaPegawai,
                    'nip' => $r['nip'],
                    'golongan' => $r['kode_pangkat'],
                    'jabatan' => jabatan($r['plt'], $r['nama_jabatan'])
                ];
                $data[] = $row;
            }
            $output['data'] = $data;
            echo json_encode($output);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadPegawaiBySkpd()
    {
        if ($this->role == "admin") {
            $skpd = htmlspecialchars($this->input->post('unit_kerja', TRUE));
            $pegawai = htmlspecialchars($this->input->post('pegawai', TRUE));
            $idSkpd = decrypt_url($skpd);
            ($pegawai == null) ? $idPegawai = null : $idPegawai = decrypt_url($pegawai);
            if ($idSkpd != "") {
                $result = $this->pegawai->getPegawai($idSkpd);
                echo "<option value=''>PILIH PEGAWAI</option>";
                foreach ($result as $r) {
                    $nama_pegawai = format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']);
                    if ($r['id_pegawai'] == $idPegawai) {
                        echo "<option value='" . encrypt_url($r['id_pegawai']) . "' selected>" . $nama_pegawai . "</option>";
                    } else {
                        echo "<option value='" . encrypt_url($r['id_pegawai']) . "'>" . $nama_pegawai . "</option>";
                    }
                }
            }
        } else {
            redirect(site_url('blocked'));
        }
    }
}
