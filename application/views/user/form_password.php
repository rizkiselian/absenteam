<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            UBAH PASSWORD
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-password">
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="hidden" name="username" id="username" value="<?= $user['username']; ?>" class="form-control" readonly>
                <input type="password" name="password" id="password" value="<?= set_value('password'); ?>" class="form-control" autocomplete="off">
                <small class="text-danger password"></small>
            </div>

            <div class="form-group">
                <label for="password_confirm">Password Confirm</label>
                <input type="password" name="password_confirm" id="password_confirm" value="<?= set_value('password_confirm'); ?>" class="form-control" autocomplete="off">
                <small class="text-danger password_confirm"></small>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" id="btn-password" class="btn btn-sm btn-primary">
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