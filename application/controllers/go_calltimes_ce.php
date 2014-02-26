<?php
####################################################################################################
####  Name:             	go_calltimes_ce.php                                                 ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1373428800                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			            <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_calltimes_ce extends Controller {
	var $userLevel;

    function __construct()
	{
		parent::Controller();
		$this->load->model(array('go_auth','go_dashboard','goingroup','go_calltimes'));
		$this->load->library(array('session','commonhelper'));
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
		$data['bannertitle'] = $this->lang->line('go_calltimes_banner');
		$data['sys']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);

		$data['userfulname'] = $this->go_calltimes->go_get_userfulname();
		$data['callmenupulldown'] = $this->goingroup->getcallmenu($this->userLevel);
		$data['ingrouppulldown'] = $this->goingroup->ingrouppulldown($this->userLevel);
		$data['scriptlists'] = $this->goingroup->scriptlists($this->userLevel);
		$data['calltimespulldown'] = $this->goingroup->calltimespulldown($this->userLevel);
		$data['groupaliaspulldown'] = $this->goingroup->groupalias();
			
		$data['go_main_content'] = 'go_settings/go_calltimes';
		$this->load->view('includes/go_dashboard_template',$data);
	}
	
	function go_get_calltimes()
	{
		$type = $this->uri->segment(3);
		
		if (strlen($type) < 1)
		{
			$data['calltimes'] = $this->go_calltimes->go_get_calltimes_list();
			$data['states'] = $this->go_calltimes->go_get_calltimes_list('state');
		
			$this->load->view('go_settings/go_calltimes_list',$data);
		} else {
			$calltime_id = $this->uri->segment(4);
			$if_state = $this->uri->segment(5);
			$data['user_groups'] = $this->go_calltimes->go_get_usergroups();
			switch ($type)
			{
				case "view":
					$data['calltime_info'] = $this->go_calltimes->go_get_calltimes_info($calltime_id,$if_state);
					$data['using_calltime'] = $this->go_calltimes->go_get_list_using_this($calltime_id,$if_state);
					$data['if_state'] = $if_state;
					
					$this->load->view('go_settings/go_calltimes_view',$data);
					break;
				
				case "update":
					if ($this->uri->segment(5)=="state_rule")
					{
						$state_rule = $this->uri->segment(6);
						$result = $this->go_calltimes->go_add_state_rule($calltime_id,$state_rule);
						list($result,$db_query) = explode("|",$result);
						$this->commonhelper->auditadmin('ADD',"Added State Call Time Rule $state_rule to $call_time_id","$db_query");
					} else {
						$items = explode("&",str_replace(";","",$this->input->post("items")));
						foreach ($items as $item)
						{
							list($var,$val) = explode("=",$item,2);
							
							if ($var!="call_time_id" && $var!="state_call_time_id" && $var!="state_rule")
								$itemSQL .= "$var='".str_replace('+',' ',mysql_real_escape_string($val))."', ";
						}
						
						$itemSQL = rtrim($itemSQL,', ');
						
						if ($if_state != "state")
							$query = $this->db->query("UPDATE vicidial_call_times SET $itemSQL WHERE call_time_id='$calltime_id';");
						else
							$query = $this->db->query("UPDATE vicidial_state_call_times SET $itemSQL WHERE state_call_time_id='$calltime_id';");
						
						if ($this->db->affected_rows())
						{
							if ($if_state != "state")
								$this->commonhelper->auditadmin('MODIFY',"Modified Call Time $calltime_id","UPDATE vicidial_call_times SET $itemSQL WHERE call_time_id='$calltime_id';");
							else
								$this->commonhelper->auditadmin('MODIFY',"Modified State Call Time $calltime_id","UPDATE vicidial_state_call_times SET $itemSQL WHERE state_call_time_id='$calltime_id';");
							$result = "SUCCESS";
						} else {
							$result = "Call time '$calltime_id' not modified.";
						}
					}
					
					echo $result;
					break;
				
				case "delete":
					if ($this->uri->segment(5)=="state_rule")
					{
						$state_rule = $this->uri->segment(6);
						$result = $this->go_calltimes->go_delete_state_rule($calltime_id,$state_rule);
						list($result,$db_query) = explode("|",$result);
						$this->commonhelper->auditadmin('DELETE',"Deleted State Call Time Rule $state_rule from $call_time_id","$db_query");
					} else {
						$calltimes = explode(",",$calltime_id);
						if (count($calltimes) > 1)
						{
							foreach ($calltimes AS $call_time_id)
							{
								$result = $this->go_calltimes->go_delete_calltimes($call_time_id,$if_state);
								list($result,$db_query) = explode("|",$result);
								if (strlen($if_state) < 1)
									$this->commonhelper->auditadmin('DELETE',"Deleted Call Times: $call_time_id","$db_query");
								else
									$this->commonhelper->auditadmin('DELETE',"Deleted State Call Times: $call_time_id","$db_query");
							}
						} else {
							$result = $this->go_calltimes->go_delete_calltimes($calltime_id,$if_state);
							list($result,$db_query) = explode("|",$result);
							if (strlen($if_state) < 1)
								$this->commonhelper->auditadmin('DELETE',"Deleted Call Times: $call_time_id","$db_query");
							else
								$this->commonhelper->auditadmin('DELETE',"Deleted State Call Times: $call_time_id","$db_query");
						}
					}
					
					echo $result;
					break;
				
				case "search":
					$data['calltimes'] = $this->go_calltimes->go_get_calltimes_list();
					$data['states'] = $this->go_calltimes->go_get_calltimes_list('state');
					$data['type'] = $this->uri->segment(4);
				
					$this->load->view('go_settings/go_calltimes_list',$data);
					break;
			}
		}
	}
	
	function go_calltimes_wizard()
	{
		$action = $this->input->post("action");
		$data['user_groups'] = $this->go_calltimes->go_get_usergroups();
		
		switch ($action)
		{
			case "create":
				$items = explode("&",str_replace(';','',$this->input->post("items")));
				foreach ($items as $item)
				{
					list($var,$val) = explode("=",$item,2);
					if (strlen($val) > 0)
					{
						$varSQL .= "$var,";
						$valSQL .= "'".str_replace('+',' ',mysql_real_escape_string($val))."',";
					}
					
					if ($var=="call_time_id")
						$call_time_id="$val";
				}
				$varSQL = rtrim($varSQL,",");
				$valSQL = rtrim($valSQL,",");
				$itemSQL = "($varSQL) VALUES ($valSQL)";
				$query = $this->db->query("INSERT INTO vicidial_call_times $itemSQL;");
				
				if ($this->db->affected_rows())
				{
					$this->commonhelper->auditadmin('ADD',"Added New Call Time: $call_time_id","INSERT INTO vicidial_call_times $itemSQL;");
					$return = "SUCCESS";
				}
				
				echo $return;
				break;
			
			default:
				$this->load->view('go_settings/go_calltimes_wizard',$data);
		}
	}
	
	function go_state_calltimes_wizard()
	{
		$action = $this->input->post("action");
		$data['user_groups'] = $this->go_calltimes->go_get_usergroups();
		
		switch ($action)
		{
			case "create":
				$items = explode("&",str_replace(';','',$this->input->post("items")));
				foreach ($items as $item)
				{
					list($var,$val) = explode("=",$item,2);
					if (strlen($val) > 0)
					{
						$varSQL .= "$var,";
						$valSQL .= "'".str_replace('+',' ',mysql_real_escape_string($val))."',";
					}
					
					if ($var=="state_call_time_id")
						$state_call_time_id="$val";
				}
				$varSQL = rtrim($varSQL,",");
				$valSQL = rtrim($valSQL,",");
				$itemSQL = "($varSQL) VALUES ($valSQL)";
				$query = $this->db->query("INSERT INTO vicidial_state_call_times $itemSQL;");
				
				if ($this->db->affected_rows())
				{
					$this->commonhelper->auditadmin('ADD',"Added New State Call Time: $state_call_time_id","INSERT INTO vicidial_state_call_times $itemSQL;");
					$return = "SUCCESS";
				}
				
				echo $return;
				break;
			
			default:
				$this->load->view('go_settings/go_state_calltimes_wizard',$data);
		}
	}
	
	function go_check_calltimes()
	{
		$calltime = $this->uri->segment(3);
		
		$query = $this->db->query("SELECT * FROM vicidial_call_times WHERE call_time_id='$calltime'");
		$return = $query->num_rows();
		
		if ($return)
		{
			$return = "<small style=\"color:red;\">Not Available.</small>";
		} else {
			$return = "<small style=\"color:green;\">Available.</small>";
		}
		
		echo $return;
	}
	
	function go_check_state_calltimes()
	{
		$calltime = $this->uri->segment(3);
		
		$query = $this->db->query("SELECT * FROM vicidial_state_call_times WHERE state_call_time_id='$calltime'");
		$return = $query->num_rows();
		
		if ($return)
		{
			$return = "<small style=\"color:red;\">Not Available.</small>";
		} else {
			$return = "<small style=\"color:green;\">Available.</small>";
		}
		
		echo $return;
	}

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			$base = base_url();
			echo "<script>javascript: window.location = 'https://".$_SERVER['HTTP_HOST']."/login'</script>";
			die();
		}
	}

}
