<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kehadiran_manual extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Kehadiran_manual_model', 'manual');
        $this->simpeg = 'kepegawaian';
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    function index()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_kehadiran_manual",
                "submenu" => null,
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC'),
                "role" => $this->role
            ];
            $this->load->view('kehadiran_manual/view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_kehadiran_manual",
                "submenu" => null,
                "skpd" => $this->session->userdata('sess_skpd'),
                "role" => $this->role
            ];
            $this->load->view('kehadiran_manual/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadData($id)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $result = $this->master->selectDataBy('kehadiran_manual', ['id_skpd' => $idSkpd], 'tanggal DESC');
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                if (($r['berkas'] != "") or ($r['berkas'] != null)) {
                    $file_berkas = base_url('uploads/berkas_absensi/' . $r['berkas']);
                    $berkas_absensi = "<a href='" . $file_berkas . "' class='btn btn-success btn-xs' target='_blank'><i class='fa fa-download'></i> UNDUH</a>";
                } else {
                    $berkas_absensi = "No File";
                }
                $delete = "<button id='tombol-hapus' data-id='" . encrypt_url($r['id_kehadiran_manual']) . "' class='btn btn-icon btn-round btn-danger btn-sm' title='HAPUS'><i class='fa fa-trash'></i></button>";
                $row = [
                    'no' => $no,
                    'tanggal' => format_tanggal($r['tanggal']),
                    'keterangan' => $r['keterangan'],
                    'berkas' => $berkas_absensi,
                    'waktu_input' => date('d-m-Y H:i:s', strtotime($r['tgl_input'])),
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

    private function _ruleFormInput()
    {
        $this->form_validation->set_rules('tgl_hadir', 'Tanggal', 'required|trim');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    function formAddData()
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $skpd = htmlspecialchars($this->input->post('unit_kerja', TRUE));
            $idSkpd = decrypt_url($skpd);
            $data['skpd'] = $skpd;
            $data['result_pegawai'] = $this->manual->getPegawai($idSkpd);
            $data['result_honor'] = $this->manual->getTenagaKontrak($idSkpd);
            $this->load->view('kehadiran_manual/form_add', $data);
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
                    'tgl_hadir' => form_error('tgl_hadir'),
                    'keterangan' => form_error('keterangan')
                ];
                $data = ['status' => FALSE, 'tanggal' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $post = $this->input->post(null, TRUE);
                $tglSekarang = date('Y-m-d');
                $tgl = htmlspecialchars($post['tgl_hadir']);
                $id = htmlspecialchars($post['id_skpd']);
                $idSkpd = decrypt_url($id);
                $tglHadir = format_tanggal_database($tgl);

                $config['upload_path']      = './uploads/berkas_absensi/';
                $config['allowed_types']    = 'pdf';
                $config['file_name']        = 'kehadiran-manual-' . $idSkpd . '-' . date("Ymd-His");
                $config['max_size']         = 1024;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('file_upload')) {
                    $errors = [
                        'tgl_hadir' => form_error('tgl_hadir'),
                        'keterangan' => form_error('keterangan'),
                        'file_upload'   => $this->upload->display_errors()
                    ];
                    $data = ['status' => FALSE, 'tanggal' => FALSE, 'errors' => $errors];
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                } else {
                    $upload = $this->upload->data();
                    // cek tabel kehadiran sesuai tanggal yang dipilih
                    $kehadiranManual = $this->master->cekCount('kehadiran_manual', ['tanggal' => $tglHadir, 'id_skpd' => $idSkpd]);

                    if ($kehadiranManual > 0) {
                        unlink(FCPATH . 'uploads/berkas_absensi/' . $upload['file_name']);
                        $data = [
                            'status' => FALSE,
                            'tanggal' => TRUE,
                            'pesan' => 'Tanggal Ini Sudah Digunakan'
                        ];
                    } else {
                        $cekCode = $this->master->cekCount('kehadiran_manual', ['tanggal' => $tglSekarang]);
                        if ($cekCode > 0) {
                            $this->db->select('MAX(RIGHT(id_kehadiran_manual, 3)) as kode');
                            $this->db->from('kehadiran_manual');
                            $this->db->where('tanggal', $tglSekarang);
                            $lastCode = $this->db->get()->row_array();
                            $kode = ((int)$lastCode['kode']) + 1;
                            $codeId = sprintf("%03s", $kode);
                        } else {
                            $codeId = '001';
                        }
                        $idManual = '1' . date('ymd') . $codeId;
                        $pegawai_hadir = $this->manual->getPegawaiHadir($tglHadir, $idSkpd, $post['id_pegawai']);
                        $this->master->selectDataBy('kehadiran', ['tgl_kehadiran' => $tglHadir, 'id_skpd' => $idSkpd]);
                        $manual = [
                            "id_kehadiran_manual" => $idManual,
                            "tanggal" => $tglHadir,
                            "id_skpd" => $idSkpd,
                            "keterangan" => htmlspecialchars($post['keterangan']),
                            "tgl_input" => date('Y-m-d H:i:s'),
                            "berkas" => $upload['file_name']
                        ];
                        $detail = [];
                        $update = [];
                        foreach ($pegawai_hadir as $row) {
                            $detail[] = [
                                'id_kehadiran_manual' => $idManual,
                                'id_kehadiran' => $row['id_kehadiran'],
                                'status_hadir' => $row['status_hadir'],
                                'jam_masuk' => $row['jam_masuk'],
                                'lambat_datang' => $row['lambat_datang']
                            ];
                            $update[] = [
                                'id_kehadiran' => $row['id_kehadiran'],
                                'status_hadir' => 'hadir',
                                'jam_masuk' => '07:30:00',
                                'lambat_datang' => 0,
                                'id_keterangan_absensi' => $idManual
                            ];
                        }
                        $data = ["kehadiran_manual" => $manual, "detail" => $detail, "kehadiran" => $update];
                    }

                    $string = [
                        'kehadiran_manual' => $manual,
                        'kehadiran_manual_detail' => $detail,
                        'kehadiran' => $update
                    ];
                    $log = simpan_log("Insert Kehadiran Manual", json_encode($string));

                    // lock agar tidak bisa upload pada tanggal yang lain
                    if ($tglSekarang != date('Y-m-d')) {
                        unlink(FCPATH . 'uploads/berkas_absensi/' . $upload['file_name']);
                        $data = [
                            'status' => FALSE,
                            'tanggal' => TRUE,
                            'pesan' => 'Tanggal Kehadiran Manual Tidak Sesuai'
                        ];
                    } else {
                        $res = $this->manual->insertKehadiranManual($update, $manual, $detail, $log);
                        $data = ['status' => TRUE, 'notif' => $res];
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
            $id = htmlspecialchars($this->input->post('kehadiran_manual', TRUE));
            $idManual = decrypt_url($id);
            $temp = $this->master->selectDataId('kehadiran_manual', ['id_kehadiran_manual' => $idManual]);
            $resultData = $this->master->selectDataBy('kehadiran_manual_detail', ['id_kehadiran_manual' => $idManual]);
            $kehadiranUpdate = [];
            foreach ($resultData as $kehadiran) {
                $kehadiranUpdate[] = [
                    "id_kehadiran" => $kehadiran['id_kehadiran'],
                    "status_hadir" => $kehadiran['status_hadir'],
                    "jam_masuk" => $kehadiran['jam_masuk'],
                    "lambat_datang" => $kehadiran['lambat_datang'],
                    "id_keterangan_absensi" => 0
                ];
            }
            $string = [
                'kehadiran_manual' => $temp,
                'kehadiran' => $kehadiranUpdate
            ];
            $log = simpan_log("Delete Kehadiran_manual", json_encode($string));
            $res = $this->manual->DeleteKehadiranManual($idManual, $kehadiranUpdate, $log);
            if ($res > 0) {
                if ($temp['berkas'] != "") {
                    if (file_exists(FCPATH . 'uploads/berkas_absensi/' . $temp['berkas'])) {
                        unlink(FCPATH . 'uploads/berkas_absensi/' . $temp['berkas']);
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
