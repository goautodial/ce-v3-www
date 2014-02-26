<?php
####################################################################################################
####  Name:             	go_phones_ce.php                                                    ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  Edited by:            GoAutoDial Development Team                                         ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_phones_ce extends Controller {
    var $userLevel;
    function __construct()
	{
		parent::Controller();
		$this->load->model(array('go_auth','go_dashboard','go_phones'));
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
		$data['bannertitle'] = $this->lang->line('go_phones_banner');
		$data['sys']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);


		$data['userfulname'] = $this->go_phones->go_get_userfulname();
		
		$phones = $this->go_phones->go_get_phone_list();
		$data['phones'] = $phones['list'];
		$data['pagelinks'] = $phones['pagelinks'];

        $data['go_main_content'] = 'go_settings/go_phones';
        $this->load->view('includes/go_dashboard_template',$data);
	}
	
	function go_get_phone()
	{
		$type = $this->uri->segment(3);
		$phone = $this->uri->segment(4);
		
		switch($type)
		{
			case "modify":
				break;
			
			default:
				$query = $this->db->query("SELECT * FROM phones WHERE extension='$phone';");
				$data['phone_info'] = $query->row();
				break;
		}
		
		$data['templates'] = $this->go_phones->go_list_templates();
		$data['servers'] = $this->go_phones->go_list_server_ips();
		
		$data['type'] = $type;
        $this->load->view('go_settings/go_phone_view',$data);
	}


        function go_phone_wizard_multi(){
			$data['server_ips'] = $this->go_phones->go_list_server_ips();
			$data['user_groups'] = $this->go_phones->go_get_usergroups();
			$data['system_settings'] = $this->go_phones->go_get_systemsettings();
           $this->load->view('go_settings/go_phone_wizard_multi',$data);
        }


        function autogen($postvars=array()){
            
            if(!is_null($postvars) && !empty($postvars)){
                 $ctr = 0;
                 while($ctr < $postvars['count']){
                    $autogen[$ctr]['extension_'.$ctr] = $postvars['start_exten'];
                    #$autogen[$ctr]["pass_$ctr"] = "{$postvars['system_settings']->default_phone_registration_password}{$postvars['start_exten']}";
                    #$autogen[$ctr]["pass_$ctr"] = "go{$postvars['start_exten']}";
                    $autogen[$ctr]["pass_$ctr"] = $postvars['pass'];
                    $autogen[$ctr]["conf_secret_$ctr"] = $postvars['conf_secret'];
                    $autogen[$ctr]["fullname_$ctr"] = $postvars['start_exten'];
                    $autogen[$ctr]["protocol_$ctr"] = $postvars['protocol'];
					$autogen[$ctr]["dial_prefix_$ctr"] = $postvars['dial_prefix'];
					$autogen[$ctr]["server_ip_$ctr"] = $postvars['server_ip'];
					$autogen[$ctr]["user_group_$ctr"] = $postvars['user_group'];
                    $postvars['start_exten'] += 1;
                    $ctr++;
                 }

                 if(count($autogen) > 3){
                    unset($_POST); # to remove $_POST
                    # recreate autogen with other info for saving
                    $exten = '';
                    foreach($autogen as $ind => $vals){
                        foreach($vals as $key => $result){
							$king2 = 4;
							if ($ind < 100)
								$king2 = 3;
							if ($ind < 10)
								$king2 = 2;
							
                           $newautogen[$ind][substr($key,0,strlen($key)-$king2)] = $result; 
                           if($key == "extension_$ind"){
                               $exten = $result;
                           }
						   
						   if ($key == "protocol_$ind") {
							   $protocol = $result;
						   }
						   
						   if ($key == "dial_prefix_$ind") {
							   $dial_prefix = $result;
						   }
						   
						//   if ($key == "server_ip_$ind") {
						//	   $server_ip = $result;
						//   }
						//   
						   if ($key == "conf_secret_$ind") {
							   $default_pass = $result;
						   }
                        }
                        # additional fields
                        $newautogen[$ind]["dialplan_number"] = $exten;
                        $newautogen[$ind]["voicemail_id"] = $exten;
                        $newautogen[$ind]['outbound_cid'] = '';
                        $newautogen[$ind]['messages'] = 0;
                        $newautogen[$ind]['old_messages'] = 0;
                        //$newautogen[$ind]['user_group'] = '---ALL---';
                        //$newautogen[$ind]['server_ip'] = $server_ip;
                        $newautogen[$ind]['login'] = '';
                        #$newautogen[$ind]['conf_secret'] = "go$exten";
                        $newautogen[$ind]['conf_secret'] = $default_pass;
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
						$king2 = strrpos($cols,"_");
						$king3 = $king2 - strlen($cols);
						$king4 = strlen($cols) - $king2;
                        $data[substr($cols,$king3+1)][substr($cols,0,$king3)] = $vals;
                    }
                }
                # save regrouped data
                foreach($data as $rawdata){
                    $this->go_check_phone($rawdata['extension'],$result);
                    if(!preg_match('/Not Available/',$result)){
			unset($rawdata['dial_prefix']);
			$rawdata['login'] = $rawdata['extension'];
			$this->db->insert('phones',$rawdata);
			$this->commonhelper->auditadmin('ADD',"Added New Phone",$rawdata);
                    }else{
                       #die("Error: Phone '{$rawdata['extension']}' Not Available");
                       die("<script>$('#box').animate({top:-3000});$('#overlay').fadeOut('fast');setTimeout(\"alert('Error: Phone `{$rawdata['extension']}` Not Available');\",200);</script>");
                    }
                }

				$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y';");
                $data = "Success";
                echo "Success";
            }
        }

	function go_phone_wizard()
	{
		$action = $this->input->post("action");
		if (strlen($action) < 1)
		{
			$data['system_settings'] = $this->go_phones->go_get_systemsettings();
			$data['server_ips'] = $this->go_phones->go_list_server_ips();
                        $generate = $this->autogen(array_merge(array('server_ips'=>$data['server_ips']),array('system_settings'=>$data['system_settings']),$_POST));
                        $data['generate'] = $generate;
			$data['user_groups'] = $this->go_phones->go_get_usergroups();
			
	        $this->load->view('go_settings/go_phone_wizard',$data);
		} else {
			if ($action == "add_new_phone")
			{
				$items = explode("&",str_replace(";","",$this->input->post("items")));
				foreach ($items as $item)
				{
					list($var,$val) = explode("=",$item,2);
					if (strlen($val) > 0)
					{
						$varSQL .= "$var,";
						$valSQL .= "'".str_replace('+',' ',mysql_real_escape_string($val))."',";
						
						if ($var=="extension")
							$extension="$val";
						
						if ($var=="server_ip")
							$server_ip="$val";
					}
				}
				$varSQL = rtrim($varSQL,",");
				$valSQL = rtrim($valSQL,",");
				$itemSQL = "($varSQL) VALUES ($valSQL)";
				$query = $this->db->query("INSERT INTO phones $itemSQL;");
				
				if ($this->db->affected_rows())
				{
					$this->commonhelper->auditadmin('ADD',"Added New Phone $extension");
					$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
					$return = "SUCCESS";
				}
			}
			
			if ($action == "delete_phone")
			{
				$exten = $this->input->post("exten");
				$query = $this->db->query("SELECT server_ip FROM phones WHERE extension = '$exten'");
				$server_ip = $query->row()->server_ip;
				$query = $this->db->query("DELETE FROM phones WHERE extension = '$exten'");
				
				$this->commonhelper->auditadmin('DELETE',"Deleted Phone $extension","DELETE FROM phones WHERE extension = '$exten'");
				$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
				$return = "DELETED";
			}
			
			if ($action == "modify_phone")
			{
				$items = explode("&",str_replace(";","",$this->input->post("items")));
				foreach ($items as $item)
				{
					list($var,$val) = explode("=",$item,2);
					if (strlen($val) > 0)
					{
						if ($var!="extension")
							$itemSQL .= "$var='".str_replace('+',' ',mysql_real_escape_string($val))."', ";
						
						if ($var=="extension")
							$extension="$val";
						
						if ($var=="server_ip")
							$server_ip="$val";
						
						if ($var=="pass")
							$passwd="$val";
					}
				}
				$itemSQL = rtrim($itemSQL,', ');
				$query = $this->db->query("UPDATE phones SET $itemSQL WHERE extension='$extension';");
				//echo "UPDATE phones SET $itemSQL WHERE extension='$extension';";
				
				$query = $this->db->query("UPDATE vicidial_users SET phone_pass='$passwd' WHERE phone_login='$extension';");
				
				if ($this->db->affected_rows())
				{
					$this->commonhelper->auditadmin('MODIFY',"Modified Phone $extension","UPDATE phones SET $itemSQL WHERE extension='$extension';");
					$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
					$return = "SUCCESS";
				}
			}
			
			echo $return;
		}
	}
	
	function go_check_phone($intrnl_phone=null,&$result=null)
	{
		$phone = $this->uri->segment(3);
		if(!is_null($intrnl_phone)){
			 $phone = $intrnl_phone;
		} 
		$query = $this->db->query("SELECT * FROM phones WHERE extension = '$phone'");
		$phoneExist = $query->num_rows();
		
		$query = $this->db->query("SELECT * FROM vicidial_voicemail WHERE voicemail_id = '$phone'");
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
	
	function go_update_phone_list()
	{
		$action = $this->uri->segment(3);
		$extensions = str_replace(',',"','",$this->uri->segment(4));

		switch($action)
		{
			case "activate":
				$query = $this->db->query("UPDATE phones SET active='Y' WHERE extension IN ('$extensions')");
				$this->commonhelper->auditadmin('ACTIVE','Activated Extensions(s): '.$this->uri->segment(4),"UPDATE phones SET active='Y' WHERE extension IN ('$extensions')");
				break;
			case "deactivate":
				$query = $this->db->query("UPDATE phones SET active='N' WHERE extension IN ('$extensions')");
				$this->commonhelper->auditadmin('INACTIVE','Deactivated Extensions(s): '.$this->uri->segment(4),"UPDATE phones SET active='N' WHERE extension IN ('$extensions')");
				break;
			case "delete":
				foreach (explode("','",$extensions) AS $extension)
				{
					$query = $this->db->query("SELECT server_ip FROM phones WHERE extension = '$extension'");
					$server_ip = $query->row()->server_ip;
					$query = $this->db->query("DELETE FROM phones WHERE extension = '$extension'");
					$db_query .= "DELETE FROM phones WHERE extension = '$extension'; ";
				}
				
				$this->commonhelper->auditadmin('DELETE',"Deleted Extensions(s): ".$this->uri->segment(4),"$db_query");
				$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
				break;
		}
		
		$phones = $this->go_phones->go_get_phone_list();
		$data['phones'] = $phones['list'];
		$data['pagelinks'] = $phones['pagelinks'];
	    $this->load->view('go_settings/go_phone_list',$data);
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
