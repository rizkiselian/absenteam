<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kehadiran extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kehadiran_model', 'kehadiran');
        $this->load->model('Pegawai_model', 'pegawai');
        $this->simpeg = 'kepegawaian';
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    function pegawai()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_kehadiran",
                "submenu" => "submenu_kehadiran_pegawai",
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC'),
                "role" => $this->role
            ];
            $this->load->view('kehadiran/pegawai_view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_kehadiran",
                "submenu" => "submenu_kehadiran_pegawai",
                "skpd" => $this->session->userdata('sess_skpd'),
                "role" => $this->role
            ];
            $this->load->view('kehadiran/pegawai_view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadPegawaiHadir($id, $tgl)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $tgl_kehadiran = format_tanggal_database($tgl);
            $result = $this->kehadiran->getPegawaiHadir($idSkpd, $tgl_kehadiran);
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                $idPegawai = encrypt_url($r['id_pegawai']);
                $namaPegawai = "<a href='" . site_url('detail-kehadiran-pegawai/' . $idPegawai) . "'>" . format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']) . "</a>";
                if ($r['lambat_datang'] <= 0) {
                    $jamMasuk = "<center style='color:#2ecc71;'>$r[jam_masuk]</center>";
                } else {
                    $lambatDatang = konversi_detik($r['lambat_datang']);
                    $lambat_datang = "<br>(Terlambat => $lambatDatang)";
                    $jamMasuk = "<center style='color:#e74c3c;'>$r[jam_masuk] $lambat_datang</center>";
                }

                if ($r['jam_pulang'] == '00:00:00') {
                    $jamPulang = "<center style='color:#2ecc71;'>-</center>";
                } else {
                    if ($r['cepat_pulang'] <= 0) {
                        $jamPulang = "<center style='color:#2ecc71;'>$r[jam_pulang]</center>";
                    } else {
                        $cepatPulang = konversi_detik($r['cepat_pulang']);
                        $cepat_pulang = "<br>(Cepat Pulang => $cepatPulang)";
                        $jamPulang = "<center style='color:#e74c3c;'>$r[jam_pulang] $cepat_pulang</center>";
                    }
                }

                $row = [
                    'no' => $no,
                    'nama' => $namaPegawai,
                    'nip' => $r['nip'],
                    'jabatan' => jabatan($r['plt'], $r['nama_jabatan']),
                    'jam_masuk' => $jamMasuk,
                    'jam_keluar' => $jamPulang
                ];
                $data[] = $row;
            }
            $output['data'] = $data;
            echo json_encode($output);
        } else {
            redirect(site_url('blocked'));
        }
    }


    function loadPegawaiTidakHadir($id, $tgl)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $tgl_kehadiran = format_tanggal_database($tgl);

            $tanggal_now=date('Y-m-d');
            $get_Pegawai = $this->pegawai->getPegawai($idSkpd);
            foreach ($get_Pegawai as $peg) {
                $idPegawai = $peg['id_pegawai'];
                $cekdatapeg = $this->master->cekCount('kehadiran', ['id_pegawai' => $idPegawai, 'tgl_kehadiran' => $tgl_kehadiran]);
                if ($cekdatapeg==0) {
                    $pegawai = $this->pegawai->getPegawaibyid($idPegawai);
                    $dataKehadiranInsert = [
                        "id_pegawai" => $idPegawai,
                        "tgl_kehadiran" => $tgl_kehadiran,
                        "status_hadir" => "absen",
                        "temp_status_hadir" => "absen",
                        "jam_masuk" => "00:00:00",
                        "jam_pulang" => "00:00:00",
                        "lambat_datang" => 0,
                        "cepat_pulang" => 0,
                        "id_keterangan_absensi" => 0,
                        "id_jabatan" => $pegawai['id_jabatan'],
                        "id_skpd" => $pegawai['id_skpd']
                    ];
                    if($tgl_kehadiran<$tanggal_now)
                    {
                        $day = date('D', strtotime($tgl_kehadiran));
                        if (($day != 'Sat') and ($day != 'Sun')) {
                            $cekHariLibur = $this->master->cekCount('hari_libur', ['tanggal_libur' => $tgl_kehadiran]);
                            if ($cekHariLibur == 0) {$this->db->insert('kehadiran', $dataKehadiranInsert);}
                        }
                    }
                }
            }
           // $this->db->insert_batch('kehadiran', $dataKehadiranInsert);

            $result = $this->kehadiran->getPegawaiTidakHadir($idSkpd, $tgl_kehadiran);
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                $idPegawai = encrypt_url($r['id_pegawai']);
                $namaPegawai = "<a href='" . site_url('detail-kehadiran-pegawai/' . $idPegawai) . "'>" . format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']) . "</a>";
                if ($r['berkas_absensi'] == "") {
                    $berkas = "";
                } else {
                    $file_upload = site_url('uploads/berkas_absensi/' . $r['berkas_absensi']);
                    $berkas = "<a href='" . $file_upload . "' class='btn btn-success btn-sm' target='_blank'><i class='fa fa-download'></i> UNDUH</a>";
                }
                $row = [
                    'no' => $no,
                    'nama' => $namaPegawai,
                    'nip' => $r['nip'],
                    'jabatan' => jabatan($r['plt'], $r['nama_jabatan']),
                    'status' => status_hadir($r['status_hadir']),
                    'keterangan' => $r['keterangan_absensi'],
                    'berkas' => $berkas
                ];
                $data[] = $row;
            }
            $output['data'] = $data;
            echo json_encode($output);
        } else {
            redirect(site_url('blocked'));
        }
    }

    // ==================================== Tenaga Kontrak ==============================================
    function tenagaKontrak()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_kehadiran",
                "submenu" => "submenu_kehadiran_tenaga_kontrak",
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC'),
                "role" => $this->role
            ];
            $this->load->view('kehadiran/tenaga_kontrak_view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_kehadiran",
                "submenu" => "submenu_kehadiran_tenaga_kontrak",
                "skpd" => $this->session->userdata('sess_skpd'),
                "role" => $this->role
            ];
            $this->load->view('kehadiran/tenaga_kontrak_view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadTenagaKontrakHadir($id, $tgl)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $tgl_kehadiran = format_tanggal_database($tgl);
            $result = $this->kehadiran->getTenagaKontrakHadir($idSkpd, $tgl_kehadiran);
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                $idPegawai = encrypt_url($r['id_pegawai']);
                $namaPegawai = "<a href='" . site_url('detail-kehadiran-pegawai/' . $idPegawai) . "'>" . format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']) . "</a>";
                $row = [
                    'no' => $no,
                    'nama' => $namaPegawai,
                    'nip' => $r['nip'],
                    'jabatan' => $r['jabatan_honor'],
                    'jam_masuk' => null,
                    'jam_keluar' => null
                ];
                $data[] = $row;
            }
            $output['data'] = $data;
            echo json_encode($output);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadTenagaKontrakTidakHadir($id, $tgl)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $tgl_kehadiran = format_tanggal_database($tgl);
            $result = $this->kehadiran->getTenagaKontrakTidakHadir($idSkpd, $tgl_kehadiran);
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                $idPegawai = encrypt_url($r['id_pegawai']);
                $namaPegawai = "<a href='" . site_url('detail-kehadiran-pegawai/' . $idPegawai) . "'>" . format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']) . "</a>";
                if ($r['id_keterangan_absensi'] == 0) {
                    $keterangan = "-";
                    $berkas = "-";
                } else {
                    $keterangan = $r['keterangan_absensi'];
                    $file_upload = site_url('uploads/berkas_absensi/' . $r['berkas_absensi']);
                    $berkas = "<a href='" . $file_upload . "' class='btn btn-success btn-sm' target='_blank'><i class='fa fa-download'></i> UNDUH</a>";
                }
                $row = [
                    'no' => $no,
                    'nama' => $namaPegawai,
                    'jabatan' => $r['jabatan_honor'],
                    'status' => status_hadir($r['status_hadir']),
                    'keterangan' => $keterangan,
                    'berkas' => $berkas
                ];
                $data[] = $row;
            }
            $output['data'] = $data;
            echo json_encode($output);
        } else {
            redirect(site_url('blocked'));
        }
    }


    // =========================== DETAIL KEHADIRAN PEGAWAI DAN HONOR ====================================
    function detailKehadiran($id)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idPegawai = decrypt_url($id);
            $data = [
                "menu" => null,
                "submenu" => null,
                "pegawai" => $this->master->selectDataId($this->simpeg. '.pegawai', ['id_pegawai' => $idPegawai]),
                "result_bulan" => $this->master->selectData('bulan', 'id_bulan ASC')
            ];
            $this->load->view('kehadiran/detail_kehadiran_view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadDetailKehadiran($id, $bulan, $tahun)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idPegawai = decrypt_url($id);
            $data['result_kehadiran'] = $this->kehadiran->getDetailKehadiran($idPegawai, $bulan, $tahun);
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;
            $this->load->view('kehadiran/detail_kehadiran_load', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }
}
