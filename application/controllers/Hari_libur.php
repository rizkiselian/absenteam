<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hari_libur extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    function index()
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $data = [
                "menu" => "menu_hari_libur",
                "submenu" => null,
                "role" => $this->role
            ];
            $this->load->view('hari_libur/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadData()
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $result = $this->master->selectData("hari_libur", "tanggal_libur DESC");
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                $edit = "<button id='tombol-ubah' data-id='" . encrypt_url($r['id_hari_libur']) . "' data-toggle='modal' data-target='#modal-ubah' class='btn btn-icon btn-round btn-success btn-sm' title='UBAH'><i class='fa fa-edit'></i> </button>";
                $delete = "<button id='tombol-hapus' data-id='" . encrypt_url($r['id_hari_libur']) . "' class='btn btn-icon btn-round btn-danger btn-sm' title='HAPUS'><i class='fa fa-trash'></i></button>";
                $row = [
                    'no' => $no,
                    'tanggal' => date_to_indo($r['tanggal_libur']),
                    'keterangan' => $r['keterangan'],
                    'aksi' => $edit . ' ' . $delete
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
        $this->form_validation->set_rules('tgl_libur', 'Tanggal', 'required|trim');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    function formAddData()
    {
        if ($this->role == "admin") {
            $this->load->view('hari_libur/form_add');
        } else {
            redirect(site_url('blocked'));
        }
    }

    function formEditData()
    {
        if ($this->role == "admin") {
            $id = htmlspecialchars($this->input->post('hari_libur', TRUE));
            $idLibur = decrypt_url($id);
            $data['libur'] = $this->master->selectDataId('hari_libur', ['id_hari_libur' => $idLibur]);
            $this->load->view('hari_libur/form_edit', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function addData()
    {
        if ($this->role == "admin") {
            $this->_ruleFormInput();
            $post = $this->input->post(null, TRUE);
            $tgl = htmlspecialchars($post['tgl_libur']);
            $tglLibur = format_tanggal_database($tgl);
            $cekTanggal = $this->master->selectDataId('hari_libur', ['tanggal_libur' => $tglLibur]);
            if ($this->form_validation->run() == false) {
                if ($cekTanggal > 0) {
                    $errors = [
                        'tgl_libur' => "<p>Tanggal sudah digunakan, silahkan pilih tanggal lain</p>",
                        'keterangan' => form_error('keterangan')
                    ];
                } else {
                    $errors = [
                        'tgl_libur' => form_error('tgl_libur'),
                        'keterangan' => form_error('keterangan')
                    ];
                }
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                if ($cekTanggal > 0) {
                    $errors = [
                        'tgl_libur' => "<p>Tanggal sudah digunakan, silahkan pilih tanggal lain</p>",
                        'keterangan' => ""
                    ];
                    $data = ['status' => FALSE, 'errors' => $errors];
                } else {
                    $dataInsert =  [
                        'tanggal_libur' => format_tanggal_database($tglLibur),
                        'keterangan' => strtoupper(htmlspecialchars($post['keterangan']))
                    ];
                    $string = ['hari_libur' => $dataInsert];
                    $log = simpan_log("Insert Hari Libur", json_encode($string));
                    $res = $this->master->insertData('hari_libur', $dataInsert, $log);
                    $data = ['status' => TRUE, 'notif' => $res];
                }
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            redirect(site_url('blocked'));
        }
    }

    function editData()
    {
        if ($this->role == "admin") {
            $this->_ruleFormInput();
            $post = $this->input->post(null, TRUE);
            $id = htmlspecialchars($post['id_hari_libur']);
            $idLibur = decrypt_url($id);
            $tgl = htmlspecialchars($post['tgl_libur']);
            $tglLibur = format_tanggal_database($tgl);
            $temp = $this->master->selectDataId('hari_libur', ['id_hari_libur' => $idLibur]);
            $cekTanggal = $this->master->selectDataId('hari_libur', ['tanggal_libur' => $tglLibur, 'tanggal_libur !=' => $temp['tanggal_libur']]);
            if ($this->form_validation->run() == false) {
                if ($cekTanggal > 0) {
                    $errors = [
                        'tgl_libur' => "<p>Tanggal sudah digunakan, silahkan pilih tanggal lain</p>",
                        'keterangan' => form_error('keterangan')
                    ];
                } else {
                    $errors = [
                        'tgl_libur' => form_error('tgl_libur'),
                        'keterangan' => form_error('keterangan')
                    ];
                }
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                if ($cekTanggal > 0) {
                    $errors = [
                        'tgl_libur' => "<p>Tanggal sudah digunakan, silahkan pilih tanggal lain</p>",
                        'keterangan' => ""
                    ];
                    $data = ['status' => FALSE, 'errors' => $errors];
                } else {
                    $dataUpdate =  [
                        'tanggal_libur' => format_tanggal_database($tglLibur),
                        'keterangan' => strtoupper(htmlspecialchars($post['keterangan']))
                    ];
                    $string = ['hari_libur' => ['data_lama' => $temp], ['data_baru' => $dataUpdate]];
                    $log = simpan_log("Update Hari Libur", json_encode($string));
                    $res = $this->master->updateData('hari_libur', $dataUpdate, ['id_hari_libur' => $idLibur], $log);
                    $data = ['status' => TRUE, 'notif' => $res];
                }
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            redirect(site_url('blocked'));
        }
    }

    function deleteData()
    {
        if ($this->role == "admin") {
            $id = htmlspecialchars($this->input->post('hari_libur', TRUE));
            $idLibur = decrypt_url($id);
            $temp = $this->master->selectDataId('hari_libur', ['id_hari_libur' => $idLibur]);
            $string = ['hari_libur' => $temp];
            $log = simpan_log("Delete Hari Libur", json_encode($string));
            $res = $this->master->deleteData('hari_libur', ['id_hari_libur' => $idLibur], $log);
            $data = ['notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            redirect(site_url('blocked'));
        }
    }
}
