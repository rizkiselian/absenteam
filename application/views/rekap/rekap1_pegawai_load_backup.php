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
            <?php $no = 1; ?>
            <?php foreach ($result_pegawai as $r) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']); ?></td>
                    <td><?= $r['nip']; ?></td>
                    <td><?= jabatan($r['plt'], $r['nama_jabatan']); ?></td>
                    <?php
                    $idPegawai = $r['id_pegawai'];
                    for ($i = 1; $i <= $jumlah_hari; $i++) {
                        $tgl = sprintf('%02s', $i);
                        $tgl_kehadiran = "$tahun-$bulan-$tgl";
                        $this->db->select('tgl_kehadiran, status_hadir');
                        $this->db->from('kehadiran');
                        $this->db->where(['id_pegawai' => $idPegawai, 'tgl_kehadiran' => $tgl_kehadiran]);
                        $hadir = $this->db->get()->row_array();
                        if ($hadir['status_hadir'] == 'absen') {
                            $status_hadir = "<i class='fa fa-times'></i>";
                        } else if ($hadir['status_hadir'] == 'hadir') {
                            $status_hadir = "<i class='fa fa-check'></i>";
                        } else if ($hadir['status_hadir'] == 'cuti') {
                            $status_hadir = "C";
                        } else if ($hadir['status_hadir'] == 'izin') {
                            $status_hadir = "I";
                        } else if ($hadir['status_hadir'] == 'sakit') {
                            $status_hadir = "S";
                        } else if ($hadir['status_hadir'] == 'tl') {
                            $status_hadir = "TL";
                        } else {
                            $status_hadir = "";
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