<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sistem Informasi Kehadiran</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="all,follow">

	<link rel="stylesheet" href="<?= base_url('assets/login/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/login/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/login/css/style.green.css'); ?>" id="theme-stylesheet">
	<link id="new-stylesheet" rel="stylesheet">
	<link rel="stylesheet" href="<?= base_url('assets/login/css/custom.css'); ?>">
	<link rel="shortcut icon" type="image/x-icon" href="<?= base_url('images/icon-labusel.ico'); ?>">
	<style>
		#load {
			width: 100%;
			height: 100%;
			position: fixed;
			text-indent: 100%;
			background: #e0e0e0 url(<?= base_url(); ?>images/ring_green.gif) no-repeat center;
			z-index: 1000;
			opacity: 0.7;
			background-size: 8%;
		}
	</style>

</head>

<body>
	<div class="page login-page">
		<div id="load"></div>

		<div class="container d-flex align-items-center">
			<div class="form-holder">
				<div class="row has-shadow">
					<div class="col-lg-6">
						<div class="info d-flex align-items-center">
							<div class="content">
								<div class="logo">
									<center><img src="<?= base_url('images/logo-labusel.png'); ?>" class="img-fluid img-logo" width="200px"></center>
								</div>
								<br>
								<p class="text-center">SISTEM ABSENSI ONLINE</p>
								<p class="text-center" style="font-weight: bold;">PEMERINTAH KABUPATEN LABUHANBATU SELATAN</p>
							</div>
						</div>
					</div>

					<div class="col-lg-6 bg-white">
						<div class="form d-flex align-items-center">
							<div class="content">
								<?= form_open(); ?>
								<div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
								<div class="form-group">
									<input type="text" name="username" id="username" class="input-material" value="<?= set_value('username'); ?>" autofocus="" autocomplete="off">
									<label for="username" class="label-material">Username</label>
									<?= form_error('username', '<small class="form-text text-danger">', '</small>'); ?>
								</div>
								<div class="form-group">
									<input type="password" name="password" id="password" class="input-material" value="<?= set_value('password'); ?>">
									<label for="password" class="label-material">Password</label>
									<?= form_error('password', '<small class="form-text text-danger">', '</small>'); ?>
								</div>
								<div id="error"></div>
								<button type="submit" class="btn btn-primary" id="btnLogin">Login</button>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="text-center" style="color: white; margin-top: 15px;">
					<strong>Copyright &copy; <?= "2022"; ?> Sistem Informasi Kehadiran</strong> Pemerintah Kabupaten Labuhanbatu Selatan
				</div>
			</div>
		</div>
	</div>
	<!-- Javascript files-->
	<script src="<?= base_url(); ?>assets/login/js/jquery-3.2.1.min.js"></script>
	<script src="<?= base_url(); ?>assets/login/js/popper.min.js"> </script>
	<script src="<?= base_url(); ?>assets/login/js/bootstrap.min.js"></script>
	<script src="<?= base_url(); ?>assets/js/sweetalert2.all.min.js"></script>
	<script src="<?= base_url(); ?>assets/login/js/jquery.cookie.js"> </script>
	<script src="<?= base_url(); ?>assets/login/js/jquery.validate.min.js"></script>
	<script src="<?= base_url(); ?>assets/login/js/front.js"></script>

	<script>
		$(document).ready(function() {
			$("#load").fadeOut();
			$('#username').focus();
		});

		const flashData = $('.flash-data').data('flashdata');
		notifikasi = flashData.split('-');
		if (flashData != '') {
			Swal.fire({
				icon: notifikasi[0],
				title: notifikasi[1],
				text: notifikasi[2],
				timer: 1500
			})
		}
	</script>
</body>

</html>