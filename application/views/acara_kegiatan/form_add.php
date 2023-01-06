<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            TAMBAH ACARA KEGIATAN
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="form-tambah">
            <div class="form-group">
                <label for="nama_kegiatan">Nama Kegiatan</label>
                <textarea name="nama_kegiatan" id="nama_kegiatan" class="form-control" rows="3"></textarea>
                <small class="text-danger nama_kegiatan"></small>
            </div>
            <div class="form-group">
                <label for="tanggal">Tanggal/Hari</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                    <input type="tanggal" name="tanggal" id="tanggal" data-date-format="dd-mm-yyyy" class="form-control" autocomplete="off">
                </div>
                <small class="text-danger tanggal"></small>
            </div>
            <div class="form-group">
                <label for="waktu">WAKTU</label>
                <div class="row">
                    <div class="col-sm-6">
                        JAM MASUK : <input type="text" name="jammasuk" id="jammasuk" value="00:00:00" class="form-control" autocomplete="off">
                    </div>
                    <div class="col-sm-6">
                        JAM KELUAR : <input type="text" name="jampulang" id="jampulang" value="00:00:00" class="form-control" autocomplete="off">
                    </div>
                </div>
                <small class="text-primary">Kosongkan Jika Menggunakan Jam Normal</small>
            </div>
            <div class="form-group">
                <label for="latitude">KOORDINAT</label>
                <div class="row">
                    <div class="col-sm-6">
                        LATITUDE : <input type="text" name="latitude" id="latitude" class="form-control" autocomplete="off">
                    </div>
                    <div class="col-sm-6">
                        LONGITUDE : <input type="text" name="longitude" id="longitude" class="form-control" autocomplete="off">
                    </div>
                    <small class="text-danger latitude"></small>
                    <small class="text-danger longitude"></small>
                </div>
            </div>
            <div class="form-group">
                <label for="radius">Radius</label>
                <input type="text" name="radius" id="radius" class="form-control" autocomplete="off">
                <small class="text-danger radius"></small>
            </div>
            <div class="form-group">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Status Jam</legend>
                    <label style="margin-right: 10px;" for="status_jam">
                        <input type="radio" name="status_jam" id="status_jam" value="0" checked> Jam Normal
                    </label>  
                    <label style="margin-right: 10px;" for="status_jam">
                        <input type="radio" name="status_jam" id="status_jam" value="1"> Jam Kegiatan
                    </label>
                </fieldset>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Status Rutin</legend>
                            <label style="margin-right: 10px;" for="status_rutin">
                                <input type="radio" name="status_rutin" id="status_rutin" value="1"> Rutin
                            </label>  
                            <label style="margin-right: 10px;" for="status_rutin">
                                <input type="radio" name="status_rutin" id="status_rutin" value="0" checked> Tidak Rutin
                            </label>
                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Status Aktif</legend>
                            <label style="margin-right: 10px;" for="status_aktif">
                                <input type="radio" name="status_aktif" id="status_aktif" value="1" checked> Aktif
                            </label>  
                            <label style="margin-right: 10px;" for="status_aktif">
                                <input type="radio" name="status_aktif" id="status_aktif" value="0"> Tidak Aktif
                            </label>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="radius">Acuan Koordinat</label>
                <div class="row">
                    <div class="col-sm-6">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Masuk</legend>
                            <label style="margin-right: 10px;" for="acuan_koor_masuk">
                                <input type="radio" name="acuan_koor_masuk" id="acuan_koor_masuk" value="1" checked> Kegiatan
                            </label>  
                            <label style="margin-right: 10px;" for="acuan_koor_masuk">
                                <input type="radio" name="acuan_koor_masuk" id="acuan_koor_masuk" value="0"> SKPD
                            </label>
                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Pulang</legend>
                            <label style="margin-right: 10px;" for="acuan_koor_pulang">
                                <input type="radio" name="acuan_koor_pulang" id="acuan_koor_pulang" value="1" checked> Kegiatan
                            </label>  
                            <label style="margin-right: 10px;" for="acuan_koor_pulang">
                                <input type="radio" name="acuan_koor_pulang" id="acuan_koor_pulang" value="0"> SKPD
                            </label>
                        </fieldset>
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
        $('#form-tambah #tanggal').datepicker({
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