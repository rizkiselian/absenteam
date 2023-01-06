<div class="table-responsive">
    <table id="tabel-data" class="table-default" style="width: 100%;">
        <thead>
            <tr style="background-color: #1572EB; color: white;">
                <th style="width: 5px;">NO</th>
                <th>TANGGAL</th>
                <th>STATUS</th>
                <th>JAM MASUK</th>
                <th>FOTO</th>
                <th>JAM KELUAR</th>
                <th>FOTO</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php
            foreach ($result_kehadiran as $r) :
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
                    <td style="text-align: center;"><?= status_hadir($r['status_hadir']); ?></td>
                    <?php if ($statusMasuk == 1) : ?>
                        <td style="text-align: center; color: #2ecc71;"><?= $jamMasuk; ?></td>
                        <td style="text-align: center; color: #2ecc71;"><?= $foto_m; ?></td>
                    <?php else : ?>
                        <td style="text-align: center; color: #e74c3c;"><?= $jamMasuk; ?></td>
                        <td style="text-align: center; color: #2ecc71;"><?= $foto_m; ?></td>
                    <?php endif; ?>
                    <?php if ($statusPulang == 1) : ?>
                        <td style="text-align: center; color: #2ecc71;"><?= $jamPulang; ?></td>
                        <td style="text-align: center; color: #2ecc71;"><?= $foto_k; ?></td>
                    <?php else : ?>
                        <td style="text-align: center; color: #e74c3c;"><?= $jamPulang; ?></td>
                        <td style="text-align: center; color: #2ecc71;"><?= $foto_k; ?></td>
                    <?php endif; ?>
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