<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tenaga_kontrak_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabel_log = 'log_aktivitas_user';
        $this->simpeg = 'kepegawaian';
    }

    function getTenagaKontrak($id_skpd)
    {
        $this->db->select('a.id_pegawai, a.gelar_depan, a.nama_pegawai, a.gelar_belakang, a.foto_profile, b.jabatan_honor, c.nama_skpd');
        $this->db->join($this->simpeg . '.pegawai_posisi as b', 'a.id_pegawai = b.id_pegawai');
        $this->db->join($this->simpeg . '.skpd as c', 'b.id_skpd = c.id_skpd');
        $this->db->where(['b.id_skpd' => $id_skpd, 'a.status_pegawai' => 'pegawai', 'a.status_kerja' => 'honor']);
        $this->db->order_by('nama_pegawai ASC');
        return $this->db->get($this->simpeg . '.pegawai as a')->result_array();
    }
}
