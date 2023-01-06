<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            UBAH DATA KOORDINAT PERSONAL
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-tambah">
            <div class="form-group">
                <label for="nama_pegawai">Nama Pegawai</label>
                <input type="hidden" name="id_pegawai" id="id_pegawai" value="<?=$pegawai['id_pegawai']?>" class="form-control" autocomplete="off" readonly>
                <input type="text" name="nama_pegawai" id="nama_pegawai" value="<?= format_nama($pegawai['gelar_depan'], $pegawai['nama_pegawai'], $pegawai['gelar_belakang']); ?>" class="form-control" autocomplete="off" readonly>
                <small class="text-danger nama_pegawai"></small>
            </div>
            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="text" name="longitude" id="longitude" value="<?=$pegawai['longitude']?>" class="form-control" autocomplete="off">
                <small class="text-danger longitude"></small>
            </div>
            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="text" name="latitude" id="latitude" value="<?=$pegawai['latitude']?>" class="form-control" autocomplete="off">
                <small class="text-danger latitude"></small>
            </div>
            <div class="form-group">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Status Koordinat Personal</legend>
                    <?php if($pegawai['status_personal']=="1") { ?>
                        <label style="margin-right: 10px;" for="status_personal">
                            <input type="radio" name="status_personal" id="status_personal" value="1" checked> Aktif
                        </label>  
                        <label style="margin-right: 10px;" for="status_1">
                            <input type="radio" name="status_personal" id="status_personal" value="0"> Tidak Aktif
                        </label>
                        <?php } else { ?>
                            <label style="margin-right: 10px;" for="status_personal">
                                <input type="radio" name="status_personal" id="status_personal" value="1"> Aktif
                            </label>  
                            <label style="margin-right: 10px;" for="status_1">
                                <input type="radio" name="status_personal" id="status_personal" value="0" checked> Tidak Aktif
                            </label>
                        <?php } ?>
                </fieldset>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" id="btn-simpan" class="btn btn-sm btn-primary">
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