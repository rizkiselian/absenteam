<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            RESET PASSWORD USER
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-reset">
            <div class="form-group">
                <label for="nip">Nip Pegawai</label>
                <input type="hidden" name="id_pegawai" id="id_pegawai" value="<?= encrypt_url($pegawai['id_pegawai']); ?>" class="form-control" autocomplete="off" readonly>
                <input type="text" name="nip" id="nip" value="<?= $pegawai['nip']; ?>" class="form-control" autocomplete="off" readonly>
                <small class="text-danger nip"></small>
            </div>
            <div class="form-group">
                <label for="nama_pegawai">Nama Pegawai</label>
                <input type="text" name="nama_pegawai" id="nama_pegawai" value="<?= $pegawai['nama_pegawai']; ?>" class="form-control" autocomplete="off" readonly>
                <small class="text-danger nama_pegawai"></small>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" id="btn-reset" class="btn btn-sm btn-primary">
            <i class="fa fa-save"></i> RESET
        </button>
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
            <i class="fa fa-times"></i> BATAL
        </button>
    </div>
</div>

<script>
    $('#form-reset').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>