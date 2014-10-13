<?php
########################################################################################################
####  Name:             	go_dnc_ce.php                                                  	    ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Christopher Lomuntad				                    ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_dnc_ce extends Controller {
	var $userLevel;

    function __construct()
	{
		parent::Controller();
		$this->load->model(array('go_campaign','go_auth','go_monitoring','golist','go_dashboard','go_reports'));
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
		$data['bannertitle'] = $this->lang->line('go_dnc_banner');
		$data['adm']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);


		$data['userfulname'] = $this->go_campaign->go_get_userfulname();
		$data['user_group'] = $this->session->userdata('user_group');

// 		$accountNum = $this->go_campaign->go_get_groupid();
// 		$allowed_campaigns = $this->go_campaign->go_get_allowed_campaigns($accountNum);
// 		$query = $this->db->query("SELECT campaign_id,campaign_name FROM vicidial_campaigns WHERE campaign_id IN ('$allowed_campaigns')");
// 		$data['list_of_campaigns'] = $query->result();
// 
// 		$query = $this->db->query("SELECT phone_number,vicidial_campaign_dnc.campaign_id,campaign_name FROM vicidial_campaign_dnc,vicidial_campaigns WHERE vicidial_campaign_dnc.campaign_id IN ('$allowed_campaigns') AND vicidial_campaigns.campaign_id=vicidial_campaign_dnc.campaign_id ORDER BY vicidial_campaign_dnc.campaign_id,phone_number");
// 		$data['dnc_list'] = $query->result();

		$data['go_main_content'] = 'go_dnc/go_dnc';
		$this->load->view('includes/go_dashboard_template',$data);
	}

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			$base = base_url("../login");
			echo "<script>javascript: window.location = 'https://".$_SERVER['HTTP_HOST']."/login'</script>";
// 			echo "<script>javascript: window.location = '$base'</script>";
			#echo 'You don\'t have permission to access this page. <a href="../go_index">Login</a>';
			die();
			#$this->load->view('go_login_form');
		}
	}

	function go_dnc_numbers()
	{
		$string = $this->go_campaign->go_unserialize($this->uri->segment(3));
		$cnt = 0;

		if ($string['campaign_id'] == "INTERNAL")
		{
			$dnc_numbers = explode("\r\n",$string['phone_numbers']);

			foreach ($dnc_numbers as $dnc)
			{
				$query = $this->db->query("SELECT phone_number AS cnt FROM vicidial_dnc WHERE phone_number='$dnc'");
				$idnc_exist = $query->num_rows();
				
				$query = $this->db->query("SELECT phone_number AS cnt FROM vicidial_campaign_dnc WHERE phone_number='$dnc'");
				$cdnc_exist = $query->num_rows();

				if ($idnc_exist < 1 && $cdnc_exist < 1)
				{
					if ($string['stage'] == "add" && $dnc != '')
					{
// 							var_dump("INSERT INTO vicidial_campaign_dnc VALUES('$dnc','".$camp->campaign_id."')");
						
						if ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) {
							$allowed_campaigns = $this->go_campaign->go_get_allowed_campaigns(true);
							$allowed_campaigns = str_replace(' -','',ltrim($allowed_campaigns));
							foreach (explode(' ',$allowed_campaigns) as $camp) {
								$dncLOG .= "INSERT INTO vicidial_campaign_dnc VALUES('$dnc','$camp'); ";
								$query = $this->db->query("INSERT INTO vicidial_campaign_dnc VALUES('$dnc','$camp');");
								$this->commonhelper->auditadmin('ADD',"Added DNC Number $dnc to Internal DNC List","INSERT INTO vicidial_campaign_dnc VALUES('$dnc','$camp');");
							}
						} else {
							$dncLOG .= "INSERT INTO vicidial_dnc VALUES('$dnc'); ";
							$query = $this->db->query("INSERT INTO vicidial_dnc VALUES('$dnc');");
							$this->commonhelper->auditadmin('ADD',"Added DNC Number $dnc to Internal DNC List","INSERT INTO vicidial_dnc VALUES('$dnc');");
						}
						$cnt++;
					}
				} else {
					if ($string['stage'] == "delete" && $dnc != '')
					{
// 							var_dump("DELETE FROM vicidial_campaign_dnc WHERE phone_number='$dnc' AND campaign_id='".$camp->campaign_id."'");
						$query = $this->db->query("DELETE FROM vicidial_dnc WHERE phone_number='$dnc'");
						$this->commonhelper->auditadmin('DELETE',"Deleted DNC Number $dnc from Internal DNC List","DELETE FROM vicidial_dnc WHERE phone_number='$dnc'");
						$cnt++;
					}
				}
			}

			if ($cnt)
			{
				if ($string['stage'] == "add")
					$msg = "added";
				else
					$msg = "deleted";
			} else {
				if ($string['stage'] == "add")
					$msg = "already exist";
				else
					$msg = "does not exist";
			}
		} else {
			$dnc_numbers = explode("\r\n",$string['phone_numbers']);

			foreach ($dnc_numbers as $dnc)
			{
				$query = $this->db->query("SELECT phone_number AS cnt FROM vicidial_campaign_dnc WHERE phone_number='$dnc' AND campaign_id='".$string['campaign_id']."'");
				$cdnc_exist = $query->num_rows();
				
				$query = $this->db->query("SELECT phone_number AS cnt FROM vicidial_dnc WHERE phone_number='$dnc'");
				$idnc_exist = $query->num_rows();

				if ($idnc_exist < 1 && $cdnc_exist < 1)
				{
					if ($string['stage'] == "add")
					{
// 						var_dump("INSERT INTO vicidial_campaign_dnc VALUES('$dnc','".$string['campaign_id']."')");
						$query = $this->db->query("INSERT INTO vicidial_campaign_dnc VALUES('$dnc','".$string['campaign_id']."')");
						$this->commonhelper->auditadmin('ADD',"Added DNC Number $dnc for Campaign ".$string['campaign_id'],"INSERT INTO vicidial_campaign_dnc VALUES('$dnc','".$string['campaign_id']."')");
						$cnt++;
					}
				} else {
					if ($string['stage'] == "delete")
					{
// 						var_dump("DELETE FROM vicidial_campaign_dnc WHERE phone_number='$dnc' AND campaign_id='".$string['campaign_id']."'");
						$query = $this->db->query("DELETE FROM vicidial_campaign_dnc WHERE phone_number='$dnc' AND campaign_id='".$string['campaign_id']."'");
						$this->commonhelper->auditadmin('DELETE',"Deleted DNC Number $dnc from Campaign ".$string['campaign_id'],"DELETE FROM vicidial_campaign_dnc WHERE phone_number='$dnc' AND campaign_id='".$string['campaign_id']."'");
						$cnt++;
					}
				}
			}

			if ($cnt)
			{
				if ($string['stage'] == "add")
					$msg = "added";
				else
					$msg = "deleted";
			} else {
				if ($string['stage'] == "add")
					$msg = "already exist";
				else
					$msg = "does not exist";
			}
		}

		echo $msg;
	}

	function go_get_dnc_numbers()
	{
		$query = $this->db->query("SELECT phone_number FROM vicidial_dnc ORDER BY phone_number");
		$dnc_int = $query->result();

		$allowed_campaigns = $this->go_campaign->go_get_allowed_campaigns();
		if ($allowed_campaigns!="ALLCAMPAIGNS")
		{
			$filter_camp_SQL = "vicidial_campaign_dnc.campaign_id IN ('$allowed_campaigns') AND";
		}
		
		$query = $this->db->query("SELECT phone_number,vicidial_campaign_dnc.campaign_id,vicidial_campaigns.campaign_name FROM vicidial_campaign_dnc,vicidial_campaigns WHERE $filter_camp_SQL vicidial_campaigns.campaign_id=vicidial_campaign_dnc.campaign_id ORDER BY phone_number");
		$dnc_camp = $query->result();

		$data['dnc_list'] = array_merge($dnc_int, $dnc_camp);
		$this->load->view('go_dnc/go_dnc_list',$data);
	}

	function go_search_dnc($page=null,$number=null)
	{
		$number = ($number==null) ? $this->uri->segment(4) : $number;
		$limit	= 5;
		$page_load = ($page=="start") ? true : false;

		if (strlen($number) > 0)
		{
			$addedSQL = "WHERE phone_number LIKE '$number%'";
			$addedSQLx = "WHERE vicidial_campaign_dnc.phone_number LIKE '$number%'";
		}

		//$query	= $this->db->query("SELECT vicidial_dnc.phone_number AS phone_number,'' AS campaign_id,'' AS campaign_name FROM vicidial_dnc LEFT JOIN vicidial_campaign_dnc ON vicidial_dnc.phone_number=vicidial_campaign_dnc.phone_number WHERE vicidial_dnc.phone_number LIKE '$number%' UNION ALL SELECT vicidial_campaign_dnc.phone_number,vicidial_campaign_dnc.campaign_id,campaign_name FROM vicidial_dnc RIGHT JOIN vicidial_campaign_dnc ON vicidial_dnc.phone_number=vicidial_campaign_dnc.phone_number LEFT JOIN vicidial_campaigns ON vicidial_campaign_dnc.campaign_id=vicidial_campaigns.campaign_id WHERE $filter_camp_SQL vicidial_campaign_dnc.phone_number LIKE '$number%'");
		$query	= $this->db->query("SELECT count(*) AS cnt FROM vicidial_dnc $addedSQL");
		$intdnc	= $query->row()->cnt;
		$query	= $this->db->query("SELECT count(*) AS cnt FROM vicidial_campaign_dnc $addedSQL");
		$pubdnc	= $query->row()->cnt;

		$total 	= ($intdnc + $pubdnc);
		$rp	= ($page=='ALL') ? $total : 25;
		if ($page==null || ($page < 1 && $page == "ALL"))
			$page = 1;
		$start	= (($page-1) * $rp);
		
		//$query = $this->db->query("SELECT phone_number FROM vicidial_dnc WHERE phone_number LIKE '$number%' ORDER BY phone_number");
		//$dnc_int = $query->result();

		$allowed_campaigns = $this->go_campaign->go_get_allowed_campaigns();
		if ($allowed_campaigns!="ALLCAMPAIGNS")
		{
			$andWhere = "WHERE";
			if (strlen($addedSQLx) > 0)
				$andWhere = "AND";
			$filter_camp_SQL = "$andWhere vicidial_campaign_dnc.campaign_id IN ('$allowed_campaigns')";
		}

		if (!$page_load) {
			//$query = $this->db->query("SELECT phone_number,vicidial_campaign_dnc.campaign_id,campaign_name FROM vicidial_campaign_dnc,vicidial_campaigns WHERE $filter_camp_SQL vicidial_campaigns.campaign_id=vicidial_campaign_dnc.campaign_id AND phone_number LIKE '$number%' ORDER BY phone_number LIMIT $start,$limit");
			$query = $this->db->query("SELECT vicidial_dnc.phone_number AS phone_number,'' AS campaign_id,'' AS campaign_name FROM vicidial_dnc LEFT JOIN vicidial_campaign_dnc ON vicidial_dnc.phone_number=vicidial_campaign_dnc.phone_number WHERE vicidial_dnc.phone_number LIKE '$number%' UNION ALL SELECT vicidial_campaign_dnc.phone_number,vicidial_campaign_dnc.campaign_id,campaign_name FROM vicidial_dnc RIGHT JOIN vicidial_campaign_dnc ON vicidial_dnc.phone_number=vicidial_campaign_dnc.phone_number LEFT JOIN vicidial_campaigns ON vicidial_campaign_dnc.campaign_id=vicidial_campaigns.campaign_id $addedSQLx $filter_camp_SQL LIMIT $start,$rp");
			$data['dnc_list'] = $query->result();
		} else {
			$data['dnc_list'] = array('start' => 'true');
		}
		
		$data['paginate'] = $this->pagelinks($page,$rp,$total,$limit);

		//$data['dnc_list'] = array_merge($dnc_int, $dnc_camp);
		$this->load->view('go_dnc/go_dnc_list',$data);
	}

	function go_submit_dnc()
	{
		$allowed_campaigns = $this->go_campaign->go_get_allowed_campaigns();
		if ($allowed_campaigns!="ALLCAMPAIGNS")
		{
			$filter_camp_SQL = "WHERE campaign_id IN ('$allowed_campaigns')";
		}
		
		$query = $this->db->query("SELECT campaign_id,campaign_name FROM vicidial_campaigns $filter_camp_SQL");
		$data['list_of_campaigns'] = $query->result();

		$this->load->view('go_dnc/go_dnc_wizard',$data);
	}

	function go_delete_dnc_number()
	{
		list($number, $camp) = explode('-',$this->uri->segment(3));
		if (strlen($camp)>0) {
			$stmt = "DELETE FROM vicidial_campaign_dnc WHERE phone_number='$number' AND campaign_id='$camp'";
			$query = $this->db->query($stmt);
		}
		else {
			$stmt = "DELETE FROM vicidial_dnc WHERE phone_number='$number'";
			$query = $this->db->query($stmt);
		}

		$return = $this->db->affected_rows();
                $this->commonhelper->auditadmin("DELETE","DELETE DNC $number",$stmt);

		echo $return;
	}

	function go_delete_mass_dnc_number()
	{
		$array = explode(',',$this->uri->segment(3));

		foreach ($array as $segment)
		{
			list($number, $camp) = explode('-',$segment);
			$query = $this->db->query("DELETE FROM vicidial_campaign_dnc WHERE phone_number='$number' AND campaign_id='$camp'");
			$query = $this->db->query("DELETE FROM vicidial_dnc WHERE phone_number='$number'");
                        $this->commonhelper->auditadmin("DELETE","DELETE DNC $number","DELETE FROM vicidial_campaign_dnc WHERE phone_number='$number' AND campaign_id='$camp'; DELETE FROM vicidial_dnc WHERE phone_number='$number'");
			$return = $this->db->affected_rows();
		}

		echo $return;
	}
	
	function pagelinks($page,$rp,$total,$limit)
	{
		$pg 	= $this->commonhelper->paging($page,$rp,$total,$limit);
		$start	= (($page-1) * $rp);
	
		if ($pg['last'] > 1) {
			$pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top:10px;">';
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
		
		$pageinfo = "<span style='float:right;padding-top:10px;'>Displaying {$pg['istart']} to {$pg['iend']} of {$pg['total']} DNC numbers</span>";
		
		$return['links'] = $pagelinks;
		$return['info'] = $pageinfo;
		
		return $return;
	}

}