<?php
####################################################################################################
####  Name:             	go_voicemail_ce.php                                                 ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  Edited by:            GoAutoDial Development Team                                         ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_voicemail_ce extends Controller {
    var $userLevel;
    function __construct()
	{
		parent::Controller();
		$this->load->model(array('go_auth','go_dashboard','go_voicemails'));
		$this->load->library(array('session','pagination','commonhelper'));
		$this->load->helper(array('date','form','url','path'));
		$this->is_logged_in();
		$this->lang->load('userauth', $this->session->userdata('ua_language'));

		$this->userLevel = $this->session->userdata('users_level');
		$config['enable_query_strings'] = FALSE;
//		$this->config->initialize($config);
    }

	function index()
	{
		if ($this->userLevel < 9) { die('Error: You do not have permission to view this page.'); }
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

		$data['theme'] = $this->session->userdata('go_theme');
		$data['bannertitle'] = $this->lang->line('go_voicemails_banner');
		$data['sys']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);


		$data['userfulname'] = $this->go_voicemails->go_get_userfulname();
		
		$data['voicemails'] = $this->go_voicemails->go_get_voicemail_list();

        $data['go_main_content'] = 'go_settings/go_voicemails';
        $this->load->view('includes/go_dashboard_template',$data);
	}
	
	function go_get_voicemail()
	{
		$type = $this->uri->segment(3);
		$vmail = $this->uri->segment(4);
		
		$query = $this->db->query("SELECT * FROM vicidial_voicemail WHERE voicemail_id='$vmail';");
		$data['vmail_info'] = $query->row();
		
		$data['servers'] = $this->go_voicemails->go_list_server_ips();
		
		$data['type'] = $type;
        $this->load->view('go_settings/go_voicemail_view',$data);
	}


	function go_phone_wizard_multi(){
	   $this->load->view('go_settings/go_phone_wizard_multi');
	}


	function autogen($postvars=array()){
		
		if(!is_null($postvars) && !empty($postvars)){
			 $ctr = 0;
			 while($ctr < $postvars['count']){
				$autogen[$ctr]['extension_'.$ctr] = $postvars['start_exten'];
				$autogen[$ctr]["pass_$ctr"] = "{$postvars['system_settings']->default_phone_registration_password}{$postvars['start_exten']}";
				$autogen[$ctr]["fullname_$ctr"] = $postvars['start_exten'];
				$autogen[$ctr]["protocol_$ctr"] = $postvars['protocol'];
				$postvars['start_exten'] += 1;
				$ctr++;
			 }

			 if(count($autogen) > 2){
				unset($_POST); # to remove $_POST
				# recreate autogen with other info for saving
				$exten = '';
				foreach($autogen as $ind => $vals){
					foreach($vals as $key => $result){
					   $newautogen[$ind][substr($key,0,strlen($key)-2)] = $result; 
					   if($key == "extension_$ind"){
						   $exten = $result;
					   }
					}
					# additional fields
					$newautogen[$ind]["dialplan_number"] = $exten;
					$newautogen[$ind]["voicemail_id"] = $exten;
					$newautogen[$ind]['outbound_cid'] = '';
					$newautogen[$ind]['messages'] = 0;
					$newautogen[$ind]['old_messages'] = 0;
					$newautogen[$ind]['user_group'] = '---ALL---';
					$newautogen[$ind]['server_ip'] = $postvars["server_ips"][0]->server_ip;
					$newautogen[$ind]['login'] = '';
					$newautogen[$ind]['conf_secret'] = "go$exten";
					$newautogen[$ind]['status'] = "ACTIVE";
					$newautogen[$ind]['active'] = "Y";
					$newautogen[$ind]['phone_type'] = "";
					$newautogen[$ind]['local_gmt'] = "-5.00";
		
				} 
				$this->autogensave($newautogen);
				if($newautogen == "Success"){
					  die("<script>$('#box').animate({top:-3000});location.reload()</script>");
				}
			 }
 
			 return $autogen;
		}
	}

	function autogensave(&$data=false){
		if(!empty($_POST) || $data){
	
			if(!$data){
				# data recieved from ui
				# data regroup
				foreach($_POST as $cols => $vals){
					$data[substr($cols,strlen($cols)-1)][substr($cols,0,strlen($cols)-2)] = $vals;
				}
			}

			# save regrouped data
			foreach($data as $rawdata){
				$this->go_check_phone($rawdata['extension'],$result);
				if(!preg_match('/Not Available/',$result)){
				   $this->db->insert('phones',$rawdata);
				}else{
				   #die("Error: Phone '{$rawdata['extension']}' Not Available");
				   die("<script>$('#box').animate({top:-3000});$('#overlay').fadeOut('fast');setTimeout(\"alert('Error: Phone `{$rawdata['extension']}` Not Available');\",200);</script>");
				}
			}

			$data = "Success";
			echo "Success";
		}
	}

	function go_voicemail_wizard()
	{
		$action = $this->input->post("action");
		if (strlen($action) < 1)
		{
	        $this->load->view('go_settings/go_voicemail_wizard',$data);
		} else {
			if ($action == "add_new_voicemail")
			{
				$items = explode("&",str_replace(";","",$this->input->post("items")));
				foreach ($items as $item)
				{
					list($var,$val) = explode("=",$item,2);
					if (strlen($val) > 0)
					{
						$varSQL .= "$var,";
						$valSQL .= "'".str_replace('+',' ',mysql_real_escape_string($val))."',";
						
						if ($var=="voicemail_id")
							$voicemail_id="$val";
					}
				}
				$varSQL = rtrim($varSQL,",");
				$valSQL = rtrim($valSQL,",");
				$itemSQL = "($varSQL) VALUES ($valSQL)";
				$query = $this->db->query("INSERT INTO vicidial_voicemail $itemSQL;");
				
				if ($this->db->affected_rows())
				{
					$query = $this->db->query("SELECT active_voicemail_server from system_settings");
					$server_ip = $query->row()->active_voicemail_server;
				
					$this->commonhelper->auditadmin('ADD',"Added New Voicemail Box $voicemail_id","INSERT INTO vicidial_voicemail $itemSQL;");
					$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
					$return = "SUCCESS";
				}
			}
			
			if ($action == "delete_voicemail")
			{
				$voicemail = $this->input->post("voicemail");
				$query = $this->db->query("SELECT active_voicemail_server from system_settings");
				$server_ip = $query->row()->active_voicemail_server;
				$query = $this->db->query("DELETE FROM vicidial_voicemail WHERE voicemail_id = '$voicemail'");
				
				$this->commonhelper->auditadmin('DELETE',"Deleted Voicemail Box $extension","DELETE FROM vicidial_voicemail WHERE voicemail_id = '$voicemail'");
				$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
				$return = "DELETED";
			}
			
			if ($action == "modify_voicemail")
			{
				$items = explode("&",str_replace(";","",$this->input->post("items")));
				foreach ($items as $item)
				{
					list($var,$val) = explode("=",$item,2);
					if (strlen($val) > 0)
					{
						if ($var!="voicemail_id")
							$itemSQL .= "$var='".str_replace('+',' ',mysql_real_escape_string($val))."', ";
						
						if ($var=="voicemail_id")
							$voicemail_id="$val";
					}
				}
				$itemSQL = rtrim($itemSQL,', ');
				$query = $this->db->query("UPDATE vicidial_voicemail SET $itemSQL WHERE voicemail_id='$voicemail_id';");
				//echo "UPDATE phones SET $itemSQL WHERE extension='$extension';";
				
				if ($this->db->affected_rows())
				{
					$query = $this->db->query("SELECT active_voicemail_server from system_settings");
					$server_ip = $query->row()->active_voicemail_server;
				
					$this->commonhelper->auditadmin('MODIFY',"Modified Voicemail Box $voicemail_id","UPDATE vicidial_voicemail SET $itemSQL WHERE voicemail_id='$voicemail_id';");
					$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
					$return = "SUCCESS";
				}
			}
			
			echo $return;
		}
	}
	
	function go_check_voicemail($intrnl_voicemail=null,&$result=null)
	{
		$voicemail = $this->uri->segment(3);
		if(!is_null($intrnl_voicemail)){
			 $voicemail = $intrnl_voicemail;
		} 
		$query = $this->db->query("SELECT * FROM phones WHERE voicemail_id = '$voicemail'");
		$phoneExist = $query->num_rows();
		
		$query = $this->db->query("SELECT * FROM vicidial_voicemail WHERE voicemail_id = '$voicemail'");
		$vmExist = $query->num_rows();
		
		if ($phoneExist || $vmExist)
		{
			$return = "<small style=\"color:red;\">Not Available.</small>";
		} else {
			$return = "<small style=\"color:green;\">Available.</small>";
		}
	
        $result = $return;	
		echo $return;
	}
	
	function go_update_voicemail_list()
	{
		$action = $this->uri->segment(3);
		$voicemails = str_replace(',',"','",$this->uri->segment(4));

		switch($action)
		{
			case "activate":
				$query = $this->db->query("UPDATE vicidial_voicemail SET active='Y' WHERE voicemail_id IN ('$voicemails')");
				$this->commonhelper->auditadmin('ACTIVE','Activated Voicemail(s): '.$this->uri->segment(4),"UPDATE vicidial_voicemail SET active='Y' WHERE voicemail_id IN ('$voicemails')");
				break;
			case "deactivate":
				$query = $this->db->query("UPDATE vicidial_voicemail SET active='N' WHERE voicemail_id IN ('$voicemails')");
				$this->commonhelper->auditadmin('INACTIVE','Deactivated Voicemail(s): '.$this->uri->segment(4),"UPDATE vicidial_voicemail SET active='N' WHERE voicemail_id IN ('$voicemails')");
				break;
			case "delete":
				foreach (explode("','",$voicemails) AS $voicemail)
				{
					$query = $this->db->query("SELECT active_voicemail_server from system_settings");
					$server_ip = $query->row()->active_voicemail_server;
					$query = $this->db->query("DELETE FROM vicidial_voicemail WHERE voicemail_id = '$voicemail'");
					$db_query .= "DELETE FROM vicidial_voicemail WHERE voicemail_id = '$voicemail'; ";
				}
				
				$this->commonhelper->auditadmin('DELETE',"Deleted Voicemail(s): ".$this->uri->segment(4),"$db_query");
				$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
				break;
		}
		
		$data['voicemails'] = $this->go_voicemails->go_get_voicemail_list();
	    $this->load->view('go_settings/go_voicemail_list',$data);
	}

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			$base = base_url();
			echo "<script>javascript: window.location = 'https://".$_SERVER['HTTP_HOST']."/login'</script>";
// 			echo "<script>javascript: window.location = '$base'</script>";
			#echo 'You don\'t have permission to access this page. <a href="../go_index">Login</a>';
			die();
			#$this->load->view('go_login_form');
		}
	}
}