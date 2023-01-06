<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
header('content-type: application/json; charset=utf-8');

class Integrasi extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Integrasi_model', 'api');

        // $this->methods['cekLoginService_get']['limit'] = 100;
        // $this->methods['users_post']['limit'] = 100;
        // $this->methods['users_delete']['limit'] = 50;
    }

    public function cekLoginService_post()
    {
        $nip = trim(htmlspecialchars($this->post('nip')));
        $password = trim(htmlspecialchars($this->post('password')));

        if ($nip == null) {
            $status = false;
            $response = "NIP Tidak Boleh Kosong";
        } else if ($password == null) {
            $status = false;
            $response = "Password Tidak Boleh Kosong";
        } else {

            // $cek_pegawai = $this->api->cek_pegawai($nip);
            // if ($cek_pegawai->num_rows() > 0) {
            //     $pegawai = $cek_pegawai->row_array();
            //     if (password_verify($password, $pegawai['password'])) {
            //         $status = true;
            //         $response = ["response" => "Login Success", "id_pegawai" => $pegawai['id_pegawai']];
            //     } else {
            //         $status = false;
            //         $response = "Password Salah";
            //     }
            // } else {
            //     $status = false;
            //     $response = "NIP Tidak Terdaftar";
            // }

            $status = false;
            $response = "NIP Tidak Terdaftar";

        }

        if ($status == true) {
            $this->response(["status_login" => $status, "result" => $response], REST_Controller::HTTP_OK);
        } else {
            $this->response(["status_login" => $status, "response" => $response], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function getDetailPegawaiService_post()
    {
        $id_pegawai = trim(htmlspecialchars($this->post('pegawai')));

        if ($id_pegawai == null) {
            $status = false;
            $response = "Pegawai Tidak Boleh Kosong";
        } else {
            $cek_pegawai = $this->api->get_detail_pegawai($id_pegawai);
            if ($cek_pegawai->num_rows() > 0) {
                $status = true;
                $pegawai = $cek_pegawai->row_array();
                if ($pegawai['status_kerja'] == "honor") {
                    $jabatan = $pegawai['jabatan_honor'];
                } else {
                    $jabatan = jabatan($pegawai['plt'], $pegawai['nama_jabatan']);
                }
                $result = [
                    "nama_pegawai" => format_nama($pegawai['gelar_depan'], $pegawai['nama_pegawai'], $pegawai['gelar_belakang']),
                    "jabatan" => $jabatan,
                    "skpd" => $pegawai['nama_skpd']
                ];
            } else {
                $status = false;
                $response = "Data Pegawai Tidak Ditemukan";
            }
        }

        if ($status == true) {
            $this->response(["response" => "OK", "result" => $result], REST_Controller::HTTP_OK);
        } else {
            $this->response(["response" => $response], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function getKehadiranByTanggalService_post()
    {
        $id_pegawai = trim(htmlspecialchars($this->post('pegawai')));
        $tgl_hadir = trim(htmlspecialchars($this->post('tanggal')));

        if ($id_pegawai == null) {
            $status = false;
            $response = "Pegawai Tidak Boleh Kosong";
        } else if ($tgl_hadir == null) {
            $status = false;
            $response = "Tanggal Tidak Boleh Kosong";
        } else {
            $cek_kehadiran = $this->api->get_kehadiran_by_tanggal($id_pegawai, $tgl_hadir);
            if ($cek_kehadiran->num_rows() > 0) {
                $hadir = $cek_kehadiran->row_array();
                if ($hadir['status_kerja'] == "honor") {
                    $jabatan = $hadir['jabatan_honor'];
                } else {
                    $jabatan = jabatan($hadir['plt'], $hadir['nama_jabatan']);
                }

                // cek tampilan output jam masuk dan jam pulang, tamplan muncul jika status hadir
                if ($hadir['status_hadir'] == 'hadir') {
                    //cek jam masuk dan status terlambat datang
                    if ($hadir['jam_masuk'] == '00:00:00') {
                        $jam_masuk = "-";
                        $lambat_datang = "-";
                    } else {
                        if ($hadir['lambat_datang'] == 0) {
                            $lambat_datang = 0;
                        } else {
                            $lambat_datang = konversi_detik($hadir['lambat_datang']);
                        }
                        $jam_masuk = $hadir['jam_masuk'];
                    }

                    // cek jam pulang dan status cepat pulang
                    if ($hadir['jam_pulang'] == '00:00:00') {
                        $jam_pulang = "-";
                        $cepat_pulang = "-";
                    } else {
                        if ($hadir['cepat_pulang'] == 0) {
                            $cepat_pulang = 0;
                        } else {
                            $cepat_pulang = konversi_detik($hadir['cepat_pulang']);
                        }
                        $jam_pulang = $hadir['jam_pulang'];
                    }
                } else {
                    $jam_masuk = "-";
                    $lambat_datang = "-";
                    $jam_pulang = "-";
                    $cepat_pulang = "-";
                }


                $status = true;
                // $result = [
                //     "id_kehadiran" => $hadir['id_kehadiran'],
                //     "id_pegawai" => $hadir['id_pegawai'],
                //     "nama_pegawai" => format_nama($hadir['gelar_depan'], $hadir['nama_pegawai'], $hadir['gelar_belakang']),
                //     "jabatan" => $jabatan,
                //     "skpd" => $hadir['nama_skpd'],
                //     "latitude" => $hadir['latitude'],
                //     "longitude" => $hadir['longitude'],
                //     "radius" => $hadir['radius'],
                //     "tanggal_hadir" => $hadir['tgl_kehadiran'],
                //     "status_hadir" => $hadir['status_hadir'],
                //     "jam_masuk" => $jam_masuk,
                //     "jam_pulang" => $jam_pulang,
                //     "lambat_datang" => $lambat_datang,
                //     "cepat_pulang" => $cepat_pulang,
                //     "foto_masuk" => base_url('uploads/foto_absensi/' . $hadir['foto_masuk']),
                //     "foto_keluar" => base_url('uploads/foto_absensi/' . $hadir['foto_pulang'])
                // ];

                $result = [
                    "data_pegawai" => [
                        "id_pegawai" => $hadir['id_pegawai'],
                        "nama_pegawai" => format_nama($hadir['gelar_depan'], $hadir['nama_pegawai'], $hadir['gelar_belakang']),
                        "jabatan" => $jabatan,
                        "skpd" => $hadir['nama_skpd'],
                        "latitude" => $hadir['latitude'],
                        "longitude" => $hadir['longitude'],
                        "radius" => $hadir['radius']
                    ],
                    "id_kehadiran" => $hadir['id_kehadiran'],
                    "tanggal_hadir" => $hadir['tgl_kehadiran'],
                    "status_hadir" => $hadir['status_hadir'],
                    "jam_masuk" => $jam_masuk,
                    "jam_pulang" => $jam_pulang,
                    "lambat_datang" => $lambat_datang,
                    "cepat_pulang" => $cepat_pulang,
                    "foto_masuk" => base_url('uploads/foto_absensi/' . $hadir['foto_masuk']),
                    "foto_keluar" => base_url('uploads/foto_absensi/' . $hadir['foto_pulang'])
                ];
            } else {
                $status = false;
                $response = "Data Kehadiran Pegawai Tidak Ditemukan";
            }
        }

        if ($status == true) {
            $this->response(["response" => "OK", "result" => $result], REST_Controller::HTTP_OK);
        } else {
            $this->response(["response" => $response], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function pushAbsenMasukService_post()
    {
        $id_kehadiran = trim(htmlspecialchars($this->post('kehadiran')));
        $jam_masuk = trim(htmlspecialchars($this->post('jam_masuk')));

        if ($id_kehadiran == null) {
            $status = false;
            $response = "Kehadiran Tidak Boleh Kosong";
        } else if ($jam_masuk == null) {
            $status = false;
            $response = "Jam Masuk Tidak Boleh Kosong";
        } else {
            $cek_masuk = $this->api->get_masuk($id_kehadiran);
            if ($cek_masuk->num_rows() > 0) {
                $masuk = $cek_masuk->row_array();
                if ($masuk['status_hadir'] == 'absen') {
                    $tgl_sekarang = date('Y-m-d');
                    //$tgl_sekarang = date('2021-02-11');
                    $day = date('D', strtotime($tgl_sekarang));
                    $jam_kerja = $this->db->get_where('jam_kerja', ['nama_hari' => $day])->row_array();
                    // proses jam kerja - jam masuk untuk menentukan nilai plus atau minus
                    // jika jam masuk lebih besar dari jam kerja, maka hasilnya minus (terlambat datang)
                    $selisih = selisih($jam_masuk, $jam_kerja['jam_masuk']);
                    if ($selisih < 0) {
                        $lambat_datang = abs($selisih);
                    } else {
                        $lambat_datang = 0;
                    }
                    $config['upload_path'] = './uploads/foto_absensi/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = $masuk['id_pegawai'] . '-' . date("Ymd-His") . '-masuk';
                    $config['max_size'] = 300;
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('foto_absen')) {
                        $status = false;
                        $response = $this->upload->display_errors();
                    } else {
                        $upload = $this->upload->data();
                        $data_absen = [
                            "status_hadir" => 'hadir',
                            "jam_masuk" => $jam_masuk,
                            "lambat_datang" => $lambat_datang,
                            "foto_masuk" => $upload['file_name']
                        ];
                        $absen_masuk = $this->api->proses_absen($data_absen, $id_kehadiran);
                        if ($absen_masuk > 0) {
                            $status = true;
                            $result = "Absen Masuk Berhasi Disimpan";
                        } else {
                            $status = false;
                            $result = "Absen Masuk Gagal Disimpan";
                        }
                    }
                } elseif ($masuk['status_hadir'] == 'hadir') {
                    $status = false;
                    $response = "Anda Sudah Melakukan Absen Sebelumnya";
                } else {
                    $status = false;
                    $response = "Maaf, Status Hadir Izin/Sakit/TL/Cuti";
                }
            } else {
                $status = false;
                $response = "Data Tidak Ditemukan";
            }
        }

        if ($status == true) {
            $this->response(["response" => "OK", "result" => $result], REST_Controller::HTTP_OK);
        } else {
            $this->response(["response" => $response], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function pushAbsenPulangService_post()
    {
        $id_kehadiran = trim(htmlspecialchars($this->post('kehadiran')));
        $jam_pulang = trim(htmlspecialchars($this->post('jam_pulang')));
        $tgl_sekarang = trim(htmlspecialchars($this->post('tanggal')));

        if ($id_kehadiran == null) {
            $status = false;
            $response = "Kehadiran Tidak Boleh Kosong";
        } else if ($jam_pulang == null) {
            $status = false;
            $response = "Jam Pulang Tidak Boleh Kosong";
        } else {
            $cek_pulang = $this->api->get_pulang($id_kehadiran);
            if ($cek_pulang->num_rows() > 0) {
                $pulang = $cek_pulang->row_array();
                if ($pulang['status_hadir'] == 'hadir') {
                    if ($pulang['jam_pulang'] == '00:00:00') {
                        //$tgl_sekarang = date('Y-m-d');
                        $day = date('D', strtotime($tgl_sekarang));
                        $jam_kerja = $this->db->get_where('jam_kerja', ['nama_hari' => $day])->row_array();
                        // proses jam kerja - jam pulang untuk menentukan nilai plus atau minus
                        // jika jam pulang lebih kecil dari jam kerja, maka hasilnya plus (cepat pulang)
                        $selisih = selisih($jam_pulang, $jam_kerja['jam_pulang']);
                        if ($selisih > 0) {
                            $cepat_pulang = abs($selisih);
                        } else {
                            $cepat_pulang = 0;
                        }
                        $config['upload_path'] = './uploads/foto_absensi/';
                        $config['allowed_types'] = 'gif|jpg|png';
                        $config['file_name'] = $pulang['id_pegawai'] . '-' . date("Ymd-His") . '-pulang';
                        $config['max_size'] = 300;
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('foto_absen')) {
                            $status = false;
                            $response = $this->upload->display_errors();
                        } else {
                            $upload = $this->upload->data();
                            $data_absen = [
                                "jam_pulang" => $jam_pulang,
                                "cepat_pulang" => $cepat_pulang,
                                "foto_pulang" => $upload['file_name']
                            ];
                            $absen_masuk = $this->api->proses_absen($data_absen, $id_kehadiran);
                            if ($absen_masuk > 0) {
                                $status = true;
                                $result = "Absen Pulang Berhasi Disimpan";
                                if ($pulang['foto_pulang'] != "no-image.png") {
                                    unlink(FCPATH . 'uploads/foto_absensi/' . $pulang['foto_pulang']);
                                }
                            } else {
                                $status = false;
                                $result = "Absen Pulang Gagal Disimpan";
                            }
                        }
                    } else {
                        $status = false;
                        $response = "Anda Sudah Melakukan Pulang Sebelumnya";
                    }
                } else {
                    $status = false;
                    $response = "Maaf, Status Hadir Absen/Izin/Sakit/TL/Cuti";
                }
            } else {
                $status = false;
                $response = "Data Tidak Ditemukan";
            }
        }

        if ($status == true) {
            $this->response(["response" => "OK", "result" => $result], REST_Controller::HTTP_OK);
        } else {
            $this->response(["response" => $response], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
