<?php $this->load->view("_partial/header"); ?>
<script src="<?= base_url(); ?>assets/js/highcharts.js"></script>
<script src="<?= base_url(); ?>assets/js/highcharts-3d.js"></script>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <h3 class="text-white pb-3 fw-bold">DASHBOARD APLIKASI ABSENSI</h3>
        </div>
    </div>
</div>
<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
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
                                    <button type="button" id="tombol-filter" class="btn btn-danger" style="padding-bottom: 5px;"> <i class="fa fa-print"></i> CETAK</button>
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
                                    <button type="button" id="tombol-filter" class="btn btn-danger" style="padding-bottom: 5px;"> <i class="fa fa-print"></i> CETAK</button>
                                </div>
                            </div>
                        </fieldset>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row mt--2">
                <div class="preload" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                    <div class="col-sm-12">
                        <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                        <h5>Sedang memuat data...</h5>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div id="grafik-pie-pegawai" class="load-grafik mb-3" style="display: none;"></div>
                </div>

                <div class="col-lg-6">
                    <div id="grafik-pie-honor" class="load-grafik mb-3" style="display: none;"></div>
                </div>
                <div class="col-lg-6">
                    <div id="grafik-bar-pegawai" class="load-grafik mb-3" style="display: none;"></div>
                </div>

                <div class="col-lg-6">
                    <div id="grafik-bar-honor" class="load-grafik mb-3" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('_partial/footer'); ?>
<script>
    <?php if ($role == "admin") : ?>
        $(document).ready(function() {
            $('.select2').select2();
            $('#tgl_kehadiran').datepicker({
                autoclose: true
            });
            var skpd = $("#skpd").val();
            var tgl_kehadiran = $("#tgl_kehadiran").val();
            load_data(skpd, tgl_kehadiran);
        });

        $(document).on("click", "#tombol-filter", function() {
            $(this).attr('disabled', true);
            $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
            var skpd = $("#skpd").val();
            var tgl_kehadiran = $("#tgl_kehadiran").val();
            load_data(skpd, tgl_kehadiran);
        });
    <?php else : ?>
        $(document).ready(function() {
            $('.select2').select2();
            $('#tgl_kehadiran').datepicker({
                autoclose: true
            });
            var skpd = "<?= $skpd; ?>";
            var tgl_kehadiran = $("#tgl_kehadiran").val();
            load_data(skpd, tgl_kehadiran);
        });

        $(document).on("click", "#tombol-filter", function() {
            $(this).attr('disabled', true);
            $(this).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
            var skpd = "<?= $skpd; ?>";
            var tgl_kehadiran = $("#tgl_kehadiran").val();
            load_data(skpd, tgl_kehadiran);
        });
    <?php endif; ?>

    function load_data(skpd, tgl_kehadiran) {
        $('.load-grafik').css('display', 'none');
        $('.preload').show();
        $.ajax({
            url: '<?= site_url('load-dashboard/'); ?>' + skpd + '/' + tgl_kehadiran,
            type: "POST",
            success: function(result) {
                Highcharts.chart('grafik-pie-pegawai', {
                    chart: {
                        type: 'pie',
                        options3d: {
                            enabled: true,
                            alpha: 45,
                            beta: 0
                        }
                    },
                    title: {
                        text: 'Data Kehadiran Pegawai'
                    },
                    subtitle: {
                        text: 'Tanggal ' + result.tanggal
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y} orang ({point.percentage:.1f}%)</b>'

                    },
                    credits: {
                        enabled: false
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            depth: 35,
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: <b>{point.y} orang ({point.percentage:.1f}%)</b>'
                            }
                        }
                    },
                    series: [{
                        name: 'Jumlah',
                        colorByPoint: true,
                        data: [{
                            name: 'Izin',
                            y: result.izin_pegawai
                        }, {
                            name: 'Tugas Luar',
                            y: result.tl_pegawai
                        }, {
                            name: 'Hadir',
                            y: result.hadir_pegawai
                        }, {
                            name: 'Sakit',
                            y: result.sakit_pegawai
                        }, {
                            name: 'Cuti',
                            y: result.cuti_pegawai
                        }, {
                            name: 'Absen',
                            y: result.absen_pegawai
                        }]
                    }]
                });


                Highcharts.chart('grafik-pie-honor', {
                    chart: {
                        type: 'pie',
                        options3d: {
                            enabled: true,
                            alpha: 45,
                            beta: 0
                        }
                    },
                    title: {
                        text: 'Data Kehadiran Tenaga Kontrak'
                    },
                    subtitle: {
                        text: 'Tanggal ' + result.tanggal
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y} orang ({point.percentage:.1f}%)</b>'

                    },
                    credits: {
                        enabled: false
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            depth: 35,
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: <b>{point.y} orang ({point.percentage:.1f}%)</b>'
                            }
                        }
                    },
                    series: [{
                        name: 'Jumlah',
                        colorByPoint: true,
                        data: [{
                            name: 'Izin',
                            y: result.izin_honor
                        }, {
                            name: 'Tugas Luar',
                            y: result.tl_honor
                        }, {
                            name: 'Hadir',
                            y: result.hadir_honor
                        }, {
                            name: 'Sakit',
                            y: result.sakit_honor
                        }, {
                            name: 'Cuti',
                            y: result.cuti_honor
                        }, {
                            name: 'Absen',
                            y: result.absen_honor
                        }]
                    }]
                });

                var chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'grafik-bar-pegawai',
                        type: 'column',
                        options3d: {
                            enabled: true,
                            alpha: 0,
                            beta: 0,
                            depth: 25,
                            viewDistance: 25
                        }
                    },
                    title: {
                        text: 'Grafik Kehadiran Pegawai'
                    },
                    subtitle: {
                        text: 'Tanggal ' + result.tanggal
                    },
                    plotOptions: {
                        column: {
                            depth: 25
                        }
                    },
                    yAxis: {
                        allowDecimals: false,
                        title: {
                            text: 'Jumlah Pegawai'
                        }
                    },
                    xAxis: {
                        categories: ['Status Kehadiran'],
                        crosshair: true
                    },
                    tooltip: {
                        valueSuffix: ' orang'
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                            name: 'Izin',
                            data: [result.izin_pegawai]
                        }, {
                            name: 'Tugas Luar',
                            data: [result.tl_pegawai]
                        }, {
                            name: 'Hadir',
                            data: [result.hadir_pegawai]
                        }, {
                            name: 'Sakit',
                            data: [result.sakit_pegawai]
                        },
                        {
                            name: 'Cuti',
                            data: [result.cuti_pegawai]
                        },
                        {
                            name: 'Absen',
                            data: [result.absen_pegawai]
                        }
                    ],
                });

                var chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'grafik-bar-honor',
                        type: 'column',
                        options3d: {
                            enabled: true,
                            alpha: 0,
                            beta: 0,
                            depth: 25,
                            viewDistance: 25
                        }
                    },
                    title: {
                        text: 'Grafik Kehadiran Tenaga Kontrak'
                    },
                    subtitle: {
                        text: 'Tanggal ' + result.tanggal
                    },
                    plotOptions: {
                        column: {
                            depth: 25
                        }
                    },
                    yAxis: {
                        allowDecimals: false,
                        title: {
                            text: 'Jumlah Pegawai'
                        }
                    },
                    xAxis: {
                        categories: ['Status Kehadiran'],
                        crosshair: true
                    },
                    tooltip: {
                        valueSuffix: ' orang'
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                            name: 'Izin',
                            data: [result.izin_honor]
                        }, {
                            name: 'Tugas Luar',
                            data: [result.tl_honor]
                        }, {
                            name: 'Hadir',
                            data: [result.hadir_honor]
                        }, {
                            name: 'Sakit',
                            data: [result.sakit_honor]
                        },
                        {
                            name: 'Cuti',
                            data: [result.cuti_honor]
                        },
                        {
                            name: 'Absen',
                            data: [result.absen_honor]
                        }
                    ],
                });

                $('.load-grafik').css('display', 'block');
                $('.preload').hide();
                $('#tombol-filter').html("<i class='fa fa-search'></i> TAMPILKAN");
                $('#tombol-filter').attr('disabled', false);
            }
        });
    }
</script>
<?php $this->load->view('_partial/tag_close'); ?>