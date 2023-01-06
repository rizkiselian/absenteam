<div class="table-responsive">
    <table id="tabel-data" class="table-default" style="width: 100%;">
        <thead>
            <tr style="background-color: #1572EB; color: white;">
                <th rowspan="2" style="width: 5px;">NO</th>
                <th rowspan="2">NAMA</th>
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
            $no = 1;
            foreach ($result_pegawai as $r) :
                $idPegawai = $r['id_pegawai'];
                $this->db->select('id_pegawai, status_hadir, tgl_kehadiran');
                $this->db->from('kehadiran');
                $this->db->where("id_pegawai = $idPegawai AND MONTH(tgl_kehadiran) = $bulan AND YEAR(tgl_kehadiran) = $tahun");
                $result_status = $this->db->get()->result_array();

                $hadir = 0;
                $izin = 0;
                $sakit = 0;
                $cuti = 0;
                $absen = 0;
                $tl = 0;
                foreach ($result_status as $st) {
                    if ($st['status_hadir'] == 'hadir') {
                        $hadir++;
                    } elseif ($st['status_hadir'] == 'izin') {
                        $izin++;
                    } elseif ($st['status_hadir'] == 'sakit') {
                        $sakit++;
                    } elseif ($st['status_hadir'] == 'cuti') {
                        $cuti++;
                    } elseif ($st['status_hadir'] == 'absen') {
                        $absen++;
                    } elseif ($st['status_hadir'] == 'tl') {
                        $tl++;
                    }
                }
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><a href="<?= site_url('detail-kehadiran-pegawai/' . encrypt_url($r['id_pegawai'])); ?>"><?= format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']); ?></a></td>
                    <td><?= $r['jabatan_honor']; ?></td>
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