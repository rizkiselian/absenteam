<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pegawai_model', 'pegawai');
        $this->simpeg = 'kepegawaian';
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    function index()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_dashboard",
                "submenu" => null,
                "role" => $this->role,
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC')
            ];
            $this->load->view('dashboard', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_dashboard",
                "submenu" => null,
                "role" => $this->role,
                "skpd" => $this->session->userdata('sess_skpd')
            ];
            $this->load->view('dashboard', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadDashboard($id, $tgl)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $result_pegawai = $this->pegawai->getPegawai($idSkpd);
            $no = 0;
            foreach ($result_pegawai as $r) {
                $no++;
            }
            $cek_jumlah=$no;
            $tgl_kehadiran = format_tanggal_database($tgl);
            $this->db->select('a.status_hadir, b.status_kerja');
            $this->db->join($this->simpeg . '.pegawai as b', 'a.id_pegawai = b.id_pegawai');
            $this->db->where(['a.id_skpd' => $idSkpd, 'a.tgl_kehadiran' => $tgl_kehadiran]);
            $result_status = $this->db->get('kehadiran as a')->result_array();
            $hadir_pegawai = 0;
            $hadir_honor = 0;
            $izin_pegawai = 0;
            $izin_honor = 0;
            $sakit_pegawai = 0;
            $sakit_honor = 0;
            $cuti_pegawai = 0;
            $cuti_honor = 0;
            $absen_pegawai = 0;
            $absen_honor = 0;
            $tl_pegawai = 0;
            $tl_honor = 0;
            foreach ($result_status as $st) {
                if ($st['status_kerja'] == 'honor') {
                    if ($st['status_hadir'] == 'hadir') {
                        $hadir_honor++;
                    } elseif ($st['status_hadir'] == 'izin') {
                        $izin_honor++;
                    } elseif ($st['status_hadir'] == 'sakit') {
                        $sakit_honor++;
                    } elseif ($st['status_hadir'] == 'cuti') {
                        $cuti_honor++;
                    } elseif ($st['status_hadir'] == 'absen') {
                        $absen_honor++;
                    } elseif ($st['status_hadir'] == 'tl') {
                        $tl_honor++;
                    }
                } else {
                    if ($st['status_hadir'] == 'hadir') {
                        $hadir_pegawai++;
                    } elseif ($st['status_hadir'] == 'izin') {
                        $izin_pegawai++;
                    } elseif ($st['status_hadir'] == 'sakit') {
                        $sakit_pegawai++;
                    } elseif ($st['status_hadir'] == 'cuti') {
                        $cuti_pegawai++;
                    } elseif ($st['status_hadir'] == 'absen') {
                        $absen_pegawai++;
                    } elseif ($st['status_hadir'] == 'tl') {
                        $tl_pegawai++;
                    }
                }
            }

            $total_data=$hadir_pegawai+$cuti_pegawai+$izin_pegawai+$sakit_pegawai+$tl_pegawai;
            $total_absen=$cek_jumlah-$total_data;
            $data = [
                "tanggal" => $tgl,
                "hadir_pegawai" => $hadir_pegawai,
                "cuti_pegawai" => $cuti_pegawai,
                "izin_pegawai" => $izin_pegawai,
                "sakit_pegawai" => $sakit_pegawai,
                "absen_pegawai" => $total_absen,
                "tl_pegawai" => $tl_pegawai,
                "hadir_honor" => $hadir_honor,
                "cuti_honor" => $cuti_honor,
                "izin_honor" => $izin_honor,
                "sakit_honor" => $sakit_honor,
                "absen_honor" => $absen_honor,
                "tl_honor" => $tl_honor
            ];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            redirect(site_url('blocked'));
        }
    }
}
