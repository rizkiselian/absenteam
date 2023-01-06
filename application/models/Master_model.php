<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableLog = 'log_aktivitas_user';
    }

    function selectData($tableName, $order = null)
    {
        $this->db->select('*');
        $this->db->from($tableName);
        if ($order != null) {
            $this->db->order_by($order);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    function selectDataId($tableName, $where, $select = null)
    {
        ($select == null) ? $this->db->select('*') : $this->db->select($select);
        $this->db->from($tableName);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row_array();
    }

    function selectDataBy($tableName, $where, $order = null)
    {
        $this->db->select('*');
        $this->db->from($tableName);
        $this->db->where($where);
        if ($order != null) {
            $this->db->order_by($order);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    function selectIdLimit($tableName, $where, $order = null)
    {
        $this->db->select('*');
        $this->db->from($tableName);
        $this->db->where($where);
        if ($order != null) {
            $this->db->order_by($order);
        }
        $this->db->limit(1, 0);
        $query = $this->db->get();
        return $query->row_array();
    }

    function insertData($tableName, $data, $log)
    {
        $this->db->trans_start();
        $this->db->insert($tableName, $data);
        $this->db->insert($this->tableLog, $log);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function insertDataBatch($tableName, $data, $log)
    {
        $this->db->trans_start();
        $this->db->insert_batch($tableName, $data);
        $this->db->insert($this->tableLog, $log);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function updateData($tableName, $data, $where, $log)
    {
        $this->db->trans_start();
        $this->db->update($tableName, $data, $where);
        if ($this->db->affected_rows() > 0) {
            $this->db->insert($this->tableLog, $log);
            $this->db->trans_complete();
            return $this->db->trans_status();
        } else {
            return $this->db->affected_rows();
        }
    }

    function deleteData($tableName, $where, $log)
    {
        $this->db->trans_start();
        $this->db->delete($tableName, $where);
        if ($this->db->affected_rows() > 0) {
            $this->db->insert($this->tableLog, $log);
            $this->db->trans_complete();
            return $this->db->trans_status();
        } else {
            return $this->db->affected_rows();
        }
    }

    function cekCount($tableName, $where)
    {
        $this->db->select('*');
        $this->db->from($tableName);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->num_rows();
    }
}
