<?php
########################################################################################################
####  Name:             	go_logs_ce.php                                                      ####
####  Type:             	ci controller - administrator                                       ####
####  Version:          	3.0                                                                 ####
####  Build:            	1375243200                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####      	                <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_logs_ce extends Controller {
	var $userLevel;

    function __construct()
	{
		parent::Controller();
		$this->load->model(array('go_auth','go_access','go_dashboard'));
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
		$data['bannertitle'] = $this->lang->line('go_admin_logs');
		$data['sys']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;
		$data['userOS'] = $this->go_dashboard->go_get_os($_SERVER['HTTP_USER_AGENT']);

		$data['userfulname'] = $this->go_dashboard->go_get_userfulname();

        $data['go_main_content'] = 'go_settings/go_logs';
        $this->load->view('includes/go_dashboard_template',$data);
	}
	
	function go_get_logs()
	{
		if (!is_null($this->uri->segment(4)) && strlen($this->uri->segment(4)) > 2) {
			$search = addcslashes(mysql_real_escape_string($this->uri->segment(4)),"%");
			$addedSQL = "WHERE (user RLIKE '$search' OR ip_address RLIKE '$search')";
		}
		
		if ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) {
			$noWHERE = (strlen($addedSQL) > 0) ? "AND" : "WHERE";
			$usergroupSQL = "$noWHERE user_group='".$this->session->userdata('user_group')."'";
		}
		
		$query	= $this->go_dashboard->goautodial->query("SELECT count(*) AS cnt FROM go_action_logs $addedSQL $usergroupSQL;");
		$total	= $query->row()->cnt;
		$limit 	= 5;
		$rp	= 25;
		$page	= $this->uri->segment(3);
		if (is_null($page) || $page < 1)
			$page = 1;
		$start	= (($page-1) * $rp);

		$data['pagelinks'] = $this->pagelinks($page,$rp,$total,$limit);
		$data['admin_logs'] = $this->go_dashboard->goautodial->query("SELECT * FROM go_action_logs $addedSQL $usergroupSQL ORDER BY event_date DESC LIMIT $start,$rp;");
		
		$this->load->view('go_settings/go_logs_list',$data);
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
	
			$pagelinks .= '<a title="'.$this->lang->line('go_to_next').'" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['next'].')"><span><img src="'.base_url().'/img/next.gif"></span></a>';
			$pagelinks .= '<a title="'.$this->lang->line('go_to_last').'" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage('.$pg['last'].')"><span><img src="'.base_url().'/img/last.gif"></span></a>';
			$pagelinks .= '</div>';
		} else {
			$pagelinks = "";
		}
		
		$pageinfo = "<span style='float:right;padding-top:10px;'>{$this->lang->line('go_displaying')} {$pg['istart']} {$this->lang->line('go_to')} {$pg['iend']} {$this->lang->line('go_of')} {$pg['total']} {$this->lang->line('go_logs_s')}</span>";
		
		$return['links'] = $pagelinks;
		$return['info'] = $pageinfo;
		
		return $return;
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
