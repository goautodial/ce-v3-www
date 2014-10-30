<?php
####################################################################################################
####  Name:             	go_campaign_ce.php                                                  ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####      	                <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  Edited by:            GoAutoDial Development Team                                         ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_campaign_ce extends Controller {
	var $userLevel;

    function __construct()
	{
		parent::Controller();
		$this->load->model(array('go_auth','go_monitoring','golist','go_dashboard','go_reports','go_campaign'));
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
		$data['cssloader'] = 'go_dashboard_cssloader.php';
		$data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
		$data['jsbodyloader'] = 'go_campaign_body_jsloader.php';

		$data['theme'] = $this->session->userdata('go_theme');
		$data['bannertitle'] = $this->lang->line('go_campaign_banner');
		$data['adm']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);

		$data['userfulname'] = $this->go_campaign->go_get_userfulname();
		
		$data['permissions'] = $this->commonhelper->getPermissions("campaign",$this->session->userdata("user_group"));

		$data['go_main_content'] = 'go_campaign/go_campaign';
		$this->load->view('includes/go_dashboard_template',$data);
	}

	function go_campaign_list()
	{
		$groupId = $this->go_campaign->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
		   $ul = '';
		}
		else
		{
		   $ul = "AND user_group='$groupId'";
		   $addedSQL = "WHERE user_group='$groupId'";
		}
		
		$campaigns = $this->go_campaign->go_get_campaigns();
		$all_statuses = $this->go_campaign->go_get_all_statuses();
		$selectable_statuses = $this->go_campaign->go_get_all_statuses(null,'Y');
		$data['status_list'] = $this->go_campaign->go_get_campaign_statuses();

		foreach($data['status_list'] as $status)
		{
			$camp_status[$status->campaign_id] .= $status->status . " ";
			$camp_name[$status->campaign_id] = $status->campaign_name;
		}

		$query = $this->db->query("SELECT recycle_id,vc.campaign_id,campaign_name,status,vlr.active,attempt_delay,attempt_maximum FROM vicidial_lead_recycle as vlr,vicidial_campaigns as vc WHERE vlr.campaign_id=vc.campaign_id $ul ORDER BY recycle_id;");
		foreach ($query->result() as $leadrec)
		{
			$lead_status[$leadrec->campaign_id] .= $leadrec->status . " ";
			$lead_name[$status->campaign_id] = $status->campaign_name;
		}
		
		$query = $this->db->query("SELECT pause_code,vpc.campaign_id from vicidial_pause_codes as vpc, vicidial_campaigns as vc where vpc.campaign_id=vc.campaign_id $ul order by pause_code;");
		foreach ($query->result() as $pausecode)
		{
			$pause_status[$pausecode->campaign_id] .= $pausecode->pause_code . " ";
		}
		
		$query = $this->db->query("SELECT status,campaign_id from vicidial_campaign_hotkeys order by status;");
		foreach ($query->result() as $hotkey)
		{
			$hotkey_status[$hotkey->campaign_id] .= $hotkey->status . " ";
		}
		
		$query = $this->db->query("SELECT lead_filter_id AS filter_id,lead_filter_name AS filter_name FROM vicidial_lead_filters $addedSQL");
		$filters['list'] = $query->result();
		
		$filters['pagelinks'] = $this->go_campaign->pagelinks($groupId,1,25,$query->num_rows(),5,"lead filters");
		
		$query = $this->db->query("SELECT user_group,group_name FROM vicidial_user_groups $addedSQL");
		$user_groups[''] = "--- SELECT A GROUP ---";
		$user_groups['---ALL---'] = "--- ALL USER GROUPS ---";
		foreach ($query->result() as $group)
		{
			$user_groups[$group->user_group] = "{$group->user_group} - {$group->group_name}";
		}
		
		$fields = $this->db->list_fields('vicidial_list');
		$fields_to_filter[''] = "--- SELECT A FIELD ---";
		foreach ($fields as $field)
		{
			if (preg_match("/entry_date|modify_date|gmt_offset_now|phone_number|state|phone_code|called_count/", $field))
			{
				switch ($field)
				{
					case "entry_date":
						$field_name = "Date Uploaded";
						break;
					case "modify_date":
						$field_name = "Date Modified";
						break;
					case "gmt_offset_now":
						$field_name = "Timezone";
						break;
					case "phone_number":
						$field_name = "Area Code";
						break;
					case "state":
						$field_name = "State";
						break;
					case "phone_code":
						$field_name = "Country Code";
						break;
					case "called_count":
						$field_name = "Called Count";
						break;
				}	
				$fields_to_filter[$field] = "$field_name";
			}
		}
		
		$filter_options = $this->go_campaign->go_get_filter_options();

		$data['campaign'] = $campaigns;
		$data['camp_status'] = $camp_status;
		$data['camp_name'] = $camp_name;
		$data['lead_status'] = $lead_status;
		$data['lead_name'] = $lead_name;
		$data['all_statuses'] = $all_statuses;
		$data['selectable_statuses'] = $selectable_statuses;
		$data['pause_status'] = $pause_status;
		$data['hotkey_status'] = $hotkey_status;
		$data['pagelinks'] = $campaigns['pagelinks'];
		$data['filters'] = $filters['list'];
		$data['filterlinks'] = $filters['pagelinks'];
		$data['user_groups'] = $user_groups;
		$data['countrycodes'] = $filter_options['countrycodes'];
		$data['areacodes'] = $filter_options['areacodes'];
		$data['states'] = $filter_options['states'];
		$data['fields_to_filter'] = $fields_to_filter;
		
		$data['permissions'] = $this->commonhelper->getPermissions("campaign",$this->session->userdata("user_group"));

        $this->load->view('go_campaign/go_campaign_list',$data);
	}

	function go_update_campaign_list()
	{
		$update = $this->go_campaign->go_update_campaign_list();

		$data['campaign'] = $this->go_campaign->go_get_campaigns(true);
		$data['status_list'] = $this->go_campaign->go_get_campaign_statuses();

		foreach($data['status_list'] as $status)
		{
			$camp_status[$status->campaign_id] .= $status->status . " ";
			$camp_name[$status->campaign_id] = $status->campaign_name;
		}

		$data['camp_status'] = $camp_status;
		$data['camp_name'] = $camp_name;
		$data['pagelinks'] = $data['campaign']['pagelinks'];

        $this->load->view('go_campaign/go_campaign_list',$data);
	}
	
	function go_check_for_leads()
	{
		$campaigns = str_replace(',',"','",$this->uri->segment(3));
		$batch = $this->uri->segment(4);
		$query = $this->db->query("SELECT list_id AS id FROM vicidial_lists WHERE campaign_id IN ('$campaigns')");
		$result = $query->result();
		$has_leads = 0;
		
		foreach ($result as $list)
		{
			$query = $this->db->query("SELECT * FROM vicidial_list WHERE list_id='{$list->id}'");
			$cnt = $query->num_rows();
			$has_leads = $has_leads + $cnt;
		}
		
		if ($has_leads > 0)
			echo "ERROR:\n\nThe campaign(s) you want to delete still has list ids that have leads loaded.\n\nPlease transfer the existing list(s) to another campaign or delete it using our List page.";
		else
			echo "OK";
	}

	function go_delete_campaign_statuses_list()
	{
		$update = $this->go_campaign->go_delete_campaign_statuses_list();

		$data['campaign'] = $this->go_campaign->go_get_campaigns();
		$data['status_list'] = $this->go_campaign->go_get_campaign_statuses();

		foreach($data['status_list'] as $status)
		{
			$camp_status[$status->campaign_id] .= $status->status . " ";
			$camp_name[$status->campaign_id] = $status->campaign_name;
		}

		$data['camp_status'] = $camp_status;
		$data['camp_name'] = $camp_name;

		$this->load->view('go_campaign/go_campaign_list',$data);
	}

	function go_get_settings()
	{
		$data = $this->go_campaign->go_get_settings();
		$data['accountNum'] = $this->go_campaign->go_get_groupid();
		$data['inbound_groups'] = $this->go_reports->go_get_inbound_groups();
		$data['inbound_dids'] = $this->go_campaign->go_get_inbound_dids($data['campaign_id']);
		$data['allowed_trans'] = $this->go_campaign->go_get_allowed_trans($data['campaign_id']);
		$data['carrier_info'] = $this->go_campaign->go_get_carriers();
		
		$groupId = $this->go_campaign->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
		   $ul = '';
		}
		else
		{
		   $ul = "WHERE user_group='$groupId'";
		}
		$query = $this->db->query("SELECT lead_filter_id,lead_filter_name FROM vicidial_lead_filters $ul");
		$filters[''] = "NONE";
		foreach ($query->result() as $filter)
		{
			$filters[$filter->lead_filter_id] = "{$filter->lead_filter_id} - {$filter->lead_filter_name}";
		}
		$data['lead_filters'] = $filters;

        $this->load->view('go_campaign/go_campaign_settings',$data);
	}
	
	function go_check_campaign()
	{
		$campaign = $this->uri->segment(3);
		$query = $this->db->query("SELECT * FROM vicidial_campaigns WHERE campaign_id='$campaign'");
		$return = $query->num_rows();
		
		if ($return)
		{
			$return = "<small style=\"color:red;\">Not Available.</small>";
		} else {
			$return = "<small style=\"color:green;\">Available.</small>";
		}
		
		echo $return;
	}
	
	function go_check_did()
	{
		$did = $this->uri->segment(3);
		$query = $this->db->query("SELECT did_pattern FROM vicidial_inbound_groups AS vig,vicidial_inbound_dids AS vid WHERE did_pattern='$did' AND vig.group_id=vid.group_id");
		$vigrslt = $query->num_rows();
		$query = $this->db->query("SELECT did_pattern FROM vicidial_inbound_dids WHERE did_pattern='$did'");
		$vidrslt = $query->num_rows();
		
		if ($vigrslt || $vidrslt)
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
// 			echo "<script>javascript: window.location = '$base'</script>";
			#echo 'You don\'t have permission to access this page. <a href="../go_index">Login</a>';
			die();
			#$this->load->view('go_login_form');
		}
	}

	function go_modify_settings()
	{
		$action = $this->uri->segment(4);

		if ($action == 'modify')
		{
			$updated = $this->go_campaign->go_update_settings();
		}

		if ($action == 'update_listid')
		{
			$updated = $this->go_campaign->go_update_listids();
		}

		if ($action == 'add_status' || $action == 'remove_status')
		{
			$updated = $this->go_campaign->go_update_status();
		}

		$this->commonhelper->auditadmin('MODIFY','Modified Campaign '.$this->uri->segment(3),"$updated");
		$data = $this->go_campaign->go_get_settings();

 		if ($this->uri->segment(7) == 1 || $this->uri->segment(7) == 0)
			$data['isAdvance'] = $this->uri->segment(7);
 		else
 			$data['isAdvance'] = $this->uri->segment(6);
		
		$groupId = $this->go_campaign->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
		   $ul = '';
		}
		else
		{
		   $ul = "WHERE user_group='$groupId'";
		}
		$query = $this->db->query("SELECT lead_filter_id,lead_filter_name FROM vicidial_lead_filters $ul");
		$filters[''] = "NONE";
		foreach ($query->result() as $filter)
		{
			$filters[$filter->lead_filter_id] = "{$filter->lead_filter_id} - {$filter->lead_filter_name}";
		}
		$data['lead_filters'] = $filters;

		$data['inbound_groups'] = $this->go_reports->go_get_inbound_groups();
		$data['accountNum'] = $this->go_campaign->go_get_groupid();
		$data['inbound_dids'] = $this->go_campaign->go_get_inbound_dids($data['campaign_id']);
		$data['allowed_trans'] = $this->go_campaign->go_get_allowed_trans($data['campaign_id']);
		$data['carrier_info'] = $this->go_campaign->go_get_carriers();

		$this->load->view('go_campaign/go_campaign_settings',$data);
	}

	function go_activate_list_ids()
	{
		$updated = $this->go_campaign->go_update_list_ids();

		$data = $this->go_campaign->go_get_settings();
		$data['accountNum'] = $this->go_campaign->go_get_groupid();
		$data['inbound_dids'] = $this->go_campaign->go_get_inbound_dids($data['campaign_id']);

		$this->load->view('go_campaign/go_campaign_settings',$data);
	}

	function go_get_listid()
	{
		$list_id = $this->uri->segment(3);
		$data['isAdvance'] = $this->uri->segment(4);

		$groupId = $this->go_campaign->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
			$ul='';
		}
		else
		{
			$ul = "WHERE user_group='$groupId'";
		}
		
		$query = $this->db->query("SELECT campaign_id,campaign_name FROM vicidial_campaigns $ul ORDER BY campaign_id");
		$data['campaigns'] = $query->result();

		$data['statuses'] = $this->golist->getstatuses($list_id);
		$data['timezones'] = $this->golist->gettzones($list_id);

		$query = $this->db->query("select status,status_name from vicidial_statuses union select status,status_name from vicidial_campaign_statuses");
		foreach ($query->result() as $status)
		{
			$statuses[$status->status] = $status->status_name;
		}

		$query = $this->db->query("SELECT list_name,list_description,campaign_id,active,reset_time,list_changedate,list_lastcalldate,agent_script_override,campaign_cid_override,drop_inbound_group_override,web_form_address,xferconf_a_number,xferconf_b_number,xferconf_c_number,xferconf_d_number,xferconf_e_number FROM vicidial_lists WHERE list_id='$list_id'");
		$data['listid'] = $query->row();
		$data['list_id'] = $list_id;
		$data['status_to_print'] = $statuses;
		$this->load->view('go_campaign/go_campaign_listid',$data);
	}

	function go_campaign_wizard()
	{
		$campType = $this->uri->segment(3);
		$data['step'] = $this->uri->segment(4);
		$campaign_id = mysql_real_escape_string($this->uri->segment(5));
		$didPattern = $this->uri->segment(6);
		$isBack = $this->uri->segment(9);
		$data['list_agents'] = $this->go_campaign->go_get_agents();
		if ($campType!='' && $campType!='back')
		{
			if ($data['step']==2)
			{
				switch($campType)
				{
					case "Inbound":
						$output = $this->go_campaign->go_wizard_blended();
						break;
					case "Blended":
						$output = $this->go_campaign->go_wizard_blended();
						break;
					case "Survey":
						$output = $this->go_campaign->go_wizard_survey();

						$data['routing_exten'] = $output['routing_exten'];
						$data['survey_type'] = $this->uri->segment(6);
						$data['num_channels'] = $this->uri->segment(7);
						break;
					case "Copy":
						$output = $this->go_campaign->go_wizard_copy();
						break;
					default:
						$output = $this->go_campaign->go_wizard_outbound();
				}

				$data['list_id'] = $output['list_id'];
				$data['list_name'] = $output['list_name'];
			}

			if ($data['step']>2 && $campType=='Survey')
			{
				switch ($surveyType)
				{
					case "BROADCAST":
						$routingExten = 8373;
						break;
					case "PRESS1":
						$routingExten = 8366;
						break;
				}

				if ($data['step']==3)
				{
					$output = $this->go_campaign->go_wizard_survey();
					if ($this->uri->segment(8) != 'NULL')
					{
						$fnames = explode(':',$this->uri->segment(8));
						if(isset($fnames[0]) && strlen($fnames[0])>0)
						{
							$query = $this->db->query("UPDATE vicidial_campaigns SET survey_first_audio_file = '{$fnames[0]}' WHERE campaign_id='$campaign_id'");
						}
					}
				}

				if ($data['step']==4)
				{
					$query = $this->db->query("SELECT survey_first_audio_file,survey_dtmf_digits,survey_ni_digit,survey_opt_in_audio_file,survey_ni_audio_file,
									survey_method,survey_no_response_action,survey_response_digit_map,survey_third_status,survey_third_audio_file,survey_third_digit,
									survey_third_exten,survey_fourth_audio_file,survey_fourth_digit,survey_fourth_status,survey_fourth_exten,
									survey_xfer_exten,survey_camp_record_dir,voicemail_ext,am_message_exten,waitforsilence_options,amd_send_to_vmx,cpd_amd_action,
									survey_ni_status,auto_dial_level,number_of_lines FROM vicidial_campaigns vc,vicidial_remote_agents vr WHERE vc.campaign_id='$campaign_id' AND vc.campaign_id=vr.campaign_id");
					$data['survey_info'] = $query->row();
				}
				$data['routing_exten'] = $output['routing_exten'];
				$data['survey_type'] = $this->uri->segment(6);
				$data['num_channels'] = $this->uri->segment(7);
				$data['list_id'] = $output['list_id'];
				$data['list_name'] = $output['list_name'];
			}
			
			if ($data['step']>2 && $campType=='Blended')
			{
				$query = $this->db->query("select did_pattern FROM vicidial_inbound_dids where campaign_id='$campaign_id'");
				$didPattern = $query->row()->did_pattern;
			}

			if ($output['campaign_id']!='' || strlen($output['campaign_id'])>0)
			{
				$campaign_id = $output['campaign_id'];
			}

			if ($data['step']==2)
				$this->commonhelper->auditadmin('ADD','Added '.$campType.' Campaign '.$campaign_id);

			$campaign_info = $this->go_campaign->go_get_campaign_info($campaign_id);
			$data['campaign_name'] = $campaign_info->campaign_name;
			$data['dial_method'] = $campaign_info->dial_method;
			$data['auto_dial_level'] = $campaign_info->auto_dial_level;
			$data['campaign_cid'] = $campaign_info->campaign_cid;
			$data['campaign_recording'] = $campaign_info->campaign_recording;
			$data['phonecodes'] = $this->go_campaign->go_get_phonecodes();
			$data['campType'] = $campType;
			$data['campaign_id'] = $campaign_id;
			$data['did_pattern'] = $didPattern;
			$data['accountNum'] = $this->go_campaign->go_get_groupid();
			$data['carrier_info'] = $this->go_campaign->go_get_carriers();
			$data['dialPrefix'] = $campaign_info->dial_prefix;
			$data['call_menus'] = $this->go_campaign->go_get_call_menu();
			$data['isBack'] = $isBack;

			$this->load->view('go_campaign/go_campaign_wizard_output',$data);
		}
		else
		{
			if ($campType=='back')
			{
				if ($data['step']==1)
					$output = $this->go_campaign->go_wizard_back();
			} else {
				if (!$this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) {
					$ul = '';
				} else {
					$ul = "WHERE user_group='".$this->session->userdata('user_group')."'";
				}
				
				$query = $this->db->query("SELECT campaign_id,campaign_name,campaign_vdad_exten FROM vicidial_campaigns $ul ORDER BY campaign_id");
				$data['campaigns'] = $query->result();
				
				do
				{
					$camp = rand(10000000,99999999);
					$query = $this->db->query("SELECT campaign_id FROM vicidial_campaigns WHERE campaign_id='$camp';");
					$camp_exist = $query->num_rows();
				}
				while ($camp_exist > 0);

				$data['campaign_id'] = $camp;
				$this->load->view('go_campaign/go_campaign_wizard',$data);
			}
		}
	}
	
	function go_campaign_preview()
	{
		$campaign_type = $this->input->post('campaign_type');
		$campaign_id = $this->input->post('campaign_id');
		$dial_method = $this->input->post('dial_method');
		$auto_dial_level = $this->input->post('auto_dial_level');
		$campaign_recording = $this->input->post('campaign_recording');
		$campaign_vdad_exten = $this->input->post('campaign_vdad_exten');
		$dial_prefix = $this->input->post('dial_prefix');
		$custom_prefix = $this->input->post('custom_prefix');
		$manual_dial_prefix = $this->input->post('manual_dial_prefix');
		
		$dial_prefixARY = explode("_","{$dial_prefix}_{$custom_prefix}");
		if (str_replace("-","",$dial_prefix) != "CUSTOM")
		{
			$query = $this->db->query("SELECT dialplan_entry FROM vicidial_server_carriers WHERE carrier_id='{$dial_prefix}'");
			if ($query->num_rows() > 0)
			{
				$prefixes = explode("\n",$query->row()->dialplan_entry);
				$prefix = explode(",",$prefixes[0]);
				$dial_prefix = substr(ltrim($prefix[0],"exten => _ "),0,(strpos(".",$prefix[0]) - 1));
				$dial_prefix = str_replace("N","",str_replace("X","",$dial_prefix));
			}
		} else {
			$dial_prefix = $custom_prefix;
		}


                $auth_user = $this->session->userdata('user_name');
		$auth_user = str_replace(" ","",$auth_user);
		
                $VARSERVTYPE = $this->config->item('VARSERVTYPE');
                $VARKAMAILIO = $this->config->item('VARKAMAILIO');

                if($VARKAMAILIO == "Y" && $auth_user != "admin") {
                     $dial_prefix = "8888".$auth_user;
                } else {
		     $dial_prefix = (strlen($dial_prefix) < 0) ? "9" : $dial_prefix;
                }
	
		if ($campaign_type != 'Survey')
		{
			$this->db->query("UPDATE vicidial_campaigns SET dial_method='$dial_method',auto_dial_level='$auto_dial_level',campaign_recording='$campaign_recording',campaign_vdad_exten='$campaign_vdad_exten',dial_prefix='$dial_prefix',manual_dial_prefix='$manual_dial_prefix' WHERE campaign_id='$campaign_id'");
		} else {
			$this->db->query("UPDATE vicidial_campaigns SET dial_prefix='$dial_prefix' WHERE campaign_id='$campaign_id'"); 
		}
		//var_dump("UPDATE vicidial_campaigns SET dial_method='$dial_method',auto_dial_level='$auto_dial_level',campaign_vdad_exten='$campaign_vdad_exten' WHERE campaign_id='$campaign_id'");
		return true;
	}
	
	function emergencylogout()
	{
		$NOW_TIME = date("Y-m-d H:i:s");
		$thedate = date('U');
		$inactive_epoch = ($thedate - 60);
		
		$query = $this->db->query("SELECT user FROM vicidial_live_agents WHERE campaign_id='".$_POST['campaign']."'");
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $agent)
			{
				$agent_logged = $agent->user;
				$this->db->select('user,campaign_id,UNIX_TIMESTAMP(last_update_time)as last_update_time');
				$Vliveagent = $this->db->get_where('vicidial_live_agents',array('user'=>$agent_logged))->result(); 
				if(!empty($Vliveagent)){
					$fields = array('agent_log_id','user','server_ip','event_time','lead_id','campaign_id','pause_epoch','pause_sec',
							'wait_epoch','wait_sec','talk_epoch','talk_sec','dispo_epoch','dispo_sec','status','user_group',
							'comments','sub_status','dead_epoch','dead_sec');
					$this->db->select($fields);
					$this->db->where(array('user'=>$Vliveagent[0]->user));
					$this->db->order_by('agent_log_id','desc'); 
					$this->db->limit(1);
					$agentlog = $this->db->get('vicidial_agent_log');
					#the result is 
					$agents = $agentlog->result();
					$this->db->trans_start();
					if($agentlog->num_rows > 0)
					{
						if($agents[0]->wait_epcoh < 1 || ($agents[0]->status == 'PAUSE' && $agents[0]->dispo_epoch < 1) ){
							$agents[0]->pause_sec = (($thedate-$agents[0]->pause_epoch)+$agents[0]->pause_sec);
							$updatefields = array('wait_epoch'=>$thedate,'pause_sec'=>$agents[0]->pause_sec);
						} else {
							if($agents[0]->talk_epoch < 1){
								$agents[0]->wait_sec = (($thedate-$agents[0]->wait_epoch) + $agents[0]->wait_sec);
								$updatefields = array('talk_epoch'=>$thedate,'wait_sec'=>$agents[0]->wait_sec);
							}else{
								if(is_null($agents[0]->status) && $agents[0]->lead_id > 0){
									$this->db->where(array('lead_id'=>$agents[0]->lead_id));
									$updatethis = array('status'=>'PU');
									$this->db->update('vicidial_list',$updatethis);
								}
								if($agents[0]->dispo_epoch < 1){
									$agents[0]->talk_sec = ($thedate-$agents[0]->talk_epoch);
									$updatefields = array_merge(array('dispo_epoch'=>$thedate,'talk_sec'=>$agents[0]->talk_sec),$updatethis);
								}else{
									if($agents[0]->dispo_epoch < 1){
										$agents[0]->dispo_sec = ($thedate-$agents[0]->dispo_epoch);
										$updatefields = array('dispo_sec'=>$agents[0]->dispo_sec);
									}
								}
							}
						}
						$this->db->where(array('agent_log_id'=>$agents[0]->agent_log_id));
						$this->db->update('vicidial_agent_log',$updatefields);
					}
					$this->db->delete('vicidial_live_agents',array('user'=>$agents[0]->user));
					#agent session
					$this->db->delete('go_agent_sessions',array('sess_agent_user'=>$agents[0]->user));
					
					$valuetolog = array('user'=>$agents[0]->user,'event'=>'LOGOUT','campaign_id'=>$agents[0]->campaign_id,'event_date'=>$NOW_TIME,
							    'event_epoch'=>$thedate,'user_group'=>$agents[0]->user_group);
					# force logout the user
					$this->commonhelper->auditlogs('vicidial_user_log',$valuetolog);
					    
					$this->db->trans_complete();
			    
					if($this->db->trans_status !== false){
						$result = 0;
						//die('Emergency logout complete make sure agent browser is closed');
					} else {
						$result = 1;
						$err_agents .= $agents[0]->user.", ";
						//die('Problem in attempt to logout agent '.$agents[0]->user);
					}
				    #}
				} else {
					$VliveagentSess = $this->db->get_where('go_agent_sessions',array('sess_agent_user'=>$agent_logged))->result();
					if (!empty($VliveagentSess))
					{
						$this->db->trans_start();
							$this->db->delete('go_agent_sessions',array('sess_agent_user'=>$agent_logged));
						$this->db->trans_complete();
						
						if($this->db->trans_status !== false){
							$result = 0;
							//die('Emergency logout complete make sure agent browser is closed');
						} else {
							$result = 1;
							$err_agents .= "$agent_logged, ";
							//die('Problem in attempt to logout agent '.$agent_logged);
						}
					} else {
						$result = 0;
						//die("Agent ".$agent_logged." is not logged in");
					}
				}
			}
		}
		
		if ($result)
		{
			die("Problem in attempt to logout the following agent(s):\n".trim($err_agents,", "));
		} else {
			die('Emergency logout complete make sure agent(s) browser is closed');
		}
	}

	function go_upload_leads()
	{
		$final = $this->uri->segment(3);
		
		//$list_id = $this->input->post('list_id');
		//$query = $this->db->query("SELECT campaign_id FROM vicidial_lists WHERE list_id='$list_id'");
		//$campaign_id = $query->row()->campaign_id;
		//$query = $this->db->query("UPDATE campaign_changedate FROM vicidial_campaigns WHERE campaign_id='$campaign_id'");
		
		if ($final != 'final')
		{
			$config['upload_path'] = '/tmp/';
			$config['allowed_types'] = 'xls|xlsx|csv';
			$config['overwrite'] = true;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
	//
	//		$data['file_exten'] = $file_exten;
	//		$data['column_name'] = $result['column_name'];
	//		$this->load->view('go_campaign/go_campaign_wizard_fields',$data);
			$LF_name = $_FILES['leadFile']['name'];
			if (preg_match("/\.csv$/i", $LF_name))
			{
				$_FILES['leadFile']['type'] = "text/x-comma-separated-values";
			}

			if ( ! $this->upload->do_upload("leadFile"))
			{
				$error = array('error' => $this->upload->display_errors());

	//var_dump($error);
				$this->load->view('go_campaign/go_campaign_wizard_output', $error);
			}
			else
			{
	//			$data = array('upload_data' => $this->upload->data());
				$result = $this->go_campaign->go_upload_leads();

	//var_dump($data);
				$data['delim_name'] = $result['delim_name'];
				$data['columns'] = $result['column_name'];
				$data['list_id'] = $result['list_id'];
				$data['phone_code'] = $result['phone_code'];
				$data['file_name'] = $result['file_name'];
				$data['file_ext'] = $result['file_ext'];
				$this->load->view('go_campaign/go_campaign_wizard_fields', $data);
			}
		}
		else
		{
			$dupcheck = $this->uri->segment(4);
			$list_id_override = $this->uri->segment(5);
			$phone_code_override = $this->uri->segment(6);
			$args = $this->uri->segment(7);
			$file_name = $this->uri->segment(8);
			$file_ext = $this->uri->segment(9);
			$lead_file = "/tmp/{$file_name}.{$file_ext}";
			$resultHTML = '';

			$fields = $this->go_campaign->go_unserialize($args);

			foreach ($fields as $field => $value)
			{
				${$field} = $value;
			}

			$dupcheck = str_replace("CHECK","DUP",$dupcheck);

			flush();
			$total=0; $good=0; $bad=0; $dup=0; $post=0; $phone_list='';

			$file=fopen("$lead_file", "r");

			$buffer=fgets($file, 4096);
			$tab_count=substr_count($buffer, "\t");
			$pipe_count=substr_count($buffer, "|");

			if ($tab_count>$pipe_count) {$delimiter="\t";  $delim_name="tab";} else {$delimiter="|";  $delim_name="pipe";}
			$field_check=explode($delimiter, $buffer);

				if (count($field_check)>=2) {
					flush();
					$file=fopen("$lead_file", "r");
					//$data['processfile'] = "<center><font face='arial, helvetica' size=3 color='#009900'><B>Processing file...\n";

					if (strlen($list_id_override)>0) {
					//print "<BR><BR>LIST ID OVERRIDE FOR THIS FILE: $list_id_override<BR><BR>";
					}

					if (strlen($phone_code_override)>0) {
					//print "<BR><BR>PHONE CODE OVERRIDE FOR THIS FILE: $phone_code_override<BR><BR>";
					}

					$systemlookup = $this->golist->systemsettingslookup();
					foreach($systemlookup as $sysinfo){
						$use_non_latin = $sysinfo->use_non_latin;
						$admin_web_directory = $sysinfo->admin_web_directory;
						$custom_fields_enabled = $sysinfo->custom_fields_enabled;
					}


					if ($custom_fields_enabled > 0) {
							$tablecount_to_print=0;
							$fieldscount_to_print=0;
							$fields_to_print=0;

							$stmt="SHOW TABLES LIKE \"custom_$list_id_override\";";
							$rslt = $this->db->query($stmt);
							$tablecount_to_print = $rslt->num_rows;

							if ($tablecount_to_print > 0) {
									$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id_override';";
									$rslt = $this->db->query($stmt);
									$fieldscount_to_print = $rslt->num_rows;

									if ($fieldscount_to_print > 0) {
										$stmt="SELECT field_label,field_type from vicidial_lists_fields where list_id='$list_id_override' order by field_rank,field_order,field_label;";

										$rslt = $this->db->query($stmt);
										$fields_to_print = $rslt->num_rows;

										$fields_list='';
										$o=0;

										while ($fields_to_print > $o) {
											$rowx = $rslt->row();
											$A_field_label[$o] =	$rowx->field_label;
											$A_field_type[$o] =	$rowx->field_type;
											$A_field_value[$o] =	'';
										$o++;
										}
									}
							}
					}


					while (!feof($file)) {
							$record++;
							$buffer=rtrim(fgets($file, 4096));
							$buffer=stripslashes($buffer);

							if (strlen($buffer)>0) {
								$row=explode($delimiter, eregi_replace("[\'\"]", "", $buffer));
								$lrow=$row;

									$pulldate=date("Y-m-d H:i:s");
									$entry_date =			"$pulldate";
									$modify_date =			"";
									$status =				"NEW";
									$user ="";
									$vendor_lead_code =		$row[$vendor_lead_code_field];
									$source_code =			$row[$source_id_field];
									$source_id=$source_code;
									$list_id =				$row[$list_id_field];
									$gmt_offset =			'0';
									$called_since_last_reset='N';
									$phone_code =			eregi_replace("[^0-9]", "", $row[$phone_code_field]);
									$phone_number =			eregi_replace("[^0-9]", "", $row[$phone_number_field]);
									$title =				$row[$title_field];
									$first_name =			$row[$first_name_field];
									$middle_initial =		$row[$middle_initial_field];
									$last_name =			$row[$last_name_field];
									$address1 =				$row[$address1_field];
									$address2 =				$row[$address2_field];
									$address3 =				$row[$address3_field];
									$city =					$row[$city_field];
									$state =				$row[$state_field];
									$province =				$row[$province_field];
									$postal_code =			$row[$postal_code_field];
									$country_code =			$row[$country_code_field];
									$gender =				$row[$gender_field];
									$date_of_birth =		$row[$date_of_birth_field];
									$alt_phone =			eregi_replace("[^0-9]", "", $row[$alt_phone_field]);
									$email =				$row[$email_field];
									$security_phrase =		$row[$security_phrase_field];
									$comments =				trim($row[$comments_field]);
									$rank =					$row[$rank_field];
									$owner =				$row[$owner_field];

									### REGEX to prevent weird characters from ending up in the fields
									$field_regx = "['\"`\\;]";



									# replace ' " ` \ ; with nothing
									$vendor_lead_code =		eregi_replace($field_regx, "", $vendor_lead_code);
									$source_code =			eregi_replace($field_regx, "", $source_code);
									$source_id = 			eregi_replace($field_regx, "", $source_id);
									$list_id =				eregi_replace($field_regx, "", $list_id);
									$phone_code =			eregi_replace($field_regx, "", $phone_code);
									$phone_number =			eregi_replace($field_regx, "", $phone_number);
									$title =				eregi_replace($field_regx, "", $title);
									$first_name =			eregi_replace($field_regx, "", $first_name);
									$middle_initial =		eregi_replace($field_regx, "", $middle_initial);
									$last_name =			eregi_replace($field_regx, "", $last_name);
									$address1 =				eregi_replace($field_regx, "", $address1);
									$address2 =				eregi_replace($field_regx, "", $address2);
									$address3 =				eregi_replace($field_regx, "", $address3);
									$city =					eregi_replace($field_regx, "", $city);
									$state =				eregi_replace($field_regx, "", $state);
									$province =				eregi_replace($field_regx, "", $province);
									$postal_code =			eregi_replace($field_regx, "", $postal_code);
									$country_code =			eregi_replace($field_regx, "", $country_code);
									$gender =				eregi_replace($field_regx, "", $gender);
									$date_of_birth =		eregi_replace($field_regx, "", $date_of_birth);
									$alt_phone =			eregi_replace($field_regx, "", $alt_phone);
									$email =				eregi_replace($field_regx, "", $email);
									$security_phrase =		eregi_replace($field_regx, "", $security_phrase);
									$comments =				eregi_replace($field_regx, "", $comments);
									$rank =					eregi_replace($field_regx, "", $rank);
									$owner =				eregi_replace($field_regx, "", $owner);

									$USarea = 			substr($phone_number, 0, 3);

									if (strlen($list_id_override)>0) {
										#	print "<BR><BR>LIST ID OVERRIDE FOR THIS FILE: $list_id_override<BR><BR>";
										$list_id = $list_id_override;
									}
									if (strlen($phone_code_override)>0) {
										$phone_code = $phone_code_override;
									}

										##### BEGIN custom fields columns list ###
										$custom_SQL='';
										if ($custom_fields_enabled > 0)
											{
											if ($tablecount_to_print > 0)
												{
												if ($fieldscount_to_print > 0)
													{
													$o=0;
													while ($fields_to_print > $o)
														{
														$A_field_value[$o] =	'';
														$field_name_id = $A_field_label[$o] . "_field";

													#	if ($DB>0) {echo "$A_field_label[$o]|$A_field_type[$o]\n";}

														if ( ($A_field_type[$o]!='DISPLAY') and ($A_field_type[$o]!='SCRIPT') )
															{
															if (!preg_match("/\|$A_field_label[$o]\|/",$vicidial_list_fields))
																{
																if (isset($_GET["$field_name_id"]))				{$form_field_value=$_GET["$field_name_id"];}
																	elseif (isset($_POST["$field_name_id"]))	{$form_field_value=$_POST["$field_name_id"];}

																if ($form_field_value >= 0)
																	{
																	$A_field_value[$o] =	$row[$form_field_value];
																	# replace ' " ` \ ; with nothing
																	$A_field_value[$o] =	eregi_replace($field_regx, "", $A_field_value[$o]);

																	$custom_SQL .= "$A_field_label[$o]='$A_field_value[$o]',";
																	}
																}
															}
														$o++;
														}
													}
												}
											}
										##### END custom fields columns list ###

										$custom_SQL = preg_replace("/,$/","",$custom_SQL);

									## checking duplicate portion

									##### Check for duplicate phone numbers in vicidial_list table for all lists in a campaign #####
									if ($dupcheck=='DUPCAMP') {

										$dup_lead=0;
										$dup_lists='';
										$stmt="select campaign_id from vicidial_lists where list_id='$list_id';";

										$rslt = $this->db->query($stmt);
										$ci_recs = $rslt->num_rows;

										if ($ci_recs > 0) {
											$row = $rslt->row();
											$dup_camp = $row->campaign_id;
											$stmt="select list_id from vicidial_lists where campaign_id='$dup_camp';";

											$rslt = $this->db->query($stmt);
											$li_recs = $rslt->num_rows;

											if ($li_recs > 0) 	{
												$L=0;
												while ($li_recs > $L) {
													$row = $rslt->row();
													$dup_lists .=	"'$row->list_id',";
													$L++;
												}
												$dup_lists = eregi_replace(",$",'',$dup_lists);

												$stmt="select list_id from vicidial_list where phone_number='$phone_number' and list_id IN($dup_lists) limit 1;";
												$rslt = $this->db->query($stmt);
												$pc_recs = $rslt->num_rows;

												if ($pc_recs > 0) {
													$dup_lead=1;
													$row = $rslt->row();
													$dup_lead_list = $row->list_id;
													$dup++;

												}
												if ($dup_lead < 1) {
													if (eregi("$phone_number$US$list_id",$phone_list))
														{$dup_lead++; $dup++;}
													}
												}
											}
									}


									##### Check for duplicate phone numbers in vicidial_list table entire database #####
									if (eregi("DUPSYS",$dupcheck)) {
										$dup_lead=0;
										$stmt="select list_id from vicidial_list where phone_number='$phone_number';";
										$rslt = $this->db->query($stmt);
										$pc_recs = $rslt->num_rows;

										if ($pc_recs > 0) {
											$dup_lead=1;
											$row = $rslt->row();
											$dup_lead_list = $row->list_id;
										}

										if ($dup_lead < 1) {
											if (eregi("$phone_number$US$list_id",$phone_list))
												{$dup_lead++; $dup++;}
										}
									}

									##### Check for duplicate phone numbers in vicidial_list table for one list_id #####
									if ($dupcheck == "DUPLIST") {
										$dup_lead=0;
										$stmt="select count(*) from vicidial_list where phone_number='$phone_number' and list_id='$list_id';";
										$rslt = $this->db->query($stmt);
										$pc_recs = $rslt->num_rows;

										if ($pc_recs > 0) {
											$row = $rslt->row();
											$dup_lead = $row->list_id;
											$dup_lead_list =	$list_id;
											$dup++;
											//die($dup_lead_list);
										}

										if ($dup_lead < 1) {
											if (eregi("$phone_number$US$list_id",$phone_list))
												{$dup_lead++; $dup++;}
										}


									}

									##### Check for duplicate title and alt-phone in vicidial_list table for one list_id #####
									if ($dupcheck == "DUPTITLEALTPHONELIST")
										{
										$dup_lead=0;
										$stmt="select count(*) from vicidial_list where title='$title' and alt_phone='$alt_phone' and list_id='$list_id';";
										$rslt = $this->db->query($stmt);
										$pc_recs = $rslt->num_rows;
										if ($pc_recs > 0) {
											$row = $rslt->row();
											$dup_lead = $row->list_id;
											$dup_lead_list =	$list_id;
										}
										if ($dup_lead < 1) {
											if (eregi("$alt_phone$title$US$list_id",$phone_list))
												{$dup_lead++; $dup++;}
											}
										}

										##### Check for duplicate phone numbers in vicidial_list table entire database #####
										if ($dupcheck == "DUPTITLEALTPHONESYS") {
											$dup_lead=0;
											$stmt="select list_id from vicidial_list where title='$title' and alt_phone='$alt_phone';";
											$rslt = $this->db->query($stmt);
											$pc_recs = $rslt->num_rows;
											if ($pc_recs > 0) {
												$dup_lead=1;
												$row = $rslt->row();
												$dup_lead_list = $row->list_id;
												$dup++;
											}
											if ($dup_lead < 1) {
												if (eregi("$alt_phone$title$US$list_id",$phone_list))
													{$dup_lead++; $dup++;}
												}
										} #end check dups



								if ( (strlen($phone_number)>6 && strlen($phone_number) < 11) and ($dup_lead<1) and ($list_id >= 100 )) {

										if (strlen($phone_code)<1) {$phone_code = '1';}
										if (eregi("TITLEALTPHONE",$dupcheck)) {
											$phone_list .= "$alt_phone$title$US$list_id|";
										} else {
											$phone_list .= "$phone_number$US$list_id|";
										}

										$gmt_offset = $this->lookup_gmt($phone_code,$USarea,$state,$LOCAL_GMT_OFF_STD,$Shour,$Smin,$Ssec,$Smon,$Smday,$Syear,$postalgmt,$postal_code,$owner);

										//$gmt_offset = 10.00; //ganito muna

										if (strlen($custom_SQL)>3) {
											$stmtZ = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values('','$entry_date','$modify_date','$status','$user','$vendor_lead_code','$source_id','$list_id','$gmt_offset','$called_since_last_reset','$phone_code','$phone_number','$title','$first_name','$middle_initial','$last_name','$address1','$address2','$address3','$city','$state','$province','$postal_code','$country_code','$gender','$date_of_birth','$alt_phone','$email','$security_phrase','$comments',0,'2008-01-01 00:00:00','$rank','$owner','$list_id');";

										$rslt = $this->db->query($stmtZ);
										$lead_id = $this->db->insert_id();
										$affected_rows = $this->db->affected_rows();

											$multistmt='';

											$custom_SQL_query = "INSERT INTO custom_$list_id_override SET lead_id='$lead_id',$custom_SQL;";
											$rslt = $this->db->query($custom_SQL_query);

											} else {

												if ($multi_insert_counter > 8) {
													### insert good record into vicidial_list table ###
													$stmtZx = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values$multistmt('','$entry_date','$modify_date','$status','$user','$vendor_lead_code','$source_id','$list_id','$gmt_offset','$called_since_last_reset','$phone_code','$phone_number','$title','$first_name','$middle_initial','$last_name','$address1','$address2','$address3','$city','$state','$province','$postal_code','$country_code','$gender','$date_of_birth','$alt_phone','$email','$security_phrase','$comments',0,'2008-01-01 00:00:00','$rank','$owner','0');";

													$rslt = $this->db->query($stmtZx);

													$multistmt='';
													$multi_insert_counter=0;

												} else {

													$multistmt .= "('','$entry_date','$modify_date','$status','$user','$vendor_lead_code','$source_id','$list_id','$gmt_offset','$called_since_last_reset','$phone_code','$phone_number','$title','$first_name','$middle_initial','$last_name','$address1','$address2','$address3','$city','$state','$province','$postal_code','$country_code','$gender','$date_of_birth','$alt_phone','$email','$security_phrase','$comments',0,'2008-01-01 00:00:00','$rank','$owner','0'),";
													$multi_insert_counter++;

												}
											}
										$good++;
									} else {

										if ($bad < 1000000)	{
											if ( $list_id < 100 ) {
												$resultHTML .= "<BR></b><font size=1 color=red>record $total BAD- PHONE: $phone_number ROW: |$lrow[0]| INVALID LIST ID</font><b>\n";
											} else {
												$resultHTML .= "<BR></b><font size=1 color=red>record $total BAD- PHONE: $phone_number ROW: |$lrow[0]| DUP: $dup_lead  $dup_lead_list</font><b>\n";
											}
										}
										$bad++;
									}
									
									if ($bad < 1) {
										$resultHTML = "<font size=1 color=red>No duplicate number found.</font>";
									}

									$total++;

									if ($total%100==0) {
											//print "<script language='JavaScript1.2'>ShowProgress($good, $bad, $total, $dup, $post)</script>";

											/* echo "<script type=\"text/javascript\">";
											echo "alert('good leads '+$good+'Bad leads'+$bad+' total'+$total);";
											echo "</script>"; */

											usleep(1000);
											flush();
									}

									## end checking duplicate

							} //end buffer if
					} // end while

					if ($multi_insert_counter!=0) {
							$stmtZ = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values".substr($multistmt, 0, -1).";";
							$rslt = $this->db->query($stmtZ);

					}

			} else {

				$resultHTML .= "<script type=\"text/javascript\">";
				$resultHTML .= "alert('<B>ERROR: The file does not have the required number of fields to process it.</B>');";
				$resultHTML .= "</script>";


			}  //dulong dulo
// 			var_dump($resultHTML);


// 			$this->extgetval($lead_file, $list_id_override, $phone_code_override, $dupcheck);
			$data['final'] = $final;
			$data['good'] = $good;
			$data['bad'] = $bad;
			$data['total'] = $total;
			$data['dup'] = $dup;
			$data['post'] = $post;
			$data['resultHTML'] = $resultHTML;
			$this->load->view('go_campaign/go_campaign_wizard_fields', $data);
		}
	}

	function go_upload_wav()
	{
		$campaign_id = $this->input->post('campaign_id');
		$config['upload_path'] = '/tmp/';
		$config['allowed_types'] = 'wav|x-wav';
		$config['overwrite'] = true;
		$this->load->library('upload', $config);
		//$this->upload->initialize($config);

		$upload_path = set_realpath("/var/lib/asterisk/sounds");

		foreach ($_FILES as $fileName => $file)
		{
			if ( ! empty($file['name']))
			{
				if ( ! $this->upload->do_upload($fileName))
				{
					$error = array('error' => $this->upload->display_errors());
					$result .= "ERROR: File ".$file['name']." not uploaded.\n";
					$result .= "\t".$error['error']."\n";

					//$this->load->view('upload_form', $error);
				}
				else
				{
					//$data = array('upload_data' => $this->upload->data());
					$prefix = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? $this->session->userdata('user_group')."_" : "";
					$fname = str_replace(' ','_',$file['name']);
					copy($config['upload_path'] . $fname,"$upload_path/go_{$prefix}" . $fname);
					chmod("$upload_path/go_{$prefix}" . $fname, 0777);
					$result .= "SUCCESS: File ".$fname." uploaded.\n";
					$fn .= "$fileName\n";
					$files .= "go_{$prefix}".str_replace(".wav",'',$fname)." ";

					$this->go_campaign->rsync_file($fname);
				}
			}
		}
		echo "wav|$result|$fn|".trim($files);
	}

	function go_get_campaign_statuses()
	{
		$campaign_id = $this->uri->segment(3);
		$action = $this->uri->segment(4);
		$str = $this->go_campaign->go_unserialize($this->uri->segment(5));
		$data['update'] = 0;

		if ($action == 'add_status')
		{
			$data['update'] = $this->go_campaign->go_add_campaign_statuses();
			$this->commonhelper->auditadmin('ADD','Added Status '.$str['status'].' on Campaign '.$campaign_id);
		}

		if ($action == 'modify_status')
		{
			$data['status_view'] = $this->go_campaign->go_modify_campaign_statuses();
		}

		if ($action == 'save_status')
		{
			$return = $this->go_campaign->go_save_campaign_statuses();
			list($update,$db_query) = explode("|",$return);
			$data['update'] = $update;
			$this->commonhelper->auditadmin('MODIFY','Modified Status '.$str['status_mod'].' from Campaign '.$campaign_id,"$db_query");
		}

		if ($action == 'delete_status')
		{
			$return = $this->go_campaign->go_delete_campaign_statuses();
			list($update,$db_query) = explode("|",$return);
			$data['update'] = $update;
			$this->commonhelper->auditadmin('DELETE','Deleted Status '.$this->uri->segment(5).' from Campaign '.$campaign_id,"$db_query");
		}

		$data['status_list'] = $this->go_campaign->go_get_campaign_statuses();

		$data['categories'] = $this->go_campaign->go_get_status_categories();
		$data['status'] = $str['status'];
		$data['action'] = $action;
		$data['campaign_id'] = $campaign_id;
        $this->load->view('go_campaign/go_campaign_statuses',$data);
	}

	function go_add_new_statuses()
	{
		$campaign_id = $this->uri->segment(3);
		$string = $this->go_campaign->go_unserialize($this->uri->segment(5));

		if ($campaign_id == 'ALLCAMP')
		{
			$campaigns = $this->go_campaign->go_get_campaigns();

			foreach ($campaigns['list'] as $camp)
			{
				$update = $this->go_campaign->go_add_campaign_statuses($camp->campaign_id);
				$this->commonhelper->auditadmin('ADD','Added Status '.$string['status'].' on Campaign '.$camp->campaign_id);
			}
		} else {
			$update = $this->go_campaign->go_add_campaign_statuses();
			$this->commonhelper->auditadmin('ADD','Added Status '.$string['status'].' on Campaign '.$camp->campaign_id);
		}
	}

	function go_monitoring()
	{
	    $realtime = $this->go_monitoring->go_update_realtime();
	    $data['realtimeHTML'] =		$realtime['html'];
	    $data['no_live_calls'] =	$realtime['no_live_calls'];
	    $data['no_agents_logged'] =	$realtime['no_agents_logged'];
	    $data['active_calls'] =		$realtime['active_calls'];
	    $data['agent_total'] =		$realtime['agent_total'];
	    $data['agent_incall'] =		$realtime['agent_incall'];
	    $data['agent_paused'] =		$realtime['agent_paused'];
	    $data['agent_ready'] =		$realtime['agent_ready'];
	    $data['agent_waiting'] =	$realtime['agent_waiting'];
		$data['user_level'] =		$this->userLevel;
	    $this->load->view('go_monitoring/go_realtime',$data);
	}

	function go_view_info($camp, $type)
	{
	    if ($type=='info')
	    {
		$data = $this->go_campaign->go_get_settings();
	    }
	    else
	    {
		$campaigns = $this->go_campaign->go_get_campaigns();
		$data['status_list'] = $this->go_campaign->go_get_campaign_statuses();

		foreach($data['status_list'] as $status)
		{
			$camp_status[$status->campaign_id] .= $status->status . " ";
			$status_name[$status->status] = $status->status_name;
			$camp_name[$status->campaign_id] = $status->campaign_name;
		}

		$data['campaign'] = $campaigns;
		$data['camp_status'] = $camp_status;
		$data['camp_name'] = $camp_name;
		$data['status_name'] = $status_name;
	    }

	    $data['type'] = $type;
	    $data['campaign_id'] = $camp;
	    $this->load->view('go_campaign/go_campaign_view',$data);
	}
	
	function go_lead_recycle()
	{
		$type = $this->uri->segment(3);
		$strs = $this->uri->segment(4);
		
		switch ($type)
		{
			case "add":
				$strarr = $this->go_campaign->go_unserialize($strs);
				$camp = $strarr['leadCampID'];
				$status = $strarr['leadStatusID'];
				$delay = $strarr['attempt_delay'] * 60;
				$attempt = $strarr['attempt_maximum'];
				$query = $this->db->query("INSERT INTO vicidial_lead_recycle (campaign_id,status,attempt_delay,attempt_maximum,active) values('$camp','$status','$delay','$attempt','N')");
				$return = true;
				break;
			
			case "modify":
				$camp = $this->uri->segment(4);
				
				$query = $this->db->query("SELECT list_id FROM vicidial_lists WHERE campaign_id='$camp'");
				foreach ($query->result() as $list_id)
				{
					$camp_list .= "'{$list_id->list_id}',";
				}
				$camp_list = (strlen($camp_list) > 0) ? trim($camp_list, ",") : "''";
				
				$query = $this->db->query("SELECT status,attempt_delay,attempt_maximum,active FROM vicidial_lead_recycle WHERE campaign_id='$camp'");
				
				foreach ($query->result() as $id => $row)
				{
					$recycle_cnt = 'Y';
					if ($row->attempt_maximum > 0)
						$recycle_cnt = "$recycle_cnt{$row->attempt_maximum}";
						
					$query2 = $this->db->query("SELECT * FROM vicidial_list WHERE status='{$row->status}' AND list_id IN($camp_list) AND called_since_last_reset='$recycle_cnt'");
					$rcycle[$id]['leads_limit'] = $query2->num_rows();
					
					$rcycle[$id]['status'] = $row->status;
					$rcycle[$id]['attempt_delay'] = $row->attempt_delay;
					$rcycle[$id]['attempt_maximum'] = $row->attempt_maximum;
					$rcycle[$id]['active'] = $row->active;
				}
				
				$data['campaign_id'] = $camp;
				$data['recycle_list'] = $rcycle;
				$data['action'] = "modify_recycle";
		        $this->load->view('go_campaign/go_campaign_statuses',$data);
				break;
			
			case "delete":
				$camp = $this->uri->segment(4);
				$status = $this->uri->segment(5);
				
				if (strlen($status) > 0)
				{
					$query = $this->db->query("DELETE FROM vicidial_lead_recycle WHERE campaign_id='$camp' AND status='$status';");
				} else {
					$query = $this->db->query("DELETE FROM vicidial_lead_recycle WHERE campaign_id='$camp';");
				}
				
				$return = "Success";
				break;
			
			case "check":
				$camp = $this->uri->segment(4);
				$status = $this->uri->segment(5);
				
				$query = $this->db->query("SELECT * FROM vicidial_lead_recycle WHERE campaign_id='$camp' AND status='$status';");
				$return = $query->num_rows();
				if ($return)
				{
					$return = "<small style=\"color:red;\">Not Available</small>";
				} else {
					$return = "<small style=\"color:green;\">Available</small>";
				}
				break;
			
			case "view":
				$camp = $this->uri->segment(4);
				$status = $this->uri->segment(5);
				
				$query = $this->db->query("SELECT format((attempt_delay/60),0) AS attempt_delay,attempt_maximum,active FROM vicidial_lead_recycle WHERE campaign_id='$camp' AND status='$status'");
				$return = json_encode($query->row());
				break;
			
			case "save":
				$camp = $this->uri->segment(4);
				$string = $this->uri->segment(5);
				$strarr = $this->go_campaign->go_unserialize($string);
				$status = $strarr['status'];
				$delay = $strarr['attempt_delay'] * 60;
				$attempt = $strarr['attempt_maximum'];
				$active = $strarr['active'];
				$query = $this->db->query("UPDATE vicidial_lead_recycle SET attempt_delay='$delay',attempt_maximum='$attempt',active='$active' WHERE campaign_id='$camp' AND status='$status';");
				$return = true;
				break;
			
			case "delselected":
				$camp_list = explode(",",$this->uri->segment(4));
				foreach ($camp_list as $camp)
				{
					$query = $this->db->query("DELETE FROM vicidial_lead_recycle WHERE campaign_id='$camp'");
				}
				$return = true;
				break;
			
			case "delete_status":
				$camp = $this->uri->segment(4);
				$status_list = explode(",",$this->uri->segment(5));
				foreach ($status_list as $status)
				{
					$query = $this->db->query("DELETE FROM vicidial_lead_recycle WHERE campaign_id='$camp' AND status='$status'");
				}
				$return = true;
				break;

			default:
		}
		echo $return;
	}
	
	function go_pause_codes()
	{
		$type = $this->uri->segment(3);
		
		switch ($type)
		{
			case "add":
				$string = $this->go_campaign->go_unserialize($this->uri->segment(4));
				$camp = $string['pauseCampID'];
				$pause_code = $string['pause_code'];
				$pause_code_name = $string['pause_code_name'];
				$billable = $string['billable'];
				
				$query = $this->db->query("INSERT INTO vicidial_pause_codes (pause_code,pause_code_name,campaign_id,billable) VALUES('$pause_code','$pause_code_name','$camp','$billable')");
				$return = true;
				break;
			
			case "check":
				$camp = $this->uri->segment(4);
				$code = $this->uri->segment(5);
				
				$query = $this->db->query("SELECT * FROM vicidial_pause_codes WHERE campaign_id='$camp' AND pause_code='$code';");
				$return = $query->num_rows();
				if ($return)
				{
					$return = "<small style=\"color:red;\">Not Available</small>";
				} else {
					$return = "<small style=\"color:green;\">Available</small>";
				}
				break;
			
			case "delete":
				$camp = $this->uri->segment(4);
				$code = $this->uri->segment(5);
				
				if (strlen($code) > 0)
				{
					$query = $this->db->query("DELETE FROM vicidial_pause_codes WHERE campaign_id='$camp' AND pause_code='$code';");
				} else {
					$query = $this->db->query("DELETE FROM vicidial_pause_codes WHERE campaign_id='$camp';");
				}
				
				$return = "Success";
				break;
			
			case "modify":
				$camp = $this->uri->segment(4);
				$query = $this->db->query("SELECT pause_code,pause_code_name,billable from vicidial_pause_codes where campaign_id='$camp' order by pause_code;");
				
				foreach ($query->result() as $id => $row)
				{
					$pcode[$id]['pause_code'] = $row->pause_code;
					$pcode[$id]['pause_code_name'] = $row->pause_code_name;
					$pcode[$id]['billable'] = $row->billable;
				}
				
				$data['campaign_id'] = $camp;
				$data['pause_list'] = $pcode;
				$data['action'] = "modify_pausecode";
		        $this->load->view('go_campaign/go_campaign_statuses',$data);
				break;
			
			case "view":
				$camp = $this->uri->segment(4);
				$code = $this->uri->segment(5);
				
				$query = $this->db->query("SELECT pause_code,pause_code_name,billable FROM vicidial_pause_codes WHERE campaign_id='$camp' AND pause_code='$code'");
				$return = json_encode($query->row());
				break;
			
			case "save":
				$camp = $this->uri->segment(4);
				$string = $this->uri->segment(5);
				$strarr = $this->go_campaign->go_unserialize($string);
				$pause_code = $strarr['pause_code'];
				$pause_code_name = $strarr['pause_code_name'];
				$billable = $strarr['billable'];
				$query = $this->db->query("UPDATE vicidial_pause_codes SET pause_code_name='$pause_code_name',billable='$billable' WHERE campaign_id='$camp' AND pause_code='$pause_code';");
				$return = true;
				break;
			
			case "delselected":
				$camp_list = explode(",",$this->uri->segment(4));
				foreach ($camp_list as $camp)
				{
					$query = $this->db->query("DELETE FROM vicidial_pause_codes WHERE campaign_id='$camp'");
				}
				$return = true;
				break;
			
			case "delete_status":
				$camp = $this->uri->segment(4);
				$pause_list = explode(",",$this->uri->segment(5));
				foreach ($pause_list as $pause_code)
				{
					$query = $this->db->query("DELETE FROM vicidial_pause_codes WHERE campaign_id='$camp' AND pause_code='$pause_code'");
				}
				$return = true;
				break;
			
			default:
		}
		
		echo $return;
	}
	
	function go_check_status()
	{
		$camp = $this->uri->segment(3);
		$status = $this->uri->segment(4);
		
		$query = $this->db->query("SELECT * FROM vicidial_campaign_statuses WHERE campaign_id='$camp' AND status='$status';");
		$campExist = $query->num_rows();
		
		$query = $this->db->query("SELECT * FROM vicidial_statuses WHERE status='$status';");
		$systemExist = $query->num_rows();
		
		if ($campExist || $systemExist)
		{
			$return = "-- <small style=\"color:red;\">Not Available</small>";
		} else {
			$return = "-- <small style=\"color:green;\">Available</small>";
		}
		
		echo $return;
	}
	
	function go_hot_keys()
	{
		$type = $this->uri->segment(3);
		
		switch ($type)
		{
			case "add":
				$string = $this->go_campaign->go_unserialize($this->uri->segment(4));
				$camp = $string['hotKeysCampID'];
				$hotkey = $string['hotKeys'];
				$status = $string['statusHotKeys'];
				
				$query = $this->db->query("SELECT status_name FROM vicidial_statuses WHERE status='$status'");
				$status_name = $query->row()->status_name;
				if ($query->num_rows() < 1)
				{
					$query = $this->db->query("SELECT status_name FROM vicidial_campaign_statuses WHERE status='$status'");
					$status_name = $query->row()->status_name;
				}
				
				$query = $this->db->query("INSERT INTO vicidial_campaign_hotkeys VALUES('$status','$hotkey','$status_name','Y','$camp')");
				$return = true;
				break;
			
			case "check":
				$camp = $this->uri->segment(4);
				$hotkey = $this->uri->segment(5);
				
				$query = $this->db->query("SELECT * FROM vicidial_campaign_hotkeys WHERE campaign_id='$camp' AND hotkey='$hotkey';");
				$return = $query->num_rows();
				if ($return)
				{
					$return = "<small style=\"color:red;\">Not Available</small>";
				} else {
					$return = "<small style=\"color:green;\">Available</small>";
				}
				break;
			
			case "delete":
				$camp = $this->uri->segment(4);
				$hotkey = $this->uri->segment(5);
				
				if (strlen($hotkey) > 0)
				{
					$query = $this->db->query("DELETE FROM vicidial_campaign_hotkeys WHERE campaign_id='$camp' AND hotkey='$hotkey';");
				} else {
					$query = $this->db->query("DELETE FROM vicidial_campaign_hotkeys WHERE campaign_id='$camp';");
				}
				
				$return = "Success";
				break;
			
			case "modify":
				$camp = $this->uri->segment(4);
				$query = $this->db->query("SELECT status,hotkey,status_name from vicidial_campaign_hotkeys where campaign_id='$camp' order by hotkey;");
				
				foreach ($query->result() as $id => $row)
				{
					$hotkeys[$id]['status'] = $row->status;
					$hotkeys[$id]['hotkey'] = $row->hotkey;
					$hotkeys[$id]['status_name'] = $row->status_name;
				}
				
				$data['campaign_id'] = $camp;
				$data['hotkeys_list'] = $hotkeys;
				$data['action'] = "modify_hotkeys";
		        $this->load->view('go_campaign/go_campaign_statuses',$data);
				break;
			
			case "delselected":
				$camp_list = explode(",",$this->uri->segment(4));
				foreach ($camp_list as $camp)
				{
					$query = $this->db->query("DELETE FROM vicidial_campaign_hotkeys WHERE campaign_id='$camp'");
				}
				$return = true;
				break;
			
			case "delete_status":
				$camp = $this->uri->segment(4);
				$hotkey_list = explode(",",$this->uri->segment(5));
				foreach ($hotkey_list as $hotkey)
				{
					$query = $this->db->query("DELETE FROM vicidial_campaign_hotkeys WHERE campaign_id='$camp' AND hotkey='$hotkey'");
				}
				$return = true;
				break;
			
			default:
		}
		
		echo $return;
	}
	
	function go_sql_filters()
	{
		$type = $this->uri->segment(3);
		$groupId = $this->go_campaign->go_get_groupid();
		if (!$this->commonhelper->checkIfTenant($groupId))
		{
		   $ul = '';
		}
		else
		{
		   $ul = "AND user_group='$groupId'";
		   $addedSQL = "WHERE user_group='$groupId'";
		}
		
		switch ($type)
		{	
			case "check":
				$filterid = $this->uri->segment(4);
				
				$query = $this->db->query("SELECT * FROM vicidial_lead_filters WHERE lead_filter_id='$filterid';");
				$return = $query->num_rows();
				if ($return)
				{
					$return = "<small style=\"color:red;\">Not Available</small>";
				} else {
					$return = "<small style=\"color:green;\">Available</small>";
				}
				break;
			
			case "add":
				$filters = explode('&',str_replace(';','',$this->input->post('filters')));
				foreach ($filters as $filter)
				{
					$filterArray = explode('=',$filter,2);
					$itemSQL .= "{$filterArray[0]},";
					$valueSQL .= '"'.str_replace('+',' ',$filterArray[1]).'",';
					
					if ($filterArray[0] == 'lead_filter_id')
						$lead_filter_id = $filterArray[1];
				}
				$itemSQL = trim($itemSQL,",");
				$valueSQL = trim($valueSQL,",");
				$this->db->query("INSERT INTO vicidial_lead_filters ($itemSQL) VALUES($valueSQL)");
				
				if ($this->db->affected_rows())
				{
					$this->commonhelper->auditadmin('ADD',"Added Lead Filter $lead_filter_id","INSERT INTO vicidial_lead_filters ($itemSQL) VALUES($valueSQL)");
					$return = "Success! Lead filter ID $lead_filter_id added.";
				} else {
					$return = "Error: Lead filter ID $lead_filter_id not added.";
				}
				break;
			
			case "delete":
				$filter_list = explode(",",$this->uri->segment(4));
				foreach ($filter_list as $filter)
				{
					$query = $this->db->query("DELETE FROM vicidial_lead_filters WHERE lead_filter_id='$filter'");
					$this->commonhelper->auditadmin('DELETE',"Deleted Lead Filter $filter","DELETE FROM vicidial_lead_filters WHERE lead_filter_id='$filter'");
				}
				$return = true;
				break;
			
			case "modify":
				$filterid = $this->uri->segment(4);
				$query = $this->db->query("SELECT * FROM vicidial_lead_filters WHERE lead_filter_id='$filterid'");
				$data['filter'] = $query->result();
				
		
				$fields = $this->db->list_fields('vicidial_list');
				$fields_to_filter[''] = "--- SELECT A FIELD ---";
				foreach ($fields as $field)
				{
					if (preg_match("/entry_date|modify_date|gmt_offset_now|phone_number|state|phone_code|called_count/", $field))
					{
						switch ($field)
						{
							case "entry_date":
								$field_name = "Date Uploaded";
								break;
							case "modify_date":
								$field_name = "Date Modified";
								break;
							case "gmt_offset_now":
								$field_name = "Timezone";
								break;
							case "phone_number":
								$field_name = "Area Code";
								break;
							case "state":
								$field_name = "State";
								break;
							case "phone_code":
								$field_name = "Country Code";
								break;
							case "called_count":
								$field_name = "Called Count";
								break;
						}	
						$fields_to_filter[$field] = "$field_name";
					}
				}
				
				$filter_options = $this->go_campaign->go_get_filter_options();
				$data['countrycodes'] = $filter_options['countrycodes'];
				$data['areacodes'] = $filter_options['areacodes'];
				$data['states'] = $filter_options['states'];
				$data['fields_to_filter'] = $fields_to_filter;
				
				$query = $this->db->query("SELECT user_group,group_name FROM vicidial_user_groups $addedSQL");
				$user_groups['---ALL---'] = "--- ALL USER GROUPS ---";
				foreach ($query->result() as $group)
				{
					$user_groups[$group->user_group] = "{$group->user_group} - {$group->group_name}";
				}
				$data['user_groups'] = $user_groups;
				
			        $this->load->view('go_campaign/go_lead_filters',$data);
				break;
			
			case "save":
				$filters = explode('&',str_replace(';','',$this->input->post('filters')));
				foreach ($filters as $filter)
				{
					$filterArray = explode('=',$filter,2);
					if ($filterArray[0] != 'lead_filter_id_mod')
						$itemSQL .= str_replace('_mod','',$filterArray[0]).'="'.str_replace('+',' ',$filterArray[1]).'",';
					
					if ($filterArray[0] == 'lead_filter_id_mod')
						$lead_filter_id = $filterArray[1];
				}
				$itemSQL = trim($itemSQL,",");
				$this->db->query("UPDATE vicidial_lead_filters SET $itemSQL WHERE lead_filter_id='$lead_filter_id'");
				
				if ($this->db->affected_rows())
				{
					$this->commonhelper->auditadmin('MODIFY',"Modified Lead Filter $lead_filter_id","UPDATE vicidial_lead_filters SET $itemSQL WHERE lead_filter_id='$lead_filter_id'");
					$return = "Success! Lead filter ID $lead_filter_id modified.";
				} else {
					$return = "Lead filter ID $lead_filter_id not modified.\nNo data changed.";
				}
				break;
			
			default:
		}
		
		echo $return;
	}

	function go_barged_in($type, $session_id, $phone, $server_ip)
	{
	    $StarTtime = date("U");
	    $NOW_TIME = date("Y-m-d H:i:s");
	    $user = $this->session->userdata('user_name');
	    $query = $this->db->query("SELECT count(*) AS cnt from vicidial_conferences where conf_exten='$session_id' and server_ip='$server_ip'");
	    $session_exist = $query->row()->cnt;

	    if ($session_exist > 0)
	    {
		$query = $this->db->query("SELECT count(*) AS cnt from phones where login='$phone'");
		$phone_exist = $query->row()->cnt;

		if ($phone_exist > 0)
		{
		    $query = $this->db->query("SELECT dialplan_number,server_ip,outbound_cid from phones where login='$phone'");
		    $phoneInfo = $query->row();
		}

		$S='*';
		$D_s_ip = explode('.', $server_ip);
		if (strlen($D_s_ip[0])<2) {$D_s_ip[0] = "0$D_s_ip[0]";}
		if (strlen($D_s_ip[0])<3) {$D_s_ip[0] = "0$D_s_ip[0]";}
		if (strlen($D_s_ip[1])<2) {$D_s_ip[1] = "0$D_s_ip[1]";}
		if (strlen($D_s_ip[1])<3) {$D_s_ip[1] = "0$D_s_ip[1]";}
		if (strlen($D_s_ip[2])<2) {$D_s_ip[2] = "0$D_s_ip[2]";}
		if (strlen($D_s_ip[2])<3) {$D_s_ip[2] = "0$D_s_ip[2]";}
		if (strlen($D_s_ip[3])<2) {$D_s_ip[3] = "0$D_s_ip[3]";}
		if (strlen($D_s_ip[3])<3) {$D_s_ip[3] = "0$D_s_ip[3]";}
		$monitor_dialstring = "$D_s_ip[0]$S$D_s_ip[1]$S$D_s_ip[2]$S$D_s_ip[3]$S";

		$GADuser = sprintf("%08s", $user);
		while (strlen($GADuser) > 8) {$GADuser = substr("$GADuser", 0, -1);}
		$BMquery = "BM$StarTtime$GADuser";
		// LISTEN = MONITOR
		if ( (ereg('LISTEN',$type)) or (strlen($type)<1) ) {$type = '0';}
		if (ereg('BARGE',$type)) {$type = '';}
		if (ereg('HIJACK',$type)) {$type = '';}

		### insert a new lead in the system with this phone number
		$query = $this->db->query("INSERT INTO vicidial_manager values('','','$NOW_TIME','NEW','N','".$phoneInfo->server_ip."','','Originate','$BMquery','Channel: Local/$monitor_dialstring$type$session_id@default','Context; default','Exten: ".$phoneInfo->dialplan_number."','Priority: 1','Callerid: \"VC Blind Monitor\" <".$phoneInfo->outbound_cid.">','','','','','')");

		echo "<script>alert('SUCCESS: Now connected to $session_id \($type\).');</script>";
	    }
	}

	function go_view_hopper_list($camp)
	{
		$query = $this->db->query("SELECT vicidial_hopper.lead_id,phone_number,vicidial_hopper.state,vicidial_list.status,called_count,vicidial_hopper.gmt_offset_now,hopper_id,alt_dial,vicidial_hopper.list_id,vicidial_hopper.priority,vicidial_hopper.source FROM vicidial_hopper,vicidial_list WHERE vicidial_hopper.campaign_id='" . mysql_real_escape_string($camp) . "' AND vicidial_hopper.status='READY' AND vicidial_hopper.lead_id=vicidial_list.lead_id ORDER BY priority DESC,hopper_id LIMIT 5000;");
		$totalHopper = $query->num_rows();
		$i = 1;
		$hopperHTML = "<table id=\"hopperTable\" class=\"tablesorter\" style=\"width:100%;\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" ><thead><tr style=\"font-weight:bold;white-space:nowrap;\"><th>&nbsp;&nbsp;ORDER&nbsp;&nbsp;</th><th>&nbsp;&nbsp;PRIORITY&nbsp;&nbsp;</th><th>&nbsp;&nbsp;LEAD ID&nbsp;&nbsp;</th><th>&nbsp;&nbsp;LIST ID&nbsp;&nbsp;</th><th>&nbsp;&nbsp;PHONE NUMBER&nbsp;&nbsp;</th><th>&nbsp;&nbsp;STATE&nbsp;&nbsp;</th><th>&nbsp;&nbsp;STATUS&nbsp;&nbsp;</th><th>&nbsp;&nbsp;COUNT&nbsp;&nbsp;</th><th>&nbsp;&nbsp;GMT&nbsp;&nbsp;</th><th>&nbsp;&nbsp;ALT&nbsp;&nbsp;</th><th>&nbsp;&nbsp;SOURCE&nbsp;&nbsp;</th></tr></thead><tbody>\n";
		foreach ($query->result() as $row)
		{
			if ($x==0) {
				$bgcolor = "#E0F8E0";
				$x=1;
			} else {
				$bgcolor = "#EFFBEF";
				$x=0;
			}
			$hopperHTML .= "<tr style=\"background-color:$bgcolor;\">";
			$hopperHTML .= "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$i&nbsp;&nbsp;</td>";
			$hopperHTML .= "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->priority}&nbsp;&nbsp;</td>";
			$hopperHTML .= "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->lead_id}&nbsp;&nbsp;</td>";
			$hopperHTML .= "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->list_id}&nbsp;&nbsp;</td>";
			$hopperHTML .= "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->phone_number}&nbsp;&nbsp;</td>";
			$hopperHTML .= "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->state}&nbsp;&nbsp;</td>";
			$hopperHTML .= "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->status}&nbsp;&nbsp;</td>";
			$hopperHTML .= "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->called_count}&nbsp;&nbsp;</td>";
			$hopperHTML .= "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->gmt_offset_now}&nbsp;&nbsp;</td>";
			$hopperHTML .= "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->alt_dial}&nbsp;&nbsp;</td>";
			$hopperHTML .= "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->source}&nbsp;&nbsp;</td>";
			$hopperHTML .= "</tr>\n";
			$i++;
		}
		$hopperHTML .= "</tbody></table>\n";

		$query = $this->db->query("SELECT campaign_name FROM vicidial_campaigns WHERE campaign_id='$camp'");

	    $data['hopperHTML'] = $hopperHTML;
	    $data['totalHopper'] = $totalHopper;
	    $data['campaign_id'] = $camp;
	    $data['campaign_name'] = $query->row()->campaign_name;
	    $this->load->view('go_campaign/go_campaign_view_hopper',$data);
	}
	
	function lookup_gmt($phone_code,$USarea,$state,$LOCAL_GMT_OFF_STD,$Shour,$Smin,$Ssec,$Smon,$Smday,$Syear,$postalgmt,$postal_code,$owner)
	{
	global $link;

	$postalgmt_found=0;
	if ( (eregi("POSTAL",$postalgmt)) && (strlen($postal_code)>4) )
		{
		if (preg_match('/^1$/', $phone_code))
			{
			$stmt="select postal_code,state,GMT_offset,DST,DST_range,country,country_code from vicidial_postal_codes where country_code='$phone_code' and postal_code LIKE \"$postal_code%\";";
//			$rslt=mysql_query($stmt, $link);
//			$pc_recs = mysql_num_rows($rslt);
			$rslt = $this->db->query($stmt);
			$pc_recs = $rslt->num_rows;
			if ($pc_recs > 0)
				{
				$row = $rslt->row();
				$gmt_offset = $row->GMT_offset;
				$gmt_offset = eregi_replace("\+","",$gmt_offset);
				$dst = $row->DST;
				$dst_range = $row->DST_range;
			
				$PC_processed++;
				$postalgmt_found++;
				$post++;
				}
			}
		}
	if ( ($postalgmt=="TZCODE") && (strlen($owner)>1) )
		{
		$dst_range='';
		$dst='N';
		$gmt_offset=0;

		$stmt="select GMT_offset from vicidial_phone_codes where tz_code='$owner' and country_code='$phone_code' limit 1;";

//		$rslt=mysql_query($stmt, $link);
//		$pc_recs = mysql_num_rows($rslt);

                $rslt = $this->db->query($stmt);
                $pc_recs = $rslt->num_rows;

		if ($pc_recs > 0)
			{
			$row = $rslt->row();
			$gmt_offset =	$row->GMT_offset;	 
			$gmt_offset = eregi_replace("\+","",$gmt_offset);
			$PC_processed++;
			$postalgmt_found++;
			$post++;
			}

		$stmt = "select distinct DST_range from vicidial_phone_codes where tz_code='$owner' and country_code='$phone_code' order by DST_range desc limit 1;";
		$rslt = $this->db->query($stmt);
                $pc_recs = $rslt->num_rows;
		
		//$rslt=mysql_query($stmt, $link);
		//$pc_recs = mysql_num_rows($rslt);
		if ($pc_recs > 0)
			{
			$row = $rslt->row();
			//$dst_range =	$row[0];
			$dst_range =	$row->DST_range;
			if (strlen($dst_range)>2) {$dst = 'Y';}
			}
		}

	if ($postalgmt_found < 1)
		{
		$PC_processed=0;
		### UNITED STATES ###
		if ($phone_code =='1')
			{
			$stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code' and areacode='$USarea';";
		//	$rslt=mysql_query($stmt, $link);
		//	$pc_recs = mysql_num_rows($rslt);
		$rslt = $this->db->query($stmt);
                $pc_recs = $rslt->num_rows;
			if ($pc_recs > 0)
				{
			$row = $rslt->row();
				$gmt_offset =	$row->GMT_offset;	 $gmt_offset = eregi_replace("\+","",$gmt_offset);
				$dst =			$row->DST;
				$dst_range =	$row->DST_range;
				$PC_processed++;
				}
			}
		### MEXICO ###
		if ($phone_code =='52')
			{
			$stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code' and areacode='$USarea';";
		/*	$rslt=mysql_query($stmt, $link);
			$pc_recs = mysql_num_rows($rslt);*/
		$rslt = $this->db->query($stmt);
                $pc_recs = $rslt->num_rows;
			if ($pc_recs > 0)
				{
			$row = $rslt->row();
				$gmt_offset =	$row->GMT_offset;	 $gmt_offset = eregi_replace("\+","",$gmt_offset);
				$dst =			$row->DST;
				$dst_range =	$row->DST_range;
				$PC_processed++;
				}
			}
		### AUSTRALIA ###
		if ($phone_code =='61')
			{
			$stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code' and state='$state';";
		//	$rslt=mysql_query($stmt, $link);
		//	$pc_recs = mysql_num_rows($rslt);
		$rslt = $this->db->query($stmt);
                $pc_recs = $rslt->num_rows;
			if ($pc_recs > 0)
				{
			$row = $rslt->row();
				$gmt_offset =	$row->GMT_offset;	 $gmt_offset = eregi_replace("\+","",$gmt_offset);
				$dst =			$row->DST;
				$dst_range =	$row->DST_range;
				$PC_processed++;
				}
			}
		### ALL OTHER COUNTRY CODES ###
		if (!$PC_processed)
			{
			$PC_processed++;
			$stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code';";
		//	$rslt=mysql_query($stmt, $link);
		//	$pc_recs = mysql_num_rows($rslt);
		$rslt = $this->db->query($stmt);
                $pc_recs = $rslt->num_rows;
			if ($pc_recs > 0)
				{
			$row = $rslt->row();
				$gmt_offset =	$row->GMT_offset;	 $gmt_offset = eregi_replace("\+","",$gmt_offset);
				$dst =			$row->DST;
				$dst_range =	$row->DST_range;
				$PC_processed++;
				}
			}
		}

	### Find out if DST to raise the gmt offset ###
	$AC_GMT_diff = ($gmt_offset - $LOCAL_GMT_OFF_STD);
	$AC_localtime = mktime(($Shour + $AC_GMT_diff), $Smin, $Ssec, $Smon, $Smday, $Syear);
		$hour = date("H",$AC_localtime);
		$min = date("i",$AC_localtime);
		$sec = date("s",$AC_localtime);
		$mon = date("m",$AC_localtime);
		$mday = date("d",$AC_localtime);
		$wday = date("w",$AC_localtime);
		$year = date("Y",$AC_localtime);
	$dsec = ( ( ($hour * 3600) + ($min * 60) ) + $sec );

	$AC_processed=0;
	if ( (!$AC_processed) and ($dst_range == 'SSM-FSN') )
		{
		if ($DBX) {print "     Second Sunday March to First Sunday November\n";}
		#**********************************************************************
		# SSM-FSN
		#     This is returns 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on Second Sunday March to First Sunday November at 2 am.
		#     INPUTS:
		#       mm              INTEGER       Month.
		#       dd              INTEGER       Day of the month.
		#       ns              INTEGER       Seconds into the day.
		#       dow             INTEGER       Day of week (0=Sunday, to 6=Saturday)
		#     OPTIONAL INPUT:
		#       timezone        INTEGER       hour difference UTC - local standard time
		#                                      (DEFAULT is blank)
		#                                     make calculations based on UTC time, 
		#                                     which means shift at 10:00 UTC in April
		#                                     and 9:00 UTC in October
		#     OUTPUT: 
		#                       INTEGER       1 = DST, 0 = not DST
		#
		# S  M  T  W  T  F  S
		# 1  2  3  4  5  6  7
		# 8  9 10 11 12 13 14
		#15 16 17 18 19 20 21
		#22 23 24 25 26 27 28
		#29 30 31
		# 
		# S  M  T  W  T  F  S
		#    1  2  3  4  5  6
		# 7  8  9 10 11 12 13
		#14 15 16 17 18 19 20
		#21 22 23 24 25 26 27
		#28 29 30 31
		# 
		#**********************************************************************

			$USACAN_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 3 || $mm > 11) {
			$USACAN_DST=0;   
			} elseif ($mm >= 4 and $mm <= 10) {
			$USACAN_DST=1;   
			} elseif ($mm == 3) {
			if ($dd > 13) {
				$USACAN_DST=1;   
			} elseif ($dd >= ($dow+8)) {
				if ($timezone) {
				if ($dow == 0 and $ns < (7200+$timezone*3600)) {
					$USACAN_DST=0;   
				} else {
					$USACAN_DST=1;   
				}
				} else {
				if ($dow == 0 and $ns < 7200) {
					$USACAN_DST=0;   
				} else {
					$USACAN_DST=1;   
				}
				}
			} else {
				$USACAN_DST=0;   
			}
			} elseif ($mm == 11) {
			if ($dd > 7) {
				$USACAN_DST=0;   
			} elseif ($dd < ($dow+1)) {
				$USACAN_DST=1;   
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (7200+($timezone-1)*3600)) {
					$USACAN_DST=1;   
				} else {
					$USACAN_DST=0;   
				}
				} else { # local time calculations
				if ($ns < 7200) {
					$USACAN_DST=1;   
				} else {
					$USACAN_DST=0;   
				}
				}
			} else {
				$USACAN_DST=0;   
			}
			} # end of month checks
		if ($DBX) {print "     DST: $USACAN_DST\n";}
		if ($USACAN_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'FSA-LSO') )
		{
		if ($DBX) {print "     First Sunday April to Last Sunday October\n";}
		#**********************************************************************
		# FSA-LSO
		#     This is returns 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on first Sunday in April and last Sunday in October at 2 am.
		#**********************************************************************
			
			$USA_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 4 || $mm > 10) {
			$USA_DST=0;
			} elseif ($mm >= 5 and $mm <= 9) {
			$USA_DST=1;
			} elseif ($mm == 4) {
			if ($dd > 7) {
				$USA_DST=1;
			} elseif ($dd >= ($dow+1)) {
				if ($timezone) {
				if ($dow == 0 and $ns < (7200+$timezone*3600)) {
					$USA_DST=0;
				} else {
					$USA_DST=1;
				}
				} else {
				if ($dow == 0 and $ns < 7200) {
					$USA_DST=0;
				} else {
					$USA_DST=1;
				}
				}
			} else {
				$USA_DST=0;
			}
			} elseif ($mm == 10) {
			if ($dd < 25) {
				$USA_DST=1;
			} elseif ($dd < ($dow+25)) {
				$USA_DST=1;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (7200+($timezone-1)*3600)) {
					$USA_DST=1;
				} else {
					$USA_DST=0;
				}
				} else { # local time calculations
				if ($ns < 7200) {
					$USA_DST=1;
				} else {
					$USA_DST=0;
				}
				}
			} else {
				$USA_DST=0;
			}
			} # end of month checks

		if ($DBX) {print "     DST: $USA_DST\n";}
		if ($USA_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'LSM-LSO') )
		{
		if ($DBX) {print "     Last Sunday March to Last Sunday October\n";}
		#**********************************************************************
		#     This is s 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on last Sunday in March and last Sunday in October at 1 am.
		#**********************************************************************
			
			$GBR_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 3 || $mm > 10) {
			$GBR_DST=0;
			} elseif ($mm >= 4 and $mm <= 9) {
			$GBR_DST=1;
			} elseif ($mm == 3) {
			if ($dd < 25) {
				$GBR_DST=0;
			} elseif ($dd < ($dow+25)) {
				$GBR_DST=0;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$GBR_DST=0;
				} else {
					$GBR_DST=1;
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$GBR_DST=0;
				} else {
					$GBR_DST=1;
				}
				}
			} else {
				$GBR_DST=1;
			}
			} elseif ($mm == 10) {
			if ($dd < 25) {
				$GBR_DST=1;
			} elseif ($dd < ($dow+25)) {
				$GBR_DST=1;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$GBR_DST=1;
				} else {
					$GBR_DST=0;
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$GBR_DST=1;
				} else {
					$GBR_DST=0;
				}
				}
			} else {
				$GBR_DST=0;
			}
			} # end of month checks
			if ($DBX) {print "     DST: $GBR_DST\n";}
		if ($GBR_DST) {$gmt_offset++;}
		$AC_processed++;
		}
	if ( (!$AC_processed) and ($dst_range == 'LSO-LSM') )
		{
		if ($DBX) {print "     Last Sunday October to Last Sunday March\n";}
		#**********************************************************************
		#     This is s 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on last Sunday in October and last Sunday in March at 1 am.
		#**********************************************************************
			
			$AUS_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 3 || $mm > 10) {
			$AUS_DST=1;
			} elseif ($mm >= 4 and $mm <= 9) {
			$AUS_DST=0;
			} elseif ($mm == 3) {
			if ($dd < 25) {
				$AUS_DST=1;
			} elseif ($dd < ($dow+25)) {
				$AUS_DST=1;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$AUS_DST=1;
				} else {
					$AUS_DST=0;
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$AUS_DST=1;
				} else {
					$AUS_DST=0;
				}
				}
			} else {
				$AUS_DST=0;
			}
			} elseif ($mm == 10) {
			if ($dd < 25) {
				$AUS_DST=0;
			} elseif ($dd < ($dow+25)) {
				$AUS_DST=0;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$AUS_DST=0;
				} else {
					$AUS_DST=1;
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$AUS_DST=0;
				} else {
					$AUS_DST=1;
				}
				}
			} else {
				$AUS_DST=1;
			}
			} # end of month checks						
		if ($DBX) {print "     DST: $AUS_DST\n";}
		if ($AUS_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'FSO-LSM') )
		{
		if ($DBX) {print "     First Sunday October to Last Sunday March\n";}
		#**********************************************************************
		#   TASMANIA ONLY
		#     This is s 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on first Sunday in October and last Sunday in March at 1 am.
		#**********************************************************************
			
			$AUST_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 3 || $mm > 10) {
			$AUST_DST=1;
			} elseif ($mm >= 4 and $mm <= 9) {
			$AUST_DST=0;
			} elseif ($mm == 3) {
			if ($dd < 25) {
				$AUST_DST=1;
			} elseif ($dd < ($dow+25)) {
				$AUST_DST=1;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$AUST_DST=1;
				} else {
					$AUST_DST=0;
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$AUST_DST=1;
				} else {
					$AUST_DST=0;
				}
				}
			} else {
				$AUST_DST=0;
			}
			} elseif ($mm == 10) {
			if ($dd > 7) {
				$AUST_DST=1;
			} elseif ($dd >= ($dow+1)) {
				if ($timezone) {
				if ($dow == 0 and $ns < (7200+$timezone*3600)) {
					$AUST_DST=0;
				} else {
					$AUST_DST=1;
				}
				} else {
				if ($dow == 0 and $ns < 3600) {
					$AUST_DST=0;
				} else {
					$AUST_DST=1;
				}
				}
			} else {
				$AUST_DST=0;
			}
			} # end of month checks						
		if ($DBX) {print "     DST: $AUST_DST\n";}
		if ($AUST_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'FSO-FSA') )
		{
		if ($DBX) {print "     Sunday in October to First Sunday in April\n";}
		#**********************************************************************
		# FSO-FSA
		#   2008+ AUSTRALIA ONLY (country code 61)
		#     This is returns 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on first Sunday in October and first Sunday in April at 1 am.
		#**********************************************************************
		
		$AUSE_DST=0;
		$mm = $mon;
		$dd = $mday;
		$ns = $dsec;
		$dow= $wday;

		if ($mm < 4 or $mm > 10) {
		$AUSE_DST=1;   
		} elseif ($mm >= 5 and $mm <= 9) {
		$AUSE_DST=0;   
		} elseif ($mm == 4) {
		if ($dd > 7) {
			$AUSE_DST=0;   
		} elseif ($dd >= ($dow+1)) {
			if ($timezone) {
			if ($dow == 0 and $ns < (3600+$timezone*3600)) {
				$AUSE_DST=1;   
			} else {
				$AUSE_DST=0;   
			}
			} else {
			if ($dow == 0 and $ns < 7200) {
				$AUSE_DST=1;   
			} else {
				$AUSE_DST=0;   
			}
			}
		} else {
			$AUSE_DST=1;   
		}
		} elseif ($mm == 10) {
		if ($dd >= 8) {
			$AUSE_DST=1;   
		} elseif ($dd >= ($dow+1)) {
			if ($timezone) {
			if ($dow == 0 and $ns < (7200+$timezone*3600)) {
				$AUSE_DST=0;   
			} else {
				$AUSE_DST=1;   
			}
			} else {
			if ($dow == 0 and $ns < 3600) {
				$AUSE_DST=0;   
			} else {
				$AUSE_DST=1;   
			}
			}
		} else {
			$AUSE_DST=0;   
		}
		} # end of month checks
		if ($DBX) {print "     DST: $AUSE_DST\n";}
		if ($AUSE_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'FSO-TSM') )
		{
		if ($DBX) {print "     First Sunday October to Third Sunday March\n";}
		#**********************************************************************
		#     This is s 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on first Sunday in October and third Sunday in March at 1 am.
		#**********************************************************************
			
			$NZL_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 3 || $mm > 10) {
			$NZL_DST=1;
			} elseif ($mm >= 4 and $mm <= 9) {
			$NZL_DST=0;
			} elseif ($mm == 3) {
			if ($dd < 14) {
				$NZL_DST=1;
			} elseif ($dd < ($dow+14)) {
				$NZL_DST=1;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$NZL_DST=1;
				} else {
					$NZL_DST=0;
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$NZL_DST=1;
				} else {
					$NZL_DST=0;
				}
				}
			} else {
				$NZL_DST=0;
			}
			} elseif ($mm == 10) {
			if ($dd > 7) {
				$NZL_DST=1;
			} elseif ($dd >= ($dow+1)) {
				if ($timezone) {
				if ($dow == 0 and $ns < (7200+$timezone*3600)) {
					$NZL_DST=0;
				} else {
					$NZL_DST=1;
				}
				} else {
				if ($dow == 0 and $ns < 3600) {
					$NZL_DST=0;
				} else {
					$NZL_DST=1;
				}
				}
			} else {
				$NZL_DST=0;
			}
			} # end of month checks						
		if ($DBX) {print "     DST: $NZL_DST\n";}
		if ($NZL_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'LSS-FSA') )
		{
		if ($DBX) {print "     Last Sunday in September to First Sunday in April\n";}
		#**********************************************************************
		# LSS-FSA
		#   2007+ NEW ZEALAND (country code 64)
		#     This is returns 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on last Sunday in September and first Sunday in April at 1 am.
		#**********************************************************************
		
		$NZLN_DST=0;
		$mm = $mon;
		$dd = $mday;
		$ns = $dsec;
		$dow= $wday;

		if ($mm < 4 || $mm > 9) {
		$NZLN_DST=1;   
		} elseif ($mm >= 5 && $mm <= 9) {
		$NZLN_DST=0;   
		} elseif ($mm == 4) {
		if ($dd > 7) {
			$NZLN_DST=0;   
		} elseif ($dd >= ($dow+1)) {
			if ($timezone) {
			if ($dow == 0 && $ns < (3600+$timezone*3600)) {
				$NZLN_DST=1;   
			} else {
				$NZLN_DST=0;   
			}
			} else {
			if ($dow == 0 && $ns < 7200) {
				$NZLN_DST=1;   
			} else {
				$NZLN_DST=0;   
			}
			}
		} else {
			$NZLN_DST=1;   
		}
		} elseif ($mm == 9) {
		if ($dd < 25) {
			$NZLN_DST=0;   
		} elseif ($dd < ($dow+25)) {
			$NZLN_DST=0;   
		} elseif ($dow == 0) {
			if ($timezone) { # UTC calculations
			if ($ns < (3600+($timezone-1)*3600)) {
				$NZLN_DST=0;   
			} else {
				$NZLN_DST=1;   
			}
			} else { # local time calculations
			if ($ns < 3600) {
				$NZLN_DST=0;   
			} else {
				$NZLN_DST=1;   
			}
			}
		} else {
			$NZLN_DST=1;   
		}
		} # end of month checks
		if ($DBX) {print "     DST: $NZLN_DST\n";}
		if ($NZLN_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'TSO-LSF') )
		{
		if ($DBX) {print "     Third Sunday October to Last Sunday February\n";}
		#**********************************************************************
		# TSO-LSF
		#     This is returns 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect. Brazil
		#     Based on Third Sunday October to Last Sunday February at 1 am.
		#**********************************************************************
			
			$BZL_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 2 || $mm > 10) {
			$BZL_DST=1;   
			} elseif ($mm >= 3 and $mm <= 9) {
			$BZL_DST=0;   
			} elseif ($mm == 2) {
			if ($dd < 22) {
				$BZL_DST=1;   
			} elseif ($dd < ($dow+22)) {
				$BZL_DST=1;   
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$BZL_DST=1;   
				} else {
					$BZL_DST=0;   
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$BZL_DST=1;   
				} else {
					$BZL_DST=0;   
				}
				}
			} else {
				$BZL_DST=0;   
			}
			} elseif ($mm == 10) {
			if ($dd < 22) {
				$BZL_DST=0;   
			} elseif ($dd < ($dow+22)) {
				$BZL_DST=0;   
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$BZL_DST=0;   
				} else {
					$BZL_DST=1;   
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$BZL_DST=0;   
				} else {
					$BZL_DST=1;   
				}
				}
			} else {
				$BZL_DST=1;   
			}
			} # end of month checks
		if ($DBX) {print "     DST: $BZL_DST\n";}
		if ($BZL_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if (!$AC_processed)
		{
		if ($DBX) {print "     No DST Method Found\n";}
		if ($DBX) {print "     DST: 0\n";}
		$AC_processed++;
		}

	return $gmt_offset;
	}
}
