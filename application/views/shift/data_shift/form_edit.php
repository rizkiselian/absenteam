<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            UBAH SHIFT
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-ubah">
            <div class="form-group">
                <label for="nama_kegnama_shiftiatan">Nama Shift</label>
                <input type="hidden" name="id_shift" id="id_shift" value="<?= encrypt_url($shift['id_shift']); ?>" class="form-control" autocomplete="off" readonly>
                <input type="text" name="nama_shift" id="nama_shift"  value="<?=$shift['nama_shift']?>" class="form-control" autocomplete="off">
                <small class="text-danger nama_shift"></small>
            </div>
            <div class="form-group">
                <label for="waktu">WAKTU</label>
                <div class="row">
                    <div class="col-sm-6">
                        JAM MASUK : <input type="text" name="jammasuk" id="jammasuk"  value="<?=$shift['jammasuk']?>" class="form-control" autocomplete="off">
                        <small class="text-danger jammasuk"></small>
                    </div>
                    <div class="col-sm-6">
                        JAM KELUAR : <input type="text" name="jampulang" id="jampulang"  value="<?=$shift['jampulang']?>" class="form-control" autocomplete="off">
                        <small class="text-danger jampulang"></small>
                    </div>
                </div>
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
        $('#form-ubah #tanggal').datepicker({
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