<?php
####################################################################################################
####  Name:             	go_ingroup.php                                                      ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####      	                <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  Edited by:            GoAutoDial Development Team                                         ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_ingroup extends Controller {

    function __construct()
	{
        parent::Controller();
        $this->load->model('goingroup');
        $this->load->library(array('session','userhelper','commonhelper'));
	$this->lang->load('userauth', $this->session->userdata('ua_language'));
	$this->load->helper('language');
	$this->is_logged_in();
    }

	function index()
	{
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

		$data['theme'] = $this->session->userdata('go_theme');
		$data['bannertitle'] = $this->lang->line('go_Inbound');
		$data['adm']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;

		$data['userfulname'] = $this->goingroup->go_get_userfulname();
		
		$data['permissions'] = $this->commonhelper->getPermissions("inbound",$this->session->userdata("user_group"));
		
		/* insert post values */ 
	    $addSUBMIT = $this->input->post('addSUBMIT');
	    $addDID = $this->input->post('addDID');
	    $addCALLMENU = $this->input->post('addCALLMENU');
		
        if($addSUBMIT) {
			$message = '';
			$group_id = $this->input->post('group_id');
			$group_name = $this->input->post('group_name');
			$group_color = $this->input->post('group_color');
			$active = $this->input->post('active');
			$web_form_address = $this->input->post('web_form_address');	     
			$voicemail_ext = $this->input->post('voicemail_ext');
			$next_agent_call = $this->input->post('next_agent_call');
			$fronter_display = $this->input->post('fronter_display');
			$script_id = $this->input->post('script_id');
			$get_call_launch = $this->input->post('get_call_launch');
			$user_group = $this->input->post('user_group');
			
			$message = $this->goingroup->insertingroup($accounts, $users, $group_id, $group_name, $group_color, $active, $web_form_address, $voicemail_ext, $next_agent_call, $fronter_display, $script_id, $get_call_launch, $user_group);
   
			if($message !=null) {
				print "<script type=\"text/javascript\">alert('{$this->lang->line("go_group_not_added")}');</script>";
			} else {
				header("Location: #");
			}
		}
		  
		if($addDID) {
			$message = '';
			$did_description = $this->input->post('did_description');
			$did_pattern = $this->input->post('did_pattern');
			$did_route = $this->input->post('did_route');
			$user_group = $this->input->post('user_group');
			switch($did_route)
			{
				case "AGENT":
					$diddata['user'] = $this->input->post('user');
					$diddata['user_unavailable_action'] = $this->input->post('user_unavailable_action');
					$diddata['user_route_settings_ingroup'] = $this->input->post('user_route_settings_ingroup');
					$db_column = ",user,user_unavailable_action,user_route_settings_ingroup";
					break;
				
				case "EXTEN":
					$diddata['extension'] = $this->input->post('extension');
					$diddata['exten_context'] = $this->input->post('exten_context');
					$db_column = ",extension,exten_context";
					break;
				
				case "VOICEMAIL":
					$diddata['voicemail_ext'] = $this->input->post('voicemail_ext');
					$db_column = ",voicemail_ext";
					break;
				
				case "PHONE":
					$diddata['phone'] = $this->input->post('phone');
					$diddata['server_ip'] = $this->input->post('server_ip');
					$db_column = ",phone,server_ip";
					break;
				
				case "IN_GROUP":
					$diddata['group_id'] = $this->input->post('group_id');
					$diddata['call_handle_method'] = (strlen($this->input->post('call_handle_method')) > 0) ? $this->input->post('call_handle_method') : "CIDLOOKUP";
					$diddata['agent_search_method'] = (strlen($this->input->post('agent_search_method')) > 0) ? $this->input->post('agent_search_method') : "LB";
					$diddata['list_id'] = $this->input->post('list_id');
					$diddata['campaign_id'] = $this->input->post('campaign_id');
					$diddata['phone_code'] = (strlen($this->input->post('phone_code')) > 0) ? $this->input->post('phone_code') : "1";
					$db_column = ",group_id,call_handle_method,agent_search_method,list_id,campaign_id,phone_code";
					break;
				
				case "CALLMENU":
					$diddata['menu_id'] = $this->input->post('menu_id');
					$db_column = ",menu_id";
					break;
			}
			$message = $this->goingroup->insertdid($did_pattern,$did_description,$user, $user_group, $diddata, $db_column, $did_route);

			if($message !=null) {
				print "<script type=\"text/javascript\">alert('{$this->lang->line("go_did_not_added")}');</script>";
			} else {
				header("Location: #");
			}

		}

		if($addCALLMENU) { 
			$menu_id = $this->input->post('menu_id');
			$menu_name = $this->input->post('menu_name');
			$user_group = $this->input->post('user_group');
			$options['menu_prompt'] = $this->input->post('menu_prompt');
			$options['menu_timeout'] = $this->input->post('menu_timeout');
			$options['menu_timeout_prompt'] = $this->input->post('menu_timeout_prompt');
			$options['menu_invalid_prompt'] = $this->input->post('menu_invalid_prompt');
			$options['menu_repeat'] = $this->input->post('menu_repeat');
			$options['menu_time_check'] = $this->input->post('menu_time_check');
			$options['call_time_id'] = $this->input->post('call_time_id');
			$options['track_in_vdac'] = $this->input->post('track_in_vdac');
			$options['custom_dialplan_entry'] = $this->input->post('custom_dialplan_entry');
			$options['tracking_group'] = $this->input->post('tracking_group');

			for ($ctr=0;$ctr<10;$ctr++)
			{
				if (strlen($this->input->post('option_value_'.$ctr)) < 1)
					break;
				
				$option_route[$ctr]['option_value'] = $this->input->post('option_value_'.$ctr);
				$option_route[$ctr]['option_description'] = $this->input->post('option_description_'.$ctr);
				$option_route[$ctr]['option_route'] = $this->input->post('option_route_'.$ctr);
				$option_route[$ctr]['option_route_value'] = $this->input->post('option_route_value_'.$ctr);
				
				switch ($this->input->post('option_route_'.$ctr))
				{
					case "EXTENSION":
						$option_route[$ctr]['option_route_value_context'] = $this->input->post('option_route_value_context_'.$ctr);
						break;
					
					case "INGROUP":
						$option_route[$ctr]['option_route_value_context'] = $this->input->post('handle_method_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('search_method_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('list_id_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('campaign_id_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('phone_code_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('enter_filename_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('id_number_filename_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('confirm_filename_'.$ctr).',';
						$option_route[$ctr]['option_route_value_context'] .= $this->input->post('validate_digits_'.$ctr);
						break;
				}
				
			}

		 	$message = $this->goingroup->insertcallmenu($menu_id,$menu_name,$user_group,$options,$option_route);
		 	header("Location: #");
		}

        $data['go_main_content'] = 'go_ingroup/go_ingroup';
	
        $this->load->view('includes/go_dashboard_template',$data);
	}
	
	function go_ingroup_list()
	{
		$data['permissions'] = $this->commonhelper->getPermissions("campaign",$this->session->userdata("user_group"));
		$data['userBrowser'] = $this->commonhelper->getBrowser($_SERVER['HTTP_USER_AGENT']);
		
		$data['ingrouplists'] = $this->goingroup->getallingroup($this->session->userdata("user_group"));
		$data['ingpagelinks'] = $this->goingroup->showpagelinks('ingroup');
		$data['getdids'] = $this->goingroup->getdids($this->session->userdata("user_group"));
		$data['didpagelinks'] = $this->goingroup->showpagelinks('did');
        $data['getallcallmenus'] = $this->goingroup->getallcallmenus($this->session->userdata("user_group"));
		$data['ivrpagelinks'] = $this->goingroup->showpagelinks('ivr');

        $this->load->view('go_ingroup/go_ingroup_list',$data);
	}
	
	function go_get_settings()
	{
		$type = $this->uri->segment(3);
		$group = $this->uri->segment(4);
		$page	= $this->uri->segment(5);
		if (is_null($page) || $page < 1) { $page = 1; }
		$total 	= $this->goingroup->get_agent_count();
		$rp 	= 20;
		$limit 	= 5;
		$start	= (($page-1) * $rp);
		$pagelinks = $this->goingroup->pagelinks($group,$page,$rp,$total,$limit);
		
		$data['group_settings'] = $this->goingroup->go_get_settings($type,$group);
		$data['script_list'] = $this->goingroup->scriptlists($this->session->userdata('user_group'));
		if ($type == 'ingroup') {
			$data['agent_ranks'] = $this->goingroup->agentranks($group,$start,$rp,$pagelinks['links']);
		}
		$data['active_ingroups'] = $this->goingroup->go_get_active_ingroups();
		$data['active_campaigns'] = $this->goingroup->go_get_active_campaigns();
		$data['active_dids'] = $this->goingroup->go_get_active_dids();
		$data['call_menus'] = $this->goingroup->go_get_call_menus();
		$data['call_times'] = $this->goingroup->go_get_call_times();
		$data['user_list'] = $this->goingroup->go_get_active_users();
		$data['phone_list'] = $this->goingroup->go_get_active_phones();
		$data['server_list'] = $this->goingroup->go_get_active_servers();
		$data['filter_phone_groups'] = $this->goingroup->go_get_filter_phone_groups();
		$data['call_menu_options'] = $this->goingroup->go_get_callmenu_options($group);
		$data['gouser'] = $this->session->userdata('user_name');
		$data['page'] = $page;
		$data['type'] = $type;

		$this->load->view('go_ingroup/go_ingroup_view',$data);
	}
	
	function go_change_page($type,$page,$search=null)
	{
		$total 	= $this->goingroup->get_ingroup_count($type);
		$rp	= ($page=='ALL') ? $total : 25;
		$limit 	= 5;
		if (is_null($page) || $page < 1) { $page = 1; }
		$start	= (($page-1) * $rp);
		
		switch ($type)
		{
			case "ingroup":
				$data['ingrouplists'] = $this->goingroup->getallingroup($this->session->userdata("user_group"),$start,$rp,$search);
				$data['ingpagelinks'] = $this->goingroup->showpagelinks('ingroup',$page,$rp,$total,$limit,$search);
				$data['getdids'] = $this->goingroup->getdids($this->session->userdata("user_group"));
				$data['didpagelinks'] = $this->goingroup->showpagelinks('did');
				$data['getallcallmenus'] = $this->goingroup->getallcallmenus($this->session->userdata("user_group"));
				$data['ivrpagelinks'] = $this->goingroup->showpagelinks('ivr');
				break;
			
			case "did":
				$data['getdids'] = $this->goingroup->getdids($this->session->userdata("user_group"),$start,$rp,$search);
				$data['didpagelinks'] = $this->goingroup->showpagelinks('did',$page,$rp,$total,$limit,$search);
				$data['getallcallmenus'] = $this->goingroup->getallcallmenus($this->session->userdata("user_group"));
				$data['ivrpagelinks'] = $this->goingroup->showpagelinks('ivr');
				$data['ingrouplists'] = $this->goingroup->getallingroup($this->session->userdata("user_group"));
				$data['ingpagelinks'] = $this->goingroup->showpagelinks('ingroup');
				break;
			
			case "ivr":
				$data['getallcallmenus'] = $this->goingroup->getallcallmenus($this->session->userdata("user_group"),$start,$rp,$search);
				$data['ivrpagelinks'] = $this->goingroup->showpagelinks('ivr',$page,$rp,$total,$limit,$search);
				$data['ingrouplists'] = $this->goingroup->getallingroup($this->session->userdata("user_group"));
				$data['ingpagelinks'] = $this->goingroup->showpagelinks('ingroup');
				$data['getdids'] = $this->goingroup->getdids($this->session->userdata("user_group"));
				$data['didpagelinks'] = $this->goingroup->showpagelinks('did');
				break;
		}
		
        $this->load->view('go_ingroup/go_ingroup_list',$data);
	}
	
	function go_change_ranks_page($group,$page,$search)
	{
		$total 	= $this->goingroup->get_agent_count($search);
		$rp	= ($page=='ALL') ? $total : 20;
		$limit 	= 5;
		if (is_null($page) || $page < 1) { $page = 1; }
		$start	= (($page-1) * $rp);
		$pagelinks	= $this->goingroup->pagelinks($group,$page,$rp,$total,$limit);
		$agent_ranks	= $this->goingroup->agentranks($group,$start,$rp,$pagelinks['links'],$search);
		
		echo $agent_ranks;
	}
	
	function go_ingroup_wizard()
	{
		$data['wiztype'] = $this->uri->segment(3);
		$data['script_list'] = $this->goingroup->scriptlists($this->session->userdata('user_group'));
		$data['usergroups'] = $this->goingroup->go_get_usergroups($this->session->userdata('user_group'));
		$data['agent_list'] = $this->goingroup->go_get_active_users();
		$data['active_ingroups'] = $this->goingroup->go_get_active_ingroups();
		$data['phone_list'] = $this->goingroup->go_get_active_phones();
		$data['server_list'] = $this->goingroup->go_get_active_servers();
		$data['call_menus'] = $this->goingroup->go_get_call_menus();
		$data['call_times'] = $this->goingroup->go_get_call_times();
		$data['gouser'] = $this->session->userdata('user_name');

		$this->load->view('go_ingroup/go_ingroup_wizard',$data);
	}
	
	function go_update_ingroup_list()
	{
		$action = $this->uri->segment(3);
		$ingroups = str_replace(",","','",$this->uri->segment(4));
        $this->asteriskDB = $this->load->database('dialerdb',TRUE);
		$active = (strtolower($action) == 'activate') ? "Y" : "N";
		$status = (strtolower($action) == 'activate') ? "ACTIVE" : "INACTIVE";
		
		$query = $this->asteriskDB->query("UPDATE vicidial_inbound_groups SET active='$active' WHERE group_id IN ('$ingroups');");
		$this->commonhelper->auditadmin('MODIFY',"Status of In-group ID(s) $ingroups set to $status","UPDATE vicidial_inbound_groups SET active='$active' WHERE group_id IN ('$ingroups');");
	}
	
	function go_update_did_list()
	{
		$action = $this->uri->segment(3);
		$dids = str_replace(",","','",$this->uri->segment(4));
        $this->asteriskDB = $this->load->database('dialerdb',TRUE);
		$active = (strtolower($action) == 'activate') ? "Y" : "N";
		$status = (strtolower($action) == 'activate') ? "ACTIVE" : "INACTIVE";
		
		$query = $this->asteriskDB->query("UPDATE vicidial_inbound_dids SET did_active='$active' WHERE did_id IN ('$dids');");
		$this->commonhelper->auditadmin('MODIFY',"Status of DID(s) $dids set to $status","UPDATE vicidial_inbound_dids SET did_active='$active' WHERE group_id IN ('$dids');");
	}
	
	function go_check_ingroup()
	{
		$menu_id = $this->uri->segment(3);
        $this->asteriskDB = $this->load->database('dialerdb',TRUE);
		$query = $this->asteriskDB->query("SELECT * FROM vicidial_call_menu WHERE menu_id = '$menu_id'");
		$return = $query->num_rows();
		
		$reserved_id = array(
			'vicidial',
			'vicidial-auto',
			'general',
			'globals',
			'default',
			'trunkinbound',
			'loopback-no-log',
			'monitor_exit',
			'monitor'
		);
		
		$exist_on_reserved_id = false;
		foreach ($reserved_id as $resid)
		{
			if ($menu_id === $resid)
				$exist_on_reserved_id = true;
		}
		
		if ($return || $exist_on_reserved_id)
		{
			$return = "<small style=\"color:red;\">{$this->lang->line('go_not_available')}</small>";
		} else {
			$return = "<small style=\"color:green;\">{$this->lang->line('go_available')}</small>";
		}
		
		echo $return;
	}

	function chooser() {
		$this->load->view('go_ingroup/go_chooser',$data);
	}
    
	function showoption() {
		$this->load->view('go_ingroup/go_values',$data);
	}
	
	function editsubmit() {
		$this->load->view('go_ingroup/go_values',$data);
	}
	
	function checkagentrank(){
		$this->load->view('go_ingroup/go_values',$data);
	}
	
	function deletesubmit() {
		$this->load->view('go_ingroup/go_values',$data);
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
