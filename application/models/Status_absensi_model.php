<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Status_absensi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableLog = 'log_aktivitas_user';
        $this->simpeg = 'kepegawaian';
    }

    function getStatusAbsensi($idSkpd, $statusIzin)
    {
        $this->db->select('a.*, b.id_pegawai, b.nip, b.gelar_depan, b.nama_pegawai, b.gelar_belakang, d.plt, b.status_kerja, d.jabatan_honor, c.nama_jabatan');
        $this->db->from('keterangan_absensi as a');
        $this->db->join($this->simpeg . '.pegawai as b', 'a.id_pegawai = b.id_pegawai', 'LEFT');
        $this->db->join($this->simpeg . '.pegawai_posisi as d', 'b.id_pegawai = d.id_pegawai');
        $this->db->join($this->simpeg . '.jabatan as c', 'd.id_jabatan = c.id_jabatan', 'LEFT');
        $this->db->where(['d.id_skpd' => $idSkpd, 'b.status_pegawai' => 'pegawai', 'a.status_hadir' => $statusIzin]);
        $this->db->order_by('a.id_keterangan_absensi DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getNamaPegawai($idSkpd)
    {
        $this->db->select('a.id_pegawai, a.gelar_depan, a.nama_pegawai, a.gelar_belakang');
        $this->db->from($this->simpeg . '.pegawai as a');
        $this->db->join($this->simpeg . '.pegawai_posisi as b', 'a.id_pegawai = b.id_pegawai');
        $this->db->where(['b.id_skpd' => $idSkpd, 'a.status_pegawai' => 'pegawai']);
        $this->db->order_by('a.status_kerja ASC, a.nama_pegawai ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getJabatanPegawai($idPegawai)
    {
        $this->db->select('a.id_pegawai, a.status_kerja, a.plt, a.jabatan_honor, b.nama_jabatan');
        $this->db->from('pegawai as a');
        $this->db->join('jabatan as b', 'a.id_jabatan = b.id_jabatan', 'LEFT');
        $this->db->where('a.id_pegawai', $idPegawai);
        $query = $this->db->get();
        return $query->row_array();
    }

    function insertStatusAbsensi($dataKeterangan, $dataKehadiranInsert, $dataKehadiranUpdate, $log)
    {
        $cekInsert = count($dataKehadiranInsert);
        $cekUpdate = count($dataKehadiranUpdate);
        $this->db->trans_start();
        $this->db->insert('keterangan_absensi', $dataKeterangan);
        if ($cekInsert > 0) {
            $this->db->insert_batch('kehadiran', $dataKehadiranInsert);
        }
        if ($cekUpdate > 0) {
            $this->db->update_batch('kehadiran', $dataKehadiranUpdate, 'id_kehadiran');
        }
        $this->db->insert($this->tableLog, $log);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function deleteStatusAbsensi($idCuti, $dataKehadiranUpdate, $dataKehadiranDelete, $log)
    {
        $cekUpdate = count($dataKehadiranUpdate);
        $cekDelete = count($dataKehadiranDelete);
        $this->db->trans_start();
        $this->db->delete('keterangan_absensi', ['id_keterangan_absensi' => $idCuti]);
        if ($cekUpdate > 0) {
            $this->db->update_batch('kehadiran', $dataKehadiranUpdate, 'id_kehadiran');
        }
        if ($cekDelete > 0) {
            $this->db->where_in('id_kehadiran', $dataKehadiranDelete);
            $this->db->delete('kehadiran');
        }
        $this->db->insert($this->tableLog, $log);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
