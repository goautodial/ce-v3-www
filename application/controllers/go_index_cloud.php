<?php
########################################################################################################
####  Name:             	go_index_cloud.php                                                  ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Rodolfo Januarius T. Manipol				            ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_index_cloud extends Controller {
	
	function index()
	{
		#$this->session->set_userdata('ua_language', 'french');	
		$this->load->helper('url');	
		$this->load->library('session');
		$this->load->model('remember_me');
		$query = $this->remember_me->checkRememberMe();
		if($query!=''){
			$data = array (
					'user_name' => $query,
					'is_logged_in' => true,
					'remember_me' => 1
				      );
			$this->session->set_userdata($data);
			#$this->session->set_userdata('ua_language', 'french');
			$remember_me = $this->input->post('remember_me');
			if ($remember_me==1) {
				$this->load->model('remember_me');
				$this->remember_me->addRememberMe($this->input->post('user_name')); 
			}
			$data['log_status'] = 'start';
			$data['go_main_cloud_content'] = 'go_main_cloud_page';
			$this->load->view('includes/go_index_cloud_template', $data);
			#redirect('go_site/go_dashboard', 'location');

		}
		else{
			$is_logged_in = $this->session->userdata('is_logged_in');
			if(!isset($is_logged_in) || $is_logged_in != true)
			{
					$data['log_status'] = 'start';
					$data['go_main_cloud_content'] = 'go_main_cloud_page';
					$this->load->view('includes/go_index_cloud_template', $data);
			}
			else
			{
					$data['log_status'] = 'start';
					$data['go_main_cloud_content'] = 'go_main_cloud_page';
					$this->load->view('includes/go_index_cloud_template', $data);
			}		
		}
	}

	function go_vtiger()
	{
		$data['go_main_cloud_content'] = 'go_vtigercrm';
		$data['bannertitle'] = 'vtigerCRM';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['theme'] = $this->session->userdata('go_theme');		
		$this->load->view('includes/go_noadmin_template', $data);
	}	
		
	function logout()
	{	 
		#$remember_me = $this->session->userdata('remember_me');
		#if ($remember_me=='') {
			$this->load->model('remember_me');
			$this->remember_me->removeRememberMe($this->input->post('user_name')); 
		#	} 
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache"); 
		#$this->session->set_userdata(array('user_name'=>''));
		$this->session->unset_userdata('user_name');
		$this->session->unset_userdata('user_pass');
		$this->session->unset_userdata('bannertitle');
		$this->session->unset_userdata('ua_language');
		$this->session->set_userdata('is_logged_in',FALSE);
		#$this->session->sess_unset();
		$this->session->sess_destroy();
		#$this->index();
		#header("index()");
		#echo "<script>javascript:alert('NOTICE: Logout successful! Thank you!');</script>";
		redirect('go_site/go_dashboard','location');
		#echo "<script>javascript:alert('NOTICE: Logout successful! Thank you!'); window.location='../../'; history.replaceState ='../../'</script>";
	}
	
function closebrowser()
	{
		$remember_me = $this->session->userdata('remember_me');
		if ($remember_me=='') {
			$this->load->model('remember_me');
			$this->remember_me->removeRememberMe($this->input->post('user_name')); 
			} 
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache"); 
		#$this->session->set_userdata(array('user_name'=>''));
		$this->session->unset_userdata('user_name');
		$this->session->unset_userdata('user_pass');
		$this->session->unset_userdata('bannertitle');
		$this->session->unset_userdata('ua_language');
		$this->session->set_userdata('is_logged_in',FALSE);
		#$this->session->sess_unset();
		$this->session->sess_destroy();
		#$this->index();
		#header("index()");
		#echo "<script>javascript:alert('NOTICE: Logout successful! Thank you!');</script>";
		redirect('go_site/go_dashboard','location');
		#echo "<script>javascript:alert('NOTICE: Logout successful! Thank you!'); window.location='../../'; history.replaceState ='../../'</script>";
	}
	
	function set_language() {
		$language = $this->uri->segment(3);
		if ( empty($language) ) { $language = $this->input->post('lang_select'); }
		if ( $language == 'detect' ) {
			// kill the cookie & session variable
			set_cookie('ua_language', '');
			$this->session->set_userdata('ua_language', '');
		} else {
			// switch session's language and set a cookie
			#set_cookie('ua_language', $language, $this->config->item('remember_me_life'));
			$this->session->set_userdata('ua_language', $language);
		}
		redirect ('../');
	}
	
	function set_theme() {
		$data['go_main_cloud_content'] = 'go_main_cloud_page';
		$data['log_status'] = 'start';
		$data['theme'] = $this->input->post('go_theme');
		$this->load->view('includes/go_index_cloud_template', $data);
		$theme = $this->input->post('go_theme');
		$this->session->set_userdata('go_theme', $theme);
	}
}