<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Izin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Status_absensi_model', 'status');
        $this->load->model('Pegawai_model', 'pegawai');
        $this->simpeg = 'kepegawaian';
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    function loadData()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_izin",
                "submenu" => "submenu_izin",
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC'),
                "role" => $this->role
            ];
            $this->load->view('keterangan_absensi/izin_view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_izin",
                "submenu" => "submenu_izin",
                "skpd" => $this->session->userdata('sess_skpd'),
                "role" => $this->role
            ];
            $this->load->view('keterangan_absensi/izin_view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    private function _ruleFormInput()
    {
        $this->form_validation->set_rules('pegawai', 'Pegawai', 'required|trim');
        $this->form_validation->set_rules('tgl_mulai', 'Tanggal mulai', 'required|trim');
        $this->form_validation->set_rules('tgl_akhir', 'Tanggal akhir', 'required|trim');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    function formAddData()
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $skpd = htmlspecialchars($this->input->post('unit_kerja', TRUE));
            $idSkpd = decrypt_url($skpd);
            $data['result_pegawai'] = $this->status->getNamaPegawai($idSkpd);
            $this->load->view('keterangan_absensi/izin_form_add', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function addData()
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $this->_ruleFormInput();
            if ($this->form_validation->run() == false) {
                $errors = [
                    'pegawai' => form_error('pegawai'),
                    'tgl_mulai' => form_error('tgl_mulai'),
                    'tgl_akhir' => form_error('tgl_akhir'),
                    'keterangan' => form_error('keterangan')
                ];
                $data = ['status' => FALSE, 'tanggal' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $post = $this->input->post(null, TRUE);
                $namaPegawai = htmlspecialchars($post['pegawai']);
                $tanggalMulai = htmlspecialchars($post['tgl_mulai']);
                $tanggalAkhir = htmlspecialchars($post['tgl_akhir']);
                $idPegawai = decrypt_url($namaPegawai);
                $tglMulai = format_tanggal_database($tanggalMulai);
                $tglAkhir = format_tanggal_database($tanggalAkhir);

                $config['upload_path']      = './uploads/berkas_absensi/';
                $config['allowed_types']    = 'pdf';
                $config['file_name']        = 'izin-' . $idPegawai . '-' . date("Ymd-His");
                $config['max_size']         = 750;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('file_upload')) {
                    $errors = [
                        'pegawai' => form_error('pegawai'),
                        'tgl_mulai' => form_error('tgl_mulai'),
                        'tgl_akhir' => form_error('tgl_akhir'),
                        'keterangan' => form_error('keterangan'),
                        'file_upload'   => $this->upload->display_errors()
                    ];
                    $data = ['status' => FALSE, 'tanggal' => FALSE, 'errors' => $errors];
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $upload = $this->upload->data();
                    // cek tabel kehadiran sesuai tanggal yang dipilih
                    $dataKehadiranUpdate = [];
                    $dataKehadiranInsert = [];
                    $tglSekarang = date('Y-m-d');
                    $cekCode = $this->master->cekCount('keterangan_absensi', ['date(tanggal_input)' => $tglSekarang]);
                    if ($cekCode > 0) {
                        $this->db->select('MAX(RIGHT(id_keterangan_absensi, 4)) as kode');
                        $this->db->from('keterangan_absensi');
                        $this->db->where('date(tanggal_input)', $tglSekarang);
                        $lastCode = $this->db->get()->row_array();
                        $kode = ((int)$lastCode['kode']) + 1;
                        $codeId = sprintf("%04s", $kode);
                    } else {
                        $codeId = '0001';
                    }
                    $idKeteranganAbsensi = date('ymd') . $codeId;
                    $dataKeterangan =  [
                        'id_keterangan_absensi' => $idKeteranganAbsensi,
                        'id_pegawai' => $idPegawai,
                        'tanggal_awal' => $tglMulai,
                        'tanggal_akhir' => $tglAkhir,
                        'status_hadir' => 'izin',
                        'keterangan_absensi' => htmlspecialchars($post['keterangan']),
                        'berkas_absensi' => $upload['file_name'],
                        'tanggal_input' => date('Y-m-d H:i:s')
                    ];
                    $cekIzin = 0;
                    while ($tglMulai <= $tglAkhir) {
                        $day = date('D', strtotime($tglMulai));
                        if (($day != 'Sat') and ($day != 'Sun')) {
                            $cekHariLibur = $this->master->cekCount('hari_libur', ['tanggal_libur' => $tglMulai]);
                            if ($cekHariLibur == 0) {
                                $resultData = $this->master->selectDataId('kehadiran', ['id_pegawai' => $idPegawai, 'tgl_kehadiran' => $tglMulai]);
                                
                                $cekresultData = $this->master->cekCount('kehadiran', ['id_pegawai' => $idPegawai, 'tgl_kehadiran' => $tglMulai]);
                                if ($cekresultData >0) {
                                    ($resultData['id_keterangan_absensi'] == 0) ? $cekIzin = $cekIzin : $cekIzin++;
                                }
                                if ($resultData == null or $resultData == "") {
                                    $pegawai = $this->pegawai->getPegawaibyid($idPegawai);
                                    $dataKehadiranInsert[] = [
                                        "id_pegawai" => $idPegawai,
                                        "tgl_kehadiran" => $tglMulai,
                                        "status_hadir" => "izin",
                                        "temp_status_hadir" => "absen",
                                        "jam_masuk" => "00:00:00",
                                        "jam_pulang" => "00:00:00",
                                        "lambat_datang" => 0,
                                        "cepat_pulang" => 0,
                                        "id_keterangan_absensi" => $idKeteranganAbsensi,
                                        "id_jabatan" => $pegawai['id_jabatan'],
                                        "id_skpd" => $pegawai['id_skpd']
                                    ];
                                } else {
                                    $dataKehadiranUpdate[] = [
                                        "id_kehadiran" => $resultData['id_kehadiran'],
                                        "status_hadir" => 'izin',
                                        "temp_status_hadir" => $resultData['status_hadir'],
                                        "id_keterangan_absensi" => $idKeteranganAbsensi
                                    ];
                                }
                            }
                        }

                        $tglMulai = date('Y-m-d', strtotime(
                            '+1 days',
                            strtotime($tglMulai)
                        ));
                    }

                    $string = [
                        'keterangan_absensi' => $dataKeterangan,
                        'kehadiran' => ['insert' => $dataKehadiranInsert, 'update' => $dataKehadiranUpdate]
                    ];
                    $log = simpan_log("Insert Pegawai Pegawai Izin", json_encode($string));

                    // lock agar tidak bisa upload saat tanggal yg dipilih lebih dari seminggu dari tanggal sekarang
                    $tglSekarang = date('Y-m-d');
                    $tglBolehUpload = date('Y-m-d', strtotime(
                        '-20 days',
                        strtotime($tglSekarang)
                    ));
                    if (format_tanggal_database($tanggalMulai) < $tglBolehUpload) {
                        unlink(FCPATH . 'uploads/berkas_absensi/' . $upload['file_name']);
                        $data = [
                            'status' => FALSE,
                            'tanggal' => TRUE,
                            'pesan' => 'Tanggal upload tidak diizinkan!!'
                        ];
                    } else {
                        // cek tanggal mulai harus lebih besar dari tanggal akhir
                        if (format_tanggal_database($tanggalMulai) <= format_tanggal_database($tanggalAkhir)) {
                            // cek untuk memastikan tanggal belum digunakan
                            if ($cekIzin == 0) {
                                $res = $this->status->insertStatusAbsensi($dataKeterangan, $dataKehadiranInsert, $dataKehadiranUpdate, $log);
                                $data = ['status' => TRUE, 'notif' => $res];
                            } else {
                                unlink(FCPATH . 'uploads/berkas_absensi/' . $upload['file_name']);
                                $data = [
                                    'status' => FALSE,
                                    'tanggal' => TRUE,
                                    'pesan' => 'Tanggal sudah digunakan, Silahkan pilih tanggl lain'
                                ];
                            }
                        } else {
                            unlink(FCPATH . 'uploads/berkas_absensi/' . $upload['file_name']);
                            $data = [
                                'status' => FALSE,
                                'tanggal' => TRUE,
                                'pesan' => 'Tanggal akhir harus lebih besar dari tanggal mulai'
                            ];
                        }
                    }
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        } else {
            redirect(site_url('blocked'));
        }
    }

    function deleteData()
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $id = htmlspecialchars($this->input->post('keterangan_absensi', TRUE));
            $idIzin = decrypt_url($id);
            $temp = $this->master->selectDataId('keterangan_absensi', ['id_keterangan_absensi' => $idIzin]);
            $resultData = $this->master->selectDataBy('kehadiran', ['id_keterangan_absensi' => $idIzin]);
            $tgl_sekarang = date('Y-m-d');
            $dataKehadiranUpdate = [];
            $dataKehadiranDelete = [];
            foreach ($resultData as $kehadiran) {
                // if ($kehadiran['tgl_kehadiran'] > $tgl_sekarang) {
                //     $dataKehadiranDelete[] = $kehadiran['id_kehadiran'];
                // } else {
                //     $dataKehadiranUpdate[] = [
                //         "id_kehadiran" => $kehadiran['id_kehadiran'],
                //         "status_hadir" => $kehadiran['temp_status_hadir'],
                //         "temp_status_hadir" => '-',
                //         "id_keterangan_absensi" => 0
                //     ];
                // }
                $dataKehadiranDelete[] = $kehadiran['id_kehadiran'];
            }
            $string = [
                'keterangan_absensi' => $temp,
                'kehadiran' => ['update' => $dataKehadiranUpdate, 'delete' => $dataKehadiranDelete]
            ];
            $log = simpan_log("Delete Pegawai Izin", json_encode($string));
            $res = $this->status->DeleteStatusAbsensi($idIzin, $dataKehadiranUpdate, $dataKehadiranDelete, $log);
            if ($res > 0) {
                if ($temp['berkas_absensi'] != "") {
                    if (file_exists(FCPATH . 'uploads/berkas_absensi/' . $temp['berkas_absensi'])) {
                        unlink(FCPATH . 'uploads/berkas_absensi/' . $temp['berkas_absensi']);
                    }
                }
            }
            $data = ['notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            redirect(site_url('blocked'));
        }
    }
}
