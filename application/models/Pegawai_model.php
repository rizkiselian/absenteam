<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabelLog = 'log_aktivitas_user';
        $this->simpeg = 'kepegawaian';
    }

    function getPegawai($id_skpd)
    {
        $this->db->select('a.id_pegawai, a.nip, a.gelar_depan, a.nama_pegawai, a.gelar_belakang, a.status_shift, b.plt, c.nama_jabatan, d.kode_pangkat');
        $this->db->join($this->simpeg . '.pegawai_posisi as b', 'a.id_pegawai = b.id_pegawai', 'LEFT');
        $this->db->join($this->simpeg . '.jabatan as c', 'b.id_jabatan = c.id_jabatan', 'LEFT');
        $this->db->join($this->simpeg . '.pangkat as d', 'a.id_pangkat = d.id_pangkat', 'LEFT');
        $this->db->join($this->simpeg . '.skpd as e', 'b.id_skpd = e.id_skpd');
        $this->db->where(['b.id_skpd' => $id_skpd, 'a.status_pegawai' => 'pegawai', 'a.status_kerja' => 'pns']);
        $this->db->order_by('a.nama_pegawai ASC');
        return $this->db->get($this->simpeg . '.pegawai as a')->result_array();
    }

    function getPegawaibyid($idPegawai)
    {
        $this->db->select('a.id_pegawai, a.nip, a.gelar_depan, a.nama_pegawai, a.gelar_belakang, b.plt, c.nama_jabatan, d.kode_pangkat, b.id_jabatan, e.id_skpd');
        $this->db->join($this->simpeg . '.pegawai_posisi as b', 'a.id_pegawai = b.id_pegawai', 'LEFT');
        $this->db->join($this->simpeg . '.jabatan as c', 'b.id_jabatan = c.id_jabatan', 'LEFT');
        $this->db->join($this->simpeg . '.pangkat as d', 'a.id_pangkat = d.id_pangkat', 'LEFT');
        $this->db->join($this->simpeg . '.skpd as e', 'b.id_skpd = e.id_skpd');
        $this->db->where(['a.id_pegawai' => $idPegawai]);
        $this->db->order_by('a.nama_pegawai ASC');
        return $this->db->get($this->simpeg . '.pegawai as a')->row_array();
    }

}
