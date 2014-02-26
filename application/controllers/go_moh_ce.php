<?php
########################################################################################################
####  Name:             	go_moh_ce.php                                                       ####
####  Type:             	ci controller - administrator                                       ####
####  Version:          	3.0                                                                 ####
####  Build:            	1375243200                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####      	                <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_moh_ce extends Controller {
	var $userLevel;

	function __construct()
	{
		parent::Controller();
		$this->load->model(array('go_auth','go_access','go_dashboard','go_moh'));
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
		//var_dump($_SERVER);
		$data['cssloader'] = 'go_dashboard_cssloader.php';
		$data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
		$data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

		$data['theme'] = $this->session->userdata('go_theme');
		$data['bannertitle'] = $this->lang->line('go_moh_banner');
		$data['sys']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);

		$data['userfulname'] = $this->go_dashboard->go_get_userfulname();

		$data['go_main_content'] = 'go_settings/go_moh';
		$this->load->view('includes/go_dashboard_template',$data);
	}
	
	function go_get_list()
	{
		$search = mysql_real_escape_string($this->uri->segment(4));
		if (strlen($search) > 0) {
			$searchSQL = "AND moh_id RLIKE '$search'";
		}
		
		$groupSQL = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "AND user_group='".$this->session->userdata('user_group')."'" : "";
		
		$query	= $this->db->query("SELECT count(*) AS cnt FROM vicidial_music_on_hold WHERE remove='N' $groupSQL $searchSQL;");
		$total	= $query->row()->cnt;
		$limit 	= 5;
		$page	= $this->uri->segment(3);
		$rp	= ($page=='ALL') ? $total : 25;
		if (is_null($page) || $page < 1)
			$page = 1;
		$start	= (($page-1) * $rp);
		
		$data['pagelinks']	= $this->go_moh->pagelinks($page,$rp,$total,$limit);
		$data['search_list']	= $search;
		$data['moh_list']	= $this->go_dashboard->db->query("SELECT * FROM vicidial_music_on_hold WHERE remove='N' $groupSQL $searchSQL ORDER BY moh_id LIMIT $start,$rp;");
		
		$this->load->view('go_settings/go_moh_list',$data);
	}
	
	function go_moh_wizard()
	{
		$action = $this->input->post("action");
		if (strlen($action)>0)
		{
			switch($action)
			{
				case "add":
					$items = str_replace(";","",$this->input->post("items"));
					$itemSQL = "INSERT INTO vicidial_music_on_hold SET ";
					foreach (explode("&",$items) as $item)
					{
						$itemX = explode("=",$item);
						
						if ($itemX[0]=="moh_id")
							$moh_id = $itemX[1];
						
						$itemSQL .= $itemX[0]."='".str_replace("+"," ",$itemX[1])."',";
					}
					$itemSQL = rtrim($itemSQL,",");
					$newQuery = $this->go_dashboard->db->query($itemSQL);
					if ($this->go_dashboard->db->affected_rows())
					{
						$this->go_dashboard->db->query("INSERT INTO vicidial_music_on_hold_files SET filename='conf',rank='1',moh_id='$moh_id';");
						$this->commonhelper->auditadmin('ADD',"Added MOH ID: $moh_id",$itemSQL);
						$this->go_dashboard->db->query("UPDATE servers SET rebuild_conf_files='Y',rebuild_music_on_hold='Y',sounds_update='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y';");
						echo "SUCCESS";
					} else {
						echo "ERROR";
					}
					break;
				
				case "modify":
					$items = str_replace(";","",$this->input->post("items"));
					$affected_rows = 0;
					$itemSQL = "UPDATE vicidial_music_on_hold SET ";
					foreach (explode("&",$items) as $item)
					{
						$itemX = explode("=",$item);
						
						if ($itemX[0]=="moh_id")
							$moh_id = $itemX[1];
						
						if ($itemX[0]=="moh_name" || $itemX[0]=="active" || $itemX[0]=="user_group" || $itemX[0]=="random")
						{
							if (strlen($itemX[1])>0)
							{
								//$itemArray[$itemX[0]] = $itemX[1];
								$itemSQL .= $itemX[0]."='".str_replace("+"," ",$itemX[1])."',";
							}
						} else {
							if (($itemX[0]!="moh_id" && $itemX[0]!="filename") && strlen($itemX[1])>0)
							{
								$fileSQL = "UPDATE vicidial_music_on_hold_files SET rank='".$itemX[1]."' WHERE moh_id='$moh_id' AND filename='".$itemX[0]."'";
								$newQuery = $this->go_dashboard->db->query($fileSQL);
								if ($this->go_dashboard->db->affected_rows())
								{
									$this->commonhelper->auditadmin('MODIFY',"Modified MOH ID $moh_id File ".$itemX[0]." Rank: ".$itemX[1],$fileSQL);
									$affected_rows++;
								}
							}
							
							if ($itemX[0]=="filename" && strlen($itemX[1])>0)
							{
								$query = $this->go_dashboard->db->query("SELECT count(*) as cnt FROM vicidial_music_on_hold_files WHERE moh_id='$moh_id';");
								$rows = $query->row()->cnt;
								$ranks = $rows + 1;
								$newFileSQL = "INSERT INTO vicidial_music_on_hold_files SET filename='".$itemX[1]."',rank='$ranks',moh_id='$moh_id';";
								$newQuery = $this->go_dashboard->db->query($newFileSQL);
								if ($this->go_dashboard->db->affected_rows())
								{
									$this->commonhelper->auditadmin('ADD',"Added File ".$itemX[0]." on MOH ID: $moh_id",$newFileSQL);
									$affected_rows++;
								}
							}
						}
					}
					$itemSQL = rtrim($itemSQL,",");
					$itemSQL .= " WHERE moh_id='$moh_id';";
					$newQuery = $this->go_dashboard->db->query($itemSQL);
					if ($this->go_dashboard->db->affected_rows())
					{
						$this->commonhelper->auditadmin('MODIFY',"Modified MOH ID: $moh_id",$itemSQL);
						$affected_rows++;
					}
					if ($affected_rows)
					{
						$this->go_dashboard->db->query("UPDATE servers SET rebuild_conf_files='Y',rebuild_music_on_hold='Y',sounds_update='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y';");
						echo "SUCCESS";
					} else {
						echo "ERROR";
					}
					break;
				
				case "delete_file":
					$moh_id = $this->input->post("mohid");
					$filename = $this->input->post("mohfile");
					$query = $this->go_dashboard->db->query("DELETE FROM vicidial_music_on_hold_files where moh_id='$moh_id' and filename='$filename';");
					if ($this->go_dashboard->db->affected_rows())
					{
						$this->commonhelper->auditadmin('DELETE',"Deleted File $filename from MOH ID: $moh_id","DELETE FROM vicidial_music_on_hold_files where moh_id='$moh_id' and filename='$filename';");
						$this->go_dashboard->db->query("UPDATE servers SET rebuild_conf_files='Y',rebuild_music_on_hold='Y',sounds_update='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y';");
						echo "DELETED";
					} else {
						echo "ERROR";
					}
					break;
			}
		} else {
			$user_group_array = $this->go_dashboard->go_list_user_groups('all');
			$user_group_array['---ALL---'] = "--- All User Groups ---";
			ksort($user_group_array);
			$data['user_group_array'] = $user_group_array;
			$data['my_user_group'] = $this->session->userdata('user_group');
			
			$this->load->view('go_settings/go_moh_wizard',$data);
		}
	}
	
	function go_get_moh()
	{
		$moh_id = $this->uri->segment(3);
		$query = $this->go_dashboard->db->query("SELECT * FROM vicidial_music_on_hold WHERE moh_id='$moh_id'");
		$result = $query->row();
		
		$data['moh_info'] = $result;
		$user_group_array = $this->go_dashboard->go_list_user_groups('all');
		$user_group_array['---ALL---'] = "--- All User Groups ---";
		ksort($user_group_array);
		$data['user_group_array'] = $user_group_array;
		
		$query = $this->go_dashboard->db->query("SELECT filename,rank from vicidial_music_on_hold_files where moh_id='$moh_id' order by rank;");
		$data['moh_files'] = $query->result();
		$data['moh_file_cnt'] = $query->num_rows();
		
		$data['moh_audio_list'] = $this->go_get_audio_list();
		
		$this->load->view('go_settings/go_moh_view',$data);
	}
	
	function go_update_moh_list()
	{
		$action = $this->uri->segment(3);
		$mohids = str_replace(',',"','",$this->uri->segment(4));

		switch($action)
		{
			case "activate":
				$query = $this->go_dashboard->db->query("UPDATE vicidial_music_on_hold SET active='Y' WHERE moh_id IN ('$mohids')");
				$this->commonhelper->auditadmin('ACTIVE','Activated MOH ID(s): '.$this->uri->segment(4),"UPDATE phones SET active='Y' WHERE extension IN ('$mohids')");
				break;
			
			case "deactivate":
				$query = $this->go_dashboard->db->query("UPDATE vicidial_music_on_hold SET active='N' WHERE moh_id IN ('$mohids')");
				$this->commonhelper->auditadmin('INACTIVE','Deactivated MOH ID(s): '.$this->uri->segment(4),"UPDATE phones SET active='N' WHERE extension IN ('$mohids')");
				break;
			
			case "delete":
				$query = $this->go_dashboard->db->query("DELETE FROM vicidial_music_on_hold WHERE moh_id IN ('$mohids')");
				$query = $this->go_dashboard->db->query("DELETE FROM vicidial_music_on_hold_files WHERE moh_id IN ('$mohids')");
				$this->commonhelper->auditadmin('DELETE',"Deleted MOH ID(s): ".$this->uri->segment(4),"DELETE FROM vicidial_music_on_hold WHERE moh_id IN ('$mohids')");
				$query = $this->go_dashboard->db->query("UPDATE servers SET rebuild_conf_files='Y',rebuild_music_on_hold='Y',sounds_update='Y' where generate_vicidial_conf='Y' and active_asterisk_server='Y';");
				break;
		}
		
		$query	= $this->db->query("SELECT count(*) AS cnt FROM vicidial_music_on_hold;");
		$total	= $query->row()->cnt;
		$limit 	= 5;
		$page	= $this->uri->segment(5);
		$rp	= ($page=='ALL') ? $total : 25;
		if (is_null($page) || $page < 1)
			$page = 1;
		$start	= (($page-1) * $rp);
		
		$data['pagelinks'] = $this->go_moh->pagelinks($page,$rp,$total,$limit);
		$data['moh_list'] = $this->go_dashboard->db->query("SELECT * FROM vicidial_music_on_hold WHERE remove='N' ORDER BY moh_id LIMIT $start,$rp;");
		//$data['pagelinks'] = $phones['pagelinks'];
		$this->load->view('go_settings/go_moh_list',$data);
	}
	
	function go_check_moh()
	{
		$moh_id = $this->uri->segment(3);
		$query = $this->db->query("SELECT * FROM vicidial_music_on_hold WHERE moh_id='$moh_id'");
		$return = $query->num_rows();
		
		if ($return)
		{
			$return = "<small style=\"color:red;\">Not Available.</small>";
		} else {
			$return = "<small style=\"color:green;\">Available.</small>";
		}
		
		echo $return;
	}
	
	function go_get_audio_list()
	{
		$i=0;
		$filename_sort=$MT;
		#$dirpath = "$WeBServeRRooT/$sounds_web_directory";
		$dirpath = "/var/lib/asterisk/sounds";
		$dh = opendir($dirpath);
		
		$file_nameArray[''] = "--- Select an audio file to upload ---";
		while (false !== ($file = readdir($dh)))
		{
			# Do not list subdirectories
			$prefix = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "go_".$this->session->userdata('user_group')."_" : "go_";
			if ( (!is_dir("$dirpath/$file")) and (preg_match('/\.wav$|\.gsm$/', $file)) and (preg_match("/^$prefix/", $file)) )
			{
				if (file_exists("$dirpath/$file"))
				{
					$file_name = preg_replace("/\.wav$|\.gsm$/","",$file);
					$file_nameArray[$file_name] = "$file_name";
				}
			}
		}
		closedir($dh);
		ksort($file_nameArray);
		
		return $file_nameArray;
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