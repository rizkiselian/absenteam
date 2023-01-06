<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Integrasi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableLog = 'log_aktivitas_pegawai';
    }

    public function cek_pegawai($nip)
    {
        $this->db->select('id_pegawai, nip, password');
        $this->db->from('pegawai');
        $this->db->where('nip', $nip);
        return $this->db->get();
    }

    public function get_detail_pegawai($id_pegawai)
    {
        $this->db->select('a.id_pegawai, a.gelar_depan, a.nama_pegawai, a.gelar_belakang, a.plt, a.jabatan_honor, a.status_kerja, b.nama_jabatan, c.nama_skpd');
        $this->db->from('pegawai as a');
        $this->db->join('jabatan as b', 'a.id_jabatan = b.id_jabatan', 'LEFT');
        $this->db->join('skpd as c', 'a.id_skpd = c.id_skpd');
        $this->db->where('a.id_pegawai', $id_pegawai);
        return $this->db->get();
    }

    public function get_kehadiran_by_tanggal($id_pegawai, $tgl_kehadiran)
    {
        $this->db->select('a.*, b.gelar_depan, b.nama_pegawai, b.gelar_belakang, b.plt, b.jabatan_honor, b.status_kerja, c.nama_jabatan, d.nama_skpd, d.latitude, d.longitude, d.radius');
        $this->db->from('kehadiran as a');
        $this->db->join('pegawai as b', 'a.id_pegawai = b.id_pegawai');
        $this->db->join('jabatan as c', 'a.id_jabatan = c.id_jabatan', 'LEFT');
        $this->db->join('skpd as d', 'a.id_skpd=d.id_skpd');
        $this->db->where(['a.id_pegawai' => $id_pegawai, 'a.tgl_kehadiran' => $tgl_kehadiran]);
        return $this->db->get();
    }

    public function get_masuk($id_kehadiran)
    {
        $this->db->select('id_kehadiran, id_pegawai, status_hadir');
        $this->db->from('kehadiran');
        $this->db->where('id_kehadiran', $id_kehadiran);
        return $this->db->get();
    }

    public function get_pulang($id_kehadiran)
    {
        $this->db->select('id_kehadiran, id_pegawai, status_hadir, jam_pulang, foto_pulang');
        $this->db->from('kehadiran');
        $this->db->where('id_kehadiran', $id_kehadiran);
        return $this->db->get();
    }

    public function proses_absen($data_absen, $id_kehadiran)
    {
        $this->db->update('kehadiran', $data_absen, ['id_kehadiran' => $id_kehadiran]);
        return $this->db->affected_rows();
    }
}
