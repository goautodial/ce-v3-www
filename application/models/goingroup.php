<?php
########################################################################################################
####  Name:             	goingroup.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Jerico James Milo                                       	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Goingroup extends Model {

     function __construct()
	 {
         parent::Model();
         $this->asteriskDB = $this->load->database('dialerdb',TRUE);
         //$this->a2billingDB =$this->load->database('billingdb',TRUE) ;
     }
     
     function getallingroup($accntnum=null,$start=null,$limit=null,$find_ingroup=null)
	 {
		  // Added error catcher when $start and $limit variables are null -- Chris
		  if ($start==null || $start < 0)
			  $start = 0;
		  if ($limit==null || $limit < 1)
			  $limit = 25;		
	
		  $groupSQL = ($this->commonhelper->checkIfTenant($accntnum)) ? "WHERE user_group='$accntnum'" : "";
		  
		  if (!is_null($find_ingroup))
		  {
			   if (strlen($groupSQL) > 0)
			   {
					$findSQL = "AND group_id RLIKE '$find_ingroup' OR group_name RLIKE '$find_ingroup'";
			   } else {
					$findSQL = "WHERE group_id RLIKE '$find_ingroup' OR group_name RLIKE '$find_ingroup'";
			   }
		  }
		
		  $stmt="SELECT group_id,group_name,queue_priority,active,call_time_id,group_color FROM vicidial_inbound_groups $groupSQL $findSQL ORDER BY group_id LIMIT $start,$limit";
		
		  $listall = $this->asteriskDB->query($stmt);
		  $ctr = 0;
		  foreach($listall->result() as $info){
			  $lists[$ctr] = $info;
			  $ctr++;
		  }
	      return $lists;
     }
     
     function getdids($accounts=null,$start=null,$limit=null,$find_did=null)
	 {
     	  if ($start==null || $start < 0)
			   $start = 0;
		  if ($limit==null || $limit < 1)
			   $limit = 25;
	
		  $filterSQL = ($this->commonhelper->checkIfTenant($accounts)) ? "WHERE user_group = '$accounts'" : "";
		  
		  if (!is_null($find_did))
		  {
			   if (strlen($filterSQL) > 0)
			   {
					$findSQL = "AND did_pattern RLIKE '$find_did' OR did_description RLIKE '$find_did'";
			   } else {
					$findSQL = "WHERE did_pattern RLIKE '$find_did' OR did_description RLIKE '$find_did'";
			   }
		  }
     	
		  $stmt="SELECT did_id,did_pattern,did_description,did_active,did_route,record_call from vicidial_inbound_dids $filterSQL $findSQL order by did_pattern LIMIT $start,$limit;";
		  
		  $didall = $this->asteriskDB->query($stmt);
		  $ctr = 0;
		  foreach($didall->result() as $info){
			   $didallin[$ctr] = $info;
			   $ctr++;
		  }
		  
		  return $didallin;
     }
  	  
	 function getallcallmenus($accounts=null,$start=null,$limit=null,$find_ivr=null)
	 {
     	  if ($start==null || $start < 0)
			   $start = 0;
		  if ($limit==null || $limit < 1)
			   $limit = 25;
			   
		  $filterSQL = ($this->commonhelper->checkIfTenant($accounts)) ? "AND user_group = '$accounts'" : "";
		  
		  if (!is_null($find_ivr))
		  {
			   $findSQL = "AND menu_id RLIKE '$find_ivr' OR menu_name RLIKE '$find_ivr'";
		  }

		  $stmt="SELECT menu_id,menu_name,menu_prompt,menu_timeout from vicidial_call_menu WHERE menu_id!='defaultlog' $filterSQL $findSQL order by menu_id limit $start,$limit";
	   
		  $callmenu = $this->asteriskDB->query($stmt);
		  $ctr = 0;
		  foreach($callmenu->result() as $info){
			   $callmenuin[$ctr] = $info;
			   $ctr++;
		  }

		  return $callmenuin;
	 }
	 
	 function go_get_settings($type,$group)
	 {
		  switch($type)
		  {
			   case "did":
					$table = "vicidial_inbound_dids";
					$filterSQL = "WHERE did_id='$group'";
					break;
			   
			   case "ivr":
					$table = "vicidial_call_menu";
					$filterSQL = "WHERE menu_id='$group'";
					break;
			   
			   default:
					$table = "vicidial_inbound_groups";
					$filterSQL = "WHERE group_id='$group'";
		  }
		  $query = $this->asteriskDB->query("SELECT * FROM $table $filterSQL");
		  $result = $query->row();
		  
		  return $result;
	 }
     
     function scriptlists($account)
	 {
		  $groupSQL = ($this->commonhelper->checkIfTenant($account)) ? "AND gs.account_num='$account'" : "";
		  $stmt="SELECT vs.script_id,vs.script_name from vicidial_scripts AS vs, go_scripts AS gs WHERE vs.script_id=gs.script_id $groupSQL order by vs.script_id";
     	  
     	  $listall = $this->asteriskDB->query($stmt);
		  $ctr = 0;
		  foreach($listall->result() as $info){
			  $lists[$ctr] = $info;
			  $ctr++;
		  }
	      return $lists;
     }	  
     
     
     function agentranks($group_id=null,$start=null,$limit=null,$pagelinks=null,$find_user=null)
	 {
		  if ($start==null || $start < 0)
			  $start = 0;
		  if ($limit==null || $limit < 1)
			  $limit = 20;

		  $user_group = $this->session->userdata('user_group');
		  if ($this->commonhelper->checkIfTenant($user_group)) {
			   $addedSQL = "AND user_group='$user_group'";
		  }
		  
		  if (!is_null($find_user))
		  {
			   $findSQL = "AND user RLIKE '$find_user'";
		  }

          $stmt="SELECT user,full_name,closer_campaigns,user_group from vicidial_users where user NOT IN ('VDAD','VDCL') and user_level != '4' $addedSQL $findSQL order by user limit $start,$limit;";

          $listval = $this->asteriskDB->query($stmt);
		      
		  $users_to_print = $listval->num_rows;
		  
		  $users_output  = "<TABLE width=\"100%\" cellspacing=0 cellspacing=0 class=\"tableeditingroup\" id=\"agentrankvalue\">\n";
		  $users_output .= "<tr class=trview style='font-weight:bold'><td style='padding: 3px 0px;'> &nbsp; USER</td>";
		  if (!$this->commonhelper->checkIfTenant($user_group)){
			   $users_output .= "<td style='padding: 3px 0px;'> &nbsp; TENANT ID</td>";
		  }
		  $users_output .= "<td style='text-align:center'>SELECTED <input type=checkbox name=\"selectAllAgents\" id=\"selectAllAgents\" onclick=\"checkdatas();\">";
		  $users_output .= "</td><td style='text-align:center'>RANK</td><td style='text-align:center'>GRADE</td><td style='text-align:center'>CALLS TODAY</td></tr>\n";	
		  $checkbox_count=0;
		  $xxx = 0;
		  
		  foreach ($listval->result() as $row) {
			   $isChecked = '';
			   if (preg_match("/ $group_id /",$row->closer_campaigns))
					{$isChecked = ' CHECKED';}
					
			   $stmtx="SELECT group_rank,group_grade,calls_today from vicidial_inbound_group_agents where group_id='$group_id' and user='{$row->user}';";
					 
			   $rsltx = $this->asteriskDB->query($stmtx);
			   $viga_to_print = $rsltx->num_rows;
				  
			   if ($viga_to_print > 0) {
					$rowx =				$rsltx->row();
					$ARIG_rank =        $rowx->group_rank;
					$ARIG_grade =       $rowx->group_grade;
					$ARIG_calls =       $rowx->calls_today;
	   
					if($ARIG_calls==null){
						 $ARIG_calls="0";                             			
					}
			   } else {
					$stmtD="INSERT INTO vicidial_inbound_group_agents set calls_today='0',group_rank='0',group_weight='0',user='{$row->user}',group_id='$group_id';";
					$rslt=$this->asteriskDB->query($stmtD);
					$ARIG_rank =        '0';
					$ARIG_grade =       '0';
					$ARIG_calls =       '0';
			   }
			   
			   $checkbox_field="CHECK_{$row->user}";
			   $rank_field="RANK_{$row->user}";
			   $grade_field="GRADE_{$row->user}";
			   
			   if ($xxx > 0) {
					$bgcolor = "#EFFBEF";
					$xxx = 0;
			   } else {
					$bgcolor = "#E0F8E0";
					$xxx = 1;
			   }

			   $checkbox_list .= "|$checkbox_field";
			   $checkbox_count++;

			   $users_output .= "<tr style='background-color:$bgcolor'><td style='border-top:#D0D0D0 dashed 1px;'> &nbsp; <font size=1>{$row->user} - {$row->full_name}</td>\n";
			   if (!$this->commonhelper->checkIfTenant($user_group)){
					$users_output .= "<td style='border-top:#D0D0D0 dashed 1px;'> &nbsp; <font size=1>{$row->user_group}</td>\n";
			   }
			   $users_output .= "<td style='border-top:#D0D0D0 dashed 1px;' align=center><input type=checkbox name=\"$checkbox_field\" id=\"$checkbox_field\" value=\"YES\"$isChecked></td>\n";
			   $users_output .= "<td style='border-top:#D0D0D0 dashed 1px;' align=center>\n";
			   $rankArray = array('9'=>'9','8'=>'8','7'=>'7','6'=>'6','5'=>'5','4'=>'4','3'=>'3','2'=>'2','1'=>'1','0'=>'0',
								  '-1'=>'-1','-2'=>'-2','-3'=>'-3','-4'=>'-4','-5'=>'-5','-6'=>'-6','-7'=>'-7','-8'=>'-8','-9'=>'-9');
			   $users_output .= form_dropdown("$rank_field",$rankArray,$ARIG_rank,"style='font-size:10px;'");
			   $users_output .= "</td>\n";
			   $users_output .= "<td style='border-top:#D0D0D0 dashed 1px;' align=center>\n";
			   $gradeArray = array('10'=>'10','9'=>'9','8'=>'8','7'=>'7','6'=>'6','5'=>'5','4'=>'4','3'=>'3','2'=>'2','1'=>'1','0'=>'0');
			   $users_output .= form_dropdown("$grade_field",$gradeArray,$ARIG_grade,"style='font-size:10px;'");
			   $users_output .= "</td>\n";
			   $users_output .= "<td style='border-top:#D0D0D0 dashed 1px;' align=center><font size=1>$ARIG_calls</td></tr>\n";
          }
		  $colspan = (!$this->commonhelper->checkIfTenant($user_group)) ? "6" : "5";
		  $users_output .= "<tr><td style='border-top:#D0D0D0 dashed 1px;' align=center colspan=$colspan style='vertical-align:top'>";
		  if (strlen($pagelinks) > 0 || !is_null($pagelinks)) {
	 		  $users_output .= "<div style='float:left;padding-top:5px;'>$pagelinks</div>";
		  }
		  $users_output .= "<input type=button name=submit value=SUBMIT onclick=\"checkdatas('$group_id');\" style=\"cursor:pointer;border:0px;color:#7A9E22;float:right;\"></td></tr>\n";
		  $users_output .= "</TABLE>\n";

		  return $users_output;     	
     }
	
	 function pagelinks($group_id,$page,$rp,$total,$limit)
	 {
		  $pg 	= $this->commonhelper->paging($page,$rp,$total,$limit);
	  
		  if ($pg['last'] > 1) {
			   $pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top:10px;">';
			   $pagelinks .= '<a title="Go to First Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="changeAgentRankPage(\''.$group_id.'\','.$pg['first'].')"><span><img src="'.base_url().'/img/first.gif"></span></a>';
			   $pagelinks .= '<a title="Go to Previous Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="changeAgentRankPage(\''.$group_id.'\','.$pg['prev'].')"><span><img src="'.base_url().'/img/prev.gif"></span></a>';
			   
			   for ($i=$pg['start'];$i<=$pg['end'];$i++) { 
					if ($i==$pg['page']) $current = 'color: #F00;cursor: default;'; else $current="";
			   
					$pagelinks .= '<a title="Go to Page '.$i.'" style="vertical-align:text-top;padding: 0px 2px;'.$current.'" onclick="changeAgentRankPage(\''.$group_id.'\','.$i.')"><span>'.$i.'</span></a>';
			   }
	   
			   $pagelinks .= '<a title="View All Pages" style="vertical-align:text-top;padding: 0px 2px;" onclick="changeAgentRankPage(\''.$group_id.'\',\'ALL\')"><span>ALL</span></a>';
			   $pagelinks .= '<a title="Go to Next Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="changeAgentRankPage(\''.$group_id.'\','.$pg['next'].')"><span><img src="'.base_url().'/img/next.gif"></span></a>';
			   $pagelinks .= '<a title="Go to Last Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="changeAgentRankPage(\''.$group_id.'\','.$pg['last'].')"><span><img src="'.base_url().'/img/last.gif"></span></a>';
			   $pagelinks .= '<input type=hidden id=currentPage value="'.$pg['page'].'" /></div>';
		  } else {
			   if ($rp > 25) {
			        $pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top:10px;">';
			        $pagelinks .= '<a title="Back to Paginated View" style="vertical-align:text-top;padding: 0px 2px;" onclick="changeAgentRankPage(\''.$group_id.'\',1)"><span>BACK</span></a>';
			        $pagelinks .= '</div>';
			   } else {
			        $pagelinks = "";
			   }
		  }
		  
		  $pageinfo = "<span style='float:right;padding-top:10px;'>Displaying {$pg['istart']} to {$pg['iend']} of {$pg['total']} in-groups</span>";
		  
		  $return['links'] = $pagelinks;
		  $return['info'] = $pageinfo;
		  
		  return $return;
	 }
	
	 function showpagelinks($type,$page=null,$rp=null,$total=null,$limit=null,$search=null)
	 {
		  if (is_null($page) || $page < 1) { $page = 1; }
		  $total 	= $this->get_ingroup_count($type,$search);
		  $rp		= (is_null($rp) || $rp < 1) ? 25 : $rp;
		  $limit 	= (is_null($limit) || $rp < 1) ? 5 : $limit;
		  $start	= (($page-1) * $rp);
		  $pg		= $this->commonhelper->paging($page,$rp,$total,$limit);
	  
		  if ($pg['last'] > 1) {
			   $pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top:10px;">';
			   $pagelinks .= '<a title="Go to First Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage(\''.$type.'\','.$pg['first'].')"><span><img src="'.base_url().'/img/first.gif"></span></a>';
			   $pagelinks .= '<a title="Go to Previous Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage(\''.$type.'\','.$pg['prev'].')"><span><img src="'.base_url().'/img/prev.gif"></span></a>';
			   
			   for ($i=$pg['start'];$i<=$pg['end'];$i++) { 
					if ($i==$pg['page']) $current = 'color: #F00;cursor: default;'; else $current="";
			   
					$pagelinks .= '<a title="Go to Page '.$i.'" style="vertical-align:text-top;padding: 0px 2px;'.$current.'" onclick="changePage(\''.$type.'\','.$i.')"><span>'.$i.'</span></a>';
			   }
	   
			   $pagelinks .= '<a title="View All Pages" style="vertical-align:text-top;padding: 0px 2px;" onclick="changePage(\''.$type.'\',\'ALL\')"><span>ALL</span></a>';
			   $pagelinks .= '<a title="Go to Next Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage(\''.$type.'\','.$pg['next'].')"><span><img src="'.base_url().'/img/next.gif"></span></a>';
			   $pagelinks .= '<a title="Go to Last Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="changePage(\''.$type.'\','.$pg['last'].')"><span><img src="'.base_url().'/img/last.gif"></span></a>';
			   $pagelinks .= '<input type=hidden id=currentPage value="'.$pg['page'].'" /></div>';
		  } else {
			   if ($rp > 25) {
			        $pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top:10px;">';
			        $pagelinks .= '<a title="Back to Paginated View" style="vertical-align:text-top;padding: 0px 2px;" onclick="changePage(\''.$type.'\',1)"><span>BACK</span></a>';
			        $pagelinks .= '</div>';
			   } else {
			        $pagelinks = "";
			   }
		  }
		  
		  $pageinfo = "<span style='float:right;padding-top:10px;'>Displaying {$pg['istart']} to {$pg['iend']} of {$pg['total']} in-groups</span>";
		  
		  $return['links'] = $pagelinks;
		  $return['info'] = $pageinfo;
		  
		  return $return;
	 }
	 
	 function get_agent_count($search=null)
	 {
		  $user_group = $this->session->userdata('user_group');
		  if ($this->commonhelper->checkIfTenant($user_group)) {
			   $addedSQL = "and user_group='$user_group'";
		  }
		  
		  if (!is_null($search))
		  {
			   $findSQL = "AND user RLIKE '$search'";
		  }
		  
		  $query = $this->asteriskDB->query("SELECT count(*) as ucnt from vicidial_users where user_level != '4' $addedSQL $findSQL order by user;");
		  
		  return $query->row()->ucnt;
	 }
	 
	 function get_ingroup_count($type,$search)
	 {
		  $user_group = $this->session->userdata('user_group');
		  if ($this->commonhelper->checkIfTenant($user_group)) {
			   $addedSQL = "WHERE user_group='$user_group'";
		  }
		  
		  switch ($type)
		  {
			   case "ingroup":
					$from_table = "vicidial_inbound_groups";
					$field = "group_id";
					$fname = "group_name";
					break;
			   
			   case "did":
					$from_table = "vicidial_inbound_dids";
					$field = "did_pattern";
					$fname = "did_description";
					break;
			   
			   case "ivr":
					$from_table = "vicidial_call_menu";
					$field = "menu_id";
					$fname = "menu_name";
					break;
		  }
		  
		  if (!is_null($search))
		  {
			   if (strlen($addedSQL) > 0)
			   {
					$findSQL = "AND $field RLIKE '$search' OR $fname RLIKE '$search'";
			   } else {
					$findSQL = "WHERE $field RLIKE '$search' OR $fname RLIKE '$search'";
			   }
		  }
		  
		  $query = $this->asteriskDB->query("SELECT count(*) as ucnt from $from_table $addedSQL $findSQL;");
		  
		  return $query->row()->ucnt;
	 }
	 
	 function go_get_usergroups($group)
	 {
		  $filterSQL = ($this->commonhelper->checkIfTenant($group)) ? "WHERE user_group='$group'" : "";
		  $query = $this->asteriskDB->query("SELECT user_group,group_name FROM vicidial_user_groups $filterSQL;");
		  $return = $query->result();
	  
		  return $return;
	 }
	 
	 function go_get_active_ingroups()
	 {
		  $user_group = $this->session->userdata('user_group');
		  if ($this->commonhelper->checkIfTenant($user_group)) {
			   $addedSQL = "AND user_group='$user_group'";
		  }
		  $query = $this->asteriskDB->query("SELECT group_id,group_name FROM vicidial_inbound_groups WHERE active='Y' $addedSQL ORDER BY group_id");
		  
		  return $query->result();
	 }
	 
	 function go_get_call_menus()
	 {
		  $user_group = $this->session->userdata('user_group');
		  if ($this->commonhelper->checkIfTenant($user_group)) {
			   $addedSQL = "AND user_group='$user_group'";
		  }
		  $query = $this->asteriskDB->query("SELECT menu_id,menu_name FROM vicidial_call_menu WHERE menu_id!='defaultlog' $addedSQL ORDER BY menu_id");
		  
		  return $query->result();
	 }
	 
	 function go_get_call_times()
	 {
		  $user_group = $this->session->userdata('user_group');
		  if ($this->commonhelper->checkIfTenant($user_group)) {
			   $addedSQL = "WHERE user_group IN ('$user_group','---ALL---')";
		  }
		  $query = $this->asteriskDB->query("SELECT call_time_id,call_time_name FROM vicidial_call_times $addedSQL ORDER BY call_time_id");
		  
		  return $query->result();
	 }
	 
	 function go_get_active_campaigns()
	 {
		  $user_group = $this->session->userdata('user_group');
		  if ($this->commonhelper->checkIfTenant($user_group)) {
			   $addedSQL = "AND user_group='$user_group'";
		  }
		  $query = $this->asteriskDB->query("SELECT campaign_id,campaign_name FROM vicidial_campaigns WHERE active='Y' $addedSQL ORDER BY campaign_id");
		  
		  return $query->result();
	 }
	 
	 function go_get_active_dids()
	 {
		  $user_group = $this->session->userdata('user_group');
		  if ($this->commonhelper->checkIfTenant($user_group)) {
			   $addedSQL = "AND user_group='$user_group'";
		  }
		  $query = $this->asteriskDB->query("SELECT did_pattern,did_description,did_route FROM vicidial_inbound_dids WHERE did_active='Y' $addedSQL ORDER BY did_pattern");
		  
		  return $query->result();
	 }
	
	 function go_get_active_users()
	 {
		  $user_group = $this->session->userdata('user_group');
		  if ($this->commonhelper->checkIfTenant($user_group)) {
			   $addedSQL = "AND user_group='$user_group'";
		  }
		  $query = $this->asteriskDB->query("SELECT user,full_name FROM vicidial_users WHERE active='Y' AND user_level != '4' AND user NOT IN ('VDAD','VDCL') $addedSQL ORDER BY user");
		  
		  return $query->result();
	 }
	 
	 function go_get_active_phones()
	 {
		  $user_group = $this->session->userdata('user_group');
		  if ($this->commonhelper->checkIfTenant($user_group)) {
			   $addedSQL = "AND user_group='$user_group'";
		  }
		  $query = $this->asteriskDB->query("SELECT extension,dialplan_number,server_ip FROM phones WHERE active='Y' $addedSQL ORDER BY extension");
		  
		  return $query->result();
	 }
	 
	 function go_get_active_servers()
	 {
		  $user_group = $this->session->userdata('user_group');
		  if ($this->commonhelper->checkIfTenant($user_group)) {
			   $addedSQL = "AND user_group='$user_group'";
		  }
		  $query = $this->asteriskDB->query("SELECT server_id,server_ip,server_description FROM servers WHERE active='Y' ORDER BY server_id");
		  
		  return $query->result();
	 }
     
     function go_get_filter_phone_groups()
	 {
		  $user_group = $this->session->userdata('user_group');
		  if ($this->commonhelper->checkIfTenant($user_group)) {
			   $addedSQL = "WHERE user_group='$user_group'";
		  }
     	  $query = $this->asteriskDB->query("select filter_phone_group_id,filter_phone_group_name from vicidial_filter_phone_groups $addedSQL order by filter_phone_group_id;");

		  return $query->result();
  	 }
	 
	 function editvalues($query=null) {
		  $this->asteriskDB->query($query);
	 }
	  
	 function deletevalues($query=null) {
		  $this->asteriskDB->query($query);
	 }
  	  
	 function insertcallmenu($menu_id=null,$menu_name=null,$user_group=null,$options=null,$option_route=null) {
		  $stmt = "SELECT value FROM vicidial_override_ids where id_table='vicidial_call_menu' and active='1';";
		  $query = $this->asteriskDB->query($stmt);
		  $voi_ct = $query->num_rows;
		  
		  if ($voi_ct > 0) {
			  $row = $query->row(); 
			  $menu_id = ($row->value + 1);
			  $stmt = "UPDATE vicidial_override_ids SET value='$menu_id' where id_table='vicidial_call_menu' and active='1';";
			  $this->asteriskDB->query($stmt);
		  }
		  
		  foreach ($options as $id => $val)
		  {
			 $ScmSQL .= ",$id";
			 $EcmSQL .= ",'$val'";
		  }

		  $stmt="SELECT count(*) as menucounts from vicidial_call_menu where menu_id='$menu_id';";
		  $query = $this->asteriskDB->query($stmt);
		  $row = $query->row();
		  
		  if ($row->menucounts > 0) {
			   $message = "CALL MENU NOT ADDED - there is already a CALL MENU in the system with this ID\n";
		  } else {
			   $stmt="INSERT INTO vicidial_call_menu (menu_id,menu_name,user_group$ScmSQL) values('$menu_id','$menu_name','$user_group'$EcmSQL);";
			   $this->asteriskDB->query($stmt);
			   # set default entry in vicidial_callmenu_options by Franco Hora 
			   $this->asteriskDB->insert('vicidial_call_menu_options',array('menu_id'=>$menu_id,'option_value'=>'TIMEOUT','option_description'=>'Hangup','option_route'=>'HANGUP','option_route_value'=>'vm-goodbye'));
			   
			   if (count($option_route) > 0)
			   {
					foreach ($option_route as $cnt => $option_value)
					{
						 $OptIdSQL = "(menu_id";
						 $OptValSQL = "('$menu_id'";
						 foreach ($option_value as $id => $val)
						 {
							  $OptIdSQL .= ",$id";
							  $OptValSQL .= ",'$val'";
						 }
						 $OptIdSQL .= ")";
						 $OptValSQL .= ")";
						 $this->asteriskDB->query("INSERT INTO vicidial_call_menu_options $OptIdSQL VALUES $OptValSQL;");
						 $stmtMerged .= "INSERT INTO vicidial_call_menu_options $OptIdSQL VALUES $OptValSQL;\n";
					}
			   }
			   $this->commonhelper->auditadmin('ADD',"Added New Call Menu ID: $menu_id","$stmt\n$stmtMerged");
		  }
	 }
	 
     function insertingroup($accounts=null, $users=null, $group_id=null, $group_name=null, $group_color=null, $active=null, $web_form_address=null, $voicemail_ext=null, $next_agent_call=null, $fronter_display=null, $script_id=null, $get_call_launch=null, $user_group=null)
	 {
		  $group_id = $trimmed = str_replace(" ", "", $group_id);
		  
		  $stmt = "SELECT value FROM vicidial_override_ids where id_table='vicidial_inbound_groups' and active='1';";
		  $query = $this->asteriskDB->query($stmt);
		  $voi_ct = $query->num_rows;
        
		  if ($voi_ct > 0) {
			   $row = $query->row(); 
			   $group_id = ($row->value + 1);
			   $stmt = "UPDATE vicidial_override_ids SET value='$group_id' where id_table='vicidial_inbound_groups' and active='1';";
			   $this->asteriskDB->query($stmt);
		  }
        
		  $stmt="SELECT count(*) as vigcount from vicidial_inbound_groups where group_id='$group_id';";
		  $query = $this->asteriskDB->query($stmt);
		  $row = $query->row(); 
      	 
		  if ($row->vigcount > 0) {
			   $message = "<br>GROUP NOT ADDED - there is already a group in the system with this ID\n";
		  } else {
               $stmt="SELECT count(*) as vccampid from vicidial_campaigns where campaign_id='$group_id';";
			   $query = $this->asteriskDB->query($stmt);
			   $row = $query->row(); 
          
			   if ($row->vccampid > 0) {
					$message = "<br>GROUP NOT ADDED - there is already a campaign in the system with this ID\n";
			   } else {
                    if ( (strlen($group_id) < 2) or (strlen($group_name) < 2)  or (strlen($group_color) < 2) or (strlen($group_id) > 20) or (eregi(' ',$group_id)) or (eregi("\-",$group_id)) or (eregi("\+",$group_id)) ) {
                         $message = "<br>GROUP NOT ADDED - Please go back and look at the data you entered\n <br>Group ID must be between 2 and 20 characters in length and contain no ' -+'.\n <br>Group name and group color must be at least 2 characters in length\n";
					} else {
						 $groupcallt = $group_id.'_ingroup';
                         
						 $stmt="INSERT INTO vicidial_inbound_groups (group_id,group_name,group_color,active,web_form_address,voicemail_ext,next_agent_call,fronter_display,ingroup_script,get_call_launch,web_form_address_two,start_call_url,dispo_call_url,add_lead_url,uniqueid_status_prefix,call_time_id,user_group) values('".mysql_real_escape_string($group_id)."','".mysql_real_escape_string($group_name)."','$group_color','$active','".mysql_real_escape_string($web_form_address)."','".mysql_real_escape_string($voicemail_ext)."','$next_agent_call','$fronter_display','$script_id','$get_call_launch','','','','','$accounts','24hours','$user_group');";
						 $query = $this->asteriskDB->query($stmt);
						 
						 $suser = $users;

						 $stmtA="INSERT INTO vicidial_campaign_stats (campaign_id) values('".mysql_real_escape_string($group_id)."');";
						 $query = $this->asteriskDB->query($stmtA);
						 $this->commonhelper->auditadmin('ADD',"Added New In-group ID: $group_id","$stmt\n$stmtA");
					}
			   }
		  }
		  return $message;
     }
     
	 function insertdid($did_pattern=null, $did_description=null, $users=null, $user_group=null, $diddata=null, $db_column=null, $did_route=null)
	 {
		  $trimmed = str_replace(" ", "", $did_pattern);
		  $did_descriptionfinal = $did_description;
		  $did_data = implode("','",$diddata);
	 
		  $stmtdf="SELECT count(*) as didpat from vicidial_inbound_dids where did_pattern='$trimmed';";
		  $querydf = $this->asteriskDB->query($stmtdf);
		  $rowdf = $querydf->row();
			
		  if ($rowdf->didpat > 0) {
			   $message = "<br>DID NOT ADDED - DID already exist.\n";
		  } else {
			   $stmt="INSERT INTO vicidial_inbound_dids (did_pattern,did_description,did_route,record_call,user_group{$db_column}) values('".mysql_real_escape_string($trimmed)."','".mysql_real_escape_string($did_descriptionfinal)."','$did_route','N','$user_group','$did_data');";
			   $query = $this->asteriskDB->query($stmt);
		  }
	 
		  $this->commonhelper->auditadmin('ADD',"Added New DID: $trimmed","$stmt");
		  return $message;
	 }
	 
	 function go_get_callmenu_options($menu_id=null)
	 {
		  $getcallmenuoptions = $this->getcallmenuoptions($menu_id);
			 
		  $countme = count($getcallmenuoptions);
		  
		  $cmOptionsHTML  = '<table border="0" class="tableedit" width="100%">';
		  if($countme > 0) {
			 $user_group = $this->session->userdata('user_group');
			 if ($this->commonhelper->checkIfTenant($user_group)) {
				  $addedSQL = "WHERE user_group='$user_group'";
			 }
			 
			   $ctr = 0;
			   foreach($getcallmenuoptions as $getcallmenuoptionsInfo) {
					// onChange="javascript:showoptionpostval(this.options[this.selectedIndex].value,'.$ctr.');"
					$optionDD = form_dropdown('option_value_'.$ctr,array('0','1','2','3','4','5','6','7','8','9','#'=>'#','*'=>'*','TIMECHECK'=>'TIMECHECK','TIMEOUT'=>'TIMEOUT','INVALID'=>'INVALID'),$getcallmenuoptionsInfo->option_value,'id="option_value_'.$ctr.'" onChange="javascript:checkoptionval(this.options[this.selectedIndex].value,'.$ctr.');"');
					$optionRoute = form_dropdown('option_route_'.$ctr,array('DEL'=>'*Remove*','CALLMENU'=>'Call Menu / IVR','INGROUP'=>'In-group','DID'=>'DID','HANGUP'=>'Hangup','EXTENSION'=>'Custom Extension','PHONE'=>'Phone','VOICEMAIL'=>'Voicemail','AGI'=>'AGI'),$getcallmenuoptionsInfo->option_route,'id="option_route_'.$ctr.'" onChange="javascript:showoptionpostval(\''.$dataval.'\',document.getElementById(\'option_value_'.$ctr.'\').options[document.getElementById(\'option_value_'.$ctr.'\').selectedIndex].value,this.options[this.selectedIndex].value,'.$ctr.');"');
					$cmOptionsHTML .= "<tr class=\"trview\" id=\"tbl_option_$ctr\">";
					$cmOptionsHTML .= "<td style='padding-left:10px;'>Option:</td><td>$optionDD</td>";
					$cmOptionsHTML .= "<td>Description:</td><td>".form_input('option_description_'.$ctr,$getcallmenuoptionsInfo->option_description,'maxlength="255" size="30"')."</td>";
					$cmOptionsHTML .= "<td>Route:</td><td>$optionRoute</td>";
					$cmOptionsHTML .= "</tr>\n";
					
					$cmOptionsHTML .= "<tr class=\"trview option_hidden_$ctr\">";
					switch ($getcallmenuoptionsInfo->option_route)
					{
						 case "CALLMENU":
							  $stmt="SELECT menu_id,menu_name from vicidial_call_menu order by menu_id";
							  $callmenupulldown = $this->asteriskDB->query($stmt);
							  
							  foreach ($callmenupulldown->result() as $callmenu)
							  {
								   $callmenuArray[$callmenu->menu_id] = "{$callmenu->menu_id} - {$callmenu->menu_name}";
							  }
							  
							  $cmOptionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\" class=\"option_display_$ctr\"><label>Call Menu: </label>".form_dropdown('option_route_value_'.$ctr,$callmenuArray,$getcallmenuoptionsInfo->option_route_value,'id="option_route_value_'.$ctr.'"')."</td>";
							  break;
						 
						 case "INGROUP":
							  $stmt="SELECT group_id,group_name FROM vicidial_inbound_groups $addedSQL ORDER BY group_id";
							  $ingrouppulldown = $this->asteriskDB->query($stmt);
							  
							  foreach ($ingrouppulldown->result() as $ingroup)
							  {
								   $grouplistArray[$ingroup->group_id] = "{$ingroup->group_id} - {$ingroup->group_name}";
							  }
							  
							  $stmt="SELECT campaign_id,campaign_name from vicidial_campaigns $addedSQL order by campaign_id";
							  $campaignpulldown = $this->asteriskDB->query($stmt);
							  
							  foreach ($campaignpulldown->result() as $campaign)
							  {
								   $campaignArray[$campaign->campaign_id] = "{$campaign->campaign_id} - {$campaign->campaign_name}";
							  }
							  
							  $handleArray = array(
								   'CID'=>'CID',
								   'CIDLOOKUP'=>'CIDLOOKUP',
								   'CIDLOOKUPRL'=>'CIDLOOKUPRL',
								   'CIDLOOKUPRC'=>'CIDLOOKUPRC',
								   'ANI'=>'ANI',
								   'ANILOOKUP'=>'ANILOOKUP',
								   'ANILOOKUPRL'=>'ANILOOKUPRL',
								   'VIDPROMPT'=>'VIDPROMPT',
								   'VIDPROMPTLOOKUP'=>'VIDPROMPTLOOKUP',
								   'VIDPROMPTLOOKUPRL'=>'VIDPROMPTLOOKUPRL',
								   'VIDPROMPTLOOKUPRC'=>'VIDPROMPTLOOKUPRC',
								   'CLOSER'=>'CLOSER',
								   '3DIGITID'=>'3DIGITID',
								   '4DIGITID'=>'4DIGITID',
								   '5DIGITID'=>'5DIGITID',
								   '10DIGITID'=>'10DIGITID');
							  $searchArray = array("LB"=>"LB - Load Balanced","LO"=>"LO - Load Balanced Overflow","SO"=>"Server Only");
							  
							  list($handle_method,$search_method,$list_id,$campaign_id,$phone_code,$enter_filename,$id_number_filename,$confirm_filename,$validate_digits) = split(',',$getcallmenuoptionsInfo->option_route_value_context);
			  
							  $cmOptionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\" class=\"option_display_$ctr\"><label>In-Group: </label>".form_dropdown('option_route_value_'.$ctr,$grouplistArray,$getcallmenuoptionsInfo->option_route_value,'id="edit_option_route_value_'.$ctr.'"')."<br />";
							  $cmOptionsHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>Handle Method: </label>".form_dropdown('handle_method_'.$ctr,$handleArray,$handle_method,'id="edit_handle_method_'.$ctr.'"')."<br /></span>";
							  $cmOptionsHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>Search Method: </label>".form_dropdown('search_method_'.$ctr,$searchArray,$search_method,'id="edit_search_method_'.$ctr.'"')." &nbsp; <label>List ID: </label>".form_input('list_id_'.$ctr,$list_id,'id="edit_list_id_'.$ctr.'" maxlength="14" size="8"')."<br /></span>";
							  $cmOptionsHTML .= "<label>Campaign ID: </label>".form_dropdown('campaign_id_'.$ctr,$campaignArray,$campaign_id,'id="edit_campaign_id_'.$ctr.'"')." <span class='advanceCallMenu_".$ctr."' style='display:none'>&nbsp; <label>Phone Code: </label>".form_input('phone_code_'.$ctr,$phone_code,'id="edit_phone_code_'.$ctr.'" maxlength="14" size="4"')."</span><br />";
							  $cmOptionsHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>VID Enter Filename: </label>".form_input('enter_filename_'.$ctr,$enter_filename,'id="edit_enter_filename_'.$ctr.'" maxlength="255" size="25"')." <a href=\"javascript:launch_chooser('edit_enter_filename_".$ctr."','date',1200,document.getElementById('edit_enter_filename_".$ctr."').value);\"><font color=\"blue\" size=\"1\">[ audio chooser ]</font></a> <div id=\"divedit_enter_filename_".$ctr."\" style=\"display:inline\"></div><br /></span>";
							  $cmOptionsHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>VID ID Number Filename: </label>".form_input('id_number_filename_'.$ctr,$id_number_filename,'id="edit_id_number_filename_'.$ctr.'" maxlength="255" size="25"')." <a href=\"javascript:launch_chooser('edit_id_number_filename_".$ctr."','date',1200,document.getElementById('edit_id_number_filename_".$ctr."').value);\"><font color=\"blue\" size=\"1\">[ audio chooser ]</font></a> <div id=\"divedit_id_number_filename_".$ctr."\" style=\"display:inline\"></div><br /></span>";
							  $cmOptionsHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>VID Confirm Filename: </label>".form_input('confirm_filename_'.$ctr,$confirm_filename,'id="edit_confirm_filename_'.$ctr.'" maxlength="255" size="25"')." <a href=\"javascript:launch_chooser('edit_confirm_filename_".$ctr."','date',1200,document.getElementById('edit_confirm_filename_".$ctr."').value);\"><font color=\"blue\" size=\"1\">[ audio chooser ]</font></a> <div id=\"divedit_confirm_filename_".$ctr."\" style=\"display:inline\"></div><br /></span>";
							  $cmOptionsHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>VID Digits: </label>".form_input('validate_digits_'.$ctr,$validate_digits,'id="edit_validate_digits_'.$ctr.'" maxlength="3" size="3"')."</span>";
							  $cmOptionsHTML .= "<div style='float:left;padding:10px 10px;font-size:11px;cursor:pointer;'><a style='color:#7A9E22' onclick='showAdvanceMenuOptions($ctr)' class='minMax'><pre style='display:inline'>[+]</pre> Advance Settings</a></div></td>";
							  break;
						 
						 case "DID":
							  $stmt="SELECT did_pattern,did_description from vicidial_inbound_dids order by did_pattern";
							  $didpulldown = $this->asteriskDB->query($stmt);
							  
							  foreach ($didpulldown->result() as $did)
							  {
								   $didArray[$did->did_pattern] = "{$did->did_pattern} - {$did->did_description}";
							  }
							  
							  $cmOptionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\" class=\"option_display_$ctr\"><label>DID: </label>".form_dropdown('option_route_value_'.$ctr,$didArray,$getcallmenuoptionsInfo->option_route_value,'id="option_route_value_'.$ctr.'"')."</td>";
							  break;
						 
						 case "HANGUP":
							  $cmOptionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\" class=\"option_display_$ctr\"><label>Audio File: </label>".form_input('option_route_value_'.$ctr,$getcallmenuoptionsInfo->option_route_value,'id="option_route_value_'.$ctr.'" maxlength="255" size="30"')." <a href=\"javascript:launch_chooser('option_route_value_$ctr','date',1200,document.getElementById('option_route_value_$ctr').value);\"><font color=\"blue\" size=\"1\">[ audio chooser ]</font></a> <div id=\"divoption_route_value_$ctr\" style=\"display:inline\"></div></td>";
							  break;
						 
						 case "EXTENSION":
							  $cmOptionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\" class=\"option_display_$ctr\"><label>Extension: </label>".form_input('option_route_value_'.$ctr,$getcallmenuoptionsInfo->option_route_value,'id="option_route_value_'.$ctr.'"')." <label>Context: </label>".form_input('option_route_value_context_'.$ctr,$getcallmenuoptionsInfo->option_route_value_context,'id="option_route_value_context_'.$ctr.'"')."</td>";
							  break;
						 
						 case "PHONE":
							  $stmt="SELECT extension,server_ip,dialplan_number,voicemail_id FROM phones ORDER BY extension";
							  $phonepulldown = $this->asteriskDB->query($stmt);
							  
							  foreach ($phonepulldown->result() as $phone)
							  {
								   $phoneArray[$phone->extension] = "{$phone->extension} - {$phone->server_ip} - {$phone->dialplan_number} - {$phone->voicemail_id}";
							  }
							  
							  $cmOptionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\" class=\"option_display_$ctr\"><label>Phone: </label>".form_dropdown('option_route_value_'.$ctr,$phoneArray,$getcallmenuoptionsInfo->option_route_value,'id="option_route_value_'.$ctr.'"')."</td>";
							  break;
						 
						 case "VOICEMAIL":
							  $cmOptionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\" class=\"option_display_$ctr\"><label>Voicemail Box: </label>".form_input('option_route_value_'.$ctr,$getcallmenuoptionsInfo->option_route_value,'id="option_route_value_'.$ctr.'" maxlength="255" size="15"')." <a href=\"javascript:launch_vm_chooser('option_route_value_$ctr','date',1200,document.getElementById('option_route_value_$ctr').value);\"><font color=\"blue\" size=\"1\">[ voicemail chooser ]</font></a> <div id=\"divoption_route_value_$ctr\" style=\"display:inline\"></div></td>";
							  break;
						 
						 case "AGI":
							  $cmOptionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\" class=\"option_display_$ctr\"><label>AGI: </label>".form_input('option_route_value_'.$ctr,$getcallmenuoptionsInfo->option_route_value,'id="option_route_value_'.$ctr.'" maxlength="255" size="50"')."</td>";
							  break;
					}
					$cmOptionsHTML .= "</tr>\n";
					
					$ctr++;
			   }
			   
			   while ($ctr < 15)
			   {
				   // onChange="javascript:showoptionpostval(this.options[this.selectedIndex].value,'.$ctr.');"
				   $optionDD = form_dropdown('option_value_'.$ctr,array(''=>'','0','1','2','3','4','5','6','7','8','9','#'=>'#','*'=>'*','TIMECHECK'=>'TIMECHECK','TIMEOUT'=>'TIMEOUT','INVALID'=>'INVALID'),null,'id="option_value_'.$ctr.'" onChange="javascript:checkoptionval(this.options[this.selectedIndex].value,'.$ctr.');"');
				   $optionRoute = form_dropdown('option_route_'.$ctr,array(''=>'','CALLMENU'=>'Call Menu / IVR','INGROUP'=>'In-group','DID'=>'DID','HANGUP'=>'Hangup','EXTENSION'=>'Custom Extension','PHONE'=>'Phone','VOICEMAIL'=>'Voicemail','AGI'=>'AGI'),null,'id="option_route_'.$ctr.'" onChange="javascript:showoptionpostval(\''.$dataval.'\',document.getElementById(\'option_value_'.$ctr.'\').options[document.getElementById(\'option_value_'.$ctr.'\').selectedIndex].value,this.options[this.selectedIndex].value,'.$ctr.');"');
				   $cmOptionsHTML .= "<tr class=\"trview\">";
				   $cmOptionsHTML .= "<td style='padding-left:10px'>Option:</td><td>$optionDD</td>";
				   $cmOptionsHTML .= "<td>Description:</td><td>".form_input('option_description_'.$ctr,null,'maxlength="255" size="30"')."</td>";
				   $cmOptionsHTML .= "<td>Route:</td><td>$optionRoute</td>";
				   $cmOptionsHTML .= "</tr>\n";
				   $cmOptionsHTML .= "<tr class=\"trview option_hidden_$ctr\" style=\"display:none;\">";
				   $cmOptionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\" class=\"option_display_$ctr\"></td>";
				   $cmOptionsHTML .= "</tr>";
				   $ctr++;
			   }
		  }
		  
		  $cmOptionsHTML .= "</table>";
		  return $cmOptionsHTML;
	 }
	  
	 function getcallmenuoptions($menu_id=null,$route_id=null,$optval=null)
	 {
		  if (strlen($route_id) > 0)
			   $routeSQL = "AND option_route='$route_id' AND option_value='$optval'";
		  
		  $stmt="SELECT option_value,option_description,option_route,option_route_value,option_route_value_context FROM vicidial_call_menu_options WHERE menu_id='$menu_id' $routeSQL ORDER BY option_value";
		  
		  $listval = $this->asteriskDB->query($stmt);
		  $ctr = 0;
		  foreach ($listval->result() as $info)
		  {
			   $lists[$ctr] = $info;
			   $ctr++;
		  }
		  
		  return $lists;
	 }
	  
	  ##### get callmenu listings for dynamic pulldown
	  function getcallmenu($userlevel=null,$accounts=null) {
	       $addedMenuSQL = '';
	       if ($this->commonhelper->checkIfTenant($accounts)) {	   
		    $addedMenuSQL = "where user_group='$accounts'";
	       }
	      
	       $stmt="SELECT menu_id,menu_name from vicidial_call_menu $addedMenuSQL order by menu_id";
	       
	       $listval = $this->asteriskDB->query($stmt);
	       $ctr = 0;
	       foreach($listval->result() as $info){
		    $lists[$ctr] = $info;
		    $ctr++;
	       }

	       return $lists;
	  }
	  
	  function ingrouppulldown($userlevel=null,$accounts=null) {
	       $filterSQL = ($this->commonhelper->checkIfTenant($accounts)) ? "and user_group='$accounts'" : "";
		     
	       $stmt="SELECT group_id,group_name from vicidial_inbound_groups where group_id NOT IN('AGENTDIRECT') $filterSQL order by group_id";
	       
	       $listval = $this->asteriskDB->query($stmt);
	       $ctr = 0;
	       foreach($listval->result() as $info){
		    $lists[$ctr] = $info;
		    $ctr++;
	       }
     
	       return $lists;
	  }
     
	  function calltimespulldown($userlevel=null,$accounts=null) {
	       $filterSQL = ($this->commonhelper->checkIfTenant($accounts)) ? "where user_group IN ('---ALL---','$accounts')" : "";
	       $stmt="SELECT call_time_id,call_time_name,ct_default_start,ct_default_stop from vicidial_call_times $filterSQL order by call_time_id";	
	    
	       $listval = $this->asteriskDB->query($stmt);
	       $ctr = 0;
	       foreach($listval->result() as $info){
		    $lists[$ctr] = $info;
		    $ctr++;
	       }
     
	       return $lists;
	  }

	  function groupalias() {
	       $stmt="SELECT group_alias_id,group_alias_name from groups_alias where active='Y' order by group_alias_id";
	       $listval = $this->asteriskDB->query($stmt);
	       $ctr = 0;
	       foreach($listval->result() as $info){
		    $lists[$ctr] = $info;
		    $ctr++;
	       }

	       return $lists;
	  }

	 function go_get_groupid()
	 {
		  $userID = $this->session->userdata('user_name');
		  $query = $this->asteriskDB->query("select user_group from vicidial_users where user='$userID'");
		  $resultsu = $query->row();
		  $groupid = $resultsu->user_group;
		  return $groupid;
	 }
	 
	 function go_get_userfulname()
	 {
		  $userID = $this->session->userdata('user_name');
		  $query = $this->asteriskDB->query("select full_name from vicidial_users where user='$userID';");
		  $resultsu = $query->row();
		  $userfulname = $resultsu->full_name;
		  return $userfulname;
	 }
}
