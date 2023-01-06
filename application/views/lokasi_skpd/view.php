<?php $this->load->view("_partial/header"); ?>
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white pb-3 fw-bold">LOKASI UNIT KERJA</h3>
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
                                        <th style="width: 40%;">UNIT KERJA</th>
                                        <th>KOORDINAT</th>
                                        <th>RADIUS</th>
                                        <th>SENIN -<br> KAMIS</th>
                                        <th>JUM'AT</th>
                                        <th>SABTU</th>
                                        <th>MENGIKUTI<br>KEGIATAN</th>
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
                url: '<?= site_url('load-lokasi-skpd'); ?>',
                type: 'POST'
            },
            columns: [{
                data: 'no'
            }, {
                data: 'unit_kerja'
            }, {
                data: 'latitude'
            }, {
                data: 'radius'
            }, {
                data: 'jadwal1'
            }, {
                data: 'jadwal2'
            }, {
                data: 'jadwal3'
            }, {
                data: 'kegiatan'
            }, {
                data: 'aksi'
            }],
        });
    }

    function reload_ajax() {
        table.ajax.reload(null, false);
    }

    //==========================================================
    $(document).on("click", "#tombol-ubah", function() {
        $.ajax({
            url: '<?= site_url('form-edit-lokasi-skpd'); ?>',
            type: "POST",
            data: {
                unit_kerja: $(this).data('id')
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
            url: "<?= site_url('edit-lokasi-skpd'); ?>",
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
</script>
<?php $this->load->view('_partial/tag_close'); ?>