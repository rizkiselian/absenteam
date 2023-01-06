<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            UBAH FOTO PROFILE
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-upload">
            <div class="form-group">
                <label for="file_upload">Foto Profile</label>
                <input type="hidden" name="username" id="username" value="<?= $user['username']; ?>" class="form-control" readonly>
                <br>
                <?php ($user['foto_profile'] == "") ? $foto = "admin.png" : $foto = $user['foto_profile']; ?>
                <img src="<?= base_url('uploads/users/' . $foto); ?>" alt="" style="width: 100%;" id="load_gambar">
                <input type="file" name="file_upload" id="file_upload" class="form-control" accept="image/*" onchange="return file_image('file_upload', '#load_gambar')">
                <small class="text-danger file_upload"></small>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" id="btn-upload" class="btn btn-sm btn-primary">
            <i class="fa fa-save"></i> SIMPAN
        </button>
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
            <i class="fa fa-times"></i> BATAL
        </button>
    </div>
</div>

<script>
    $('#form-upload').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>