<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            TAMBAH HARI LIBUR
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-tambah">
            <div class="form-group">
                <label for="tgl_libur">Tanggal</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                    <input type="text" name="tgl_libur" id="tgl_libur" data-date-format="dd-mm-yyyy" class="form-control" autocomplete="off">
                </div>
                <small class="text-danger tgl_libur"></small>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
                <small class="text-danger keterangan"></small>
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
        $('#form-tambah #tgl_libur').datepicker({
            autoclose: true
        });
    });

    $('#form-tambah').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>