<!-- Sidebar -->
<div class="sidebar sidebar-style-2">

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="<?= base_url("uploads/users/" . $foto); ?>" alt="users" class="avatar-img rounded-circle">
                </div>
                <div class="info">
                    <a href="<?= site_url('profile-user'); ?>">
                        <span>
                            <?= $nama; ?>
                            <span class="user-level"><?= $role; ?></span>
                        </span>
                    </a>
                </div>
            </div>
            <ul class="nav nav-primary">
                <li class="nav-item <?php if ($menu == "menu_dashboard") {
                                        echo "active";
                                    } ?>">
                    <a href="<?= site_url("dashboard"); ?>">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item <?php if ($menu == "menu_pegawai") {
                                        echo "active";
                                    } ?>">
                    <a href="<?= site_url("data-pegawai"); ?>">
                        <i class="fas fa-users"></i>
                        <p>Pegawai</p>
                    </a>
                </li>

                <li class="nav-item <?php if ($menu == "menu_tenaga_kontrak") {
                                        echo "active";
                                    } ?>">
                    <a href="<?= site_url("tenaga-kontrak"); ?>">
                        <i class="fas fa-users"></i>
                        <p>Tenaga Kontrak</p>
                    </a>
                </li>

                <li class="nav-item <?php if ($menu == "menu_kehadiran") {
                                        echo "active submenu";
                                    } ?>">
                    <a data-toggle="collapse" href="#kehadiran" class="collapsed" aria-expanded="false">
                        <i class="fas fa-th-large"></i>
                        <p>Kehadiran</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?php if ($menu == "menu_kehadiran") {
                                                echo "show";
                                            } ?>" id="kehadiran">
                        <ul class="nav nav-collapse">
                            <li <?php if ($submenu == "submenu_kehadiran_pegawai") {
                                    echo "class='active'";
                                } ?>>
                                <a href="<?= base_url("kehadiran-pegawai"); ?>">
                                    <span class="sub-item">Pegawai</span>
                                </a>
                            </li>
                            <li <?php if ($submenu == "submenu_kehadiran_tenaga_kontrak") {
                                    echo "class='active'";
                                } ?>>
                                <a href="<?= base_url("kehadiran-tenaga-kontrak"); ?>">
                                    <span class="sub-item">Tenaga Kontrak</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item <?php if ($menu == "menu_rekap1") {
                                        echo "active submenu";
                                    } ?>">
                    <a data-toggle="collapse" href="#rekap-kehadiran-1" class="collapsed" aria-expanded="false">
                        <i class="fas fa-file"></i>
                        <p>Rekap Kehadiran I</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?php if ($menu == "menu_rekap1") {
                                                echo "show";
                                            } ?>" id="rekap-kehadiran-1">
                        <ul class="nav nav-collapse">
                            <li <?php if ($submenu == "submenu_rekap1_kehadiran_pegawai") {
                                    echo "class='active'";
                                } ?>>
                                <a href="<?= base_url("rekap1-kehadiran-pegawai"); ?>">
                                    <span class="sub-item">Pegawai</span>
                                </a>
                            </li>
                            <li <?php if ($submenu == "submenu_rekap1_kehadiran_tenaga_kontrak") {
                                    echo "class='active'";
                                } ?>>
                                <a href="<?= base_url("rekap1-kehadiran-tenaga-kontrak"); ?>">
                                    <span class="sub-item">Tenaga Kontrak</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item <?php if ($menu == "menu_rekap2") {
                                        echo "active submenu";
                                    } ?>">
                    <a data-toggle="collapse" href="#rekap-kehadiran-2" class="collapsed" aria-expanded="false">
                        <i class="fas fa-file"></i>
                        <p>Rekap Kehadiran II</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?php if ($menu == "menu_rekap2") {
                                                echo "show";
                                            } ?>" id="rekap-kehadiran-2">
                        <ul class="nav nav-collapse">
                            <li <?php if ($submenu == "submenu_rekap2_kehadiran_pegawai") {
                                    echo "class='active'";
                                } ?>>
                                <a href="<?= base_url("rekap2-kehadiran-pegawai"); ?>">
                                    <span class="sub-item">Pegawai</span>
                                </a>
                            </li>
                            <li <?php if ($submenu == "submenu_rekap2_kehadiran_tenaga_kontrak") {
                                    echo "class='active'";
                                } ?>>
                                <a href="<?= base_url("rekap2-kehadiran-tenaga-kontrak"); ?>">
                                    <span class="sub-item">Tenaga Kontrak</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item <?php if ($menu == "menu_izin") {
                                        echo "active submenu";
                                    } ?>">
                    <a data-toggle="collapse" href="#rekap-izin" class="collapsed" aria-expanded="false">
                        <i class="fas fa-list-alt"></i>
                        <p>Izin/Sakit</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?php if ($menu == "menu_izin") {
                                                echo "show";
                                            } ?>" id="rekap-izin">
                        <ul class="nav nav-collapse">
                            <li <?php if ($submenu == "submenu_cuti") {
                                    echo "class='active'";
                                } ?>>
                                <a href="<?= base_url("pegawai-cuti"); ?>">
                                    <span class="sub-item">Cuti</span>
                                </a>
                            </li>
                            <li <?php if ($submenu == "submenu_izin") {
                                    echo "class='active'";
                                } ?>>
                                <a href="<?= base_url("pegawai-izin"); ?>">
                                    <span class="sub-item">Izin</span>
                                </a>
                            </li>
                            <li <?php if ($submenu == "submenu_sakit") {
                                    echo "class='active'";
                                } ?>>
                                <a href="<?= base_url("pegawai-sakit"); ?>">
                                    <span class="sub-item">Sakit</span>
                                </a>
                            </li>
                            <li <?php if ($submenu == "submenu_tugas_luar") {
                                    echo "class='active'";
                                } ?>>
                                <a href="<?= base_url("pegawai-tugas-luar"); ?>">
                                    <span class="sub-item">Tugas Luar</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item <?php if ($menu == "menu_kehadiran_manual") {
                                        echo "active";
                                    } ?>">
                    <a href="<?= site_url("kehadiran-manual"); ?>">
                        <i class="fas fa-th"></i>
                        <p>Kehadiran Manual</p>
                    </a>
                </li>

                <li class="nav-item <?php if ($menu == "menu_hari_libur") {
                                        echo "active";
                                    } ?>">
                    <a href="<?= site_url("hari-libur"); ?>">
                        <i class="fas fa-calendar"></i>
                        <p>Hari Libur</p>
                    </a>
                </li>

                <li class="nav-item <?php if ($menu == "acara_kegiatan") {
                                        echo "active";
                                    } ?>">
                    <a href="<?= site_url("acara-kegiatan"); ?>">
                        <i class="fa fa-bars"></i>
                        <p>Acara Dan Kegiatan</p>
                    </a>
                </li>

                <li class="nav-item <?php if ($menu == "menu_shift") {
                                        echo "active submenu";
                                    } ?>">
                    <a data-toggle="collapse" href="#shift" class="collapsed" aria-expanded="false">
                        <i class="fas fa-clipboard-list"></i>
                        <p>Data Shift</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?php if ($menu == "menu_shift") {
                                                echo "show";
                                            } ?>" id="shift">
                        <ul class="nav nav-collapse">
                            <li <?php if ($submenu == "submenu_shift") {
                                    echo "class='active'";
                                } ?>>
                                <a href="<?= base_url("data-shift"); ?>">
                                    <span class="sub-item">Data Shift</span>
                                </a>
                            </li>
                            <li <?php if ($submenu == "submenu_shift_pegawai") {
                                    echo "class='active'";
                                } ?>>
                                <a href="<?= base_url("data-shift-pegawai"); ?>">
                                    <span class="sub-item">Data Shift Pegawai</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item <?php if ($menu == "menu_tpp") {
                                        echo "active";
                                    } ?>">
                    <a href="<?= site_url("tpp-kehadiran"); ?>">
                        <i class="fas fa-donate"></i>
                        <p>TPP Kehadiran</p>
                    </a>
                </li>

                <?php if ($this->session->userdata('role_admin') == "admin") : ?>
                    <li class="nav-item <?php if ($menu == "menu_user_unit_kerja") {
                                            echo "active";
                                        } ?>">
                        <a href="<?= site_url("user-unit-kerja"); ?>">
                            <i class="fas fa-sitemap"></i>
                            <p>User Unit Kerja</p>
                        </a>
                    </li>

                    <li class="nav-item <?php if ($menu == "menu_lokasi_skpd") {
                                            echo "active";
                                        } ?>">
                        <a href="<?= site_url("lokasi-skpd"); ?>">
                            <i class="fas fa-map-marker-alt"></i>
                            <p>Lokasi SKPD</p>
                        </a>
                    </li>
                <?php endif; ?>

                    <li class="nav-item <?php if ($menu == "logout") {
                                            echo "active";
                                        } ?>">
                        <a href="<?= site_url('logout'); ?>">
                            <i class="fas fa-power-off"></i>
                            <p>Logout</p>
                        </a>
                    </li>
            </ul>
        </div>
    </div>
</div>