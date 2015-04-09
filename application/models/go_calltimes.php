<?php
####################################################################################################
####  Name:             	go_calltimes.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1373515200                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_calltimes extends Model {

	function __construct()
	{
	    parent::Model();
	    $this->db = $this->load->database('dialerdb', true);
	}

	function go_get_calltimes_list($type=null,$dropdown=false)
	{
		$addedSQL = '';
		if (!$dropdown) {
			$tableUsed = ($type == 'state') ? "vicidial_state_call_times" : "vicidial_call_times";
			if (!is_null($this->uri->segment(6)) && strlen($this->uri->segment(6)) > 2) {
				$search = $this->uri->segment(6);
				$columnUsed = ($type=='state') ? "state_call_time_id" : "call_time_id";
				$columnUsed2 = ($type=='state') ? "state_call_time_name" : "call_time_name";
				$addedSQL = "WHERE $columnUsed RLIKE '$search' OR $columnUsed2 RLIKE '$search'";
			}
	
			$query	= $this->db->query("SELECT count(*) AS cnt FROM $tableUsed $addedSQL;");
			$total	= $query->row()->cnt;
			$limit 	= 5;
			$page	= $this->uri->segment(5);
			$rp	= ($page=='ALL') ? $total : 25;
			if (is_null($page) || $page < 1)
				$page = 1;
			$start	= (($page-1) * $rp);
	
			$return['pagelinks'] = $this->pagelinks($page,$rp,$total,$limit,$type);
			$limitSQL = "LIMIT $start,$rp";
		}
		
		if (strlen($type) < 1) {
			if ($this->uri->segment(4)!='showList')
				$addedSQL = '';
			$query = $this->db->query("SELECT call_time_id,call_time_name,ct_default_start,ct_default_stop,user_group FROM vicidial_call_times $addedSQL ORDER BY call_time_id $limitSQL;");
		} else {
			if ($this->uri->segment(4)!='showState')
				$addedSQL = '';
			$query = $this->db->query("SELECT state_call_time_id,state_call_time_state,state_call_time_name,sct_default_start,sct_default_stop,user_group FROM vicidial_state_call_times $addedSQL ORDER BY state_call_time_id $limitSQL;");
		}

		$return['list'] = $query->result();

		return $return;
	}
	
	function go_get_calltimes_info($cid,$type=null)
	{
		if (strlen($type) < 1)
			$query = $this->db->query("SELECT * FROM vicidial_call_times WHERE call_time_id='$cid';");
		else
			$query = $this->db->query("SELECT * FROM vicidial_state_call_times WHERE state_call_time_id='$cid';");

		$return = $query->row();
		return $return;
	}
	
	function go_add_state_rule($cid,$rule)
	{
		$query = $this->db->query("SELECT ct_state_call_times FROM vicidial_call_times WHERE call_time_id='$cid'");
		$new_rule = $query->row()->ct_state_call_times . "$rule|";
		$query = $this->db->query("UPDATE vicidial_call_times SET ct_state_call_times='$new_rule' WHERE call_time_id='$cid'");
		
		$result = "SUCCESS|UPDATE vicidial_call_times SET ct_state_call_times='$new_rule' WHERE call_time_id='$cid'";
		return $result;
	}
	
	function go_delete_state_rule($cid,$rule)
	{
		$query = $this->db->query("SELECT ct_state_call_times FROM vicidial_call_times WHERE call_time_id='$cid'");
		$new_rule = str_replace("||","|",str_replace($rule,"",$query->row()->ct_state_call_times));
		if ($new_rule=="|")
			$new_rule = "";
		$query = $this->db->query("UPDATE vicidial_call_times SET ct_state_call_times='$new_rule' WHERE call_time_id='$cid'");
		
		$result = "SUCCESS|UPDATE vicidial_call_times SET ct_state_call_times='$new_rule' WHERE call_time_id='$cid'";
		return $result;
	}
	
	function go_delete_calltimes($cid,$type)
	{
		if (strlen($type) < 1)
		{
			$query = $this->db->query("SELECT * FROM vicidial_call_times WHERE call_time_id='$cid'");
			if ($query->num_rows())
			{
				$query = $this->db->query("DELETE FROM vicidial_call_times WHERE call_time_id='$cid'");
				$result = "SUCCESS|DELETE FROM vicidial_call_times WHERE call_time_id='$cid'";
			} else {
				$result = "FAILED";
			}
		} else {
			$query = $this->db->query("SELECT * FROM vicidial_state_call_times WHERE state_call_time_id='$cid'");
			if ($query->num_rows())
			{
				$query = $this->db->query("DELETE FROM vicidial_state_call_times WHERE state_call_time_id='$cid'");
				$result = "SUCCESS|DELETE FROM vicidial_state_call_times WHERE state_call_time_id='$cid'";
			} else {
				$result = "FAILED";
			}
		}
		return $result;
	}
	
	function go_get_list_using_this($cid,$type=null)
	{
		if (strlen($type) < 1)
		{
			$query = $this->db->query("SELECT campaign_id,campaign_name FROM vicidial_campaigns WHERE local_call_time='$cid' ORDER BY campaign_id");
			$result['camp'] = $query->result();
			
			$query = $this->db->query("SELECT group_id,group_name FROM vicidial_inbound_groups WHERE call_time_id='$cid' ORDER BY group_id");
			$result['inb'] = $query->result();
		} else {
			$query = $this->db->query("SELECT call_time_id,call_time_name FROM vicidial_call_times WHERE ct_state_call_times rlike '\\\|$cid\\\|' ORDER BY call_time_id");
			$result['list'] = $query->result();
		}
		
		return $result;
	}
	
	function pagelinks($page,$rp,$total,$limit,$type)
	{
		$pg 	= $this->commonhelper->paging($page,$rp,$total,$limit);
		$start	= (($page-1) * $rp);
	
		if ($pg['last'] > 1) {
			$pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top:10px;">';
			$pagelinks .= '<a title="'.$this->lang->line('go_to_1').'" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['first'].')"><span><img src="'.base_url().'/img/first.gif"></span></a>';
			$pagelinks .= '<a title="'.$this->lang->line('go_to_prev_p').'" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['prev'].')"><span><img src="'.base_url().'/img/prev.gif"></span></a>';
			
			for ($i=$pg['start'];$i<=$pg['end'];$i++) { 
			   if ($i==$pg['page']) $current = 'color: #F00;cursor: default;'; else $current="";
			
			$pagelinks .= '<a title="'.$this->lang->line('go_to_page').' '.$i.'" style="vertical-align:text-top;padding: 0px 2px;'.$current.'" onclick="changePage('.$i.')"><span>'.$i.'</span></a>';
			
			}
	
			$pagelinks .= '<a title="'.$this->lang->line('go_to_view_all').'" style="vertical-align:text-top;padding: 0px 2px;" onclick="changePage(\'ALL\')"><span>ALL</span></a>';
			$pagelinks .= '<a title="'.$this->lang->line('go_to_next').'" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['next'].')"><span><img src="'.base_url().'/img/next.gif"></span></a>';
			$pagelinks .= '<a title="'.$this->lang->line('go_to_last').'" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['last'].')"><span><img src="'.base_url().'/img/last.gif"></span></a>';
			$pagelinks .= '</div>';
		} else {
			if ($rp > 25) {
				$pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top:10px;">';
				$pagelinks .= '<a title="'.$this->lang->line('go_to_back_pag').'" style="vertical-align:text-top;padding: 0px 2px;" onclick="changePage(1)"><span>BACK</span></a>';
				$pagelinks .= '</div>';
			} else {
				$pagelinks = "";
			}
		}
		
		$pageinfo = "<span style='float:right;padding-top:10px;'>{$this->lang->line('go_displaying')} {$pg['istart']} {$this->lang->line('go_to')} {$pg['iend']} {$this->lang->line('go_of')} {$pg['total']} $type {$this->lang->line('go_call_times_s')}</span>";
		
		$return['links'] = $pagelinks;
		$return['info'] = $pageinfo;
		
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
	
	function go_list_server_ips()
	{
		$query = $this->db->query("SELECT server_ip,server_description FROM servers WHERE active='Y';");
		$return = $query->result();
		return $return;
	}

	function go_get_usergroups()
	{
	    $query = $this->db->query("select user_group,group_name from vicidial_user_groups");
	    $groups = $query->result();
	    return $groups;
	}
	
	function go_get_systemsettings()
	{
		$query = $this->db->query("SELECT use_non_latin,enable_queuemetrics_logging,enable_vtiger_integration,qc_features_active,outbound_autodial_active,sounds_central_control_active,enable_second_webform,user_territories_active,custom_fields_enabled,admin_web_directory,webphone_url,first_login_trigger,hosted_settings,default_phone_registration_password,default_phone_login_password,default_server_password,test_campaign_calls,active_voicemail_server,voicemail_timezones,default_voicemail_timezone,default_local_gmt,campaign_cid_areacodes_enabled,pllb_grouping_limit,did_ra_extensions_enabled,expanded_list_stats,contacts_enabled,alt_log_server_ip,alt_log_dbname,alt_log_login,alt_log_pass,tables_use_alt_log_db FROM system_settings");
		$settings = $query->row();
		return $settings;
	}

}
