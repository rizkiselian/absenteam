<div class="table-responsive">
    <table id="tabel-data" class="table-default" style="width: 100%;">
        <thead>
            <tr style="background-color: #1572EB; color: white;">
                <th rowspan="2" style="width: 5px;">NO</th>
                <th rowspan="2">NAMA</th>
                <th rowspan="2">NIP</th>
                <th rowspan="2">PANGKAT(GOL)</th>
                <th rowspan="2">JABATAN</th>
                <th colspan="6">JUMLAH KEHADIRAN</th>
                <th rowspan="2">JUMLAH</th>
            </tr>
            <tr>
                <th>H</th>
                <th>I</th>
                <th>A</th>
                <th>S</th>
                <th>TL</th>
                <th>C</th>
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
                $hadir = 0;
                    $izin = 0;
                    $sakit = 0;
                    $cuti = 0;
                    $absen = 0;
                    $tl = 0;

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
                                $day = date('D', strtotime($tgl));
                                if (($day != 'Sat') and ($day != 'Sun')) {
                                    $cekHariLibur = $this->master->cekCount('hari_libur', ['tanggal_libur' => $tgl]);
                                    if ($cekHariLibur == 0) {$absen++;}
                                }
                            } else if ($st_hadir == 'hadir') {
                                $hadir++;
                            } else if ($st_hadir == 'cuti') {
                                $cuti++;
                            } else if ($st_hadir == 'izin') {
                                $izin++;
                            } else if ($st_hadir == 'sakit') {
                                $sakit++;
                            } else if ($st_hadir == 'tl') {
                                $tl++;
                            } else {
                                if($tgl<=$tanggal_now)
                                {
                                    $day = date('D', strtotime($tgl));
                                    if (($day != 'Sat') and ($day != 'Sun')) {
                                        $cekHariLibur = $this->master->cekCount('hari_libur', ['tanggal_libur' => $tgl]);
                                        if ($cekHariLibur == 0) {$absen++;}
                                    }
                                }
                            }
                        }
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><a href="<?= site_url('detail-kehadiran-pegawai/' . encrypt_url($r['id_pegawai'])); ?>"><?= format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']); ?></a></td>
                    <td><?= $r['nip']; ?></td>
                    <td><?= $r['nama_pangkat'] . ' (' . $r['kode_pangkat'] . ')'; ?></td>
                    <td><?= jabatan($r['plt'], $r['nama_jabatan']); ?></td>
                    <td style="text-align: center;"><?= $hadir; ?></td>
                    <td style="text-align: center;"><?= $izin; ?></td>
                    <td style="text-align: center;"><?= $absen; ?></td>
                    <td style="text-align: center;"><?= $sakit; ?></td>
                    <td style="text-align: center;"><?= $tl; ?></td>
                    <td style="text-align: center;"><?= $cuti; ?></td>
                    <td style="text-align: center;"><?= $hadir + $izin + $sakit + $cuti + $absen + $tl; ?></td>
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