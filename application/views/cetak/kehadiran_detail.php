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
            size: 21.5cm 33cm;
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
        <h3>DATA KEHADIRAN PEGAWAI <?= format_nama($result_pegawai['gelar_depan'], $result_pegawai['nama_pegawai'], $result_pegawai['gelar_belakang']); ?></h3>
        <table style="width: 100%;">
            <thead>
                <tr style="background-color: #1572EB; color: white;">
                    <th width="5px">NO</th>
                    <th>TANGGAL</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">JAM MASUK</th>
                    <th class="text-center">FOTO</th>
                    <th class="text-center">JAM KELUAR</th>
                    <th class="text-center">FOTO</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $no = 0;
                foreach ($result_kehadiran as $r) :
                    $no++;
                    $day = date('D', strtotime($r['tgl_kehadiran']));
                    $hari = format_hari($day);
                    $statusPulang = 0;
                    $path = cek_file('uploads/foto_absensi/', $r['foto_masuk']);
                    $foto_m = "<img src='" . $path . "' style='width: 50px'>";
                    $path = cek_file('uploads/foto_absensi/', $r['foto_pulang']);
                    $foto_k = "<img src='" . $path . "' style='width: 50px'>";
                    // cek jam masuk dan status lambat datang
                    if ($r['status_hadir'] == 'hadir') {
                        if ($r['jam_masuk'] == '00:00:00') {
                            $jamMasuk = "-";
                        } else {
                            if ($r['lambat_datang'] <= 0) {
                                $statusMasuk = 1;
                                $lambat_datang = "";
                            } else {
                                $lambatDatang = konversi_detik($r['lambat_datang']);
                                $statusMasuk = 0;
                                $lambat_datang = "<br>(Terlambat => $lambatDatang)";
                            }
                            $jamMasuk = $r['jam_masuk'] . $lambat_datang;
                        }

                        // cek jam pulang dan status cepat pulang
                        if ($r['jam_pulang'] == '00:00:00') {
                            $jamPulang = "-";
                        } else {
                            if ($r['cepat_pulang'] <= 0) {
                                $statusPulang = 1;
                                $cepat_pulang = "";
                            } else {
                                $cepatPulang = konversi_detik($r['cepat_pulang']);
                                $statusPulang = 0;
                                $cepat_pulang = "<br>(Cepat Pulang => $cepatPulang)";
                            }
                            $jamPulang = $r['jam_pulang'] . $cepat_pulang;
                        }
                    } else {
                        $statusMasuk = 0;
                        $statusPulang = 0;
                        $jamMasuk = "-";
                        $jamPulang = "-";
                    }
            
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $hari . ', ' . format_tanggal($r['tgl_kehadiran']); ?></td>
                        <td style="text-align: center;"><?= status_hadir_white($r['status_hadir']); ?></td>
                        <?php if ($statusMasuk == 1) : ?>
                            <td style="text-align: center;"><?= $jamMasuk; ?></td>
                            <td style="text-align: center; color: #2ecc71;"><?= $foto_m; ?></td>
                        <?php else : ?>
                            <td style="text-align: center; color: #e74c3c;"><?= $jamMasuk; ?></td>
                            <td style="text-align: center; color: #2ecc71;"><?= $foto_m; ?></td>
                        <?php endif; ?>
                        <?php if ($statusPulang == 1) : ?>
                            <td style="text-align: center;"><?= $jamPulang; ?></td>
                            <td style="text-align: center; color: #2ecc71;"><?= $foto_k; ?></td>
                        <?php else : ?>
                            <td style="text-align: center; color: #e74c3c;"><?= $jamPulang; ?></td>
                            <td style="text-align: center; color: #2ecc71;"><?= $foto_k; ?></td>
                        <?php endif; ?>
                    </tr>
                <?php  endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>