<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends CI_Controller
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
                "menu" => "menu_pegawai",
                "submenu" => null,
                "result_skpd" => $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC'),
                "role" => $this->role
            ];
            $this->load->view('pegawai/view', $data);
        } elseif ($this->role == "skpd") {
            $data = [
                "menu" => "menu_pegawai",
                "submenu" => null,
                "skpd" => $this->session->userdata('sess_skpd'),
                "role" => $this->role
            ];
            $this->load->view('pegawai/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }
    
    function loadPegawai($id)
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $idSkpd = decrypt_url($id);
            $result = $this->pegawai->getPegawai($idSkpd);
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $idPegawai = encrypt_url($r['id_pegawai']);
                $reset = "<button id='tombol-reset' data-id='" . encrypt_url($r['id_pegawai']) . "' data-toggle='modal' data-target='#modal-reset' class='btn btn-icon btn-round btn-warning btn-sm' title='RESET PASSWORD'><i class='fa fa-key'></i> </button>";
                $namaPegawai = "<a href='" . site_url('detail-kehadiran-pegawai/' . $idPegawai) . "'>" . format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']) . "</a>";
                
                if($r['status_shift']==1)
                {$status_shift = "<button id='tombol-shift' data-id='" . $idPegawai . "' data-nilai='0' data-deskripsi='Tidak Aktif' class='btn btn-info btn-sm' title='STATUS SHIFT AKTIF'><i class='fas fa-clipboard-list'></i> Aktif</button>";}
                else{$status_shift = "<button id='tombol-shift' data-id='" . $idPegawai . "' data-nilai='1' data-deskripsi='Aktif' class='btn btn-info btn-border btn-sm' title='STATUS SHIFT TIDAK AKTIF'><i class='fas fa-clipboard-list'></i> Tidak Aktif</button>";}
                
                if ($this->role == "admin")
                {
                    $koordinat_personal = "<a href='" . site_url('koordinat-personal/' . $idPegawai) . "' class='btn btn-round btn-success btn-sm' title='KOORDINAT PERSONAL'><i class='fas fa-map-marked'></i></a>";
                    $reset = $reset." ".$koordinat_personal;
                }
                $no++;
                $row = [
                    'no' => $no,
                    'nama' => $namaPegawai,
                    'nip' => $r['nip'],
                    'golongan' => $r['kode_pangkat'],
                    'jabatan' => jabatan($r['plt'], $r['nama_jabatan']),
                    'status_shift' => $status_shift,
                    'aksi' => $reset
                ];
                $data[] = $row;
            }
            $output['data'] = $data;
            echo json_encode($output);
        } else {
            redirect(site_url('blocked'));
        }
    }
    
    function loadPegawaiBySkpd()
    {
        if ($this->role == "admin") {
            $skpd = htmlspecialchars($this->input->post('unit_kerja', TRUE));
            $pegawai = htmlspecialchars($this->input->post('pegawai', TRUE));
            $idSkpd = decrypt_url($skpd);
            ($pegawai == null) ? $idPegawai = null : $idPegawai = decrypt_url($pegawai);
            if ($idSkpd != "") {
                $result = $this->pegawai->getPegawai($idSkpd);
                echo "<option value=''>PILIH PEGAWAI</option>";
                foreach ($result as $r) {
                    $nama_pegawai = format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']);
                    if ($r['id_pegawai'] == $idPegawai) {
                        echo "<option value='" . encrypt_url($r['id_pegawai']) . "' selected>" . $nama_pegawai . "</option>";
                    } else {
                        echo "<option value='" . encrypt_url($r['id_pegawai']) . "'>" . $nama_pegawai . "</option>";
                    }
                }
            }
        } else {
            redirect(site_url('blocked'));
        }
    }

    function formResetData()
    {
        if ($this->role == "admin") {
            $id = htmlspecialchars($this->input->post('user', TRUE));
            $id_pegawai = decrypt_url($id);
            $data['pegawai'] = $this->pegawai->getPegawaibyid($id_pegawai);
            $this->load->view('pegawai/form_reset', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function resetData()
    {
        if ($this->role == "admin") {
            $post = $this->input->post(null, TRUE);
            $id = htmlspecialchars($post['id_pegawai']);
            $id_pegawai = decrypt_url($id);

            $dataUser = [
                'password' =>  password_hash('123456', PASSWORD_DEFAULT)
            ];
            $this->db->update($this->simpeg. '.pegawai', $dataUser, ['id_pegawai' => $id_pegawai]);
            $res = $this->db->affected_rows();
            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            redirect(site_url('blocked'));
        }
    }

    function koordinat($id)
    {
        if ($this->role == "admin") {
            $idPegawai = decrypt_url($id);
            $data = [
                "menu" => "menu_pegawai",
                "submenu" => null,
                "pegawai" => $this->master->selectDataId($this->simpeg. '.pegawai', ['id_pegawai' => $idPegawai]),
                "role" => $this->role
            ];
            $this->load->view('pegawai/kor_personal', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function koordinat_update($nilai, $idPegawai)
    {
        if ($this->role == "admin") {
            $id_encrypt = encrypt_url($idPegawai);
            $dataUser = [
                'status_personal' =>  $nilai
            ];
            $this->db->update($this->simpeg. '.pegawai', $dataUser, ['id_pegawai' => $idPegawai]);
            redirect(site_url('koordinat-personal/'.$id_encrypt));
        } else {
            redirect(site_url('blocked'));
        }
    }

    function koordinat_load()
    {
        if ($this->role == "admin") {
            $id_pegawai = htmlspecialchars($this->input->post('id_pegawai', TRUE));
            $data = [
                "menu" => "menu_pegawai",
                "submenu" => null,
                "pegawai" => $this->master->selectDataId($this->simpeg. '.pegawai', ['id_pegawai' => $id_pegawai]),
                "role" => $this->role
            ];
            $this->load->view('pegawai/load_kor_personal', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function form_ubah_koordinat_load()
    {
        if ($this->role == "admin") {
            $id_pegawai = htmlspecialchars($this->input->post('id_pegawai', TRUE));
            $data['pegawai'] = $this->master->selectDataId($this->simpeg. '.pegawai', ['id_pegawai' => $id_pegawai]);
            $this->load->view('pegawai/form_edit', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function ubah_koordinat_load()
    {
        if ($this->role == "admin") {
            $post = $this->input->post(null, TRUE);
            $id = htmlspecialchars($post['id_pegawai']);

            $dataUser = [
                'longitude' =>  htmlspecialchars($post['longitude']),
                'latitude' =>  htmlspecialchars($post['latitude']),
                'status_personal' =>  htmlspecialchars($post['status_personal'])
            ];
            $this->db->update($this->simpeg. '.pegawai', $dataUser, ['id_pegawai' => $id]);
            $res = $this->db->affected_rows();
            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            redirect(site_url('blocked'));
        }
    }

    function shift_update()
    {
        $id = htmlspecialchars($this->input->post('idPegawai', TRUE));
        $idPegawai = decrypt_url($id);
        $nilai = htmlspecialchars($this->input->post('nilai', TRUE));
        $dataUser = [
            'status_shift' =>  $nilai
        ];
        $res = $this->db->update($this->simpeg. '.pegawai', $dataUser, ['id_pegawai' => $idPegawai]);
        $data = ['status' => TRUE, 'notif' => $res];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
