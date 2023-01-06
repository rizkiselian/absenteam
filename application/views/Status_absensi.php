<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Status_absensi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Status_absensi_model', 'status');
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    function import_kode_pegawai()
    {
        $this->db->select('keterangan_absensi.id_pegawai_lama, pegawai_ori.id_pegawai, pegawai_ori.kode_pegawai');
        $this->db->from('keterangan_absensi');
        $this->db->join('pegawai_ori', 'keterangan_absensi.id_pegawai_lama = pegawai_ori.id_pegawai', 'LEFT');
        $get_pegawai = $this->db->get();
        // print_r($get_pegawai->num_rows());
        // die;
        $params = [];
        foreach ($get_pegawai->result_array() as $r) {
            $params[] = [
                "id_pegawai_lama" => $r['id_pegawai'],
                "id_pegawai" => $r['kode_pegawai']
            ];
        }

        $cek = $this->db->update_batch('keterangan_absensi', $params, 'id_pegawai_lama');
        echo $cek;
    }

    // load pegawai izin digunakan untuk semua keterangan absensi cuti/izin/sakit/tugas luar
    function loadStatusAbsensi($id, $statusAbsensi)
    {
        if ($this->role == "admin") {
            $idSkpd = decrypt_url($id);
            $result = $this->status->getStatusAbsensi($idSkpd, $statusAbsensi);
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                if ($r['status_kerja'] == 'honor') {
                    $jabatan = $r['jabatan_honor'];
                    $nip = "-";
                } else {
                    $jabatan = jabatan($r['plt'], $r['nama_jabatan']);
                    $nip = $r['nip'];
                }
                $delete = "<button id='tombol-hapus' data-id='" . encrypt_url($r['id_keterangan_absensi']) . "' class='btn btn-icon btn-round btn-danger btn-sm' title='HAPUS'><i class='fa fa-trash'></i></button>";
                $row = [
                    'no' => $no,
                    'nama' => format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']),
                    'nip' => $nip,
                    'jabatan' => $jabatan,
                    'waktu' => format_tanggal($r['tanggal_awal']) . ' s/d ' . format_tanggal($r['tanggal_akhir']),
                    'status' => status_hadir($r['status_hadir']),
                    'keterangan' => $r['keterangan_absensi'],
                    'berkas' => $r['berkas_absensi'],
                    'aksi' => $delete
                ];
                $data[] = $row;
            }
            $output['data'] = $data;
            echo json_encode($output);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function cekJabatanPegawai()
    {
        if ($this->role == "admin") {
            $pegawai = htmlspecialchars($this->input->post('pegawai', TRUE));
            $idPegawai = decrypt_url($pegawai);
            $result = $this->status->getJabatanPegawai($idPegawai);
            if ($result['status_kerja'] == 'honor') {
                echo $result['jabatan_honor'];
            } else {
                echo jabatan($result['plt'], $result['nama_jabatan']);
            }
        } else {
            redirect(site_url('blocked'));
        }
    }
}
