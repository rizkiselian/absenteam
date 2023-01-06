<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['logout'] = 'auth/logout';

$route['load-dashboard/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'dashboard/loadDashboard/$1/$2';

// ================================ MODULE PEGAWAI ====================================================
$route['data-pegawai'] = 'pegawai';
$route['load-pegawai/([0-9a-zA-Z=_-]+)'] = 'pegawai/loadPegawai/$1';
$route['load-pegawai-by-skpd'] = 'pegawai/loadPegawaiBySkpd';

$route['koordinat-personal/([0-9a-zA-Z=_-]+)'] = 'pegawai/koordinat/$1';
$route['koordinat-personal-update/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'pegawai/koordinat_update/$1/$2';
$route['koordinat-personal-load/load'] = 'pegawai/koordinat_load';
$route['form-ubah-koordinat-personal'] = 'pegawai/form_ubah_koordinat_load';
$route['ubah-koordinat-personal'] = 'pegawai/ubah_koordinat_load';

// ================================ MODULE TENAGA KONTRAK =============================================
$route['tenaga-kontrak'] = 'tenaga_kontrak';
$route['load-tenaga-kontrak/([0-9a-zA-Z=_-]+)'] = 'tenaga_kontrak/loadTenagaKontrak/$1';

// ================================ MODULE KEHADIRAN ==================================================
$route['kehadiran-pegawai'] = 'kehadiran/pegawai';
$route['load-pegawai-hadir/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'kehadiran/loadPegawaiHadir/$1/$2';
$route['load-pegawai-tidak-hadir/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'kehadiran/loadPegawaiTidakHadir/$1/$2';
$route['kehadiran-tenaga-kontrak'] = 'kehadiran/tenagaKontrak';
$route['load-tenaga-kontrak-hadir/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'kehadiran/loadTenagaKontrakHadir/$1/$2';
$route['load-tenaga-kontrak-tidak-hadir/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'kehadiran/loadTenagaKontrakTidakHadir/$1/$2';
$route['detail-kehadiran-pegawai/([0-9a-zA-Z=_-]+)'] = 'kehadiran/detailKehadiran/$1';
$route['load-detail-kehadiran-pegawai/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'kehadiran/loadDetailKehadiran/$1/$2/$3';

// ================================ MODULE REKAP 1 KEHADIRAN ============================================
$route['rekap1-kehadiran-pegawai'] = 'rekap/rekap1Pegawai';
$route['load-rekap1-kehadiran-pegawai/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'rekap/loadRekap1Pegawai/$1/$2/$3';
$route['rekap1-kehadiran-tenaga-kontrak'] = 'rekap/rekap1TenagaKontrak';
$route['load-rekap1-kehadiran-tenaga-kontrak/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'rekap/loadRekap1TenagaKontrak/$1/$2/$3';

// ================================ MODULE REKAP 2 KEHADIRAN ============================================
$route['rekap2-kehadiran-pegawai'] = 'rekap/rekap2Pegawai';
$route['load-rekap2-kehadiran-pegawai/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'rekap/loadRekap2Pegawai/$1/$2/$3';
$route['rekap2-kehadiran-tenaga-kontrak'] = 'rekap/rekap2TenagaKontrak';
$route['load-rekap2-kehadiran-tenaga-kontrak/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'rekap/loadRekap2TenagaKontrak/$1/$2/$3';

// ==================================== MODULE STATUS ABSENSI ===========================================
// load pegawai untuk semua keterangan absensi (cuti/izin/sakit/tugas luar)
$route['load-status-absensi/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'status_absensi/loadStatusAbsensi/$1/$2';
$route['cek-jabatan-pegawai'] = 'status_absensi/cekJabatanPegawai';
$route['pegawai-cuti'] = 'cuti/loadData';
$route['form-add-pegawai-cuti'] = 'cuti/formAddData';
$route['add-pegawai-cuti'] = 'cuti/addData';
$route['delete-pegawai-cuti'] = 'cuti/deleteData';
$route['pegawai-izin'] = 'izin/loadData';
$route['form-add-pegawai-izin'] = 'izin/formAddData';
$route['add-pegawai-izin'] = 'izin/addData';
$route['delete-pegawai-izin'] = 'izin/deleteData';
$route['pegawai-sakit'] = 'sakit/loadData';
$route['form-add-pegawai-sakit'] = 'sakit/formAddData';
$route['add-pegawai-sakit'] = 'sakit/addData';
$route['delete-pegawai-sakit'] = 'sakit/deleteData';
$route['pegawai-tugas-luar'] = 'tl/loadData';
$route['form-add-pegawai-tugas-luar'] = 'tl/formAddData';
$route['add-pegawai-tugas-luar'] = 'tl/addData';
$route['delete-pegawai-tugas-luar'] = 'tl/deleteData';

// ================================ MODULE HARI LIBUR =================================================
$route['hari-libur'] = 'hari_libur';
$route['load-hari-libur'] = 'hari_libur/loadData';
$route['form-add-hari-libur'] = 'hari_libur/formAddData';
$route['add-hari-libur'] = 'hari_libur/addData';
$route['form-edit-hari-libur'] = 'hari_libur/formEditData';
$route['edit-hari-libur'] = 'hari_libur/editData';
$route['delete-hari-libur'] = 'hari_libur/deleteData';

// ================================ MODULE ACARA KEGIATAN =================================================
$route['acara-kegiatan'] = 'acara_kegiatan';
$route['load-acara-kegiatan'] = 'acara_kegiatan/loadData';
$route['form-add-acara-kegiatan'] = 'acara_kegiatan/formAddData';
$route['add-acara-kegiatan'] = 'acara_kegiatan/addData';
$route['form-edit-acara-kegiatan'] = 'acara_kegiatan/formEditData';
$route['edit-acara-kegiatan'] = 'acara_kegiatan/editData';
$route['delete-acara-kegiatan'] = 'acara_kegiatan/deleteData';

// ================================ MODULE ACARA KEGIATAN =================================================
$route['data-shift'] = 'shift/data_shift';
$route['load-data-shift'] = 'shift/data_shift/loadData';
$route['form-add-data-shift'] = 'shift/data_shift/formAddData';
$route['add-data-shift'] = 'shift/data_shift/addData';
$route['form-edit-data-shift'] = 'shift/data_shift/formEditData';
$route['edit-data-shift'] = 'shift/data_shift/editData';
$route['delete-data-shift'] = 'shift/data_shift/deleteData';

// ================================ MODULE ACARA KEGIATAN =================================================
$route['data-shift-pegawai'] = 'shift/data_shift_pegawai';
$route['load-data-shift-pegawai'] = 'shift/data_shift_pegawai/loadData';
$route['form-add-data-shift-pegawai'] = 'shift/data_shift_pegawai/formAddData';
$route['add-data-shift-pegawai'] = 'shift/data_shift_pegawai/addData';
$route['form-edit-data-shift-pegawai'] = 'shift/data_shift_pegawai/formEditData';
$route['edit-data-shift-pegawai'] = 'shift/data_shift_pegawai/editData';
$route['delete-data-shift-pegawai'] = 'shift/data_shift_pegawai/deleteData';

// ================================ MODULE TPP KEHADIRAN ==============================================
$route['tpp-kehadiran'] = 'tpp';
$route['load-tpp-kehadiran/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'tpp/loadTpp/$1/$2/$3';

// ================================ MODULE LOKASI SKPD =================================================
$route['lokasi-skpd'] = 'skpd';
$route['load-lokasi-skpd'] = 'skpd/loadData';
$route['form-edit-lokasi-skpd'] = 'skpd/formEditData';
$route['edit-lokasi-skpd'] = 'skpd/editData';

// ================================ MODULE KEHADIRAN MANUAL =================================================
$route['kehadiran-manual'] = 'kehadiran_manual';
$route['load-kehadiran-manual/([0-9a-zA-Z=_-]+)'] = 'kehadiran_manual/loadData/$1';
$route['form-add-kehadiran-manual'] = 'kehadiran_manual/formAddData';
$route['add-kehadiran-manual'] = 'kehadiran_manual/addData';
$route['delete-kehadiran-manual'] = 'kehadiran_manual/deleteData';

// ================================ MODULE USER =======================================================
$route['user-unit-kerja'] = 'user';
$route['load-user-unit-kerja'] = 'user/loadData';
$route['form-add-user-unit-kerja'] = 'user/formAddData';
$route['add-user-unit-kerja'] = 'user/addData';
$route['form-edit-user-unit-kerja'] = 'user/formEditData';
$route['edit-user-unit-kerja'] = 'user/editData';
$route['delete-user-unit-kerja'] = 'user/deleteData';

$route['profile-user'] = 'user/profile';
$route['form-upload-foto'] = 'user/formUploadFoto';
$route['upload-foto'] = 'user/uploadFoto';
$route['ubah-password'] = 'user/changePassword';
$route['form-change-password'] = 'user/formChangePassword';
$route['change-password'] = 'user/changePassword';

$route['cetak-kehadiran-pegawai/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'cetak/kehadiran_pegawai/$1/$2';
$route['cetak-kehadiran-detail/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'cetak/kehadiran_detail/$1/$2/$3';
$route['cetak-kehadiran-rekap1/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'cetak/kehadiran_rekap1/$1/$2/$3';
$route['cetak-kehadiran-rekap2/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'cetak/kehadiran_rekap2/$1/$2/$3';
$route['cetak-kehadiran-tpp/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)/([0-9a-zA-Z=_-]+)'] = 'cetak/kehadiran_tpp/$1/$2/$3';

$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/cekLoginService'] = 'api/integrasi/cekLoginService';
$route['api/getDetailPegawaiService'] = 'api/integrasi/getDetailPegawaiService';
$route['api/getKehadiranByTanggalService'] = 'api/integrasi/getKehadiranByTanggalService';
$route['api/pushAbsenMasukService'] = 'api/integrasi/pushAbsenMasukService';
$route['api/pushAbsenPulangService'] = 'api/integrasi/pushAbsenPulangService';

$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
