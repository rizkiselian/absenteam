<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Progress Report Pengendalian Pembangunan Provinsi Sumatera Utara</title>
    <meta name="description" content="Progress Report Pengendalian Pembangunan Provinsi Sumatera Utara" />
    <meta name="keywords" content="aplikasi,kota,pematangsiantar,siantar,pengendalian,pembangunan,report" />
    <meta name="author" content="Progress Report Pengendalian Pembangunan Provinsi Sumatera Utara" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="all,follow">

    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('images/preloader.png'); ?>">
    <style type='text/css'>
        @page {
            size: 33cm 21.5cm;
        }

        body {
            font-family: Times;
            font-size: 10px;
        }

        div.box-header {
            text-align: center;
            padding-bottom: 20px;
        }

        div.box-body {
            font-size: 12px;
            clear: both;
            margin-top: 15px;
        }

        span.title {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            display: block;
        }

        span.alamat {
            margin: 0;
            font-size: 12px;
            display: block;
        }

        span.telepon {
            font-size: 12px;
            display: block;
        }

        .alignleft {
            float: left;
            width: 100px;
            text-align: left;
        }

        .aligncenter {
            float: left;
            width: 80%;
            text-align: center;
        }

        .alignright {
            float: right;
            width: 300px;
            text-align: right;
        }

        .aligntandatangan {
            padding-left: 75%;
            float: left;
            width: 250px;
            text-align: center;
        }

        table {
            font-size: 11px;
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px 8px;
        }

        hr {
            margin-top: 20px;
            margin-bottom: 26px;
        }
        @media print {
            .pagebreak { page-break-before: always; } /* page-break-after works, as well */
        }
    </style>

</head>

<body onload='window.print()' onfocus='window.close()'>
    <div class='box-header'>
        <div class='alignleft'>
            <img src='<?= base_url('images/logo-labusel.png'); ?>' width='50px'>
        </div>
        <div class='aligncenter'>
            <span class='title'>PEMERINTAH KABUPATEN LABUHANBATU SELATAN</span>
            <span class='title'>DATA KEHADIRAN PEGAWAI</span>
            <span class='title'><?= $skpd['nama_skpd'] ?></span>
            <i><?=fungsi_bulan($bulan, $tahun)?>  </i>
        </div>
    </div>
    <br>
    <br>
    <br>
    <hr>
    <div class='box-body'>
        <h3>REKAP KEHADIRAN PEGAWAI</h3>
        <table style="width: 100%;">
            <thead>
                <tr style="background-color: #1572EB; color: white;">
                    <th rowspan="2" style="width: 5px;">NO</th>
                    <th rowspan="2">NAMA</th>
                    <th rowspan="2">NIP</th>
                    <th rowspan="2">JABATAN</th>
                    <?php
                    $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                    ?>
                    <th colspan="<?= $jumlah_hari; ?>">TANGGAL</th>
                </tr>
                <tr>
                    <?php for ($i = 1; $i <= $jumlah_hari; $i++) : ?>
                        <th><?= $i; ?></th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $tanggal_now=date('Y-m-d');
                for ($i = 1; $i <= $jumlah_hari; $i++) {
                    $tgl = sprintf('%02s', $i);
                    $array_tanggal[] = "$tahun-$bulan-$tgl";
                }
                $no = 1;
                ?>
                <?php foreach ($result_pegawai as $r) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']); ?></td>
                        <td><?= $r['nip']; ?></td>
                        <td><?= jabatan($r['plt'], $r['nama_jabatan']); ?></td>
                        <?php
                        $idPegawai = $r['id_pegawai'];
                        $this->db->select('status_hadir, tgl_kehadiran');
                        $this->db->from('kehadiran');
                        $this->db->where('id_pegawai', $idPegawai);
                        $this->db->where_in('tgl_kehadiran', $array_tanggal);
                        $result_hasil = $this->db->get()->result_array();
                        $result_status = [];
                        $result_status[] = ["status_hadir" => "-", "tgl_kehadiran" => "0"];
                        foreach ($result_hasil as $hasil) {
                            $result_status[] = [
                                "status_hadir" => $hasil['status_hadir'],
                                "tgl_kehadiran" => $hasil['tgl_kehadiran']
                            ];
                        }

                        foreach ($array_tanggal as $tgl) {
                            $cari = array_search($tgl, array_column($result_status, 'tgl_kehadiran'));
                            if ($cari != "") {
                                $st_hadir = $result_status[$cari]['status_hadir'];
                            } else {
                                $st_hadir = "-";
                            }

                            if ($st_hadir == 'absen') {
                                $status_hadir = "";
                                $day = date('D', strtotime($tgl));
                                if (($day != 'Sat') and ($day != 'Sun')) {
                                    $cekHariLibur = $this->master->cekCount('hari_libur', ['tanggal_libur' => $tgl]);
                                    if ($cekHariLibur == 0) {$status_hadir = "<b>x</b>";}
                                }
                            } else if ($st_hadir == 'hadir') {
                                $status_hadir = "<b>✓</b>";
                            } else if ($st_hadir == 'cuti') {
                                $status_hadir = "<b>C</b>";
                            } else if ($st_hadir == 'izin') {
                                $status_hadir = "<b>I</b>";
                            } else if ($st_hadir == 'sakit') {
                                $status_hadir = "<b>S</b>";
                            } else if ($st_hadir == 'tl') {
                                $status_hadir = "<b>TL</b>";
                            } else {
                                if($tgl<=$tanggal_now)
                                {
                                    $status_hadir = "";
                                    $day = date('D', strtotime($tgl));
                                    if (($day != 'Sat') and ($day != 'Sun')) {
                                        $cekHariLibur = $this->master->cekCount('hari_libur', ['tanggal_libur' => $tgl]);
                                        if ($cekHariLibur == 0) {$status_hadir = "<b>x</b>";}
                                    }
                                }
                                else{$status_hadir = "";}
                            }
                            echo "<td>$status_hadir</td>";
                        }
                        ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>