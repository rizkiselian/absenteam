<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('User_model', 'user');
        $this->simpeg = 'kepegawaian';
        $this->role = $this->session->userdata('role_admin');
    }

    function index()
    {
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_user_unit_kerja",
                "submenu" => null
            ];
            $this->load->view('user/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadData()
    {
        if ($this->role == "admin") {
            $result = $this->user->getUserSkpd();
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                $status = ($r['status_aktif'] == 'Y') ? $status = "AKTIF" : $status = "TIDAK AKTIF";
                $reset = "<button id='tombol-reset' data-id='" . encrypt_url($r['id_user']) . "' data-toggle='modal' data-target='#modal-reset' class='btn btn-icon btn-round btn-warning btn-sm' title='RESET'><i class='fa fa-key'></i> </button>";
                $edit = "<button id='tombol-ubah' data-id='" . encrypt_url($r['id_user']) . "' data-toggle='modal' data-target='#modal-ubah' class='btn btn-icon btn-round btn-success btn-sm' title='UBAH'><i class='fa fa-edit'></i> </button>";
                $delete = "<button id='tombol-hapus' data-id='" . encrypt_url($r['id_user']) . "' class='btn btn-icon btn-round btn-danger btn-sm' title='HAPUS'><i class='fa fa-trash'></i></button>";
                $row = [
                    'no' => $no,
                    'skpd' => $r['nama_skpd'],
                    'username' => $r['username'],
                    'nip' => $r['nip'],
                    'nama' => format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']),
                    'status' => $status,
                    'aksi' => $reset . ' ' .$edit . ' ' . $delete
                ];
                $data[] = $row;
            }
            $output['data'] = $data;
            echo json_encode($output);
        } else {
            redirect(site_url('blocked'));
        }
    }

    private function _ruleFormInput($rule)
    {
        if ($rule == "input") {
            $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[user_absensi.username]');
            $this->form_validation->set_message('is_unique', '%s sudah digunakan');
        } else {

            $this->form_validation->set_rules('username', 'Username', 'required|trim');
        }
        $this->form_validation->set_rules('unit_kerja', 'Unit kerja', 'required|trim');
        $this->form_validation->set_rules('nama_pegawai', 'Nama pegawai', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    function formAddData()
    {
        if ($this->role == "admin") {
            $data['result_skpd'] = $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC');
            $this->load->view('user/form_add', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function formEditData()
    {
        if ($this->role == "admin") {
            $id = htmlspecialchars($this->input->post('user', TRUE));
            $idUser = decrypt_url($id);
            $data['user'] = $this->master->selectDataId('user_absensi', ['id_user' => $idUser]);
            $data['result_skpd'] = $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC');
            $this->load->view('user/form_edit', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function formResetData()
    {
        if ($this->role == "admin") {
            $id = htmlspecialchars($this->input->post('user', TRUE));
            $idUser = decrypt_url($id);
            $data['user'] = $this->master->selectDataId('user_absensi', ['id_user' => $idUser]);
            $data['result_skpd'] = $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC');
            $this->load->view('user/form_reset', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function addData()
    {
        if ($this->role == "admin") {
            $this->_ruleFormInput("input");
            $post = $this->input->post(null, TRUE);
            $skpd = htmlspecialchars($post['unit_kerja']);
            $idSkpd = decrypt_url($skpd);
            $cekUser = $this->master->selectDataId('user_absensi', ['id_skpd' => $idSkpd, 'role_admin' => 'skpd']);
            if ($this->form_validation->run() == false) {
                if ($cekUser > 0) {
                    $errors = [
                        'unit_kerja' => "<p>User unit kerja ini sudah digunakan</p>",
                        'username' => form_error('username'),
                        'nama_pegawai' => form_error('nama_pegawai')
                    ];
                } else {
                    $errors = [
                        'unit_kerja' => form_error('unit_kerja'),
                        'username' => form_error('username'),
                        'nama_pegawai' => form_error('nama_pegawai')
                    ];
                }
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                if ($cekUser > 0) {
                    $errors = [
                        'unit_kerja' => "<p>User unit kerja ini sudah digunakan</p>",
                        'username' => form_error('username'),
                        'nama_pegawai' => form_error('nama_pegawai')
                    ];
                    $data = ['status' => FALSE, 'errors' => $errors];
                } else {
                    $idPegawai = htmlspecialchars($post['nama_pegawai']);
                    $dataUser =  [
                        'username' => htmlspecialchars($post['username']),
                        'password' =>  password_hash('123456', PASSWORD_DEFAULT),
                        'role_admin' => "skpd",
                        'id_skpd' => $idSkpd,
                        'id_pegawai' => decrypt_url($idPegawai),
                        'status_aktif' => 'Y',
                        'tgl_input' => date('Y-m-d H:i:s')
                    ];
                    $dataUserHistory = [
                        'username' => htmlspecialchars($post['username']),
                        'role_admin' => "skpd",
                        'id_skpd' => $idSkpd,
                        'id_pegawai' => decrypt_url($idPegawai),
                        'tgl_input' => date('Y-m-d H:i:s')
                    ];
                    $string = ['user_absensi' => $dataUser, 'user_absensi_history' => $dataUserHistory];
                    $log = simpan_log("Insert User Unit Kerja", json_encode($string));
                    $res = $this->user->insertUser($dataUser, $dataUserHistory, $log);
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
            $this->_ruleFormInput("update");
            if ($this->form_validation->run() == false) {
                $errors = [
                    'unit_kerja' => form_error('unit_kerja'),
                    'username' => form_error('username'),
                    'nama_pegawai' => form_error('nama_pegawai')
                ];
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $post = $this->input->post(null, TRUE);
                $id = htmlspecialchars($post['id_user']);
                $idPegawai = htmlspecialchars($post['nama_pegawai']);
                $skpd = htmlspecialchars($post['unit_kerja']);
                $idUser = decrypt_url($id);
                $idSkpd = decrypt_url($skpd);
                $dataUser =  [
                    'username' => htmlspecialchars($post['username']),
                    'id_skpd' => $idSkpd,
                    'id_pegawai' => decrypt_url($idPegawai)
                ];
                $dataUserHistory = [
                    'username' => htmlspecialchars($post['username']),
                    'role_admin' => "skpd",
                    'id_skpd' => $idSkpd,
                    'id_pegawai' => decrypt_url($idPegawai),
                    'tgl_input' => date('Y-m-d H:i:s')
                ];
                $temp = $this->master->selectDataId('user_absensi', ['id_user' => $idUser]);
                $string = ['user_absensi' => ['data_lama' => $temp], ['data_baru' => $dataUser], 'user_absensi_history' => $dataUserHistory];
                $log = simpan_log("Update User Unit Kerja", json_encode($string));
                $res = $this->user->updateUser($idUser, $dataUser, $dataUserHistory, $log);
                $data = ['status' => TRUE, 'notif' => $res];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            redirect(site_url('blocked'));
        }
    }

    function resetData()
    {
        if ($this->role == "admin") {
            $this->_ruleFormInput("update");
            if ($this->form_validation->run() == false) {
                $errors = [
                    'unit_kerja' => form_error('unit_kerja'),
                    'username' => form_error('username'),
                    'nama_pegawai' => form_error('nama_pegawai')
                ];
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $post = $this->input->post(null, TRUE);
                $id = htmlspecialchars($post['id_user']);
                $idPegawai = htmlspecialchars($post['nama_pegawai']);
                $skpd = htmlspecialchars($post['unit_kerja']);
                $idUser = decrypt_url($id);
                $idSkpd = decrypt_url($skpd);
                $dataUser =  [
                    'username' => htmlspecialchars($post['username']),
                    'password' =>  password_hash('123456', PASSWORD_DEFAULT),
                    'id_skpd' => $idSkpd,
                    'id_pegawai' => decrypt_url($idPegawai)
                ];
                $dataUserHistory = [
                    'username' => htmlspecialchars($post['username']),
                    'role_admin' => "skpd",
                    'id_skpd' => $idSkpd,
                    'id_pegawai' => decrypt_url($idPegawai),
                    'tgl_input' => date('Y-m-d H:i:s')
                ];
                $temp = $this->master->selectDataId('user_absensi', ['id_user' => $idUser]);
                $string = ['user_absensi' => ['data_lama' => $temp], ['data_baru' => $dataUser], 'user_absensi_history' => $dataUserHistory];
                $log = simpan_log("reset password User Unit Kerja", json_encode($string));
                $res = $this->user->updateUser($idUser, $dataUser, $dataUserHistory, $log);
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
            $id = htmlspecialchars($this->input->post('user', TRUE));
            $idUser = decrypt_url($id);
            $temp = $this->master->selectDataId('user_absensi', ['id_user' => $idUser]);
            $string = ['user_absensi' => $temp];
            $log = simpan_log("Delete User Unit Kerja", json_encode($string));
            $res = $this->master->deleteData('user_absensi', ['id_user' => $idUser], $log);
            $data = ['notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            redirect(site_url('blocked'));
        }
    }

    // ==============================================================================================
    function profile()
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $username = $this->session->userdata('username');
            $data = [
                "menu" => null,
                "submenu" => null,
                "user" => $this->user->getUserId($username)
            ];
            $this->load->view('user/profile', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    
    function formUploadFoto()
    {
        if (($this->role == "admin") or ($this->role = "skpd")) {
            $username = $this->session->userdata('username');
            $data['user'] = $this->user->getUserId($username);
            $this->load->view('user/form_upload_foto', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function uploadFoto()
    {
        if (($this->role == "admin") or ($this->role = "skpd")) {
            $post = $this->input->post(null, TRUE);
            $username = htmlspecialchars($post['username']);

            $config['upload_path']      = './uploads/users/';
            $config['allowed_types']    = 'jpg|png|jpeg';
            $config['file_name']        = $username . '-' . date("Ymd-His");
            $config['max_size']         = 512;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file_upload')) {
                $errors = ['file_upload' => $this->upload->display_errors()];
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $upload = $this->upload->data();
                $data_upload =  [
                    'foto_profile' => $upload['file_name']
                ];
                $temp = $this->master->selectDataId('user_absensi', ['username' => $username]);
                $string = ['user_absensi' => $data_upload];
                $log = simpan_log("Change Photo Profile", json_encode($string));
                $res = $this->master->updateData('user_absensi', $data_upload, ['username' => $username], $log);
                if (($res > 0) && ($temp['foto_profile'] != "")) {
                    if (file_exists(FCPATH . 'uploads/users/' . $temp['foto_profile'])) {
                        unlink(FCPATH . 'uploads/users/' . $temp['foto_profile']);
                    }
                }
                $data = ['status' => TRUE, 'notif' => $res, 'file_name' => $upload['file_name']];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            redirect(site_url('blocked'));
        }
    }

    function formChangePassword()
    {
        if (($this->role == "admin") or ($this->role = "skpd")) {
            $username = $this->session->userdata('username');
            $data['user'] = $this->user->getUserId($username);
            $this->load->view('user/form_password', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function changePassword()
    {
        if (($this->role == "admin") or ($this->role = "skpd")) {
            $this->form_validation->set_rules('password', 'New password', 'required|trim');
            $this->form_validation->set_rules('password_confirm', 'Password confirm', 'required|trim|matches[password]');
            $this->form_validation->set_message('required', '%s tidak boleh kosong');
            $this->form_validation->set_message('matches', '%s tidak sama');
            if ($this->form_validation->run() == false) {
                $errors = [
                    'password' => form_error('password'),
                    'password_confirm' => form_error('password_confirm')
                ];
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $post = $this->input->post(null, TRUE);
                $username = htmlspecialchars($post['username']);
                $password   = htmlspecialchars($post['password']);
                $data_password =  [
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ];

                $string = ['user_absensi' => $data_password];
                $log = simpan_log("Change Password", json_encode($string));
                $res = $this->master->updateData('user_absensi', $data_password, ['username' => $username], $log);
                $data = ['status' => TRUE, 'notif' => $res];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            redirect(site_url('blocked'));
        }
    }
}
