<?php

function is_logged_in()
{
	$ci = get_instance();
	if (!$ci->session->userdata('username')) {
		redirect(site_url());
	} else {
		$timeout = $ci->session->userdata('timeout');
		if (time() < $timeout) {
			$ci->session->set_userdata('timeout', time() + 9000);
		} else {
			redirect(site_url());
		}
	}
}

function detail_user()
{
	$ci = get_instance();
	$ci->db->select('user_absensi.*, a.nama_pegawai, c.nama_skpd');
	$ci->db->from('user_absensi');
	$ci->db->join('kepegawaian.pegawai as a', 'user_absensi.id_pegawai = a.id_pegawai');
	$ci->db->join('kepegawaian.pegawai_posisi as b', 'a.id_pegawai = b.id_pegawai', 'LEFT');
	$ci->db->join('kepegawaian.skpd as c', 'b.id_skpd = c.id_skpd');
	$ci->db->where('user_absensi.username', $ci->session->userdata('username'));
	$users = $ci->db->get()->row_array();

	($users['role_admin'] == 'admin') ? $role = "Admin Master" : $role = "Admin SKPD";
	($users['foto_profile'] == "") ? $foto = "admin.png" : $foto = $users['foto_profile'];
	$params = [
		'nama' => $users['nama_pegawai'],
		'role' => $role,
		'skpd' => $users['nama_skpd'],
		'foto' => $foto
	];
	return $params;
}

function encrypt_url($string)
{
	$output = false;
	$security       = parse_ini_file("security.ini");
	$secret_key     = $security["encryption_key"];
	$secret_iv      = $security["iv"];
	$encrypt_method = $security["encryption_mechanism"];
	$key    = hash("sha256", $secret_key);
	$iv     = substr(hash("sha256", $secret_iv), 0, 16);
	$result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	$output = base64_encode($result);
	return $output;
}

function decrypt_url($string)
{
	$output = false;
	$security       = parse_ini_file("security.ini");
	$secret_key     = $security["encryption_key"];
	$secret_iv      = $security["iv"];
	$encrypt_method = $security["encryption_mechanism"];
	$key    = hash("sha256", $secret_key);
	$iv = substr(hash("sha256", $secret_iv), 0, 16);
	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	return $output;
}
