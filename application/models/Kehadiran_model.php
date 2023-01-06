<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kehadiran_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableLog = 'log_aktivitas_user';
        $this->simpeg = 'kepegawaian';
    }

    function getPegawaiHadir($idSkpd, $tgl_kehadiran)
    {
        $this->db->select('kehadiran.*, a.nip, a.gelar_depan, a.nama_pegawai, a.gelar_belakang, c.plt, b.nama_jabatan');
        $this->db->from('kehadiran');
        $this->db->join($this->simpeg . '.pegawai as a', 'kehadiran.id_pegawai=a.id_pegawai');
        $this->db->join($this->simpeg . '.pegawai_posisi as c', 'a.id_pegawai = c.id_pegawai');
        $this->db->join($this->simpeg . '.jabatan as b', 'kehadiran.id_jabatan = b.id_jabatan', 'LEFT');
        $this->db->where(['kehadiran.tgl_kehadiran' => $tgl_kehadiran, 'kehadiran.status_hadir' => 'hadir', 'kehadiran.id_skpd' => $idSkpd, 'a.status_pegawai' => 'pegawai', 'a.status_kerja !=' => 'honor']);
        $this->db->order_by('a.nama_pegawai ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getPegawaiTidakHadir($idSkpd, $tgl_kehadiran)
    {
        $this->db->select('kehadiran.*, a.nip, a.gelar_depan, a.nama_pegawai, a.gelar_belakang, d.plt, b.nama_jabatan, c.keterangan_absensi, c.berkas_absensi');
        $this->db->from('kehadiran');
        $this->db->join($this->simpeg . '.pegawai as a', 'kehadiran.id_pegawai=a.id_pegawai');
        $this->db->join($this->simpeg . '.jabatan as b', 'kehadiran.id_jabatan = b.id_jabatan', 'LEFT');
        $this->db->join('keterangan_absensi as c', 'kehadiran.id_keterangan_absensi = c.id_keterangan_absensi', 'LEFT');
        $this->db->join($this->simpeg . '.pegawai_posisi as d', 'a.id_pegawai = d.id_pegawai');
        $this->db->where(['kehadiran.tgl_kehadiran' => $tgl_kehadiran, 'kehadiran.status_hadir !=' => 'hadir', 'kehadiran.id_skpd' => $idSkpd, 'a.status_pegawai' => 'pegawai', 'a.status_kerja !=' => 'honor']);
        $this->db->order_by('a.nama_pegawai ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    // ============================================= Tenaga Kontrak ======================================
    function getTenagaKontrakHadir($idSkpd, $tgl_kehadiran)
    {
        $this->db->select('kehadiran.*, a.gelar_depan, a.nama_pegawai, a.gelar_belakang, b.jabatan_honor');
        $this->db->from('kehadiran');
        $this->db->join($this->simpeg . '.pegawai as a', 'kehadiran.id_pegawai=a.id_pegawai');
        $this->db->join($this->simpeg . '.pegawai_posisi as b', 'a.id_pegawai = b.id_pegawai');
        $this->db->where(['kehadiran.tgl_kehadiran' => $tgl_kehadiran, 'kehadiran.status_hadir' => 'hadir', 'kehadiran.id_skpd' => $idSkpd, 'a.status_pegawai' => 'pegawai', 'a.status_kerja' => 'honor']);
        $this->db->order_by('a.nama_pegawai ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getTenagaKontrakTidakHadir($idSkpd, $tgl_kehadiran)
    {
        $this->db->select('kehadiran.*, a.gelar_depan, a.nama_pegawai, a.gelar_belakang, b.jabatan_honor, b.keterangan_absensi, b.berkas_absensi');
        $this->db->from('kehadiran');
        $this->db->join($this->simpeg . '.pegawai as a', 'kehadiran.id_pegawai=a.id_pegawai');
        $this->db->join($this->simpeg . '.pegawai_posisi as b', 'a.id_pegawai = b.id_pegawai');
        $this->db->join('keterangan_absensi as b', 'kehadiran.id_keterangan_absensi = b.id_keterangan_absensi', 'LEFT');
        $this->db->where(['kehadiran.tgl_kehadiran' => $tgl_kehadiran, 'kehadiran.status_hadir !=' => 'hadir', 'kehadiran.id_skpd' => $idSkpd, 'a.status_pegawai' => 'pegawai', 'a.status_kerja' => 'honor']);
        $this->db->order_by('a.nama_pegawai ASC');
        $query = $this->db->get();
        return $query->result_array();
    }


    // ============================== LOAD DETAIL KEHADIRAN ============================================
    function getDetailKehadiran($idPegawai, $bulan, $tahun)
    {
        $this->db->select('*');
        $this->db->from('kehadiran');
        $this->db->where("id_pegawai = $idPegawai AND MONTH(tgl_kehadiran) = $bulan AND YEAR(tgl_kehadiran) = $tahun");
        $this->db->order_by('tgl_kehadiran ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
}
