<?php
############################################################################################
####  Name:             go_values.php                                                   ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Jerico James F. Milo                                            ####
####  Modified by:      Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################

	$this->asteriskDB = $this->load->database('dialerdb',TRUE);
	//$this->a2billingDB =$this->load->database('billingdb',TRUE) ;
	$this->load->model('goingroup');



	//post variables
	$vicidial_list_fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_r eset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|';
	$items = $this->input->post('items');
	$itemsumit = $this->input->post('itemsumit');
	$action = $this->input->post('action');
	$list_id = $this->input->post('listid');
	$field_label = $this->input->post('field_label');
	$field_rank = $this->input->post('field_rank');
	$field_order = $this->input->post('field_order');
	$field_name = $this->input->post('field_name');
	$name_position = $this->input->post('name_position');
	$field_description = $this->input->post('field_description');
	$field_type = $this->input->post('field_type');
	$field_options = $this->input->post('field_options');
	$multi_position = $this->input->post('multi_position');
	$field_size = $this->input->post('field_size');
	$field_max = $this->input->post('field_max');
	$field_default = $this->input->post('field_default');
	$field_required = $this->input->post('field_required');
	$field_id = $this->input->post('field_id');

/* start lists */
	if($action=="editlistfinal") {
		
		$itemsumitexplode = explode('&', str_replace(';', '',$this->input->post('itemsumit')));
		$listid_data = split('=', $itemsumitexplode[2]);
		$listid_data = htmlspecialchars(urldecode($listid_data[1]));
		$webformdatasplit = split('=', $itemsumitexplode[6]);
		$webformdata = htmlspecialchars(urldecode($webformdatasplit[1]));
		$webformdata_field = htmlspecialchars(urldecode($webformdatasplit[0]));
		
		
		if(is_null($sucesshint)) {
	 		
	 		for( $i = 3; $i < count( $itemsumitexplode ); $i++ ) {
	 			
	 			$itemsumitsplit = split('=', $itemsumitexplode[$i]);
	 			$showval = htmlspecialchars(urldecode($itemsumitsplit[0]));
				$datavals = htmlspecialchars(urldecode($itemsumitsplit[1]));
				$showval = str_replace(';', '',$showval);
				$finalvalues = "".$showval."='".mysql_real_escape_string($datavals)."' "."";
				$squote = "'\". ";
				$equote = " .\"' ";
				
				if($showval=="web_form_address") {
					
					if(!is_null($webformdata)){
						$webset = "web_form_address = ". $this->asteriskDB->escape($webformdata);
						$finalvaluesweb = "".$webset;
						$querywebset = "UPDATE vicidial_inbound_groups set ".$finalvaluesweb." WHERE group_id='".$listid_data."';";
						$this->goingroup->editvalues($querywebset);
					}
					
				} else {
					   $query = "UPDATE vicidial_inbound_groups set ".$finalvalues." WHERE group_id='".$listid_data."';";
					   $this->goingroup->editvalues($query);
					   $query_log .= "$query\n";
				}

			} //end for
			
	/*			$SQLdate = date("Y-m-d H:i:s");
				$querydate="UPDATE vicidial_lists SET list_changedate='$SQLdate' WHERE list_id='$listid_data';";
				$this->golist->editvalues($querydate);
			*/
				$this->commonhelper->auditadmin('MODIFY',"Modified Group ID: $listid_data","$query_log");
				$sucesshint = "SUCCESS";
		} 
			
			if($sucesshint=="FAILED") {
				echo "FAILED: Group I.D. $listid_data modified";
				$sucesshint = "FAILED";
			} else {
				echo "SUCCESS: Group I.D. $listid_data modified";
				$sucesshint = "";
			}
	}
	
	if($action=="editdidfinal") {
		
		$itemsumitexplode = explode('&', $this->input->post('itemsumit'));
		$didid_data = split('=', $itemsumitexplode[2]);
		$didid_data = htmlspecialchars(urldecode($didid_data[1]));
		if(is_null($sucesshint)) {
			for( $i = 3; $i < count( $itemsumitexplode ); $i++ ) {
				
				$itemsumitsplit = split('=', $itemsumitexplode[$i]);
	 			$showval = htmlspecialchars(urldecode($itemsumitsplit[0]));
				$datavals = htmlspecialchars(urldecode($itemsumitsplit[1]));
				$showval = str_replace(';', '',$showval);
				$finalvalues = "".$showval."='".mysql_real_escape_string($datavals)."' "."";
				
				$stmt ="UPDATE vicidial_inbound_dids set ".$finalvalues." WHERE did_id='".$didid_data."';";
				$this->goingroup->editvalues($stmt);
				$query_log .= "$stmt\n";
			}
			$sucesshint = "SUCCESS";
			$this->commonhelper->auditadmin('MODIFY',"Modified DID ID: $didid_data","$query_log");
		}		
		
		if($sucesshint=="FAILED") {
				echo "FAILED: DID modified";
				$sucesshint = "FAILED";
			} else {
				echo "SUCCESS: DID modified";
				$sucesshint = "";
		}
	}
	
	if($action=="editcallmenufinal") {
		//chris
		$itemsumitexplode = explode('&', str_replace(';', '',$this->input->post('itemsubmit')));
		$callmenu_data = split('=', $itemsumitexplode[0]);
		$callmenu_data = htmlspecialchars(urldecode($callmenu_data[1]));
		$itemField = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? 13 : 14;
		if(is_null($sucesshint)) {
			$stmtx = "UPDATE vicidial_call_menu SET";
			for($x=3;$x<$itemField;$x++) {
				
				$submititemsplit = explode('=', $itemsumitexplode[$x], 2);
	 			$showvalx = htmlspecialchars(urldecode($submititemsplit[0]));
				$datavalsx = htmlspecialchars(urldecode($submititemsplit[1]));
				
				$stmtx .= " $showvalx='$datavalsx',";
			}
			$stmtx = rtrim($stmtx,",");
			$stmtx .= " WHERE menu_id='$callmenu_data'";  # END DEFAUL UPDATE Details
			
			if(strlen($stmtx)){
				$this->goingroup->editvalues($stmtx);
			}
			
            for ($i=0;$i<$itemField;$i++) {
                $stmt = "UPDATE vicidial_call_menu_options SET";
                for ($j=$itemField;$j<count($itemsumitexplode);$j++) {
					$itemsumitsplit = explode('=', $itemsumitexplode[$j]);
					$showval = htmlspecialchars(urldecode($itemsumitsplit[0]));
					$datavals = htmlspecialchars(urldecode($itemsumitsplit[1]));
					if((preg_match("/^option_value_$i/",$showval)) && strlen($datavals) > 0){
						if(is_null($opt_value)){
							$stmt .= " option_value='$datavals',";
							$opt_value=$datavals;
						}
					}

					if (preg_match("/^option_description_$i/",$showval)) {
                        if(is_null($opt_desc)){
							$stmt .= " option_description='$datavals',";
                            $opt_desc = false;
                        }
                    }

					if ((preg_match("/^option_route_$i/",$showval) && strlen($datavals) > 0)) {
                        if(is_null($opt_route)){
							$stmt .= " option_route='$datavals',";
                            $opt_route = $datavals;
						}
					}

					if (preg_match("/^option_route_value_$i/",$showval)){
						if(is_null($opt_routeVal)){
							$stmt .= " option_route_value='$datavals'";
							$opt_routeVal = false;
						}
					}

					switch ($opt_route) {
						case "INGROUP":
							switch($showval){
								case (preg_match("/^handle_method_$i/",$showval)? true : false ):
								case (preg_match("/^search_method_$i/",$showval)? true : false ):
								case (preg_match("/^list_id_$i/",$showval)? true : false ):
								case (preg_match("/^campaign_id_$i/",$showval)? true : false ):
								case (preg_match("/^phone_code_$i/",$showval)? true : false ):
								case (preg_match("/^enter_filename_$i/",$showval)? true : false ):
								case (preg_match("/^id_number_filename_$i/",$showval)? true : false ):
								case (preg_match("/^confirm_filename_$i/",$showval)? true : false ):
								case (preg_match("/^validate_digits_$i/",$showval)? true : false ):
								$opt_routeValCont .= ",$datavals";
								break;
							}
							break;
						
						case "EXTENSION":
							if((preg_match("/^option_route_value_context_$i/",$showval) && strlen($datavals) > 0)){
								if(is_null($opt_routeValCont)){
									$opt_routeValCont = $datavals;
								}
							}
							break;
					}

                    # clean the stmt last comma if opt_route_$i has no detail

                }
				
				$stmt = rtrim($stmt,",");
				$stmt .= ",option_route_value_context='".ltrim($opt_routeValCont,",")."' WHERE menu_id='$callmenu_data' AND option_value='$opt_value'";  # END DEFAUL UPDATE Details
				/* override #stmt if opt_value is null empty string
				 * else check if we are going to insert or 
				 * retain default in updating data
				 */
				if (is_null($opt_value)) {
				   $stmt = "";
				} else {
					$this->goingroup->asteriskDB->where(array('option_value'=>$opt_value,'menu_id'=>$callmenu_data));
					$inOption = $this->goingroup->asteriskDB->get('vicidial_call_menu_options');
					if ($inOption->num_rows() == 0) {
						$stmt = preg_replace("/UPDATE vicidial_call_menu_options SET /","",$stmt);
						$stmt = preg_replace("/ WHERE menu_id='{$callmenu_data}' AND option_value='{$opt_value}'/","",$stmt);
						$datas = explode(',',$stmt);
		
						$stmt = "INSERT INTO vicidial_call_menu_options VALUES('$callmenu_data',";
						foreach ($datas as $newdata) {
							$dData = explode("=",$newdata);
							if ($dData[0]!='option_route_value_context') {
								$stmt .= "{$dData[1]},";
							}
							if ($dData[0]=='option_route_value_context') {
								$stmt .= "'".ltrim($opt_routeValCont,",")."'";
							}
							if(count($datas)==2){
								$stmt .= "'',";
							}
						}
						$stmt = rtrim($stmt,",");
						//if($opt_route !== 'INGROUP' && $opt_route !== 'EXTENSION'){
						//   $stmt .= ",'".ltrim($opt_routeValCont,",")."'";
						//} else {
						   //$stmt .= ",''";
						//}
						$stmt .= ")";
					}
				}
				/* Check if $opt_route is equal to DEL
				 * if true, the option will be deleted
				 */
				if ($opt_route=="DEL")
				{
					$stmt = "DELETE FROM vicidial_call_menu_options WHERE menu_id='$callmenu_data' AND option_value='$opt_value'";
				}
				
				unset($opt_value);
				unset($opt_desc);
				unset($opt_route);
				unset($opt_routeVal);
				unset($opt_routeValCont);
				
				if (strlen($stmt)) {
					$this->goingroup->editvalues($stmt);
				}
			}

			$this->commonhelper->auditadmin('MODIFY',"Modified Call Menu ID: $callmenu_data","$stmtx\n$stmt");
			$sucesshint = "SUCCESS";
		}		
		
		if($sucesshint=="FAILED") {
			echo "FAILED: CALLMENU modified";
			$sucesshint = "FAILED";
		} else {
			echo "SUCCESS: CALLMENU modified";
			$sucesshint = "";
		}
	}
	
	if($action=="deletedid") {
		$didids = $this->input->post('didid_delete');
		if (!$didids) {
			$didids = $this->uri->segment(3);
		}

		if(is_null($sucesshint) && !is_null($didids)) {
			$didids = explode(",",$didids);
			foreach ($didids as $dididdel) {
				$stmt="DELETE from vicidial_inbound_dids where did_id='$dididdel' limit 1;";
				$this->goingroup->deletevalues($stmt);
				
				$this->commonhelper->auditadmin('DELETE',"Deleted DID: $dididdel","$stmt");
				$sucesshint = "SUCCESS";
			}
		}
		
		if($sucesshint=="FAILED") {
			echo "FAILED: DID I.D. $listiddel deleted";
			$sucesshint = "FAILED";
		} else {
			echo "SUCCESS: DID I.D. $listiddel deleted";
			
			$sucesshint = "";
		}
	}
	
	if($action=="deletelist") {
		$listids = $this->input->post('listid_delete');
		if (!$listids) {
			$listids = $this->uri->segment(3);
		}
		
		if(is_null($sucesshint) && !is_null($listids)) {
			$listids = explode(",",$listids);
			foreach ($listids as $listiddel) {
				/*$querydelete = "DELETE FROM vicidial_lists WHERE list_id='$listiddel'; ";
				$this->golist->deletevalues($querydelete);*/
				$stmtA = "DELETE from vicidial_inbound_groups where group_id='$listiddel' and group_id NOT IN('AGENTDIRECT') limit 1;";
				$stmtB = "DELETE from vicidial_inbound_group_agents where group_id='$listiddel';";
				$stmtC = "DELETE from vicidial_live_inbound_agents where group_id='$listiddel';";
				$stmtD = "DELETE from vicidial_campaign_stats where campaign_id='$listiddel';";
				  
				$this->goingroup->deletevalues($stmtA);
				$this->goingroup->deletevalues($stmtB);
				$this->goingroup->deletevalues($stmtC);
				$this->goingroup->deletevalues($stmtD);
				
				$this->commonhelper->auditadmin('DELETE',"Deleted In-group ID: $listiddel","$stmtA\n$stmtB\n$stmtC\n$stmtD");
				$sucesshint = "SUCCESS";
			}
		}
			
		if($sucesshint=="FAILED") {
			echo "FAILED: In-Group I.D. $listid_data deleted";
			$sucesshint = "FAILED";
		} else {
			echo "SUCCESS: In-Group I.D. $listid_data deleted";
			$sucesshint = "";
		}
	}
	
	if($action=="deletecallmenu") {
		$callmenu = $this->input->post('callmenu_delete');
		if (!$callmenu) {
			$callmenu = $this->uri->segment(3);
		}
		
		if(is_null($sucesshint) && !is_null($callmenu)) {
			$callmenu = explode(",",$callmenu);
			foreach ($callmenu as $callmenudel) {
				$stmtA = "DELETE from vicidial_call_menu where menu_id='$callmenudel' limit 1;";
				$stmtB = "DELETE from vicidial_call_menu_options where menu_id='$callmenudel';";
			  
				$this->goingroup->deletevalues($stmtA);
				$this->goingroup->deletevalues($stmtB);
				
				$this->commonhelper->auditadmin('DELETE',"Deleted Call Menu ID: $callmenudel","$stmtA\n$stmtB");
				$sucesshint = "SUCCESS";
			}
		}
		
		if($sucesshint=="FAILED") {
			echo "FAILED: Call Menu $callmenudel deleted";
			$sucesshint = "FAILED";
		} else {
			echo "SUCCESS: Call Menu $callmenudel deleted";
			$sucesshint = "";
		}
	}
	
/* end lists */

	if($action=="editcallmenu"){
		
		$a = explode('&', $this->input->post('items'));
		$i = 0;
		$b = split('=', $a[0]);
		$cmOptionsHTML  = '<table border="0" class="tableedit" width="100%">';
		
		$showval = htmlspecialchars(urldecode($b[0]));
		$dataval = htmlspecialchars(urldecode($b[1]));
		
		
		$getcallmenudetails = $this->goingroup->getcallmenudetails($dataval);
		$getcallmenuoptions = $this->goingroup->getcallmenuoptions($dataval);
		
		$countme = count($getcallmenudetails);
		
		if($countme > 0) { 
			foreach($getcallmenudetails as $getcallmenudetailsInfo) {
				$menu_name = $getcallmenudetailsInfo->menu_name;//[0];
				$menu_prompt = $getcallmenudetailsInfo->menu_prompt; //$row[1];
				$menu_timeout = $getcallmenudetailsInfo->menu_timeout; //$row[2];
				$menu_timeout_prompt = $getcallmenudetailsInfo->menu_timeout_prompt; //  $row[3];
				$menu_invalid_prompt = $getcallmenudetailsInfo->menu_invalid_prompt; //  $row[4];
				$menu_repeat = $getcallmenudetailsInfo->menu_repeat; //                  $row[5];
				$menu_time_check = $getcallmenudetailsInfo->menu_time_check; //              $row[6];
				$call_time_id = $getcallmenudetailsInfo->call_time_id; //                 $row[7];
				$track_in_vdac = $getcallmenudetailsInfo->track_in_vdac; //               $row[8];
				$custom_dialplan_entry = $getcallmenudetailsInfo->custom_dialplan_entry; // = $row[9];
				$tracking_group = $getcallmenudetailsInfo->tracking_group; //               $row[10];
			}
		}
		
		$countme = count($getcallmenuoptions);
		
		if($countme > 0) {
			$ctr = 0;
			$filterSQL = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "where user_group='{$this->session->userdata('user_group')}'" : "";
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
						$stmt="SELECT menu_id,menu_name from vicidial_call_menu $filterSQL order by menu_id";
						$callmenupulldown = $this->asteriskDB->query($stmt);
						
						foreach ($callmenupulldown->result() as $callmenu)
						{
						   $callmenuArray[$callmenu->menu_id] = "{$callmenu->menu_id} - {$callmenu->menu_name}";
						}
						
						$cmOptionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\" class=\"option_display_$ctr\"><label>Call Menu: </label>".form_dropdown('option_route_value_'.$ctr,$callmenuArray,$getcallmenuoptionsInfo->option_route_value,'id="option_route_value_'.$ctr.'" style="width:400px"')."</td>";
						break;
					case "INGROUP":
						$stmt="SELECT group_id,group_name FROM vicidial_inbound_groups $filterSQL ORDER BY group_id";
						$ingrouppulldown = $this->asteriskDB->query($stmt);
						
						foreach ($ingrouppulldown->result() as $ingroup)
						{
						   $grouplistArray[$ingroup->group_id] = "{$ingroup->group_id} - {$ingroup->group_name}";
						}
						
						$stmt="SELECT campaign_id,campaign_name from vicidial_campaigns $filterSQL order by campaign_id";
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
						$stmt="SELECT did_pattern,did_description from vicidial_inbound_dids $filterSQL order by did_pattern";
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
						$stmt="SELECT extension,server_ip,dialplan_number,voicemail_id FROM phones $filterSQL ORDER BY extension";
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
		
/*		 $stmt="SELECT menu_name,menu_prompt,menu_timeout,menu_timeout_prompt,menu_invalid_prompt,menu_repeat,menu_time_check,call_time_id,track_in_vdac,custom_dialplan_entry,tracking_group from vicidial_call_menu where menu_id='$dataval';";
		 
		 echo $stmt;
		 */
                /*$rslt=mysql_query($stmt, $link);
                $row=mysql_fetch_row($rslt);
                $menu_name =                    $row[0];
                $menu_prompt =                  $row[1];
                $menu_timeout =                 $row[2];
                $menu_timeout_prompt =  $row[3];
                $menu_invalid_prompt =  $row[4];
                $menu_repeat =                  $row[5];
                $menu_time_check =              $row[6];
                $call_time_id =                 $row[7];
                $track_in_vdac =                $row[8];
                $custom_dialplan_entry= $row[9];
                $tracking_group =               $row[10];*/
				
		echo $dataval.'##'.$menu_name.'##'.$menu_prompt.'##'.$menu_timeout.'##'.$menu_timeout_prompt.'##'.$menu_invalid_prompt.'##'.$menu_repeat.'##'.$menu_time_check.'##'.$call_time_id.'##'.$track_in_vdac.'##'.$custom_dialplan_entry.'##'.$tracking_group.'##'.$cmOptionsHTML;

	}
	
	if($action=="editlist") {

			$a = explode('&', $this->input->post('items'));
			$i = 0;
			$b = split('=', $a[0]);
			
			$showval = htmlspecialchars(urldecode($b[0]));
			$dataval = htmlspecialchars(urldecode($b[1]));
			
			$ingroupvalues = $this->goingroup->geteditvalues($dataval);
		
		   $countme = count($ingroupvalues);
			   
				if($countme > 0) { 
				
					foreach($ingroupvalues as $ingroupvaluesInfo) {
						
								  $group_id = $ingroupvaluesInfo->group_id;
								  $group_name =                           $ingroupvaluesInfo->group_name;
				                $group_color =                          $ingroupvaluesInfo->group_color;
				                $active =                                       $ingroupvaluesInfo->active;
				                $web_form_address =                     stripslashes($ingroupvaluesInfo->web_form_address);
				                $voicemail_ext =                        $ingroupvaluesInfo->voicemail_ext;
				                $next_agent_call =                      $ingroupvaluesInfo->next_agent_call;
				                $fronter_display =                      $ingroupvaluesInfo->fronter_display;
				                $script_id =                            $ingroupvaluesInfo->ingroup_script;
				                $get_call_launch =                      $ingroupvaluesInfo->get_call_launch;
				                $xferconf_a_dtmf =                      $ingroupvaluesInfo->xferconf_a_dtmf;
				                $xferconf_a_number =            $ingroupvaluesInfo->xferconf_a_number;
				                $xferconf_b_dtmf =                      $ingroupvaluesInfo->xferconf_b_dtmf;
				                $xferconf_b_number =            $ingroupvaluesInfo->xferconf_b_number;
				                $drop_call_seconds =            $ingroupvaluesInfo->drop_call_seconds;
				                $drop_action =                          $ingroupvaluesInfo->drop_action;
				                $drop_exten =                           $ingroupvaluesInfo->drop_exten;
				                $call_time_id =                         $ingroupvaluesInfo->call_time_id;
				                $after_hours_action =           $ingroupvaluesInfo->after_hours_action;
				                $after_hours_message_filename = $ingroupvaluesInfo->after_hours_message_filename;
				                $after_hours_exten =            $ingroupvaluesInfo->after_hours_exten;
				                $after_hours_voicemail =        $ingroupvaluesInfo->after_hours_voicemail;
				                $welcome_message_filename =     $ingroupvaluesInfo->welcome_message_filename;
				                $moh_context =                          $ingroupvaluesInfo->moh_context;
				                $onhold_prompt_filename =       $ingroupvaluesInfo->onhold_prompt_filename;
				                $prompt_interval =                      $ingroupvaluesInfo->prompt_interval;
				                $agent_alert_exten =            $ingroupvaluesInfo->agent_alert_exten;
				                $agent_alert_delay =            $ingroupvaluesInfo->agent_alert_delay;
				                $default_xfer_group =           $ingroupvaluesInfo->default_xfer_group;
				                $queue_priority =                       $ingroupvaluesInfo->queue_priority;
				                $drop_inbound_group =           $ingroupvaluesInfo->drop_inbound_group;
				                $ingroup_recording_override = $ingroupvaluesInfo->ingroup_recording_override;
				                $ingroup_rec_filename =         $ingroupvaluesInfo->ingroup_rec_filename;
				                $afterhours_xfer_group =        $ingroupvaluesInfo->afterhours_xfer_group;
				                $qc_enabled =                           $ingroupvaluesInfo->qc_enabled;
				                $qc_statuses =                          $ingroupvaluesInfo->qc_statuses;
				                $qc_shift_id =                          $ingroupvaluesInfo->qc_shift_id;
				                $qc_get_record_launch =         $ingroupvaluesInfo->qc_get_record_launch;
				                $qc_show_recording =            $ingroupvaluesInfo->qc_show_recording;
				                $qc_web_form_address =          stripslashes($ingroupvaluesInfo->qc_web_form_address);
				                $qc_script =                            $ingroupvaluesInfo->qc_script;
				                $play_place_in_line =           $ingroupvaluesInfo->play_place_in_line;
				                $play_estimate_hold_time =      $ingroupvaluesInfo->play_estimate_hold_time;
				                $hold_time_option =             $ingroupvaluesInfo->hold_time_option;
				                $hold_time_option_seconds = $ingroupvaluesInfo->hold_time_option_seconds;
				                $hold_time_option_exten =       $ingroupvaluesInfo->hold_time_option_exten;
				                $hold_time_option_voicemail =   $ingroupvaluesInfo->hold_time_option_voicemail;
				                $hold_time_option_xfer_group =  $ingroupvaluesInfo->hold_time_option_xfer_group;
				                $hold_time_option_callback_filename =   $ingroupvaluesInfo->hold_time_option_callback_filename;
				                $hold_time_option_callback_list_id =    $ingroupvaluesInfo->hold_time_option_callback_list_id;
				                $hold_recall_xfer_group =       $ingroupvaluesInfo->hold_recall_xfer_group;
				                $no_delay_call_route =          $ingroupvaluesInfo->no_delay_call_route;
				                $play_welcome_message =         $ingroupvaluesInfo->play_welcome_message;
				                $answer_sec_pct_rt_stat_one =   $ingroupvaluesInfo->answer_sec_pct_rt_stat_one;
				                $answer_sec_pct_rt_stat_two =   $ingroupvaluesInfo->answer_sec_pct_rt_stat_two;
				                $default_group_alias =          $ingroupvaluesInfo->default_group_alias;
				                $no_agent_no_queue =            $ingroupvaluesInfo->no_agent_no_queue;
				                $no_agent_action =                      $ingroupvaluesInfo->no_agent_action;
				                $no_agent_action_value =        $ingroupvaluesInfo->no_agent_action_value;
				                $web_form_address_two =         stripslashes($ingroupvaluesInfo->web_form_address_two);
				                $timer_action =                         $ingroupvaluesInfo->timer_action;
								  $timer_action_message =         $ingroupvaluesInfo->timer_action_message;
				                $timer_action_seconds =         $ingroupvaluesInfo->timer_action_seconds;
				                $start_call_url =                       $ingroupvaluesInfo->start_call_url;
				                $dispo_call_url =                       $ingroupvaluesInfo->dispo_call_url;
				                $xferconf_c_number =            $ingroupvaluesInfo->xferconf_c_number;
				                $xferconf_d_number =            $ingroupvaluesInfo->xferconf_d_number;
				                $xferconf_e_number =            $ingroupvaluesInfo->xferconf_e_number;
				                $ignore_list_script_override = $ingroupvaluesInfo->ignore_list_script_override;
				                $extension_appended_cidname = $ingroupvaluesInfo->extension_appended_cidname;
				                $uniqueid_status_display =      $ingroupvaluesInfo->uniqueid_status_display;
				                $uniqueid_status_prefix =       $ingroupvaluesInfo->uniqueid_status_prefix;
				                $hold_time_option_minimum = $ingroupvaluesInfo->hold_time_option_minimum;
				                $hold_time_option_press_filename = $ingroupvaluesInfo->hold_time_option_press_filename;
				                $hold_time_option_callmenu = $ingroupvaluesInfo->hold_time_option_callmenu;
				                $onhold_prompt_no_block =       $ingroupvaluesInfo->onhold_prompt_no_block;
				                $onhold_prompt_seconds =        $ingroupvaluesInfo->onhold_prompt_seconds;
				                $hold_time_option_no_block = $ingroupvaluesInfo->hold_time_option_no_block;
				                $hold_time_option_prompt_seconds =      $ingroupvaluesInfo->hold_time_option_prompt_seconds;
				                $hold_time_second_option =                      $ingroupvaluesInfo->hold_time_second_option;
				                $hold_time_third_option =                       $ingroupvaluesInfo->hold_time_third_option;
				                $wait_hold_option_priority =            $ingroupvaluesInfo->wait_hold_option_priority;
				                $wait_time_option =                                     $ingroupvaluesInfo->wait_time_option;
				                $wait_time_second_option =                      $ingroupvaluesInfo->wait_time_second_option;
				                $wait_time_third_option =                       $ingroupvaluesInfo->wait_time_third_option;
				                $wait_time_option_seconds =                     $ingroupvaluesInfo->wait_time_option_seconds;
				                $wait_time_option_exten =                       $ingroupvaluesInfo->wait_time_option_exten;
				                $wait_time_option_voicemail =           $ingroupvaluesInfo->wait_time_option_voicemail;
				                $wait_time_option_xfer_group =          $ingroupvaluesInfo->wait_time_option_xfer_group;
				                $wait_time_option_callmenu =            $ingroupvaluesInfo->wait_time_option_callmenu;
				                $wait_time_option_callback_filename =   $ingroupvaluesInfo->wait_time_option_callback_filename;
				                $wait_time_option_callback_list_id =    $ingroupvaluesInfo->wait_time_option_callback_list_id;
				                $wait_time_option_press_filename =      $ingroupvaluesInfo->wait_time_option_press_filename;
				                $wait_time_option_no_block =            $ingroupvaluesInfo->wait_time_option_no_block;
				                $wait_time_option_prompt_seconds =      $ingroupvaluesInfo->wait_time_option_prompt_seconds;
				                $timer_action_destination =                     $ingroupvaluesInfo->timer_action_destination;
				                $calculate_estimated_hold_seconds = $ingroupvaluesInfo->calculate_estimated_hold_seconds;
				                $add_lead_url =                                         $ingroupvaluesInfo->add_lead_url;
				                $eht_minimum_prompt_filename =          $ingroupvaluesInfo->eht_minimum_prompt_filename;
				                $eht_minimum_prompt_no_block =          $ingroupvaluesInfo->eht_minimum_prompt_no_block;
				                $eht_minimum_prompt_seconds =           $ingroupvaluesInfo->eht_minimum_prompt_seconds;
				                $on_hook_ring_time =                            $ingroupvaluesInfo->on_hook_ring_time;	
			
			 

						}
						
					    ### for agent ranks
						$user_group = $this->session->userdata('user_group');
						if ($this->commonhelper->checkIfTenant($user_group)) {
							 $addedSQL = "and user_group='$user_group'";
						}
					    $page = $this->input->post('page');
						if (is_null($page) || $page < 1) { $page = 1; }
						$query = $this->asteriskDB->query("SELECT count(*) as ucnt from vicidial_users where active='Y' and user_level != '4' $addedSQL order by user;");
												
						$total 	= $query->row()->ucnt;
						$rp 	= ($this->input->post('page') == "ALL") ? $total : 20;
						$limit 	= 5;
						$pg 	= $this->commonhelper->paging($page,$rp,$total,$limit);
						$start = (($page-1) * $rp);
				
						if ($pg['last'] > 1) {
							$pagelinks  = '<div style="cursor: pointer;font-weight: bold;">';
							$pagelinks .= '<a title="Go to First Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="postval(\''.$group_id.'\','.$pg['first'].')"><span><img src="'.base_url().'/img/first.gif"></span></a>';
							$pagelinks .= '<a title="Go to Previous Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="postval(\''.$group_id.'\','.$pg['prev'].')"><span><img src="'.base_url().'/img/prev.gif"></span></a>';
							
							for ($i=$pg['start'];$i<=$pg['end'];$i++) { 
							   if ($i==$pg['page']) $current = 'color: #F00;cursor: default;'; else $current="";
							
							$pagelinks .= '<a title="Go to Page '.$i.'" style="vertical-align:text-top;padding: 0px 2px;'.$current.'" onclick="postval(\''.$group_id.'\','.$i.')"><span>'.$i.'</span></a>';
							
							}
					
							$pagelinks .= '<a title="View all entries" style="vertical-align:text-top;padding: 0px 2px;" onclick="postval(\''.$group_id.'\',\'ALL\')"><span>ALL</span></a>';
							$pagelinks .= '<a title="Go to Next Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="postval(\''.$group_id.'\','.$pg['next'].')"><span><img src="'.base_url().'/img/next.gif"></span></a>';
							$pagelinks .= '<a title="Go to Last Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="postval(\''.$group_id.'\','.$pg['last'].')"><span><img src="'.base_url().'/img/last.gif"></span></a>';
							$pagelinks .= '</div>';
						} else {
							$pagelinks = "";
						}
						
						//$this->pagination->initialize($pageconfig);
		  				$agentranks = $this->goingroup->agentranks($group_id,$start,$rp,$pagelinks);
						
						$pagelinks = "Displaying {$pg['istart']} to {$pg['iend']} of {$pg['total']} agents";

				}
				
				echo ucwords($group_id).'##'.$group_name.'##'.$group_color.'##'.$active.'##'.$web_form_address.'##'.$voicemail_ext.'##'.$next_agent_call.'##'.$fronter_display.'##'.$script_id.'##'.$get_call_launch.'##'.$xferconf_a_dtmf .'##'.$xferconf_a_number.'##'.$xferconf_b_dtmf.'##'.$xferconf_b_number.'##'.$drop_call_seconds.'##'.$drop_action.'##'.$drop_exten.'##'.$call_time_id.'##'.$after_hours_action.'##'.$after_hours_message_filename.'##'.$after_hours_exten.'##'.$after_hours_voicemail.'##'.$welcome_message_filename.'##'.$moh_context.'##'.$onhold_prompt_filename.'##'.$prompt_interval.'##'.$agent_alert_exten.'##'.$agent_alert_delay.'##'.$default_xfer_group.'##'.$queue_priority
.'##'.$drop_inbound_group.'##'.$ingroup_recording_override.'##'.$ingroup_rec_filename.'##'.$afterhours_xfer_group.'##'.$qc_enabled.'##'.$qc_statuses.'##'.$qc_shift_id.'##'.$qc_get_record_launch.'##'.$qc_show_recording.'##'.$qc_web_form_address.'##'.$qc_script.'##'.$play_place_in_line.'##'.$play_estimate_hold_time.'##'.$hold_time_option.'##'.$hold_time_option_seconds.'##'.$hold_time_option_exten.'##'.$hold_time_option_voicemail.'##'.$hold_time_option_xfer_group.'##'.$hold_time_option_callback_filename.'##'.$hold_time_option_callback_list_id.'##'.$hold_recall_xfer_group.'##'.$no_delay_call_route.'##'.$play_welcome_message.'##'.$answer_sec_pct_rt_stat_one.'##'.$answer_sec_pct_rt_stat_two.'##'.$default_group_alias.'##'.$no_agent_no_queue.'##'.$no_agent_action.'##'.$no_agent_action_value.'##'.$web_form_address_two.'##'.$timer_action.'##'.$timer_action_message.'##'.$timer_action_seconds.'##'.$start_call_url.'##'.$dispo_call_url.'##'.$xferconf_c_number.'##'.$xferconf_d_number.'##'.$xferconf_e_number.'##'.$ignore_list_script_override.'##'.$extension_appended_cidname.'##'.$uniqueid_status_display.'##'.$uniqueid_status_prefix.'##'.$hold_time_option_minimum.'##'.$hold_time_option_press_filename.'##'.$hold_time_option_callmenu.'##'.$onhold_prompt_no_block.'##'.$onhold_prompt_seconds.'##'.$hold_time_option_no_block.'##'.$hold_time_option_prompt_seconds.'##'.$hold_time_second_option.'##'.$hold_time_third_option.'##'.$wait_hold_option_priority.'##'.$wait_time_option.'##'.$wait_time_second_option.'##'.$wait_time_third_option.'##'.$wait_time_option_seconds.'##'.$wait_time_option_exten.'##'.$wait_time_option_voicemail.'##'.$wait_time_option_xfer_group.'##'.$wait_time_option_callmenu.'##'.$wait_time_option_callback_filename.'##'.$wait_time_option_callback_list_id.'##'.$wait_time_option_press_filename.'##'.$wait_time_option_no_block.'##'.$wait_time_option_prompt_seconds.'##'.$timer_action_destination.'##'.$calculate_estimated_hold_seconds.'##'.$add_lead_url.'##'.$eht_minimum_prompt_filename.'##'.$eht_minimum_prompt_no_block.'##'.$eht_minimum_prompt_seconds.'##'.$on_hook_ring_time.'##'.$agentranks.'##'.$pagelinks; 					
				
				  

	}
	
	
	if($action=="getcheckagentrank") {
		
		$itemsumitexplode = explode('&', $this->input->post('itemrank'));
		$group_id = $this->input->post('idgroup');
		
		for( $i = 0; $i < count( $itemsumitexplode ); $i++ ) {
				$itemsumitsplit = split('=', $itemsumitexplode[$i]);
		 		$showval = htmlspecialchars(urldecode($itemsumitsplit[0]));
				$datavals = htmlspecialchars(urldecode($itemsumitsplit[1]));
				$finalvalues = $showval."||".$datavals.""; 
	
				if(preg_match("/CHECK/i", "$itemsumitexplode[$i]")) {
					
					if (preg_match("/YES/i", "$itemsumitexplode[$i]")) {
						$checked = $itemsumitexplode[$i]."\n";	
						$repcheck = str_replace("CHECK_", "", $itemsumitexplode[$i]);
						$user = str_replace("=YES", "", $repcheck);
						
						$query = $this->asteriskDB->query("SELECT closer_campaigns FROM vicidial_users WHERE user='$user'");
						$closer_campaigns = $query->row()->closer_campaigns;
						$closer_campaigns = rtrim($closer_campaigns,"-");
						$closer_campaigns = str_replace(" $group_id", "", $closer_campaigns);
						$closer_campaigns = trim($closer_campaigns);
						if (strlen($closer_campaigns) > 1)
							$closer_campaigns = " $closer_campaigns";
						$NEWcloser_campaigns = " $group_id{$closer_campaigns} -";
					} else {
						$checked = $itemsumitexplode[$i]."\n";	
						$repcheck = str_replace("CHECK_", "", $itemsumitexplode[$i]);
						$user = str_replace("=NO", "", $repcheck);
						
						$query = $this->asteriskDB->query("SELECT closer_campaigns FROM vicidial_users WHERE user='$user'");
						$closer_campaigns = $query->row()->closer_campaigns;
						$closer_campaigns = rtrim($closer_campaigns,"-");
						$closer_campaigns = str_replace(" $group_id", "", $closer_campaigns);
						$closer_campaigns = trim($closer_campaigns);
						$NEWcloser_campaigns = "{$closer_campaigns} -";
					}
					
					$query = $this->asteriskDB->query("UPDATE vicidial_users set closer_campaigns='$NEWcloser_campaigns' where user='$user';");
					$query_log .= "UPDATE vicidial_users set closer_campaigns='$NEWcloser_campaigns' where user='$user';\n";
					//echo "UPDATE vicidial_users set closer_campaigns='$NEWcloser_campaigns' where user='$user';";
				}
				
				if(preg_match("/RANK/i", "$itemsumitexplode[$i]")) {
					$itemsumitsplit1 = split('=', $itemsumitexplode[$i]);
					$datavals1 = htmlspecialchars(urldecode($itemsumitsplit1[1]));
					
					$itemsexplode = explode("_",$itemsumitsplit1[0]);
					$query = $this->asteriskDB->query("UPDATE vicidial_inbound_group_agents SET group_rank='$datavals1',group_weight='$datavals1' WHERE user='{$itemsexplode[1]}' AND group_id='$group_id';");
					//echo "UPDATE vicidial_inbound_group_agents SET group_rank='$datavals1',group_weight='$datavals1' WHERE user='{$itemsexplode[1]}' AND group_id='$group_id';";
					$query_log .= "UPDATE vicidial_inbound_group_agents SET group_rank='$datavals1',group_weight='$datavals1' WHERE user='{$itemsexplode[1]}' AND group_id='$group_id';\n";
					if($datavals1 != 0){
						$ranknotzero .= $itemsumitexplode[$i]."\n";
					}
				}
				
				if(preg_match("/GRADE/i", "$itemsumitexplode[$i]")) {
					$itemsumitsplit1 = split('=', $itemsumitexplode[$i]);
					$datavals1 = htmlspecialchars(urldecode($itemsumitsplit1[1]));
					
					$itemsexplode = explode("_",$itemsumitsplit1[0]);
					$query = $this->asteriskDB->query("UPDATE vicidial_inbound_group_agents SET group_grade='$datavals1' WHERE user='{$itemsexplode[1]}' AND group_id='$group_id';");
					//echo "UPDATE vicidial_inbound_group_agents SET group_rank='$datavals1',group_weight='$datavals1' WHERE user='{$itemsexplode[1]}' AND group_id='$group_id';";
					$query_log .= "UPDATE vicidial_inbound_group_agents SET group_grade='$datavals1' WHERE user='{$itemsexplode[1]}' AND group_id='$group_id';\n";
				}
		}
		//echo $checked."\n".$ranknotzero."\n";
		$this->commonhelper->auditadmin('MODIFY',"Modified Agent Rank(s)","$query_log");
		

		
		$reprank = str_replace("RANK_", "", $ranknotzero);
		//echo $reprank;
		$itemsumitexplode = explode('=', $reprank);

		for( $i = 0; $i < count( $itemsumitexplode ); $i++ ) {
			//$reprank = split('=',$reprank[$i]);
			//echo $i;
			//echo $reprank[$i];
		}
		$time = $reprank;
		$length = strlen($$reprank);
		$characters = 2;
		$start = $length - $characters;
		$xreprank = substr($time , $start ,$characters);
//echo $user;

		/*$itemsumitsplit1 = split('=', $reprank[$i]);
		$xreprank = $reprank."=";
		$rank = str_replace($xreprank."=", "", $reprank);
		
		echo $rank;*/
		
		
/*
UPDATE vicidial_users set closer_campaigns='$group_id' where user='$user';<--A 
UPDATE vicidial_inbound_group_agents set group_rank='2',group_weight='2' where user='7245435501' and group_id='6098542657_16';<--C 
UPDATE vicidial_users set closer_campaigns=' 6098542657_16 - ' where user='7245436001';<--A 
UPDATE vicidial_inbound_group_agents set group_rank='1',group_weight='1' where user='7245436001' and group_id='6098542657_16';<--C 
*/	}
	
	
	
	
	if($action=="customedit") {

			$fieldid = $this->input->post('fieldid');
			$listid = $this->input->post('list_id');
			
			
			$customlistvalues = $this->golist->customeditfield($fieldid,$listid);
		
			$countme = count($customlistvalues);
		   
				if($countme > 0) { 
				
					foreach($customlistvalues as $customlistvaluesInfo) {
						 $field_id = $customlistvaluesInfo->field_id;
						 $list_id = $customlistvaluesInfo->list_id;
						 $field_label = $customlistvaluesInfo->field_label;
						 $field_name = $customlistvaluesInfo->field_name;
						 $field_description = $customlistvaluesInfo->field_description;
						 $field_rank = $customlistvaluesInfo->field_rank;
						 $field_help = $customlistvaluesInfo->field_help;
						 $field_type = $customlistvaluesInfo->field_type;
						 $field_options = $customlistvaluesInfo->field_options;
						 $field_size = $customlistvaluesInfo->field_size;
						 $field_max = $customlistvaluesInfo->field_max;
						 $field_default = $customlistvaluesInfo->field_default;
						 $field_cost = $customlistvaluesInfo->field_cost;
						 $field_required = $customlistvaluesInfo->field_required; 
						 $name_position = $customlistvaluesInfo->name_position;
						 $multi_position = $customlistvaluesInfo->multi_position;
						 $field_order = $customlistvaluesInfo->field_order;
						}
				}
		
			echo $field_id.'||'.$list_id.'||'.$field_label.'||'.$field_name.'||'.$field_description.'||'.$field_rank.'||'.$field_help.'||'.$field_type.'||'.$field_options.'||'.$field_size.'||'.$field_max.'||'.$field_default.'||'.$field_cost.'||'.$field_required.'||'.$name_position.'||'.$multi_position.'||'.$field_order; 
		
	}
	
	
	
	if($action=="customview") {
		
			$listid = $this->input->post('list_id');
			
			$customlistvalues = $this->golist->customviewmodel($listid);
		
			$countme = count($customlistvalues);
			
			$viewall .= "<label class=\"modify-value\" style=\"margin-left: -50%;\"><b>Example custom form</b></lable>\n";
			if($countme > 0) {
					$viewall .= "<form action=$PHP_SELF method=POST name=form_custom_$listid id=form_custom_$listid>\n";
					
					$viewall .= "<center><TABLE class=\"tableedit\" width=100%>\n";
					$last_field_rank=0;
					$o=0;	
							foreach($customlistvalues as $fieldsvalues){
							
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
							
								if ($last_field_rank=="$A_field_rank") {
									$viewall .= " &nbsp; &nbsp; &nbsp; &nbsp; ";
								} else {
									$viewall .= "</td></tr>\n";
									$viewall .= "<tr bgcolor=white><td align=";
								
									if ($A_name_position=='TOP') {
										$viewall .= "left colspan=2";
									} else {
										$viewall .= "right";
									}
								$viewall .= "><font size=2>";
								}	
								$viewall .= "<B>$A_field_name</B>";
								
								if ($A_name_position=='TOP') {
										$helpHTML .= "help+";
									if (strlen($A_field_help)<1){
										$helpHTML .= '';
									}
									$viewall .= " &nbsp; <span style=\"position:static;\" id=P_HELP_$A_field_label></span><span style=\"position:static;background:white;\" id=HELP_$A_field_label> &nbsp; $helpHTML</span><BR>";
								} else {
									if ($last_field_rank=="$A_field_rank") { 
										$viewall .= " &nbsp;";
									} else {
										$viewall .= "</td><td align=left><font size=2>";
									}
								}
								
								/* start fields */
								$field_HTML='';
								
								if ($A_field_type=='SELECT') {
									$field_HTML .= "<select size=1 name=$A_field_label id=$A_field_label>\n";
								}
								
								if ($A_field_type=='MULTI'){
									$field_HTML .= "<select MULTIPLE size=$A_field_size name=$A_field_label id=$A_field_label>\n";
								}
	
								/* data's */								
								if ( ($A_field_type=='SELECT') or ($A_field_type=='MULTI') or ($A_field_type=='RADIO') or ($A_field_type=='CHECKBOX') )
			{
			
						$field_options_array = explode(",",$A_field_options);
						
						$field_options_count = count($field_options_array);
						$te=0;
						while ($te < $field_options_count)
							{
								$field_selected='';
								$field_options_value_array = explode(' ',$field_options_array[$te]);
								
							
								if ( ($A_field_type=='SELECT') or ($A_field_type=='MULTI') )
									{
										
									if ($A_field_default == "$field_options_value_array[0]") {$field_selected = 'SELECTED';}
									$field_HTML .= "<option value=\"$field_options_value_array[0]\" $field_selected>$field_options_value_array[0]</option>\n";
									}
									
								if ( ($A_field_type=='RADIO') or ($A_field_type=='CHECKBOX') )
									{
										
									if ($A_multi_position=='VERTICAL') 
										{$field_HTML .= " &nbsp; ";}
									if ($A_field_default == "$field_options_value_array[0]") {$field_selected = 'CHECKED';}
							
									$lblname = $A_field_label.'[]';
				
									$field_HTML .= "<input type=$A_field_type name=$lblname id=$lblname value=\"$field_options_value_array[0]\" $field_selected> $field_options_value_array[0]\n";
								
									
									if ($A_multi_position=='VERTICAL') 
										{$field_HTML .= "<BR>\n";}
									}
							$te++;
							}
						}
			
			/* end datas */
			
							if ( ($A_field_type=='SELECT') or ($A_field_type=='MULTI') )
								{
								$field_HTML .= "</select>\n";
								}
							if ($A_field_type=='TEXT') 
								{
								if ($A_field_default=='NULL') {$A_field_default='';}
								$field_HTML .= "<input type=text size=$A_field_size maxlength=$A_field_max name=$A_field_label id=$A_field_label value=\"$A_field_default\">\n";
								}
							if ($A_field_type=='AREA') 
								{
								$field_HTML .= "<textarea name=$A_field_label id=$A_field_label ROWS=$A_field_max COLS=$A_field_size></textarea>";
								}
							if ($A_field_type=='DISPLAY')
								{
								if ($A_field_default=='NULL') {$A_field_default='';}
								$field_HTML .= "$A_field_default\n";
								}
							if ($A_field_type=='SCRIPT')
								{
								if ($A_field_default=='NULL') {$A_field_default='';}
								$field_HTML .= "$A_field_options\n";
								}
							if ($A_field_type=='DATE') 
								{
								if ( (strlen($A_field_default)<1) or ($A_field_default=='NULL') ) {$A_field_default=0;}
								$day_diff = $A_field_default;
								

								$default_date = date("Y-m-d", mktime(date("H"),date("i"),date("s"),date("m"),date("d")+$day_diff,date("Y")));

								$field_HTML .= "<input type=text size=11 maxlength=10 name=$A_field_label id=$A_field_label value=\"$default_date\">\n";
								/*$field_HTML .= "<script language=\"JavaScript\">\n";
								$field_HTML .= "var o_cal = new tcal ({\n";
								$field_HTML .= "	'formname': 'form_custom_$listid',\n";
								$field_HTML .= "	'controlname': '$A_field_label'});\n";
								$field_HTML .= "o_cal.a_tpl.yearscroll = false;\n";
								$field_HTML .= "</script>\n";*/
								$baseurl = base_url();
								$urlcalendar = $baseurl.'js/images/cal.gif';
								$field_HTML .= "<img id=\"$A_field_label\" name=\"$A_field_label\" src=\"$urlcalendar\">";
								
								}
							if ($A_field_type=='TIME') 
								{
								$minute_diff = $A_field_default;
								$default_time = date("H:i:s", mktime(date("H"),date("i")+$minute_diff,date("s"),date("m"),date("d"),date("Y")));
								$default_hour = date("H", mktime(date("H"),date("i")+$minute_diff,date("s"),date("m"),date("d"),date("Y")));
								$default_minute = date("i", mktime(date("H"),date("i")+$minute_diff,date("s"),date("m"),date("d"),date("Y")));
								$field_HTML .= "<input type=hidden name=$A_field_label id=$A_field_label value=\"$default_time\">";
								$field_HTML .= "<SELECT name=HOUR_$A_field_label id=HOUR_$A_field_label>";
								$field_HTML .= "<option>00</option>";
								$field_HTML .= "<option>01</option>";
								$field_HTML .= "<option>02</option>";
								$field_HTML .= "<option>03</option>";
								$field_HTML .= "<option>04</option>";
								$field_HTML .= "<option>05</option>";
								$field_HTML .= "<option>06</option>";
								$field_HTML .= "<option>07</option>";
								$field_HTML .= "<option>08</option>";
								$field_HTML .= "<option>09</option>";
								$field_HTML .= "<option>10</option>";
								$field_HTML .= "<option>11</option>";
								$field_HTML .= "<option>12</option>";
								$field_HTML .= "<option>13</option>";
								$field_HTML .= "<option>14</option>";
								$field_HTML .= "<option>15</option>";
								$field_HTML .= "<option>16</option>";
								$field_HTML .= "<option>17</option>";
								$field_HTML .= "<option>18</option>";
								$field_HTML .= "<option>19</option>";
								$field_HTML .= "<option>20</option>";
								$field_HTML .= "<option>21</option>";
								$field_HTML .= "<option>22</option>";
								$field_HTML .= "<option>23</option>";
								$field_HTML .= "<OPTION value=\"$default_hour\" selected>$default_hour</OPTION>";
								$field_HTML .= "</SELECT>";
								$field_HTML .= "<SELECT name=MINUTE_$A_field_label id=MINUTE_$A_field_label>";
								$field_HTML .= "<option>00</option>";
								$field_HTML .= "<option>05</option>";
								$field_HTML .= "<option>10</option>";
								$field_HTML .= "<option>15</option>";
								$field_HTML .= "<option>20</option>";
								$field_HTML .= "<option>25</option>";
								$field_HTML .= "<option>30</option>";
								$field_HTML .= "<option>35</option>";
								$field_HTML .= "<option>40</option>";
								$field_HTML .= "<option>45</option>";
								$field_HTML .= "<option>50</option>";
								$field_HTML .= "<option>55</option>";
								$field_HTML .= "<OPTION value=\"$default_minute\" selected>$default_minute</OPTION>";
								$field_HTML .= "</SELECT>";
								}								

								/* end fields */
								
								if ($A_name_position=='LEFT') {
										$helpHTML = "help+";
									if (strlen($A_field_help)<1) {
										$helpHTML = '';
									}
										$viewall .= " $field_HTML <span style=\"position:static;\" id=P_HELP_$A_field_label></span><span style=\"position:static;background:white;\" id=HELP_$A_field_label> &nbsp; $helpHTML</span>";
								} else {
									echo " $field_HTML\n";
								}								
								$last_field_rank=$A_field_rank;
								$o++;							
							}// end for
					$viewall .= "</td></tr></table></form></center><BR><BR>\n";
					echo $viewall;
			}
		
	}
	
	#add
	if($action=="customadd") {
		
		//check if field is already exist
		$stmt="SELECT count(*) as countchecking from vicidial_lists_fields where list_id='$list_id' and field_label='$field_label'";
		$rslt=$this->asteriskDB->query($stmt);
		
		foreach ($rslt->result() as $row) {
		   $fieldcheck = $row->countchecking;
		}
		
		if ( (strlen($field_label)<1) or (strlen($field_name)<2) or (strlen($field_size)<1) ) {
			echo "ERROR: You must enter a field label, field name and field size - $list_id | $field_label | $field_name | $field_size";
		} else {
			if($fieldcheck > 0) {
				echo "ERROR: Field already exists for this list - $list_id | $field_label";
			} else {

				$table_exists=0;
				$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
				$rslt=$this->asteriskDB->query($stmt);
				$tablecount_to_print  = $rslt->num_rows();
			
				if ($tablecount_to_print > 0) {
					$table_exists =	1;
				}
				
				if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/",$vicidial_list_fields)) ){
					if ($table_exists < 1) {
							$field_sql = "CREATE TABLE custom_$list_id (lead_id INT(9) UNSIGNED PRIMARY KEY NOT NULL);";	
							$rslt=$this->asteriskDB->query($field_sql);			
					}
				}
				
				## add field 
					$table_exists=0;
					$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
					$rslt=$this->asteriskDB->query($stmt);
					$tablecount_to_print  = $rslt->num_rows();
					
					if ($tablecount_to_print > 0) {
						$table_exists =	1;
					}
					
					if ($table_exists < 1)
						{$field_sql = "CREATE TABLE custom_$list_id (lead_id INT(9) UNSIGNED PRIMARY KEY NOT NULL, $field_label ";}
					else
						{$field_sql = "ALTER TABLE custom_$list_id ADD $field_label ";}

					/* type of field data */
		
					if ( ($field_type=='SELECT') or ($field_type=='RADIO') ) {
						$field_options_array = explode("\n",$field_options);
						$field_options_count = count($field_options_array);
						$te=0;
						while ($te < $field_options_count)
							{
							if (preg_match("/,/",$field_options_array[$te]))
								{
								$field_options_value_array = explode(",",$field_options_array[$te]);
								$field_options_ENUM .= str_replace(" ","_","'$field_options_value_array[0]',");
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
							$field_options_ENUM .= str_replace(" ","_","'$field_options_value_array[0]',");
							}
						$te++;
						}
					$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
					$field_cost = strlen($field_options_ENUM);
					if ($field_cost < 1) {$field_cost=1;};
					$field_sql .= "VARCHAR($field_cost) ";
					}	
		
					if ($field_type=='TEXT') {
						if ($field_max < 1) {$field_max=1;};
						$field_sql .= "VARCHAR($field_max) ";
					}
					
					if ($field_type=='AREA') {
						$field_sql .= "TEXT ";
						$field_cost = 15;
					}
					
					if ($field_type=='DATE') {
						$field_sql .= "DATE ";
						$field_cost = 10;
					}
					
					if ($field_type=='TIME') {
						$field_sql .= "TIME ";
						$field_cost = 8;
					}
					
					/* end type of field data */
					
					
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
						if (strlen($copy_option) < 3)
							{
							$stmtCUSTOM="$field_sql";
							$rslt=$this->asteriskDB->query($stmtCUSTOM);
							}
						}
								
					//insert
					$stmt="INSERT INTO vicidial_lists_fields set field_label='$field_label',field_name='$field_name',field_description='$field_description',field_rank='$field_rank', field_help='$field_help',field_type='$field_type',field_options='$field_options',field_size='$field_size',field_max='$field_max', field_default='$field_default',field_required='$field_required',field_cost='$field_cost',list_id='$list_id', multi_position='$multi_position',name_position='$name_position',field_order='$field_order';";	
					//echo $stmt;
					$rslt=$this->asteriskDB->query($stmt);				

				
					}
			
				}
		}
		
		#edit
		if($action=='customeditsub') {
			
			$field_sql = "ALTER TABLE custom_$list_id MODIFY $field_label ";
			
			$field_options_ENUM='';
			$field_cost=1;
		
				if ( ($field_type=='SELECT') or ($field_type=='RADIO') ) {
					$field_options_array = explode("\n",$field_options);
					$field_options_count = count($field_options_array);
					$te=0;
					
				while ($te < $field_options_count) {
					if (preg_match("/,/",$field_options_array[$te]))
						{
						$field_options_value_array = explode(",",$field_options_array[$te]);
						$field_options_ENUM .= str_replace(" ","_","'$field_options_value_array[0]',");
						}
					$te++;
				}
				$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
				$field_sql .= "ENUM($field_options_ENUM) ";
				$field_cost = strlen($field_options_ENUM);
				}
	
				if ( ($field_type=='MULTI') or ($field_type=='CHECKBOX') ) {
					$field_options_array = explode("\n",$field_options);
					$field_options_count = count($field_options_array);
					$te=0;
					while ($te < $field_options_count) {
						if (preg_match("/,/",$field_options_array[$te]))
							{
							$field_options_value_array = explode(",",$field_options_array[$te]);
							$field_options_ENUM .= str_replace(" ","_","'$field_options_value_array[0]',");
							}
						$te++;
					}
					$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
					$field_cost = strlen($field_options_ENUM);
					$field_sql .= "VARCHAR($field_cost) ";
				}
	
				if ($field_type=='TEXT') {
					$field_sql .= "VARCHAR($field_max) ";
				}
				
				if ($field_type=='AREA') {
					$field_sql .= "TEXT ";
				}

				if ($field_type=='DATE') {
					$field_sql .= "DATE ";
				}

				if ($field_type=='TIME') {
					$field_sql .= "TIME ";
				}
				
				if ( ($field_default == 'NULL') or ($field_type=='AREA') or ($field_type=='DATE') or ($field_type=='TIME') ) {
					$field_sql .= ";";
				} else {
					$field_sql .= "default '$field_default';";
				}

				if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/",$vicidial_list_fields)) ) {
					echo "Non-DB $field_type field type, $field_label\n"; 
				} else {
					$stmtCUSTOM="$field_sql";
					
	//				echo $stmtCUSTOM;
					$rslt=$this->asteriskDB->query($stmtCUSTOM);	
					
				}

					$stmt="UPDATE vicidial_lists_fields set field_label='$field_label',field_name='$field_name',field_description='$field_description',field_rank='$field_rank',field_help='$field_help',field_type='$field_type',field_options='$field_options',field_size='$field_size',field_max='$field_max',field_default='$field_default',field_required='$field_required',field_cost='$field_cost',multi_position='$multi_position',name_position='$name_position',field_order='$field_order' where list_id='$list_id' and field_id='$field_id';";
					//echo $stmt;			
					$rslt=$this->asteriskDB->query($stmt);	

					### LOG INSERTION Admin Log Table ###
		/* 			$SQL_log = "$stmt|$stmtCUSTOM";
					$SQL_log = ereg_replace(';','',$SQL_log);
					$SQL_log = addslashes($SQL_log);
					$stmt="INSERT INTO vicidial_admin_log set event_date=NOW(), user='$user', ip_address='$ip', event_section='CUSTOM_FIELDS', event_type='MODIFY', record_id='$list_id', event_code='ADMIN MODIFY CUSTOM LIST FIELD', event_sql=\"$SQL_log\", event_notes='';";
	*/					
		}
		
		if($action=="customdelete") {
			if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/",$vicidial_list_fields)) )
		{
		if ($DB) {echo "Non-DB $field_type field type, $field_label\n";} 
		}
	else
		{
		$stmtCUSTOM="ALTER TABLE custom_$list_id DROP $field_label;";
	/*	$rsltCUSTOM=mysql_query($stmtCUSTOM, $linkCUSTOM);
		$table_update = mysql_affected_rows($linkCUSTOM);*/
		if ($DB) {echo "$table_update|$stmtCUSTOM\n";}
		if (!$rsltCUSTOM) {echo('Could not execute: ' . mysql_error());}
		}

	$stmt="DELETE FROM vicidial_lists_fields WHERE field_label='$field_label' and field_id='$field_id' and list_id='$list_id' LIMIT 1;";		
		}
		
		
		if($action=="editdid") {
			
			$a = explode('&', $this->input->post('items'));
			$i = 0;
			$b = split('=', $a[0]);
			
			$showval = htmlspecialchars(urldecode($b[0]));
			$dataval = htmlspecialchars(urldecode($b[1]));
			
			$didvalues = $this->goingroup->getdidvalues($dataval);
			
			   $countme = count($didvalues);
			   
				if($countme > 0) { 
				
					foreach($didvalues as $didvaluesInfo) {
					  
					  $did_id = $didvaluesInfo->did_id; //0
					  $did_pattern = $didvaluesInfo->did_pattern; //1
					  $did_description = $didvaluesInfo->did_description; //2
	                $did_active = $didvaluesInfo->did_active; //3
	                $did_route = $didvaluesInfo->did_route; //4
	                $extension = $didvaluesInfo->extension; //5
	                $exten_context = $didvaluesInfo->exten_context; //6
	                $voicemail_ext = $didvaluesInfo->voicemail_ext; //7
	                $phone = $didvaluesInfo->phone; //8
	                $server_ip = $didvaluesInfo->server_ip; //9
	                $user = $didvaluesInfo->user; //10
	                $user_unavailable_action =  $didvaluesInfo->user_unavailable_action; //11
	                $user_route_settings_ingroup = $didvaluesInfo->user_route_settings_ingroup; //12
	                $group_id = $didvaluesInfo->group_id; //13
	                $call_handle_method = $didvaluesInfo->call_handle_method; //14
	                $agent_search_method = $didvaluesInfo->agent_search_method; //15
	                $list_id = $didvaluesInfo->list_id; //16                              
	                $campaign_id = $didvaluesInfo->campaign_id; //17
	                $phone_code = $didvaluesInfo->phone_code; //18
	                $menu_id = $didvaluesInfo->menu_id; //19
	                $record_call = $didvaluesInfo->record_call; //20
	                $filter_inbound_number = $didvaluesInfo->filter_inbound_number; //21
	                $filter_phone_group_id = $didvaluesInfo->filter_phone_group_id; //22
	                $filter_url = $didvaluesInfo->filter_url; //23
	                $filter_action = $didvaluesInfo->filter_action; //24
	                $filter_extension = $didvaluesInfo->filter_extension; //25
	                $filter_exten_context = $didvaluesInfo->filter_exten_context; //26
	                $filter_voicemail_ext = $didvaluesInfo->filter_voicemail_ext; //27
	                $filter_phone = $didvaluesInfo->filter_phone; //28
	                $filter_server_ip = $didvaluesInfo->filter_server_ip; //29
	                $filter_user = $didvaluesInfo->filter_user; //30
	                $filter_user_unavailable_action = $didvaluesInfo->filter_user_unavailable_action; //31
	                $filter_user_route_settings_ingroup = $didvaluesInfo->filter_user_route_settings_ingroup; //32
	                $filter_group_id = $didvaluesInfo->filter_group_id; //33
	                $filter_call_handle_method = $didvaluesInfo->filter_call_handle_method; //34
	                $filter_agent_search_method = $didvaluesInfo->filter_agent_search_method; //35
	                $filter_list_id = $didvaluesInfo->filter_list_id; //36
	                $filter_campaign_id = $didvaluesInfo->filter_campaign_id; //37
	                $filter_phone_code = $didvaluesInfo->filter_phone_code; //38
	                $filter_menu_id = $didvaluesInfo->filter_menu_id; //39
	                $filter_clean_cid_number = $didvaluesInfo->filter_clean_cid_number; //40
					}
				
				}
			
echo $did_id.'##'.$did_pattern.'##'.$did_description.'##'.$did_active.'##'.$did_route.'##'.$extension.'##'.$exten_context.'##'.$voicemail_ext.'##'.$phone.'##'.$server_ip.'##'.$user.'##'.$user_unavailable_action.'##'.$user_route_settings_ingroup.'##'. 
$group_id.'##'.$call_handle_method .'##'.$agent_search_method.'##'.$list_id.'##'.$campaign_id.'##'.$phone_code.'##'.$menu_id.'##'.$record_call.'##'.$filter_inbound_number.'##'.$filter_phone_group_id.'##'.$filter_url.'##'.$filter_action.'##'.$filter_extension.'##'.$filter_exten_context.'##'.$filter_voicemail_ext.'##'.$filter_phone.'##'.$filter_server_ip.'##'.$filter_user.'##'.$filter_user_unavailable_action.'##'.$filter_user_route_settings_ingroup.'##'.$filter_group_id.'##'.$filter_call_handle_method.'##'.$filter_agent_search_method.'##'.$filter_list_id.'##'.$filter_campaign_id.'##'.$filter_phone_code 
.'##'.$filter_menu_id.'##'.$filter_clean_cid_number; 

		}
		
	if ($action == "showcallmenuoption")
	{
		$routeid = $this->input->post('routeid');
		$ctr = $this->input->post('ctr');
		
		$optionsHTML .= "<tr class=\"trview\">";
		$filterSQL = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "where user_group='{$this->session->userdata('user_group')}'" : "";
		switch ($routeid)
		{
			case "CALLMENU":
				$stmt="SELECT menu_id,menu_name from vicidial_call_menu $filterSQL order by menu_id";
				$callmenupulldown = $this->asteriskDB->query($stmt);
				
				foreach ($callmenupulldown->result() as $callmenu)
				{
				   $callmenuArray[$callmenu->menu_id] = "{$callmenu->menu_id} - {$callmenu->menu_name}";
				}
				
				$optionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\"><label>Call Menu: </label>".form_dropdown('option_route_value_'.$ctr,$callmenuArray,null,'id="option_route_value_'.$ctr.'" style="width:400px"')."</td>";
				break;
			case "INGROUP":
				$stmt="SELECT group_id,group_name FROM vicidial_inbound_groups $filterSQL ORDER BY group_id";
				$ingrouppulldown = $this->asteriskDB->query($stmt);
				
				foreach ($ingrouppulldown->result() as $ingroup)
				{
				   $grouplistArray[$ingroup->group_id] = "{$ingroup->group_id} - {$ingroup->group_name}";
				}
				
				$stmt="SELECT campaign_id,campaign_name from vicidial_campaigns $filterSQL order by campaign_id";
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

				$optionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\"><label>In-Group: </label>".form_dropdown('option_route_value_'.$ctr,$grouplistArray,null,'id="edit_option_route_value_'.$ctr.'"')."<br />";
				$optionsHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>Handle Method: </label>".form_dropdown('handle_method_'.$ctr,$handleArray,'CIDLOOKUP','id="edit_handle_method_'.$ctr.'"')."<br /></span>";
				$optionsHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>Search Method: </label>".form_dropdown('search_method_'.$ctr,$searchArray,'LB','id="edit_search_method_'.$ctr.'"')." &nbsp; <label>List ID: </label>".form_input('list_id_'.$ctr,'998','id="edit_list_id_'.$ctr.'" maxlength="14" size="8"')."<br /></span>";
				$optionsHTML .= "<label>Campaign ID: </label>".form_dropdown('campaign_id_'.$ctr,$campaignArray,null,'id="edit_campaign_id_'.$ctr.'"')." <span class='advanceCallMenu_".$ctr."' style='display:none'>&nbsp; <label>Phone Code: </label>".form_input('phone_code_'.$ctr,'1','id="edit_phone_code_'.$ctr.'" maxlength="14" size="4"')."</span><br />";
				$optionsHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>VID Enter Filename: </label>".form_input('enter_filename_'.$ctr,null,'id="edit_enter_filename_'.$ctr.'" maxlength="255" size="25"')." <a href=\"javascript:launch_chooser('edit_enter_filename_".$ctr."','date',1200,document.getElementById('edit_enter_filename_".$ctr."').value);\"><font color=\"blue\" size=\"1\">[ audio chooser ]</font></a> <div id=\"divedit_enter_filename_".$ctr."\" style=\"display:inline\"></div><br /></span>";
				$optionsHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>VID ID Number Filename: </label>".form_input('id_number_filename_'.$ctr,null,'id="edit_id_number_filename_'.$ctr.'" maxlength="255" size="25"')." <a href=\"javascript:launch_chooser('edit_id_number_filename_".$ctr."','date',1200,document.getElementById('edit_id_number_filename_".$ctr."').value);\"><font color=\"blue\" size=\"1\">[ audio chooser ]</font></a> <div id=\"divedit_id_number_filename_".$ctr."\" style=\"display:inline\"></div><br /></span>";
				$optionsHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>VID Confirm Filename: </label>".form_input('confirm_filename_'.$ctr,null,'id="edit_confirm_filename_'.$ctr.'" maxlength="255" size="25"')." <a href=\"javascript:launch_chooser('edit_confirm_filename_".$ctr."','date',1200,document.getElementById('edit_confirm_filename_".$ctr."').value);\"><font color=\"blue\" size=\"1\">[ audio chooser ]</font></a> <div id=\"divedit_confirm_filename_".$ctr."\" style=\"display:inline\"></div><br /></span>";
				$optionsHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>VID Digits: </label>".form_input('validate_digits_'.$ctr,null,'id="edit_validate_digits_'.$ctr.'" maxlength="3" size="3"')."</span>";
				$optionsHTML .= "<div style='float:left;padding:10px 10px;font-size:11px;cursor:pointer;'><a style='color:#7A9E22' onclick='showAdvanceMenuOptions($ctr)' class='minMax'><pre style='display:inline'>[+]</pre> Advance Settings</a></div></td>";
				break;
			case "DID":
				$stmt="SELECT did_pattern,did_description from vicidial_inbound_dids $filterSQL order by did_pattern";
				$didpulldown = $this->asteriskDB->query($stmt);
				
				foreach ($didpulldown->result() as $did)
				{
				   $didArray[$did->did_pattern] = "{$did->did_pattern} - {$did->did_description}";
				}
				
				$optionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\"><label>DID: </label>".form_dropdown('option_route_value_'.$ctr,$didArray,null,'id="option_route_value_'.$ctr.'"')."</td>";
				break;
			case "HANGUP":
				$optionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\"><label>Audio File: </label>".form_input('option_route_value_'.$ctr,null,'id="option_route_value_'.$ctr.'" maxlength="255" size="30"')." <a href=\"javascript:launch_chooser('option_route_value_$ctr','date',1200,document.getElementById('option_route_value_$ctr').value);\"><font color=\"blue\" size=\"1\">[ audio chooser ]</font></a> <div id=\"divoption_route_value_$ctr\" style=\"display:inline\"></div></td>";
				break;
			case "EXTENSION":
				$optionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\"><label>Extension: </label>".form_input('option_route_value_'.$ctr,null,'id="option_route_value_'.$ctr.'"')." <label>Context: </label>".form_input('option_route_value_context_'.$ctr,null,'id="option_route_value_context_'.$ctr.'"')."</td>";
				break;
			case "PHONE":
				$stmt="SELECT extension,server_ip,dialplan_number,voicemail_id FROM phones $filterSQL ORDER BY extension";
				$phonepulldown = $this->asteriskDB->query($stmt);
				
				foreach ($phonepulldown->result() as $phone)
				{
				   $phoneArray[$phone->extension] = "{$phone->extension} - {$phone->server_ip} - {$phone->dialplan_number} - {$phone->voicemail_id}";
				}
				
				$optionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\"><label>Phone: </label>".form_dropdown('option_route_value_'.$ctr,$phoneArray,null,'id="option_route_value_'.$ctr.'"')."</td>";
				break;
			case "VOICEMAIL":
				$optionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\"><label>Voicemail Box: </label>".form_input('option_route_value_'.$ctr,null,'id="option_route_value_'.$ctr.'" maxlength="255" size="15"')." <a href=\"javascript:launch_vm_chooser('option_route_value_$ctr','date',1200,document.getElementById('option_route_value_$ctr').value);\"><font color=\"blue\" size=\"1\">[ voicemail chooser ]</font></a> <div id=\"divoption_route_value_$ctr\" style=\"display:inline\"></div></td>";
				break;
			case "AGI":
				$optionsHTML .= "<td colspan=\"6\" style=\"text-align:center;\"><label>AGI: </label>".form_input('option_route_value_'.$ctr,null,'id="option_route_value_'.$ctr.'" maxlength="255" size="50"')."</td>";
				break;
		}
		$optionsHTML .= "</tr>";
		
		echo $optionsHTML;
	}
	
	if ($action == "showoption")
	{
		$menuid = $this->input->post('menuid');
		$optval = $this->input->post('optionval');
		$routeid = $this->input->post('route');
		$ctr = $this->input->post('ctr');
		$filterSQL = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "where user_group='{$this->session->userdata('user_group')}'" : "";
		
		$getcallmenuoptions = $this->goingroup->getcallmenuoptions($menuid,$routeid,$optval);
		
		$hiddenOptionHTML = '';
		switch ($routeid)
		{
			case "CALLMENU":
				$stmt="SELECT menu_id,menu_name from vicidial_call_menu $filterSQL order by menu_id";
				$callmenupulldown = $this->asteriskDB->query($stmt);
				
				foreach ($callmenupulldown->result() as $callmenu)
				{
				   $callmenuArray[$callmenu->menu_id] = "{$callmenu->menu_id} - {$callmenu->menu_name}";
				}
				
				$hiddenOptionHTML .= "<label>Call Menu: </label>".form_dropdown('option_route_value_'.$ctr,$callmenuArray,$getcallmenuoptions[0]->option_route_value,'id="option_route_value_'.$ctr.'" style="width:400px"');
				break;
			case "INGROUP":
				$stmt="SELECT group_id,group_name FROM vicidial_inbound_groups $filterSQL ORDER BY group_id";
				$ingrouppulldown = $this->asteriskDB->query($stmt);
				
				foreach ($ingrouppulldown->result() as $ingroup)
				{
				   $grouplistArray[$ingroup->group_id] = "{$ingroup->group_id} - {$ingroup->group_name}";
				}
				
				$stmt="SELECT campaign_id,campaign_name from vicidial_campaigns $filterSQL order by campaign_id";
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
				
				list($handle,$search,$list,$camp,$code,$vid_enter,$vid_id,$vid_conf,$vid_digits) = explode(",",$getcallmenuoptions[0]->option_route_value_context);
				
				if (strlen($list) < 1)
					$list = '998';
				if (strlen($code) < 1)
					$code = '1';
				if (strlen($vid_enter) < 1)
					$vid_enter = 'sip-silence';
				if (strlen($vid_id) < 1)
					$vid_id = 'sip-silence';
				if (strlen($vid_conf) < 1)
					$vid_conf = 'sip-silence';
				if (strlen($vid_digits) < 1)
					$vid_digits = '1';
					
				$handle = (strlen($handle) > 0) ? $handle : "CIDLOOKUP";
				$search = (strlen($search) > 0) ? $search : "LB";

				$hiddenOptionHTML .= "<label>In-Group: </label>".form_dropdown('option_route_value_'.$ctr,$grouplistArray,$getcallmenuoptions[0]->option_route_value,'id="edit_option_route_value_'.$ctr.'"')."<br />";
				$hiddenOptionHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>Handle Method: </label>".form_dropdown('handle_method_'.$ctr,$handleArray,$handle,'id="edit_handle_method_'.$ctr.'"')."<br /></span>";
				$hiddenOptionHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>Search Method: </label>".form_dropdown('search_method_'.$ctr,$searchArray,$search,'id="edit_search_method_'.$ctr.'"')." &nbsp; <label>List ID: </label>".form_input('list_id_'.$ctr,$list,'id="edit_list_id_'.$ctr.'" maxlength="14" size="8"')."<br /></span>";
				$hiddenOptionHTML .= "<label>Campaign ID: </label>".form_dropdown('campaign_id_'.$ctr,$campaignArray,$camp,'id="edit_campaign_id_'.$ctr.'"')." <span class='advanceCallMenu_".$ctr."' style='display:none'>&nbsp; <label>Phone Code: </label>".form_input('phone_code_'.$ctr,$code,'id="edit_phone_code_'.$ctr.'" maxlength="14" size="4"')."</span><br />";
				$hiddenOptionHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>VID Enter Filename: </label>".form_input('enter_filename_'.$ctr,$vid_enter,'id="edit_enter_filename_'.$ctr.'" maxlength="255" size="25"')." <a href=\"javascript:launch_chooser('edit_enter_filename_".$ctr."','date',1200,document.getElementById('edit_enter_filename_".$ctr."').value);\"><font color=\"blue\" size=\"1\">[ audio chooser ]</font></a> <div id=\"divedit_enter_filename_".$ctr."\" style=\"display:inline\"></div><br /></span>";
				$hiddenOptionHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>VID ID Number Filename: </label>".form_input('id_number_filename_'.$ctr,$vid_id,'id="edit_id_number_filename_'.$ctr.'" maxlength="255" size="25"')." <a href=\"javascript:launch_chooser('edit_id_number_filename_".$ctr."','date',1200,document.getElementById('edit_id_number_filename_".$ctr."').value);\"><font color=\"blue\" size=\"1\">[ audio chooser ]</font></a> <div id=\"divedit_id_number_filename_".$ctr."\" style=\"display:inline\"></div><br /></span>";
				$hiddenOptionHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>VID Confirm Filename: </label>".form_input('confirm_filename_'.$ctr,$vid_conf,'id="edit_confirm_filename_'.$ctr.'" maxlength="255" size="25"')." <a href=\"javascript:launch_chooser('edit_confirm_filename_".$ctr."','date',1200,document.getElementById('edit_confirm_filename_".$ctr."').value);\"><font color=\"blue\" size=\"1\">[ audio chooser ]</font></a> <div id=\"divedit_confirm_filename_".$ctr."\" style=\"display:inline\"></div><br /></span>";
				$hiddenOptionHTML .= "<span class='advanceCallMenu_".$ctr."' style='display:none'><label>VID Digits: </label>".form_input('validate_digits_'.$ctr,$vid_digits,'id="edit_validate_digits_'.$ctr.'" maxlength="3" size="3"')."</span>";
				$hiddenOptionHTML .= "<div style='float:left;padding:10px 10px;font-size:11px;cursor:pointer;'><a style='color:#7A9E22' onclick='showAdvanceMenuOptions($ctr)' class='minMax'><pre style='display:inline'>[+]</pre> Advance Settings</a></div>";
				break;
			case "DID":
				$stmt="SELECT did_pattern,did_description from vicidial_inbound_dids $filterSQL order by did_pattern";
				$didpulldown = $this->asteriskDB->query($stmt);
				
				foreach ($didpulldown->result() as $did)
				{
				   $didArray[$did->did_pattern] = "{$did->did_pattern} - {$did->did_description}";
				}
				
				$hiddenOptionHTML .= "<label>DID: </label>".form_dropdown('option_route_value_'.$ctr,$didArray,$getcallmenuoptions[0]->option_route_value,'id="option_route_value_'.$ctr.'"');
				break;
			case "HANGUP":
				$hiddenOptionHTML .= "<label>Audio File: </label>".form_input('option_route_value_'.$ctr,$getcallmenuoptions[0]->option_route_value,'id="option_route_value_'.$ctr.'" maxlength="255" size="30"')." <a href=\"javascript:launch_chooser('option_route_value_$ctr','date',1200,document.getElementById('option_route_value_$ctr').value);\"><font color=\"blue\" size=\"1\">[ audio chooser ]</font></a> <div id=\"divoption_route_value_$ctr\" style=\"display:inline\"></div>";
				break;
			case "EXTENSION":
				$hiddenOptionHTML .= "<label>Extension: </label>".form_input('option_route_value_'.$ctr,$getcallmenuoptions[0]->option_route_value,'id="option_route_value_'.$ctr.'"')." <label>Context: </label>".form_input('option_route_value_context_'.$ctr,$getcallmenuoptions[0]->option_route_value_context,'id="option_route_value_context_'.$ctr.'"');
				break;
			case "PHONE":
				$stmt="SELECT extension,server_ip,dialplan_number,voicemail_id FROM phones $filterSQL ORDER BY extension";
				$phonepulldown = $this->asteriskDB->query($stmt);
				
				foreach ($phonepulldown->result() as $phone)
				{
				   $phoneArray[$phone->extension] = "{$phone->extension} - {$phone->server_ip} - {$phone->dialplan_number} - {$phone->voicemail_id}";
				}
				
				$hiddenOptionHTML .= "<label>Phone: </label>".form_dropdown('option_route_value_'.$ctr,$phoneArray,$getcallmenuoptions[0]->option_route_value,'id="option_route_value_'.$ctr.'"');
				break;
			case "VOICEMAIL":
				$hiddenOptionHTML .= "<label>Voicemail Box: </label>".form_input('option_route_value_'.$ctr,$getcallmenuoptions[0]->option_route_value,'id="option_route_value_'.$ctr.'" maxlength="255" size="15"')." <a href=\"javascript:launch_vm_chooser('option_route_value_$ctr','date',1200,document.getElementById('option_route_value_$ctr').value);\"><font color=\"blue\" size=\"1\">[ voicemail chooser ]</font></a> <div id=\"divoption_route_value_$ctr\" style=\"display:inline\"></div>";
				break;
			case "AGI":
				$hiddenOptionHTML .= "<label>AGI: </label>".form_input('option_route_value_'.$ctr,$getcallmenuoptions[0]->option_route_value,'id="option_route_value_'.$ctr.'" maxlength="255" size="50"');
				break;
		}
		
		echo $hiddenOptionHTML;
	}
?>
