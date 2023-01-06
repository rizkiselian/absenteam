<?php $this->load->view("_partial/header"); ?>
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white pb-3 fw-bold">DATA SHIFT</h3>
            <?php if ($role == "admin") : ?>
                <div class="ml-sm-auto py-md-0">
                    <button id="tombol-tambah" class="btn btn-secondary btn-round btn-sm mr-2 mb-3" data-toggle="modal" data-target="#modal-tambah">TAMBAH DATA</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card full-height">
        <div class="card-body">
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
                                        <th>NAMA SHIFT</th>
                                        <th>JAM MASUK</th>
                                        <th>JAM PULANG</th>
                                        <th style="width: 75px;">AKSI</th>
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
    <div class="modal-dialog modal-md" role="document">
        <div id="load-form-tambah"></div>
    </div>
</div>

<div class="modal fade" id="modal-ubah" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div id="load-form-ubah"></div>
    </div>
</div>

<?php $this->load->view('_partial/footer'); ?>
<script>
    $(document).ready(function() {
        load_data();
    });

        function load_data() {
            $('#load-data-tabel').css('display', 'none');
            $('.preload').show();
            table = $('#tabel-data').DataTable({
                destroy: true,
                ordering: false,
                bAutoWidth: false,
                initComplete: function() {
                    $('#load-data-tabel').css('display', 'block');
                    $('.preload').hide();
                },
                ajax: {
                    url: '<?= site_url('load-data-shift'); ?>',
                    type: 'POST'
                },
                columnDefs: [{
                    className: 'text-center',
                    targets: [0, 2, 3]
                }],
                columns: [{
                    data: 'no'
                }, {
                    data: 'nama_shift'
                }, {
                    data: 'jammasuk'
                }, {
                    data: 'jampulang'
                }, {
                    data: 'aksi'
                }],
            });
        }

    function reload_ajax() {
        table.ajax.reload(null, false);
    }

    // ====================================================================================
    $(document).on("click", "#tombol-tambah", function() {
        $(this).attr('disabled', true);
        $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
        $.ajax({
            url: '<?= site_url('form-add-data-shift'); ?>',
            type: "POST",
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
            url: "<?= site_url('add-data-shift'); ?>",
            type: 'POST',
            data: $('#form-tambah').serialize(),
            dataType: "json",
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
    $(document).on("click", "#tombol-ubah", function() {
        $.ajax({
            url: '<?= site_url('form-edit-data-shift'); ?>',
            type: "POST",
            data: {
                id: $(this).data('id')
            },
            success: function(data) {
                $('#load-form-ubah').html(data);
            }
        });
    });

    $(document).on("click", "#btn-ubah", function() {
        $(this).attr('disabled', true);
        $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
        $.ajax({
            url: "<?= site_url('edit-data-shift'); ?>",
            type: 'POST',
            data: $('#form-ubah').serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    $('#modal-ubah').modal('hide');
                    reload_ajax();
                    if (data.notif) {
                        notifikasi('success', 'Berhasil', 'Data Berhasil Disimpan');
                    } else {
                        notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                    }
                } else {
                    notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                    $.each(data.errors, function(key, value) {
                        $('#form-ubah [name="' + key + '"]').parents(".form-group").removeClass('has-success');
                        $('#form-ubah [name="' + key + '"]').parents(".form-group").addClass('has-error');
                        $('#form-ubah .' + key).html(value);
                        if (value == "") {
                            $('#form-ubah [name="' + key + '"]').parents(".form-group").removeClass('has-error');
                            $('#form-ubah [name="' + key + '"]').parents(".form-group").addClass('has-success');
                        }
                    });
                }
                $('#btn-ubah').html("<i class='fa fa-save'></i> SIMPAN");
                $('#btn-ubah').attr('disabled', false);
            },
            error: function(xhr, status, msg) {
                alert('Status: ' + status + "\n" + msg);
                $('#btn-ubah').html("<i class='fa fa-save'></i> SIMPAN");
                $('#btn-ubah').attr('disabled', false);
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
                    url: '<?= site_url('delete-data-shift'); ?>',
                    type: "POST",
                    data: {
                        id: $(this).data('id')
                    },
                    success: function(data) {
                        reload_ajax();
                        if (data.notif) {
                            notifikasi('success', 'Berhasil', 'Data Berhasil Dihapus');
                        } else {
                            notifikasi('error', 'Gagal', 'Data Gagal Dihapus');
                        }
                    }
                });
            }
        })
    });
</script>
<?php $this->load->view('_partial/tag_close'); ?>