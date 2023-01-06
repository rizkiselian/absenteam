<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            TAMBAH USER UNIT KERJA
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-tambah">
            <div class="form-group">
                <label for="unit_kerja">Unit Kerja</label>
                <select name="unit_kerja" id="unit_kerja" class="form-control select2" style="width: 100%;">
                    <option value="">PILIH UNIT KERJA</option>
                    <?php foreach ($result_skpd as $r) : ?>
                        <option value="<?= encrypt_url($r['id_skpd']); ?>"><?= $r['nama_skpd']; ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-danger unit_kerja"></small>
            </div>
            <div class="form-group">
                <label for="nama_pegawai">Nama Pegawai</label>
                <select name="nama_pegawai" id="nama_pegawai" class="form-control select2" style="width: 100%;">
                </select>
                <small class="text-danger nama_pegawai"></small>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" autocomplete="off">
                <small class="text-danger username"></small>
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
        $('#form-tambah .select2').select2();
    });

    $("#form-tambah #unit_kerja").change(function() {
        var unit = $("#form-tambah #unit_kerja").val();
        $.ajax({
            url: '<?= site_url('load-pegawai-by-skpd'); ?>',
            type: "POST",
            data: {
                unit_kerja: $("#form-tambah #unit_kerja").val(),
                pegawai: null
            },
            success: function(data) {
                $('#form-tambah #nama_pegawai').html(data);
            }
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