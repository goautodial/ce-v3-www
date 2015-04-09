<?php
########################################################################################################
####  Name:             	go_servers.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_servers extends Model {

	function __construct()
	{
	    parent::Model();
	    $this->db = $this->load->database('dialerdb', true);
	    //$this->a2db = $this->load->database('billingdb', true);
	}

	function go_get_server_list()
	{
		if ($this->uri->segment(3) == 'search') {
			if (!is_null($this->uri->segment(5)) && strlen($this->uri->segment(5)) > 2) {
				$search = $this->uri->segment(5);
				$addedSQL = "WHERE server_id RLIKE '$search' OR server_description RLIKE '$search' OR server_ip RLIKE '$search'";
			}
		}

		$query	= $this->db->query("SELECT count(*) AS cnt FROM servers $addedSQL;");
		$total	= $query->row()->cnt;
		$limit 	= 5;
		$page	= $this->uri->segment(4);
		$rp	= ($page=='ALL') ? $total : 25;
		if (is_null($page) || $page < 1)
			$page = 1;
		$start	= (($page-1) * $rp);

		$return['pagelinks'] = $this->pagelinks($page,$rp,$total,$limit);
		
		$query = $this->db->query("SELECT server_id,server_description,server_ip,active,asterisk_version,max_vicidial_trunks,local_gmt,user_group FROM servers $addedSQL ORDER BY server_id LIMIT $start,$rp;");
		$return['list'] = $query->result();

		return $return;
	}

        function get_servers_unorder(){
		$query = $this->db->query("SELECT server_id,server_description,server_ip,active,asterisk_version,max_vicidial_trunks,local_gmt,user_group FROM servers WHERE server_description NOT LIKE '%FTP%' AND server_description NOT LIKE '%RECORDING%' AND active = 'Y';");
		$return = $query->result();
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
			$pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top:10px;">';
                        $pagelinks .= '<a title="'.$this->lang->line('go_to_1').'" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['first'].')"><span><img src="'.base_url().'/img/first.gif"></span></a>';
                        $pagelinks .= '<a title="'.$this->lang->line('go_to_prev_p').'" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['prev'].')"><span><img src="'.base_url().'/img/prev.gif"></span></a>';

                        for ($i=$pg['start'];$i<=$pg['end'];$i++) {
                           if ($i==$pg['page']) $current = 'color: #F00;cursor: default;'; else $current="";

                        $pagelinks .= '<a title="'.$this->lang->line('go_to_page').' '.$i.'" style="vertical-align:text-top;padding: 0px 2px;'.$current.'" onclick="changePage('.$i.')"><span>'.$i.'</span></a>';

                        }

                        $pagelinks .= '<a title="'.$this->lang->line('go_to_view_all').'" style="vertical-align:text-top;padding: 0px 2px;" onclick="changePage(\'ALL\')"><span>'.strtoupper($this->lang->line('go_all')).'</span></a>';
                        $pagelinks .= '<a title="'.$this->lang->line('go_to_next').'" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['next'].')"><span><img src="'.base_url().'/img/next.gif"></span></a>';
                        $pagelinks .= '<a title="'.$this->lang->line('go_to_last').'" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['last'].')"><span><img src="'.base_url().'/img/last.gif"></span></a>';
			$pagelinks .= '</div>';
		} else {
			if ($rp > 25) {
				$pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top:10px;">';
                                $pagelinks .= '<a title="'.$this->lang->line('go_to_back_pag').'" style="vertical-align:text-top;padding: 0px 2px;" onclick="changePage(1)"><span> '.$this->lang->line("go_back").'</span></a>';
				$pagelinks .= '</div>';
			} else {
				$pagelinks = "";
			}
		}
		
                $pageinfo = "<span style='float:right;padding-top:10px;'>{$this->lang->line('go_displaying')} {$pg['istart']} {$this->lang->line('go_to')} {$pg['iend']} {$this->lang->line('go_of')} {$pg['total']} {$this->lang->line('go_servers_s')}</span>";
		
		$return['links'] = $pagelinks;
		$return['info'] = $pageinfo;
		
		return $return;
	}

}
