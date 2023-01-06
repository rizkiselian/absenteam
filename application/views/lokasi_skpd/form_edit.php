<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            UBAH LOKASI SKPD
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-ubah">
            <div class="form-group">
                <label for="unit_kerja">Unit Kerja</label>
                <input type="hidden" name="id_unit_kerja" id="id_unit_kerja" value="<?= encrypt_url($skpd['id_skpd']); ?>" class="form-control" autocomplete="off" readonly>
                <input type="text" name="unit_kerja" id="unit_kerja" value="<?= $skpd['nama_skpd']; ?>" class="form-control" autocomplete="off" readonly>
                <small class="text-danger unit_kerja"></small>
            </div>
            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="text" name="latitude" id="latitude" value="<?= $skpd['latitude']; ?>" class="form-control" autocomplete="off">
                <small class="text-danger latitude"></small>
            </div>
            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="text" name="longitude" id="longitude" value="<?= $skpd['longitude']; ?>" class="form-control" autocomplete="off">
                <small class="text-danger longitude"></small>
            </div>
            <div class="form-group">
                <label for="radius">Radius</label>
                <input type="text" name="radius" id="radius" value="<?= $skpd['radius']; ?>" class="form-control" autocomplete="off">
                <small class="text-danger radius"></small>
            </div>
            <div class="form-group">
                <label for="radius">SENIN - KAMIS</label>
                <div class="row">
                    <div class="col-sm-6">
                        JAM MASUK : <input type="text" name="jammasuk" id="jammasuk" value="<?= $skpd['jammasuk']; ?>" class="form-control" autocomplete="off">
                    </div>
                    <div class="col-sm-6">
                        JAM KELUAR : <input type="text" name="jampulang" id="jampulang" value="<?= $skpd['jampulang']; ?>" class="form-control" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="radius">JUM'AT</label>
                <div class="row">
                    <div class="col-sm-6">
                        JAM MASUK : <input type="text" name="jammasuk2" id="jammasuk2" value="<?= $skpd['jammasuk2']; ?>" class="form-control" autocomplete="off">
                    </div>
                    <div class="col-sm-6">
                        JAM KELUAR : <input type="text" name="jampulang2" id="jampulang2" value="<?= $skpd['jampulang2']; ?>" class="form-control" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Status Jam</legend>
                    <?php if($skpd['status_sabtu']=="1") { ?>
                        <label style="margin-right: 10px;" for="status_sabtu">
                            <input type="radio" name="status_sabtu" id="status_sabtu" value="1" checked> Aktif
                        </label>  
                        <label style="margin-right: 10px;" for="status_sabtu">
                            <input type="radio" name="status_sabtu" id="status_sabtu" value="0"> Tidak Aktif
                        </label>
                    <?php } else { ?>
                        <label style="margin-right: 10px;" for="status_sabtu">
                            <input type="radio" name="status_sabtu" id="status_sabtu" value="1"> Aktif
                        </label>  
                        <label style="margin-right: 10px;" for="status_sabtu">
                            <input type="radio" name="status_sabtu" id="status_sabtu" value="0" checked> Tidak Aktif
                        </label>
                    <?php } ?>
                </fieldset>
            </div>
            <div class="form-group">
                <label for="radius">SABTU</label>
                <div class="row">
                    <div class="col-sm-6">
                        JAM MASUK : <input type="text" name="jammasuk3" id="jammasuk3" value="<?= $skpd['jammasuk3']; ?>" class="form-control" autocomplete="off">
                    </div>
                    <div class="col-sm-6">
                        JAM KELUAR : <input type="text" name="jampulang3" id="jampulang3" value="<?= $skpd['jampulang3']; ?>" class="form-control" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">MENGIKUTI KEGIATAN</legend>
                    <?php if($skpd['status_kegiatan']=="1") { ?>
                        <label style="margin-right: 10px;" for="status_kegiatan">
                            <input type="radio" name="status_kegiatan" id="status_kegiatan" value="1" checked> MENGIKUTI
                        </label>  
                        <label style="margin-right: 10px;" for="status_kegiatan">
                            <input type="radio" name="status_kegiatan" id="status_kegiatan" value="0"> TIDAK MENGIKUTI
                        </label>
                    <?php } else { ?>
                        <label style="margin-right: 10px;" for="status_kegiatan">
                            <input type="radio" name="status_kegiatan" id="status_kegiatan" value="1"> MENGIKUTI
                        </label>  
                        <label style="margin-right: 10px;" for="status_kegiatan">
                            <input type="radio" name="status_kegiatan" id="status_kegiatan" value="0" checked> TIDAK MENGIKUTI
                        </label>
                    <?php } ?>
                </fieldset>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" id="btn-ubah" class="btn btn-sm btn-primary">
            <i class="fa fa-save"></i> SIMPAN
        </button>
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
            <i class="fa fa-times"></i> BATAL
        </button>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#form-ubah #tgl_libur').datepicker({
            autoclose: true
        });
    });

    $('#form-ubah').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>