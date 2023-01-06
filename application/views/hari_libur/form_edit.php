<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            UBAH HARI LIBUR
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-ubah">
            <div class="form-group">
                <label for="tgl_libur">Tanggal</label>
                <input type="hidden" name="id_hari_libur" id="id_hari_libur" value="<?= encrypt_url($libur['id_hari_libur']); ?>" class="form-control" autocomplete="off" readonly>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                    <input type="text" name="tgl_libur" id="tgl_libur" value="<?= format_tanggal($libur['tanggal_libur']); ?>" data-date-format="dd-mm-yyyy" class="form-control" autocomplete="off">
                </div>
                <small class="text-danger tgl_libur"></small>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="form-control" rows="3"><?= $libur['keterangan']; ?></textarea>
                <small class="text-danger keterangan"></small>
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