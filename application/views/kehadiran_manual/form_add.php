<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            TAMBAH KEHADIRAN MANUAL
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-tambah">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="jabatan">Tanggal</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" name="tgl_hadir" id="tgl_hadir" value="<?= date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" class="form-control" autocomplete="off" readonly>
                        </div>
                        <small class="text-danger tgl_hadir"></small>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="berkas">Berkas (Format PDF)</label>
                        <input type="file" name="file_upload" id="file_upload" class="form-control" accept="application/pdf" onchange="return file_pdf('file_upload')">
                        <small class="text-danger berkas"></small>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
                        <small class="text-danger keterangan"></small>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="berkas">PNS HADIR</label>
                        <input type="hidden" name="id_skpd" id="id_skpd" value="<?= $skpd; ?>" class="form-control" readonly>
                        <table class="table-default" style="width: 100%;">
                            <tr style="background-color: #1572EB; color: white;">
                                <td>NO</td>
                                <td>#</td>
                                <td>NAMA</td>
                                <td>JABATAN</td>
                            </tr>
                            <?php $no = 1; ?>
                            <?php foreach ($result_pegawai as $pegawai) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><input type="checkbox" name="id_pegawai[]" value="<?= $pegawai['id_pegawai']; ?>" checked></td>
                                    <td><?= format_nama($pegawai['gelar_depan'], $pegawai['nama_pegawai'], $pegawai['gelar_belakang']); ?></td>
                                    <td><?= jabatan($pegawai['plt'], $pegawai['nama_jabatan']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <small class="text-danger berkas"></small>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="berkas">TENAGA KONTRAK HADIR</label>
                        <table class="table-default" style="width: 100%;">
                            <tr style="background-color: #1572EB; color: white;">
                                <td>NO</td>
                                <td>#</td>
                                <td>NAMA</td>
                                <td>JABATAN</td>
                            </tr>
                            <?php $no = 1; ?>
                            <?php foreach ($result_honor as $honor) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><input type="checkbox" name="id_pegawai[]" value="<?= $honor['id_pegawai']; ?>" checked></td>
                                    <td><?= format_nama($honor['gelar_depan'], $honor['nama_pegawai'], $honor['gelar_belakang']); ?></td>
                                    <td><?= $honor['jabatan_honor']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
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
        // $('#form-tambah #tgl_hadir').datepicker({
        //     autoclose: true
        // });
    });

    $('#form-tambah').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>