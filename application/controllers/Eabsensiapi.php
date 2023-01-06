<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Eabsensiapi extends REST_Controller
{
    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();
        $this->db2 = $this->load->database('kepegawaian', TRUE);
        $this->load->library('upload');
    }

    function index_get()
    {

        $absen['pesan'] = "Cari Apa Bang??";
        $this->response($absen, 200);
    }

    function index_post()
    {
        $username = htmlspecialchars($this->post('username', TRUE));
        $stabsen = htmlspecialchars($this->post('stabsen', TRUE));
        $longitude = htmlspecialchars($this->post('longitude', TRUE));
        $latitude = htmlspecialchars($this->post('latitude', TRUE));
        $keterangan = htmlspecialchars($this->post('keterangan', TRUE));
        $idskpd = htmlspecialchars($this->post('idskpd', TRUE));
        $hari = date('w');
        if ($hari == 5) {
            $this->db2->select("jammasuk2 as jammasuk, jampulang2 as jampulang, longitude, latitude,radius");
        } else {
            $this->db2->select("jammasuk, jampulang, longitude, latitude,radius");
        }
        $this->db2->where('id_skpd', $idskpd);
        $hsl_skpd = $this->db2->get('skpd')->row();
        // $hsl_skpd = $this->master->selectDataId('skpd', ['id_skpd' => $idskpd]);
        $waktu_sekarang = date('Y-m-d');
        $jam_sekarang = date('H:i:s');
        $user = $this->db2->get_where('pegawai', ['nip' => $username])->row_array();

        $this->db2->select('a.id_pegawai, b.id_jabatan');
        $this->db2->join('pegawai_posisi as b', 'a.id_pegawai = b.id_pegawai', 'LEFT');
        $this->db2->where(['a.id_pegawai' => $user['id_pegawai']]);
        $hsl_pegawai = $this->db2->get('pegawai as a')->row_array();

        $jam = date('Y-m-d H:i:s');
        if ($stabsen == "1"  || $stabsen == "3" || $stabsen == "5") {
            $sudah = $this->db->get_where('kehadiran', [
                'id_pegawai' => $user['id_pegawai'],
                'DATE(tgl_kehadiran)' => $waktu_sekarang,
                'jam_masuk IS NOT NULL' => null,
            ])->num_rows();


            if ($sudah > 0) {
                $status['status'] = 'Sudah Absen Masuk';
                $this->response($status, 200);
            } else {

                if ($stabsen == "1") {
                    $sts = 'hadir';
                } else if ($stabsen == "3") {
                    $sts = 'wfh';
                } else if ($stabsen == "5") {
                    $sts = 'tl';
                }
                if ($keterangan == 1) {
                    $keterangan = selisih($hsl_skpd->jammasuk, $jam_sekarang);
                }
                $data =  [
                    'id_pegawai' => $user['id_pegawai'],
                    'id_skpd' => $idskpd,
                    'id_jabatan' => $hsl_pegawai['id_jabatan'],
                    'tgl_kehadiran' => $waktu_sekarang,
                    'status_hadir' => $sts,
                    'jam_masuk' => $jam,
                    'lambat_datang' => $keterangan,
                    'longitude_masuk' => $longitude,
                    'latitude_masuk' => $latitude
                ];
                $config['upload_path'] = './uploads/foto_absensi/';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['encrypt_name'] = TRUE;
                $config['overwrite'] = FALSE;
                // $config['max_size'] = 3048;
                $this->upload->initialize($config);
                $new_image = "";
                if (!empty($_FILES['foto']['name'])) {
                    if ($this->upload->do_upload('foto')) {
                        $gbr = $this->upload->data();
                        $config['image_library'] = 'gd2';
                        $this->load->library('image_lib', $config);
                        $this->image_lib->resize();

                        $new_image = $gbr['file_name'];
                    } else {
                        $error = array('error' => $this->upload->display_errors());
                        $data['foto_masuk'] = $this->upload->display_errors();
                    }
                    $data['foto_masuk'] = $new_image;
                }

                // $res = $this->M_crud->tambah_data1('kehadiran', $data);
                $insert = $this->db->insert('kehadiran', $data);
                if ($insert) {
                    $data['status'] = "Oke";
                    $this->response($data, 200);
                } else {
                    $this->response(array('status' => 'fail', 502));
                }
            }
        } else if ($stabsen == "2" || $stabsen == "4" || $stabsen == "6") {
            $idpegawai = $user['id_pegawai'];
            $this->db2->join("pegawai_posisi", "pegawai_posisi.id_pegawai = $idpegawai");
            $jabatan = $this->db2->get('pegawai')->row();

            // Cek Jabataan Satpam Atau Tidak
            if ($jabatan->jabatan_honor == 'Satpam') {
            } else {
                // pegawai selain satpam
                $sudah = $this->db->get_where('kehadiran', [
                    'id_pegawai' => $user['id_pegawai'],
                    'DATE(tgl_kehadiran)' => $waktu_sekarang,
                    'jam_pulang !=' => '00:00:00',
                ])->num_rows();

                if ($sudah > 0) {
                    $status['status'] = 'Sudah Absen Pulang';
                    $this->response($status, 200);
                } else {
                    if ($keterangan == 1) {
                        $keterangan = selisih($jam_sekarang, $hsl_skpd->jampulang);
                    }
                    $data =  [
                        'jam_pulang' => $jam,
                        'cepat_pulang' => $keterangan,
                        'longitude_pulang' => $longitude,
                        'latitude_pulang' => $latitude
                    ];

                    $config['upload_path'] = './uploads/foto_absensi/';
                    $config['allowed_types'] = 'jpg|png|jpeg';
                    $config['encrypt_name'] = TRUE;
                    $config['overwrite'] = FALSE;
                    // $config['max_size'] = 3048;
                    $this->upload->initialize($config);
                    $new_image = "";
                    $id = "";
                    if (!empty($_FILES['foto']['name'])) {
                        if ($this->upload->do_upload('foto')) {
                            $gbr = $this->upload->data();
                            $config['image_library'] = 'gd2';
                            $this->load->library('image_lib', $config);
                            $this->image_lib->resize();

                            $new_image = $gbr['file_name'];
                        } else {
                            $error = array('error' => $this->upload->display_errors());
                            $data['foto_pulang'] = $this->upload->display_errors();
                        }
                        $data['foto_pulang'] = $new_image;
                    }
                    $getidhadir = $this->db->get_where('kehadiran', [
                        'id_pegawai' => $user['id_pegawai'],
                        'DATE(tgl_kehadiran)' => $waktu_sekarang
                    ])->row_array();

                    $id = $getidhadir['id_kehadiran'];
                    $this->db->where('id_kehadiran', $id);
                    $update = $this->db->update('kehadiran', $data);

                    // $res = $this->M_crud->tambah_data1('kehadiran', $data);
                    // $insert = $this->db->insert('kehadiran', $data);
                    if ($update) {
                        $data['status'] = "Oke";
                        $data['id_kehadiran'] =  $user['id_pegawai'];
                        $this->response($data, 200);
                    } else {
                        $this->response(array('status' => 'fail', 502));
                    }
                }
            }
        } else {
            $status['status'] = 'Salah Parameter';
            $this->response($status, 200);
        }
    }

    public function login_post()
    {
        $username = htmlspecialchars($this->post('username', TRUE));
        $password = htmlspecialchars($this->post('password', TRUE));
        $data = [];
        if (empty($username) || empty($password)) {
            $data['status'] = "Tidak Boleh Kosong";
            $this->response($data, 200);
        } else {
            $user = $this->db2->get_where('pegawai', ['nip' => $username])->row_array();
            if ($user) {
                if ($user['status_aktif'] == "aktif") {
                    if (password_verify($password, $user['password'])) {
                        $this->db2->join("pegawai_posisi", "pegawai_posisi.id_pegawai = " . $user['id_pegawai'] . "");
                        $this->db2->join("skpd", "pegawai_posisi.id_skpd = skpd.id_skpd");
                        $skpd = $this->db2->get('pegawai')->row();

                        $data['user'] = $user;
                        $data['id_skpd'] = $skpd->id_skpd;
                        $data['nama_skpd'] = $skpd->nama_skpd;
                        $data['nama_skpd'] = $skpd->nama_skpd;
                        $data['longitude'] = $skpd->longitude;
                        $data['latitude'] = $skpd->latitude;
                        $data['status_sabtu'] = $skpd->status_sabtu;
                        $data['status_kegiatan'] = $skpd->status_kegiatan;
                        $data['status'] = "Oke";
                        $this->response($data, 200);
                    } else {
                        $data['status'] = "Password Salah";
                        $this->response($data, 200);
                    }
                } else {
                    $data['status'] = "Username Tidak Aktif";
                    $this->response($data, 200);
                }
            } else {
                $data['status'] = "Username Salah";
                $this->response($data, 200);
            }
        }
    }

    function setting_get($id)
    {
        // cek index hari jum'at
        $hari = date('w');
        if ($hari == 5) {
            $this->db2->select("jammasuk2 as jammasuk, jampulang2 as jampulang, longitude, latitude,radius");
        } else {
            $this->db2->select("jammasuk, jampulang, longitude, latitude,radius");
        }
        $this->db2->where('id_skpd', $id);
        $setting = $this->db2->get('skpd')->result();
        // $setting['id'] = $id;
        $this->response($setting, 200);
    }

    function status_get()
    {
        $waktu_sekarang = date('Y-m-d');
        $username = $this->get('username');
        // $idskpd = $this->get('idskpd');
        $user = $this->db2->get_where('pegawai', ['nip' => $username])->row_array();

        $id = $user['id_pegawai'];
        $data = [];

        $this->db2->join("pegawai_posisi", "pegawai_posisi.id_pegawai = $id");
        $jabatan = $this->db2->get('pegawai')->row();

        // Cek Jabataan Satpan Atau Tidak
        if ($jabatan->jabatan_honor == 'Satpam') {
        } else {

            $this->db->where(['id_pegawai' => $id, 'DATE(tgl_kehadiran)' => $waktu_sekarang]);
            $absen = $this->db->get('kehadiran')->result();
            $data['absen'] = $absen;
            $this->response($data, 200);
        }
    }


    function statustl_post()
    {
        $username = htmlspecialchars($this->post('username', TRUE));
        $waktu_sekarang = date('Y-m-d');
        $user = $this->db2->get_where('pegawai', ['nip' => $username])->row_array();
        $data = $this->db->query('SELECT * FROM tugas_luar 
            WHERE DATE(NOW()) >= DATE(tgl_awal_tugasluar) 
            AND DATE(NOW()) <= DATE(tgl_akhir_tugasluar) AND id_pegawai =' . $user['id_pegawai'] . '')->result();
        if ($data) {
            $res['status'] = 'Anda TL';
            $res['data'] = $data;
        } else {
            $res['status'] = 'Anda Tidak TL';
        }

        // $data['sql'] = $this->db->last_query();
        $this->response($res, 200);
    }



    function statuswfh_post()
    {
        $username = htmlspecialchars($this->post('username', TRUE));
        $waktu_sekarang = date('Y-m-d');
        $user = $this->db2->get_where('pegawai', ['nip' => $username])->row_array();
        $data = $this->db->query('SELECT * FROM wfh 
            WHERE DATE(NOW()) >= DATE(tgl_awal_wfh) 
            AND DATE(NOW()) <= DATE(tgl_akhir_wfh) AND id_pegawai =' . $user['id_pegawai'] . '')->result();
        if ($data) {
            $res['status'] = 'Anda WFH';
            $res['data'] = $data;
        } else {
            $res['status'] = 'Anda Tidak WFH';
        }

        // $data['sql'] = $this->db->last_query();
        $this->response($res, 200);
    }

    function tugasluar_post()
    {
        $username = htmlspecialchars($this->post('username', TRUE));
        $tglawal = htmlspecialchars($this->post('tglawal', TRUE));
        $tglakhir = htmlspecialchars($this->post('tglakhir', TRUE));
        $keterangan = htmlspecialchars($this->post('keterangan', TRUE));

        $user = $this->db2->get_where('pegawai', ['nip' => $username])->row_array();
        $data =  [
            'id_pegawai' => $user['id_pegawai'],
            'tgl_awal_tugasluar' => $tglawal,
            'tgl_akhir_tugasluar' => $tglakhir,
            'keterangan' => $keterangan
        ];
        $config['upload_path'] = './uploads/berkas_absensi/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['encrypt_name'] = TRUE;
        $config['overwrite'] = FALSE;
        // $config['max_size'] = 3048;
        $this->upload->initialize($config);
        $new_image = "";

        if (!empty($_FILES['foto']['name'])) {
            if ($this->upload->do_upload('foto')) {
                $gbr = $this->upload->data();
                $config['image_library'] = 'gd2';
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                $new_image = $gbr['file_name'];
            } else {
                $error = array('error' => $this->upload->display_errors());
                $data['foto'] = $this->upload->display_errors();
            }
            $data['foto'] = $new_image;
        }

        $insert = $this->db->insert('tugas_luar', $data);

        if ($insert) {
            $data['status'] = "Oke";
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function wfh_post()
    {
        $this->response(array('status' => 'fail', 502));

        $username = htmlspecialchars($this->post('username', TRUE));
        $tglawal = htmlspecialchars($this->post('tglawal', TRUE));
        $tglakhir = htmlspecialchars($this->post('tglakhir', TRUE));
        $keterangan = htmlspecialchars($this->post('keterangan', TRUE));

        $user = $this->db2->get_where('pegawai', ['nip' => $username])->row_array();
        $data =  [
            'id_pegawai' => $user['id_pegawai'],
            'tgl_awal_wfh' => $tglawal,
            'tgl_akhir_wfh' => $tglakhir,
            'keterangan' => $keterangan
        ];
        $config['upload_path'] = './uploads/berkas_absensi/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['encrypt_name'] = TRUE;
        $config['overwrite'] = FALSE;
        // $config['max_size'] = 3048;
        $this->upload->initialize($config);
        $new_image = "";

        if (!empty($_FILES['foto']['name'])) {
            if ($this->upload->do_upload('foto')) {
                $gbr = $this->upload->data();
                $config['image_library'] = 'gd2';
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                $new_image = $gbr['file_name'];
            } else {
                $error = array('error' => $this->upload->display_errors());
                $data['foto'] = $this->upload->display_errors();
            }
            $data['foto'] = $new_image;
        }

        $insert = $this->db->insert('wfh', $data);

        if ($insert) {
            $data['status'] = "Oke";
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function gantipass_put()
    {
        $username = htmlspecialchars($this->put('username', TRUE));
        $password = htmlspecialchars($this->put('password', TRUE));
        $user = $this->db2->get_where('pegawai', ['nip' => $username])->row_array();

        $data = array(
            'password' => password_hash($password, PASSWORD_DEFAULT),
        );
        $this->db2->where('id_pegawai', $user['id_pegawai']);
        $update = $this->db2->update('pegawai', $data);
        if ($update) {
            $data['status'] = 'Oke';
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
