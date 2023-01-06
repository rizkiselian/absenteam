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
        <h3>REKAP TPP PEGAWAI</h3>
        <table style="width: 100%;">
        <thead>
            <tr style="background-color: #1572EB; color: white;">
                <th rowspan="2" style="width: 5px;">NO</th>
                <th rowspan="2">NAMA</th>
                <th rowspan="2">JABATAN</th>
                <th colspan="2">Terlambat < 15 menit</th>
                <th colspan="2">Terlambat 15 menit s/d 1 jam</th>
                <th colspan="2">Terlambat 1 s/d 2 jam</th>
                <th colspan="2">Terlambat 2 s/d 3 jam</th>
                <th colspan="2">Terlambat > jam</th>
                <th colspan="2">Cepat Pulang < 15 menit</th>
                <th colspan="2">Cepat Pulang 15 menit s/d 1 jam</th>
                <th colspan="2">Cepat Pulang 1 s/d 2 jam</th>
                <th colspan="2">Cepat Pulang 2 s/d 3 jam</th>
                <th colspan="2">Cepat Pulang > 3 jam</th>
                <th colspan="2">Sakit</th>
                <th colspan="2">Cuti</th>
                <th colspan="2">Absen</th>
                <th colspan="2">Izin</th>
                <th rowspan="2">SKOR KEHADIRAN</th>
                <th rowspan="2">PERSENTASE KEHADIRAN</th>
                <th rowspan="2">TPP KEHADIRAN</th>
            </tr>
            <tr>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
                <th>JLH</th>
                <th>SKOR</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                $tanggal_now=date('Y-m-d');
                for ($i = 1; $i <= $jumlah_hari; $i++) {
                    $tgl = sprintf('%02s', $i);
                    $array_tanggal[] = "$tahun-$bulan-$tgl";
                }
            $no = 1;
            foreach ($result_pegawai as $r) :
                $jumlah1 = 0;
                $jumlah2 = 0;
                $jumlah3 = 0;
                $jumlah4 = 0;
                $jumlah5 = 0;
                $jumlah6 = 0;
                $jumlah7 = 0;
                $jumlah8 = 0;
                $jumlah9 = 0;
                $jumlah10 = 0;
                $jumlah11 = 0;
                $jumlah12 = 0;
                $jumlah13 = 0;
                $jumlah14 = 0;
                $tl = 0;

                $idPegawai = $r['id_pegawai'];
                        $this->db->select('status_hadir, tgl_kehadiran, lambat_datang, cepat_pulang');
                        $this->db->from('kehadiran');
                        $this->db->where('id_pegawai', $idPegawai);
                        $this->db->where_in('tgl_kehadiran', $array_tanggal);
                        $result_hasil = $this->db->get()->result_array();
                        $result_status = [];
                        $result_status[] = ["status_hadir" => "-", "tgl_kehadiran" => "0"];
                        foreach ($result_hasil as $hasil) {
                            $result_status[] = [
                                "status_hadir" => $hasil['status_hadir'],
                                "tgl_kehadiran" => $hasil['tgl_kehadiran'],
                                "lambat_datang" => $hasil['lambat_datang'],
                                "cepat_pulang" => $hasil['cepat_pulang']
                            ];
                        }

                        foreach ($array_tanggal as $tgl) {
                            $cari = array_search($tgl, array_column($result_status, 'tgl_kehadiran'));
                            if ($cari != "") {
                                $st_hadir = $result_status[$cari]['status_hadir'];
                                $st_datang = $result_status[$cari]['lambat_datang'];
                                $st_pulang = $result_status[$cari]['cepat_pulang'];
                            } else {
                                $st_hadir = "-";
                            }

                            if ($st_hadir == 'absen') {
                                $day = date('D', strtotime($tgl));
                                if (($day != 'Sat') and ($day != 'Sun')) {
                                    $cekHariLibur = $this->master->cekCount('hari_libur', ['tanggal_libur' => $tgl]);
                                    if ($cekHariLibur == 0) {$jumlah13++;}
                                }
                            } else if ($st_hadir == 'hadir') 
                            {
                                // score untuk cepat pulang
                                if ($st_pulang >= 10800) {
                                    $jumlah10++;
                                } elseif ($st_pulang < 10800 && $st_pulang >= 7200) {
                                    $jumlah9++;
                                } elseif ($st_pulang < 7200 && $st_pulang >= 3600) {
                                    $jumlah8++;
                                } elseif ($st_pulang < 3600 && $st_pulang >= 900) {
                                    $jumlah7++;
                                } elseif ($st_pulang < 900 && $st_pulang > 0) {
                                    $jumlah6++;
                                }

                                // score untuk terlambat datang
                                if ($st_datang >= 10800) {
                                    $jumlah5++;
                                } elseif ($st_datang < 10800 && $st_datang >= 7200) {
                                    $jumlah4++;
                                } elseif ($st_datang < 7200 && $st_datang >= 3600) {
                                    $jumlah3++;
                                } elseif ($st_datang < 3600 && $st_datang >= 900) {
                                    $jumlah2++;
                                } elseif ($st_datang < 900 && $st_datang > 0) {
                                    $jumlah1++;
                                }
                            } else if ($st_hadir == 'cuti') {
                                $jumlah12++;
                            } else if ($st_hadir == 'izin') {
                                $jumlah14++;
                            } else if ($st_hadir == 'sakit') {
                                $jumlah11++;
                            } else if ($st_hadir == 'tl') {
                                $tl++;
                            } else {
                                if($tgl<=$tanggal_now)
                                {
                                    $day = date('D', strtotime($tgl));
                                    if (($day != 'Sat') and ($day != 'Sun')) {
                                        $cekHariLibur = $this->master->cekCount('hari_libur', ['tanggal_libur' => $tgl]);
                                        if ($cekHariLibur == 0) {$jumlah13++;}
                                    }
                                }
                            }
                        }

                $skor1 = 100 - (0.25 * $jumlah1);
                $skor2 = 100 - (1 * $jumlah2);
                $skor3 = 100 - (2 * $jumlah3);
                $skor4 = 100 - (3 * $jumlah4);
                $skor5 = 100 - (4 * $jumlah5);
                $skor6 = 100 - (0.25 * $jumlah6);
                $skor7 = 100 - (1 * $jumlah7);
                $skor8 = 100 - (2 * $jumlah8);
                $skor9 = 100 - (3 * $jumlah9);
                $skor10 = 100 - (4 * $jumlah10);
                $skor11 = 100 - (1 * $jumlah11);
                $skor12 = 100 - (3 * $jumlah12);
                $skor13 = 100 - (6 * $jumlah13);
                $skor14 = 100 - (5 * $jumlah14);
                $skor_kehadiran = $skor1 + $skor2 + $skor3 + $skor4 + $skor5 + $skor6 + $skor7 + $skor8 + $skor9 + $skor10 + $skor11 + $skor12 + $skor13 + $skor14;

                $persentase_kehadiran = 100 - (1400 - $skor_kehadiran);
                if ($skor_kehadiran > 1300) {
                    $tpp = $persentase_kehadiran / 100 * $r['tpp'];
                    $tpp_kehadiran = round($tpp);
                } else {
                    $tpp_kehadiran = 0;
                }

            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']); ?></td>
                    <td><?= jabatan($r['plt'], $r['nama_jabatan']); ?></td>

                    <td style="text-align: center;"><?= $jumlah1; ?></td>
                    <td style="text-align: center;"><?= $skor1; ?></td>
                    <td style="text-align: center;"><?= $jumlah2; ?></td>
                    <td style="text-align: center;"><?= $skor2; ?></td>
                    <td style="text-align: center;"><?= $jumlah3; ?></td>
                    <td style="text-align: center;"><?= $skor3; ?></td>
                    <td style="text-align: center;"><?= $jumlah4; ?></td>
                    <td style="text-align: center;"><?= $skor4; ?></td>
                    <td style="text-align: center;"><?= $jumlah5; ?></td>
                    <td style="text-align: center;"><?= $skor5; ?></td>
                    <td style="text-align: center;"><?= $jumlah6; ?></td>
                    <td style="text-align: center;"><?= $skor6; ?></td>
                    <td style="text-align: center;"><?= $jumlah7; ?></td>
                    <td style="text-align: center;"><?= $skor7; ?></td>
                    <td style="text-align: center;"><?= $jumlah8; ?></td>
                    <td style="text-align: center;"><?= $skor8; ?></td>
                    <td style="text-align: center;"><?= $jumlah9; ?></td>
                    <td style="text-align: center;"><?= $skor9; ?></td>
                    <td style="text-align: center;"><?= $jumlah10; ?></td>
                    <td style="text-align: center;"><?= $skor10; ?></td>
                    <td style="text-align: center;"><?= $jumlah11; ?></td>
                    <td style="text-align: center;"><?= $skor11; ?></td>
                    <td style="text-align: center;"><?= $jumlah12; ?></td>
                    <td style="text-align: center;"><?= $skor12; ?></td>
                    <td style="text-align: center;"><?= $jumlah13; ?></td>
                    <td style="text-align: center;"><?= $skor13; ?></td>
                    <td style="text-align: center;"><?= $jumlah14; ?></td>
                    <td style="text-align: center;"><?= $skor14; ?></td>
                    <td style="text-align: center;"><b><?= $skor_kehadiran; ?></b></td>
                    <td style="text-align: center;"><b><?= $persentase_kehadiran; ?></b></td>
                    <td style="text-align: center;"><b><?= format_angka($tpp_kehadiran); ?></b></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
</body>

</html>