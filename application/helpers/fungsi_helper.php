<?php
function simpan_log($aksi, $keterangan)
{
    $ci = get_instance();
    $tgl_sekarang = date('Y-m-d');
    $tgl_kode = date('y-m-d');
    $cek_kode = $ci->db->get_where('log_aktivitas_user', ['date(waktu_akses)' => $tgl_sekarang])->num_rows();
    if ($cek_kode > 0) {
        $ci->db->select('log_id');
        $ci->db->from('log_aktivitas_user');
        $ci->db->where('date(waktu_akses)', $tgl_sekarang);
        $ci->db->order_by("log_id DESC");
        $ci->db->limit(1, 0);
        $last_kode = $ci->db->get()->row_array();
        $no_urut = substr($last_kode['log_id'], 6, 4);
        $v_kode = (int)($no_urut);
        $id_log = $v_kode + 1;
    } else {
        $id_log = 1;
    }
    $kode_log = str_replace('-', '', $tgl_kode) . str_pad($id_log, 4, "0",  STR_PAD_LEFT);

    $browser = [
        'browser' => $ci->agent->browser(),
        'version' => $ci->agent->version(),
        'os' => $ci->agent->platform(),
        'ip' => $ci->input->ip_address()
    ];
    $string = [
        'log_id'    => $kode_log,
        'username'     => $ci->session->userdata('username'),
        'aktivitas'    => $aksi,
        'aktivitas_detail' => $keterangan,
        'browser'     => json_encode($browser),
        'waktu_akses' => date('Y-m-d H:i:s')
    ];
    return $string;
}

function cek_query($res, $notifikasi)
{
    $ci = get_instance();
    if ($res > 0) {
        return $ci->session->set_flashdata('flash', 'success-Berhasil-Data Berhasil Di' . $notifikasi);
    } else {
        return $ci->session->set_flashdata('flash', 'error-Gagal-Data Gagal Di' . $notifikasi);
    }
}

function format_nama($gelar_depan, $nama_pegawai, $gelar_belakang)
{
    if ($gelar_depan == '') {
        if ($gelar_belakang == '') {
            $nama = "$nama_pegawai";
        } else {
            $nama = "$nama_pegawai, $gelar_belakang";
        }
    } else {
        if ($gelar_belakang == '') {
            $nama = "$gelar_depan $nama_pegawai";
        } else {
            $nama = "$gelar_depan $nama_pegawai, $gelar_belakang";
        }
    }

    return $nama;
}

function format_angka($nilai)
{
    return number_format($nilai, 0, ',', '.');
}

function format_pecahan($nilai)
{
    return number_format($nilai, 2, ',', '.');
}

function jabatan($plt, $jabatan)
{
    if ($plt == 'Y') {
        return "Plt. $jabatan";
    } else {
        return $jabatan;
    }
}

function status_hadir($status)
{
    if ($status == 'absen') {
        return "<span class='text-white' style='background-color: #e74c3c; padding: 3px 10px;'>Absen</span>";
    } elseif ($status == 'izin') {
        return "<span class='text-white' style='background-color: #e056fd; padding: 3px 10px;'>Izin</span>";
    } elseif ($status == 'tl') {
        return "<span class='text-white' style='background-color: #1abc9c; padding: 3px 10px;'>Tugas Luar</span>";
    } elseif ($status == 'sakit') {
        return "<span class='text-white' style='background-color: #f1c40f; padding: 3px 10px;'>Sakit</span>";
    } elseif ($status == 'cuti') {
        return "<span class='text-white' style='background-color: #3498db; padding: 3px 10px;'>Cuti</span>";
    } else {
        return "<span class='text-white' style='background-color: #2ecc71; padding: 3px 10px;'>Hadir</span>";
    }
}

function status_hadir_white($status)
{
    if ($status == 'absen') {
        return "Absen";
    } elseif ($status == 'izin') {
        return "Izin";
    } elseif ($status == 'tl') {
        return "Tugas Luar";
    } elseif ($status == 'sakit') {
        return "Sakit";
    } elseif ($status == 'cuti') {
        return "Cuti";
    } else {
        return "Hadir";
    }
}

function date_to_indo($date)
{
    if($date=="00:00:00"){return "";}
    else
    {
        $BulanIndo = array(
            "Januari", "Februari", "Maret",
            "April", "Mei", "Juni",
            "Juli", "Agustus", "September",
            "Oktober", "November", "Desember"
        );
    
        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl   = substr($date, 8, 2);
    
        $result = $tgl . " " . $BulanIndo[(int)$bulan - 1] . " " . $tahun;
        return $result;
    }
}

function format_hari($day)
{
    if ($day == 'Mon') {
        return 'Senin';
    } elseif ($day == 'Tue') {
        return 'Selasa';
    } elseif ($day == 'Wed') {
        return 'Rabu';
    } elseif ($day == 'Thu') {
        return 'Kamis';
    } elseif ($day == 'Fri') {
        return "Jum'at";
    } elseif ($day == 'Sat') {
        return 'Sebtu';
    } elseif ($day == 'Sun') {
        return 'Minggu';
    }
}

function nama_hari($tanggal)
{
    $day = date('D', strtotime($tanggal));
    $dayList = array(
    	'Sun' => 'Minggu',
    	'Mon' => 'Senin',
    	'Tue' => 'Selasa',
    	'Wed' => 'Rabu',
    	'Thu' => 'Kamis',
    	'Fri' => 'Jumat',
    	'Sat' => 'Sabtu'
    );
    return $dayList[$day];
}

function format_tanggal($tanggal)
{
    return date('d-m-Y', strtotime($tanggal));
}

function format_tanggal_database($tanggal)
{
    return date('Y-m-d', strtotime($tanggal));
}

function konversi_detik($detik)
{
    //$dtk = $detik % 60;
    $mnt = floor(($detik % 3600) / 60);
    $jam = floor(($detik % 86400) / 3600);
    if ($jam == 0) {
        return abs($mnt)." Menit";
    } else {
        return abs($jam)." Jam ".abs($mnt)." Menit";
    }
}

function selisih($jam_1, $jam_2)
{
    list($h, $m, $s) = explode(":", $jam_1);
    $dtAwal = mktime($h, $m, $s, "1", "1", "1");
    list($h, $m, $s) = explode(":", $jam_2);
    $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
    $dtSelisih = $dtAkhir - $dtAwal;
    return $dtSelisih;
}

function cek_file($path, $file)
{
    if (($file == "") or (!file_exists(FCPATH . $path . $file))) {
        return base_url("uploads/no-image.png");
    } else {
        return base_url($path . $file);
    }
}

function nama_bulan($tanggal)
    {
        $bulan = array (1 =>   'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                );
        $split = explode('-', $tanggal);
        return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
    }


function fungsi_bulan($bulan, $tahun)
{
    $BulanIndo = array(
        "Januari", "Februari", "Maret",
        "April", "Mei", "Juni",
        "Juli", "Agustus", "September",
        "Oktober", "November", "Desember"
    );
    
    $result = $BulanIndo[(int)$bulan - 1] . " " . $tahun;
    return $result;
}

function get_datapersonal($id){
    $ci = get_instance();
    $data= $ci->db2
    ->where(['id_pegawai' => $id])
    ->get('pegawai')->row_array();
    return $data;
}

function get_koordpersonal($id)
{
    $ci = get_instance();
    $data = $ci->db2
        ->select("skpd.jammasuk, skpd.jampulang,pegawai.longitude, pegawai.latitude,skpd.radius, 'Lokasi Khusus Peorangan' as keterangan")
        ->where(['pegawai.id_pegawai' => $id])
        ->join("pegawai_posisi", "pegawai_posisi.id_pegawai = pegawai.id_pegawai")
        ->join("skpd", "pegawai_posisi.id_skpd = skpd.id_skpd")
        ->get('pegawai')->row_array();
    return $data;
}

function get_koordshift($id)
{
    
    $ci = get_instance();
    $data = $ci->db2
        ->select("shift.jammasuk, shift.jampulang,skpd.longitude, skpd.latitude,skpd.radius, CONCAT('Bekerja ',shift.nama_shift) as keterangan")
        ->where(['pegawai.id_pegawai' => $id])
        ->join("shift", "shift.id_shift = pegawai.id_shift")
        ->join("pegawai_posisi", "pegawai_posisi.id_pegawai = pegawai.id_pegawai")
        ->join("skpd", "pegawai_posisi.id_skpd = skpd.id_skpd")
        ->get('pegawai')->row_array();
    return $data;
}

function get_statusskpd($id)
{
    $ci = get_instance();
    $data = $ci->db2
        ->where(['id_skpd' => $id])
        ->get('skpd')->row_array();
    return $data;
}

function get_acarakegiatan()
{
    $ci = get_instance();
    $hari = date('w');
    if($hari == 1){
        $data = $ci->db2
            ->select("jammasuk, jampulang,longitude, latitude,radius, nama_kegiatan as keterangan")
            ->where(['status_aktif' => 1, 'DAYOFWEEK(tanggal)-1' => $hari])
            ->order_by('id_acara','DESC')
            ->get('acara_kegiatan')->row_array();
            return $data;
    }else{
        $waktu_sekarang = date('Y-m-d');
        $data = $ci->db2
            ->select("jammasuk, jampulang, longitude, latitude,radius, nama_kegiatan as keterangan")
            ->where(['status_aktif' => 1, 'tanggal' => $waktu_sekarang])
            ->order_by('id_acara', 'DESC')
            ->get('acara_kegiatan')->row_array();
    
            return $data;
    }
}
