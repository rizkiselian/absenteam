<?php $this->load->view("_partial/header"); ?>
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <h3 class="text-white pb-3 fw-bold">DATA PEGAWAI PER UNIT KERJA</h3>
        </div>
    </div>
</div>

<div class="page-inner mt--5 mb-2">
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
                                        <th>NAMA</th>
                                        <th>NIP</th>
                                        <th>GOLONGAN</th>
                                        <th>JABATAN</th>
                                        <th>STATUS SHIFT</th>
                                        <th>AKSI</th>
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

<div class="modal fade" id="modal-reset">
    <div class="modal-dialog modal-md">
        <div id="load-form-reset"></div>
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
                    url: '<?= site_url('load-pegawai/'); ?>' + skpd,
                    type: 'POST'
                },
                columnDefs: [{
                    className: 'text-center',
                    targets: [0, 2, 3, 5, 6]
                }],
                columns: [{
                    data: 'no'
                }, {
                    data: 'nama'
                }, {
                    data: 'nip'
                }, {
                    data: 'golongan'
                }, {
                    data: 'jabatan'
                }, {
                    data: 'status_shift'
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

        
    //==========================================================
    $(document).on("click", "#tombol-reset", function() {
        $.ajax({
            url: '<?= site_url('pegawai/formResetData'); ?>',
            type: "POST",
            data: {
                user: $(this).data('id')
            },
            success: function(data) {
                $('#load-form-reset').html(data);
            }
        });
    });

    $(document).on("click", "#btn-reset", function() {
        $(this).attr('disabled', true);
        $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
        $.ajax({
            url: "<?= site_url('pegawai/resetData'); ?>",
            type: 'POST',
            data: $('#form-reset').serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    $('#modal-reset').modal('hide');
                    reload_ajax();
                    if (data.notif) {
                        notifikasi('success', 'Berhasil', 'Data Berhasil Disimpan');
                    } else {
                        notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                    }
                } else {
                    notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                    $.each(data.errors, function(key, value) {
                        $('#form-reset [name="' + key + '"]').parents(".form-group").removeClass('has-success');
                        $('#form-reset [name="' + key + '"]').parents(".form-group").addClass('has-error');
                        $('#form-reset .' + key).html(value);
                        if (value == "") {
                            $('#form-reset [name="' + key + '"]').parents(".form-group").removeClass('has-error');
                            $('#form-reset [name="' + key + '"]').parents(".form-group").addClass('has-success');
                        }
                    });
                }
                $('#btn-reset').html("<i class='fa fa-save'></i> SIMPAN");
                $('#btn-reset').attr('disabled', false);
            },
            error: function(xhr, status, msg) {
                alert('Status: ' + status + "\n" + msg);
                $('#btn-reset').html("<i class='fa fa-save'></i> SIMPAN");
                $('#btn-reset').attr('disabled', false);
            }
        });
    });

        //==========================================================
        $(document).on("click", "#tombol-shift", function(e) {
            e.preventDefault();
            swal({
                title: 'Konfirmasi Update',
                text: "Apakah Anda Yakin Akan Mengubah Status Shift Pegawai Ini Menjadi : "+$(this).data('deskripsi')+"?",
                icon: 'warning',
                buttons: {
                    confirm: {
                        text: 'UPDATE DATA',
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
                        url: '<?= site_url('pegawai/shift_update'); ?>',
                        type: "POST",
                        data: {
                            idPegawai: $(this).data('id'),
                            nilai: $(this).data('nilai')
                        },
                        success: function(data) {
                            reload_ajax();
                            if (data.notif) {
                                notifikasi('success', 'Berhasil', 'Data Berhasil Diupdate');
                            } else {
                                notifikasi('error', 'Gagal', 'Data Gagal Diupdate');
                            }
                        }
                    });
                }
            })
        });
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
                    url: '<?= site_url('load-pegawai/'); ?>' + skpd,
                    type: 'POST'
                },
                columnDefs: [{
                    className: 'text-center',
                    targets: [0, 2, 3, 5]
                }],
                columns: [{
                    data: 'no'
                }, {
                    data: 'nama'
                }, {
                    data: 'nip'
                }, {
                    data: 'golongan'
                }, {
                    data: 'jabatan'
                }, {
                    data: 'status_shift'
                }],
            });
        }

        function reload_ajax() {
            table.ajax.reload(null, false);
        }

        //==========================================================
        $(document).on("click", "#tombol-shift", function(e) {
            e.preventDefault();
            swal({
                title: 'Konfirmasi Update',
                text: "Apakah Anda Yakin Akan Mengubah Status Shift Pegawai Ini Menjadi : "+$(this).data('deskripsi')+"?",
                icon: 'warning',
                buttons: {
                    confirm: {
                        text: 'UPDATE DATA',
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
                        url: '<?= site_url('pegawai/shift_update'); ?>',
                        type: "POST",
                        data: {
                            idPegawai: $(this).data('id'),
                            nilai: $(this).data('nilai')
                        },
                        success: function(data) {
                            reload_ajax();
                            if (data.notif) {
                                notifikasi('success', 'Berhasil', 'Data Berhasil Diupdate');
                            } else {
                                notifikasi('error', 'Gagal', 'Data Gagal Diupdate');
                            }
                        }
                    });
                }
            })
        });
    </script>
<?php endif; ?>
<?php $this->load->view('_partial/tag_close'); ?>