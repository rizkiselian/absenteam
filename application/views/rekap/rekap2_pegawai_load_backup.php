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
            <?php $no = 1; ?>
            <?php foreach ($result_pegawai as $r) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']); ?></td>
                    <td><?= $r['nip']; ?></td>
                    <td><?= $r['nama_pangkat'] . ' (' . $r['kode_pangkat'] . ')'; ?></td>
                    <td><?= jabatan($r['plt'], $r['nama_jabatan']); ?></td>
                    <?php
                    $idPegawai = $r['id_pegawai'];
                    $hadir = $this->master->cekCount('kehadiran', "id_pegawai = $idPegawai AND MONTH(tgl_kehadiran) = $bulan AND YEAR(tgl_kehadiran) = $tahun AND status_hadir='hadir'");
                    $izin = $this->master->cekCount('kehadiran', "id_pegawai = $idPegawai AND MONTH(tgl_kehadiran) = $bulan AND YEAR(tgl_kehadiran) = $tahun AND status_hadir='izin'");
                    $absen = $this->master->cekCount('kehadiran', "id_pegawai = $idPegawai AND MONTH(tgl_kehadiran) = $bulan AND YEAR(tgl_kehadiran) = $tahun AND status_hadir='absen'");
                    $sakit = $this->master->cekCount('kehadiran', "id_pegawai = $idPegawai AND MONTH(tgl_kehadiran) = $bulan AND YEAR(tgl_kehadiran) = $tahun AND status_hadir='sakit'");
                    $tl = $this->master->cekCount('kehadiran', "id_pegawai = $idPegawai AND MONTH(tgl_kehadiran) = $bulan AND YEAR(tgl_kehadiran) = $tahun AND status_hadir='tl'");
                    $cuti = $this->master->cekCount('kehadiran', "id_pegawai = $idPegawai AND MONTH(tgl_kehadiran) = $bulan AND YEAR(tgl_kehadiran) = $tahun AND status_hadir='cuti'");
                    $jumlah = $this->master->cekCount('kehadiran', "id_pegawai = $idPegawai AND MONTH(tgl_kehadiran) = $bulan AND YEAR(tgl_kehadiran) = $tahun");
                    ?>
                    <td style="text-align: center;"><?= $hadir; ?></td>
                    <td style="text-align: center;"><?= $izin; ?></td>
                    <td style="text-align: center;"><?= $absen; ?></td>
                    <td style="text-align: center;"><?= $sakit; ?></td>
                    <td style="text-align: center;"><?= $tl; ?></td>
                    <td style="text-align: center;"><?= $cuti; ?></td>
                    <td style="text-align: center;"><?= $jumlah; ?></td>
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