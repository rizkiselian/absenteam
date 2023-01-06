<div class="table-responsive">
    <table id="tabel-data" class="table-rekap" style="width: 100%;">
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
            $no = 1;
            foreach ($result_pegawai as $r) :
                $idPegawai = $r['id_pegawai'];
                $this->db->select('id_pegawai, status_hadir, lambat_datang, cepat_pulang');
                $this->db->from('kehadiran');
                $this->db->where("id_pegawai = $idPegawai AND MONTH(tgl_kehadiran) = $bulan AND YEAR(tgl_kehadiran) = $tahun");
                $result_status = $this->db->get()->result_array();

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

                foreach ($result_status as $st) {
                    if ($st['status_hadir'] == 'izin') {
                        $jumlah14++;
                    } elseif ($st['status_hadir'] == 'absen') {
                        $jumlah13++;
                    } elseif ($st['status_hadir'] == 'cuti') {
                        $jumlah12++;
                    } elseif ($st['status_hadir'] == 'sakit') {
                        $jumlah11++;
                    } elseif ($st['status_hadir'] == 'hadir') {
                        // score untuk cepat pulang
                        if ($st['cepat_pulang'] >= 10800) {
                            $jumlah10++;
                        } elseif ($st['cepat_pulang'] < 10800 && $st['cepat_pulang'] >= 7200) {
                            $jumlah9++;
                        } elseif ($st['cepat_pulang'] < 7200 && $st['cepat_pulang'] >= 3600) {
                            $jumlah8++;
                        } elseif ($st['cepat_pulang'] < 3600 && $st['cepat_pulang'] >= 900) {
                            $jumlah7++;
                        } elseif ($st['cepat_pulang'] < 900 && $st['cepat_pulang'] > 0) {
                            $jumlah6++;
                        }

                        // score untuk terlambat datang
                        if ($st['lambat_datang'] >= 10800) {
                            $jumlah5++;
                        } elseif ($st['lambat_datang'] < 10800 && $st['lambat_datang'] >= 7200) {
                            $jumlah4++;
                        } elseif ($st['lambat_datang'] < 7200 && $st['lambat_datang'] >= 3600) {
                            $jumlah3++;
                        } elseif ($st['lambat_datang'] < 3600 && $st['lambat_datang'] >= 900) {
                            $jumlah2++;
                        } elseif ($st['lambat_datang'] < 900 && $st['lambat_datang'] > 0) {
                            $jumlah1++;
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
                    <td><a href="<?= site_url('detail-kehadiran-pegawai/' . encrypt_url($r['id_pegawai'])); ?>"><?= format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']); ?></a></td>
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

<script>
    $(document).ready(function() {
        $('#tabel-data').DataTable({
            destroy: true,
            ordering: false,
            bAutoWidth: false,
        });
    });
</script>