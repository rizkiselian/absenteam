<?php $this->load->view("_partial/header"); ?>
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white pb-3 fw-bold">DATA KEHADIRAN MANUAL</h3>
            <div class="ml-sm-auto py-md-0">
                <button id="tombol-tambah" class="btn btn-secondary btn-round btn-sm mr-2 mb-3" data-toggle="modal" data-target="#modal-tambah">TAMBAH DATA</button>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card full-height">
        <div class="card-body">
            <?php if ($role == "admin") : ?>
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">FILTER UNIT KERJA</legend>
                            <div class="row p-2">
                                <div class="col-md-8 mb-3">
                                    <select name="skpd" id="skpd" class="form-control select2" style="width: 100%;">
                                        <?php foreach ($result_skpd as $r) : ?>
                                            <option value="<?= encrypt_url($r['id_skpd']); ?>"><?= $r['nama_skpd']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="tombol-filter" class="btn btn-primary" style="width: 100%; padding-bottom: 5px;"> <i class="fa fa-search"></i> TAMPILKAN</button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-sm-12">
                    <div class="preload" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                        <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                        <h5>Sedang memuat data...</h5>
                    </div>
                    <div id="load-data-tabel" style="display: none;">
                        <div class="table-responsive">
                            <table id="tabel-data" class="table-default" style="width: 100%;">
                                <thead>
                                    <tr style="background-color: #1572EB; color: white;">
                                        <th style="width: 5px;">NO</th>
                                        <th>TANGGAL</th>
                                        <th>KETERANGAN</th>
                                        <th>BERKAS</th>
                                        <th>WAKTU INPUT</th>
                                        <th style="width: 30px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-tambah" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="load-form-tambah"></div>
    </div>
</div>

<?php $this->load->view('_partial/footer'); ?>
<?php if ($role == "admin") : ?>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            var skpd = $("#skpd").val();
            load_data(skpd);
        });

        function load_data(skpd) {
            $('#load-data-tabel').css('display', 'none');
            $('.preload').show();
            table = $('#tabel-data').DataTable({
                destroy: true,
                ordering: false,
                bAutoWidth: false,
                initComplete: function() {
                    $('#load-data-tabel').css('display', 'block');
                    $('.preload').hide();
                    $('#tombol-filter').html("<i class='fa fa-search'></i> TAMPILKAN");
                    $('#tombol-filter').attr('disabled', false);
                },
                ajax: {
                    url: '<?= site_url('load-kehadiran-manual/'); ?>' + skpd,
                    type: 'POST'
                },
                columns: [{
                    data: 'no'
                }, {
                    data: 'tanggal'
                }, {
                    data: 'keterangan'
                }, {
                    data: 'berkas'
                }, {
                    data: 'waktu_input'
                }, {
                    data: 'aksi'
                }],
            });
        }

        function reload_ajax() {
            table.ajax.reload(null, false);
        }

        $(document).on("click", "#tombol-filter", function() {
            $(this).attr('disabled', true);
            $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
            var skpd = $("#skpd").val();
            load_data(skpd);
        });

        // ====================================================================================
        $(document).on("click", "#tombol-tambah", function() {
            $(this).attr('disabled', true);
            $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
            $.ajax({
                url: '<?= site_url('form-add-kehadiran-manual'); ?>',
                type: "POST",
                data: {
                    unit_kerja: $("#skpd").val()
                },
                success: function(data) {
                    $('#load-form-tambah').html(data);
                    $('#tombol-tambah').html("<i class='fa fa-plus'></i> TAMBAH DATA");
                    $('#tombol-tambah').attr('disabled', false);
                }
            });
        });

        $(document).on("click", "#btn-simpan", function() {
            $(this).attr('disabled', true);
            $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
            $.ajax({
                url: "<?= site_url('add-kehadiran-manual'); ?>",
                type: 'POST',
                enctype: 'multipart/form-data',
                data: new FormData($('#form-tambah')[0]),
                dataType: "json",
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {
                    if (data.status) {
                        $('#modal-tambah').modal('hide');
                        reload_ajax();
                        if (data.notif) {
                            notifikasi('success', 'Berhasil', 'Data Berhasil Disimpan');
                        } else {
                            notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                        }
                    } else {
                        if (data.tanggal) {
                            notifikasi('error', 'Gagal', data.pesan);
                        } else {
                            notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                            $.each(data.errors, function(key, value) {
                                $('#form-tambah [name="' + key + '"]').parents(".form-group").removeClass('has-success');
                                $('#form-tambah [name="' + key + '"]').parents(".form-group").addClass('has-error');
                                $('#form-tambah .' + key).html(value);
                                if (value == "") {
                                    $('#form-tambah [name="' + key + '"]').parents(".form-group").removeClass('has-error');
                                    $('#form-tambah [name="' + key + '"]').parents(".form-group").addClass('has-success');
                                }
                            });
                        }
                    }
                    $('#btn-simpan').html("<i class='fa fa-save'></i> SIMPAN");
                    $('#btn-simpan').attr('disabled', false);
                },
                error: function(xhr, status, msg) {
                    alert('Status: ' + status + "\n" + msg);
                    $('#btn-simpan').html("<i class='fa fa-save'></i> SIMPAN");
                    $('#btn-simpan').attr('disabled', false);
                }
            });
        });

        //==========================================================
        $(document).on("click", "#tombol-hapus", function(e) {
            e.preventDefault();
            swal({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda Yakin Akan Menghapus Data Ini?",
                icon: 'warning',
                buttons: {
                    confirm: {
                        text: 'HAPUS DATA',
                        className: 'btn btn-success'
                    },
                    cancel: {
                        visible: true,
                        text: 'BATAL',
                        className: 'btn btn-danger'
                    }
                }
            }).then((Delete) => {
                if (Delete) {
                    $.ajax({
                        url: '<?= site_url('delete-kehadiran-manual'); ?>',
                        type: "POST",
                        data: {
                            kehadiran_manual: $(this).data('id')
                        },
                        success: function(data) {
                            console.log(data);
                            reload_ajax();
                            if (data.notif) {
                                notifikasi('success', 'Berhasil', 'Data Berhasil Dihapus');
                            } else {
                                notifikasi('error', 'Gagal', 'Data Gagal Dihapus');
                            }
                        },
                        error: function(xhr, status, msg) {
                            alert('Status: ' + status + "\n" + msg);
                        }
                    });
                }
            })
        });

        function file_pdf(fileupload) {
            var input_file = document.getElementById(fileupload);
            var path_file = input_file.value;
            var extention_ok = /(\.pdf)$/i;
            if (!extention_ok.exec(path_file)) {
                swal({
                    icon: 'error',
                    title: 'KONFIRMASI',
                    text: 'FORMAT EKSTENSI HARUS .PDF',
                    timer: 1500
                })
                input_file.value = '';
                return false;
            } else {
                if (input_file.files && input_file.files[0]) {
                    if (input_file.files[0].size > 512000) {
                        swal({
                            icon: 'error',
                            title: 'KONFIRMASI',
                            text: 'UKURAN FILE HARUS DI BAWAH ' + (512000 / 1024) + ' KB',
                            timer: 1500
                        })
                        input_file.value = '';
                        return false;
                    }
                }
            }
        }
    </script>
<?php else : ?>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            var skpd = "<?= $skpd; ?>";
            load_data(skpd);
        });

        function load_data(skpd) {
            $('#load-data-tabel').css('display', 'none');
            $('.preload').show();
            table = $('#tabel-data').DataTable({
                destroy: true,
                ordering: false,
                bAutoWidth: false,
                initComplete: function() {
                    $('#load-data-tabel').css('display', 'block');
                    $('.preload').hide();
                    $('#tombol-filter').html("<i class='fa fa-search'></i> TAMPILKAN");
                    $('#tombol-filter').attr('disabled', false);
                },
                ajax: {
                    url: '<?= site_url('load-kehadiran-manual/'); ?>' + skpd,
                    type: 'POST'
                },
                columns: [{
                    data: 'no'
                }, {
                    data: 'tanggal'
                }, {
                    data: 'keterangan'
                }, {
                    data: 'berkas'
                }, {
                    data: 'waktu_input'
                }, {
                    data: 'aksi'
                }],
            });
        }

        function reload_ajax() {
            table.ajax.reload(null, false);
        }

        $(document).on("click", "#tombol-filter", function() {
            $(this).attr('disabled', true);
            $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
            var skpd = "<?= $skpd; ?>";
            load_data(skpd);
        });

        // ====================================================================================
        $(document).on("click", "#tombol-tambah", function() {
            $(this).attr('disabled', true);
            $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
            $.ajax({
                url: '<?= site_url('form-add-kehadiran-manual'); ?>',
                type: "POST",
                data: {
                    unit_kerja: "<?= $skpd; ?>"
                },
                success: function(data) {
                    $('#load-form-tambah').html(data);
                    $('#tombol-tambah').html("<i class='fa fa-plus'></i> TAMBAH DATA");
                    $('#tombol-tambah').attr('disabled', false);
                }
            });
        });

        $(document).on("click", "#btn-simpan", function() {
            $(this).attr('disabled', true);
            $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
            $.ajax({
                url: "<?= site_url('add-kehadiran-manual'); ?>",
                type: 'POST',
                enctype: 'multipart/form-data',
                data: new FormData($('#form-tambah')[0]),
                dataType: "json",
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {
                    if (data.status) {
                        $('#modal-tambah').modal('hide');
                        reload_ajax();
                        if (data.notif) {
                            notifikasi('success', 'Berhasil', 'Data Berhasil Disimpan');
                        } else {
                            notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                        }
                    } else {
                        if (data.tanggal) {
                            notifikasi('error', 'Gagal', data.pesan);
                        } else {
                            notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                            $.each(data.errors, function(key, value) {
                                $('#form-tambah [name="' + key + '"]').parents(".form-group").removeClass('has-success');
                                $('#form-tambah [name="' + key + '"]').parents(".form-group").addClass('has-error');
                                $('#form-tambah .' + key).html(value);
                                if (value == "") {
                                    $('#form-tambah [name="' + key + '"]').parents(".form-group").removeClass('has-error');
                                    $('#form-tambah [name="' + key + '"]').parents(".form-group").addClass('has-success');
                                }
                            });
                        }
                    }
                    $('#btn-simpan').html("<i class='fa fa-save'></i> SIMPAN");
                    $('#btn-simpan').attr('disabled', false);
                },
                error: function(xhr, status, msg) {
                    alert('Status: ' + status + "\n" + msg);
                    $('#btn-simpan').html("<i class='fa fa-save'></i> SIMPAN");
                    $('#btn-simpan').attr('disabled', false);
                }
            });
        });

        //==========================================================
        $(document).on("click", "#tombol-hapus", function(e) {
            e.preventDefault();
            swal({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda Yakin Akan Menghapus Data Ini?",
                icon: 'warning',
                buttons: {
                    confirm: {
                        text: 'HAPUS DATA',
                        className: 'btn btn-success'
                    },
                    cancel: {
                        visible: true,
                        text: 'BATAL',
                        className: 'btn btn-danger'
                    }
                }
            }).then((Delete) => {
                if (Delete) {
                    $.ajax({
                        url: '<?= site_url('delete-kehadiran-manual'); ?>',
                        type: "POST",
                        data: {
                            kehadiran_manual: $(this).data('id')
                        },
                        success: function(data) {
                            console.log(data);
                            reload_ajax();
                            if (data.notif) {
                                notifikasi('success', 'Berhasil', 'Data Berhasil Dihapus');
                            } else {
                                notifikasi('error', 'Gagal', 'Data Gagal Dihapus');
                            }
                        },
                        error: function(xhr, status, msg) {
                            alert('Status: ' + status + "\n" + msg);
                        }
                    });
                }
            })
        });

        function file_pdf(fileupload) {
            var input_file = document.getElementById(fileupload);
            var path_file = input_file.value;
            var extention_ok = /(\.pdf)$/i;
            if (!extention_ok.exec(path_file)) {
                swal({
                    icon: 'error',
                    title: 'KONFIRMASI',
                    text: 'FORMAT EKSTENSI HARUS .PDF',
                    timer: 1500
                })
                input_file.value = '';
                return false;
            } else {
                if (input_file.files && input_file.files[0]) {
                    if (input_file.files[0].size > 512000) {
                        swal({
                            icon: 'error',
                            title: 'KONFIRMASI',
                            text: 'UKURAN FILE HARUS DI BAWAH ' + (512000 / 1024) + ' KB',
                            timer: 1500
                        })
                        input_file.value = '';
                        return false;
                    }
                }
            }
        }
    </script>
<?php endif; ?>
<?php $this->load->view('_partial/tag_close'); ?>