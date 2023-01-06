<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            TAMBAH SHIFT PEGAWAI
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-tambah">
            <div class="form-group">
                <label for="id_pegawai">PEGAWAI</label>
                <input type="hidden" name="id_skpd" id="id_skpd" value="<?=$idSkpd?>" class="form-control" autocomplete="off">
                <small class="text-danger id_skpd"></small>
                <select name="id_pegawai" id="id_pegawai" class="form-control select2" style="width: 100%;">
                    <option value="">PILIH PEGAWAI</option>
                    <?php foreach ($result_pegawai as $r) : ?>
                        <option value="<?= encrypt_url($r['id_pegawai']); ?>"><?= format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']); ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-danger id_pegawai"></small>
            </div>
            <div class="form-group">
                <label for="tanggal">TANGGAL</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" autocomplete="off">
                <small class="text-danger tanggal"></small>
            </div>
            <div class="form-group">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">SHIFT</legend>
                    <label style="margin-right: 10px;" for="id_shift">
                        <input type="radio" name="id_shift" id="id_shift" value="1" checked> Shift Pagi
                    </label>  
                    <label style="margin-right: 10px;" for="id_shift">
                        <input type="radio" name="id_shift" id="id_shift" value="2"> Shift Siang
                    </label>
                    <label style="margin-right: 10px;" for="id_shift">
                        <input type="radio" name="id_shift" id="id_shift" value="3"> Shift Malam
                    </label>
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
        $('.select2').select2();
    });

    $('#form-tambah').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>