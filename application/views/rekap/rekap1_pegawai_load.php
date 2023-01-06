<div class="table-responsive">
    <table id="tabel-data" class="table-rekap" style="width: 100%;">
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
                    <td><a href="<?= site_url('detail-kehadiran-pegawai/' . encrypt_url($r['id_pegawai'])); ?>"><?= format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']); ?></a></td>
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
                                if ($cekHariLibur == 0) {$status_hadir = "<i class='fa fa-times'></i>";}
                            }
                        } else if ($st_hadir == 'hadir') {
                            $status_hadir = "<i class='fa fa-check'></i>";
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
                                    if ($cekHariLibur == 0) {$status_hadir = "<i class='fa fa-times'></i>";}
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


<script>
    $(document).ready(function() {
        $('#tabel-data').DataTable({
            destroy: true,
            ordering: false,
            bAutoWidth: false,
        });
    });
</script>