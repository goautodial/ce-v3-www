<?php
########################################################################################################
####  Name:             	go_servers_ce.php                                                   ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Christopher Lomuntad				            	    ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_servers_ce extends Controller {
    var $userLevel;
    function __construct()
	{
		parent::Controller();
		$this->load->model(array('go_auth','go_dashboard','go_servers'));
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
		$data['bannertitle'] = $this->lang->line('go_server_banner');
		$data['sys']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);


		$data['userfulname'] = $this->go_servers->go_get_userfulname();
		
		$data['servers'] = $this->go_servers->go_get_server_list();

        $data['go_main_content'] = 'go_settings/go_servers';
        $this->load->view('includes/go_dashboard_template',$data);
	}
	
	function go_get_server()
	{
		$type = $this->uri->segment(3);
		$serverid = $this->uri->segment(4);
		$serverip = $this->uri->segment(5);
		
		switch($type)
		{
			case "modify":
				break;
			
			default:
				$query = $this->db->query("SELECT * FROM servers WHERE server_id='$serverid' AND server_ip='$serverip';");
				$data['server_info'] = $query->row();
				
				$query = $this->db->query("SELECT carrier_id,carrier_name,registration_string,active FROM vicidial_server_carriers WHERE server_ip='$serverip' ORDER BY carrier_id");
				$data['server_carriers'] = $query->result();
				
				$query = $this->db->query("SELECT extension,fullname,active FROM phones WHERE server_ip='$serverip' ORDER BY extension");
				$data['server_phones'] = $query->result();
				
				$query = $this->db->query("SELECT conf_exten,extension FROM vicidial_conferences WHERE server_ip='$serverip' ORDER BY conf_exten");
				$data['server_conferences'] = $query->result();
				
				$this->load->model('go_campaign');
				$allowed_campaigns = $this->go_campaign->go_get_allowed_campaigns();
				
				$allowed_campaigns = str_replace(",","','",$allowed_campaigns);
				$allowed_campaignsSQL = '';
				if (!preg_match("/ALLCAMPAIGNS/",$allowed_campaigns))
				{
					$Aallowed_campaignsSQL = "AND campaign_id IN ('$allowed_campaigns')";
					$Wallowed_campaignsSQL = "WHERE campaign_id IN ('$allowed_campaigns')";
				}
				$query = $this->db->query("SELECT campaign_id,campaign_name FROM vicidial_campaigns $Wallowed_campaignsSQL ORDER BY campaign_id");
				$data['allowed_campaigns'] = $query->result();
				
				$query = $this->db->query("SELECT server_ip,campaign_id,dedicated_trunks,trunk_restriction FROM vicidial_server_trunks WHERE server_ip='$serverip' $Aallowed_campaignsSQL ORDER BY campaign_id;");
				$data['server_trunks'] = $query->result();
				break;
		}
		
		$data['templates'] = $this->go_servers->go_list_templates();
		
		$data['type'] = $type;
        $this->load->view('go_settings/go_server_view',$data);
	}
	
	function go_server_wizard()
	{
		$action = $this->input->post("action");
		if (strlen($action) < 1)
		{
			$data['user_groups'] = $this->go_servers->go_get_usergroups();
			$data['system_settings'] = $this->go_servers->go_get_systemsettings();
			
	        $this->load->view('go_settings/go_server_wizard',$data);
		} else {
			if ($action == "add_new_server")
			{
				$items = explode("&",str_replace(";","",$this->input->post("items")));
				foreach ($items as $item)
				{
					list($var,$val) = explode("=",$item,2);
					if (strlen($val) > 0)
					{
						$varSQL .= "$var,";
						$valSQL .= "'".str_replace('+',' ',mysql_real_escape_string($val))."',";
						
						if ($var=="server_id")
							$server_id="$val";
						
						if ($var=="server_ip")
							$server_ip="$val";
					}
				}
				$varSQL = rtrim($varSQL,",");
				$valSQL = rtrim($valSQL,",");
				$itemSQL = "($varSQL) VALUES ($valSQL)";
				$query = $this->db->query("INSERT INTO servers $itemSQL;");
				
				if ($this->db->affected_rows())
				{
					$this->commonhelper->auditadmin('ADD',"Added New Server $server_id ($server_ip)","INSERT INTO servers $itemSQL;");
					$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
					$return = "SUCCESS";
				}
			}
			
			if ($action == "delete_server")
			{
				$serverid = $this->input->post("serverid");
				$serverip = $this->input->post("serverip");
				$query = $this->db->query("DELETE FROM servers WHERE server_id = '$serverid' AND server_ip = '$serverip'");
				
				$this->commonhelper->auditadmin('DELETE',"Deleted Server $serverid ($serverip)","DELETE FROM servers WHERE server_id = '$serverid' AND server_ip = '$serverip'");
				//$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
				$return = "DELETED";
			}
			
			if ($action == "modify_server")
			{
				$items = explode("&",str_replace(";","",$this->input->post("items")));
				foreach ($items as $item)
				{
					list($var,$val) = explode("=",$item,2);
					//if (strlen($val) > 0)
					//{
						if ($var!="server_id" && $var!="vicidial_balance_offlimits")
							$itemSQL .= "$var='".str_replace('+',' ',mysql_real_escape_string($val))."', ";
						
						if ($var=="server_id")
							$server_id="$val";
						
						if ($var=="server_ip")
							$server_ip="$val";
					//}
				}
				$itemSQL = rtrim($itemSQL,', ');
				$query = $this->db->query("UPDATE servers SET $itemSQL WHERE server_id='$server_id';");
				//echo "UPDATE phones SET $itemSQL WHERE extension='$extension';";
				
				if ($this->db->affected_rows())
				{
					$this->commonhelper->auditadmin('MODIFY',"Modified Server $server_id ($server_ip)","UPDATE servers SET $itemSQL WHERE server_id='$server_id';");
					$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
					$return = "SUCCESS";
				}
			}
			
			echo $return;
		}
	}
	
	function go_check_server()
	{
		$serverid = $this->uri->segment(3);
		$serverip = $this->uri->segment(4);
		if (strlen($serverip) > 0)
			$serverSQL = "AND server_ip = '$serverip'";
		$query = $this->db->query("SELECT * FROM servers WHERE server_id = '$serverid' $serverSQL");
		$return = $query->num_rows();
		
		if ($return)
		{
			$return = "<small style=\"color:red;\">{$this->lang->line('go_not_available')}.</small>";
		} else {
			$return = "<small style=\"color:green;\">{$this->lang->line('go_available')}.</small>";
		}
		
		echo $return;
	}
	
	function go_update_server_list()
	{
		$action = $this->uri->segment(3);
		$servers = str_replace(',',"','",$this->uri->segment(4));

		switch($action)
		{
			case "activate":
				$query = $this->db->query("UPDATE servers SET active='Y' WHERE server_id IN ('$servers')");
				$this->commonhelper->auditadmin('ACTIVE','Activated Server(s): '.$this->uri->segment(4),"UPDATE servers SET active='Y' WHERE server_id IN ('$servers')");
				break;
			case "deactivate":
				$query = $this->db->query("UPDATE servers SET active='N' WHERE server_id IN ('$servers')");
				$this->commonhelper->auditadmin('INACTIVE','Deactivated Server(s): '.$this->uri->segment(4),"UPDATE servers SET active='N' WHERE server_id IN ('$servers')");
				break;
			case "delete":
				foreach (explode("','",$servers) AS $server)
				{
					$query = $this->db->query("DELETE FROM servers WHERE server_id = '$server'");
					$db_query .= "DELETE FROM servers WHERE server_id = '$server'; ";
				}
				
				$this->commonhelper->auditadmin('DELETE',"Deleted Server(s): ".$this->uri->segment(4),"$db_query");
				//$query = $this->db->query("UPDATE servers SET rebuild_conf_files='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y' and server_ip='$server_ip';");
				break;
		}
		
		$result = $this->go_servers->go_get_server_list();
		$data['servers'] = $result['list'];
		$data['pagelinks'] = $result['pagelinks'];
		$this->load->view('go_settings/go_servers_list',$data);
	}
	
	function go_system_load()
	{
		$serverid = $this->uri->segment(3);
		$serverip = $this->uri->segment(4);
		
		$query = $this->db->query("SELECT sysload,cpu_idle_percent FROM servers WHERE server_id='$serverid' AND server_ip='$serverip';");
		$server = $query->row();
		
		$cpu = (100 - $server->cpu_idle_percent);
		echo "{$server->sysload} - $cpu%";
	}
	
	function go_live_channels()
	{
		$serverid = $this->uri->segment(3);
		$serverip = $this->uri->segment(4);
		
		$query = $this->db->query("SELECT channels_total FROM servers WHERE server_id='$serverid' AND server_ip='$serverip';");
		$server = $query->row();

		echo $server->channels_total;
	}
	
	function go_disk_usage()
	{
		$serverid = $this->uri->segment(3);
		$serverip = $this->uri->segment(4);
		
		$query = $this->db->query("SELECT disk_usage FROM servers WHERE server_id='$serverid' AND server_ip='$serverip';");
		$server = $query->row();

		$disk_usage = preg_replace("/ /"," - ",$server->disk_usage);
		$disk_usage = preg_replace("/\|/","% &nbsp; &nbsp; ",$disk_usage);
		echo $disk_usage;
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
