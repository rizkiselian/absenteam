<?php $this->load->view("_partial/header"); ?>
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <h3 class="text-white pb-3 fw-bold">DATA TENAGA KONTRAK PER UNIT KERJA</h3>
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
                                        <th>NAMA</th>
                                        <th>JABATAN</th>
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
                    url: '<?= site_url('load-tenaga-kontrak/'); ?>' + skpd,
                    type: 'POST'
                },
                columns: [{
                    data: 'no'
                }, {
                    data: 'nama'
                }, {
                    data: 'jabatan'
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
                    url: '<?= site_url('load-tenaga-kontrak/'); ?>' + skpd,
                    type: 'POST'
                },
                columns: [{
                    data: 'no'
                }, {
                    data: 'nama'
                }, {
                    data: 'jabatan'
                }],
            });
        }
    </script>
<?php endif; ?>
<?php $this->load->view('_partial/tag_close'); ?>