<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Acara_kegiatan extends CI_Controller
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
                "menu" => "acara_kegiatan",
                "submenu" => null,
                "role" => $this->role
            ];
            $this->load->view('acara_kegiatan/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function loadData()
    {
        if (($this->role == "admin") or ($this->role == "skpd")) {
            $result = $this->master->selectData('acara_kegiatan', 'tanggal DESC, id_acara ASC');
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                if($r['status_rutin']==1)
                {
                    $ket_rutin="<button class='btn btn-success btn-xs' title='Status Rutin'>Rutin</button>";
                    $waktu="Setiap Hari ".nama_hari($r['tanggal']);
                }
                else
                {
                    $ket_rutin="<button class='btn btn-primary btn-border btn-xs' title='Status Tidak Rutin'>Tidak Rutin</button>";
                    $waktu=date_to_indo($r['tanggal']);
                }
                if($r['status_jam']==1)
                {
                    $ket_jam="<button class='btn btn-info btn-xs' title='Status Jam Kegiatan'>Jam Kegiatan</button>";
                    $waktu=$waktu."<br>Jam Masuk : ".$r['jammasuk']."<br>Jam Pulang : ".$r['jampulang'];
                }
                else
                {
                    $ket_jam="<button class='btn btn-success btn-xs' title='Status Jam Normal'>Jam Normal</button>";
                }
                if($r['status_aktif']==1)
                {
                    $ket_aktif="<button class='btn btn-success btn-xs' title='Status Aktif'>Aktif</button>";
                }
                else
                {
                    $ket_aktif="<button class='btn btn-danger btn-xs' title='Status Tidak Aktif'>Tidak Aktif</button>";
                }
                if($r['acuan_koor_masuk']==1)
                {
                    $ket_koor_masuk="<button class='btn btn-info btn-xs' title='Acuan Koordinat Kegiatan'>Kegiatan</button>";
                }
                else
                {
                    $ket_koor_masuk="<button class='btn btn-success btn-xs' title='Acuan Koordinat SKPD'>SKPD</button>";
                }
                if($r['acuan_koor_pulang']==1)
                {
                    $ket_koor_pulang="<button class='btn btn-info btn-xs' title='Acuan Koordinat Kegiatan'>Kegiatan</button>";
                }
                else
                {
                    $ket_koor_pulang="<button class='btn btn-success btn-xs' title='Acuan Koordinat SKPD'>SKPD</button>";
                }

                $edit = "<button id='tombol-ubah' data-id='" . encrypt_url($r['id_acara']) . "' data-toggle='modal' data-target='#modal-ubah' class='btn btn-icon btn-round btn-success btn-sm' title='UBAH'><i class='fa fa-edit'></i> </button>";
                $delete = "<button id='tombol-hapus' data-id='" . encrypt_url($r['id_acara']) . "' class='btn btn-icon btn-round btn-danger btn-sm' title='HAPUS'><i class='fa fa-trash'></i></button>";
                $row = [
                    'no' => $no,
                    'nama_kegiatan' => $r['nama_kegiatan'],
                    'waktu' => $waktu,
                    'koordinat' => "Latitude : ".$r['latitude']."<br>Longitude : ".$r['longitude']."<br>Radius : ".$r['radius']." meter",
                    'jam' => $ket_jam,
                    'rutin' => $ket_rutin,
                    'aktif' => $ket_aktif,
                    'koor_masuk' => $ket_koor_masuk,
                    'koor_pulang' => $ket_koor_pulang,
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
        $this->form_validation->set_rules('nama_kegiatan', 'Nama Kegiatan', 'required|trim');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
        $this->form_validation->set_rules('latitude', 'Latitude', 'required|trim');
        $this->form_validation->set_rules('longitude', 'Longitude', 'required|trim');
        $this->form_validation->set_rules('radius', 'Radius', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    function formAddData()
    {
        if ($this->role == "admin") {
            $this->load->view('acara_kegiatan/form_add');
        } else {
            redirect(site_url('blocked'));
        }
    }

    function formEditData()
    {
        if ($this->role == "admin") {
            $id = htmlspecialchars($this->input->post('id', TRUE));
            $idacara = decrypt_url($id);
            $data['acara'] = $this->master->selectDataId('acara_kegiatan', ['id_acara' => $idacara]);
            $this->load->view('acara_kegiatan/form_edit', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function addData()
    {
        if ($this->role == "admin") {
            $this->_ruleFormInput();
            $post = $this->input->post(null, TRUE);
            $tgl = htmlspecialchars($post['tanggal']);
            $tgl_input = format_tanggal_database($tgl);
            if ($this->form_validation->run() == false) {
                $errors = [
                    'nama_kegiatan' => form_error('nama_kegiatan'),
                    'tanggal' => form_error('tanggal'),
                    'latitude' => form_error('latitude'),
                    'longitude' => form_error('longitude'),
                    'radius' => form_error('radius')
                ];
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $dataInsert =  [
                    'nama_kegiatan' => htmlspecialchars($post['nama_kegiatan']),
                    'tanggal' => $tgl_input,
                    'latitude' => htmlspecialchars($post['latitude']),
                    'longitude' => htmlspecialchars($post['longitude']),
                    'radius' => htmlspecialchars($post['radius']),
                    'jammasuk' => htmlspecialchars($post['jammasuk']),
                    'jampulang' => htmlspecialchars($post['jampulang']),
                    'status_jam' => htmlspecialchars($post['status_jam']),
                    'status_rutin' => htmlspecialchars($post['status_rutin']),
                    'status_aktif' => htmlspecialchars($post['status_aktif']),
                    'acuan_koor_masuk' => htmlspecialchars($post['acuan_koor_masuk']),
                    'acuan_koor_pulang' => htmlspecialchars($post['acuan_koor_pulang'])
                ];
                $string = ['acara_kegiatan' => $dataInsert];
                $log = simpan_log("Insert acara_kegiatan", json_encode($string));
                $res = $this->master->insertData('acara_kegiatan', $dataInsert, $log);
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
            $id = htmlspecialchars($post['id_acara']);
            $id_acara = decrypt_url($id);
            $tgl = htmlspecialchars($post['tanggal']);
            $tanggal = format_tanggal_database($tgl);
            $temp = $this->master->selectDataId('acara_kegiatan', ['id_acara' => $id_acara]);
            if ($this->form_validation->run() == false) {
                $errors = [
                    'nama_kegiatan' => form_error('nama_kegiatan'),
                    'tanggal' => form_error('tanggal'),
                    'latitude' => form_error('latitude'),
                    'longitude' => form_error('longitude'),
                    'radius' => form_error('radius')
                ];
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $dataUpdate =  [
                    'nama_kegiatan' => htmlspecialchars($post['nama_kegiatan']),
                    'tanggal' => $tanggal,
                    'latitude' => htmlspecialchars($post['latitude']),
                    'longitude' => htmlspecialchars($post['longitude']),
                    'radius' => htmlspecialchars($post['radius']),
                    'jammasuk' => htmlspecialchars($post['jammasuk']),
                    'jampulang' => htmlspecialchars($post['jampulang']),
                    'status_jam' => htmlspecialchars($post['status_jam']),
                    'status_rutin' => htmlspecialchars($post['status_rutin']),
                    'status_aktif' => htmlspecialchars($post['status_aktif']),
                    'acuan_koor_masuk' => htmlspecialchars($post['acuan_koor_masuk']),
                    'acuan_koor_pulang' => htmlspecialchars($post['acuan_koor_pulang'])
                ];
                $string = ['acara_kegiatan' => ['data_lama' => $temp], ['data_baru' => $dataUpdate]];
                $log = simpan_log("Update acara_kegiatan", json_encode($string));
                $res = $this->master->updateData('acara_kegiatan', $dataUpdate, ['id_acara' => $id_acara], $log);
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
            $id_acara = decrypt_url($id);
            $temp = $this->master->selectDataId('acara_kegiatan', ['id_acara' => $id_acara]);
            $string = ['acara_kegiatan' => $temp];
            $log = simpan_log("Delete acara_kegiatan", json_encode($string));
            $res = $this->master->deleteData('acara_kegiatan', ['id_acara' => $id_acara], $log);
            $data = ['notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            redirect(site_url('blocked'));
        }
    }
}
