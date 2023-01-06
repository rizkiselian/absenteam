<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_shift extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->simpeg = 'kepegawaian';
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    function index()
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $data = [
                "menu" => "menu_shift",
                "submenu" => "submenu_shift",
                "role" => $this->role
            ];
            $this->load->view('shift/data_shift/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadData()
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $result = $this->master->selectData('shift', 'id_shift ASC');
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;

                if($this->role == "admin")
                {
                    $edit = "<button id='tombol-ubah' data-id='" . encrypt_url($r['id_shift']) . "' data-toggle='modal' data-target='#modal-ubah' class='btn btn-icon btn-round btn-success btn-sm' title='UBAH'><i class='fa fa-edit'></i> </button>";
                    $delete = "<button id='tombol-hapus' data-id='" . encrypt_url($r['id_shift']) . "' class='btn btn-icon btn-round btn-danger btn-sm' title='HAPUS'><i class='fa fa-trash'></i></button>";  
                }
                else
                {
                    $edit = "";
                    $delete ="";
                }
                $row = [
                    'no' => $no,
                    'nama_shift' => $r['nama_shift'],
                    'jammasuk' => $r['jammasuk'],
                    'jampulang' => $r['jampulang'],
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
        $this->form_validation->set_rules('nama_shift', 'Nama Shift', 'required|trim');
        $this->form_validation->set_rules('jammasuk', 'Jam Masuk', 'required|trim');
        $this->form_validation->set_rules('jampulang', 'Jam Pulang', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    function formAddData()
    {
        if ($this->role == "admin") {
            $this->load->view('shift/data_shift/form_add');
        } else {
            redirect(site_url('blocked'));
        }
    }

    function formEditData()
    {
        if ($this->role == "admin") {
            $id = htmlspecialchars($this->input->post('id', TRUE));
            $id_shift = decrypt_url($id);
            $data['shift'] = $this->master->selectDataId('shift', ['id_shift' => $id_shift]);
            $this->load->view('shift/data_shift/form_edit', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function addData()
    {
        if ($this->role == "admin") {
            $this->_ruleFormInput();
            $post = $this->input->post(null, TRUE);
            if ($this->form_validation->run() == false) {
                $errors = [
                    'nama_shift' => form_error('nama_shift'),
                    'jammasuk' => form_error('jammasuk'),
                    'jampulang' => form_error('jampulang')
                ];
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $dataInsert =  [
                    'nama_shift' => htmlspecialchars($post['nama_shift']),
                    'jammasuk' => htmlspecialchars($post['jammasuk']),
                    'jampulang' => htmlspecialchars($post['jampulang'])
                ];
                $string = ['shift' => $dataInsert];
                $log = simpan_log("Insert shift", json_encode($string));
                $res = $this->master->insertData('shift', $dataInsert, $log);
                $data = ['status' => TRUE, 'notif' => $res];
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
            $id = htmlspecialchars($post['id_shift']);
            $id_shift = decrypt_url($id);
            $temp = $this->master->selectDataId('shift', ['id_shift' => $id_shift]);
            if ($this->form_validation->run() == false) {
                $errors = [
                    'nama_shift' => form_error('nama_shift'),
                    'jammasuk' => form_error('jammasuk'),
                    'jampulang' => form_error('jampulang')
                ];
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $dataUpdate =  [
                    'nama_shift' => htmlspecialchars($post['nama_shift']),
                    'jammasuk' => htmlspecialchars($post['jammasuk']),
                    'jampulang' => htmlspecialchars($post['jampulang'])
                ];
                $string = ['shift' => ['data_lama' => $temp], ['data_baru' => $dataUpdate]];
                $log = simpan_log("Update shift", json_encode($string));
                $res = $this->master->updateData('shift', $dataUpdate, ['id_shift' => $id_shift], $log);
                $data = ['status' => TRUE, 'notif' => $res];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            redirect(site_url('blocked'));
        }
    }

    function deleteData()
    {
        if ($this->role == "admin") {
            $id = htmlspecialchars($this->input->post('id', TRUE));
            $id_shift = decrypt_url($id);
            $temp = $this->master->selectDataId('shift', ['id_shift' => $id_shift]);
            $string = ['shift' => $temp];
            $log = simpan_log("Delete shift", json_encode($string));
            $res = $this->master->deleteData('shift', ['id_shift' => $id_shift], $log);
            $data = ['notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            redirect(site_url('blocked'));
        }
    }
}
