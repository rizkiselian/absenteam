<?php 
    if($pegawai['status_personal']==1)
    {
        $btn_aktif = "<button class='btn btn-success btn-sm' title='KOORDINAT PERSONAL'><i class='fa fa-map-marker'></i> Aktif</button>";
    }
    else
    {
        $btn_aktif = " <button class='btn btn-primary btn-border btn-sm' title='KOORDINAT PERSONAL'><i class='fa fa-map-marker'></i> Tidak Aktif</button>";
    }
?>
                    <table class="table table-border">
                        <tr>
                            <td width="25%">NAMA PEGAWAI</td>
                            <td>: <?= format_nama($pegawai['gelar_depan'], $pegawai['nama_pegawai'], $pegawai['gelar_belakang']); ?></td>
                        </tr>
                        <tr>
                            <td>LONGITUDE</td>
                            <td>: <?= $pegawai['longitude']; ?></td>
                        </tr>
                        <tr>
                            <td>LATITUDE</td>
                            <td>: <?= $pegawai['latitude']; ?></td>
                        </tr>
                        <tr>
                            <td>STATUS KOORDINAT PERSONAL</td>
                            <td>: <?=$btn_aktif?></td>
                        </tr>
                    </table>