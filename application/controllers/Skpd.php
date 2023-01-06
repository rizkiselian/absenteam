<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Skpd extends CI_Controller
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
        if ($this->role == "admin") {
            $data = [
                "menu" => "menu_lokasi_skpd",
                "submenu" => null
            ];
            $this->load->view('lokasi_skpd/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }


    function loadData()
    {
        if ($this->role == "admin") {
            $result = $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC');
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                if($r['status_sabtu']==1){$jadwal3=$r['jammasuk3']."<br>".$r['jampulang3'];}
                else{$jadwal3="";}
                if($r['status_kegiatan']==1){$kegiatan="<button class='btn btn-info btn-xs' title='Status Mengikuti Kegiatan'>Mengikuti</button>";}
                else{$kegiatan="<button class='btn btn-primary btn-border btn-xs' title='Status Tidak Mengikuti Kegiatan'>Tidak</button>";}
                $edit = "<button id='tombol-ubah' data-id='" . encrypt_url($r['id_skpd']) . "' data-toggle='modal' data-target='#modal-ubah' class='btn btn-icon btn-round btn-success btn-sm' title='UBAH'><i class='fa fa-edit'></i> </button>";
                $row = [
                    'no' => $no,
                    'unit_kerja' => $r['nama_skpd'],
                    'latitude' => "Latitude : ".$r['latitude']."<br>Longitude : ".$r['longitude'],
                    'radius' => $r['radius'],
                    'jadwal1' => $r['jammasuk']."<br>".$r['jampulang'],
                    'jadwal2' => $r['jammasuk2']."<br>".$r['jampulang2'],
                    'jadwal3' => $jadwal3,
                    'kegiatan' => $kegiatan,
                    'aksi' => $edit
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
        $this->form_validation->set_rules('latitude', 'Latitude', 'required|trim');
        $this->form_validation->set_rules('longitude', 'Longitude', 'required|trim');
        $this->form_validation->set_rules('radius', 'Radius', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    function formEditData()
    {
        if ($this->role == "admin") {
            $id = htmlspecialchars($this->input->post('unit_kerja', TRUE));
            $idSkpd = decrypt_url($id);
            $data['skpd'] = $this->master->selectDataId($this->simpeg. '.skpd', ['id_skpd' => $idSkpd]);
            $this->load->view('lokasi_skpd/form_edit', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    function editData()
    {
        if ($this->role == "admin") {
            $this->_ruleFormInput();
            if ($this->form_validation->run() == false) {
                $errors = [
                    'latitude' => form_error('latitude'),
                    'longitude' => form_error('longitude'),
                    'radius' => form_error('radius')
                ];
                $data = ['status' => FALSE, 'errors' => $errors];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $post = $this->input->post(null, TRUE);
                $id = htmlspecialchars($post['id_unit_kerja']);
                $idSkpd = decrypt_url($id);
                $dataUpdate =  [
                    'latitude' => $post['latitude'],
                    'longitude' => htmlspecialchars($post['longitude']),
                    'radius' => htmlspecialchars($post['radius']),
                    'jammasuk' => htmlspecialchars($post['jammasuk']),
                    'jampulang' => htmlspecialchars($post['jampulang']),
                    'jammasuk2' => htmlspecialchars($post['jammasuk2']),
                    'jampulang2' => htmlspecialchars($post['jampulang2']),
                    'jammasuk3' => htmlspecialchars($post['jammasuk3']),
                    'jampulang3' => htmlspecialchars($post['jampulang3']),
                    'status_sabtu' => htmlspecialchars($post['status_sabtu']),
                    'status_kegiatan' => htmlspecialchars($post['status_kegiatan'])
                ];
                $temp = $this->master->selectDataId($this->simpeg. '.skpd', ['id_skpd' => $idSkpd]);
                $string = ['skpd' => ['data_lama' => $temp], ['data_baru' => $dataUpdate]];
                $log = simpan_log("Update Lokasi SKPD", json_encode($string));
                $res = $this->master->updateData($this->simpeg. '.skpd', $dataUpdate, ['id_skpd' => $idSkpd], $log);
                $data = ['status' => TRUE, 'notif' => $res];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            redirect(site_url('blocked'));
        }
    }
}
