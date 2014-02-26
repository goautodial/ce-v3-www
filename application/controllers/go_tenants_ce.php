<?php
####################################################################################################
####  Name:             	go_tenants_ce.php                                                   ####
####  Type:             	ci controller - administrator                                       ####
####  Version:          	3.0                                                                 ####
####  Build:            	1375243200                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####      	                <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_tenants_ce extends Controller {
	var $userLevel;

    function __construct()
	{
		parent::Controller();
		$this->load->model(array('go_auth','go_usergroups','go_dashboard','go_campaign','go_servers','gouser','go_access','go_tenants'));
		$this->load->library(array('session','pagination','commonhelper','userhelper'));
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
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

		$data['theme'] = $this->session->userdata('go_theme');
		$data['bannertitle'] = $this->lang->line('go_tenants_banner');
		$data['sys']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);

		$data['userfulname'] = $this->go_dashboard->go_get_userfulname();

        $data['go_main_content'] = 'go_settings/go_tenants';
        $this->load->view('includes/go_dashboard_template',$data);
	}
	
	function go_update_tenants_list()
	{
		$action = $this->uri->segment(3);
		$groups = str_replace(',',"','",$this->uri->segment(4));

		switch($action)
		{
			case "activate":
				foreach (explode("','",$groups) AS $group)
				{
					$this->go_tenants->go_update_tenant($group,$action);
				}
				$this->commonhelper->auditadmin('ACTIVE','Activated Tenant(s): '.$this->uri->segment(4));
				break;
			case "deactivate":
				foreach (explode("','",$groups) AS $group)
				{
					$this->go_tenants->go_update_tenant($group,$action);
				}
				$this->commonhelper->auditadmin('INACTIVE','Deactivated Tenant(s): '.$this->uri->segment(4));
				break;
			case "delete":
				foreach (explode("','",$groups) AS $group)
				{
					$return = $this->go_tenants->go_delete_tenant($group);
				}
				
				break;
		}
		
		$data['tenants'] = $this->go_tenants->go_get_usergroup_list();
		$this->load->view('go_settings/go_tenants_list',$data);
	}
	
	function go_get_tenant()
	{
		$action = $this->uri->segment(3);
		$tenantid = $this->uri->segment(4);
		switch($action)
		{
			case "view":
				$data['tenant_info'] = $this->go_tenants->go_get_tenant_info();
				$data['admin_list'] = $this->go_tenants->go_get_admin_list();
				$data['agent_list'] = $this->go_tenants->go_get_agent_list($tenantid);
				$data['camp_list'] = $this->go_tenants->go_get_campaign_list($tenantid);
				$data['list_ids'] = $this->go_tenants->go_get_list_ids($tenantid);
				$data['phones'] = $this->go_tenants->go_get_phones($tenantid);
				
				$this->load->view('go_settings/go_tenant_view',$data);
				break;
		}
	}
	
	function go_tenants_wizard()
	{
		$action = $this->input->post("action");
		if (strlen($action) < 1)
		{
            do
			{
				$randNum = rand(1000000000,9999999999);
				$query = $this->db->query("SELECT count(*) AS cnt FROM vicidial_user_groups WHERE user_group='$randNum'");
			} while ($query->row()->cnt > 0);
			
			$query = $this->go_tenants->godb->query("SELECT user_group FROM user_access_group ORDER BY user_group");
			foreach ($query->result() as $group)
			{
				$group_template[$group->user_group] = $group->user_group;
			}
	
			$data['server_list'] = $this->go_servers->go_get_server_list();
			$data['group_template'] = $group_template;
			$data['tenant_id'] = $randNum;
	        $this->load->view('go_settings/go_tenants_wizard',$data);
		} else {
			switch ($action)
			{
				case "add_new_tenant":
					$return = $this->go_tenants->go_add_new_tenant();
					break;
				
				case "delete_tenant":
					$tenantid = $this->input->post("tenantid");
					$return = $this->go_tenants->go_delete_tenant($tenantid);
					break;
				
				case "modify_tenant":
					$return = $this->go_tenants->go_modify_tenant();
					break;
			}
			
			echo $return;
		}
	}
	
	function go_check_tenant()
	{
		$tenant_admin = $this->uri->segment(3);
		
		$query = $this->db->query("SELECT * FROM vicidial_users WHERE user='$tenant_admin'");
		$return = $query->num_rows();
		//var_dump("SELECT * FROM vicidial_server_carriers WHERE $carrierSQL");
		if ($return && !$isSelf)
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
}