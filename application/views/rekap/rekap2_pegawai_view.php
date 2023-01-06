<?php $this->load->view("_partial/header"); ?>
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <h3 class="text-white pb-3 fw-bold">REKAP 2 KEHADIRAN PEGAWAI PER UNIT KERJA</h3>
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
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <select name="skpd" id="skpd" class="form-control select2" style="width: 100%;">
                                        <?php foreach ($result_skpd as $r) : ?>
                                            <option value="<?= encrypt_url($r['id_skpd']); ?>"><?= $r['nama_skpd']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-3 mb-3">
                                    <select name="bulan" id="bulan" class="form-control select2" style="width: 100%;">
                                        <?php $bulanSekarang = date('m'); ?>
                                        <?php foreach ($result_bulan as $r) : ?>
                                            <?php if ($r['id_bulan'] == $bulanSekarang) : ?>
                                                <option value="<?= $r['id_bulan']; ?>" selected><?= strtoupper($r['nama_bulan']); ?></option>
                                            <?php else : ?>
                                                <option value="<?= $r['id_bulan']; ?>"><?= strtoupper($r['nama_bulan']); ?></option><?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-3 mb-3">
                                    <select name="tahun" id="tahun" class="form-control select2" style="width: 100%;">
                                        <?php
                                        for ($tahun = date('Y'); $tahun >= 2021; $tahun--) :
                                        ?>
                                            <option value="<?= $tahun ?>"><?= $tahun; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <button type="button" id="tombol-filter" class="btn btn-primary" style="padding-bottom: 5px;"> <i class="fa fa-search"></i> TAMPILKAN</button>
                                    <button type="button" id="tombol-cetak" class="btn btn-danger" style="padding-bottom: 5px;"> <i class="fa fa-print"></i> CETAK</button>
                                </div>
                            </div>
                        </fieldset>
                    <?php else : ?>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">FILTER</legend>
                            <div class="row p-2">
                                <div class="col-md-3 mb-3">
                                    <select name="bulan" id="bulan" class="form-control select2" style="width: 100%;">
                                        <?php $bulanSekarang = date('m'); ?>
                                        <?php foreach ($result_bulan as $r) : ?>
                                            <?php if ($r['id_bulan'] == $bulanSekarang) : ?>
                                                <option value="<?= $r['id_bulan']; ?>" selected><?= strtoupper($r['nama_bulan']); ?></option>
                                            <?php else : ?>
                                                <option value="<?= $r['id_bulan']; ?>"><?= strtoupper($r['nama_bulan']); ?></option><?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <select name="tahun" id="tahun" class="form-control select2" style="width: 100%;">
                                        <?php
                                        for ($tahun = date('Y'); $tahun >= 2021; $tahun--) :
                                        ?>
                                            <option value="<?= $tahun ?>"><?= $tahun; ?></option>
                                        <?php endfor; ?>
                                    </select>
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
                    <div class="preload" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                        <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                        <h5>Sedang memuat data...</h5>
                    </div>
                    <div id="load-data-tabel" style="display: none;">
                        <div id="load-kehadiran"></div>
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
            var skpd = $("#skpd").val();
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            load_data(skpd, bulan, tahun);
        });

        function load_data(skpd, bulan, tahun) {
            $.ajax({
                url: '<?= site_url('load-rekap2-kehadiran-pegawai/'); ?>' + skpd + '/' + bulan + '/' + tahun,
                type: 'POST',
                beforeSend: function() {
                    $('#load-data-tabel').css('display', 'none');
                    $('.preload').show();
                },
                success: function(data) {
                    $('#load-kehadiran').html(data);
                    $('#load-data-tabel').css('display', 'block');
                    $('.preload').hide();
                    $('#tombol-filter').html("<i class='fa fa-search'></i> TAMPILKAN");
                    $('#tombol-filter').attr('disabled', false);
                },
                error: function(xhr, status, msg) {
                    alert('Status: ' + status + "\n" + msg);
                }
            });

        }

        $(document).on("click", "#tombol-filter", function() {
            $('#tombol-filter').attr('disabled', true);
            $('#tombol-filter').html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
            var skpd = $("#skpd").val();
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            load_data(skpd, bulan, tahun);
        });

        $(document).on("click", "#tombol-cetak", function() {
            var skpd = $("#skpd").val();
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            window.open ("<?= site_url('cetak-kehadiran-rekap2/'); ?>" + skpd + "/" + tahun + "/" + bulan,"_blank");
        });
    </script>
<?php else : ?>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            var skpd = "<?= $skpd; ?>";
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            load_data(skpd, bulan, tahun);
        });

        function load_data(skpd, bulan, tahun) {
            $.ajax({
                url: '<?= site_url('load-rekap2-kehadiran-pegawai/'); ?>' + skpd + '/' + bulan + '/' + tahun,
                type: 'POST',
                beforeSend: function() {
                    $('#load-data-tabel').css('display', 'none');
                    $('.preload').show();
                },
                success: function(data) {
                    $('#load-kehadiran').html(data);
                    $('#load-data-tabel').css('display', 'block');
                    $('.preload').hide();
                    $('#tombol-filter').html("<i class='fa fa-search'></i> TAMPILKAN");
                    $('#tombol-filter').attr('disabled', false);
                },
                error: function(xhr, status, msg) {
                    alert('Status: ' + status + "\n" + msg);
                }
            });

        }

        $(document).on("click", "#tombol-filter", function() {
            $('#tombol-filter').attr('disabled', true);
            $('#tombol-filter').html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
            var skpd = "<?= $skpd; ?>";
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            load_data(skpd, bulan, tahun);
        });

        $(document).on("click", "#tombol-cetak", function() {
            var skpd = "<?= $skpd; ?>";
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            window.open ("<?= site_url('cetak-kehadiran-rekap2/'); ?>" + skpd + "/" + tahun + "/" + bulan,"_blank");
        });
    </script>
<?php endif; ?>
<?php $this->load->view('_partial/tag_close'); ?>