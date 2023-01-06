<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cronjob extends CI_Controller
{
    function createpassword()
    {
        $this->db->select('id_pegawai, password');
        $this->db->from('pegawai');
        $get_pegawai = $this->db->get();
        $params = [];
        foreach ($get_pegawai->result_array() as $r) {
            $params[] = [
                "id_pegawai" => $r['id_pegawai'],
                "password" => password_hash('123456', PASSWORD_DEFAULT)
            ];
        }

        $cek = $this->db->update_batch('pegawai', $params, 'id_pegawai');
        echo $cek;
    }
    function import_image()
    {
        $data = ["foto_masuk" => "no-image.png", "foto_pulang" => "no-image.png"];
        $this->db->update("kehadiran", $data);
        echo $this->db->affected_rows();
    }
    function import_kehadiran()
    {
        $kehadiran = [];
        $tgl_sekarang = date('Y-m-d');
        $day = date('D', strtotime($tgl_sekarang));
        if (($day != 'Sat') and ($day != 'Sun')) {
            $cekHariLibur = $this->master->cekCount('hari_libur', ['tanggal_libur' => $tgl_sekarang]);
            if ($cekHariLibur == 0) {
                $cek_kehadiran = $this->db->get_where('kehadiran', ["tgl_kehadiran" => $tgl_sekarang]);
                if ($cek_kehadiran->num_rows() < 1000) {
                    // cek id pegawai yg tidak ada di tabel kehadiran pada tanggal sekarang
                    $where = $this->db->select('id_pegawai')->from('kehadiran')->where('tgl_kehadiran', $tgl_sekarang)->get_compiled_select();
                    $this->db->select('*');
                    $this->db->from('pegawai');
                    $this->db->where(["status_pegawai" => 'pegawai', "status_aktif" => 'aktif', "status_hk" => 'Y']);
                    $this->db->where("id_pegawai NOT IN ($where)");
                    // atau bisa gunakan perintah ini
                    //$this->db->where("id_pegawai IN (SELECT `id_pegawai` FROM `kehadiran` WHERE `tgl_kehadiran` = '$tgl_sekarang')");
                    $this->db->order_by('id_skpd ASC');
                    $query = $this->db->get();
                    foreach ($query->result_array() as $data) {
                        $kehadiran[] = [
                            "id_pegawai" => $data['id_pegawai'],
                            "tgl_kehadiran" => $tgl_sekarang,
                            "status_hadir" => "absen",
                            "jam_masuk" => "00:00:00",
                            "jam_pulang" => "00:00:00",
                            "lambat_datang" => 0,
                            "cepat_pulang" => 0,
                            "foto_masuk" => "no-image.png",
                            "foto_pulang" => "no-image.png",
                            "id_jabatan" => $data['id_jabatan'],
                            "id_skpd" => $data['id_skpd']
                        ];
                    }
                    //echo count($kehadiran);
                    $cek = $this->db->insert_batch('kehadiran', $kehadiran);
                    echo $cek . " Data pegawai berhasil diimport";
                } else {
                    echo "Data kehadiran sudah di import sebelumnya";
                }
            } else {
                echo "Hari Libur";
            }
        } else {
            echo "Sabtu dan Minggu";
        }
    }
}
