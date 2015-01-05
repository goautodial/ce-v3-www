<?php
########################################################################################################
####  Name:             	go_auth.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  Modified by:      	GoAutoDial Development Team                                         ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_auth extends Model {
	
function validate()
	{
	
	        $uname = $this->uri->segment(3);
	        $upass = $this->uri->segment(4);
		

		if (!$uname && !$upass ){
		    $uname = $this->input->post('user_name');
		    $upass = $this->input->post('user_pass');
		}
		
		if ($uname!='' && $upass!=''){
			
			$this->adb = $this->load->database('dialerdb', true);
		
			$this->adb->where('user', $this->adb->escape_str($uname));
			#$this->adb->where('pass', $upass);
			$this->adb->where('pass', $this->adb->escape_str($upass));
			$this->adb->where('user_level > 7');
                        $this->adb->where('active',"Y");
			$query = $this->adb->get('vicidial_users');

                        
 
				if($query->num_rows == 1){
					return true;
					}
			}
			else
			{
			return false;
			
			//	if ((!$upass)&&(!$uname)){
			//		#echo "<script>javascript:alert('ERROR: You must type a valid username and password!'); window.location = '../../'</script>";
			//		echo "<script>javascript:window.location = '../../'</script>";
                        //
			//	
			//	}
			//	
			//	if (!$uname){
			//		#echo "<script>javascript:alert('ERROR: You must type a valid username!'); window.location = '../../'</script>";
			//		echo "<script>javascript:window.location = '../../'</script>";
                        //
			//	
			//	}
			//	
			//	if (!$upass){
			//		#echo "<script>javascript:alert('ERROR: You must type a valid password!'); window.location = '../../'</script>";
			//		echo "<script>javascript:window.location = '../../'</script>";
                        //
			//	}
			}
	}
	
	function create_member()
	{
		$new_member_insert_data = array(
			'user_name' => $this->input->post('user_name'),
			'user_pass' => md5($this->input->post('user_pass')),
			'user_fname' => $this->input->post('user_fname'),			
			'user_lname' => $this->input->post('user_lname'),
			'user_email' => $this->input->post('user_email')						
		);
		
		$insert = $this->db->insert('go_users', $new_member_insert_data);
		return $insert;
	}
}
