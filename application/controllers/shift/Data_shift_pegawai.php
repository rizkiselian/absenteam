<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_shift_pegawai extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Status_absensi_model', 'status');
        $this->simpeg = 'kepegawaian';
        is_logged_in();

        $this->role = $this->session->userdata('role_admin');
    }

    function index()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_shift",
                "submenu" => "submenu_shift_pegawai",
                "role" => $this->role,
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC'),
            ];
            $this->load->view('shift/data_shift_pegawai/view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_shift",
                "submenu" => "submenu_shift_pegawai",
                "skpd" => $this->session->userdata('sess_skpd'),
                "role" => $this->role
            ];
            $this->load->view('shift/data_shift_pegawai/view_skpd', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadData()
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $id = htmlspecialchars($this->input->post('skpd', TRUE));
            $idSkpd = decrypt_url($id);
            $result = $this->master->selectDataBy('shift_pegawai', ['id_skpd'=>$idSkpd], 'id_pegawai ASC, tanggal ASC');
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                $pegawai=$this->master->selectDataId($this->simpeg. '.pegawai', ['id_pegawai' => $r['id_pegawai']]);
                $nama_pegawai = format_nama($pegawai['gelar_depan'], $pegawai['nama_pegawai'], $pegawai['gelar_belakang']);

                $edit = "<button id='tombol-ubah' data-id='" . encrypt_url($r['id']) . "' data-toggle='modal' data-target='#modal-ubah' class='btn btn-icon btn-round btn-success btn-sm' title='UBAH'><i class='fa fa-edit'></i> </button>";
                $delete = "<button id='tombol-hapus' data-id='" . encrypt_url($r['id']) . "' class='btn btn-icon btn-round btn-danger btn-sm' title='HAPUS'><i class='fa fa-trash'></i></button>";  

                if($r['id_shift']==1){$ket="Shift Pagi";}
                elseif($r['id_shift']==2){$ket="Shift Siang";}
                else{$ket="Shift Malam";}
                $row = [
                    'no' => $no,
                    'nama_pegawai' => $nama_pegawai,
                    'tanggal' => format_tanggal($r['tanggal']),
                    'id_shift' => $ket,
                    'aksi' => $edit." ".$delete
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
        $this->form_validation->set_rules('id_skpd', 'ID SKPD', 'required|trim');
        $this->form_validation->set_rules('id_pegawai', 'Nama Pegawai', 'required|trim');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
        $this->form_validation->set_rules('id_shift', 'SHift', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    function formAddData()
    {
        if ($this->role == "admin" or $this->role == "skpd") {
            $id = htmlspecialchars($this->input->post('skpd', TRUE));
            $idSkpd = decrypt_url($id);
            $data['idSkpd'] = $idSkpd;
            $data['result_pegawai'] = $this->status->getNamaPegawai($idSkpd);
            $this->load->view('shift/data_shift_pegawai/form_add', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function formEditData()
    {
        if ($this->role == "admin" OR $this->role == "skpd") {
            $id_get = htmlspecialchars($this->input->post('id', TRUE));
            $id = decrypt_url($id_get);
            $shift_pegawai=$this->master->selectDataId('shift_pegawai', ['id' => $id]);
            $id = decrypt_url($id_get);
            $data['result_pegawai'] = $this->status->getNamaPegawai($shift_pegawai['id_skpd']);
            $data['shift_pegawai'] = $this->master->selectDataId('shift_pegawai', ['id' => $id]);
            $this->load->view('shift/data_shift_pegawai/form_edit', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function addData()
    {
        if ($this->role == "admin" OR $this->role == "skpd") {
            $this->_ruleFormInput();
            $post = $this->input->post(null, TRUE);
            if ($this->form_validation->run() == false) {
                $errors = [
                    'id_skpd' => form_error('id_skpd'),
                    'id_pegawai' => form_error('id_pegawai'),
                    'tanggal' => form_error('tanggal'),
                    'id_shift' => form_error('id_shift')
                ];
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $id = htmlspecialchars($post['id_pegawai']);
                $idPegawai = decrypt_url($id);
                $tgl = htmlspecialchars($post['tanggal']);
                $tanggal = format_tanggal_database($tgl);
                $dataInsert =  [
                    'id_skpd' => htmlspecialchars($post['id_skpd']),
                    'id_pegawai' => $idPegawai,
                    'tanggal' => $tanggal,
                    'id_shift' => htmlspecialchars($post['id_shift'])
                ];

                $dataUser = [
                    'status_shift' =>  1
                ];
                $res = $this->db->update($this->simpeg. '.pegawai', $dataUser, ['id_pegawai' => $idPegawai]);

                $string = ['shift_pegawai' => $dataInsert];
                $log = simpan_log("Insert shift_pegawai", json_encode($string));
                $res = $this->master->insertData('shift_pegawai', $dataInsert, $log);
                $data = ['status' => TRUE, 'notif' => $res];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            redirect(site_url('blocked'));
        }
    }

    function editData()
    {
        if ($this->role == "admin" OR $this->role == "skpd") {
            $this->_ruleFormInput();
            $post = $this->input->post(null, TRUE);
            $id = htmlspecialchars($post['id_skpd']);
            $temp = $this->master->selectDataId('shift_pegawai', ['id' => $id]);
            if ($this->form_validation->run() == false) {
                $errors = [
                    'id_skpd' => form_error('id_skpd'),
                    'id_pegawai' => form_error('id_pegawai'),
                    'tanggal' => form_error('tanggal'),
                    'id_shift' => form_error('id_shift')
                ];
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $id_peg = htmlspecialchars($post['id_pegawai']);
                $idPegawai = decrypt_url($id_peg);
                $tgl = htmlspecialchars($post['tanggal']);
                $tanggal = format_tanggal_database($tgl);
                $dataUpdate =  [
                    'id_pegawai' => $idPegawai,
                    'tanggal' => $tanggal,
                    'id_shift' => htmlspecialchars($post['id_shift'])
                ];
                $string = ['shift_pegawai' => ['data_lama' => $temp], ['data_baru' => $dataUpdate]];
                $log = simpan_log("Update shift_pegawai", json_encode($string));
                $res = $this->master->updateData('shift_pegawai', $dataUpdate, ['id' => $id], $log);
                $data = ['status' => TRUE, 'notif' => $res];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            redirect(site_url('blocked'));
        }
    }

    function deleteData()
    {
        if ($this->role == "admin" OR $this->role == "skpd") {
            $id = htmlspecialchars($this->input->post('id', TRUE));
            $id_shift = decrypt_url($id);
            $temp = $this->master->selectDataId('shift_pegawai', ['id' => $id_shift]);
            $string = ['shift_pegawai' => $temp];
            $log = simpan_log("Delete shift_pegawai", json_encode($string));
            $res = $this->master->deleteData('shift_pegawai', ['id' => $id_shift], $log);
            $data = ['notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            redirect(site_url('blocked'));
        }
    }
}
