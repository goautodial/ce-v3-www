<?php
########################################################################################################
####  Name:             	go_login.php                                                  	    ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Rodolfo Januarius T. Manipol				            ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_login extends Controller {
	function index()
	{
		#$data['go_main_content'] = 'go_login_form';
		#$this->load->view('includes/template', $data);
			#$this->session->set_userdata('ua_language', 'french');

		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
			{
				$data['go_main_content'] = 'go_main_page';
				$this->load->view('includes/go_index_template', $data);
			}
			else
			{
				redirect('go_site/go_dashboard', 'location');
			}
	}
	
	function validate_credentials()
	{
                
		$this->load->library(array('session','commonhelper'));
		$this->load->model('go_auth');
		$query = $this->go_auth->validate();

		if($query)
		{
			$uname = $this->uri->segment(3);
			$upass = $this->uri->segment(4);
			if(empty($uname) && empty($upass)){
				$uname = $this->input->post('user_name');
				$upass = $this->input->post('user_pass');
			}

			$this->db = $this->load->database('goautodialdb', true);

			
			$data = array (
					'user_name' => $uname,
					'user_pass' => md5($upass),
					'is_logged_in' => true,
					'remember_me' => $this->input->post('remember_me')
				    );
			
			############ added information by franco ################
			$asteriskDB = $this->load->database('dialerdb',true);
			$asteriskDB->where(array('user'=>$uname));
			$userinfo = $asteriskDB->get('vicidial_users')->result();
			$access = $asteriskDB->get('go_useraccess')->result();
			if(!empty($userinfo)){
				foreach($userinfo as $info){
					if(!empty($access)){
						// get all enable in the object
						$ctr = 0;
						foreach($access as $fields){
							$col = $fields->vicidial_users_column_name;
							if(is_numeric($info->$col)){
								 if($info->$col > 0){
									 $useraccess[$ctr] = $fields->vicidial_users_column_name;
								 }
							}else{
								 if($info->$col != 'DISABLED' && $info->$col != 'NEVER' &&
									$info->$col != 'NOT_ACTIVE' && $info->$col != 'N')
								 {
									 $useraccess[$ctr] = $fields->vicidial_users_column_name.":".$info->$col;
								 }
							}
							$ctr++;
						}
						$data['useraccess'] = serialize($useraccess);
					}else{
						$data['useraccess'] = serialize(array());
					}
				}
				$data['users_level'] = $info->user_level;
				$data['user_group'] = $info->user_group; 
				$data['full_name'] = $info->full_name; 
			}
			####################### END #############################
			$this->session->set_userdata($data);
			
			#$this->session->set_userdata('ua_language', 'french');

			$remember_me = $this->input->post('remember_me');

				if ($remember_me==1) {
						$this->load->model('remember_me');
						$this->remember_me->addRememberMe($this->input->post('user_name'));

					}
					else{
						$this->load->model('remember_me');
						$this->remember_me->removeRememberMe($this->input->post('user_name')); 

					}

			#$this->session->cookie_monster($this->input->post('remember_me') ? FALSE : TRUE);
			#$this->session->cookie_monster($this->input->post('user_name'));
			#$this->session->cookie_monster($this->input->post('user_pass'));
			#$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
			#$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
			#$this->output->set_header("Pragma: no-cache"); 




			//$data = $this->input->post('data');
			//$array = unserialize($data);
			 // print_r($array);
			
			
			//echo $this->input->post('user_name');

			//echo $this->input->post('user_pass');

			//return true;
                        $this->commonhelper->auditadmin('LOGIN',"$uname logged-in");
			echo "Authenticated";
			//redirect('go_site/go_dashboard', 'location');
		}
		else
		{
			$uname = $this->uri->segment(3);
			if(empty($uname)){
				$uname = $this->input->post('user_name');
			}
			
					//$data = $this->input->post('data');
			//$array = unserialize($data);
			  //print_r($array);
			
                        $this->commonhelper->auditadmin('LOGIN',"Log-in failed for user: $uname",null,$uname);
			echo "Login Failed";
			//echo $this->input->post('user_name');

			//echo $this->input->post('user_pass');
			
			
			
			#$this->session->isset_userdata($data);	
			#echo "<script>javascript:alert('Invalid');</script>";
			#usleep(2000000);
			//echo "<script>javascript:window.location = '../../'</script>";					
					
				//$data['log_status'] = 'invalid';
				//$data['go_main_content'] = 'go_main_page';
				//$this->load->view('includes/go_index_template', $data);

			//echo "<script>javascript:window.location = '../../'</script>";	
	 
//			return false;

//						 echo $this->input->post('user_name');

//			echo  $this->input->post('user_pass');


		}
	}
	
	function signup()
	{
		$data['go_main_content'] = 'go_signup_form';
		$this->load->view('includes/template', $data);
	}
	
	function create_member()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_fname', 'Name', 'trim|required');
		$this->form_validation->set_rules('user_lname', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('user_email', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('user_name', 'Username', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('user_pass', 'Password', 'trim|required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('user_pass2', 'Password Confirmation', 'trim|required|matches[user_pass]');
		if($this->form_validation->run() == FALSE)
		{
			$this->load->view('go_signup_form');
		}
		else
		{			
			$this->load->model('go_auth');	
			if($query = $this->go_auth->create_member())
			{
				$data['go_main_content'] = 'go_signup_successful';
				$this->load->view('includes/template', $data);
			}
			else
			{
				$this->load->view('go_signup_form');			
			}
		}
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
}
