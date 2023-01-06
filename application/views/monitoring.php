<?php $this->load->view("_partial/header_monitoring"); ?>
<script src="<?= base_url(); ?>assets/js/highcharts.js"></script>
<script src="<?= base_url(); ?>assets/js/highcharts-3d.js"></script>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <h3 class="text-white pb-3 fw-bold">DASHBOARD APLIKASI ABSENSI PEGAWAI</h3>
        </div>
    </div>
</div>
<div class="page-inner mt--5">
    <div class="card full-height">
        <div class="card-body">
            <div class="row">
                <?php
                    $tgl_kehadiran = date('Y-m-d');
                    $total1 = $this->master->cekCount($this->simpeg. '.pegawai_posisi',['id_jabatan!='=>0]);
                    $hadir1 = $this->master->cekCount('kehadiran',['status_hadir'=>'hadir', 'tgl_kehadiran'=>$tgl_kehadiran]);
                    $tl1 = $this->master->cekCount('kehadiran',['status_hadir'=>'tl', 'tgl_kehadiran'=>$tgl_kehadiran]);
                    $absen1=$total1-$hadir1-$tl1;
                ?>
                    <div class="col-sm-4 col-md-6">
                        <div class="card card-stats card-success card-round">
                            <div class="card-body">
                                <div class="row">
                                <div class="col-12">
                                    <p class="card-category">Pemerintahan Kabupaten Labuhanbatu Selatan</p>
                                </div>
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <?=$total1?>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <h4 class="card-title">HADIR : <?=$hadir1?></h4>
                                            <h4 class="card-title">TL : <?=$tl1?></h4>
                                            <h4 class="card-title">ABSEN : <?=$absen1?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    $result_skpd = $this->master->selectDataBy($this->simpeg. '.skpd', ['is_active'=>'Y'], 'nama_skpd ASC');
                    foreach ($result_skpd as $r) : 
                        $tgl_kehadiran = date('Y-m-d');
                        //$tgl_kehadiran = '2022-07-13';
                        $total = $this->master->cekCount($this->simpeg. '.pegawai_posisi',['id_skpd'=>$r['id_skpd'], 'id_jabatan!='=>0]);
                        $hadir = $this->master->cekCount('kehadiran',['id_skpd'=>$r['id_skpd'], 'status_hadir'=>'hadir', 'tgl_kehadiran'=>$tgl_kehadiran]);
                        $tl = $this->master->cekCount('kehadiran',['id_skpd'=>$r['id_skpd'], 'status_hadir'=>'tl', 'tgl_kehadiran'=>$tgl_kehadiran]);
                        $absen=$total-$hadir-$tl;
                        if($total!=0){
                ?>
                    <div class="col-sm-4 col-md-3">
                        <?php if($hadir!=0) { ?>
                            <?php if($hadir==1) { ?>
                                <div class="card card-stats card-warning card-round">
                            <?php } elseif($hadir==2) { ?>
                                <div class="card card-stats card-info card-round">
                            <?php } elseif($hadir==3) { ?>
                                <div class="card card-stats card-primary card-round">
                            <?php } else { ?>
                                <div class="card card-stats card-success card-round">
                            <?php } ?>
                        <?php } else { ?>
                            <div class="card card-stats card-danger card-round">
                        <?php } ?>
                            <div class="card-body">
                                <div class="row">
                                <div class="col-12">
                                    <p class="card-category"><?=$r['nama_skpd']?></p>
                                </div>
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <?=$total?>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <h4 class="card-title">HADIR : <?=$hadir?></h4>
                                            <h4 class="card-title">TL : <?=$tl?></h4>
                                            <h4 class="card-title">ABSEN : <?=$absen?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('_partial/footer'); ?>
<?php $this->load->view('_partial/tag_close'); ?>