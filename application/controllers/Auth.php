<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	function index()
	{
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		$this->form_validation->set_message('required', '%s tidak boleh kosong');
		if ($this->form_validation->run() == false) {
			$this->load->view('login');
		} else {
			$this->_login();
		}
	}

	function _login()
	{
		$post = $this->input->post(null, TRUE);
		$username = htmlspecialchars($post['username']);
		$password = htmlspecialchars($post['password']);

		$user = $this->db->get_where('user_absensi', ['username' => $username])->row_array();
		if ($user) {
			if (password_verify($password, $user['password'])) {
				$data = [
					'username' => $user['username'],
					'role_admin' => $user['role_admin'],
					'sess_skpd' => encrypt_url($user['id_skpd']),
					'timeout' => time() + 1800
				];
				$this->session->set_userdata($data);

				$string = simpan_log("Login Aplikasi", "Login Sistem Informasi Kehadiran");
				$this->db->insert('log_aktivitas_user', $string);
				redirect(site_url('dashboard'));
			} else {
				$this->session->set_flashdata('flash', 'error-KONFIRMASI-PASSWORD SALAH');
				redirect(site_url());
			}
		} else {
			$this->session->set_flashdata('flash', 'error-KONFIRMASI-USERNAME TIDAK TERDAFTAR');
			redirect(site_url());
		}
	}

	public function logout()
	{
		//$this->session->sess_destroy();
		$this->session->unset_userdata('role_admin');
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('timeout');
		$this->session->set_flashdata('flash', 'success-KONFIRMASI-ANDA SUDAH KELUAR DARI APLIKASI');
		redirect(site_url('auth'));
	}
}
