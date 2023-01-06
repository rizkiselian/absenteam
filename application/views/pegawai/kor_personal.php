<?php 
    $this->load->view("_partial/header"); 
?>
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white pb-3 fw-bold">KOORDINAT PERSONAL PEGAWAI : <?= format_nama($pegawai['gelar_depan'], $pegawai['nama_pegawai'], $pegawai['gelar_belakang']); ?></h3>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card full-height">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                <button id="tombol-tambah" class="btn btn-secondary btn-round btn-sm mr-2 mb-3" data-toggle="modal" data-target="#modal-tambah">EDIT DATA</button>
                    <div class="preload_personal" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                        <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                        <h5>Sedang memuat data...</h5>
                    </div>
                    <div id="load-kor-personal" style="display: none;"></div>
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

<?php $this->load->view('_partial/footer'); ?>
<script>
    $(document).ready(function() {
            load_data_personal();
        });

    function load_data_personal() {
            $('#load-kor-personal').css('display', 'none');
            $('.preload_personal').show();
            $.ajax({
                url: '<?= site_url('koordinat-personal-load/load'); ?>',
                type: "POST",
                data: {
                    id_pegawai : <?=$pegawai['id_pegawai']?>
                },
                dataType: "html",
                success: function(data) {
                    $('#load-kor-personal').css('display', 'block');
                    $('.preload_personal').hide();
                    $('#load-kor-personal').html(data);
                }
            });
        }

        // ====================================================================================
        $(document).on("click", "#tombol-tambah", function() {
            $(this).attr('disabled', true);
            $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
            $.ajax({
                url: '<?= site_url('form-ubah-koordinat-personal'); ?>',
                type: "POST",
                data: {
                    id_pegawai : <?=$pegawai['id_pegawai']?>
                },
                success: function(data) {
                    $('#load-form-tambah').html(data);
                    $('#tombol-tambah').html("<i class='fa fa-plus'></i> EDIT DATA");
                    $('#tombol-tambah').attr('disabled', false);
                }
            });
        });

        $(document).on("click", "#btn-simpan", function() {
            $(this).attr('disabled', true);
            $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
            $.ajax({
                url: "<?= site_url('ubah-koordinat-personal'); ?>",
                type: 'POST',
                data: $('#form-tambah').serialize(),
                dataType: "json",
                success: function(data) {
                    if (data.status) {
                        $('#modal-tambah').modal('hide');
                        load_data_personal();
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
</script>
<?php $this->load->view('_partial/tag_close'); ?>