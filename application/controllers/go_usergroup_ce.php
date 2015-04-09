<?php
########################################################################################################
####  Name:             	go_usergroup_ce.php                                                 ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Christopher Lomuntad				            	    ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_usergroup_ce extends Controller {
    var $userLevel;
    function __construct()
	{
		parent::Controller();
		$this->load->model(array('go_auth','go_dashboard','go_usergroups','go_access'));
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
		if ($this->userLevel < 9) { die($this->lang->line("go_err_permission_view")); }
                $data['cssloader'] = 'go_dashboard_cssloader.php';
                $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
                $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

		$data['theme'] = $this->session->userdata('go_theme');
		$data['bannertitle'] = $this->lang->line("go_ug_banner");
		$data['sys']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);


		$data['userfulname'] = $this->go_usergroups->go_get_userfulname();
		
		
	$data['usergroups'] = $this->go_usergroups->go_get_usergroup_list();
		
		
        $data['go_main_content'] = 'go_settings/go_usergroups';
        $this->load->view('includes/go_dashboard_template',$data);
	}
	
	function go_get_usergroup()
	{
		$type = $this->uri->segment(3);
		$group = $this->uri->segment(4);
		
		switch($type)
		{
			case "modify":
				break;
			
			default:
				$query = $this->db->query("SELECT * FROM vicidial_user_groups WHERE user_group='$group';");
				$data['group_info'] = $query->row();
				break;
		}
		
		$data['campaign_list'] = $this->go_usergroups->go_get_campaigns();
		$data['shift_list'] = $this->go_usergroups->go_get_shifts();
		$data['group_list'] = $this->go_usergroups->go_get_usergroups();
		$data['calltime_list'] = $this->go_usergroups->go_get_calltimes();
		$data['type'] = $type;
                $group_id = $this->go_access->get_usergroup_permission_id($group);
                $data['permission'] = $group_id[0]->id;
        $this->load->view('go_settings/go_usergroup_view',$data);
	}
	
	function go_usergroup_wizard()
	{

		$action = $this->input->post("action");
		if (strlen($action) < 1)
		{
		
                    $data['access'] = $this->go_access->get_all_access();
	
	            $this->load->view('go_settings/go_usergroup_wizard',$data);
		} else {
			if ($action == "add_new_usergroup")
			{
                                $postItem = rtrim($this->input->post("items"),"&");
				$items = explode("&",str_replace(";","",$postItem));
				foreach ($items as $item)
				{
					list($var,$val) = split("=",$item);
					if (strlen($val) > 0)
					{
						$varSQL .= "$var,";
						$valSQL .= "'".str_replace('+',' ',mysql_real_escape_string($val))."',";
						
						if ($var=="user_group"){
							$group="$val";
                                                        $user_group['user_group'] = $val;
                                                }
					}
				}

				$varSQL = rtrim($varSQL,",");
				$valSQL = rtrim($valSQL,",");
				$itemSQL = "($varSQL) VALUES ($valSQL)";
				$query = $this->db->query("INSERT INTO vicidial_user_groups $itemSQL;");
				if ($this->db->affected_rows())
				{
					$this->commonhelper->auditadmin('ADD',"Added New User Group $group","INSERT INTO vicidial_user_groups $itemSQL;");
                                  
                                        if($this->go_access->go_check_access_exist($user_group['user_group'])){
                                             $groupings = array_merge($user_group,array('permissions'=>$_POST['permiso'],'group_level'=>$_POST['group_level'])); 
                                             $this->go_access->goautodialDB->insert('user_access_group',$groupings);
		                             $this->commonhelper->auditadmin('ADD',"Add new Group Access: $group");
                                        }

					$return = "SUCCESS";
				}



			}
			
			if ($action == "delete_usergroup")
			{
				$groupid = $this->input->post("groupid");
				$query = $this->db->query("DELETE FROM vicidial_user_groups WHERE user_group='$groupid'");
				$this->commonhelper->auditadmin('DELETE',"Deleted User Group $groupid","DELETE FROM vicidial_user_groups WHERE user_group='$groupid'");

                                $this->go_access->goautodialDB->where("user_group",$groupid);
				$query = $this->go_access->goautodialDB->delete('user_access_group');
				$this->commonhelper->auditadmin('DELETE',"Deleted User Group user_access_group table $groupid");
				
				$return = "DELETED";
			}
			
			if ($action == "modify_usergroup")
			{
				#$items = explode("&",str_replace(";","",$this->input->post("items")));

                                $postItem = rtrim($this->input->post("items"),"&");
				$items = explode("&",str_replace(";","",$postItem));
				$updated = 0;
				foreach ($items as $item)
				{
					list($var,$val) = split("=",$item);
					if (strlen($val) > 0)
					{
						$var = str_replace('[]','',$var);
						if ($var!="user_group" && $var!="allowed_campaigns" && $var!="agent_status_viewable_groups" && $var!="allowed_reports" && $var!="admin_viewable_groups" && $var!="admin_viewable_call_times")
							$itemSQL .= "$var='".str_replace('+',' ',mysql_real_escape_string($val))."', ";
						
						if ($var=="user_group"){
							$group="$val";
                                                        $user_group['user_group'] = $val;
                                                }
						
						if ($var=="allowed_campaigns")
							$allowed_campaignsSQL .= "$val ";
						
						if ($var=="agent_status_viewable_groups")
							$viewable_groupsSQL .= "$val ";
						
						if ($var=="allowed_reports")
							$allowed_reportsSQL .= str_replace('+',' ',$val).", ";
						
						if ($var=="admin_viewable_groups")
							$Aviewable_groupsSQL .= "$val ";
						
						if ($var=="admin_viewable_call_times")
							$viewable_calltimesSQL .= "$val ";
					}
				}
				$itemSQL = rtrim($itemSQL,', ');
				$allowed_reportsSQL = substr($allowed_reportsSQL,0,(strlen($allowed_reportsSQL)-2));
				$otherSQL = ", allowed_campaigns=' $allowed_campaignsSQL-', agent_status_viewable_groups=' $viewable_groupsSQL', allowed_reports='$allowed_reportsSQL', admin_viewable_groups=' $Aviewable_groupsSQL', admin_viewable_call_times=' $viewable_calltimesSQL'";
				$query = $this->db->query("UPDATE vicidial_user_groups SET $itemSQL$otherSQL WHERE user_group='$group';");
				
				if ($this->db->affected_rows())
				{
				    $this->commonhelper->auditadmin('MODIFY',"Modified User Group $group","UPDATE vicidial_user_groups SET $itemSQL$otherSQL WHERE user_group='$group';");
				    $updated++;
				}
				
				$groupings = array_merge($user_group,array('permissions'=>$_POST['permiso'],'group_level'=>$_POST['group_level'])); 
				if(!$this->go_access->go_check_access_exist($group)){
				    $this->go_access->goautodialDB->where('user_group',$group);
				    $this->go_access->goautodialDB->update('user_access_group',$groupings);
				    if ($this->go_access->goautodialDB->affected_rows()) {
					$this->commonhelper->auditadmin('UPDATE',"UPDATE new Group Access: $group");
					$updated++;
				    }
				}else{
				    $this->go_access->goautodialDB->insert('user_access_group',$groupings);
				    if ($this->go_access->goautodialDB->affected_rows()) {
					$this->commonhelper->auditadmin('ADD',"Add new Group Access: $group");
					$updated++;
				    }
				}

				if ($updated) {
				    $return = "SUCCESS";
				}

			}
			
			echo $return;
		}
	}
	
	function go_check_usergroup()
	{
		$groupid = $this->uri->segment(3);
		$query = $this->db->query("SELECT * FROM vicidial_user_groups WHERE user_group='$groupid'");
		$return = $query->num_rows();
		
		if ($return)
		{
			$return = "<small style=\"color:red;\">{$this->lang->line('go_not_available')}.</small>";
		} else {
			$return = "<small style=\"color:green;\">{$this->lang->line('go_available')}.</small>";
		}
		
		echo $return;
	}
	
	function go_update_usergroup_list()
	{
	    $action = $this->uri->segment(3);
	    $groups = str_replace(',',"','",$this->uri->segment(4));

	    switch($action)
	    {
		//case "activate":
		//    $query = $this->db->query("UPDATE vicidial_user_groups SET active='Y' WHERE server_id IN ('$servers')");
		//    $this->commonhelper->auditadmin('ACTIVE','Activated Server(s): '.$this->uri->segment(4));
		//    break;
		//case "deactivate":
		//    $query = $this->db->query("UPDATE servers SET active='N' WHERE server_id IN ('$servers')");
		//    $this->commonhelper->auditadmin('INACTIVE','Deactivated Server(s): '.$this->uri->segment(4));
		//    break;
		case "delete":
		    foreach (explode("','",$groups) AS $group)
		    {
			$query = $this->db->query("DELETE FROM vicidial_user_groups WHERE user_group = '$group'");
			$this->commonhelper->auditadmin('DELETE',"Deleted User Group: $group","DELETE FROM vicidial_user_groups WHERE user_group = '$group'");
			$this->go_access->goautodialDB->where("user_group",$group);
			$this->go_access->goautodialDB->delete('user_access_group');
		    }
		    break;
	    }
	    $data['usergroups'] = $this->go_usergroups->go_get_usergroup_list();
	    $this->load->view('go_settings/go_usergroup_list',$data);
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

        function get_permission(){
            if(!empty($_POST)){
                 $result = $this->go_access->get_all_access($_POST['id']);
                 echo json_encode($result);
            }
        }
}
