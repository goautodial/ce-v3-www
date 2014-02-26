<?php
########################################################################################################
####  Name:             	go_usergroups.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_usergroups extends Model {

	function __construct()
	{
	    parent::Model();
	    $this->db = $this->load->database('dialerdb', true);
	    //$this->a2db = $this->load->database('billingdb', true);
	}
	
	function count_usergrouplist()
	{
		$stmt ="SELECT count(user_group) as cntugrp FROM vicidial_user_groups order by user_group"; 
		$query = $this->db->query($stmt);
		$row = $query->row_array();
		$cntugrp = $row['cntugrp'];
		return $cntugrp;
	}

	
	function go_get_usergroup_list()
	{
		$ul = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "WHERE user_group='".$this->session->userdata('user_group')."'" : "";
		
		if ($this->uri->segment(3)=='search') {
			if (!is_null($this->uri->segment(5)) && strlen($this->uri->segment(5)) > 2) {
				$search = $this->uri->segment(5);
				$addedSQL = "(user_group RLIKE '$search' OR group_name RLIKE '$search')";
				$addedSQL = (strlen($ul) < 1) ? "WHERE $addedSQL" : "AND $addedSQL";
			}
			$return['search'] = $search;
		}

		$query	= $this->db->query("SELECT count(*) AS cnt FROM vicidial_user_groups $ul $addedSQL;");
		$total	= $query->row()->cnt;
		$limit 	= 5;
		$page	= $this->uri->segment(4);
		$rp	= ($page=='ALL') ? $total : 25;
		if (is_null($page) || $page < 1)
			$page = 1;
		$start	= (($page-1) * $rp);

		$return['pagelinks'] = $this->pagelinks($page,$rp,$total,$limit);
		  
		$query = $this->db->query("SELECT user_group,group_name,forced_timeclock_login FROM vicidial_user_groups $ul $addedSQL ORDER BY user_group LIMIT $start,$rp;");
		$return['list'] = $query->result();

		return $return;
	}

	function go_get_groupid()
	{
		$userID = $this->session->userdata('user_name');
	    $query = $this->db->query("select user_group from vicidial_users where user='$userID'");
	    $resultsu = $query->row();
	    $groupid = $resultsu->user_group;
	    return $groupid;
	}

	function go_get_userfulname()
	{
		$userID = $this->session->userdata('user_name');
		$query = $this->db->query("select full_name from vicidial_users where user='$userID';");
		$resultsu = $query->row();
		$userfulname = $resultsu->full_name;
		return $userfulname;
	}
	
	function go_list_templates()
	{
		$query = $this->db->query("SELECT template_id as id,template_name as name from vicidial_conf_templates order by template_id;");
		$return = $query->result();
		return $return;
	}

	function go_get_usergroups()
	{
	    $query = $this->db->query("select user_group,group_name from vicidial_user_groups");
	    $groups = $query->result();
	    return $groups;
	}
	
	function go_get_campaigns()
	{
		$query = $this->db->query("select campaign_id,campaign_name from vicidial_campaigns order by campaign_id");
		$campaigns = $query->result();
		return $campaigns;
	}
	
	function go_get_shifts()
	{
		$query = $this->db->query("select shift_id,shift_name from vicidial_shifts order by shift_id");
		$shifts = $query->result();
		return $shifts;
	}
	
	function go_get_calltimes()
	{
		$query = $this->db->query("select call_time_id,call_time_name from vicidial_call_times order by call_time_id");
		$calltimes = $query->result();
		return $calltimes;
	}
	
	function go_get_systemsettings()
	{
		$query = $this->db->query("SELECT use_non_latin,enable_queuemetrics_logging,enable_vtiger_integration,qc_features_active,outbound_autodial_active,sounds_central_control_active,enable_second_webform,user_territories_active,custom_fields_enabled,admin_web_directory,webphone_url,first_login_trigger,hosted_settings,default_phone_registration_password,default_phone_login_password,default_server_password,test_campaign_calls,active_voicemail_server,voicemail_timezones,default_voicemail_timezone,default_local_gmt,campaign_cid_areacodes_enabled,pllb_grouping_limit,did_ra_extensions_enabled,expanded_list_stats,contacts_enabled,alt_log_server_ip,alt_log_dbname,alt_log_login,alt_log_pass,tables_use_alt_log_db FROM system_settings");
		$settings = $query->row();
		return $settings;
	}
	
	function pagelinks($page,$rp,$total,$limit)
	{
		$pg 	= $this->commonhelper->paging($page,$rp,$total,$limit);
		$start	= (($page-1) * $rp);
	
		if ($pg['last'] > 1) {
			$pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top: 10px;">';
			$pagelinks .= '<a title="Go to First Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['first'].')"><span><img src="'.base_url().'/img/first.gif"></span></a>';
			$pagelinks .= '<a title="Go to Previous Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['prev'].')"><span><img src="'.base_url().'/img/prev.gif"></span></a>';
			
			for ($i=$pg['start'];$i<=$pg['end'];$i++) { 
			   if ($i==$pg['page']) $current = 'color: #F00;cursor: default;'; else $current="";
			
			$pagelinks .= '<a title="Go to Page '.$i.'" style="vertical-align:text-top;padding: 0px 2px;'.$current.'" onclick="changePage('.$i.')"><span>'.$i.'</span></a>';
			
			}
	
			$pagelinks .= '<a title="View All Pages" style="vertical-align:text-top;padding: 0px 2px;" onclick="changePage(\'ALL\')"><span>ALL</span></a>';
			$pagelinks .= '<a title="Go to Next Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['next'].')"><span><img src="'.base_url().'/img/next.gif"></span></a>';
			$pagelinks .= '<a title="Go to Last Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['last'].')"><span><img src="'.base_url().'/img/last.gif"></span></a>';
			$pagelinks .= '</div>';
		} else {
			if ($rp > 25) {
				$pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top:10px;">';
				$pagelinks .= '<a title="Back to Paginated View" style="vertical-align:text-top;padding: 0px 2px;" onclick="changePage(1)"><span>BACK</span></a>';
				$pagelinks .= '</div>';
			} else {
				$pagelinks = "";
			}
		}
		
		$pageinfo = "<span style='float:right;padding-top:10px;'>Displaying {$pg['istart']} to {$pg['iend']} of {$pg['total']} user groups</span>";
		
		$return['links'] = $pagelinks;
		$return['info'] = $pageinfo;
		
		return $return;
	}

}
