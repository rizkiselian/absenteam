<?php $this->load->view("_partial/header"); ?>
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <h3 class="text-white pb-3 fw-bold">DATA KEHADIRAN PEGAWAI PER UNIT KERJA</h3>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card full-height">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <?php if ($role == "admin") : ?>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">FILTER UNIT KERJA</legend>
                            <div class="row p-2">
                                <div class="col-md-5 mb-3">
                                    <select name="skpd" id="skpd" class="form-control select2" style="width: 100%;">
                                        <?php foreach ($result_skpd as $r) : ?>
                                            <option value="<?= encrypt_url($r['id_skpd']); ?>"><?= $r['nama_skpd']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" name="tgl_kehadiran" id="tgl_kehadiran" value="<?= date('d-m-Y'); ?>" style="padding: 8px 15px;" data-date-format="dd-mm-yyyy" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="tombol-filter" class="btn btn-primary" style="padding-bottom: 5px;"> <i class="fa fa-search"></i> TAMPILKAN</button>
                                    <button type="button" id="tombol-cetak" class="btn btn-danger" style="padding-bottom: 5px;"> <i class="fa fa-print"></i> CETAK</button>
                                </div>
                            </div>
                        </fieldset>
                    <?php else : ?>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">FILTER</legend>
                            <div class="row justify-content-center p-2">
                                <div class="col-md-3 mb-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" name="tgl_kehadiran" id="tgl_kehadiran" value="<?= date('d-m-Y'); ?>" style="padding: 8px 15px;" data-date-format="dd-mm-yyyy" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" id="tombol-filter" class="btn btn-primary" style="padding-bottom: 5px;"> <i class="fa fa-search"></i> TAMPILKAN</button>
                                    <button type="button" id="tombol-cetak" class="btn btn-danger" style="padding-bottom: 5px;"> <i class="fa fa-print"></i> CETAK</button>
                                </div>
                            </div>
                        </fieldset>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <h3 class="mt-3 mb-1 text-success">DAFTAR PEGAWAI HADIR</h3>
                    <hr class="mt-0 mb-1">
                    <div class="preload1" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                        <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                        <h5>Sedang memuat data...</h5>
                    </div>
                    <div id="load-data-tabel1" style="display: none;">
                        <div class="table-responsive">
                            <table id="tabel-hadir" class="table-default" style="width: 100%;">
                                <thead>
                                    <tr style="background-color: #1572EB; color: white;">
                                        <th style="width: 5px;">NO</th>
                                        <th>NAMA</th>
                                        <th>NIP</th>
                                        <th>JABATAN</th>
                                        <th>JAM MASUK</th>
                                        <th>JAM KELUAR</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <h3 class="mt-5 mb-1 text-danger">DAFTAR PEGAWAI ABSEN (TUGAS LUAR/IZIN/SAKIT/TANPA KETERANGAN)</h3>
                    <hr class="mt-0 mb-1">
                    <div class="preload2" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                        <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                        <h5>Sedang memuat data...</h5>
                    </div>
                    <div id="load-data-tabel2" style="display: none;">
                        <div class="table-responsive">
                            <table id="tabel-tidak-hadir" class="table-default" style="width: 100%;">
                                <thead>
                                    <tr style="background-color: #1572EB; color: white;">
                                        <th style="width: 5px;">NO</th>
                                        <th>NAMA</th>
                                        <th>NIP</th>
                                        <th>JABATAN</th>
                                        <th>STATUS</th>
                                        <th>KETERANGAN</th>
                                        <th>BERKAS</th>
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
<?php $this->load->view('_partial/footer'); ?>
<?php if ($role == "admin") : ?>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('#tgl_kehadiran').datepicker({
                autoclose: true
            });
            var skpd = $("#skpd").val();
            var tgl_kehadiran = $("#tgl_kehadiran").val();
            load_data(skpd, tgl_kehadiran);
        });

        function load_data(skpd, tgl_kehadiran) {
            $('#load-data-tabel1').css('display', 'none');
            $('.preload1').show();
            $('#load-data-tabel2').css('display', 'none');
            $('.preload2').show();
            table = $('#tabel-hadir').DataTable({
                destroy: true,
                ordering: false,
                bAutoWidth: false,
                initComplete: function() {
                    $('#load-data-tabel1').css('display', 'block');
                    $('.preload1').hide();
                    $('#tombol-filter').html("<i class='fa fa-search'></i> TAMPILKAN");
                    $('#tombol-filter').attr('disabled', false);
                },
                ajax: {
                    url: '<?= site_url('load-pegawai-hadir/'); ?>' + skpd + '/' + tgl_kehadiran,
                    type: 'POST'
                },
                columns: [{
                    data: 'no'
                }, {
                    data: 'nama'
                }, {
                    data: 'nip'
                }, {
                    data: 'jabatan'
                }, {
                    data: 'jam_masuk'
                }, {
                    data: 'jam_keluar'
                }],
            });

            table = $('#tabel-tidak-hadir').DataTable({
                destroy: true,
                ordering: false,
                bAutoWidth: false,
                initComplete: function() {
                    $('#load-data-tabel2').css('display', 'block');
                    $('.preload2').hide();
                    $('#tombol-filter').html("<i class='fa fa-search'></i> TAMPILKAN");
                    $('#tombol-filter').attr('disabled', false);
                },
                ajax: {
                    url: '<?= site_url('load-pegawai-tidak-hadir/'); ?>' + skpd + '/' + tgl_kehadiran,
                    type: 'POST'
                },
                columns: [{
                    data: 'no'
                }, {
                    data: 'nama'
                }, {
                    data: 'nip'
                }, {
                    data: 'jabatan'
                }, {
                    data: 'status'
                }, {
                    data: 'keterangan'
                }, {
                    data: 'berkas'
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
            var tgl_kehadiran = $("#tgl_kehadiran").val();
            load_data(skpd, tgl_kehadiran);
        });

        $(document).on("click", "#tombol-cetak", function() {
            var skpd = $("#skpd").val();
            var tgl_kehadiran = $("#tgl_kehadiran").val();
            window.open ("<?= site_url('cetak-kehadiran-pegawai/'); ?>" + skpd + "/" + tgl_kehadiran,"_blank");
        });
    </script>
<?php else : ?>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('#tgl_kehadiran').datepicker({
                autoclose: true
            });
            var skpd = "<?= $skpd; ?>";
            var tgl_kehadiran = $("#tgl_kehadiran").val();
            load_data(skpd, tgl_kehadiran);
        });

        function load_data(skpd, tgl_kehadiran) {
            $('#load-data-tabel1').css('display', 'none');
            $('.preload1').show();
            $('#load-data-tabel2').css('display', 'none');
            $('.preload2').show();
            table = $('#tabel-hadir').DataTable({
                destroy: true,
                ordering: false,
                bAutoWidth: false,
                initComplete: function() {
                    $('#load-data-tabel1').css('display', 'block');
                    $('.preload1').hide();
                    $('#tombol-filter').html("<i class='fa fa-search'></i> TAMPILKAN");
                    $('#tombol-filter').attr('disabled', false);
                },
                ajax: {
                    url: '<?= site_url('load-pegawai-hadir/'); ?>' + skpd + '/' + tgl_kehadiran,
                    type: 'POST'
                },
                columns: [{
                    data: 'no'
                }, {
                    data: 'nama'
                }, {
                    data: 'nip'
                }, {
                    data: 'jabatan'
                }, {
                    data: 'jam_masuk'
                }, {
                    data: 'jam_keluar'
                }],
            });

            table = $('#tabel-tidak-hadir').DataTable({
                destroy: true,
                ordering: false,
                bAutoWidth: false,
                initComplete: function() {
                    $('#load-data-tabel2').css('display', 'block');
                    $('.preload2').hide();
                    $('#tombol-filter').html("<i class='fa fa-search'></i> TAMPILKAN");
                    $('#tombol-filter').attr('disabled', false);
                },
                ajax: {
                    url: '<?= site_url('load-pegawai-tidak-hadir/'); ?>' + skpd + '/' + tgl_kehadiran,
                    type: 'POST'
                },
                columns: [{
                    data: 'no'
                }, {
                    data: 'nama'
                }, {
                    data: 'nip'
                }, {
                    data: 'jabatan'
                }, {
                    data: 'status'
                }, {
                    data: 'keterangan'
                }, {
                    data: 'berkas'
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
            var tgl_kehadiran = $("#tgl_kehadiran").val();
            load_data(skpd, tgl_kehadiran);
        });

        $(document).on("click", "#tombol-cetak", function() {
            var skpd = "<?= $skpd; ?>";
            var tgl_kehadiran = $("#tgl_kehadiran").val();
            window.open ("<?= site_url('cetak-kehadiran-pegawai/'); ?>" + skpd + "/" + tgl_kehadiran,"_blank");
        });
    </script>
<?php endif; ?>
<?php $this->load->view('_partial/tag_close'); ?>