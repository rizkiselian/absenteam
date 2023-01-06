<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            TAMBAH PEGAWAI TUGAS LUAR
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-tambah">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="pegawai">Pegawai</label>
                        <select name="pegawai" id="pegawai" class="form-control select2" style="width: 100%;">
                            <option value="">PILIH PEGAWAI</option>
                            <?php foreach ($result_pegawai as $r) : ?>
                                <option value="<?= encrypt_url($r['id_pegawai']); ?>"><?= format_nama($r['gelar_depan'], $r['nama_pegawai'], $r['gelar_belakang']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-danger pegawai"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" name="jabatan" id="jabatan" class="form-control" autocomplete="off" readonly>
                        <small class="text-danger jabatan"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="jabatan">Tanggal Tugas Luar</label>
                        <div class="input-daterange input-group">
                            <input type="text" class="input-sm form-control" name="tgl_mulai" value="<?= date('d-m-Y'); ?>" placeholder="Tanggal mulai" autocomplete="off" />
                            <span class="input-group-addon" style="background-color: whitesmoke; font-weight: bold; padding: 10px;">
                                s/d
                            </span>
                            <input type="text" class="input-sm form-control" name="tgl_akhir" value="<?= date('d-m-Y'); ?>" placeholder="Tanggal akhir" autocomplete="off" />
                        </div>
                        <small class="text-danger tgl_mulai"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
                        <small class="text-danger keterangan"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="berkas">Berkas (Format PDF)</label>
                        <input type="file" name="file_upload" id="file_upload" class="form-control" accept="application/pdf" onchange="return file_pdf('file_upload')">
                        <small class="text-danger berkas"></small>
                    </div>
                </div>
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
        $('.input-daterange').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });

    $("#form-tambah #pegawai").change(function() {
        $.ajax({
            url: '<?= site_url('cek-jabatan-pegawai'); ?>',
            type: "POST",
            data: {
                pegawai: $("#form-tambah #pegawai").val()
            },
            success: function(data) {
                $("#form-tambah #jabatan").val(data);
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