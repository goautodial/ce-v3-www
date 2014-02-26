<?php
########################################################################################################
####  Name:             	go_dashboard.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  Modified by:      	GoAutoDial Development Team                                    	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_dashboard extends Model {

   function __construct()
	{
		 parent::Model();
		 $this->db = $this->load->database('dialerdb', true);
		 $this->goautodial = $this->load->database('goautodialdb',true);
	}

	function go_outbound_today()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and vl.campaign_id IN ('$stringv') ";
	    }
	    $query_date =  date('Y-m-d');
	    $status = "SALE";
	    $query = $this->db->query("SELECT status FROM vicidial_statuses WHERE sale='Y'");
	    foreach ($query->result() as $status)
	    {
	       $sstatuses[$status->status] = $status->status;
	    }
	    $sstatuses = implode("','",$sstatuses);
	    
	    $query = $this->db->query("SELECT status FROM vicidial_campaign_statuses WHERE sale='Y' $ul");
	    foreach ($query->result() as $status)
	    {
	       $cstatuses[$status->status] = $status->status;
	    }
	    $cstatuses = implode("','",$cstatuses);
	    if (strlen($sstatuses) > 0 && strlen($cstatuses) > 0)
	    {
	       $statuses = "{$sstatuses}','{$cstatuses}";
	    } else {
	       $statuses = (strlen($sstatuses) > 0 && strlen($cstatuses) < 1) ? $sstatuses : $cstatuses;
	    }
	    
	    $date = "call_date BETWEEN '$query_date 00:00:00' AND '$query_date 23:59:59'";
	    $query = $this->db->query("select count(*) as qresult from vicidial_log vl,vicidial_agent_log val where vl.uniqueid=val.uniqueid and val.status IN ('$statuses') and $date $ul");
	    $resultsu = $query->row();
	    $fresults = $resultsu->qresult;
	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

	function go_inbound_today()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and vcl.campaign_id IN ('$stringv') ";
	    }
	    $query_date =  date('Y-m-d');
	    $status = "SALE";
	    $query = $this->db->query("SELECT status FROM vicidial_statuses WHERE sale='Y'");
	    foreach ($query->result() as $status)
	    {
	       $sstatuses[$status->status] = $status->status;
	    }
	    $sstatuses = implode("','",$sstatuses);
	    
	    $query = $this->db->query("SELECT status FROM vicidial_campaign_statuses WHERE sale='Y' $ul");
	    foreach ($query->result() as $status)
	    {
	       $cstatuses[$status->status] = $status->status;
	    }
	    $cstatuses = implode("','",$cstatuses);
	    if (strlen($sstatuses) > 0 && strlen($cstatuses) > 0)
	    {
	       $statuses = "{$sstatuses}','{$cstatuses}";
	    } else {
	       $statuses = (strlen($sstatuses) > 0 && strlen($cstatuses) < 1) ? $sstatuses : $cstatuses;
	    }
	    $date = "call_date BETWEEN '$query_date 00:00:00' AND '$query_date 23:59:59'";
	    $query = $this->db->query("select count(*) as qresult from vicidial_closer_log vcl,vicidial_agent_log val where vcl.uniqueid=val.uniqueid and val.status IN ('$statuses') and $date $ul");
	    $resultsu = $query->row();
	    $fresults = $resultsu->qresult;
	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

	function go_sippy_info($username) {
	    $stmt = "SELECT carrier_id,username,web_password,authname,voip_password,vm_password,i_account FROM justgovoip_sippy_info WHERE username='$username';";
	    $listresults = $this->goautodial->query($stmt);
              $ctr = 0;
              foreach($listresults->result() as $info){
                  $listresultsval[$ctr] = $info;
                  $ctr++;
              }
              return $listresultsval;
	}
	
	function go_carrier_info() {
	    $stmt = "SELECT jsi.carrier_id, vsc.carrier_name, vsc.active FROM vicidial_server_carriers vsc, justgovoip_sippy_info jsi WHERE vsc.carrier_id = jsi.carrier_id;";
	    $listresults = $this->db->query($stmt);
              $ctr = 0;
              foreach($listresults->result() as $info){
                  $listresultsval[$ctr] = $info;
                  $ctr++;
              }
              return $listresultsval;
	}

	function go_inbound_sph()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and vcl.campaign_id IN ('$stringv') ";
	    }
	    $query_date =  date('Y-m-d');
	    $status = "SALE";
	    $query = $this->db->query("SELECT status FROM vicidial_statuses WHERE sale='Y'");
	    foreach ($query->result() as $status)
	    {
	       $sstatuses[$status->status] = $status->status;
	    }
	    $sstatuses = implode("','",$sstatuses);
	    
	    $query = $this->db->query("SELECT status FROM vicidial_campaign_statuses WHERE sale='Y' $ul");
	    foreach ($query->result() as $status)
	    {
	       $cstatuses[$status->status] = $status->status;
	    }
	    $cstatuses = implode("','",$cstatuses);
	    if (strlen($sstatuses) > 0 && strlen($cstatuses) > 0)
	    {
	       $statuses = "{$sstatuses}','{$cstatuses}";
	    } else {
	       $statuses = (strlen($sstatuses) > 0 && strlen($cstatuses) < 1) ? $sstatuses : $cstatuses;
	    }
	    $date = "call_date BETWEEN '$query_date 00:00:00' AND '$query_date 23:59:59'";
	    $query = $this->db->query("select count(*) as qresult from vicidial_closer_log vcl,vicidial_agent_log val where vcl.uniqueid=val.uniqueid and val.status IN ('$statuses') and $date $ul");
	    $resultsu = $query->row();
	    $fresults = $resultsu->qresult;
	    $resultf = number_format($fresults/8, 2, '.', '');
	    return $resultf;
	}

	function go_outbound_sph()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and vl.campaign_id IN ('$stringv') ";
	    }
            $query_date =  date('Y-m-d');
	    $status = "SALE";
	    $query = $this->db->query("SELECT status FROM vicidial_statuses WHERE sale='Y'");
	    foreach ($query->result() as $status)
	    {
	       $sstatuses[$status->status] = $status->status;
	    }
	    $sstatuses = implode("','",$sstatuses);
	    
	    $query = $this->db->query("SELECT status FROM vicidial_campaign_statuses WHERE sale='Y' $ul");
	    foreach ($query->result() as $status)
	    {
	       $cstatuses[$status->status] = $status->status;
	    }
	    $cstatuses = implode("','",$cstatuses);
	    if (strlen($sstatuses) > 0 && strlen($cstatuses) > 0)
	    {
	       $statuses = "{$sstatuses}','{$cstatuses}";
	    } else {
	       $statuses = (strlen($sstatuses) > 0 && strlen($cstatuses) < 1) ? $sstatuses : $cstatuses;
	    }
	    $date = "call_date BETWEEN '$query_date 00:00:00' AND '$query_date 23:59:59'";
	    $query = $this->db->query("select count(*) as qresult from vicidial_log vl,vicidial_agent_log val where vl.uniqueid=val.uniqueid and val.status IN ('$statuses') and $date $ul");
            $resultsu = $query->row();
	    $fresults = $resultsu->qresult;
	    $resultf = number_format($fresults/8, 2, '.', '');
            return $resultf;
	}

        function go_live_inbound_today()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
	    $query = $this->db->query("select count(*) AS inbound from vicidial_live_agents as vla,vicidial_users as vu where vla.user=vu.user and status = 'INCALL' and comments = 'INBOUND' $ul");
	    $resultsu = $query->row();
	    $fresults = $resultsu->inbound;
	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

        function go_live_outbound_today()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
	    $query = $this->db->query("select count(*) AS outbound from vicidial_live_agents as vla,vicidial_users as vu where vla.user=vu.user and status = 'INCALL' and (comments IN ('MANUAL','AUTO') or length(comments) < '1') $ul");
	    $resultsu = $query->row();
	    $fresults = $resultsu->outbound;
	    if ($fresults == NULL)
	    {
		  $fresults = 0;
	    }
	    return $fresults;
	}

        function go_calls_queue_today()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
	    $query = $this->db->query("select count(*) AS queue from vicidial_auto_calls where status NOT IN('XFER') and call_type = 'IN' $ul");
	    $resultsu = $query->row();
	    $fresults = $resultsu->queue;
	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

	function go_calls_outbound_queue_today()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
// 		$query = $this->db->query("select count(*) AS queue from vicidial_auto_calls where status NOT rlike 'XFER' and call_type rlike 'IN' $ul");

		$query = $this->db->query("SELECT count(*) AS queue FROM vicidial_auto_calls where status NOT IN('XFER') and (call_type IN('OUT','OUTBALANCE') $ul)");
	    $resultsu = $query->row();
	    $fresults = $resultsu->queue;

	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

	function go_calls_inbound_queue_today()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_inbound_groups();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }

		$query = $this->db->query("SELECT count(*) AS queue FROM vicidial_auto_calls where status NOT IN('XFER','CLOSER') and (call_type='IN' $ul)");
	    $resultsu = $query->row();
	    $fresults = $resultsu->queue;

	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

        function go_calls_ringing_today()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
	    $query = $this->db->query("select count(*) AS ringing from vicidial_auto_calls where status NOT IN('XFER') and call_type RLIKE 'OUT' $ul");
            $resultsu = $query->row();
	    $fresults = $resultsu->ringing;
	    if ($fresults == NULL)
	    {
	          $fresults = 0;
	    }
	    return $fresults;
	}

    function go_dropped_calls_today()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $outCamps = $this->go_getall_allowed_campaigns();
		   $inbCamps = $this->go_getall_inbound_groups();
	       $ul = "and campaign_id IN ('$outCamps','$inbCamps') ";
	    }
		$NOW = date("Y-m-d");
		
	    $query = $this->db->query("select sum(calls_today) as calls_today,sum(drops_today) as drops_today,sum(answers_today) as answers_today from vicidial_campaign_stats where calls_today > -1 and update_time BETWEEN '$NOW 00:00:00' AND '$NOW 23:59:59' $ul");
	    $resultsu = $query->row();
	    return $resultsu;
	}

        function go_ibcalls_morethan_minute()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
	    $query = $this->db->query("select SUM(IF(UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(last_call_time) > '60', 1, 0)) AS timediffin from vicidial_live_agents as vla,vicidial_users as vu where vla.user=vu.user and status = 'INCALL' and comments = 'INBOUND' $ul");
            $resultsu = $query->row();
	    $fresults = $resultsu->timediffin;
	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

       function go_obcalls_morethan_minute()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
	    $query = $this->db->query("select SUM(IF(UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(last_call_time) > '60', 1, 0)) AS timediffout from vicidial_live_agents as vla,vicidial_users as vu where vla.user=vu.user and status = 'INCALL' and (comments IN ('MANUAL','AUTO') or length(comments) < '1') $ul");
            $resultsu = $query->row();
	    $fresults = $resultsu->timediffout;
	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

       function go_total_calls()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       //$stringv = $this->go_getall_allowed_campaigns();
	       //$ul = " and campaign_id IN ('$stringv') ";
	       $outCamps = $this->go_getall_allowed_campaigns();
		   $inbCamps = $this->go_getall_inbound_groups();
	       $ul = "and campaign_id IN ('$outCamps','$inbCamps') ";
	    }
	    $query_date =  date('Y-m-d');
		//"SELECT count(*) as totcalls from vicidial_users as us, vicidial_log as vlog, vicidial_list as vl where us.user=vlog.user and vl.phone_number=vlog.phone_number and vl.lead_id=vlog.lead_id and vlog.call_date between '$query_date 00:00:00' and '$query_date 23:59:59' $ul"
	    $query = $this->db->query("SELECT sum(calls_today) as totcalls from vicidial_campaign_stats where calls_today > -1 and update_time between '$query_date 00:00:00' and '$query_date 23:59:59' $ul");
            $resultsu = $query->row();
	    $fresults = $resultsu->totcalls;
	    if ($fresults == NULL)
	    {
	        $fresults = 0;
	    }
	    return $fresults;
	}

       function go_total_agents_calls()
	{
	    $query_date =  date('Y-m-d');
	    $query = $this->db->query("select count(*) as qresult from vicidial_live_agents where status IN ('INCALL','CLOSER')");
            $resultsu = $query->row();
	    $fresults = $resultsu->qresult;
	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}


     function go_total_agents_callv()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $query = $this->db->query("select count(*) as qresult from vicidial_users");
	    }
	    else
	    {
	       $query = $this->db->query("select count(*) as qresult from vicidial_users where user_group='$groupId'");
	    }
	    $resultsu = $query->row();
	    $fresults = $resultsu->qresult;
	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

      function go_total_agents_call()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul=' and user_level != 4';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_users();
	       $ul = " and user IN ($stringv) and user_level != 4";
	    }
	    $query = $this->db->query("select count(*) as qresult from vicidial_live_agents where status IN ('INCALL','QUEUE','3-WAY','PARK') $ul");
            $resultsu = $query->row();
	    $fresults = $resultsu->qresult;
	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

       function go_total_agents_paused()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul=' and user_level != 4';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_users();
	       $ul = " and user IN ($stringv) and user_level != 4";
	    }
            $query = $this->db->query("select count(*) as qresult from vicidial_live_agents where status IN ('PAUSED') $ul");
            $resultsu = $query->row();
	    $fresults = $resultsu->qresult;
	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

       function go_total_agents_wait_calls()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul=' and user_level != 4';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_users();
	       $ul = " and user IN ($stringv) and user_level != 4";
	    }
            $query = $this->db->query("select count(*) as qresult from vicidial_live_agents where status IN ('READY','CLOSER') $ul");
            $resultsu = $query->row();
	    $fresults = $resultsu->qresult;
	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

       function go_total_agents_online()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul=' where user_level != 4';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_users();
	       $ul = " where user IN ($stringv) and user_level != 4";
	    }

            $query = $this->db->query("select count(*) as qresult from vicidial_live_agents $ul");
            $resultsu = $query->row();
	    $fresults = $resultsu->qresult;
	    if ($fresults == NULL)
	    {
	       $fresults = 0;
	    }
	    return $fresults;
	}

       function go_total_hopper_leads()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " where campaign_id IN ('$stringv') ";
	    }
             $query = $this->db->query("select count(*) qresult from vicidial_hopper $ul");
             $resultsu = $query->row();
	     $fresults = $resultsu->qresult;
	     if ($fresults == NULL)
	     {
	        $fresults = 0;
	     }
	     return $fresults;
	}

       function go_total_dialable_leads()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " where campaign_id IN ('$stringv') ";
	    }
             $stringv = $this->go_getall_allowed_campaigns();
             $query = $this->db->query("select sum(dialable_leads) as qresult from vicidial_campaign_stats $ul");
             $resultsu = $query->row();
	     $fresults = $resultsu->qresult;
	     if ($fresults == NULL)
	     {
	        $fresults = 0;
	     }
	     return $fresults;
	}

       function go_total_leads()
       {
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
  	  $query = $this->db->query("select count(*) qresult from vicidial_lists as vls,vicidial_list as vl where vl.list_id=vls.list_id and active='Y' $ul");
          $resultsu = $query->row();
	  $fresults = $resultsu->qresult;
	  if ($fresults == NULL)
	  {
	       $fresults = 0;
	  }
	  return $fresults;
	}

       function go_hopper_leads_warning()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " where campaign_id IN ('$stringv') ";
	    }
	  $query = $this->db->query("select count(*) as hoppercnt,campaign_id from vicidial_hopper $ul group by campaign_id having count(*) < 100");

	 $datacount = $query->num_rows();
	 $dataval   = $query->result();
	 $return['datacount']=$datacount;
	 $return['dataval']  =$dataval;
	 return $return;
      	}

       function go_hopper_leads_warning_zero()
	{
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and vl.campaign_id IN ('$stringv') ";
	    }
	  $query = ("SELECT COUNT(distinct vh.campaign_id) as mycnt, vl.campaign_id FROM vicidial_hopper as vh    RIGHT OUTER JOIN vicidial_campaigns as vl ON (vl.campaign_id=vh.campaign_id) where vl.active='Y' $ul GROUP BY vl.campaign_id HAVING COUNT(distinct vh.campaign_id)='0';");

	 $datacount = $query->num_rows();
	 $dataval   = $query->result();
	 $return['datacount']=$datacount;
	 $return['dataval']  =$dataval;
	 return $return;
	}


       function go_hopper_leads_warning_less_h()
	{
	    $groupId = $this->go_get_groupid();
		$NOW = date("Gi");
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and vl.campaign_id IN ('$stringv') ";
	    }
	  //$query = $this->db->query("SELECT COUNT(distinct vh.campaign_id) as mycnt, vl.campaign_id FROM vicidial_hopper as vh    RIGHT OUTER JOIN vicidial_campaigns as vl ON (vl.campaign_id=vh.campaign_id) $ul GROUP BY vl.campaign_id HAVING COUNT(distinct vh.campaign_id) < '100' ORDER BY mycnt DESC , campaign_id ASC;");

	  $query = $this->db->query("SELECT COUNT(vh.campaign_id) as mycnt, vl.campaign_id, vl.campaign_name FROM vicidial_hopper as vh RIGHT OUTER JOIN vicidial_campaigns as vl ON (vl.campaign_id=vh.campaign_id) RIGHT OUTER JOIN vicidial_call_times as vct ON (call_time_id=local_call_time) where vl.active='Y' AND ct_default_start < '$NOW' AND ct_default_stop > '$NOW' $ul GROUP BY vl.campaign_id HAVING COUNT(vh.campaign_id) < '100' ORDER BY mycnt DESC , campaign_id ASC;");

	 $datacount = $query->num_rows();
	 $dataval   = $query->result();
	 $return['datacount']=$datacount;
	 $return['dataval']  =$dataval;
	 return $return;
	}


        function go_get_hourly_in_sales()
        {
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
             $query_date =  date('Y-m-d');
	     $query = $this->db->query("select date_format(call_date, '%Y-%m-%d') as cdate,sum(if(date_format(call_date,'%H') = 09, 1, 0)) as 'Hour9',sum(if(date_format(call_date,'%H') = 10, 1, 0)) as 'Hour10',sum(if(date_format(call_date,'%H') = 11, 1, 0)) as 'Hour11',sum(if(date_format(call_date,'%H') = 12, 1, 0)) as 'Hour12',sum(if(date_format(call_date,'%H') = 13, 1, 0)) as 'Hour13',sum(if(date_format(call_date,'%H') = 14, 1, 0)) as 'Hour14',sum(if(date_format(call_date,'%H') = 15, 1, 0)) as 'Hour15',sum(if(date_format(call_date,'%H') = 16, 1, 0)) as 'Hour16',sum(if(date_format(call_date,'%H') = 17, 1, 0)) as 'Hour17',sum(if(date_format(call_date,'%H') = 18, 1, 0)) as 'Hour18',sum(if(date_format(call_date,'%H') = 19, 1, 0)) as 'Hour19',sum(if(date_format(call_date,'%H') = 20, 1, 0)) as 'Hour20',sum(if(date_format(call_date,'%H') = 21, 1, 0)) as 'Hour21' from vicidial_closer_log where status='SALE' and date_format(call_date, '%Y-%m-%d') = '$query_date' $ul group by cdate;");
	     $datacount = $query->num_rows();
	     $dataval   = $query->result();
	     $return['datacount']=$datacount;
	     $return['dataval']  =$dataval;
	     return $return;
        }

        function go_get_weekly_in_sales()
        {
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
	   $query_date =  date('Y-m-d');
	   $query = $this->db->query("select week(DATE_FORMAT( call_date, '%Y-%m-%d' )) as weekno, sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 0, 1, 0))  as 'Day0', sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 1, 1, 0))  as 'Day1', sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 2, 1, 0))  as 'Day2', sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 3, 1, 0))  as 'Day3', sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 4, 1, 0))  as 'Day4', sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 5, 1, 0))  as 'Day5', sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 6, 1, 0))  as 'Day6' from vicidial_closer_log where status='SALE' and week(call_date)=week('$query_date') $ul;");
	   $datacount = $query->num_rows();
	   $dataval   = $query->result();
	   $return['datacount']=$datacount;
	   $return['dataval']  =$dataval;
	   return $return;
	}

        function go_get_cmonth_daily_in_sales()
        {
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
	   $query_date =  date('Y-m-d');
	   $query = $this->db->query("select monthname(DATE_FORMAT( call_date, '%Y-%m-%d' )) as monthname, sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 1, 1, 0))  as 'Day1', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 2, 1, 0))  as 'Day2', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 3, 1, 0))  as 'Day3', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 4, 1, 0))  as 'Day4', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 5, 1, 0))  as 'Day5', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 6, 1, 0))  as 'Day6', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 7, 1, 0))  as 'Day7', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 8, 1, 0))  as 'Day8', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 9, 1, 0))  as 'Day9', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 10, 1, 0))  as 'Day10', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 11, 1, 0))  as 'Day11', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 12, 1, 0))  as 'Day12', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 13, 1, 0))  as 'Day13', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 14, 1, 0))  as 'Day14', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 15, 1, 0))  as 'Day15', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 16, 1, 0))  as 'Day16', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 17, 1, 0))  as 'Day17', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 18, 1, 0))  as 'Day18', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 19, 1, 0))  as 'Day19', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 20, 1, 0))  as 'Day20', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 21, 1, 0))  as 'Day21', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 22, 1, 0))  as 'Day22', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 23, 1, 0))  as 'Day23', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 24, 1, 0))  as 'Day24', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 25, 1, 0))  as 'Day25', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 26, 1, 0))  as 'Day26', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 27, 1, 0))  as 'Day27', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 28, 1, 0))  as 'Day28', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 29, 1, 0))  as 'Day29', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 30, 1, 0))  as 'Day30', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 31, 1, 0))  as 'Day31' from vicidial_closer_log where status='SALE' and monthname(call_date)=monthname('$query_date') $ul;");
	   $datacount = $query->num_rows();
	   $dataval   = $query->result();
	   $return['datacount']=$datacount;
	   $return['dataval']  =$dataval;
	   return $return;
	}

        function go_get_hourly_out_sales()
        {
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
            $query_date =  date('Y-m-d');
	    $query = $this->db->query("select date_format(call_date, '%Y-%m-%d') as cdate,sum(if(date_format(call_date,'%H') = 09, 1, 0)) as 'Hour9',sum(if(date_format(call_date,'%H') = 10, 1, 0)) as 'Hour10',sum(if(date_format(call_date,'%H') = 11, 1, 0)) as 'Hour11',sum(if(date_format(call_date,'%H') = 12, 1, 0)) as 'Hour12',sum(if(date_format(call_date,'%H') = 13, 1, 0)) as 'Hour13',sum(if(date_format(call_date,'%H') = 14, 1, 0)) as 'Hour14',sum(if(date_format(call_date,'%H') = 15, 1, 0)) as 'Hour15',sum(if(date_format(call_date,'%H') = 16, 1, 0)) as 'Hour16',sum(if(date_format(call_date,'%H') = 17, 1, 0)) as 'Hour17',sum(if(date_format(call_date,'%H') = 18, 1, 0)) as 'Hour18',sum(if(date_format(call_date,'%H') = 19, 1, 0)) as 'Hour19',sum(if(date_format(call_date,'%H') = 20, 1, 0)) as 'Hour20',sum(if(date_format(call_date,'%H') = 21, 1, 0)) as 'Hour21' from vicidial_log where status='SALE' and date_format(call_date, '%Y-%m-%d') = '$query_date' $ul group by cdate;");
	    $datacount = $query->num_rows();
	    $dataval   = $query->result();
	    $return['datacount']=$datacount;
	    $return['dataval']  =$dataval;
	    return $return;
        }

        function go_get_weekly_out_sales()
        {
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
	    $query_date =  date('Y-m-d');
	    $query = $this->db->query("select week(DATE_FORMAT( call_date, '%Y-%m-%d' )) as weekno, sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 0, 1, 0))  as 'Day0', sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 1, 1, 0))  as 'Day1', sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 2, 1, 0))  as 'Day2', sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 3, 1, 0))  as 'Day3', sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 4, 1, 0))  as 'Day4', sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 5, 1, 0))  as 'Day5', sum(if(weekday(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 6, 1, 0))  as 'Day6' from vicidial_log where status='SALE' and week(call_date)=week('$query_date') $ul;");
	    $datacount = $query->num_rows();
	    $dataval   = $query->result();
	    $return['datacount']=$datacount;
	    $return['dataval']  =$dataval;
	    return $return;
	}

        function go_get_monthly_out_sales()
        {
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       $stringv = $this->go_getall_allowed_campaigns();
	       $ul = " and campaign_id IN ('$stringv') ";
	    }
	   $query_date =  date('Y-m-d');
	   $query = $this->db->query("select monthname(DATE_FORMAT( call_date, '%Y-%m-%d' )) as monthname, sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 1, 1, 0))  as 'Day1', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 2, 1, 0))  as 'Day2', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 3, 1, 0))  as 'Day3', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 4, 1, 0))  as 'Day4', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 5, 1, 0))  as 'Day5', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 6, 1, 0))  as 'Day6', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 7, 1, 0))  as 'Day7', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 8, 1, 0))  as 'Day8', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 9, 1, 0))  as 'Day9', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 10, 1, 0))  as 'Day10', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 11, 1, 0))  as 'Day11', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 12, 1, 0))  as 'Day12', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 13, 1, 0))  as 'Day13', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 14, 1, 0))  as 'Day14', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 15, 1, 0))  as 'Day15', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 16, 1, 0))  as 'Day16', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 17, 1, 0))  as 'Day17', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 18, 1, 0))  as 'Day18', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 19, 1, 0))  as 'Day19', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 20, 1, 0))  as 'Day20', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 21, 1, 0))  as 'Day21', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 22, 1, 0))  as 'Day22', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 23, 1, 0))  as 'Day23', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 24, 1, 0))  as 'Day24', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 25, 1, 0))  as 'Day25', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 26, 1, 0))  as 'Day26', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 27, 1, 0))  as 'Day27', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 28, 1, 0))  as 'Day28', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 29, 1, 0))  as 'Day29', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 30, 1, 0))  as 'Day30', sum(if(DAYOFMONTH(DATE_FORMAT( call_date, '%Y-%m-%d' )) = 31, 1, 0))  as 'Day31' from vicidial_log where status='SALE' and monthname(call_date)=monthname('$query_date') $ul;");
	   $datacount = $query->num_rows();
	   $dataval   = $query->result();
	   $return['datacount']=$datacount;
	   $return['dataval']  =$dataval;
	   return $return;
	}


        function go_search_user()
        {
	   $queryString = $this->uri->segment(3);
	   $groupId = $this->go_get_groupid();
	   if ($this->commonhelper->checkIfTenant($groupId)) {
	     $ul = "AND user_group='$groupId'";
	   } else {
		 $ul = '';
	   }
	   #$query_date =  date('Y-m-d');
	   $this->db->cache_on();
	   $query = $this->db->query("SELECT user,full_name FROM vicidial_users_view WHERE user rlike '$queryString' $ul order by user asc LIMIT 10");
	   $datacount = $query->num_rows();
	   $dataval   = $query->result();
	   $return['datacount']=$datacount;
	   $return['dataval']  =$dataval;
	   return $return;
	}


        function go_search_list()
        {
	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       //$stringv = $this->go_getall_allowed_campaigns();
	       //$ul = " and campaign_id IN ('$stringv') ";
		   $ul = " and user_group='$groupId' ";
	    }


           $queryString = $this->uri->segment(3);
	   $query_date =  date('Y-m-d');
	   $this->db->cache_on();
	   //$query = $this->db->query("SELECT fullname FROM vicidial_list_view WHERE fullname rlike '$queryString' order by fullname asc  LIMIT 10");
	   $query = $this->db->query("
	    SELECT
			      fullname
			      ,phone
			      ,lead_id

                        FROM
                           goautodial_recordings_views
                        WHERE
			   fullname rlike '$queryString'

			   AND call_date BETWEEN '$query_date 00:00:00' AND '$query_date 23:59:59'
			   $ul

                        ORDER BY
                            call_date DESC;
                   ");
	   $datacount = $query->num_rows();
	   $dataval   = $query->result();
	   $return['datacount']=$datacount;
	   $return['dataval']  =$dataval;
	   return $return;
	}


        function go_search_phone()
        {

	    $groupId = $this->go_get_groupid();
	    //if ($groupId == 'ADMIN' || $groupId == 'admin')
		if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {
	       //$stringv = $this->go_getall_allowed_campaigns();
	       //$ul = " and campaign_id IN ('$stringv') ";
		   $ul = " and user_group='$groupId' ";
	    }

	   $queryString = $this->uri->segment(3);
	   $query_date =  date('Y-m-d');
	   $this->db->cache_on();
	   //$query = $this->db->query("SELECT phone_number FROM vicidial_list_view WHERE phone_number rlike '$queryString' order by phone_number asc LIMIT 10");
	   	   $query = $this->db->query("
	    SELECT
			      fullname
			      ,phone
			      ,lead_id

                        FROM
                           goautodial_recordings_views
                        WHERE
			   phone rlike '$queryString'

			   AND call_date BETWEEN '$query_date 00:00:00' AND '$query_date 23:59:59'

			   $ul
                        ORDER BY
                            call_date DESC;
                   ");

	   $datacount = $query->num_rows();
	   $dataval   = $query->result();
	   $return['datacount']=$datacount;
	   $return['dataval']  =$dataval;
	   return $return;
	}

        function go_search_clear()
        {
	$this->db->cache_delete_all();
	}


      function go_getall_allowed_campaigns($tenant=null)
      {
	    $groupId = $this->go_get_groupid();
		if (!is_null($tenant)) {
			$groupId = $tenant;
		}
	    $query_date =  date('Y-m-d');
	    $query = $this->db->query("select trim(allowed_campaigns) as qresult from vicidial_user_groups where user_group='$groupId'");
	    $resultsu = $query->row();

            if(count($resultsu) > 0){
	        $fresults = $resultsu->qresult;
	        $allowedCampaigns = explode(",",str_replace(" ",',',rtrim(ltrim(str_replace('-','',$fresults)))));

                $allAllowedCampaigns = implode("','",$allowedCampaigns);

            }else{
                $allAllowedCampaigns = '';
            }
	    return $allAllowedCampaigns;
      }


      function go_getall_inbound_groups()
      {
	    $groupId = $this->go_get_groupid();
	    $query_date =  date('Y-m-d');
	    $allInboundGroups = '';
	    $query = $this->db->query("select trim(allowed_campaigns) as qresult from vicidial_user_groups where user_group='$groupId'");
	    $resultsu = $query->row();

		if(count($resultsu) > 0){
			$fresults = $resultsu->qresult;
			$allowedCampaigns = explode(",",str_replace(" ",',',rtrim(ltrim(str_replace('-','',$fresults)))));

			$allAllowedCampaigns = implode("','",$allowedCampaigns);
		}else{
			$allAllowedCampaigns = '';
		}

		$query = $this->db->query("SELECT trim(closer_campaigns) AS qresult FROM vicidial_campaigns WHERE campaign_id IN ('$allAllowedCampaigns')");
	    $resultsu = $query->result();

		foreach ($resultsu as $row)
		{
			if($row->qresult != 'NULL' && $row->qresult != ''){
				$fresults = $row->qresult;
				$inboundGroups = explode(",",str_replace(" ",',',rtrim(ltrim(str_replace('-','',$fresults)))));

				$allInboundGroups .= implode("','",$inboundGroups)."','";
			}
		}

	    return $allInboundGroups;
      }

      function go_getall_allowed_users()
      {
	    $groupId = $this->go_get_groupid();
	    if ($groupId=='ADMIN' || $groupId=='admin')
	    {
	       $query = $this->db->query("select user as userg from vicidial_users");
	    }
	    else
	    {
	       $query = $this->db->query("select user as userg from vicidial_users where user_group='$groupId'");
	    }
	    $fresults = $query->result();
      	    $callfunc = $this->go_dashboard->go_total_agents_callv();
	    $v = $callfunc - 1;
	    $allowed_users='';
	    $i=0;
	    foreach($fresults as $item):
	        $users = $item->userg;
		if ($i==$v)
		{
		  $allowed_users .= "'" . $users. "'";
		}
		else
		{
		  $allowed_users .= "'" . $users. "'" . ',';
		}
	       $i++;
	    endforeach;

	    return $allowed_users;
      }
	  
	  function go_list_user_groups($all=null)
	  {
	         if (is_null($all) || strtolower($all) != "all")
			$listSQL = "WHERE user_group NOT IN ('ADMIN','AGENTS')";
		 $query = $this->db->query("SELECT user_group,group_name FROM vicidial_user_groups $listSQL ORDER BY user_group;");
		 
		 foreach ($query->result() as $row) {
			$result[$row->user_group] = "{$row->user_group} - {$row->group_name}";
		 }
		 
		 return $result;
	  }

      function go_get_groupid()
      {
      	 $userID = $this->session->userdata('user_name');
		 $query = $this->db->query("select user_group from vicidial_users where user='$userID'");
		 //$query = $this->db->query("select user from vicidial_users where user='$userID'");
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

      function go_get_userinfo()
      {
	    $userID = $this->session->userdata('user_name');
	    $query = $this->db->query("select user,pass,phone_login,phone_pass,active from vicidial_users where user='$userID';");
	    $resultsu = $query->row();
	    return $resultsu;
      }
	  
	  function go_show_me_more($type,$tenantID)
	  {
		 $showResult['type'] = $type;
		 
		 if ($type=="campaign") {
			if (!$this->commonhelper->checkIfTenant($tenantID))
			{
			   $ul='';
			}
			else
			{
			   $stringv = $this->go_getall_allowed_campaigns($tenantID);
			   $ul = " and vl.campaign_id IN ('$stringv') ";
			}
			
			$NOW = date("Gi");
			$query = $this->db->query("SELECT COUNT(vh.campaign_id) as mycnt, vl.campaign_id, vl.campaign_name, vl.local_call_time, vl.user_group FROM vicidial_hopper as vh RIGHT OUTER JOIN vicidial_campaigns as vl ON (vl.campaign_id=vh.campaign_id) RIGHT OUTER JOIN vicidial_call_times as vct ON (call_time_id=local_call_time) where vl.active='Y' AND ct_default_start < '$NOW' AND ct_default_stop > '$NOW' $ul GROUP BY vl.campaign_id HAVING COUNT(vh.campaign_id) < '100' ORDER BY mycnt DESC, vl.user_group, campaign_id ASC;");
			$showResult['list'] = $query->result();
		 } else {
			$group = $this->go_get_groupid();
			if (!$this->commonhelper->checkIfTenant($tenantID))
			   $ul = '';
			else
			   $ul = "AND vicidial_users.user_group='$tenantID'";
				
			$this->db->cache_on();
			$query = $this->db->query("SELECT user,pass,full_name,active,user_group FROM vicidial_users WHERE user_level='1' $ul AND user NOT IN ('VDAD','VDCL') ORDER BY user");
			$showResult['list']['users'] = $query->result();
			$query = $this->db->query("SELECT login,phones.pass,vicidial_users.active,vicidial_users.user_group FROM phones,vicidial_users WHERE login=REPLACE(vicidial_users.phone_login,'_','') AND login!='' $ul AND user_level='1' and vicidial_users.user NOT IN ('VDAD','VDCL') ORDER BY login");
			$showResult['list']['phones'] = $query->result();
		 }
		 
		 return $showResult;
	  }
	  
	 function go_check_status()
	 {
	    $query = $this->db->query("SELECT server_id,server_description,server_ip,active,sysload,channels_total,cpu_idle_percent,disk_usage from servers order by server_id;");
	    $serverCnt = $query->num_rows();
	    $fields = $query->field_data();
	    
	    foreach ($query->result_array() as $key => $row)
	    {
	       foreach ($fields as $field)
	       {
		  $serverInfo[$key][$field->name] = $row[$field->name];
	       }
	    }
	    return $serverInfo;
	 }

//      function go_check_if_new()
//      {
//	    $groupId = $this->go_get_groupid();
//	    $query = $this->goautodial->query("select new_signup from go_login_type where account_num='$groupId';");
//	    $resultsu = $query->row()->new_signup;
//	    return $resultsu;
//      }

	function go_get_os($userAgent) {
	// Create list of operating systems with operating system name as array key
		$oses = array (
			'iPhone' => '(iPhone)',
			'Windows 3.11' => 'Win16',
			'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)', // Use regular expressions as value to identify operating system
			'Windows 98' => '(Windows 98)|(Win98)',
			'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
			'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
			'Windows 2003' => '(Windows NT 5.2)',
			'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
			'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
			'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
			'Windows ME' => 'Windows ME',
			'Open BSD'=>'OpenBSD',
			'Sun OS'=>'SunOS',
			'Linux'=>'(Linux)|(X11)',
			'Safari' => '(Safari)',
			'Macintosh'=>'(Mac_PowerPC)|(Macintosh)',
			'QNX'=>'QNX',
			'BeOS'=>'BeOS',
			'OS/2'=>'OS/2',
			'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
		);

		foreach($oses as $os=>$pattern){ // Loop through $oses array
		// Use regular expressions to check operating system type
			if(eregi($pattern, $userAgent)) { // Check if a value in $oses array matches current user agent.
				return $os; // Operating system was matched so return $oses key
			}
		}
		return 'Unknown'; // Cannot find operating system so return Unknown
	}

}
