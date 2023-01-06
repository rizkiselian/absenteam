<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kehadiran_manual_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->simpeg = 'kepegawaian';
        $this->tableLog = 'log_aktivitas_user';
    }

    function getPegawai($idSkpd)
    {
        $this->db->select('a.id_pegawai, a.nip, a.gelar_depan, a.nama_pegawai, a.gelar_belakang, b.plt, c.nama_jabatan, d.kode_pangkat');
        $this->db->join($this->simpeg . '.pegawai_posisi as b', 'a.id_pegawai = b.id_pegawai', 'LEFT');
        $this->db->join($this->simpeg . '.jabatan as c', 'b.id_jabatan = c.id_jabatan', 'LEFT');
        $this->db->join($this->simpeg . '.pangkat as d', 'a.id_pangkat = d.id_pangkat', 'LEFT');
        $this->db->join($this->simpeg . '.skpd as e', 'b.id_skpd = e.id_skpd');
        $this->db->where(['b.id_skpd' => $idSkpd, 'a.status_pegawai' => 'pegawai', 'a.status_kerja' => 'pns', 'b.status_absen' => 'Y']);
        $this->db->order_by('a.nama_pegawai ASC');
        return $this->db->get($this->simpeg . '.pegawai as a')->result_array();
    }

    function getTenagaKontrak($idSkpd)
    {
        $this->db->select('a.id_pegawai, a.gelar_depan, a.nama_pegawai, a.gelar_belakang, a.foto_profile, b.jabatan_honor, c.nama_skpd');
        $this->db->join($this->simpeg . '.pegawai_posisi as b', 'a.id_pegawai = b.id_pegawai');
        $this->db->join($this->simpeg . '.skpd as c', 'b.id_skpd = c.id_skpd');
        $this->db->where(['b.id_skpd' => $idSkpd, 'a.status_pegawai' => 'pegawai', 'a.status_kerja' => 'honor', 'b.status_absen' => 'Y']);
        $this->db->order_by('nama_pegawai ASC');
        return $this->db->get($this->simpeg . '.pegawai as a')->result_array();
    }

    function getPegawaiHadir($tglHadir, $idSkpd, $pegawai)
    {
        $this->db->select('*');
        $this->db->from('kehadiran');
        $this->db->where(['id_skpd' => $idSkpd, 'tgl_kehadiran' => $tglHadir]);
        $this->db->where_in('id_pegawai', $pegawai);
        $query = $this->db->get();
        return $query->result_array();
    }

    function insertKehadiranManual($update, $manual, $detail, $log)
    {
        $this->db->trans_start();
        $this->db->update_batch('kehadiran', $update, 'id_kehadiran');
        $this->db->insert('kehadiran_manual', $manual);
        $this->db->insert_batch('kehadiran_manual_detail', $detail);
        $this->db->insert($this->tableLog, $log);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function deleteKehadiranManual($idManual, $kehadiranUpdate, $log)
    {
        $this->db->trans_start();
        $this->db->delete('kehadiran_manual', ['id_kehadiran_manual' => $idManual]);
        $this->db->delete('kehadiran_manual_detail', ['id_kehadiran_manual' => $idManual]);
        if ($kehadiranUpdate != []) {
            $this->db->update_batch('kehadiran', $kehadiranUpdate, 'id_kehadiran');
        }
        $this->db->insert($this->tableLog, $log);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
