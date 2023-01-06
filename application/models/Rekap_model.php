<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableLog = 'log_aktivitas_user';
        $this->simpeg = 'kepegawaian';
    }

    function getRekap1Pegawai($idSkpd, $bulan, $tahun)
    {
        $this->db->select("kehadiran.*, a.nip, a.gelar_depan, a.nama_pegawai, a.gelar_belakang, d.plt, b.nama_jabatan, c.kode_pangkat, c.nama_pangkat");
        $this->db->from('kehadiran');
        $this->db->join($this->simpeg . '.pegawai as a', 'kehadiran.id_pegawai=a.id_pegawai');
        $this->db->join($this->simpeg . '.jabatan as b', 'kehadiran.id_jabatan = b.id_jabatan', 'LEFT');
        $this->db->join($this->simpeg . '.pangkat as c', 'a.id_pangkat = c.id_pangkat', 'LEFT');
        $this->db->join($this->simpeg . '.pegawai_posisi as d', 'a.id_pegawai = d.id_pegawai');
        $this->db->where("MONTH(kehadiran.tgl_kehadiran) = $bulan AND YEAR(kehadiran.tgl_kehadiran) = $tahun");
        $this->db->where(['kehadiran.id_skpd' => $idSkpd, 'a.status_pegawai' => 'pegawai', 'a.status_kerja !=' => 'honor']);
        $this->db->group_by("kehadiran.id_pegawai");
        $this->db->order_by('a.nama_pegawai ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    // ============================================= Tenaga Kontrak ============================================
    function getRekap1TenagaKontrak($idSkpd, $bulan, $tahun)
    {
        $this->db->select("kehadiran.*, a.gelar_depan, a.nama_pegawai, a.gelar_belakang, b.jabatan_honor");
        $this->db->from('kehadiran');
        $this->db->join($this->simpeg . '.pegawai as a', 'kehadiran.id_pegawai=a.id_pegawai');
        $this->db->join($this->simpeg . '.pegawai_posisi as b', 'a.id_pegawai = b.id_pegawai');
        $this->db->where("MONTH(kehadiran.tgl_kehadiran) = $bulan AND YEAR(kehadiran.tgl_kehadiran) = $tahun");
        $this->db->where(['kehadiran.id_skpd' => $idSkpd, 'a.status_pegawai' => 'pegawai', 'a.status_kerja' => 'honor']);
        $this->db->group_by("kehadiran.id_pegawai");
        $this->db->order_by('a.nama_pegawai ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
}
