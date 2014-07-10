<?php
####################################################################################################
####  Name:             	go_campaign.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Originated by:        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_campaign extends Model {

	function __construct()
	{
	    parent::Model();
	    $this->db = $this->load->database('dialerdb', true);
	    $this->godb = $this->load->database('goautodialdb', true);
	    //$this->a2db = $this->load->database('billingdb', true);
	}

	function go_get_campaigns($ifNotPage)
	{
		$groupId = $this->go_get_groupid();
		    $base = base_url();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
		   $ul='';
		}
		else
		{
		   $ul = "WHERE user_group='$groupId'";
		}
	    
		$page	= (! $ifNotPage) ? $this->uri->segment(3) : 1;
		if ((is_numeric($page) || $page=='ALL') && !is_null($this->uri->segment(4)) && strlen($this->uri->segment(4)) > 2) {
			$search   = addcslashes(mysql_real_escape_string($this->uri->segment(4)),"%");
			if (strlen($search)>0) {
				$addedSQL = "(campaign_id RLIKE '$search' OR campaign_name RLIKE '$search')";
			}
			$addedSQL = (strlen($ul) < 1) ? "WHERE $addedSQL" : "AND $addedSQL";
		}
		//$addedSQL = '';

		$query	= $this->db->query("SELECT count(*) AS cnt FROM vicidial_campaigns $ul $addedSQL;");
		$total	= $query->row()->cnt;
		$limit 	= 5;
		$rp	= ($this->uri->segment(3)=='ALL') ? $total : 25;
		if (is_null($page) || $page < 1 || !is_numeric($page))
			$page = 1;
		$start	= (($page-1) * $rp);

		$return['pagelinks'] = $this->pagelinks($groupId,$page,$rp,$total,$limit);

		$query = $this->db->query("SELECT campaign_id,campaign_name,active,dial_method,auto_dial_level FROM vicidial_campaigns $ul $addedSQL ORDER BY campaign_id limit $start,$rp;");
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

	function go_get_campaign_info($camp)
	{
		$query = $this->db->query("SELECT campaign_name,campaign_description,web_form_address,dial_method,auto_dial_level,campaign_script,campaign_cid,
									campaign_recording,campaign_rec_filename,campaign_vdad_exten,local_call_time, TRIM(TRAILING ' -' FROM LTRIM(dial_statuses)) AS dial_statuses,
									campaign_changedate,campaign_logindate,campaign_calldate,active,park_ext,web_form_target,allow_closers,campaign_allow_inbound,
									lead_order,lead_order_randomize,lead_order_secondary,list_order_mix,lead_filter_id,drop_lockout_time,hopper_level,use_auto_hopper,
									auto_hopper_multi,auto_trim_hopper,available_only_ratio_tally,adaptive_dropped_percentage,adaptive_maximum_level,
									adaptive_latest_server_time,adaptive_intensity,adaptive_dl_diff_target,concurrent_transfers,queue_priority,drop_rate_group,
									inbound_queue_no_dial,auto_alt_dial,next_agent_call,dial_timeout,dial_prefix,manual_dial_prefix,omit_phone_code,use_custom_cid,
									campaign_rec_exten,allcalls_delay,per_call_notes,agent_lead_search,agent_lead_search_method,get_call_launch,am_message_exten,
									waitforsilence_options,amd_send_to_vmx,cpd_amd_action,xferconf_a_dtmf,xferconf_a_number,xferconf_b_dtmf,xferconf_b_number,
									xferconf_c_number,xferconf_d_number,xferconf_e_number,enable_xfer_presets,hide_xfer_number_to_dial,quick_transfer_button,
									prepopulate_transfer_preset,ivr_park_call,ivr_park_call_agi,timer_action,timer_action_message,timer_action_seconds,
									timer_action_destination,alt_number_dialing,scheduled_callbacks,scheduled_callbacks_alert,scheduled_callbacks_count,my_callback_option,
									drop_call_seconds,drop_action,safe_harbor_exten,voicemail_ext,drop_inbound_group,wrapup_seconds,wrapup_message,use_internal_dnc,
									use_campaign_dnc,agent_pause_codes_active,auto_pause_precall,auto_resume_precall,auto_pause_precall_code,campaign_stats_refresh,
									realtime_agent_time_stats,disable_alter_custdata,disable_alter_custphone,no_hopper_leads_logins,no_hopper_dialing,agent_dial_owner_only,
									agent_display_dialable_leads,display_queue_count,view_calls_in_queue,view_calls_in_queue_launch,grab_calls_in_queue,call_requeue_button,
									pause_after_each_call,manual_dial_override,manual_dial_list_id,manual_dial_filter,manual_preview_dial,manual_dial_call_time_check,
									api_manual_dial,manual_dial_cid,post_phone_time_diff_alert,agent_clipboard_copy,agent_extended_alt_dial,three_way_call_cid,
									three_way_dial_prefix,customer_3way_hangup_logging,customer_3way_hangup_seconds,customer_3way_hangup_action,agent_allow_group_alias,
									crm_popup_login,crm_login_address,start_call_url,dispo_call_url,extension_appended_cidname,blind_monitor_warning,blind_monitor_message,
									blind_monitor_filename,closer_campaigns,default_xfer_group,xfer_groups,survey_first_audio_file,survey_dtmf_digits,survey_ni_digit,
									survey_opt_in_audio_file,survey_ni_audio_file,survey_method,survey_menu_id,survey_no_response_action,survey_response_digit_map,survey_third_status,
									survey_third_audio_file,survey_third_digit,survey_third_exten,survey_fourth_audio_file,survey_fourth_digit,survey_fourth_status,
									survey_fourth_exten,survey_xfer_exten,survey_camp_record_dir,survey_ni_status,survey_wait_sec,user_group
									FROM vicidial_campaigns	WHERE campaign_id='$camp'");
		$campinfo = $query->row();

		return $campinfo;
	}

	function go_get_campaign_lists($camp)
	{
		$query = $this->db->query("SELECT list_id,list_name,list_description,active,list_lastcalldate,list_changedate FROM vicidial_lists WHERE campaign_id='$camp' ORDER BY list_id");
		$list_ids = $query->result();

		foreach ($list_ids as $row)
		{
			$query = $this->db->query("SELECT count(*) AS count FROM vicidial_list WHERE list_id='".$row->list_id."'");
			$camplist[$row->list_id]['list_id'] = $row->list_id;
			$camplist[$row->list_id]['list_name'] = $row->list_name;
			$camplist[$row->list_id]['list_description'] = $row->list_description;
			$camplist[$row->list_id]['active'] = $row->active;
			$camplist[$row->list_id]['list_lastcalldate'] = $row->list_lastcalldate;
			$camplist[$row->list_id]['list_changedate'] = $row->list_changedate;
			$camplist[$row->list_id]['leads'] = $query->row();
		}

		$return['camplist']	= $camplist;
		return $return;
	}

	function go_get_allowed_scripts()
	{
		$userID = $this->session->userdata('user_name');
	    $groupId = $this->go_get_groupid();
	    if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul = '';
	    }
	    else
	    {
	       $ul = "WHERE user_group='$groupId'";
	    }

		$query = $this->db->query("SELECT script_id,script_name,active FROM vicidial_scripts $ul");
		$campscripts = $query->result();

		return $campscripts;
	}

	function go_get_allowed_campaigns($raw=false)
	{
	    $groupId = $this->go_get_groupid();
	    if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $ul = "WHERE user_group='$groupId'";
	    }

		if (!$raw) {
			$query = $this->db->query("SELECT TRIM(REPLACE(allowed_campaigns,'-','')) AS allowed_campaigns FROM vicidial_user_groups $ul");
			$row = $query->row();

			$allowed_campaigns = str_replace(' ',"','",$row->allowed_campaigns);
		}
		else
		{
			$query = $this->db->query("SELECT allowed_campaigns FROM vicidial_user_groups $ul");
			$row = $query->row();

			$allowed_campaigns = $row->allowed_campaigns;
		}
		return $allowed_campaigns;
	}

	function go_get_call_times()
	{
	    $groupId = $this->go_get_groupid();
	    if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $ul = "WHERE user_group IN ('$groupId','---ALL---')";
	    }

		$query = $this->db->query("SELECT call_time_id,call_time_name,ct_default_start,ct_default_stop FROM vicidial_call_times $ul ORDER BY call_time_id");
		$campcalltimes = $query->result();

		return $campcalltimes;
	}
	
	function go_get_call_menu()
	{
		$groupId = $this->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
		   $ul='';
		}
		else
		{
		   $ul = "WHERE user_group IN ('$groupId')";
		}
		
		$query = $this->db->query("SELECT menu_id,menu_name FROM vicidial_call_menu $ul ORDER BY menu_id;");
		$callmenus = $query->result();
		
		return $callmenus;
	}

	function go_get_leads_on_hopper($camp)
	{
		$query = $this->db->query("SELECT count(*) AS count FROM vicidial_hopper WHERE campaign_id='$camp'");
		$leads_on_hopper = $query->row();

		return $leads_on_hopper;
	}

	function go_get_settings()
	{
		$campaignID = $this->uri->segment(3);
		$campinfo = $this->go_get_campaign_info($campaignID);

		switch ($campinfo->dial_method)
		{
			case "RATIO":
				$data['dial_method'] = "AUTO_DIAL";
				break;
			case "ADAPT_AVERAGE":
				$data['dial_method'] = "PREDICTIVE";
				break;
			default:
				$data['dial_method'] = $campinfo->dial_method;
		}

		switch ($campinfo->auto_dial_level)
		{
			case "0":
				$data['auto_dial_level'] = "OFF";
				break;
			case "1.0":
				$data['auto_dial_level'] = "SLOW";
				break;
			case "2.0":
				$data['auto_dial_level'] = "NORMAL";
				break;
			case "4.0":
				$data['auto_dial_level'] = "HIGH";
				break;
			case "6.0":
				$data['auto_dial_level'] = "MAX";
				break;
			default:
				$data['auto_dial_level'] = "ADVANCE";
				$data['auto_dial_level_adv'] = $campinfo->auto_dial_level;
		}

		$query = $this->db->query("SELECT number_of_lines,status FROM vicidial_remote_agents WHERE campaign_id='$campaignID'");
		$data['remoteinfo'] = $query->row();

		$data['closer_campaigns'] = $campinfo->closer_campaigns;
		$data['dial_statuses'] = $this->go_get_statuses($campaignID);
		$data['camplist'] = $this->go_get_campaign_lists($campaignID);
		$data['campscripts'] = $this->go_get_allowed_scripts();
		$data['campcalltimes'] = $this->go_get_call_times();
		$data['leads_on_hopper'] = $this->go_get_leads_on_hopper($campaignID);
		$data['call_menus'] = $this->go_get_call_menu();
		$data['campinfo'] = $campinfo;
		$data['campaign_id'] = $campaignID;

		return $data;
	}

	function go_update_settings()
	{
		$campaignID = $this->uri->segment(3);
		$status = $this->uri->segment(4);
		$args = $this->uri->segment(5);
		$NOW = date("Y-m-d H:i:s");

		$basic = explode(',', $args);

		if ($this->uri->segment(7) == 1)
		{
		    $args = explode(',',$this->uri->segment(6));

		    foreach ($args as $item)
		    {
				list($name, $value) = explode(':',$item);
				if ($name=='lead_order')
				{
					$value = str_replace('_',' ',$value);
				}
				
				if ($name=='waitforsilence_options')
				{
					$value = str_replace('+',',',$value);
				}
				
				if ($name!='force_reset_hopper')
				{
					$advance .= ",$name='$value'";
				} else {
					$reset_hopper = $value;
				}
		    }

			$inbound_groups = str_replace(',',' ',$this->uri->segment(8));
			$advance .= ",closer_campaigns=' $inbound_groups -'";
		
                        $tranfers_groups = str_replace(',',' ',$this->uri->segment(11));
                        $advancetrans .= ",xfer_groups=' $tranfers_groups -'";
		}

		if ($basic[7] == '8368' || $basic[7] == '8369')
		{
			switch ($basic[1])
			{
				case "AUTO_DIAL":
					$dial_method = "RATIO";
					break;
				case "PREDICTIVE":
					$dial_method = "ADAPT_AVERAGE";
					break;
				default:
					$dial_method = $basic[1];
			}

			switch ($basic[2])
			{
				case "OFF":
					$auto_dial_level = "0";
					break;
				case "SLOW":
					$auto_dial_level = "1.0";
					break;
				case "NORMAL":
					$auto_dial_level = "2.0";
					break;
				case "HIGH":
					$auto_dial_level = "4.0";
					break;
				case "MAX":
					$auto_dial_level = "6.0";
					break;
				default:
					if ($basic[2] == "ADVANCE")
						$auto_dial_level = $basic[3];
					else
						$auto_dial_level = $basic[2];
			}
			
			$dial_prefixARY = explode("_",$basic[11]);
			if ($dial_prefixARY[0] != "CUSTOM")
			{
				$query = $this->db->query("SELECT dialplan_entry FROM vicidial_server_carriers WHERE carrier_id='{$basic[11]}'");
				if ($query->num_rows() > 0)
				{
					$prefixes = explode("\n",$query->row()->dialplan_entry);
					$prefix = explode(",",$prefixes[0]);
					$dial_prefix = substr(ltrim($prefix[0],"exten => _ "),0,(strpos(".",$prefix[0]) - 1));
					$dial_prefix = str_replace("N","",str_replace("X","",$dial_prefix));
				}
			} else {
				$dial_prefix = $dial_prefixARY[1];
			}

			$rdslash = str_replace("dslash","//", $basic[10]);
			$qmark = str_replace("qmark","?",$rdslash);
			$rsslash = str_replace("sslash","/",$qmark);

			$query = $this->db->query("UPDATE vicidial_campaigns SET campaign_name='".mysql_real_escape_string($basic[0])."', dial_method='".$dial_method."',
										campaign_description='".mysql_real_escape_string($basic[9])."',campaign_changedate='".$NOW."',
										auto_dial_level='".$auto_dial_level."', campaign_script='".$basic[4]."',
										campaign_cid='".$basic[5]."', campaign_recording='".$basic[6]."', web_form_address='".$rsslash."',
										campaign_vdad_exten='".$basic[7]."', local_call_time='".$basic[8]."',dial_prefix='".$dial_prefix."',active='".$basic[12]."'$advance $advancetrans
										WHERE campaign_id='".$campaignID."'");
			
			$return = "UPDATE vicidial_campaigns SET campaign_name='".mysql_real_escape_string($basic[0])."', dial_method='".$dial_method."',
										campaign_description='".mysql_real_escape_string($basic[9])."',campaign_changedate='".$NOW."',
										auto_dial_level='".$auto_dial_level."', campaign_script='".$basic[4]."',
										campaign_cid='".$basic[5]."', campaign_recording='".$basic[6]."', web_form_address='".$rsslash."',
										campaign_vdad_exten='".$basic[7]."', local_call_time='".$basic[8]."',dial_prefix='".$dial_prefix."',active='".$basic[12]."'$advance $advancetrans
										WHERE campaign_id='".$campaignID."'";

			if ($reset_hopper=="Y")
			{
				$query = $this->db->query("DELETE from vicidial_hopper where campaign_id='$campaignID' and status IN('READY','QUEUE','DONE')");
			}
		}
		else
		{
			
			$dial_prefixARY = explode("_",$basic[10]);
			if ($dial_prefixARY[0] != "CUSTOM")
			{
				$query = $this->db->query("SELECT dialplan_entry FROM vicidial_server_carriers WHERE carrier_id='{$basic[10]}'");
				if ($query->num_rows() > 0)
				{
					$prefixes = explode("\n",$query->row()->dialplan_entry);
					$prefix = explode(",",$prefixes[0]);
					$dial_prefix = substr(ltrim($prefix[0],"exten => _ "),0,(strpos(".",$prefix[0]) - 1));
					$dial_prefix = str_replace("N","",str_replace("X","",$dial_prefix));
				}
			} else {
				$dial_prefix = $dial_prefixARY[1];
			}

			$rdslash = str_replace("dslash","//", $basic[10]);
			$qmark = str_replace("qmark","?",$rdslash);
			$rsslash = str_replace("sslash","/",$qmark);
			
			$query = $this->db->query("UPDATE vicidial_campaigns SET survey_first_audio_file='".$basic[0]."',campaign_changedate='".$NOW."',
										survey_method='".$basic[1]."',survey_menu_id='".$basic[11]."',campaign_cid='".$basic[4]."',web_form_address='".$rsslash."',
										local_call_time='".$basic[5]."',active='".$basic[8]."',dial_prefix='".$dial_prefix."'$advance $advancetrans
										WHERE campaign_id='".$campaignID."'");
			
			$query = $this->db->query("UPDATE vicidial_remote_agents SET status='".(($basic[2]=="Y")?"ACTIVE":"INACTIVE")."',
										number_of_lines='".$basic[3]."' WHERE campaign_id='".$campaignID."'");

			$return = "UPDATE vicidial_campaigns SET survey_first_audio_file='".$basic[0]."',campaign_changedate='".$NOW."',
										survey_method='".$basic[1]."',survey_menu_id='".$basic[11]."',campaign_cid='".$basic[4]."',web_form_address='".$rsslash."',
										local_call_time='".$basic[5]."',active='".$basic[8]."',dial_prefix='".$dial_prefix."'$advance $advancetrans
										WHERE campaign_id='".$campaignID."'";		}

		return $return;
	}

	function go_update_listids()
	{
		$campaignID = $this->uri->segment(3);
		$active = explode(',',$this->uri->segment(5));
		$inactive = explode(',',$this->uri->segment(6));
		$NOW = date("Y-m-d H:i:s");

		foreach ($active as $list)
		{
			$query = $this->db->query("UPDATE vicidial_lists SET active='Y' WHERE list_id='$list' AND campaign_id='$campaignID'");
		}

		foreach ($inactive as $list)
		{
			$query = $this->db->query("UPDATE vicidial_lists SET active='N' WHERE list_id='$list' AND campaign_id='$campaignID'");
		}

		$query = $this->db->query("UPDATE vicidial_campaigns SET campaign_changedate='$NOW' WHERE campaign_id='$campaignID'");

		return "UPDATE vicidial_campaigns SET campaign_changedate='$NOW' WHERE campaign_id='$campaignID'";
	}

	function go_get_statuses($camp)
	{
		$query = $this->db->query("SELECT status,status_name from vicidial_statuses UNION SELECT status,status_name FROM vicidial_campaign_statuses where campaign_id='$camp' order by status");
		$statuses = $query->result();

		return $statuses;
	}

	function go_update_status()
	{
		$campaignID = $this->uri->segment(3);
		$action = $this->uri->segment(4);
		$status = $this->uri->segment(5);

		$query = $this->db->query("SELECT TRIM(TRAILING '-' FROM dial_statuses) AS dial_statuses FROM vicidial_campaigns WHERE campaign_id='$campaignID'");
		$row = $query->row();

		if ($action == 'add_status' && $status != 'NONE')
		{
			$dial_statuses = "$status ".$row->dial_statuses."-";
		}

		if ($action == 'remove_status' && $status != 'NONE')
		{
			$dial_statuses = ltrim(str_replace("  "," ",preg_replace("/\b$status\b/i", "", $row->dial_statuses)))."-";
		}

		if ($dial_statuses != '')
		{
			$query = $this->db->query("UPDATE vicidial_campaigns SET dial_statuses='$dial_statuses' WHERE campaign_id='$campaignID'");
		}

		return "UPDATE vicidial_campaigns SET dial_statuses='$dial_statuses' WHERE campaign_id='$campaignID'";
	}

	function go_update_campaign_list()
	{
		$action = $this->uri->segment(3);
		$campaigns = str_replace(',',"','",$this->uri->segment(4));
		$groupId = $this->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
		   $tenant_id = "---ALL---";
		}
		else
		{
		   $tenant_id = "$groupId";
		}

		switch($action)
		{
			case "activate":
				$query = $this->db->query("UPDATE vicidial_campaigns SET active='Y' WHERE campaign_id IN ('$campaigns')");
				$this->commonhelper->auditadmin('ACTIVE','Activated Campaign(s): '.$this->uri->segment(4),"UPDATE vicidial_campaigns SET active='Y' WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("UPDATE vicidial_remote_agents SET status='ACTIVE' WHERE campaign_id IN ('$campaigns')");
				break;
			case "deactivate":
				$query = $this->db->query("UPDATE vicidial_campaigns SET active='N' WHERE campaign_id IN ('$campaigns')");
				$this->commonhelper->auditadmin('INACTIVE','Deactivated Campaign(s): '.$this->uri->segment(4),"UPDATE vicidial_campaigns SET active='N' WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("UPDATE vicidial_remote_agents SET status='INACTIVE' WHERE campaign_id IN ('$campaigns')");
				break;
			case "delete":
				$allowed_campaigns = $this->go_get_allowed_campaigns(true);
				foreach (explode("','",$campaigns) as $camp)
				{
					if (in_array($camp,explode(" ",$allowed_campaigns)))
						$allowed_campaigns = str_replace("$camp ","",$allowed_campaigns);
				}

				// Deleting Remote Agents/Users by chris@goautodial.com
				foreach (explode("','",$campaigns) AS $campaign_id)
				{
					$query = $this->db->query("SELECT user_start,number_of_lines FROM vicidial_remote_agents WHERE campaign_id='$campaign_id'");
					$row=$query->row();
					$user_start=$row->user_start;
					$number_of_lines=$row->number_of_lines;
						
					$query = $this->db->query("DELETE FROM vicidial_users WHERE user_level='4' AND user='$user_start';");
				}

				$query = $this->db->query("UPDATE vicidial_user_groups SET allowed_campaigns='$allowed_campaigns' WHERE user_group='$tenant_id'");
				$query = $this->db->query("DELETE FROM vicidial_campaigns WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_campaign_agents WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_campaign_stats WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_remote_agents WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_live_agents WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_campaign_statuses WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_campaign_hotkeys WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_callbacks WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_lead_recycle WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_campaign_server_stats WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_server_trunks WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_pause_codes WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_campaigns_list_mix WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_xfer_presets WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_xfer_stats WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("DELETE FROM vicidial_hopper WHERE campaign_id IN ('$campaigns')");
				$query = $this->db->query("SELECT menu_id,did_pattern FROM vicidial_inbound_dids WHERE campaign_id='$campaign_id'");
				$menu_id = $query->row()->menu_id;
				$did_pattern = $query->row()->did_pattern;
				if ($menu_id != '')
				{
					$query = $this->db->query("DELETE FROM vicidial_call_menu WHERE menu_id = '$menu_id'");
				}
				$query = $this->db->query("DELETE FROM vicidial_inbound_dids WHERE campaign_id='$campaign_id'");
				$query = $this->db->query("DELETE FROM vicidial_inbound_groups WHERE group_id='ING".$did_pattern."'");

				$query = $this->db->query("SELECT list_id FROM vicidial_lists WHERE campaign_id IN ('$campaigns')");
				foreach ($query->result() AS $list_id)
				{
					$lquery = $this->db->query("SELECT count(*) AS leads FROM vicidial_list WHERE list_id='".$list_id->list_id."'");
					$lead_cnt = $lquery->row()->leads;
					if ($lead_cnt<1)
						$dquery = $this->db->query("DELETE FROM vicidial_lists WHERE list_id='".$list_id->list_id."'");
				}

				$this->commonhelper->auditadmin('DELETE','Deleted Campaign(s): '.$this->uri->segment(4),"DELETE FROM vicidial_campaigns WHERE campaign_id IN ('$campaigns')");
				break;
		}
	}

	function go_delete_campaign_statuses_list()
	{
		$campaigns = str_replace(',',"','",$this->uri->segment(3));

		$query = $this->db->query("DELETE FROM vicidial_campaign_statuses WHERE campaign_id IN ('$campaigns')");

		return true;
	}

	function go_wizard_outbound()
	{
		$userID = $this->session->userdata('user_name');
		$campType = $this->uri->segment(3);
		$stepNum = $this->uri->segment(4);
		$campaign_id = mysql_real_escape_string($this->uri->segment(5));
		$isBack = $this->uri->segment(6);
		$campaign_desc = mysql_real_escape_string(str_replace('+',' ',$this->uri->segment(7)));
		$SQLdate = date("Y-m-d H:i:s");
		$NOW = date("Y-m-d");
		$groupId = $this->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
		   $tenant_id = '---ALL---';
		} else {
		   $tenant_id = "$groupId";
		}

		if ($campaign_id!='undefined' && $campaign_id!='')
		{
			$query = $this->db->query("SELECT campaign_id FROM vicidial_campaigns WHERE campaign_id = '$campaign_id'");
			$campNum = $query->num_rows();
			if ($campNum < 1)
			{
				$local_call_time = "9am-9pm";
				$dial_prefix = "9";
	
				// Insert new Outbound campaign
				$query = $this->db->query("INSERT INTO vicidial_campaigns (campaign_id,campaign_name,active,dial_method,dial_status_a,
											dial_statuses,lead_order,allow_closers,hopper_level,auto_dial_level,
											next_agent_call,local_call_time,dial_prefix,get_call_launch,campaign_changedate,
											campaign_stats_refresh,list_order_mix,dial_timeout,
											campaign_vdad_exten,campaign_recording,campaign_rec_filename,scheduled_callbacks,
											scheduled_callbacks_alert,no_hopper_leads_logins,use_internal_dnc,use_campaign_dnc,
											available_only_ratio_tally,campaign_cid,manual_dial_filter,user_group,drop_call_seconds)
											VALUES('$campaign_id','$campaign_desc','Y','MANUAL','NEW',' N NA A AA DROP B NEW -','DOWN','Y','100','0','oldest_call_finish',
											'$local_call_time','$dial_prefix','NONE','$SQLdate','Y','DISABLED','30','8369','NEVER','FULLDATE_CUSTPHONE_CAMPAIGN_AGENT',
											'Y','BLINK_RED','Y','Y','Y','Y','5164536886','DNC_ONLY','$tenant_id','7')");
				
				$query = $this->db->query("INSERT INTO vicidial_campaign_stats (campaign_id) values('$campaign_id')");

				$allowed_campaigns = $this->go_get_allowed_campaigns(true);
				if (strlen($allowed_campaigns) < 1) { $allowed_campaigns = " -"; }
				$query = $this->db->query("UPDATE vicidial_user_groups SET allowed_campaigns=' {$campaign_id}$allowed_campaigns' WHERE user_group='$tenant_id'");
			}
		}

		if ($isBack=='false')
		{
			// Get list_id count
			$allowed_campaigns = $this->go_get_allowed_campaigns();
			// WHERE campaign_id IN ('$allowed_campaigns')
			$query = $this->db->query("SELECT list_id AS id FROM vicidial_lists ORDER BY list_id DESC LIMIT 1");
			if ($query->num_rows() > 0)
			{
				$list = $query->row();
				$listIDnum = str_replace($tenant_id,'',$list->id);
				$listIDnum = str_replace("---ALL---",'',$listIDnum);
			} else {
				$listIDnum = "999";
			}
			$cnt = ($listIDnum+1);
			if ($this->commonhelper->checkIfTenant($groupId) && is_numeric($tenant_id))
			{
				$list_id = "{$tenant_id}{$cnt}";
			} else {
				if ($cnt < 1000) {
					$cnt = 1000;
					$cnt = $this->checkListID($cnt);
				}
				$list_id = "$cnt";
			}
			$list_name = "ListID $list_id";

			// Create List ID
			$query = $this->db->query("INSERT INTO vicidial_lists (list_id,list_name,campaign_id,active,list_description,list_changedate)
										values('$list_id','$list_name','$campaign_id','Y','$campType ListID $cnt - $NOW','$SQLdate')");
		}
		else
		{
			$query = $this->db->query("SELECT list_id,list_name FROM vicidial_lists WHERE campaign_id='$campaign_id'");
			$lrslt = $query->row();
			$list_id = $lrslt->list_id;
			$list_name = $lrslt->list_name;
		}

		$return['campaign_id'] = $campaign_id;
		$return['list_id'] = $list_id;
		$return['list_name'] = $list_name;
		return $return;
	}

	function go_wizard_blended()
	{
		$userID = $this->session->userdata('user_name');
		$campType = $this->uri->segment(3);
		$stepNum = $this->uri->segment(4);
		$campaign_id = mysql_real_escape_string($this->uri->segment(5));
		$didPattern = $this->uri->segment(6);
		$groupColor = $this->uri->segment(7);
		$emailORagent = $this->uri->segment(8);
		$isBack = $this->uri->segment(9);
		$campaign_desc = mysql_real_escape_string(str_replace('+',' ',$this->uri->segment(10)));
		$callRoute = $this->uri->segment(11);
		$SQLdate = date("Y-m-d H:i:s");
		$NOW = date("m-d-Y");
		$groupId = $this->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
		   $tenant_id = "---ALL---";
		}
		else
		{
		   $tenant_id = "$groupId";
		}

		if ($campaign_id!='undefined' && $campaign_id!='')
		{
			$query = $this->db->query("SELECT campaign_id FROM vicidial_campaigns WHERE campaign_id = '$campaign_id'");
			$campNum = $query->num_rows();

			if ($campNum < 1)
			{
				$local_call_time = "9am-9pm";
				
				$group_id = "ING$didPattern";
				$group_name = "$campType Group $didPattern";
	
				// Insert new Inbound group
				$query = $this->db->query("INSERT INTO vicidial_inbound_groups (group_id,group_name,group_color,active,web_form_address,voicemail_ext,next_agent_call,
											fronter_display,ingroup_script,get_call_launch,web_form_address_two,start_call_url,dispo_call_url,add_lead_url,
											call_time_id,user_group)
											VALUES('$group_id','$group_name','$groupColor','Y','','','oldest_call_finish','Y','NONE','NONE','','',
											'','','$local_call_time','$tenant_id')");
	
				$query = $this->db->query("SELECT campaign_id FROM vicidial_campaigns WHERE campaign_id = '$campaign_id'");
				$campNum = $query->num_rows();
	
				if ($campNum < 1)
				{
					// Insert new Inbound Campaign
					$manualDialPrefix = '';
					$manualDialPrefixVal = '';
					$local_call_time = "9am-9pm";
					$dial_prefix = "9";
		
					if ($campType=='Inbound')
					{
						$manualDialPrefix = ',manual_dial_prefix';
						$manualDialPrefixVal = ",'5164536886'";
					}

					$query = $this->db->query("INSERT INTO vicidial_campaigns (campaign_id,campaign_name,campaign_description,active,dial_method,dial_status_a,dial_statuses,
												lead_order,park_ext,park_file_name,web_form_address,allow_closers,hopper_level,auto_dial_level,available_only_ratio_tally,
												next_agent_call,local_call_time,dial_prefix,voicemail_ext,campaign_script,get_call_launch,campaign_changedate,campaign_stats_refresh,
												list_order_mix,web_form_address_two,start_call_url,dispo_call_url,dial_timeout,campaign_vdad_exten,
												campaign_recording,campaign_rec_filename,scheduled_callbacks,scheduled_callbacks_alert,
												no_hopper_leads_logins,per_call_notes,agent_lead_search,campaign_allow_inbound,use_internal_dnc,use_campaign_dnc,campaign_cid,
												manual_dial_filter,user_group,drop_call_seconds $manualDialPrefix)
												VALUES ('$campaign_id','$campaign_desc','','Y','RATIO','NEW',' N NA A AA DROP B NEW -','DOWN','','','','Y','100','1.0','Y','oldest_call_finish',
												'$local_call_time','$dial_prefix','','','','$SQLdate','Y','DISABLED','','','','30','8369','ALLFORCE','FULLDATE_CUSTPHONE_CAMPAIGN_AGENT',
												'Y','BLINK_RED','Y','ENABLED','ENABLED','Y','Y','Y','5164536886','DNC_ONLY','$tenant_id','7' $manualDialPrefixVal)");
		
					$query = $this->db->query("INSERT INTO vicidial_campaign_stats (campaign_id) values('$campaign_id')");

					$allowed_campaigns = $this->go_get_allowed_campaigns(true);
					if (strlen($allowed_campaigns) < 1) { $allowed_campaigns = " -"; }
					$query = $this->db->query("UPDATE vicidial_user_groups SET allowed_campaigns=' {$campaign_id}$allowed_campaigns' WHERE user_group='$tenant_id'");
				}
			}
		}

		if ($isBack=='false')
		{
			// Get list_id count
			$allowed_campaigns = $this->go_get_allowed_campaigns();
			// WHERE campaign_id IN ('$allowed_campaigns')
			$query = $this->db->query("SELECT list_id AS id FROM vicidial_lists ORDER BY list_id DESC LIMIT 1");
			if ($query->num_rows() > 0)
			{
				$list = $query->row();
				$listIDnum = str_replace($tenant_id,'',$list->id);
				$listIDnum = str_replace("---ALL---",'',$listIDnum);
			} else {
				$listIDnum = "999";
			}
			$cnt = ($listIDnum+1);
			if ($this->commonhelper->checkIfTenant($groupId) && is_numeric($tenant_id))
			{
				$list_id = "{$tenant_id}{$cnt}";
			} else {
				if ($cnt < 1000) {
					$cnt = 1000;
					$cnt = $this->checkListID($cnt);
				}
				$list_id = "$cnt";
			}
			$list_name = "ListID $list_id";

			// Create List ID
			$query = $this->db->query("INSERT INTO vicidial_lists (list_id,list_name,campaign_id,active,list_description,list_changedate)
										values('$list_id','$list_name','$campaign_id','Y','$campType ListID $cnt - $NOW','$SQLdate')");
		}
		else
		{
			$query = $this->db->query("SELECT list_id,list_name FROM vicidial_lists WHERE campaign_id='$campaign_id'");
			$lrslt = $query->row();
			$list_id = $lrslt->list_id;
			$list_name = $lrslt->list_name;
		}


		if ($isBack=='false')
		{
			// Call Route
			$didDesc = "$campaign_id $campType DID";
			switch ($callRoute)
			{
				case "INGROUP":
					$query = $this->db->query("INSERT INTO vicidial_inbound_dids (did_pattern,did_description,did_active,did_route,
										user_route_settings_ingroup,campaign_id,record_call,filter_list_id,
										filter_campaign_id,group_id,server_ip,user_group)
										VALUES ('$didPattern','$didDesc','Y','IN_GROUP',
										'$group_id','$campaign_id','Y','$list_id',
										'$campaign_id','$group_id','10.0.0.12','$tenant_id')");
					break;
				case "IVR":
					$menuID = "$cntX";
					$query = $this->db->query("INSERT INTO vicidial_call_menu (menu_id,menu_name,user_group) values('$menuID','$menuID Inbound Call Menu','$tenant_id')");

					$query = $this->db->query("INSERT INTO vicidial_inbound_dids (did_pattern,did_description,did_active,did_route,campaign_id,record_call,
										filter_list_id,filter_campaign_id,server_ip,menu_id,user_group)
										VALUES ('$didPattern','$didDesc','Y','CALLMENU','$campaign_id','Y','$list_id','$campaign_id','10.0.0.12','$menuID','$tenant_id')");
					break;
				case "AGENT":
					$query = $this->db->query("INSERT INTO vicidial_inbound_dids (did_pattern,did_description,did_active,did_route,user_route_settings_ingroup,
										campaign_id,record_call,filter_list_id,filter_campaign_id,user,group_id,server_ip,user_group)
										VALUES ('$didPattern','$didDesc','Y','AGENT','$group_id','$campaign_id','Y','$list_id','$campaign_id','$emailORagent',
										'$group_id','10.10.10.12','$tenant_id')");
					break;
				case "VOICEMAIL":
					if ($emailORagent=='undefined')
						$emailORagent='';

					$query = $this->db->query("INSERT INTO vicidial_voicemail SET voicemail_id='$campaign_id',pass='$campaign_id',email='$emailORagent',fullname='$campaign_id VOICEMAIL',active='Y',user_group='$tenant_id'");

					$query = $this->db->query("INSERT INTO vicidial_inbound_dids (did_pattern,did_description,did_active,did_route,user_route_settings_ingroup,
										campaign_id,record_call,filter_list_id,filter_campaign_id,voicemail_ext,user_group)
										VALUES ('$didPattern','$didDesc','Y','VOICEMAIL','$group_id','$campaign_id','Y','$list_id','$campaign_id','$campaign_id','$tenant_id')");
					break;
			}

			$query = $this->db->query("UPDATE vicidial_campaigns SET closer_campaigns = ' $group_id -',campaign_allow_inbound = 'Y' WHERE campaign_id = '$campaign_id'");

			$query = $this->db->query("UPDATE vicidial_users set modify_inbound_dids='1' where user='$userID'");
		}

		$return['campaign_id'] = $campaign_id;
		$return['list_id'] = $list_id;
		$return['list_name'] = $list_name;
		return $return;
	}

	function go_wizard_survey()
	{
		$userID = $this->session->userdata('user_name');
		$campType = $this->uri->segment(3);
		$stepNum = $this->uri->segment(4);
		$campaign_id = mysql_real_escape_string($this->uri->segment(5));
		$surveyType = $this->uri->segment(6);
		$numChannels = $this->uri->segment(7);
		$isBack = $this->uri->segment(9);
		$campaign_desc = mysql_real_escape_string(str_replace('+',' ',$this->uri->segment(10)));
		$SQLdate = date("Y-m-d H:i:s");
		$NOW = date("m-d-Y");
		$groupId = $this->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
			$tenant_id = "---ALL---";
		}
		else
		{
			$tenant_id = "$groupId";
		}

		switch ($surveyType)
		{
			case "BROADCAST":
				$routingExten = 8373;
				break;
			case "PRESS1":
				$routingExten = 8366;
				break;
		}

		// Create List ID
		if ($stepNum==3)
		{
			if ($isBack=='false')
			{
				// Get list_id count
				$allowed_campaigns = $this->go_get_allowed_campaigns();
				// WHERE campaign_id IN ('$allowed_campaigns')
				$query = $this->db->query("SELECT list_id AS id FROM vicidial_lists ORDER BY list_id DESC LIMIT 1");
				if ($query->num_rows() > 0)
				{
					$list = $query->row();
					$listIDnum = str_replace($tenant_id,'',$list->id);
					$listIDnum = str_replace("---ALL---",'',$listIDnum);
				} else {
					$listIDnum = "999";
				}
				$cnt = ($listIDnum+1);
				//if ($cnt < 1000)
				//	$cnt = "1000";
				if ($this->commonhelper->checkIfTenant($groupId) && is_numeric($tenant_id))
				{
					$list_id = "{$tenant_id}{$cnt}";
				} else {
					if ($cnt < 1000) {
						$cnt = 1000;
						$cnt = $this->checkListID($cnt);
					}
					$list_id = "$cnt";
				}
				$list_name = "ListID $list_id";
	
				// Create List ID
				$query = $this->db->query("INSERT INTO vicidial_lists (list_id,list_name,campaign_id,active,list_description,list_changedate)
											values('$list_id','$list_name','$campaign_id','Y','$campType ListID $cnt - $NOW','$SQLdate')");
			}
			else
			{
				$query = $this->db->query("SELECT list_id,list_name FROM vicidial_lists WHERE campaign_id='$campaign_id'");
				$lrslt = $query->row();
				$list_id = $lrslt->list_id;
				$list_name = $lrslt->list_name;
			}
		}

		// Create New Survey Campaign
		if ($stepNum==2 && ($campaign_id!='undefined' && $campaign_id!=''))
		{
			//server_id RLIKE 'meetme01'
			$query = $this->db->query("SELECT server_ip FROM servers WHERE active='Y' ORDER BY max_vicidial_trunks DESC LIMIT 1;");
			$main_server_ip = $query->row()->server_ip;

				$query = $this->db->query("SELECT campaign_id FROM vicidial_campaigns WHERE campaign_id = '$campaign_id'");
				$campNum = $query->num_rows();
		
				if ($campNum < 1)
				{
					$local_call_time = "9am-9pm";
					$dial_prefix = "9";
				
					$query = $this->db->query("INSERT INTO vicidial_campaigns (campaign_id,campaign_name,campaign_description,active,dial_method,
											dial_status_a,dial_statuses,lead_order,park_ext,park_file_name,
											web_form_address,allow_closers,hopper_level,auto_dial_level,
											available_only_ratio_tally,next_agent_call,local_call_time,dial_prefix,voicemail_ext,
											campaign_script,get_call_launch,campaign_changedate,campaign_stats_refresh,
											list_order_mix,web_form_address_two,start_call_url,dispo_call_url,
											dial_timeout,campaign_vdad_exten,campaign_recording,
											campaign_rec_filename,scheduled_callbacks,scheduled_callbacks_alert,
											no_hopper_leads_logins,per_call_notes,agent_lead_search,use_internal_dnc,
											use_campaign_dnc,campaign_cid,user_group,drop_call_seconds,survey_opt_in_audio_file)
											VALUES('$campaign_id','$campaign_desc','','N','RATIO','NEW',
											' N NA A AA DROP B NEW -','DOWN','','','','Y','100','1.0',
											'Y','random','$local_call_time','$dial_prefix','','','','$SQLdate','Y','DISABLED','','','',
											'30','$routingExten','NEVER','FULLDATE_CUSTPHONE_CAMPAIGN_AGENT','Y',
											'BLINK_RED','Y','ENABLED','ENABLED','Y','Y','5164536886','$tenant_id','7','')");
				
					$query = $this->db->query("INSERT INTO vicidial_campaign_stats (campaign_id) values('$campaign_id')");
					
					$allowed_campaigns = $this->go_get_allowed_campaigns(true);
					if (strlen($allowed_campaigns) < 1) { $allowed_campaigns = " -"; }
					$query = $this->db->query("UPDATE vicidial_user_groups SET allowed_campaigns=' {$campaign_id}$allowed_campaigns' WHERE user_group='$tenant_id'");

					do
					{
						$agvar=$this->rand_digit(10);
						$query = $this->db->query("SELECT user FROM vicidial_users WHERE user='$agvar';");
						$user_exist = $query->num_rows();
					}
					while ($user_exist > 0);
					
					$pass=$this->rand_string(10);
					
					$agent_user="$agvar";
					$agent_name="Survey Agent - $campaign_id";
					$agent_phone="$agvar";
			
					$query = $this->db->query("INSERT INTO vicidial_remote_agents (user_start,number_of_lines,server_ip,conf_exten,status,campaign_id,closer_campaigns) values('$agent_user','$numChannels','$main_server_ip','8300','INACTIVE','$campaign_id','')");
			
					$query = $this->db->query("SELECT * FROM vicidial_users WHERE user='$agent_user'");
					if ($query->num_rows() < 1)
					{
						$tenant_id = ($tenant_id=='---ALL---') ? "AGENTS" : "$tenant_id";
						$query = $this->db->query("INSERT INTO vicidial_users (user,pass,full_name,user_level,user_group) values('$agent_user','$pass','$agent_name','4','$tenant_id')");
					}
				}
		}

		$return['campaign_id'] = $campaign_id;
		$return['campaign_name'] = $campaign_desc;
		$return['list_id'] = $list_id;
		$return['list_name'] = $list_name;
		$return['routing_exten'] = $routingExten;
		return $return;
	}

	function go_wizard_copy()
	{
		$userID = $this->session->userdata('user_name');
		$campType = $this->uri->segment(3);
		$stepNum = $this->uri->segment(4);
		$campaign_id = mysql_real_escape_string($this->uri->segment(5));
		$copy_from = mysql_real_escape_string($this->uri->segment(7));
		$campaign_desc = mysql_real_escape_string(str_replace('+',' ',$this->uri->segment(8)));
		$SQLdate = date("Y-m-d H:i:s");
		$NOW = date("m-d-Y");
		$groupId = $this->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
		   $tenant_id = '---ALL---';
		}
		else
		{
		   $tenant_id = "$groupId";
		}

		$query = $this->db->query("SELECT campaign_id FROM vicidial_campaigns WHERE campaign_id = '$campaign_id'");
		$campNum = $query->num_rows();

		if ($campNum < 1)
		{
			$campaign_desc = "$campaign_desc ($copy_from)";
		
			$query = $this->db->query("SELECT * FROM vicidial_campaigns WHERE campaign_id='$copy_from'");
		
			foreach ($query->row() as $field => $value)
			{
				if ($field != 'campaign_id' && $field != 'campaign_name')
				{
					$fieldSQL .= "$field,";
					$valueSQL .= "'$value',";
				}
	
				if ($field == 'campaign_id')
				{
					$fieldSQL .= "campaign_id,";
					$valueSQL .= "'$campaign_id',";
				}
	
				if ($field == 'campaign_name')
				{
					$fieldSQL .= "campaign_name,";
					$valueSQL .= "'$campaign_desc',";
				}
			}
			$fieldSQL = trim($fieldSQL, ',');
			$valueSQL = trim($valueSQL, ',');
	
			$query = $this->db->query("INSERT INTO vicidial_campaigns ($fieldSQL) VALUES ($valueSQL)");
	
			$query = $this->db->query("INSERT INTO vicidial_campaign_stats (campaign_id) values('$campaign_id')");

			$allowed_campaigns = $this->go_get_allowed_campaigns(true);
			if (strlen($allowed_campaigns) < 1) { $allowed_campaigns = " -"; }
			$query = $this->db->query("UPDATE vicidial_user_groups SET allowed_campaigns=' {$campaign_id}$allowed_campaigns' WHERE user_group='$tenant_id'");
		}

		$return['campaign_id'] = $campaign_id;
		return $return;
	}

	function go_wizard_back()
	{
		$stepNum = $this->uri->segment(4);
		$campaign_id = mysql_real_escape_string($this->uri->segment(5));
		$userID = $this->session->userdata('user_name');

		// Deleting Remote Agents/Users by chris@goautodial.com
		$query = $this->db->query("SELECT SUBSTRING(user_start,6) AS user_start,number_of_lines FROM vicidial_remote_agents WHERE campaign_id='$campaign_id'");
		$row=$query->row();
		$user_start=$row->user_start;
		$number_of_lines=$row->number_of_lines;

		if ($user_start > 0) {
// 						echo "<br>REMOVING REMOTE AGENTS/USERS ($campaign_id)\n";
			$agvar="77000";
			$start = (isset($user_start)) ? (int) $user_start : 1;
			$end = (ceil($start / 500) * 500);

			if ($start < 1000)
				$start = "0$start";
			if ($start < 100)
				$start = "00$start";
			if ($start < 10)
				$start = "000$start";

			if ($end < 1000)
				$end = "0$end";
			if ($end < 100)
				$end = "00$end";
			if ($end < 10)
				$end = "000$end";

			
			$query = $this->db->query("DELETE FROM vicidial_users WHERE user_level='4' AND user BETWEEN '" . $agvar . $start . "' AND '" . $agvar . $end . "';");
		}

		$query = $this->db->query("DELETE FROM vicidial_campaigns WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_campaign_agents WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_campaign_stats WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_remote_agents WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_live_agents WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_campaign_statuses WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_campaign_hotkeys WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_callbacks WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_lead_recycle WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_campaign_server_stats WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_server_trunks WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_pause_codes WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_campaigns_list_mix WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_xfer_presets WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_xfer_stats WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_hopper WHERE campaign_id='$campaign_id'");
// 		$query = $this->db->query("DELETE FROM vicidial_voicemail WHERE voicemail_id='$accountNum'");
		$query = $this->db->query("SELECT menu_id,did_pattern FROM vicidial_inbound_dids WHERE campaign_id='$campaign_id'");
		$menu_id = $query->row()->menu_id;
		$did_pattern = $query->row()->did_pattern;
		if ($menu_id != '')
		{
			$query = $this->db->query("DELETE FROM vicidial_call_menu WHERE menu_id = '$menu_id'");
		}
		$query = $this->db->query("DELETE FROM vicidial_inbound_dids WHERE campaign_id='$campaign_id'");
		$query = $this->db->query("DELETE FROM vicidial_inbound_groups WHERE group_id='ING".$did_pattern."'");

		$query = $this->db->query("SELECT list_id FROM vicidial_lists WHERE campaign_id='$campaign_id'");
		foreach ($query->result() AS $list_id)
		{
			$lquery = $this->db->query("SELECT count(*) AS leads FROM vicidial_list WHERE list_id='".$list_id->list_id."'");
			$lead_cnt = $lquery->row()->leads;
			if ($lead_cnt<1)
				$dquery = $this->db->query("DELETE FROM vicidial_lists WHERE list_id='".$list_id->list_id."'");
		}
	}

	function rand_string($length)
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";

		$size = strlen($chars);
		for($i=0;$i<$length;$i++)
		{
			$str .= $chars[rand(0,$size-1)];
		}
		return $str;
	}
	
	function rand_digit($length)
	{
		$chars = "0123456789";

		$size = strlen($chars);
		for($i=0;$i<$length;$i++)
		{
			$str .= $chars[rand(0,$size-1)];
		}
		return $str;
	}

	function go_get_allowed_list_ids()
	{
		//$accountNum = $this->go_get_groupid();
		//$allowed_campaigns = $this->go_get_allowed_campaigns($accountNum);
		//$query = $this->db->query("SELECT list_id,list_name FROM vicidial_lists WHERE campaign_id IN ('$allowed_campaigns')");
		$query = $this->db->query("SELECT list_id,list_name FROM vicidial_lists");
		$list_ids = $query->result();

		return $list_ids;
	}

	function go_get_phonecodes()
	{
		$query = $this->db->query("SELECT DISTINCT country_code, country FROM vicidial_phone_codes;");
		$ctr = 0;
		foreach($query->result() as $info)
		{
			$phonecodes[$ctr] = $info;
			$ctr++;
		}

		return $phonecodes;
	}

	function go_get_system_settings()
	{
		$query = $this->db->query("SELECT use_non_latin,admin_web_directory,custom_fields_enabled FROM system_settings");
		$return = $query->row();

		return $return;
	}

	function go_get_campaign_statuses()
	{
		$groupId = $this->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
		   $ul = '';
		}
		else
		{
		   $ul = "AND user_group='$groupId'";
		}

		$query = $this->db->query("SELECT vcs.campaign_id,vc.campaign_name,status,status_name,selectable,human_answered,category,sale,dnc,customer_contact,not_interested,unworkable,scheduled_callback FROM vicidial_campaign_statuses vcs, vicidial_campaigns vc WHERE vcs.campaign_id=vc.campaign_id $ul ORDER BY status");
		$return = $query->result();

		return $return;
	}

	function go_add_campaign_statuses($camp=null)
	{
		if ($camp!=null)
		{
			$campaign_id = $camp;
		} else {
			$campaign_id = $this->uri->segment(3);
		}
		
		$str = $this->go_unserialize($this->uri->segment(5));
		$is_exist = 0;

		$query = $this->db->query("SELECT count(*) AS cnt FROM vicidial_statuses WHERE status='".$str['status']."'");
		$global_status = $query->row();
		if ($global_status->cnt > 0)
			$is_exist = 1;

		$query = $this->db->query("SELECT count(*) AS cnt FROM vicidial_campaign_statuses WHERE status='".$str['status']."' AND campaign_id='$campaign_id'");
		$campaign_status = $query->row();
		if($campaign_status->cnt > 0)
			$is_exist = 2;

		if ($is_exist < 1)
		{
			$query = $this->db->query("INSERT INTO vicidial_campaign_statuses (status,status_name,selectable,campaign_id,human_answered,category,sale,dnc,customer_contact,not_interested,unworkable,scheduled_callback) VALUES('".$str['status']."','".str_replace('+',' ',$str['status_name'])."','".$str['selectable']."','".$campaign_id."','".$str['human_answered']."','".$str['category']."','".$str['sale']."','".$str['dnc']."','".$str['customer_contact']."','".$str['not_interested']."','".$str['unworkable']."','".$str['scheduled_callback']."')");
		}

		return $is_exist;
	}

	function go_modify_campaign_statuses()
	{
		$campaign_id = $this->uri->segment(3);
		$status = $this->uri->segment(5);

		$query = $this->db->query("SELECT status,status_name,selectable,campaign_id,human_answered,category,sale,dnc,customer_contact,not_interested,unworkable,scheduled_callback FROM vicidial_campaign_statuses WHERE status='$status' AND campaign_id='$campaign_id'");
		$return = $query->row();

		return $return;
	}

	function go_save_campaign_statuses()
	{
		$campaign_id = $this->uri->segment(3);
		$str = $this->go_unserialize($this->uri->segment(5));
		$is_exist = 0;

		$query = $this->db->query("UPDATE vicidial_campaign_statuses SET status_name='".str_replace('+',' ',$str['status_name_mod'])."',selectable='".$str['selectable_mod']."',human_answered='".$str['human_answered_mod']."',category='".$str['category_mod']."',sale='".$str['sale_mod']."',dnc='".$str['dnc_mod']."',customer_contact='".$str['customer_contact_mod']."',not_interested='".$str['not_interested_mod']."',unworkable='".$str['unworkable_mod']."',scheduled_callback='".$str['scheduled_callback_mod']."' WHERE status='".$str['status_mod']."' AND campaign_id='$campaign_id'");

		return $is_exist;
	}

	function go_get_status_categories()
	{
		$categories = '';

		$query = $this->db->query("SELECT vsc_id,vsc_name FROM vicidial_status_categories ORDER BY vsc_id DESC");
		foreach ($query->result() as $row)
		{
			$categories .= "<option value=\"".$row->vsc_id."\">".$row->vsc_id." - " . substr($row->vsc_name,0,20) . "</option>\n";
		}
		return $categories;
	}

	function go_delete_campaign_statuses()
	{
		$campaign_id = $this->uri->segment(3);
		$statuses = str_replace(',',"','",$this->uri->segment(5));
		$is_exist = 0;

		$query = $this->db->query("DELETE FROM vicidial_campaign_statuses WHERE status IN ('$statuses') AND campaign_id='$campaign_id'");
		return $is_exist;
	}

	function go_unserialize($string)
	{
		$args = explode('&',$string);

		foreach ($args as $string)
		{
			list($var,$val) = explode('=',$string);

			$str[$var] = $val;
		}

		return $str;
	}

	function go_get_agents()
	{
		$query = $this->db->query("SELECT user,full_name,phone_login FROM vicidial_users WHERE user_level='1' ORDER BY user");
		$result = $query->result();

		return $result;
	}

	function go_upload_leads()
	{
		$system_settings = $this->go_get_system_settings();

		$LF_name = $_FILES['leadFile']['name'];
		$fileName = "loaded_leads_file.txt";
		$filePath = "/tmp";
		$list_id = $this->input->post('list_id');
		$phone_code = $this->input->post('phone_code');

		if (preg_match("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", $LF_name)) {
			$LF_name = ereg_replace("[^-\.\_0-9a-zA-Z]","_",$LF_name);
			copy("$filePath/$LF_name", "$filePath/$fileName");
			$new_filename = preg_replace("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", '.txt', $LF_name);
			$WeBServeRRooT = $_SERVER['DOCUMENT_ROOT'];
			$admin_web_directory = "application/views/go_list";
			$convert_command = "$WeBServeRRooT/$admin_web_directory/sheet2tab.pl /tmp/$LF_name /tmp/$new_filename";

			passthru("$convert_command");
			$lead_file = "/tmp/$new_filename";
			$data['lead_file'] = $lead_file;
			$fname = $new_filename;


			if (preg_match("/\.csv$/i", $LF_name)) {$delim_name="CSV: Comma Separated Values";}
			if (preg_match("/\.xls$/i", $LF_name)) {$delim_name="XLS: MS Excel 2000-XP";}
			if (preg_match("/\.xlsx$/i", $LF_name)) {$delim_name="XLSX: MS Excel 2007+";}
			if (preg_match("/\.ods$/i", $LF_name)) {$delim_name="ODS: OpenOffice.org OpenDocument Spreadsheet";}
			if (preg_match("/\.sxc$/i", $LF_name)) {$delim_name="SXC: OpenOffice.org First Spreadsheet";}
			$delim_set=1;


		} else {
			copy("$filePath/$LF_name", "$filePath/$fileName");
			$lead_file = "$filePath/$fileName";
			$fname = $fileName;
		}

		$file_name = preg_replace("/\.txt$|\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", '', $fname);
		$file_ext = str_replace("{$file_name}.",'',$fname);

		$fileArray = file("$filePath/$new_filename");

		$fh = fopen("$filePath/$new_filename", "r");

		foreach ($fileArray as $i => $line)
		{
			$lineArray[$i] = split("\t", trim($line));
		}
		fclose($fh);

		foreach ($lineArray as $x => $line)
		{
			foreach ($line as $n => $cell)
			{
				$cell_val[$x][$n] = $cell;
			}
		}

		$fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_reset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|entry_list_id|';
// 		$standard_SQL = "vendor_lead_code, source_id, list_id, phone_code, phone_number, title, first_name, middle_initial, last_name, address1, address2, address3, city, state, province, postal_code, country_code, gender, date_of_birth, alt_phone, email, security_phrase, comments, rank, owner";
		$standard_SQL = "list_id, phone_code, phone_number, first_name, middle_initial, last_name, address1, city, state, postal_code, alt_phone, email, comments";
		$table_SQL = "vicidial_list";

		if ($system_settings->custom_fields_enabled > 0)
		{
			$query = $this->db->query("SHOW TABLES LIKE 'custom_".$list_id."'");
			if ($query->num_rows() > 0)
			{
				$query = $this->db->query("SELECT count(*) AS cnt FROM vicidial_lists_fields WHERE list_id='".$list_id."'");
				$fields_cnt = $query->row();
				if ($fields_cnt->cnt > 0)
				{
					$custom_SQL='';
					$query = $this->db->query("SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='".$list_id."' order by field_rank,field_order,field_label");

					foreach ($query->result() as $i => $row)
					{
						$field_label[$i]	= $row->field_label;
						$field_type[$i]		= $row->field_type;

						if ( ($field_type[$i]!='DISPLAY') and ($field_type[$i]!='SCRIPT') )
						{
							if (!preg_match("/\|$field_label[$i]\|/",$fields))
							{
							$custom_SQL .= ",$field_label[$i]";
							}
						}
					}
				}
				$table_SQL = "vicidial_list, custom_".$list_id;
			}
		}
		$fields_SQL = "SELECT $standard_SQL $custom_SQL FROM $table_SQL limit 1";

		$query = $this->db->query($fields_SQL);

		$columnHTML = '';
		foreach ($query->list_fields() as $field)
		{
			$columnHTML .= "<tr>";
			if (($field == 'list_id' and $list_id != '') or ($field == 'phone_code' and $phone_code != ''))
			{
				$column_name .= "<!-- skipping $field -->\n";
			}
			else
			{
				$field_name = $field;
				if ($field == 'address1')
					$field_name = 'address';

				$columnHTML .= "<td style=\"text-align:right;width:50%;\">".strtoupper(eregi_replace("_", " ", $field_name)).":</td><td style\"width:50%;\">\n";

				$columnHTML .= "<select name=\"{$field}_field\" id=\"{$field}_field\" class=\"uploadLeads\">\n";
				$columnHTML .= "<option value=\"-1\">(none)</option>\n";
				for ($i=0;$i<count($cell_val[0]);$i++)
				{
					$columnHTML .= "<option value=\"$i\">".$cell_val[0][$i]."</option>\n";
				}
				$columnHTML .= "</select>\n";

				$columnHTML .= "</td>\n";
			}
			$columnHTML .= "</tr>";
		}

		$return['delim_name'] = $delim_name;
		$return['column_name'] = $columnHTML;
		$return['list_id'] = $list_id;
		$return['phone_code'] = $phone_code;
		$return['file_name'] = $file_name;
		$return['file_ext'] = $file_ext;
		return $return;
	}

	function rsync_file($file)
	{
		$query = $this->db->query("SELECT server_id,server_ip,active,server_description FROM servers");

		foreach ($query->row() as $row)
		{
			if(!preg_match('/WEB SERVER/i',$row->server_description) && $row->active == 'Y')
			{
				exec('/usr/share/goautodial/goautodialc.pl "rsync -avz -e \"ssh -p222\" /var/lib/asterisk/sounds/'.$file.' root@'.$row->server_ip.':/var/lib/asterisk/sounds"');
			}
		}
	}

	function go_get_inbound_dids($camp)
	{
		$query = $this->db->query("SELECT did_pattern,did_description FROM vicidial_inbound_dids WHERE campaign_id='$camp'");
		$return = $query->result();

		return $return;
	}

	function go_get_allowed_trans($camp)
	{
		$query = $this->db->query("SELECT closer_campaigns,xfer_groups from vicidial_campaigns WHERE campaign_id='$camp'");
		$return = $query->result();

		return $return;
	}
	
	function go_get_carriers()
	{	
		$groupId = $this->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
		   $ul = '';
		}
		else
		{
		   $ul = "AND user_group IN ('$groupId','---ALL---') ORDER BY user_group DESC";
		}
		
		$query = $this->db->query("SELECT * FROM vicidial_server_carriers WHERE active='Y' $ul;");

		foreach ($query->result() as $carrier_info)
		{
			$prefixes = explode("\n",$carrier_info->dialplan_entry);
			$prefix = explode(",",$prefixes[0]);
			$prefixuse = substr(ltrim($prefix[0],"exten => _ "),0,(strpos(".",$prefix[0]) - 1));
			
			$return[$carrier_info->carrier_id]['carrier_name'] = $carrier_info->carrier_name;
			$return[$carrier_info->carrier_id]['prefix'] = $prefixuse;
		}
		
		return $return;
	}
	
	function go_get_all_statuses($camp=null,$select="N")
	{
		$selectSQL = "";
		if ($select=="Y")
			$selectSQL = "WHERE selectable='Y'";
		
		$campSQL = "";
		if (!is_null($camp))
			$campSQL = "AND campaign_id='$camp'";
		
		if (is_null($camp) && $this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) {
			$camps = $this->go_get_allowed_campaigns();
			if ($select=="N")
				$campSQL = "WHERE campaign_id IN ('$camps')";
			else
				$campSQL = "AND campaign_id IN ('$camps')";
		}
		
		$query = $this->db->query("SELECT status,status_name,campaign_id FROM vicidial_campaign_statuses $selectSQL $campSQL ORDER BY status");
		foreach ($query->result() as $list)
		{
			$status["CAMPAIGN_STATUSES"][$list->status] = $list->status_name;
		}
		
		$query = $this->db->query("SELECT status,status_name FROM vicidial_statuses $selectSQL ORDER BY status");
		foreach ($query->result() as $list)
		{
			$status['SYSTEM_STATUSES'][$list->status] = $list->status_name;
		}

		$return['list'] = $status;
		return $return;
	}
	
	function pagelinks($group_id,$page,$rp,$total,$limit,$displaywhat=null)
	{
		$pg 	= $this->commonhelper->paging($page,$rp,$total,$limit);
		$start	= (($page-1) * $rp);
		if (strlen($displaywhat) < 1)
			$displaywhat = "campaigns";
	
		if ($pg['last'] > 1) {
			$pagelinks  = '<div style="cursor: pointer;font-weight: bold;">';
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
				$pagelinks  = '<div style="cursor: pointer;font-weight: bold;">';
				$pagelinks .= '<a title="Back to Paginated View" style="vertical-align:text-top;padding: 0px 2px;" onclick="changePage(1)"><span>BACK</span></a>';
				$pagelinks .= '</div>';
			} else {
				$pagelinks = "";
			}
		}
		
		$pageinfo = "Displaying {$pg['istart']} to {$pg['iend']} of {$pg['total']} $displaywhat";
		$return['links'] = $pagelinks;
		$return['info'] = $pageinfo;
		
		return $return;
	}
	
	function checkListID($cnt)
	{
		$query = $this->db->query("SELECT count(*) AS cnt FROM vicidial_lists WHERE list_id='$cnt';");
		while ($query->row()->cnt > 0)
		{
			$cnt++;
			$query = $this->db->query("SELECT count(*) AS cnt FROM vicidial_lists WHERE list_id='$cnt';");
		}
		
		return $cnt;
	}
	
	function go_get_filter_options($country=null,$state=null)
	{
		//if (is_null($country))
		//	$country = "USA";
		
		$query = $this->db->query("SELECT areacode,geographic_description,country FROM vicidial_phone_codes WHERE areacode <> '*' GROUP BY country,areacode ORDER BY country,areacode");
		foreach ($query->result() as $area)
		{
			$return['areacodes']["{$area->country}_{$area->areacode}"] = "{$area->country} - {$area->areacode} - {$area->geographic_description}";
		}
		
		$query = $this->db->query("SELECT state,geographic_description,country FROM vicidial_phone_codes WHERE state <> '' GROUP BY country,state ORDER BY country,state;");
		foreach ($query->result() as $state)
		{
			$return['states']["{$state->country}_{$state->state}"] = "{$state->country} - {$state->state} - {$state->geographic_description}";
		}
		
		$query = $this->db->query("SELECT country_code,country FROM vicidial_phone_codes GROUP BY country,country_code");
		foreach ($query->result() as $country)
		{
			$return['countrycodes']["{$country->country}_{$country->country_code}"] = "{$country->country} - {$country->country_code}";
		}
		
		return $return;
	}

}
