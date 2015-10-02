<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Controller extends CI_Controller {
	
	protected $logout_url, $login_url;

	public function __construct()
	{
		parent::__construct();

		$loginId = $this->session->userdata('id');
		$member_id = $loginId ? $loginId : '0';
		$uri = $this->uri->uri_string();

		$sessLang = $this->session->userdata('bahasa') ? $this->session->userdata('bahasa') : "indonesia";
		$this->lang->load('language', $sessLang);

		$this->db->where('tanggal < (NOW() - INTERVAL 48 HOUR)')->delete('log_access2');
		$this->db->where('dt_datetime < (NOW() - INTERVAL 24 HOUR)')->delete('proyek_history');

		if(($uri != 'profile/getMessages') && (strpos($uri,'images/details_open.png') != true) && (strpos($uri,'assets/img/') != true)){
			$this->db->insert('log_access2', array('member_id' => $member_id, 'segments' => $uri, 'tanggal' => date("Y-m-d H:i:s")));
		}
	}

	function _facebook(){
		$user = $this->facebook->getUser();
		if ($user) {
			$this->logout_url = site_url('member/logout');
		} else {
			$this->login_url = $this->facebook->getLoginUrl(array(
				'redirect_uri' => site_url('member/register_facebook'), 
                'scope' => array("email") // permissions here
                ));
		}
	}
	function _remember_me(){
		if ($this->input->cookie('remember_me')) {
			$getMember = $this->model->get_where('member',array('email'=>$this->input->cookie('remember_me')));
			$newdata = array(
				'email'  => @$getMember[0]['email'],
				'phone'  => @$getMember[0]['phone'],
				'logged_in' => TRUE,
				);
			$this->session->set_userdata($newdata);
		}	
	}
}
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */