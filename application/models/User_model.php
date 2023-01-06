<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableLog = 'log_aktivitas_user';
        $this->simpeg = 'kepegawaian';
    }

    function getUserSkpd()
    {
        $this->db->select('a.id_user, a.username, a.status_aktif, b.nip, b.gelar_depan, b.nama_pegawai, b.gelar_belakang, c.nama_skpd');
        $this->db->from('user_absensi as a');
        $this->db->join($this->simpeg . '.pegawai as b', 'a.id_pegawai = b.id_pegawai', 'LEFT');
        $this->db->join($this->simpeg . '.skpd as c', 'a.id_skpd = c.id_skpd', 'LEFT');
        $this->db->where('role_admin', 'skpd');
        $this->db->order_by('c.nama_skpd ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getUserId($username)
    {
        $this->db->select('a.id_pegawai, a.username, a.foto_profile, b.nip, b.gelar_depan, b.nama_pegawai, b.gelar_belakang, e.plt, b.status_kerja, e.jabatan_honor, c.nama_jabatan, d.nama_skpd');
        $this->db->from('user_absensi as a');
        $this->db->join($this->simpeg . '.pegawai as b', 'a.id_pegawai = b.id_pegawai', 'LEFT');
        $this->db->join($this->simpeg . '.pegawai_posisi as e', 'b.id_pegawai = e.id_pegawai');
        $this->db->join($this->simpeg . '.jabatan as c', 'e.id_jabatan = c.id_jabatan', 'LEFT');
        $this->db->join($this->simpeg . '.skpd as d', 'a.id_skpd = d.id_skpd', 'LEFT');
        $this->db->where('a.username', $username);
        $query = $this->db->get();
        return $query->row_array();
    }

    function insertUser($dataUser, $dataUserHistory, $log)
    {
        $this->db->trans_start();
        $this->db->insert('user_absensi', $dataUser);
        $this->db->insert('user_absensi_history', $dataUserHistory);
        $this->db->insert($this->tableLog, $log);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function updateUser($idUser, $dataUser, $dataUserHistory, $log)
    {
        $this->db->trans_start();
        $this->db->update('user_absensi', $dataUser, ['id_user' => $idUser]);
        if ($this->db->affected_rows() > 0) {
            $this->db->insert('user_absensi_history', $dataUserHistory);
            $this->db->insert($this->tableLog, $log);
            $this->db->trans_complete();
            return $this->db->trans_status();
        } else {
            return $this->db->affected_rows();
        }
    }
}
