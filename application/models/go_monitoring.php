<?php
########################################################################################################
####  Name:             	go_monitoring.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_monitoring extends Model {
	
	function __construct()
	{
	    parent::Model();
	    $this->db = $this->load->database('dialerdb', true);
	    $this->godb = $this->load->database('goautodialdb', true);
	}
	
	function go_update_realtime()
	{
	    $groupId = $this->go_get_groupid();
	    $isExpanded = $this->uri->segment(3);
	    $orderby = $this->uri->segment(4);
	    $type = $this->uri->segment(5);
	    $sCampaign = $this->uri->segment(6);
	    $user_group = $this->uri->segment(7);
	    $STARTtime = date("U");
	    $agent_incall=0;
	    $agent_ready=0;
	    $agent_paused=0;
	    $agent_total=0;
	    if (!isset($orderby) || $orderby=='') {$orderby='timeup';}
	    if (!isset($refresh) || $refresh=="") { $refresh="5"; }
	    if (!isset($group) || $group=="") { $group="ALL-ACTIVE"; }

	    if (!$this->commonhelper->checkIfTenant($groupId))
	    {
			if (strlen($user_group) < 1 || strtolower($user_group) == "all") {
			    $ul=' AND vicidial_live_agents.user_level != 4';
			} else {
				$ul = "AND user_group='$user_group' AND vicidial_live_agents.user_level != 4";  
			}
	    }
	    else
	    {  
			$ul = "AND user_group='$groupId' AND vicidial_live_agents.user_level != 4";         
	    }
		
		if (strtolower($sCampaign) != 'all')
		{
			$sCamp = "AND campaign_id='$sCampaign'";
		}
		else
		{
			$sCamp = '';
		}

		if ($type=='agents')
		{
			if ($orderby=='timeup') {$orderSQL='status,last_call_time';}
			if ($orderby=='timedown') {$orderSQL='status desc,last_call_time desc';}
			if ($orderby=='campaignup') {$orderSQL='campaign_id,status,last_call_time';}
			if ($orderby=='campaigndown') {$orderSQL='campaign_id desc,status desc,last_call_time desc';}
			if ($orderby=='groupup') {$orderSQL='user_group,status,last_call_time';}
			if ($orderby=='groupdown') {$orderSQL='user_group desc,status desc,last_call_time desc';}
			if ($UidORname > 0) {
				if ($orderby=='userup') {$orderSQL='full_name,status,last_call_time';}
				if ($orderby=='userdown') {$orderSQL='full_name desc,status desc,last_call_time desc';}
			} else {
				if ($orderby=='userup') {$orderSQL='vicidial_live_agents.user';}
				if ($orderby=='userdown') {$orderSQL='vicidial_live_agents.user desc';}
			}
			
			$mQuery = $this->db->query("select extension as 'station',vicidial_live_agents.user as 'user',vicidial_users.user_group as 'user_group',conf_exten as 'sessionid',status,comments,server_ip,call_server_ip,UNIX_TIMESTAMP(last_call_time) as 'mm:ss',UNIX_TIMESTAMP(last_call_finish),campaign_id as 'campaign',UNIX_TIMESTAMP(last_state_change) as 'last_state_change',lead_id,agent_log_id,vicidial_live_agents.callerid as 'callerid' from vicidial_live_agents,vicidial_users WHERE vicidial_live_agents.user=vicidial_users.user $ul $sCamp ORDER BY $orderSQL LIMIT 2000");
			$totalRecords = $mQuery->num_rows();
			$colNum = $mQuery->num_fields();
	
			### QUERY DB COLUMN NAMES FOR HEADERS ###
			$realtimeHTML = "<thead><tr>";
			foreach ($mQuery->list_fields() as $i => $field) 
			{
				$fields[$i] = $field;
				switch($i) {
					case 1:
					if ($orderby=="userup") {
						$sortorder="userdown";
					} else {
						$sortorder="userup";
					}
					break;
					case 2:
					if ($orderby=="groupup") {
						$sortorder="groupdown";
					} else {
						$sortorder="groupup";
					}
					break;
					case 8:
					if ($orderby=="timeup") {
						$sortorder="timedown";
					} else {
						$sortorder="timeup";
					}
					break;
					case 10:
					if ($orderby=="campaignup") {
						$sortorder="campaigndown";
					} else {
						$sortorder="campaignup";
					}
					break;
				}
		
				$sortLinkA = "";
				$sortLinkB = "";
				
				if (!eregi("5$|9$|11$|12$", $i)) {
					if ($this->userLevel < 7 && eregi("^0$|1$|2$|3$|6$|7$|8$", $i))
					{
						// Do Nothing...
					}
					else
					{
						$cursorType = "cursor:default;";
						$onClickField = "";
						if ($field=="campaign" || $field=="user" || $field=="mm:ss")
						{
							$cursorType = "cursor:pointer;";
							$onClickField = "onClick=\"updateOrder('$sortorder','agents');\"";
						}
						$realtimeHTML .= "<th nowrap style=\"font-weight:normal;font-style:italic;font-family:ÒLucida Sans UnicodeÓ, Lucida Grande, sans-serif;color:#777;border-bottom:#ececec 1px solid;padding:3px 0px 9px 0px;text-align:center;$cursorType\" $onClickField>&nbsp;".ucwords(eregi_replace("_", " ", $field))."&nbsp;</th>";
					}
				}
				if (eregi("^1$",$i)) {
					$onClickField = "onClick=\"updateOrder('$sortorder','agents');\"";
					$realtimeHTML .= "<th nowrap style=\"font-weight:normal;font-style:italic;font-family:ÒLucida Sans UnicodeÓ, Lucida Grande, sans-serif;color:#777;border-bottom:#ececec 1px solid;padding:3px 0px 9px 0px;text-align:center;cursor:pointer;\" $onClickField>&nbsp;Agent&nbsp;</th>";
				}
				if (eregi("^2$",$i) && !$this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) {
					$onClickField = "onClick=\"updateOrder('$sortorder','agents');\"";
					$realtimeHTML .= "<th nowrap style=\"font-weight:normal;font-style:italic;font-family:ÒLucida Sans UnicodeÓ, Lucida Grande, sans-serif;color:#777;border-bottom:#ececec 1px solid;padding:3px 0px 9px 0px;text-align:center;cursor:pointer;\" $onClickField>&nbsp;User Group&nbsp;</th>";
				}
				if (eregi("8$",$i)) {
					$onClickField = "onClick=\"updateOrder('$sortorder','agents');\"";
					$realtimeHTML .= "<th nowrap style=\"font-weight:normal;font-style:italic;font-family:ÒLucida Sans UnicodeÓ, Lucida Grande, sans-serif;color:#777;border-bottom:#ececec 1px solid;padding:3px 0px 9px 0px;text-align:center;cursor:pointer;\" $onClickField>&nbsp;MM:SS&nbsp;</th>";
				}
				if (eregi("5$",$i)) {
					$realtimeHTML .= "<th nowrap style=\"font-weight:normal;font-style:italic;font-family:ÒLucida Sans UnicodeÓ, Lucida Grande, sans-serif;color:#777;border-bottom:#ececec 1px solid;padding:3px 0px 9px 0px;text-align:center;cursor:default;\" $onClickField>&nbsp;Cust Phone&nbsp;</th>";
				}
			}
	
			$realtimeHTML .= "</tr></thead>";
	
			if ($totalRecords > 0)
			{
				$realtimeHTML .= "<tbody>";
				$no_agents_logged = 0;
				foreach ($mQuery->result_array() as $i => $row)
				{
					$station =			$row['station'];
					$user =				$row['user'];
					$user_group =			$row['user_group'];
					$sessionid =			$row['sessionid'];
					$status = 			$row['status'];
					$call_type =			$row['comments'];
					$server_ip =			$row['server_ip'];
					$call_server_ip =		$row['call_server_ip'];
					$last_call_time =		$row['mm:ss'];
					$last_call_finish =		$row['last_call_finish'];
					$campaign_id =			$row['campaign'];
					$last_state_change =		$row['last_state_change'];
					$lead_id =			$row['lead_id'];
					$agent_log_id =			$row['agent_log_id'];
					$caller_id =			$row['callerid'];
					$pausecode =			"";
					
					if (eregi("R/",$station)) 
						{
						$protocol = 'EXTERNAL';
						$dialplan = eregi_replace('R/',"",$station);
						$dialplan = eregi_replace("\@.*",'',$dialplan);
						$exten = "dialplan_number='$dialplan'";
						}
					if (eregi("Local/",$station)) 
						{
						$protocol = 'EXTERNAL';
						$dialplan = eregi_replace('Local/',"",$station);
						$dialplan = eregi_replace("\@.*",'',$dialplan);
						$exten = "dialplan_number='$dialplan'";
						}
					if (eregi('SIP/',$station)) 
						{
						$protocol = 'SIP';
						$dialplan = eregi_replace('SIP/',"",$station);
						$dialplan = eregi_replace("-.*",'',$dialplan);
						$exten = "extension='$dialplan'";
						}
					if (eregi('IAX2/',$station)) 
						{
						$protocol = 'IAX2';
						$dialplan = eregi_replace('IAX2/',"",$station);
						$dialplan = eregi_replace("-.*",'',$dialplan);
						$exten = "extension='$dialplan'";
						}
					if (eregi('Zap/',$station)) 
						{
						$protocol = 'Zap';
						$dialplan = eregi_replace('Zap/',"",$station);
						$exten = "extension='$dialplan'";
						}
					if (eregi('DAHDI/',$station)) 
						{
						$protocol = 'Zap';
						$dialplan = eregi_replace('DAHDI/',"",$station);
						$exten = "extension='$dialplan'";
						}
					
					$stmt = $this->db->query("SELECT full_name FROM vicidial_users WHERE user='$user'");
					$row['full_name'] = $stmt->row()->full_name;
					
					$stmt = $this->db->query("SELECT campaign_name FROM vicidial_campaigns WHERE campaign_id='$campaign_id'");
					$row['campaign_name'] = $stmt->row()->campaign_name;
					
					$stmt = $this->db->query("SELECT group_name FROM vicidial_user_groups WHERE user_group='$user_group'");
					$row['group_name'] = $stmt->row()->group_name;
	
					$query_phone = $this->db->query("select phone_number from vicidial_list where lead_id='$lead_id' limit 1;");
					$cust = $query_phone->row_array();
					
					if (!isset($cust['phone_number']) || $cust['phone_number']=="") { $cust['phone_number']="---"; }
					if (!isset($call_server_ip) || $call_server_ip=="") { $call_server_ip="---"; }
					

					### 3-WAY Check ###
					if ($lead_id!=0) 
						{
						$threewaystmt="select UNIX_TIMESTAMP(last_call_time) as mostrecent from vicidial_live_agents where lead_id='$lead_id' and status='INCALL' order by UNIX_TIMESTAMP(last_call_time) desc";
						$threewayrslt=$this->db->query($threewaystmt);
						if ($threewayrslt->num_rows()>1) 
							{
							$status="3-WAY";
							$call_mostrecent=$threewayrslt->row()->mostrecent;
							}
						}
					### END 3-WAY Check ###
	
		//		    if (eregi("1$|3$|5$|7$|9$", $i))
		//			    {$bgcolor='#F5FAFA';} 
		//		    else
		//			    {$bgcolor='#edf3f3';}
		
					$callerids = '';
					$query = $this->db->query("select callerid,lead_id,phone_number from vicidial_auto_calls;");
					if ($query->num_rows() > 0)
					{
						foreach ($query->result() as $rlist)
						{
							$callerids .=	"{$rlist->callerid}|";
						}
					}
	
					if (eregi("INCALL",$status)) 
					{
						$query = $this->db->query("select * from parked_channels where channel_group='$caller_id';");
						$parked_channel = $query->num_rows();
			
						if ($parked_channel > 0)
						{
							$status = 'PARK';
						}
						else
						{
							if (!ereg("$caller_id\|",$callerids))
							{
								$last_call_time=$last_state_change;
			
								$status = 'DEAD';
							}
						}
						
						if ( (eregi("AUTO",$call_type)) or (strlen($call_type)<1) )
							{$CM='[A]';}
						else
						{
							if (eregi("INBOUND",$call_type))
							{$CM='[I]';}
							else
							{$CM='[M]';}
						}
					}
					else {$CM='';}
					
					if (eregi("READY|PAUSED|CLOSER",$status))
					{
						$last_call_time=$last_state_change;
						if ($lead_id>0) { $status="DISPO"; }
					}
					if (!eregi("INCALL|QUEUE|PARK|3-WAY",$status))
						{$call_time_S = ($STARTtime - $last_state_change);}
					else if (eregi("3-WAY",$status))
						{$call_time_S = ($STARTtime - $call_mostrecent);}
					else
						{$call_time_S = ($STARTtime - $last_call_time);}
	
					$call_time_M = ($call_time_S / 60);
					$call_time_M = round($call_time_M, 2);
					$call_time_M_int = intval("$call_time_M");
					$call_time_SEC = ($call_time_M - $call_time_M_int);
					$call_time_SEC = ($call_time_SEC * 60);
					$call_time_SEC = round($call_time_SEC, 0);
					if ($call_time_SEC < 10) {$call_time_SEC = "0$call_time_SEC";}
					$call_time_MS = "$call_time_M_int:$call_time_SEC";
			//		$call_time_MS =		sprintf("%7s", $call_time_MS);
					$G = '';		$EG = '';
					//echo $call_time_S."<br>".$row[8]."<br>".$row[7];
	
					if ( ($status=='INCALL') or ($status=='PARK') or ($status=='3-WAY') )
						{
						$bgcolor="#DEEEC3";
						$color="black";
						if ($call_time_S >= 10) {$bgcolor="#DEEEC3";$color="black";}
						if ($call_time_M_int >= 1) {$bgcolor="#DEEEC3";$color="black";}
						if ($call_time_M_int >= 5) {$bgcolor="#DEEEC3";$color="black";}
				#		if ($call_time_M_int >= 10) {$G='<SPAN class="purple"><B>'; $EG='</B></SPAN>';}
						}
						
					if ($status=="PAUSED") 
						{
						$query = $this->db->query("SELECT agent_pause_codes_active FROM vicidial_campaigns WHERE campaign_id='$campaign_id'");
						if (!ereg("N",$query->row()->agent_pause_codes_active))
							{
							$query=$this->db->query("select sub_status from vicidial_agent_log where agent_log_id >= \"$agent_log_id\" and user='$user' order by agent_log_id desc limit 1;");
							$pausecode = $query->row()->sub_status;
							if (strlen($pausecode)>0 && $pausecode != 'NULL')
								$pausecode = "[$pausecode]";
							}
						else
							{$pausecode='';}
						
						$bgcolor="#F9F57A";
						$color="black";
						if ($call_time_M_int >= 3600) 
							{$i++; continue;} 
						else
							{
							$agent_paused++;  $agent_total++;
							if ($call_time_S >= 10) {$bgcolor="#F9F57A";$color="black";}
							if ($call_time_M_int >= 1) {$bgcolor="#F9F57A";$color="black";}
							if ($call_time_M_int >= 5) {$bgcolor="#F9F57A";$color="black";}
							}
						}
	
					if ( (eregi("READY",$status)) or (eregi("CLOSER",$status)) ) 
						{
						$bgcolor="#BAEE62";
						$color="black";
						if ($call_time_M_int >= 3) {$bgcolor="#FF8000";$color="black";}
						}
	
					if ( eregi("DEAD",$status) ) 
						{
						$agent_dead++;
						$agent_total++;
						$bgcolor="black";
						$color="white";
						}
	
					if ( eregi("DISPO",$status) ) 
						{
						$bgcolor="white";
						$color="#333";
						}
	
					if ( (eregi("INCALL",$status)) or (eregi("QUEUE",$status))  or (eregi("3-WAY",$status)) or (eregi("PARK",$status))) {$agent_incall++;  $agent_total++;}
					if ( (eregi("READY",$status)) or (eregi("CLOSER",$status)) ) {$agent_ready++;  $agent_total++;}
	
	
					if($i > 14) {
						if (!$isExpanded)
						{
							$realtimeHTML .= "<tr id=\"trid\" style=\"display: none;color:#333;\" align=center>\n";
		// 					$realtimeHTML .= "<tr id=\"trid\" style=\"display: none;background-color:$bgcolor;color:$color;\" align=center>\n";
						}
						else
						{
							$realtimeHTML .= "<tr id=\"trid\" style=\"color:#333;\" align=center>\n";
		// 					$realtimeHTML .= "<tr id=\"trid\" style=\"background-color:$bgcolor;color:$color;\" align=center>\n";
						}
					}else{
						$realtimeHTML .= "<tr style=\"color:#333\" align=center>\n";
		// 		    	$realtimeHTML .= "<tr style=\"background-color:$bgcolor;color:$color;\" align=center>\n";
					}
					
					foreach ($fields as $c => $field)
					{
						if (!eregi("3$|4$|5$|7$|8$|9$|11$|12$", $c)) {
							if ($this->userLevel < 9 && eregi("^0$|6$", $c))
							{
								// Do Nothing...
							}
							else
							{
								$value = $row[$field];
								if ($field == 'user')
								{
									$value = $row['full_name'];
									$tvalue = (strlen($value) >30) ? substr($value,0,30)."..." : $value;
									$bvalue = "style=\"font-size:11px;cursor:pointer;\" class=\"toolTip\" title=\"Click to listen or barge:<br />$value\">&nbsp;<span id=\"sendMonitor\" onclick=\"sendMonitor('".substr($value,0,23)."','$sessionid','$server_ip');\"";
	
									$realtimeHTML .= "<td nowrap $bvalue>$tvalue</span>&nbsp;</td>\n";
								}
									
								if ($field == 'campaign')
								{
									$value = $row['campaign_name'];
									$tvalue = (strlen($value) >30) ? substr($value,0,30)."..." : $value;
									$bvalue = "style=\"font-size:11px;\">&nbsp;<span style=\"cursor:pointer\" onclick=\"modify('$campaign_id')\"";
	
									$realtimeHTML .= "<td nowrap $bvalue>$tvalue</span>&nbsp;</td>\n";
								}
									
								if ($field == 'user_group' && !$this->commonhelper->checkIfTenant($this->session->userdata('user_group')))
								{
									$value = $row['group_name'];
									$tvalue = (strlen($value) >30) ? substr($value,0,30)."..." : $value;
									$bvalue = "style=\"font-size:11px;\">&nbsp;<span";
	
									$realtimeHTML .= "<td nowrap $bvalue>$tvalue</span>&nbsp;</td>\n";
								}
							}
						}
						if ($this->userLevel > 7 && eregi("3$",$c)) {
							$monitor_call=(isset($monitor_phone) && strlen($monitor_phone)>0) ? " [<a href=\"#\" onclick=\"send_monitor('$sessionid','$server_ip','MONITOR');\" title=\"Monitor Call\">M</a>] [<a href=\"#\" onclick=\"send_monitor('$sessionid','$server_ip','BARGE');\" title=\"Barge Call\">B</a>]" : "";
							$realtimeHTML .= "<td nowrap style=\"font-size:11px;\">&nbsp;$row[$field]&nbsp;</td>\n";
						}
						if (eregi("4$",$c)) {
							if (eregi("INCALL|QUEUE|PARK|3-WAY|READY|PAUSED|CLOSER|DEAD",$row[$field]))
							{
								$output_status = "$status $CM $pausecode";
							} else {
								if (strlen($row[$field])>0)
									$output_status = $row[$field];
								else
									$output_status = "---";
							}
							$realtimeHTML .= "<td nowrap style=\"font-size:11px;\">&nbsp;$output_status&nbsp;</td>\n";
						}
						if (eregi("5$",$c)) {
							$realtimeHTML .= "<td nowrap style=\"font-size:11px;\">&nbsp;".$cust['phone_number']."&nbsp;</td>\n";
						}
						if ($this->userLevel > 8 && eregi("7$",$c)) {
							$realtimeHTML .= "<td nowrap style=\"font-size:11px;\">&nbsp;$call_server_ip&nbsp;</td>\n";
						}
						if (eregi("8$",$c)) {
							$realtimeHTML .= "<td nowrap style=\"font-size:11px;background-color:$bgcolor;color:$color;\" >&nbsp;$call_time_MS&nbsp;</td>\n";
						}
					}
					$realtimeHTML .= "</tr>";
				}
				$realtimeHTML .= "</tbody>\n";
			}
			else
			{
				$no_agents_logged = 1;
			}
		} else {
			if ($orderby=='timeup' || $orderby=='statusup') {$orderSQL='status,call_time';}
			if ($orderby=='timedown' || $orderby=='statusdown') {$orderSQL='status desc,call_time desc';}
			if ($orderby=='campaignup') {$orderSQL='vac.campaign_id,status,call_time';}
			if ($orderby=='campaigndown') {$orderSQL='vac.campaign_id desc,status desc,call_time desc';}
			if ($orderby=='typeup') {$orderSQL='call_type,status,call_time';}
			if ($orderby=='typedown') {$orderSQL='call_type desc,status desc,call_time desc';}
			if ($orderby=='phoneup') {$orderSQL='phone_number,status,call_time';}
			if ($orderby=='phonedown') {$orderSQL='phone_number desc,status desc,call_time desc';}
			if ($orderby=='groupup') {$orderSQL='user_group,call_type,status,call_time';}
			if ($orderby=='groupdown') {$orderSQL='user_group desc,call_type desc,status desc,call_time desc';}
			
			// Current Active Calls / Calls Ringing / Waiting For Agents
			$stringv = $this->go_get_allowed_campaigns($groupId);
			//var_dump("$stringv");
			if (isset($ul)) {$campSQL="and campaign_id IN ('$stringv')";}
			$query = $this->db->query("select * from vicidial_campaigns where campaign_allow_inbound='Y' $campSQL;");
			$campaign_allow_inbound = $query->num_rows();
	
			if (!$this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) {
				$isAdmin = ",IF (call_type='IN',vig.user_group,vc.user_group) as 'user_group'";
			}
	
			if ($campaign_allow_inbound > 0)
				{
				$stmt="select closer_campaigns from vicidial_campaigns where active='Y' and campaign_id IN ('$stringv');";
				$query = $this->db->query($stmt);
				//$row = $query->row();
				foreach ($query->result() as $row)
				{
					$closer_campaigns = preg_replace("/^ | -$/","",$row->closer_campaigns);
					if ($closer_campaigns != '0' && $closer_campaigns != '')
					{
						$closer_campaigns = preg_replace("/ /","','",$closer_campaigns);
						$closer_campaignsX .= "$closer_campaigns','";
					}
				}
				$closer_campaignsX = preg_replace("/','$/","",$closer_campaignsX);
				$closer_campaignsX = "'$closer_campaignsX'";
				if ($this->commonhelper->checkIfTenant($groupId)) {
					$closer_campSQL = "and vac.campaign_id IN($closer_campaignsX)";
					$out_campSQL = "vac.campaign_id IN ('$stringv') and ";
				}
	
				$stmt="select status,phone_number,call_type,UNIX_TIMESTAMP(call_time) as 'call_time',vac.campaign_id{$isAdmin} from vicidial_auto_calls as vac, vicidial_campaigns as vc, vicidial_inbound_groups as vig where ( (call_type='IN' $closer_campSQL) or ($out_campSQL call_type rlike 'OUT') ) AND (vac.campaign_id=vc.campaign_id OR vac.campaign_id=vig.group_id OR vac.campaign_id='CALLMENU') GROUP BY status,call_type,phone_number ORDER BY $orderSQL LIMIT 2000;";
				//$stmt="select status,phone_number,call_type,UNIX_TIMESTAMP(call_time) as 'call_time',vac.campaign_id{$isAdmin} from vicidial_auto_calls as vac, vicidial_campaigns as vc where ( (call_type='IN' and vac.campaign_id IN($closer_campaignsX)) or (vac.campaign_id IN ('$stringv') and call_type rlike 'OUT') ) AND vac.campaign_id=vc.campaign_id ORDER BY $orderSQL LIMIT 2000;";
				}
			else
				{
	
				$stmt="select status,phone_number,call_type,UNIX_TIMESTAMP(call_time) as 'call_time',vac.campaign_id{$isAdmin} from vicidial_auto_calls as vac, vicidial_campaigns as vc, vicidial_inbound_groups as vig where vac.campaign_id IN ('$stringv') AND (vac.campaign_id=vc.campaign_id OR vac.campaign_id=vig.group_id) GROUP BY status,call_type,phone_number ORDER BY $orderSQL LIMIT 2000;";
				//$stmt="select status,phone_number,call_type,UNIX_TIMESTAMP(call_time) as 'call_time',vac.campaign_id{$isAdmin} from vicidial_auto_calls as vac, vicidial_campaigns as vc where vac.campaign_id IN ('$stringv') AND vac.campaign_id=vc.campaign_id ORDER BY $orderSQL LIMIT 2000;";
				}

			$query = $this->db->query($stmt);
			$parked_to_print = $query->num_rows();

			if ($parked_to_print > 0)
			{
				### QUERY DB COLUMN NAMES FOR HEADERS ###
				$realtimeHTML = "<thead><tr>";
				foreach ($query->list_fields() as $i => $field) 
				{
					$fields[$i] = $field;
					switch($i) {
						case 0:
						if ($orderby=="statusup") {
							$sortorder="statusdown";
						} else {
							$sortorder="statusup";
						}
						break;
						case 1:
						if ($orderby=="phoneup") {
							$sortorder="phonedown";
						} else {
							$sortorder="phoneup";
						}
						break;
						case 2:
						if ($orderby=="typeup") {
							$sortorder="typedown";
						} else {
							$sortorder="typeup";
						}
						break;
						case 3:
						if ($orderby=="timeup") {
							$sortorder="timedown";
						} else {
							$sortorder="timeup";
						}
						break;
						case 4:
						if ($orderby=="campaignup") {
							$sortorder="campaigndown";
						} else {
							$sortorder="campaignup";
						}
						break;
						case 5:
						if ($orderby=="groupup") {
							$sortorder="groupdown";
						} else {
							$sortorder="groupup";
						}
						break;
					}
			
					$sortLinkA = "";
					$sortLinkB = "";

					if($field=="campaign_id") {
							$field = "Campaign / InGroup";
					}

					if($field=="user_group") {
							$field = "User Group";
					}
					
					$realtimeHTML .= "<th nowrap style=\"font-weight:normal;font-style:italic;font-family:ÒLucida Sans UnicodeÓ, Lucida Grande, sans-serif;color:#777;border-bottom:#ececec 1px solid;padding:3px 0px 9px 0px;text-align:center;cursor:pointer;\" onClick=\"updateOrder('$sortorder','calls');\">&nbsp;".ucwords(eregi_replace("_", " ", $field))."&nbsp;</th>";
				}
		
				$realtimeHTML .= "</tr></thead>";
				
				
				$i=0;
				$active_calls=0;
				$calls_ringing=0;
				$calls_waiting=0;
				$no_live_calls=0;
				$realtimeHTML .= "<tbody>";
				foreach ($query->result() as $i => $row)
				{
					if($i > 14) {
						if (!$isExpanded)
						{
							$realtimeHTML .= "<tr id=\"trid\" style=\"display: none;color:#333;\" align=center>\n";
		// 					$realtimeHTML .= "<tr id=\"trid\" style=\"display: none;background-color:$bgcolor;color:$color;\" align=center>\n";
						}
						else
						{
							$realtimeHTML .= "<tr id=\"trid\" style=\"color:#333;\" align=center>\n";
		// 					$realtimeHTML .= "<tr id=\"trid\" style=\"background-color:$bgcolor;color:$color;\" align=center>\n";
						}
					}else{
						$realtimeHTML .= "<tr style=\"color:#333\" align=center>\n";
		// 		    	$realtimeHTML .= "<tr style=\"background-color:$bgcolor;color:$color;\" align=center>\n";
					}
					
					$call_time_S = ($STARTtime - $row->call_time);
	
					$call_time_M = ($call_time_S / 60);
					$call_time_M = round($call_time_M, 2);
					$call_time_M_int = intval("$call_time_M");
					$call_time_SEC = ($call_time_M - $call_time_M_int);
					$call_time_SEC = ($call_time_SEC * 60);
					$call_time_SEC = round($call_time_SEC, 0);
					if ($call_time_SEC < 10) {$call_time_SEC = "0$call_time_SEC";}
					$call_time_MS = "$call_time_M_int:$call_time_SEC";
					
					if ($row->call_type == "IN")
					{
						$call_typeS = "INBOUND";
						$bgcolor="#F9F57A";
						$color="black";
					}
					else
					{
						$call_typeS = "OUTBOUND";
						$bgcolor="#BAEE62";
						$color="black";
					}
					
					$stmt = $this->db->query("SELECT campaign_name FROM vicidial_campaigns WHERE campaign_id='{$row->campaign_id}'");
					$campaign_name = $stmt->row()->campaign_name;
					
					if (!$this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) {
						$stmt = $this->db->query("SELECT group_name FROM vicidial_user_groups WHERE user_group='{$row->user_group}'");
						$group_name = $stmt->row()->group_name;
					}
		
					if ($call_typeS == "INBOUND")
						$campaign_name = $row->campaign_id;
		
					$realtimeHTML .= "<td nowrap style=\"font-size:11px;\">&nbsp;{$row->status}&nbsp;</td>";
					$realtimeHTML .= "<td nowrap style=\"font-size:11px;\">&nbsp;{$row->phone_number}&nbsp;</td>";
					$realtimeHTML .= "<td nowrap style=\"font-size:11px;background-color:$bgcolor;color:$color;\">&nbsp;$call_typeS&nbsp;</td>";
					$realtimeHTML .= "<td nowrap style=\"font-size:11px;\">&nbsp;$call_time_MS&nbsp;</td>";
					$realtimeHTML .= "<td nowrap style=\"font-size:11px;\">&nbsp;$campaign_name&nbsp;</td>";
					if (!$this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) {
						$realtimeHTML .= "<td nowrap style=\"font-size:11px;\">&nbsp;$group_name&nbsp;</td>";
					}
		
					if (eregi("LIVE",$row->status)) 
					{$calls_waiting++;}
					else
					{
					if (eregi("CLOSER",$row->status)) 
						{$nothing=1;}
					else 
						{$calls_ringing++;}
					}
					$realtimeHTML .= "</tr>";
					$active_calls++;
					$i++;
				}
				$realtimeHTML .= "</tbody>";
			}
			else
			{
				$no_live_calls = 1;
			}
		}

	    $return['html'] = $realtimeHTML;
	    $return['no_live_calls'] = $no_live_calls;
	    $return['no_agents_logged'] = $no_agents_logged;
	    $return['active_calls'] = $active_calls;
	    $return['agent_total'] = $agent_total;
	    $return['agent_incall'] = $agent_incall;
	    $return['agent_paused'] = $agent_paused;
	    $return['agent_ready'] = $agent_ready;
	    $return['agent_waiting'] = $agent_waiting;
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
	
	function go_get_allowed_campaigns($groupId,$raw=false)
	{
	    if (!$this->commonhelper->checkIfTenant($groupId))
	    {
	       $ul='';
	    }
	    else
	    {  
	       $ul = "WHERE user_group='$groupId'";         
	    }
		
		if (!$raw) {
			$query = $this->db->query("SELECT REPLACE(allowed_campaigns,'-','') AS allowed_campaigns FROM vicidial_user_groups $ul");
			
			foreach ($query->result() as $row)
			{
				$allowed_campaigns .= $row->allowed_campaigns;
			}
			$allowed_campaigns = str_replace(' ',"','",trim($allowed_campaigns));
		}
		else
		{
			$query = $this->db->query("SELECT allowed_campaigns FROM vicidial_user_groups $ul");
			$row = $query->row();
		
			$allowed_campaigns = $row->allowed_campaigns;
		}
		return $allowed_campaigns;
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

	function go_get_campaigns()
	{
		$filterSQL = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "WHERE user_group='".$this->session->userdata('user_group')."'" : "";
		$query = $this->db->query("SELECT campaign_id,campaign_name,active FROM vicidial_campaigns $filterSQL ORDER BY campaign_id;");
		$return['list'] = $query->result();

		return $return;
	}
	
	function go_get_tenants()
	{
		$tenant = $this->uri->segment(3);
		if (strlen($tenant) < 1)
		{
			$query = $this->db->query("SELECT user_group as tenant_id,group_name as tenant_name FROM vicidial_user_groups");
			foreach ($query->result() as $tenants)
			{
				$return['tenant'][$tenants->tenant_id] = "{$tenants->tenant_name}";
			}
		} else {
			$filterSQL = ($tenant == "ALL") ? "" : "WHERE user_group='$tenant'";
			$query = $this->db->query("SELECT campaign_id,campaign_name FROM vicidial_campaigns $filterSQL ORDER BY campaign_id;");
			$campOptions = "<option value='ALL'>--- All Campaign ---</option>";
			foreach ($query->result() as $camp)
			{
				$campOptions .= "<option value='{$camp->campaign_id}'>{$camp->campaign_name}</option>";
			}
			$return['camp_dropdown'] = $campOptions;
		}
		return $return;
	}
	
	function go_dropped_call_list_out()
	{
		$NOW = date("Y-m-d");
		$xx = 0;
		$filterSQL = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "AND vc.user_group='".$this->session->userdata('user_group')."'" : "";
		$query = $this->db->query("SELECT vcs.campaign_id AS campaign_id,campaign_name,calls_today,'' AS dropped_percentage,answers_today AS 'answered',drops_today AS 'dropped' FROM vicidial_campaign_stats vcs, vicidial_campaigns vc WHERE vcs.campaign_id=vc.campaign_id AND update_time BETWEEN '$NOW 00:00:00' AND '$NOW 23:59:59' $filterSQL ORDER BY vcs.campaign_id");
		
		$return = "<thead><tr style='font-weight:bold'>";
		foreach ($query->list_fields() AS $field)
		{
			$alignLeft = ($field!='campaign_id' && $field!='campaign_name') ? "" : $alignLeft = "text-align:left";
			$return .= "<th style='cursor: pointer;$alignLeft'>&nbsp;".strtoupper(str_replace("_"," ",$field))."</th>";
		}
		$return .= "</tr></thead><tbody>";
		
		foreach ($query->result() as $line)
		{
//			$drop_percentage = ( ($line->drops_today / $line->answers_today) * 100);
			$dropped_percentage = ( ($line->dropped / $line->answered) * 100);
			$dropped_percentage = ($dropped_percentage > 0) ? round($dropped_percentage,2) : "0";
			$return .= "<tr>";
			$return .= "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$line->campaign_id}</td>";
			$return .= "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$line->campaign_name}</td>";
			$return .= "<td style='border-top:#D0D0D0 dashed 1px;text-align:center'>{$line->calls_today}</td>";
			$return .= "<td style='border-top:#D0D0D0 dashed 1px;text-align:center'>{$dropped_percentage}%</td>";
//			$return .= "<td style='border-top:#D0D0D0 dashed 1px;text-align:center'>{$line->drops_today}</td>";
			$return .= "<td style='border-top:#D0D0D0 dashed 1px;text-align:center'>{$line->answered}</td>";
//			$return .= "<td style='border-top:#D0D0D0 dashed 1px;text-align:center'>{$line->answers_today}</td>";
			$return .= "<td style='border-top:#D0D0D0 dashed 1px;text-align:center'>{$line->dropped}</td>";
			$return .= "</tr>";
		}
		$return .= "</tbody>";
		return $return;
	}
	
	function go_dropped_call_list_in()
	{
		$NOW = date("Y-m-d");
		$xx = 0;
		$filterSQL = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "AND vig.user_group='".$this->session->userdata('user_group')."'" : "";
		$query = $this->db->query("SELECT campaign_id AS 'ingroup_id',group_name,calls_today,'' AS dropped_percentage,answers_today AS 'answered',drops_today AS 'dropped' FROM vicidial_campaign_stats vcs, vicidial_inbound_groups vig WHERE vcs.campaign_id=vig.group_id AND update_time BETWEEN '$NOW 00:00:00' AND '$NOW 23:59:59' $filterSQL ORDER BY vcs.campaign_id");
		
		$return = "<thead><tr style='font-weight:bold'>";
		foreach ($query->list_fields() AS $field)
		{
			$alignLeft = ($field!='ingroup_id' && $field!='group_name') ? "" : $alignLeft = "text-align:left";
			$return .= "<th style='cursor: pointer;$alignLeft'>&nbsp;".strtoupper(str_replace("_"," ",$field))."</th>";
		}
		$return .= "</tr></thead><tbody>";
		
		foreach ($query->result() as $line)
		{
//			$drop_percentage = ( ($line->drops_today / $line->answers_today) * 100);
			$dropped_percentage = ( ($line->dropped / $line->answered) * 100);
			$dropped_percentage = ($dropped_percentage > 0) ? round($dropped_percentage,2) : "0";
			$return .= "<tr>";
			$return .= "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$line->ingroup_id}</td>";
			$return .= "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$line->group_name}</td>";
			$return .= "<td style='border-top:#D0D0D0 dashed 1px;text-align:center'>{$line->calls_today}</td>";
			$return .= "<td style='border-top:#D0D0D0 dashed 1px;text-align:center'>{$dropped_percentage}%</td>";
//			$return .= "<td style='border-top:#D0D0D0 dashed 1px;text-align:center'>{$line->drops_today}</td>";
			$return .= "<td style='border-top:#D0D0D0 dashed 1px;text-align:center'>{$line->answered}</td>";
//			$return .= "<td style='border-top:#D0D0D0 dashed 1px;text-align:center'>{$line->answers_today}</td>";
			$return .= "<td style='border-top:#D0D0D0 dashed 1px;text-align:center'>{$line->dropped}</td>";
			$return .= "</tr>";
		}
		$return .= "</tbody>";
		return $return;
	}

}
