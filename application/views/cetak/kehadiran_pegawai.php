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
            <i>Tanggal <?=date_to_indo($tgl_kehadiran)?> </i>
        </div>
    </div>
    <br>
    <br>
    <br>
    <hr>
    <div class='box-body'>
        <h3>DAFTAR PEGAWAI HADIR</h3>
        <table style="width: 100%;">
            <thead>
                <tr style="background-color: #1572EB; color: white;">
                    <th width="5px">NO</th>
                    <th>NAMA</th>
                    <th>NIP</th>
                    <th class="text-center">JABATAN</th>
                    <th class="text-center">JAM MASUK</th>
                    <th class="text-center">JAM KELUAR</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $no = 0;
                foreach ($kehadiran as $r) :
                    $no++;
                    $namaPegawai = format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']);
                    if ($r['lambat_datang'] <= 0) {
                        $jamMasuk = "<center>$r[jam_masuk]</center>";
                    } else {
                        $lambatDatang = konversi_detik($r['lambat_datang']);
                        $lambat_datang = "<br>(Terlambat => $lambatDatang)";
                        $jamMasuk = "<center style='color:#e74c3c;'>$r[jam_masuk] $lambat_datang</center>";
                    }
    
                    if ($r['jam_pulang'] == '00:00:00') {
                        $jamPulang = "<center>-</center>";
                    } else {
                        if ($r['cepat_pulang'] <= 0) {
                            $jamPulang = "<center>$r[jam_pulang]</center>";
                        } else {
                            $cepatPulang = konversi_detik($r['cepat_pulang']);
                            $cepat_pulang = "<br>(Cepat Pulang => $cepatPulang)";
                            $jamPulang = "<center style='color:#e74c3c;'>$r[jam_pulang] $cepat_pulang</center>";
                        }
                    }
            
                ?>
                    <tr>
                        <td style="text-align: center;"><?= $no; ?></td>
                        <td style="text-align: left;"><?= $namaPegawai ?></td>
                        <td style="text-align: center;"><?= $r['nip'] ?></td>
                        <td style="text-align: left;"><?= jabatan($r['plt'], $r['nama_jabatan']) ?></td>
                        <td style="text-align: center;"><?= $jamMasuk ?></td>
                        <td style="text-align: center;"><?= $jamPulang ?></td>
                    </tr>
                <?php  endforeach; ?>
            </tbody>
        </table>
        <br><br>
        <?php $modulus=$no % 25; if($modulus>=20) { ?>
            <div class="pagebreak"> </div>
        <?php } ?>
        <h3>DAFTAR PEGAWAI ABSEN (TUGAS LUAR/IZIN/SAKIT/TANPA KETERANGAN)</h3>
        <table style="width: 100%;">
            <thead>
                <tr style="background-color: #1572EB; color: white;">
                    <th width="5px">NO</th>
                    <th>NAMA</th>
                    <th>NIP</th>
                    <th class="text-center">JABATAN</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $no = 0;
                foreach ($tidak_hadir as $r) :
                    $no++;
                    $namaPegawai = format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']);
                ?>
                    <tr>
                        <td style="text-align: center;"><?= $no; ?></td>
                        <td style="text-align: left;"><?= $namaPegawai ?></td>
                        <td style="text-align: center;"><?= $r['nip'] ?></td>
                        <td style="text-align: left;"><?= jabatan($r['plt'], $r['nama_jabatan']) ?></td>
                        <td style="text-align: center;"><?=status_hadir_white($r['status_hadir'])?></td>
                        <td style="text-align: center;"><?=$r['keterangan_absensi']?></td>
                    </tr>
                <?php  endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>