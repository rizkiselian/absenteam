<?php $this->load->view("_partial/header"); ?>
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white pb-3 fw-bold">PROFILE USER</h3>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card full-height">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <?php
                    ($user['foto_profile'] == "") ? $foto = "admin.png" : $foto = $user['foto_profile'];
                    if ($user['status_kerja'] == "honor") {
                        $jabatan = $user['jabatan_honor'];
                    } else {
                        $jabatan = jabatan($user['plt'], $user['nama_jabatan']);
                    }
                    ?>
                    <img src="<?= base_url('uploads/users/' . $foto); ?>" alt="" id="profile-gambar" style="width: 100%; min-height: 280px;">
                </div>

                <div class="col-lg-9 col-md-8">
                    <table class="table table-border">
                        <tr>
                            <td>USERNAME</td>
                            <td>: <?= $user['username']; ?></td>
                        </tr>
                        <tr>
                            <td>PENANGGUNG JAWAB</td>
                            <td>: <?= format_nama($user['gelar_depan'], $user['nama_pegawai'], $user['gelar_belakang']); ?></td>
                        </tr>
                        <tr>
                            <td>JABATAN</td>
                            <td>: <?= $jabatan; ?></td>
                        </tr>
                        <tr>
                            <td>UNIT KERJA</td>
                            <td>: <?= $user['nama_skpd']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <button id="upload-foto" class="btn btn-warning btn-round btn-sm mr-2 mb-3" data-toggle="modal" data-target="#modal-foto">Upload Foto</button>

                                <button id="ubah-password" class="btn btn-danger btn-round btn-sm mr-2 mb-3" data-toggle="modal" data-target="#modal-password">Change Password</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-foto">
    <div class="modal-dialog modal-sm">
        <div id="load-form-foto"></div>
    </div>
</div>

<div class="modal fade" id="modal-password">
    <div class="modal-dialog modal-sm">
        <div id="load-form-password"></div>
    </div>
</div>

<?php $this->load->view('_partial/footer'); ?>
<script>
    // ====================================================================================
    $(document).on("click", "#upload-foto", function() {
        $(this).attr('disabled', true);
        $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
        $.ajax({
            url: '<?= site_url('form-upload-foto'); ?>',
            type: "POST",
            success: function(data) {
                $('#load-form-foto').html(data);
                $('#upload-foto').html("Upload Foto");
                $('#upload-foto').attr('disabled', false);
            }
        });
    });

    $(document).on("click", "#btn-upload", function() {
        $(this).attr('disabled', true);
        $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
        var form = $('#form-upload')[0];
        $.ajax({
            url: "<?= site_url('upload-foto'); ?>",
            type: "POST",
            enctype: 'multipart/form-data',
            data: new FormData(form),
            dataType: "json",
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                if (data.status) {
                    $('#modal-foto').modal('hide');
                    if (data.notif) {
                        notifikasi('success', 'Berhasil', 'Data Berhasil Disimpan');
                        $('#profile-gambar').attr('src', "<?= base_url('uploads/users/'); ?>" + data.file_name);
                    } else {
                        notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                    }
                } else {
                    notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                    $.each(data.errors, function(key, value) {
                        $('#form-upload [name="' + key + '"]').parents(".form-group").removeClass('has-success');
                        $('#form-upload [name="' + key + '"]').parents(".form-group").addClass('has-error');
                        $('#form-upload .' + key).html(value);
                        if (value == "") {
                            $('#form-upload [name="' + key + '"]').parents(".form-group").removeClass('has-error');
                            $('#form-upload [name="' + key + '"]').parents(".form-group").addClass('has-success');
                        }
                    });
                }
                $('#btn-upload').html("<i class='fa fa-save'></i> SIMPAN");
                $('#btn-upload').attr('disabled', false);
            },
            error: function(xhr, status, msg) {
                alert('Status: ' + status + "\n" + msg);
                $('#btn-upload').html("Upload Foto");
                $('#btn-upload').attr('disabled', false);
            }
        });
    });

    //==========================================================
    $(document).on("click", "#ubah-password", function() {
        $.ajax({
            url: '<?= site_url('form-change-password'); ?>',
            type: "POST",
            success: function(data) {
                $('#load-form-password').html(data);
            }
        });
    });

    $(document).on("click", "#btn-password", function() {
        $(this).attr('disabled', true);
        $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
        $.ajax({
            url: "<?= site_url('change-password'); ?>",
            type: 'POST',
            data: $('#form-password').serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    $('#modal-password').modal('hide');
                    if (data.notif) {
                        notifikasi('success', 'Berhasil', 'Data Berhasil Disimpan');
                    } else {
                        notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                    }
                } else {
                    notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                    $.each(data.errors, function(key, value) {
                        $('#form-password [name="' + key + '"]').parents(".form-group").removeClass('has-success');
                        $('#form-password [name="' + key + '"]').parents(".form-group").addClass('has-error');
                        $('#form-password .' + key).html(value);
                        if (value == "") {
                            $('#form-password [name="' + key + '"]').parents(".form-group").removeClass('has-error');
                            $('#form-password [name="' + key + '"]').parents(".form-group").addClass('has-success');
                        }
                    });
                }
                $('#btn-password').html("Change Password");
                $('#btn-password').attr('disabled', false);
            },
            error: function(xhr, status, msg) {
                alert('Status: ' + status + "\n" + msg);
                $('#btn-password').html("Change Password");
                $('#btn-password').attr('disabled', false);
            }
        });
    });

    function file_image(fileupload, preview) {
        var input_file = document.getElementById(fileupload);
        var path_file = input_file.value;
        var extention_ok = /(\.jpg|\.jpeg|\.png)$/i;
        if (!extention_ok.exec(path_file)) {
            swal({
                icon: 'error',
                title: 'KONFIRMASI',
                text: 'FORMAT EKSTENSI .JPG ATAU .PNG',
                timer: 1500
            })
            input_file.value = '';
            $(preview).attr('src', "<?= base_url('uploads/users/' . $foto); ?>");
            return false;
        } else {
            if (input_file.files && input_file.files[0]) {
                if (input_file.files[0].size > 512000) {
                    swal({
                        icon: 'error',
                        title: 'KONFIRMASI',
                        text: 'UKURAN GAMBAR HARUS DI BAWAH ' + (512000 / 1024) + ' KB',
                        timer: 1500
                    })
                    input_file.value = '';
                    $(preview).attr('src', "<?= base_url('uploads/users/' . $foto); ?>");
                    return false;
                } else {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(preview).attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input_file.files[0]);
                }
            }
        }
    }
</script>
<?php $this->load->view('_partial/tag_close'); ?>