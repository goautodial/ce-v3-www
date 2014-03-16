<?php
########################################################################################################
####  Name:             	golist.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Jerico James Milo                                        	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Golist extends Model {


     function __construct(){
         parent::Model();
         $this->asteriskDB = $this->load->database('dialerdb',TRUE);
	 $this->customdialerdb = $this->load->database('customdialerdb',TRUE);
     }


     function countlist($wherecampaigns=null,$type=null) {
	  if ($type!=null && $type=='custom') {
	       if (strlen($wherecampaigns) < 1) {
		    $joinSQLcompare = "WHERE vl.list_id=vlf.list_id";
	       } else {
		    $joinSQLcompare = "and vl.list_id=vlf.list_id";
	       }
	       $stmt = "SELECT vl.list_id FROM vicidial_lists AS vl,vicidial_lists_fields AS vlf $wherecampaigns $joinSQLcompare GROUP BY vl.list_id ORDER BY vl.list_id";
	  } else {
	       $stmt = "SELECT list_id FROM vicidial_lists $wherecampaigns order by list_id";
	  }

	  $query = $this->asteriskDB->query($stmt);
	  $row = $query->num_rows();
	  $cntlist = $row;
	  return $cntlist;
     }

     function getalllist($wherecampaigns=null,$allowedcampaign=null,$limit=null,$start=null,$showall=false) {
	 
	 // Added error catcher when $start and $limit variables are null -- Chris
	  if ($start==null || $start < 0)
		  $start = 0;
	  if ($limit==null || $limit < 1)
		  $limit = 25;
	  
	  $limitSQL = (! $showall) ? "LIMIT $start,$limit" : "";
	 
	  $stmt="SELECT list_id,list_name,list_description,IF(active='Y',(SELECT count(*) as tally FROM vicidial_list WHERE list_id = vicidial_lists.list_id),(SELECT count(*) as tally FROM vicidial_list_archive WHERE list_id = vicidial_lists.list_id)) as tally,active,list_lastcalldate,campaign_id,reset_time from vicidial_lists $wherecampaigns order by list_id $limitSQL";
	  //$stmt="SELECT list_id,list_name,list_description,(SELECT count(*) as tally FROM vicidial_list WHERE list_id = vicidial_lists.list_id) as tally,active,list_lastcalldate,campaign_id,reset_time from vicidial_lists $wherecampaigns order by list_id $limitSQL";
	  
	  		
   	  $listall = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listall->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              } 
	  return $lists;
     }

     function getcampaign($wherecampaigns=null) {
	      
	      $stmt="SELECT campaign_id,campaign_name from vicidial_campaigns $wherecampaigns order by campaign_id";
	   
	      $campaignall = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($campaignall->result() as $info){
                  $campaigns[$ctr] = $info;
                  $ctr++;
              }

	      return $campaigns;
     }

     function dlquery($listid=null) {
	  $query = $this->asteriskDB->query("SELECT custom_fields_enabled FROM system_settings;");
	  $custom_fields_enabled = $query->row()->custom_fields_enabled;
	  
	  if ($custom_fields_enabled)
	  {
	       $custom_table = "custom_$listid";
	       $stmt = "SHOW TABLES WHERE Tables_in_".$this->asteriskDB->database." = '$custom_table';";
	       $rslt = $this->asteriskDB->query($stmt);
	       if ($rslt->num_rows() > 0) 
	       {
		    $cllist = $this->asteriskDB->query("DESCRIBE $custom_table;");
		    $u=0;
		    foreach($cllist->result() as $clrow)
		    {
			 $column = $clrow->Field;
			 if ($column!='lead_id')
			      $header_columns .= ",$column";
		    }
		    if ($columns_ct > 1)
		    {
			 $valid_custom_table=1;
		    }
	       }
	       $added_custom_SQL  = ", $custom_table ct";
	       $added_custom_SQL2 = "AND vl.lead_id=ct.lead_id";
	  }

	  $stmt = "SELECT vl.lead_id AS lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner{$header_columns} FROM vicidial_list vl{$added_custom_SQL} WHERE list_id='$listid' $added_custom_SQL2;";

	  $dllist = $this->asteriskDB->query($stmt);
	  $ctr = 0;
	  foreach($dllist->result() as $info){
	       $dllistval[$ctr] = $info;
	       $ctr++;
	  }
	  return $dllistval;
     }

     function insertlist($list_id=null, $list_name=null, $campaign_id=null, $active=null, $list_description=null) {

	if($list_id=="" || $list_id==NULL) {
		$message = "LIST ID NOT ADDED - List ID field is required.";
	}
		
	$stmt = "SELECT count(*) as cntlistid FROM vicidial_lists WHERE list_id='$list_id';";
	$query = $this->asteriskDB->query($stmt);
        $row = $query->row();
	
	if($row->cntlistid > 0) {
		$message = "LIST ID NOT ADDED - there is already a LIST ID in the system with this ID.";
	} else {

	      	$SQLdate = date("Y-m-d H:i:s");
	      	$stmt="INSERT INTO vicidial_lists (list_id,list_name,campaign_id,active,list_description,list_changedate) values('$list_id','$list_name','$campaign_id','$active','$list_description','$SQLdate');";
		$this->asteriskDB->query($stmt);
                $this->commonhelper->auditadmin("ADD","ADD NEW LIST in vicidial_lists values '$list_id','$list_name','$campaign_id','$active','$list_description','$SQLdate'");
	}

		return $message;

     }
     
     function getuserlevel($uname=null) {

	      $query = $this->asteriskDB->query("SELECT user_id,user,pass,full_name,user_level FROM vicidial_users where user='$uname'");
	      $row = $query->row();
	      return $row->user_level;

     }

	   //autogen
     function autogenlist($accnt) {
	      
	      $stmt = "SELECT TRIM(allowed_campaigns) as trimallowedcamp  FROM vicidial_user_groups WHERE user_group='$accnt';";
	    //  $stmt = "SELECT *  FROM vicidial_user_groups;";
	      $query = $this->asteriskDB->query($stmt);
	      $row = $query->row_array();	
	      $allowed_campaigns = $row['trimallowedcamp'];
	      $camp_list=str_replace(" ",",",str_replace(" -","",$allowed_campaigns));
         $allowed_campaigns=str_replace(" ","','",str_replace(" -","",$allowed_campaigns));
	

         $stmt="SELECT TRIM(campaign_name) as trimcampname FROM vicidial_campaigns WHERE campaign_id IN('$allowed_campaigns');";
	 #echo $stmt;
         //$stmt="SELECT * FROM vicidial_campaigns;";
	      $query = $this->asteriskDB->query($stmt);
	      $num = $query->num_rows;		

	      $ctr = 0;
	      foreach ($query->result_array() as $row) {
    			$camp_name[$ctr] = $row['trimcampname'];
				$ctr++;
	      }

	      //$stmt="SELECT list_id FROM vicidial_lists order by list_id desc limit 1;";
	      //$accnt = substr_replace($accnt ,"",-1);

//	      $stmtlist="SELECT list_id FROM vicidial_lists WHERE list_id LIKE '%$accnt%' order by list_id desc limit 1;"; 
//	       
//	      $query = $this->asteriskDB->query($stmtlist);
//	      $num = $query->num_rows;		
//	      $lists = $query->row_array();
//	      $listID = $lists['list_id'];
//	      
//	 
//         #$cnt=str_replace($num,$num,$listID); 
//         $cnt=str_replace(ltrim($accnt."1","",$listID)); 
//    		$cnt=intval("$cnt");
//		
//    			if ($cnt < $num) {
//        			$cnt = $num; 
//				
//    			}
//    			$countcname = strlen($camp_name);
//			
//    			if($countcname > 0 ){
//	       $camp_name = implode(",",$camp_name);
//	       }
//	       
//	       if($listID=="") {
//		    $accntx = $accnt."1";
//	       } else {
//		    $accntx = $listID+1;    
//	       }

	  // Get list_id count
	  $groupId = $this->go_get_groupid();
	  $allowed_campaigns = $this->go_get_allowed_campaigns();
	  // WHERE campaign_id IN ('$allowed_campaigns')
	  $query = $this->db->query("SELECT list_id AS id FROM vicidial_lists ORDER BY list_id DESC LIMIT 1");
	  if ($query->num_rows() > 0)
	  {
		  $list = $query->row();
		  $listIDnum = str_replace($groupId,'',$list->id);
		  $listIDnum = str_replace("---ALL---",'',$listIDnum);
	  } else {
		  $listIDnum = "999";
	  }
	  $cnt = ($listIDnum+1);
	  if ($this->commonhelper->checkIfTenant($groupId) && is_numeric($groupId))
	  {
		  $accntx = "{$groupId}{$cnt}";
	  } else {
		  if ($cnt < 1000) {
			  $cnt = 1000;
			  $cnt = $this->checkListID($cnt);
		  }
		  $accntx = "$cnt";
	  }
	       
 	  $camps = "$cnt\n$camp_list\n$accntx\n".$camp_name;
		
	  return $camps;
     }

     function go_get_allowed_campaigns($raw=false)
     {
	  $groupId = $this->go_get_groupid();
	  if (!$this->commonhelper->checkIfTenant($groupId))
	  {
	     $ul='';
	  }
	  else
	  {
	     $ul = "WHERE user_group='$groupId'";
	  }

	  if (!$raw) {
	       $query = $this->asteriskDB->query("SELECT TRIM(REPLACE(allowed_campaigns,'-','')) AS allowed_campaigns FROM vicidial_user_groups $ul");
	       $row = $query->row();

	       $allowed_campaigns = str_replace(' ',"','",$row->allowed_campaigns);
	  }
	  else
	  {
	       $query = $this->asteriskDB->query("SELECT allowed_campaigns FROM vicidial_user_groups $ul");
	       $row = $query->row();

	       $allowed_campaigns = $row->allowed_campaigns;
	  }
	  return $allowed_campaigns;
     }

     function go_get_groupid()
     {
	  $userID = $this->session->userdata('user_name');
	  $query = $this->asteriskDB->query("select user_group from vicidial_users where user='$userID'");
	  $resultsu = $query->row();
	  $groupid = $resultsu->user_group;
	  return $groupid;
     }
	
     function checkListID($cnt)
     {
	  $query = $this->asteriskDB->query("SELECT count(*) AS cnt FROM vicidial_lists WHERE list_id='$cnt';");
	  while ($query->row()->cnt > 0)
	  {
	       $cnt++;
	       $query = $this->db->query("SELECT count(*) AS cnt FROM vicidial_lists WHERE list_id='$cnt';");
	  }
	  
	  return $cnt;
     }
     
     function showtable($list_id_override) {
     		$tablecount_to_print=0;
			$fieldscount_to_print=0;
			$fields_to_print=0;
								
	  		$stmt="SHOW TABLES LIKE \"custom_$list_id_override\";";
			$syslook = $this->asteriskDB->query($stmt);
			$fieldscount_to_print = $syslook->num_rows;
			
				if ($fieldscount_to_print > 0) {
						$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id_override';";
						$listcount = $this->asteriskDB->query($stmt);
						$fields_to_print = $listcount->num_rows;
						
						if ($fieldscount_to_print > 0) {
							$stmt="SELECT field_label,field_type from vicidial_lists_fields where list_id='$list_id_override' order by field_rank,field_order,field_label;";
							$labelcount = $this->asteriskDB->query($stmt);
							$fields_to_print = $labelcount->num_rows;
							$fields_list='';
							$o=0;
								foreach($labelcount->result() as $info){
                  			$A_field_label[$o] = $info->field_label;
                  			$A_field_type[$o] = $info->field_type;
                  			$A_field_value[$o] =	'';
                  			$o++;
             				} 

						}
				}
	  } // end function

	  function geteditvalues($listid) {
	  		
	  		$stmt="SELECT list_id,list_name,campaign_id,active,list_description,list_changedate,list_lastcalldate,reset_time,agent_script_override,campaign_cid_override,am_message_exten_override,drop_inbound_group_override,xferconf_a_number,xferconf_b_number,xferconf_c_number,xferconf_d_number,xferconf_e_number,web_form_address,web_form_address_two from vicidial_lists where list_id='$listid';";
	  		
	  		$listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }
              

	      return $lists;
	  		
	  }


	  function globalstatus(){
		$stmt="SELECT status,status_name,selectable,human_answered,category,sale,dnc,customer_contact,not_interested,unworkable,scheduled_callback from vicidial_statuses order by status";
	         $listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }
              return $lists;
	  }
 
          function getstatuses($listid) {
		$stmt="SELECT status as stats,called_since_last_reset,count(*) as countvlists FROM vicidial_list where list_id='$listid' group by status,called_since_last_reset order by status,called_since_last_reset";
                        $listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }
              return $lists;
          }

	  function gettzones($listid) {
	      
	      $stmt="SELECT gmt_offset_now,called_since_last_reset,count(*) as counttlist from vicidial_list where list_id='$listid' group by gmt_offset_now,called_since_last_reset order by gmt_offset_now,called_since_last_reset";

              $listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }
              return $lists;
	  }

	  function getdropcopylist($allowed_campaign) {
		
	      $stmt="SELECT list_id,list_name from vicidial_lists $allowed_campaign order by list_id";
		
              $listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }


              return $lists;


	  }

	
	  function editvalues($query=null) {
	  		$this->asteriskDB->query($query);
	  }
	  
	  function deletevalues($query=null) {
	  		$this->asteriskDB->query($query);
	  }
	  
	  function resetleads($query=null) {
	  		$this->asteriskDB->query($query);
	  }
	  
	  function getphonecodes() {
	  	
	  		$stmt="select distinct country_code, country from vicidial_phone_codes;";
	  		$pcodes = $this->asteriskDB->query($stmt);
	  		$ctr = 0;
              foreach($pcodes->result() as $info){
                  $phonecodes[$ctr] = $info;
                  $ctr++;
              }

	      return $phonecodes;
	  		
	  }
	  
	  function systemsettingslookup() {

	  		$stmt = "SELECT use_non_latin,admin_web_directory,custom_fields_enabled FROM system_settings;";
	  		$syslook = $this->asteriskDB->query($stmt);
	  		$ctr = 0;
              foreach($syslook->result() as $info){
                  $syslookup[$ctr] = $info;
                  $ctr++;
              }

	      return $syslookup;
	  		
	  }
	  
	  function getlistme() {
			$fields_stmt = "SELECT vendor_lead_code, source_id, list_id, phone_code, phone_number, title, first_name, middle_initial, last_name, address1, address2, address3, city, state, province, postal_code, country_code, gender, date_of_birth, alt_phone, email, security_phrase, comments, rank, owner from vicidial_list limit 1";
			$fields = $this->asteriskDB->query($fields_stmt);
	  		$ctr = 0;
              foreach($fields->result() as $info){
                  $allfields[$ctr] = $info;
                  $ctr++;
              }

	      return $allfields;
	  }	  
	  
//	  function getaccounts($accountnum=null) {
//		
//		  // $stmt = "SELECT TRIM(allowed_campaigns) as trimallowedcamp  FROM vicidial_user_groups WHERE user_group='$accnt';";	
//		
//			/*
//			$query = $this->asteriskDB->query("SELECT account_num,company FROM a2billing_wizard WHERE account_num='$accountnum' ORDER BY company");
//	      	$row = $query->row();
//	      	return $row->user_level;
//	      */
//	      
//	      $stmt="SELECT account_num,company FROM a2billing_wizard WHERE account_num='$accountnum' ORDER BY company";	
//   	      $acctno = $this->asteriskDB->query($stmt);
//              $ctr = 0;
//              foreach($acctno->result() as $info){
//                  $accounts[$ctr] = $info;
//                  $ctr++;
//              } 
//
//              return $accounts;
//     }

     function getinboundgrp($wherecampaigns=null) {
	     
	      $stmt="";
	      $inboundgrp = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($inboundgrp->result() as $info){
                  $inboundgrps[$ctr] = $info;
                  $ctr++;
              }

	      return $inboundgrps;
     }

					function getactivecustom() {
					  $stmt = "SELECT DISTINCT (vlf.list_id) as listids, vl.list_name FROM  `vicidial_lists` vl,  `vicidial_lists_fields` vlf WHERE vl.list_id = vlf.list_id";	
					  	      $inboundgrp = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($inboundgrp->result() as $info){
                  $inboundgrps[$ctr] = $info;
                  $ctr++;
              }

	      return $inboundgrps;
					}

     
     function getcustomlist($whereLOGallowed_campaignsSQL,$limit=null,$start=null){
	  $base = base_url();
	  // Added error catcher when $start and $limit variables are null -- Chris
	  if ($start==null || $start < 0)
		  $start = 0;
	  if ($limit==null || $limit < 1)
		  $limit = 25;
		  
	  if (strlen($whereLOGallowed_campaignsSQL) < 1) {
	       $joinSQLcompare = "WHERE vl.list_id=vlf.list_id";
	  } else {
	       $joinSQLcompare = "and vl.list_id=vlf.list_id";
	  }
	  $stmt="SELECT vl.list_id,list_name,active,campaign_id from vicidial_lists as vl,vicidial_lists_fields as vlf $whereLOGallowed_campaignsSQL $joinSQLcompare group by vl.list_id order by vl.list_id limit $start,$limit;";
	  $clist = $this->asteriskDB->query($stmt);
	  $lists_to_print = $clist->num_rows;
	  $o = 0;
       	  foreach($clist->result() as $info) {
	       $clistss[$o] = $info;
	       $o++;
          }
			
	  $countalls = count($clistss);
	  if($lists_to_print > 0) {
	       foreach($clistss as $clistInfo) {
		    $A_list_id = $clistInfo->list_id;
		    $A_list_name = $clistInfo->list_name;
		    $A_active = $clistInfo->active;
		    $A_campaign_id = $clistInfo->campaign_id;
		    $stmt2 = "SELECT count(*) as countfields from vicidial_lists_fields where list_id='$A_list_id'";
		    $custlist = $this->asteriskDB->query($stmt2);
		    $o=0;
		       
		    foreach($custlist->result() as $info2) {
			 $clistss2[$o] = $info2;
			 $o++;
		    }	

//		            $custall .="<br>";

		    foreach($clistss2 as $clistInfo2) {
			 $fieldscount = $clistInfo2->countfields;
			 if($fieldscount > 0) {
			      $custall .= "<tr align=left class=tr".alternator('1', '2').">";
			      $custall .= "<td align=left>&nbsp;&nbsp;";
			      $custall .= "<a id=\"activator\" class=\"activator\" title=\"VIEW EXAMPLE CUSTOM FIELD\"  onClick=\"customviews('$A_list_id?>');\">$A_list_id</a>";
			      $custall .= "</td>";
			      $custall .= "<td align=left style=\"\">".ucwords(strtolower($A_list_name))."</td>";
				      
			      if($A_active =="Y") {
				   $A_active = "<b><font color=green>ACTIVE</font></b>";
			      } else {
				   $A_active = "<b><font color=red>INACTIVE</font></b>";
			      }	
						      
			      $custall .= "<td align=left>$A_active</td>";
			      $custall .= "<td align=left>$A_campaign_id &nbsp;</td>";
			      $custall .= "<td align=left style=\"\">$fieldscount</td>";
			      $custall .= "<td align=left style=\"\">&nbsp;</td>";
			      
			      $custall .= "<td align=right style=\"padding:1.5px;\" colspan=\"3\">";
			      $custall .= " <a href=".$base."index.php/go_list/editcustomlist/$A_list_id title=\"MODIFY CUSTOM FIELD $A_list_id\">";
			      $custall .="<img src=".$base."img/edit.png style=cursor:pointer;width:14px; title=\"MODIFY CUSTOM FIELD $A_list_id\" /></a>&nbsp;\n";
			      $custall .="<img src=".$base."img/delete_grayed.png style=cursor:pointer;width:12px; title=\"CUSTOM FIELD $A_list_id\" /></a>&nbsp;\n";
			      $custall .="<img src=".$base."img/status_display_i.png style=cursor:pointer;width:14px; title=\"VIEW EXAMPLE CUSTOM FIELD $A_list_id\" onClick=\"customviews('$A_list_id?>');\" /></a></td>";
			      $custall .="<td align=center style=\"\"><input type=\"checkbox\" name=\"grayedcheck\" disabled></td>"; 
			      $custall .="</tr>\n";
			 }
		    }
	       }
	  }

          return $custall;
     }
     
	  function custeditview($list_id) {
				$base = base_url();
				$vicidial_list_fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_reset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|';

			   $custom_records_count=0;
				$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
				$rslt = $this->asteriskDB->query($stmt);
				$tablecount_to_print = $rslt->num_rows;
				if ($tablecount_to_print > 0) 
					{$table_exists =	1;}
				if ($table_exists > 0){
					$stmt="SELECT count(*) as countcustom from custom_$list_id;";
					$rslt = $this->asteriskDB->query($stmt);
					$fieldscount_to_print = $rslt->num_rows;
					if ($fieldscount_to_print > 0) {
						$rowx=$rslt->row();
						$custom_records_count =	$rowx->countcustom;
						}
					}
				
		
			$stmt="SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,
				field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='$list_id' order by field_rank,field_order,field_label;";
				
		
			$rslt=$this->asteriskDB->query($stmt);
			$fields_to_print = $rslt->num_rows;
			$fields_list='';
			$o=0;

       		foreach($rslt->result() as $info){
                  $fieldsval[$o] = $info;
                  $o++;
              }
			
				
				
				$o++;
				$rank_select .= "<option>$o</option>";
				$last_rank = $o;

				
	
					### SUMMARY OF FIELDS ###
//					$field_HTML .= "<br>";
					$field_HTML .= "<form name=\"formcustomview\" id=\"formcustomview\">";
/*					$field_HTML .= "<center>";
								
					$field_HTML .= "<table id=\"customlisttableresult\" class=\"tablesorter\" width=\"100%\" class=\"\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"margin-left:auto; margin-right:auto; width:100%;\" >"; 
					$field_HTML .= "<tr align=\"left\" class=\"nowrap\">";
					$field_HTML .= "<td class=\"thheader\" align=\"left\"><b>RANK</b> </td>";
					$field_HTML .= "<td class=\"thheader\" align=\"left\"><b>LABEL</b> </td>";
					$field_HTML .= "<td class=\"thheader\" align=\"left\"><b>NAME</b> </td>";
					$field_HTML .= "<td class=\"thheader\" align=\"left\"><b>TYPE</b> </td>";
					$field_HTML .= "<td colspan=\"4\" class=\"thheader\" style=\"width:8%;\" align=\"right\">";
					$field_HTML .= "<span style=\"cursor:pointer;\" id=\"selectAction\"><b>&nbsp;ACTION &nbsp;</b><img src=\"$base/img/arrow_down.png\" />&nbsp;</span>";							
					$field_HTML .="</td></tr>";		
*/	
				
					$fieldcount = count($fieldsval);
					if($fieldcount > 0) {
					
						foreach($fieldsval as $fieldsvalues){
						
						$A_field_id =			$fieldsvalues->field_id;
						$A_field_label =		$fieldsvalues->field_label;
						$A_field_name =			$fieldsvalues->field_name;
						$A_field_description =	$fieldsvalues->field_description;
						$A_field_rank =			$fieldsvalues->field_rank;
						$A_field_help =			$fieldsvalues->field_help;
						$A_field_type =			$fieldsvalues->field_type;
						$A_field_options =		$fieldsvalues->field_options;
						$A_field_size =			$fieldsvalues->field_size;
						$A_field_max =			$fieldsvalues->field_max;
						$A_field_default =		$fieldsvalues->field_default;
						$A_field_cost =			$fieldsvalues->field_cost;
						$A_field_required =		$fieldsvalues->field_required;
						$A_multi_position =		$fieldsvalues->multi_position;
						$A_name_position =		$fieldsvalues->name_position;
						$A_field_order =		$fieldsvalues->field_order;
						$rank_select .= "<option>$o</option>";
	
	
						$field_HTML .= "<tr class=tr".alternator('1', '2').">";
						$field_HTML .=  "<td align=\"left\" style=\"padding-bottom:-1px;\">$A_field_rank - $A_field_order &nbsp; &nbsp; </td>";
						$field_HTML .=  "<td align=\"left\" style=\"padding-bottom:-1px;\">$A_field_label &nbsp; &nbsp; </td>";
						$field_HTML .=  "<td align=\"left\" style=\"padding-bottom:-1px;\"> $A_field_name &nbsp; &nbsp; </td>";
						$field_HTML .=  "<td align=\"left\" style=\"padding-bottom:-1px; padding: 3px;\"> $A_field_type &nbsp; &nbsp; </td>";
						$field_HTML .=  "<td align=\"left\" style=\"padding-bottom:-1px;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
						$field_HTML .=  "<td align=\"right\" style=\"padding-bottom:-1px;\"><a id=\"activator\"  class=\"xrigth toolTip\"  onClick=\"custompostval('$A_field_id','$list_id');\" title=\"MODIFY $A_field_label\"> &nbsp;&nbsp;<img src=\"$base/img/edit.png\" style=\"cursor:pointer;width:14px;\">&nbsp;&nbsp;</a></td>";
						
						$field_HTML .=  "<td align=\"right\" style=\"padding-bottom:-1px;\"><a id=\"activator\" class=\"xrigth toolTip\" title=\"DELETE $A_field_label\"> <img src=\"$base/img/delete.png\" style=\"cursor:pointer;width:12px;\" onClick=\"delpostval('$A_field_label','$list_id','$A_field_id');\">&nbsp;&nbsp;</a> </td>\n";
						
						 $field_HTML .=  "<td align=\"right\" style=\"padding-bottom:-1px;\"><img  src=\"$base/img/status_display_i.png\" onclick=\"customviews('$list_id');\" style=\"cursor:pointer;width:14px;\" class=\"xrigth toolTip\" style=\"cursor:pointer;width:12px;\" title=\"VIEW CREATED CUSTOM FIELD\"></td>";
					
$field_HTML .=  "<td align=\"center\" width=\"26px\" style=\"margin-top:-1px;padding-bottom:-1px;\"><input type=\"checkbox\" id=\"delCampaign[]\" value=\"$A_field_id\" /></td>";
$field_HTML .= "</tr>\n";
								 
										
						$total_cost = ($total_cost + $A_field_cost);
						
						
						}
					} else {
						$field_HTML .=  "<TR>";
						$field_HTML .=  "<TD align=\"center\" colspan=\"5\"><b>No custom field created</b></TD>";
						$field_HTML .=  "</TR>\n";
					}				
					
			

					$field_HTML .=  "</form>\n";

					return $field_HTML;
					
		}  // end function
		
		
		function customeditfield($fieldid,$listid) {

			$stmt = "SELECT `field_id`, `list_id`, `field_label`, `field_name`, `field_description`, `field_rank`, `field_help`, `field_type`, `field_options`, `field_size`, `field_max`, `field_default`, `field_cost`, `field_required`, `name_position`, `multi_position`, `field_order` FROM `vicidial_lists_fields` WHERE `field_id`='$fieldid' and `list_id` = '$listid' ";					
						
			$fields = $this->asteriskDB->query($stmt);
	  		$ctr = 0;
              foreach($fields->result() as $info){
                  $allfields[$ctr] = $info;
                  $ctr++;
              }

	      return $allfields;
			
		}
		
		function countfields($listid) {
			$stmt = "SELECT * FROM vicidial_lists_fields WHERE list_id='$listid'";
			$rslt = $this->asteriskDB->query($stmt);
			$countfields = $rslt->num_rows;
			
			return $countfields;
			
		}
		
		function customviewmodel($list_id) {
			
			$custom_records_count=0;
				$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
				$rslt = $this->asteriskDB->query($stmt);
				$tablecount_to_print = $rslt->num_rows;
				if ($tablecount_to_print > 0) 
					{$table_exists =	1;}
				if ($table_exists > 0){
					$stmt="SELECT count(*) as countcustom from custom_$list_id;";
					$rslt = $this->asteriskDB->query($stmt);
					$fieldscount_to_print = $rslt->num_rows;
					if ($fieldscount_to_print > 0) {
						$rowx=$rslt->row();
						$custom_records_count =	$rowx->countcustom;
						}
					}
				
		
			$stmt="SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,
				field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='$list_id' order by field_rank,field_order,field_label;";
				
		
			$rslt=$this->asteriskDB->query($stmt);
			$fields_to_print = $rslt->num_rows;
			$fields_list='';
			$o=0;

       		foreach($rslt->result() as $info){
                  $fieldsval[$o] = $info;
                  $o++;
              }	
              
              return $fieldsval;
              
		}
		
	    function copymodifycustomfield($table_exists,$field_id,$list_id,$field_label,$field_name,$field_description,$field_rank,$field_help,$field_type,$field_options,$field_size,$field_max,$field_default,$field_required,$field_cost,$multi_position,$name_position,$field_order,$vicidial_list_fields) {
		$field_sql = "ALTER TABLE custom_$list_id MODIFY $field_label ";
		echo $field_sql;
		$field_options_ENUM='';
		$field_cost=1;
		if ( ($field_type=='SELECT') or ($field_type=='RADIO') )
			{
			$field_options_array = explode("\n",$field_options);
			$field_options_count = count($field_options_array);
			$te=0;
			while ($te < $field_options_count)
				{
				if (preg_match("/,/",$field_options_array[$te]))
					{
					$field_options_value_array = explode(",",$field_options_array[$te]);
					$field_options_ENUM .= "'$field_options_value_array[0]',";
					}
				$te++;
				}
			$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
			$field_sql .= "ENUM($field_options_ENUM) ";
			$field_cost = strlen($field_options_ENUM);
			}
		if ( ($field_type=='MULTI') or ($field_type=='CHECKBOX') )
			{
			$field_options_array = explode("\n",$field_options);
			$field_options_count = count($field_options_array);
			$te=0;
			while ($te < $field_options_count)
				{
				if (preg_match("/,/",$field_options_array[$te]))
					{
					$field_options_value_array = explode(",",$field_options_array[$te]);
					$field_options_ENUM .= "'$field_options_value_array[0]',";
					}
				$te++;
				}
			$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
			$field_cost = strlen($field_options_ENUM);
			$field_sql .= "VARCHAR($field_cost) ";
			}
		if ($field_type=='TEXT') 
			{
			$field_sql .= "VARCHAR($field_max) ";
			$field_cost = ($field_max + $field_cost);
			}
		if ($field_type=='AREA') 
			{
			$field_sql .= "TEXT ";
			$field_cost = 15;
			}
		if ($field_type=='DATE') 
			{
			$field_sql .= "DATE ";
			$field_cost = 10;
			}
		if ($field_type=='TIME') 
			{
			$field_sql .= "TIME ";
			$field_cost = 8;
			}
		$field_cost = ($field_cost * 3); # account for utf8 database

		if ( ($field_default == 'NULL') or ($field_type=='AREA') or ($field_type=='DATE') or ($field_type=='TIME') )
			{$field_sql .= ";";}
		else
			{$field_sql .= "default '$field_default';";}

		if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/",$vicidial_list_fields)) )
			{
			if ($DB) {echo "Non-DB $field_type field type, $field_label\n";} 
			}
		else
			{
			$stmtCUSTOM="$field_sql";
			$this->customdialerdb->query($stmtCUSTOM);
			}

		$stmt="UPDATE vicidial_lists_fields set field_label='$field_label',field_name='$field_name',field_description='$field_description',field_rank='$field_rank',field_help='$field_help',field_type='$field_type',field_options='$field_options',field_size='$field_size',field_max='$field_max',field_default='$field_default',field_required='$field_required',field_cost='$field_cost',multi_position='$multi_position',name_position='$name_position',field_order='$field_order' where list_id='$list_id' and field_id='$field_id';";
		
		$this->customdialerdb->query($stmt);
    
	    }
	    
	    function copydeletecustomfield($table_exists,$field_id,$list_id,$field_label,$field_name,$field_description,$field_rank,$field_help,$field_type,$field_options,$field_size,$field_max,$field_default,$field_required,$field_cost,$multi_position,$name_position,$field_order,$vicidial_list_fields) {
	    	if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/",$vicidial_list_fields)) )
		{
		if ($DB) {echo "Non-DB $field_type field type, $field_label\n";} 
		}
		else
			{
			$stmtCUSTOM="ALTER TABLE custom_$list_id DROP $field_label;";
			$this->customdialerdb->query($stmtCUSTOM);
			}

		$stmt="DELETE FROM vicidial_lists_fields WHERE field_label='$field_label' and field_id='$field_id' and list_id='$list_id' LIMIT 1;";
		$this->customdialerdb->query($stmt);

	    }
		
	    function copyaddcustomfield($table_exists,$field_id,$list_id,$field_label,$field_name,$field_description,$field_rank,$field_help,$field_type,$field_options,$field_size,$field_max,$field_default,$field_required,$field_cost,$multi_position,$name_position,$field_order,$vicidial_list_fields){
	    	$table_exists=0;
		$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
		$rslt=$this->customdialerdb->query($stmt);
		$tablecount_to_print = $rslt->num_rows();
		if ($tablecount_to_print > 0) 
			{$table_exists =	1;}
		#if ($rslt>0) {echo "$stmt|$tablecount_to_print|$table_exists";}

		if ($table_exists < 1)
			{$field_sql = "CREATE TABLE custom_$list_id (lead_id INT(9) UNSIGNED PRIMARY KEY NOT NULL, $field_label ";}
		else
			{$field_sql = "ALTER TABLE custom_$list_id ADD $field_label ";}

		$field_options_ENUM='';
		$field_cost=1;
		if ( ($field_type=='SELECT') or ($field_type=='RADIO') )
			{
			$field_options_array = explode("\n",$field_options);
			$field_options_count = count($field_options_array);
			$te=0;
			while ($te < $field_options_count)
				{
				if (preg_match("/,/",$field_options_array[$te]))
					{
					$field_options_value_array = explode(",",$field_options_array[$te]);
					$field_options_ENUM .= "'$field_options_value_array[0]',";
					}
				$te++;
				}
			$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
			$field_sql .= "ENUM($field_options_ENUM) ";
			$field_cost = strlen($field_options_ENUM);
			}
		if ( ($field_type=='MULTI') or ($field_type=='CHECKBOX') )
			{
			$field_options_array = explode("\n",$field_options);
			$field_options_count = count($field_options_array);
			$te=0;
			while ($te < $field_options_count)
				{
				if (preg_match("/,/",$field_options_array[$te]))
					{
					$field_options_value_array = explode(",",$field_options_array[$te]);
					$field_options_ENUM .= "'$field_options_value_array[0]',";
					}
				$te++;
				}
			$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
			$field_cost = strlen($field_options_ENUM);
			if ($field_cost < 1) {$field_cost=1;};
			$field_sql .= "VARCHAR($field_cost) ";
			}
		if ($field_type=='TEXT') 
			{
			if ($field_max < 1) {$field_max=1;};
			$field_sql .= "VARCHAR($field_max) ";
			$field_cost = ($field_max + $field_cost);
			}
		if ($field_type=='AREA') 
			{
			$field_sql .= "TEXT ";
			$field_cost = 15;
			}
		if ($field_type=='DATE') 
			{
			$field_sql .= "DATE ";
			$field_cost = 10;
			}
		if ($field_type=='TIME') 
			{
			$field_sql .= "TIME ";
			$field_cost = 8;
			}
		$field_cost = ($field_cost * 3); # account for utf8 database

		if ( ($field_default != 'NULL') and ($field_type!='AREA') and ($field_type!='DATE') and ($field_type!='TIME') )
			{$field_sql .= "default '$field_default'";}

		if ($table_exists < 1)
			{$field_sql .= ");";}
		else
			{$field_sql .= ";";}

		if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/",$vicidial_list_fields)) )
			{
			if ($DB) {echo "Non-DB $field_type field type, $field_label\n";} 
			}
		else
			{
			$stmtCUSTOM="$field_sql";
			$this->customdialerdb->query($stmtCUSTOM);
			}

		$stmtins="INSERT INTO vicidial_lists_fields set field_label='$field_label',field_name='$field_name',field_description='$field_description',field_rank='$field_rank',field_help='$field_help',field_type='$field_type',field_options='$field_options',field_size='$field_size',field_max='$field_max',field_default='$field_default',field_required='$field_required',field_cost='$field_cost',list_id='$list_id',multi_position='$multi_position',name_position='$name_position',field_order='$field_order';";
		$this->customdialerdb->query($stmtins);
	  
	    }
		
     
}

?>
