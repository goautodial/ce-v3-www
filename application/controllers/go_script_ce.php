<?php
########################################################################################################
####  Name:             	go_script_ce.php                                                    ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Franco Hora				            		    ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_script_ce extends Controller{
    function __construct(){
         parent::Controller();
         $this->load->model(array('go_script'));
         $this->load->library(array('session','commonhelper'));
         $this->load->helper(array('html'));
	 $this->lang->load('userauth', $this->session->userdata('ua_language'));
	$this->is_logged_in();
    }

    /*
     * index
     * display template
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function index(){

        $username = $this->session->userdata('user_name');
        if(empty($username)){
               $this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
        }else{

          $folder = pathinfo($this->config->item('base_url'));
          if(ereg("[a-zA-Z_\-]",$folder['basename'])){

              $base_folder = $folder['basename']."/";

          } else {
  
              $base_folder = "";

          } 
          /*$this->commonhelper->writetofile("/var/www/html/$base_folder"."extrafiles/",
                                           'project_auth_entries.txt',
                                           "VICIDIAL|GOOD|".date('r')."|$username|XXXX|".$this->session->userdata('ip_address')."|".$this->session->userdata('user_agent')."|".$this->session->userdata('full_name')."|\n");
          */
        }

        /*$activeacounts = array();
        $accounts = $this->commonhelper->hostedaccounts();
        if(!empty($accounts)){
              foreach($accounts as $account){
                  $activeaccounts[$account->account_num] = $account->company;
              } 
        }
        
        $data['accounts'] = $activeaccounts;*/
        $data['userfulname'] = $this->session->userdata('full_name');
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';
        $data['user_level'] = $this->session->userdata('users_level');
	
	$data['theme'] = $this->session->userdata('go_theme');
	$data['bannertitle'] = $this->lang->line('go_scripts_banner');
	$data['adm']= 'wp-has-current-submenu';
	$data['hostp'] = $_SERVER['SERVER_ADDR'];
	$data['folded'] = 'folded';
	$data['foldlink'] = '';
	$togglestatus = "1";
	$data['togglestatus'] = $togglestatus;
	
        $data['fields'] =array('fullname'=>'Agent Name','vendor_lead_code'=>'vendor_lead_code','source_id'=>'source_id',
                               'list_id'=>'list_id','gmt_offset_now'=>'gmt_offset_now','called_since_last_reset'=>'called_since_last_reset',
                               'phone_code'=>'phone_code','phone_number'=>'phone_number','title'=>'title',
                               'first_name'=>'first_name','middle_initial'=>'middle_initial','last_name'=>'last_name',
                               'address1'=>'address1','address2'=>'address2','address3'=>'address3',
                               'city'=>'city','state'=>'state','province'=>'province','postal_code'=>'postal_code',
                               'country_code'=>'country_code','gender'=>'gender','date_of_birth'=>'date_of_birth',
                               'alt_phone'=>'alt_phone','email'=>'email','security_phrase'=>'security_phrase',
                               'comments'=>'comments','lead_id'=>'lead_id','campaign'=>'campaign',
                               'phone_login'=>'phone_login','group'=>'group','channel_group'=>'channel_group',
                               'SQLdate'=>'SQLdate','epoch'=>'epoch','uniqueid'=>'uniqueid',
                               'customer_zap_channel'=>'customer_zap_channel','server_ip'=>'server_ip','SIPexten'=>'SIPexten',
                               'session_id'=>'session_id','dialed_number'=>'dialed_number','dialed_label'=>'dialed_label',
                               'rank'=>'rank','owner'=>'owner','camp_script'=>'camp_script',
                               'in_script'=>'in_script','script_width'=>'script_width','script_height'=>'script_height',
                               'recording_filename'=>'recording_filename','recording_id'=>'recording_id','user_custom_one'=>'user_custom_one',
                               'user_custom_two'=>'user_custom_two','user_custom_three'=>'user_custom_three','user_custom_four'=>'user_custom_four',
                               'user_custom_five'=>'user_custom_five','preset_number_a'=>'preset_number_a','preset_number_b'=>'preset_number_b',
                               'preset_number_c'=>'preset_number_c','preset_number_d'=>'preset_number_d','preset_number_e'=>'preset_number_e',
                               'preset_number_f'=>'preset_number_f','preset_dtmf_a'=>'preset_dtmf_a','preset_dtmf_b'=>'preset_dtmf_b',
                               'did_id'=>'did_id','did_extension'=>'did_extension','did_pattern'=>'did_pattern',
                               'did_description'=>'did_description', 'closecallid'=>'closecallid','xfercallid'=>'xfercallid',
                               'agent_log_id'=>'agent_log_id','entry_list_id'=>'entry_list_id'
                              );
        $data['go_main_content'] = 'go_script/go_script_main_ce';
        $this->load->view('includes/go_dashboard_template.php',$data);
    }


    /*
     * collectscripts
     * function to collect all scripts
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function collectscripts(){
	 $page = $this->uri->segment(3);
	 $search = $this->uri->segment(4);
	 
         #check session
         $userlevel = $this->session->userdata('users_level');
         if($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))){
             $condition = array("`vs`.`user_group` IN ('".$this->session->userdata('user_group')."')");
         }
         $permissions = $this->commonhelper->getPermissions("script",$this->session->userdata("user_group"));
         if($permissions->script_read == "N"){
            die("You don't have permission to view this record(s)");
         }

	 if (strlen($search) > 0) {
	     $searchSQL = array("`vs`.`script_id` RLIKE '$search' OR `vs`.`script_name` RLIKE '$search'");
	     if (count($condition) > 0) {
		 $condition = $condition + $searchSQL;
	     } else {
		 $condition = $searchSQL;
	     }
	 }
	 
	 $query = $this->commonhelper
			->simpleretrievedata('vicidial_scripts vs',
				    'count(*) as ucnt',
				     array(array('go_scripts as gs',"vs.script_id=gs.script_id",'left')),
				     $condition
				   );
	 $total = $query->row()->ucnt;
	 $limit = 5;
	 $rp	= ($page=='ALL') ? $total : 25;
	 if (is_null($page) || $page < 1) { $page = 1; }
	 $start	= (($page-1) * $rp);
	 $limitSQL = array("$rp,$start");

         $this->limesurvey  = $this->load->database('limesurveydb',TRUE); 
         $data['results'] = $this->commonhelper
                                       ->simpleretrievedata('vicidial_scripts vs',
                                                   'vs.script_id,vs.script_name,vs.active,gs.campaign_id,vs.script_comments,gs.surveyid,vs.user_group',
                                                    array(array('go_scripts as gs',"vs.script_id=gs.script_id",'left')),
                                                    $condition,
                                                    array(array("vs.script_id","asc")),
						    $limitSQL
                                                  );
         if($data['results']->num_rows() > 0){
             $limeresult = array();
             foreach($data['results']->result() as $scripts){
                 $this->limesurvey->where(array('sid'=>$scripts->surveyid));
                 $lime = $this->limesurvey->get('lime_surveys');
                 if(!empty($lime)){ 
                    $limeresult[$scripts->script_id] = $lime->num_rows();
                 }
             }
             $data['limeresult'] = $limeresult;
         }
	 
	 $pg 	= $this->commonhelper->paging($page,$rp,$total,$limit);
	
	 if ($pg['last'] > 1) {
	     $pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top: 10px;">';
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
		$pagelinks = '';
	    }
	 }
	 $pageinfo = "<span style='float:right;padding-top:10px;'>Displaying {$pg['istart']} to {$pg['iend']} of {$pg['total']} scripts</span>";
	 $data['pagelinks'] = $pagelinks;
	 $data['pageinfo'] = $pageinfo;

         $this->load->view('go_script/go_script_elem_ce',$data);
    }

    /*
     * getscriptinfo
     * function to get information of specific script
     * @author: Franco E. Hora <info@goautodial.com>
     */
     function getscriptinfo(){
         
          if(!empty($_POST)){
               $userlevel = $this->session->userdata('users_level');
               $info = $this->commonhelper->simpleretrievedata('vicidial_scripts',
                                                               "script_id,script_name,script_comments,script_text,active",
                                                               null,
                                                               array(array('script_id'=>$_POST['scriptid'])));

               if($info->num_rows > 0){
                    $script = $info->result();
                    foreach($script as $detail){
                         $url = substr($detail->script_text,strpos($detail->script_text,'="')+2,strpos($detail->script_text,'B--"')-13);
                         $displayurl=rawurldecode($url);
                         $script[0]->script_text = eregi_replace($url,$displayurl,$detail->script_text);
                    }

                    $this->limesurvey  = $this->load->database('limesurveydb',TRUE); 
                    $results = $this->commonhelper
                                                ->simpleretrievedata('vicidial_scripts vs',
                                                                     'vs.script_id,vs.script_name,vs.active,gs.campaign_id,vs.script_comments,gs.surveyid',
                                                                     array(array('go_scripts as gs',"vs.script_id=gs.script_id",'left')),
                                                                     array(array('vs.script_id'=>$_POST['scriptid'])),
                                                                     array(array("vs.script_id","asc"))
                                                  );
                    if(!empty($results->row()->surveyid)){
                        $script[0]->surveyid = $results->row()->surveyid;
                    }else{
                        $script[0]->surveyid = "";
                    }

                    echo json_encode($script);
               }
          }
     }

     function batchprocess(){
 
           if(!empty($_POST)){


                 $postvars = $_POST;
                 unset($_POST);
                 foreach($postvars['ids'] as $val){

                     switch($postvars['action']){
                          case "Y" :
                          case "N" :
                               $data['active'] = $postvars['action'];
                               $data['script_id'] = $val;
                               $_POST = $data;
                               $this->modifyscript();
                          break;
                          case "D" :
                               $data['scriptid'] = $val;
                               $_POST = $data;
                               $this->deletescript();
                          break;
                     }
                 }

           } else {

               die("Error: No script to process!");

           }

     }


     /*
      * modifyscript
      * function to modify script
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function modifyscript(){

          $access = unserialize($this->session->userdata('useraccess'));
          //if(in_array('modify_scripts',$access)){
              if(!empty($_POST)){
                   if(array_key_exists("script_text",$_POST)){
                       $_POST['script_text'] = html_entity_decode($_POST['script_text']);
                   }
                   $result = $this->go_script->updatescript($_POST);
                   if(preg_match("/successful/",$result)){
                       $go_scripts = $this->commonhelper->simpleretrievedata('go_scripts',
                                                                       null,
                                                                       null,
                                                                       array(array("script_id"=>$_POST['script_id'])));
                        if($go_scripts->num_rows() > 0){
                             $goScript = $go_scripts->result();
                             if(!empty($goScript[0]->surveyid)){
                                 $quickvar['active'] = $_POST['active'];
                                 $quickvar['sid'] = $goScript[0]->surveyid;
                                 $quickvar['selected'] = "quick";
                                 unset($_POST); # unset first post values then change for quick change
                                 $_POST = $quickvar;
                                 $this->saveconfig();
                                 unset($result);
                             }
                        }
                   }
                   echo $result;
              }
          //}else{
          //    echo "Error: You are not allowed to modify data";
          //}
     }


     /*
      * deletescript
      * function to delete script
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function deletescript(){

          if(!empty($_POST['scriptid'])){

               $access = unserialize($this->session->userdata('useraccess'));
               if(in_array('delete_scripts',str_replace(":","",$access))){
                    $this->go_script->asteriskDB->trans_start();
                         $this->go_script->asteriskDB->delete('vicidial_scripts',array('script_id'=>$_POST['scriptid']));
                         $this->go_script->asteriskDB->delete('go_scripts',array('script_id'=>$_POST['scriptid']));
                         $this->go_script->asteriskDB->where('campaign_script',$_POST['scriptid']);
                         $this->go_script->asteriskDB->update('vicidial_campaigns',array('campaign_script'=>''));
                    $this->go_script->asteriskDB->trans_complete();

                    if($this->go_script->asteriskDB->trans_status === false){
                         echo "Error: Something went wrong while deleting data";
                    }else{
                         $queries = null;
                         foreach($this->go_script->asteriskDB->queries as $query){
                             $queries .= $query."\n";
                         }
                         $this->commonhelper->auditadmin('DELETE script',"Delete script script_id:".$_POST['scriptid'],$queries);
                         echo "Success: Item deleted complete";
                    }
               }else{
                    echo "Error: You are not allowed to delete this";
               }
               
          }else{
               echo "Error: Empty scriptid";
          }  
     }

     /*
      * previewscript
      * just to preview script text
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function previewscript (){

          $url = null;
          $sampledata = array('vendor_lead_code'=>'VENDOR:LEAD;CODE','list_id'=>'LISTID','list_name'=>'LISTNAME',
                              'list_description'=>'LISTDESCRIPTION','gmt_offset_now'=>'GMTOFFSET','phone_code'=>'1',
                              'phone_number'=>'7275551212','title'=>'Mr.','first_name'=>'JOHN','middle_initial'=>'Q',
                              'last_name'=>'PUBLIC','address1'=>'1234 Main St.','address2'=>'Apt. 3','address3'=>'ADDRESS3',
                              'city'=>'CHICAGO','state'=>'IL','province'=>'PROVINCE','postal_code'=>'33760',
                              'country_code'=>'USA','gender'=>'M','date_of_birth'=>'1970-01-01','alt_phone'=>'3125551111',
                              'email'=>'test@test.com','security_phrase'=>'SECUTIRY','comments'=>'COMMENTS',
                              'fullname'=>'JOE AGENT','fronter'=>'6666','user'=>'6666','lead_id'=>'1234','campaign'=>'TESTCAMP',
                              'phone_login'=>'gs102','group'=>'TESTCAMP','channel_group'=>'TESTCAMP','SQLdate'=>date("Y-m-d H:i:s"),
                              'epoch'=>date("U"),'uniqueid'=>'1163095830.4136','customer_zap_channel'=>'Zap/1-1',
                              'server_ip'=>'192.168.100.112','SIPexten'=>'SIP/gs102','session_id'=>'8600051',
                              'dialed_number'=>'3125551111','dialed_label'=>'ALT','rank'=>'99','owner'=>'6666',
                              'camp_script'=>'TESTSCRIPT','in_script'=>'','script_width'=>'800','script_height'=>'600',
                              'recording_filename'=>'1639_6666_7275551212','recording_id'=>'1235','user_custom_one'=>'custom one',
                              'user_custom_two'=>'custom two','custom_three'=>'custom three','custom_four'=>'cutom four',
                              'cutom_five'=>'custom five','preset_number_a'=>'preset_a','preset_number_b'=>'preset_b',
                              'preset_number_c'=>'preset_c','preset_number_d'=>'preset_d','preset_number_e'=>'preset_e',
                              'preset_number_f'=>'preset_f','preset_dtmf_a'=>'preset_dtmf_a','preset_dtmf_b'=>'preset_dtmf_b',
                              'did_id'=>'did_id','did_extension'=>'did_extension','did_pattern'=>'did_pattern',
                              'did_description'=>'did_description','closecallid'=>'closecallid','xfercallid'=>'xfercallid',
                              'agent_log_id'=>'agent_log_id','entry_list_id'=>'entry_list_id'
                             );


          $script_text = html_entity_decode($_POST['script_text']);
          $protocol = eregi_replace("/1.1","",$_SERVER['SERVER_PROTOCOL']);
          $host = $_SERVER['HTTP_HOST'];
          foreach($sampledata as $field => $value){
               if(eregi("iframe src",$script_text)){
                    $value = eregi_replace(' ',"+",$value);
               }
               $script_text = eregi_replace('--A--'.$field.'--B--',$value,$script_text);
               $script_text = eregi_replace("\n","<br/>",$script_text);
               $display = array('display'=>$script_text);
          }
          echo json_encode(array_merge(array('script_id'=>$_POST['script_id'],'script_name'=>$_POST['script_name']),$display));
     }


     /*
      * scriptadd
      * add new script
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function scriptaddwizard(){

          $username = $this->session->userdata('user_name');
          if(empty($username)){
               $this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
               #die( "Error: Session expired kindly re-login" );
          }
          if(!empty($_POST)){
                if($_POST['script_type']=="default"){
                    $jsonResult = array();
                    if($_POST['step']<4){
                        $userlevel = $this->session->userdata('users_level');
                        switch($_POST['step']){
                              case 1:
                                    $type = array('default'=>'Default','advance'=>'Advance(limesurvey)');
                                    $theElements = "
                                                    <div id='wizard-form-elems' class='scripts-values'>
                                                    ".form_open(null,"id='script-add-wizard'");
                                    /*if($userlevel > 8){
                                         $theElements .="<div class='scripts-labels script-add-labels' >Accounts</div>
                                                         <div class='scripts-values script-add-values' >";
                                                                 $accounts = $this->commonhelper->hostedaccounts();
                                                                 if(array_key_exists('accounts',$_POST)){
                                                                     if($accounts[0]->account_num === $_POST['accounts']){
                                                                          $selected = $_POST['accounts'];
                                                                     }else{
                                                                          $selected = '';
                                                                     }
                                                                 }
                                         $theElements .= form_dropdown('accounts',$accounts,$selected,'id="accounts"');
                                         $theElements .="
                                                         </div>";
                                    }*/
                                    $theElements .="     <div class='scripts-labels script-add-labels' ><strong>Script Type:</strong></div>
                                                         <div class='scripts-values script-add-values' >";
                                                                     if(array_key_exists('script_type',$type)){
                                                                          $selected = $_POST['script_type'];
                                                                     }else{
                                                                          $selected = '';
                                                                     }
                                    $theElements .= form_dropdown('script_type',$type,$selected,'id="script_type"');
                                    $theElemnts .="           
                                                         </div>
                                                         <div>&nbsp;</div>
                                                    ".form_close()."
                                                    </div>";

                                    $jsonResult['display'] = array('breadcrumb'=>array('script_wizard_breadcrumb'=>$this->config->item('base_url').'/img/step-navigation-small.png'),
                                                                   'content'=>array('step'=>2,
                                                                                    'wizard_step'=>$this->config->item('base_url').'/img/step1-trans.png',
                                                                                    'wizard_form_elems'=>$theElements),
                                                                   'action'=>array('actions'=>'<a onclick="next(this)" rel="2">Next</a>')
                                                                  );
                               break;
                               case 2:
                                      if($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))){
                                        $account_group = $this->session->userdata('user_group');
                                      }else{
                                      #$account_group = $this->session->userdata('user_group');
										$account_group = "script";
                                      }
                                      #$campaigns = $this->commonhelper->getallowablecampaign($account_group,true,array('campaign_name NOT LIKE "%Survey%"'));
                                      $this->go_script->asteriskDB->select('campaign_id,campaign_name');
                                      $result = $this->go_script->asteriskDB->get('vicidial_campaigns')->result();
                                      if(!empty($result)){
                                           $campaigns[" "] = "None";
                                           foreach($result as $info){
                                               $campaigns[$info->campaign_id] = "{$info->campaign_id} - {$info->campaign_name}";
                                           }
                                      }else{
                                           $campaigns = array();
                                      }
                                      $script = $this->commonhelper->simpleretrievedata('go_scripts',
                                                                                        "script_id,account_num,REPLACE(script_id,'script','') AS scriptid",
                                                                                        null,
                                                                                        array(array('account_num'=>$account_group)),
                                                                                        array(array('CAST(scriptid AS unsigned)','desc')),
                                                                                        1
                                                                                       );
                                      $scriptId_elem = $this->scriptid($script,$_POST['script_type']);
                                      #$scriptId_elem

                                      $fields = array('fullname'=>'Agent Name','vendor_lead_code'=>'vendor_lead_code','source_id'=>'source_id',
                                                      'list_id'=>'list_id','gmt_offset_now'=>'gmt_offset_now','called_since_last_reset'=>'called_since_last_reset',
                                                      'phone_code'=>'phone_code','phone_number'=>'phone_number','title'=>'title',
                                                      'first_name'=>'first_name','middle_initial'=>'middle_initial','last_name'=>'last_name',
                                                      'address1'=>'address1','address2'=>'address2','address3'=>'address3',
                                                      'city'=>'city','state'=>'state','province'=>'province','postal_code'=>'postal_code',
                                                      'country_code'=>'country_code','gender'=>'gender','date_of_birth'=>'date_of_birth',
                                                      'alt_phone'=>'alt_phone','email'=>'email','security_phrase'=>'security_phrase',
                                                      'comments'=>'comments','lead_id'=>'lead_id','campaign'=>'campaign',
                                                      'phone_login'=>'phone_login','group'=>'group','channel_group'=>'channel_group',
                                                      'SQLdate'=>'SQLdate','epoch'=>'epoch','uniqueid'=>'uniqueid',
                                                      'customer_zap_channel'=>'customer_zap_channel','server_ip'=>'server_ip','SIPexten'=>'SIPexten',
                                                      'session_id'=>'session_id','dialed_number'=>'dialed_number','dialed_label'=>'dialed_label',
                                                      'rank'=>'rank','owner'=>'owner','camp_script'=>'camp_script',
                                                      'in_script'=>'in_script','script_width'=>'script_width','script_height'=>'script_height',
                                                      'recording_filename'=>'recording_filename','recording_id'=>'recording_id','user_custom_one'=>'user_custom_one',
                                                      'user_custom_two'=>'user_custom_two','user_custom_three'=>'user_custom_three','user_custom_four'=>'user_custom_four',
                                                      'user_custom_five'=>'user_custom_five','preset_number_a'=>'preset_number_a','preset_number_b'=>'preset_number_b',
                                                      'preset_number_c'=>'preset_number_c','preset_number_d'=>'preset_number_d','preset_number_e'=>'preset_number_e',
                                                      'preset_number_f'=>'preset_number_f','preset_dtmf_a'=>'preset_dtmf_a','preset_dtmf_b'=>'preset_dtmf_b',
                                                      'did_id'=>'did_id','did_extension'=>'did_extension','did_pattern'=>'did_pattern',
                                                      'did_description'=>'did_description', 'closecallid'=>'closecallid','xfercallid'=>'xfercallid',
                                                      'agent_log_id'=>'agent_log_id','entry_list_id'=>'entry_list_id'
                                                 );
				     //james
                                    $theElements = "<div id='wizard-form-elems' class='scripts-values'>
                                                    ".form_open(null,"id='step2-wizard'")."
                                                    <div class='scripts-labels script-add-labels' ><strong>Script ID:</strong></div>
                                                    <div class='scripts-values script-add-values' >".$scriptId_elem."</div><br class='clear'/>
                                                    <div class='scripts-labels script-add-labels' ><strong>Script Name:</strong></div>
                                                    <div class='scripts-values script-add-values' >".form_input('script_name',
                                                                                                                  ((array_key_exists('script_name',$_POST))?$_POST['script_name']:''),
                                                                                                                 'id="script_name" size="25"')."</div>
                                                    <div class='scripts-labels script-add-labels' ><strong>Script Comments:</strong></div>
                                                    <div class='scripts-values script-add-values' >".form_input('script_comments',
                                                                                                                ((array_key_exists('script_comments',$_POST))?$_POST['script_comments']:''),
                                                                                                                "size='35'")."</div>
                                                    <div class='scripts-labels script-add-labels' ><strong>Active:</strong></div>
                                                    <div class='scripts-values script-add-values' >";
                                    $theElements .= form_dropdown('active',array('Y'=>'Yes','N'=>'No'),$_POST['active'],'id="active"');
                                    $theElements.= "</div>
                                                    <div class='scripts-labels script-add-labels' ><strong>Script Text:</strong></div>
                                                    <div class='scripts-values script-add-values' >";
                                    $theElements .=     form_dropdown(null,$fields,null);
                                    $theElements .=  "  <a  onclick='updatetextarea(this)'>Insert</a><br/>";
                                    $theElements .=     form_textarea(array('cols'=>'33',
                                                                            'rows'=>'10',
                                                                            'id'=>'script_text',
                                                                            'name'=>'script_text',
                                                                            'value'=>((array_key_exists('script_text',$_POST))?$_POST['script_text']:'')
                                                                           )).
                                                    "</div>
                                                    <!-- <div class='scripts-labels script-add-labels' ><strong>Choose Campaign:</strong></div>
                                                    <div class='scripts-values script-add-values'>";
                                                         if(array_key_exists('campaign_id',$_POST)){
                                                               $selected = $_POST['campaign_id'];
                                                         }else{
                                                               $selected = "";
                                                         }
                                    $theElements .=      form_dropdown('campaign_id',$campaigns,$selected,'id="campaign_id" maxlength="30"');
                                    $theElements .="</div> -->
                                                    <div> </div>
                                                    ".form_close()."
                                                    </div>";
                                    $_POST['step'] -= 1;
                                    $jsonResult['prev_step']  = $_POST;
                                    $jsonResult['display'] = array('breadcrumb'=>array('script_wizard_breadcrumb'=>$this->config->item('base_url').'/img/step2-navigation-small.png'),
                                                                   'content'=>array(
                                                                                    'wizard_step'=>$this->config->item('base_url').'/img/step2-trans.png',
                                                                                    'wizard_form_elems'=>$theElements),
                                                                   'action'=>array('actions'=>'<a onclick="back(this)" rel="1">Back</a>&nbsp;|&nbsp;<a onclick="next(this)" rel="3">Next</a>'),
                                                                   'step'=>2
                                                                  );
                               break;
                               case 3:
                                     $sampledata = array('vendor_lead_code'=>'VENDOR:LEAD;CODE','list_id'=>'LISTID','list_name'=>'LISTNAME',
                                                         'list_description'=>'LISTDESCRIPTION','gmt_offset_now'=>'GMTOFFSET','phone_code'=>'1',
                                                         'phone_number'=>'7275551212','title'=>'Mr.','first_name'=>'JOHN','middle_initial'=>'Q',
                                                         'last_name'=>'PUBLIC','address1'=>'1234 Main St.','address2'=>'Apt. 3','address3'=>'ADDRESS3',
                                                         'city'=>'CHICAGO','state'=>'IL','province'=>'PROVINCE','postal_code'=>'33760',
                                                         'country_code'=>'USA','gender'=>'M','date_of_birth'=>'1970-01-01','alt_phone'=>'3125551111',
                                                         'email'=>'test@test.com','security_phrase'=>'SECUTIRY','comments'=>'COMMENTS',
                                                         'fullname'=>'JOE AGENT','fronter'=>'6666','user'=>'6666','lead_id'=>'1234','campaign'=>'TESTCAMP',
                                                         'phone_login'=>'gs102','group'=>'TESTCAMP','channel_group'=>'TESTCAMP','SQLdate'=>date("Y-m-d H:i:s"),
                                                         'epoch'=>date("U"),'uniqueid'=>'1163095830.4136','customer_zap_channel'=>'Zap/1-1',
                                                         'server_ip'=>'192.168.100.112','SIPexten'=>'SIP/gs102','session_id'=>'8600051',
                                                         'dialed_number'=>'3125551111','dialed_label'=>'ALT','rank'=>'99','owner'=>'6666',
                                                         'camp_script'=>'TESTSCRIPT','in_script'=>'','script_width'=>'800','script_height'=>'600',
                                                         'recording_filename'=>'1639_6666_7275551212','recording_id'=>'1235','user_custom_one'=>'custom one',
                                                         'user_custom_two'=>'custom two','custom_three'=>'custom three','custom_four'=>'cutom four',
                                                         'cutom_five'=>'custom five','preset_number_a'=>'preset_a','preset_number_b'=>'preset_b',
                                                         'preset_number_c'=>'preset_c','preset_number_d'=>'preset_d','preset_number_e'=>'preset_e',
                                                         'preset_number_f'=>'preset_f','preset_dtmf_a'=>'preset_dtmf_a','preset_dtmf_b'=>'preset_dtmf_b',
                                                         'did_id'=>'did_id','did_extension'=>'did_extension','did_pattern'=>'did_pattern',
                                                         'did_description'=>'did_description','closecallid'=>'closecallid','xfercallid'=>'xfercallid',
                                                         'agent_log_id'=>'agent_log_id','entry_list_id'=>'entry_list_id'
                                                    );
                                    $script_text = $_POST['script_text'];
                                    foreach($sampledata as $field => $value){
                                        $script_text = eregi_replace('--A--'.$field.'--B--',$value,$script_text);
                                        $script_text = eregi_replace("\n","<br/>",$script_text);
                                    }

                                    $theElements = "<div id='wizard-form-elems' class='scripts-values'>
                                                    ".form_open(null,"id='step3-wizard'")."
                                                    <div class='script-message'><strong><i>Script Preview</i></strong></div>
                                                    <div class='scripts-labels script-add-labels' ><strong>Script ID:</strong></div>
                                                    <div class='scripts-values script-add-values' ><span>".$_POST['script_id']."</span>".form_hidden('script_id',$_POST['script_id'])."</div><br class='clear'/>
                                                    <div class='scripts-labels script-add-labels' ><strong>Script Name:</strong></div>
                                                    <div class='scripts-values script-add-values' >".$_POST['script_name'].form_hidden('script_name',$_POST['script_name'])."</div><br class='clear'/>
                                                    <div class='scripts-labels script-add-labels' ><strong>Script Comments:</strong></div>
                                                    <div class='scripts-values script-add-values' >".$_POST['script_comments'].form_hidden('script_comments',$_POST['script_comments'])."</div><br class='clear'/>
                                                    <div class='scripts-labels script-add-labels' ><strong>Active:</strong></div>
                                                    <div class='scripts-values script-add-values' >".($_POST['active']=='Y'?'Yes':'No').form_hidden('active',$_POST['active'])."</div><br class='clear'/>
                                                    <div class='scripts-labels script-add-labels' ><strong>Script Text:</strong></div>
                                                    <div class='scripts-values script-add-values' ><div class='corner-all'>$script_text</div>".form_hidden('script_text',$_POST['script_text'])."</div><br class='clear'/>
                                                    <div class='scripts-labels script-add-labels' ><strong>Campaign Id</strong></div>
                                                    <div class='scripts-values script-add-values' >".$_POST['campaign_id'].form_hidden('campaign_id',$_POST['campaign_id'])."</div><br class='clear'/>
                                                    <div>&nbsp;</div>
                                                    ".form_close()."
                                                    </div>";
                                                    
                                    $_POST['step']-=1;
                                    $jsonResult['prev_step']  = $_POST;
                                    $jsonResult['display'] = array('breadcrumb'=>array('script_wizard_breadcrumb'=>$this->config->item('base_url').'/img/step3-navigation-small.png'),
                                                                   'content'=>array(
                                                                                    'wizard_step'=>$this->config->item('base_url').'/img/step3-trans.png',
                                                                                    'wizard_form_elems'=>$theElements),
                                                                   'action'=>array('actions'=>'<a onclick="back(this)" rel="2">Back</a>&nbsp;|&nbsp;<a onclick="next(this)" rel="4">Save</a>'),
                                                                   'step'=>3
                                                                  ); 
                              break;
                        }
                    }else{ # save data
                         $_POST['accounts'] = "script";
                         $this->savescript($_POST);
                    }
                }else{
                    if($_POST['step'] != 'Now' && $_POST['step'] != 'Later'){ # check end

                        $rootdir = $this->config->item('lime_path')."/limesurvey";
                        require_once($rootdir.'/classes/adodb/adodb.inc.php');
                        require_once($rootdir.'/common_functions_ci.php');
                        require_once($rootdir.'/admin/admin_functions.php');
                        require_once($rootdir.'/classes/core/sanitize.php');
                        require_once($rootdir.'/classes/core/language.php');
                        require_once($rootdir.'/admin/classes/core/sha256.php');
                        $clang = new limesurvey_lang('en');
                        require_once($rootdir.'/classes/core/surveytranslator_ci.php');
                        switch($_POST['step']){
                            case 1:
                                    $type = array('default'=>'Default','advance'=>'Advance(limesurvey)');
                                    $theElements = "
                                                    <div id='wizard-form-elems' class='scripts-values'>
                                                    ".form_open(null,"id='script-add-wizard'");
                                    if($userlevel > 8){
                                         $theElements .="<div class='scripts-labels script-add-labels' >Accounts</div>
                                                         <div class='scripts-values script-add-values' >";
                                                                 $accounts = $this->commonhelper->hostedaccounts();
                                                                 if(array_key_exists('accounts',$_POST)){
                                                                     if($accounts[0]->account_num === $_POST['accounts']){
                                                                          $selected = $_POST['accounts'];
                                                                     }else{
                                                                          $selected = '';
                                                                     }
                                                                 }
                                         $theElements .= form_dropdown('accounts',$accounts,$selected,'id="accounts"');
                                         $theElements .="
                                                         </div>";
                                    }
                                    $theElements .="     <div class='scripts-labels script-add-labels' ><strong>Script Type:</strong></div>
                                                         <div class='scripts-values script-add-values' >";
                                                                     if(array_key_exists('script_type',$type)){
                                                                          $selected = $_POST['script_type'];
                                                                     }else{
                                                                          $selected = '';
                                                                     }
                                    $theElements .= form_dropdown('script_type',$type,$selected,'id="script_type"');
                                    $theElemnts .="           
                                                         </div>
                                                         <div>&nbsp;</div>
                                                    ".form_close()."
                                                    </div>";

                                    $jsonResult['display'] = array('breadcrumb'=>array('script_wizard_breadcrumb'=>$this->config->item('base_url').'/img/step-navigation-small.png'),
                                                                   'content'=>array('step'=>2,
                                                                                    'wizard_step'=>$this->config->item('base_url').'/img/step1-trans.png',
                                                                                    'wizard_form_elems'=>$theElements),
                                                                   'action'=>array('actions'=>'<a onclick="next(this)" rel="2">Next</a>')
                                                                  );
                            break;
                            case '2':
										if($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))){
										  $account_group = $this->session->userdata('user_group');
										}else{
										#$account_group = $this->session->userdata('user_group');
										  $account_group = "lime";
										}
                                       #$campaigns = $this->commonhelper->getallowablecampaign($account_group,true,array('campaign_name NOT LIKE "%Survey%"'));
                                       $this->go_script->asteriskDB->select('campaign_id,campaign_name');
                                       $result = $this->go_script->asteriskDB->get('vicidial_campaigns')->result();
                                       if(!empty($result)){
                                           $campaigns[" "] = "None";
                                           foreach($result as $info){
                                               $campaigns[$info->campaign_id] = "{$info->campaign_id} - {$info->campaign_name}";
                                           }
                                       }else{
                                           $campaigns = array();
                                       }
                                       $script = $this->commonhelper->simpleretrievedata('go_scripts',
                                                                                         "script_id,account_num,REPLACE(script_id,'script','') AS scriptid",
                                                                                         null,
                                                                                         array(array('account_num'=>$account_group)),
                                                                                         array(array('CAST(scriptid AS unsigned)','desc')),
                                                                                         1 
                                                                                        );
                                       $scriptId_elem = $this->scriptid($script,$_POST['script_type']);
                                       $theElements = "
                                                       <div id='wizard-form-elems' class='scripts-values'>
                                                       ".form_open("id='step2-wizard'")."
                                                       <div class='scripts-labels script-add-labels' ><strong>Script ID:</strong></div>
                                                       <div class='scripts-values script-add-values' >".$scriptId_elem."</div><br class='clear'/>
                                                       <div class='scripts-labels script-add-labels' ><strong>Script Name:</strong></div>
                                                       <div class='scripts-values script-add-values' >".form_input('script_name',((array_key_exists('script_name',$_POST))?$_POST['script_name']:''),'size="25" id="script_name"')."</div>
                                                       <div class='scripts-labels script-add-labels' ><strong>Language:</strong></div>
                                                       <div class='scripts-values script-add-values' >";
                                                       $theElements .= "<select name='lang' id='lang'>";
                                                          foreach(getLanguageData(false,$clang) as $key=>$value){
                                                              if($key == 'en'){
                                                                 $selected = 'selected="selected"';
                                                              }else{
                                                                 $selected = "";
                                                              }
                                                              $theElements .= "<option value='$key' $selected>".getLanguageNameFromCode($key,false,$clang) ."</option>";
                                                          }
                                                       $theElements .= "</select>";
                                       $theElements .= "
                                                       </div>
                                                       <!-- <div class='scripts-labels script-add-labels' ><strong>Choose Campaign:</strong></div>
                                                       <div class='scripts-values script-add-values'>
                                                            ";
                                                            if(array_key_exists('campaign_id',$_POST)){
                                                                $selected = $_POST['campaign_id'];
                                                            }else{
                                                                $selected = "";
                                                            }
                                       $theElements .=      form_dropdown('campaign_id',$campaigns,$selected,'id="campaign_id" maxlength="30"');
                                       $theElements .="     
                                                       </div> -->
                                                       <div>&nbsp;</div>
                                                       ".form_close()."
                                                       </div>
                                                      ";
                                       $_POST['step'] = 1;
                                       $jsonResult['prev_step'] = $_POST;
                                       $jsonResult['display'] = array('breadcrumb'=>array('script_wizard_breadcrumb'=>$this->config->item('base_url').'/img/step2-navigation-small.png'),
                                                                      'content'=>array(
                                                                                       'wizard_step'=>$this->config->item('base_url').'/img/step2-trans.png',
                                                                                       'wizard_form_elems'=>$theElements),
                                                                      'action'=>array('actions'=>'<a onclick="back(this)" rel="1">Back</a>&nbsp;|&nbsp;<a onclick="next(this)" rel="2_1">Next</a>'),
                                                                      'step'=>2
                                                                     );
                            break;
                            case '2_1':
                                       $theElements = "
                                                       <div id='wizard-form-elems' class='scripts-values'>
                                                       ".form_open(null,"id='step2-1-wizard'")."
                                                       <div class='scripts-labels script-add-labels' ><strong>Script ID:</strong></div>
                                                       <div class='scripts-values script-add-values' ><span>".$_POST['script_id']."</span>".form_hidden('script_id',((array_key_exists('script_id',$_POST))?$_POST['script_id']:''))."</div><br class='clear'/>
                                                       <div class='scripts-labels script-add-labels' ><strong>Description/Comments:</strong></div>
                                                       <div class='scripts-values script-add-values' >".form_input('script_comments',((array_key_exists('script_comments',$_POST))?$_POST['script_comments']:''),'size="25"')."</div>
                                                       <div class='scripts-labels script-add-labels' ><strong>Welcome Message:</strong></div>
                                                       <div class='scripts-values script-add-values' >";
                                       $theElements .=     form_input('welcome_message',((array_key_exists('welcome_message',$_POST))?$_POST['welcome_message']:''),'size="25"');
                                       $theElements .= "
                                                       </div><br/>
                                                       <div class='scripts-labels script-add-labels' ><strong>Closing/End Message:</strong></div>
                                                       <div class='scripts-values script-add-values'>
                                                            ";
                                       $theElements .=     form_input('end_message',((array_key_exists('end_message',$_POST))?$_POST['end_message']:''),'size="25"');
                                       $theElements .="     
                                                       </div>
                                                       <div class='scripts-labels script-add-labels' ><strong>Post / End URL:</strong></div>
                                                       <div class='scripts-values script-add-values'>";
                                       $theElements .=     form_input('survey_url',((array_key_exists('survey_url',$_POST))?$_POST['survey_url']:'http://'),'size="25"');
                                       $theElements .="     
                                                       </div>
                                                       <div class='scripts-labels script-add-labels' ><strong>URL Description:</strong></div>
                                                       <div class='scripts-values script-add-values'>";
                                       $theElements .=     form_input('survey_url_desc',((array_key_exists('survey_url_desc',$_POST))?$_POST['survey_url_desc']:''),'size="25"');
                                       $theElements .="     
                                                       </div>";
                                       $theElements .=" <div>&nbsp;</div>
                                                       ".form_close()."
                                                       </div>
                                                      ";
                                       $_POST['step'] = 2;
                                       $jsonResult['prev_step'] = $_POST;
                                       $jsonResult['display'] = array('breadcrumb'=>array('script_wizard_breadcrumb'=>$this->config->item('base_url').'/img/step2-navigation-small.png'),
                                                                      'content'=>array(
                                                                                       'wizard_step'=>$this->config->item('base_url').'/img/step2.1-trans.png',
                                                                                       'wizard_form_elems'=>$theElements),
                                                                      'action'=>array('actions'=>'<a onclick="back(this)" rel="2">Back</a>&nbsp;|&nbsp;<a onclick="next(this)" rel="3">Next</a>'),
                                                                      'step'=>"2_1"
                                                                     );
                            break;
                            case 3:
                                       $theElements = "
                                                       <div id='wizard-form-elems' class='scripts-values'>";
                                       $theElements .= form_open(null,'id="script-save"');
                                                       $theElements .= "<div class='scripts-values shoutout'>
                                                                              <input type='hidden' name='script_id' value='".$_POST['script_id']."'>
                                                                              Would you like to configure your 
                                                                              survey questions or your LimeSurvey survey <br/> in advance mode now?
                                                                        </div>";
                                       $theElements .=" <div>&nbsp;</div>";
                                       $theElements .= form_close();
                                       $theElements .=" </div>
                                                      ";
                                       $_POST['step'] = "2_1";
                                       $jsonResult['prev_step'] = $_POST;
                                       $jsonResult['display'] = array('breadcrumb'=>array('script_wizard_breadcrumb'=>$this->config->item('base_url').'/img/step3-navigation-small.png'),
                                                                      'content'=>array(
                                                                                       'wizard_step'=>$this->config->item('base_url').'/img/step3-trans.png',
                                                                                       'wizard_form_elems'=>$theElements),
                                                                      'action'=>array('actions'=>'<a onclick="back(this)" rel="2_1">Back</a>&nbsp;|&nbsp;<a rel="Now" onclick="next(this)">Yes</a>&nbsp;|&nbsp;<a rel="Later" onclick="next(this)">Later</a>'),
                                                                      'step'=>3
                                                                     );
                            break;
                            default:
                            break;
                        }
                    } else { // saving limesurvey 
                        $_POST['accounts'] = "lime";
                        $this->savescript($_POST);
                    }
                }

                if(!empty($jsonResult)){
                     echo json_encode($jsonResult);
                }

          }else{
                echo "Error: Empty data variables!";
          }
     }

    /*
     * savescript
     * preparing to save data 
     * @author: Franco E. Hora <info@goautodial.com>
     * @param : $postvars > post variables
     */
    function savescript($postvars=array()){
          $username = $this->session->userdata('user_name');
          if(empty($username) || is_null($username)){
              $this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
              #die("Error: Session expired kindly re-login");
          }
          if(!empty($postvars)){
 
              if($postvars['script_type'] == 'default'){
					if($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))){
						$accounts = $this->session->userdata('user_group');
					} else {
						if(array_key_exists('accounts',$postvars)){
							$accounts = $postvars['accounts'];
						}else{
						    $accounts = $this->session->userdata('user_group');
						}
					}

                   $data['vicidial_scripts'] = array('data'=>
                                                       array('script_id'=>$postvars['script_id'],'script_name'=>$postvars['script_name'],
                                                             'script_comments'=>$postvars['script_comments'],'active'=>$postvars['active'],
                                                             'script_text'=>$postvars['script_text'],'user_group'=>$accounts));
                   $data['go_scripts'] = array('data'=>
                                               array('account_num'=>$accounts,'script_id'=>$postvars['script_id'],
                                                     'campaign_id'=>$postvars['campaign_id'],'surveyid'=>''));
                   $data['vicidial_campaigns'] = array('data'=>
                                                           array('campaign_script'=>$postvars['script_id']),
                                                       'condition'=>
                                                           array('campaign_id'=>$postvars['campaign_id'])
                                                 );
                   $result=$this->go_script->savedefaultscript($data);
                   die($result);
              } else {

                   $rootdir = $this->config->item('lime_path')."/limesurvey";
                   require_once($rootdir.'/classes/adodb/adodb.inc.php');
                   require_once($rootdir.'/common_functions_ci.php');
                   require_once($rootdir.'/admin/admin_functions.php');
                   require_once($rootdir.'/classes/core/sanitize.php');
                   require_once($rootdir.'/classes/core/language.php');
                   require_once($rootdir.'/admin/classes/core/sha256.php');
                   $clang = new limesurvey_lang('en');
                   require_once($rootdir.'/classes/core/surveytranslator_ci.php');

                   do
                   {
                      $surveyid = sRandomChars(5,'123456789');
                      $this->go_script->limesurveyDB->where(array('sid'=>$surveyid));
                      $isexist = $this->go_script->limesurveyDB->get('lime_surveys');
                   }
                   while ($isexist->num_rows > 0);

                   $userInfo = $this->go_script->collectfromviciuser($username);
                   if($userInfo->num_rows() > 0){
                        $userDetail = $userInfo->result();
                        $viciemail = $userDetail[0]->email;
                        $viciuseralias = $userDetail[0]->user;
                        $vicipass = $userDetail[0]->pass;
                        $vicicompany = $userDetail[0]->full_name;
                        #$viciuser = $userDetail[0]->user_group;
						if($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))){
							$viciuser = $userDetail[0]->user_group;
						} else {
							$viciuser = "lime";
						}
                   }
                   $userInfo = $this->go_script->collectfromlimesurvey($viciuseralias);
                   $userlevel = $this->session->userdata('users_level');
                   if($userInfo->num_rows() < 1 ){
                             # create new limesurvey user
                             $newUser = array('users_name'=>$viciuseralias,'password'=>SHA256::hashing($vicipass),
                                              'full_name'=>$vicicompany,'parent_id'=>'1','lang'=>'auto',
                                              'email'=>$viciemail,'create_survey'=>'1','create_user'=>'1',
                                              'delete_user'=>'1','configurator'=>'1','manage_template'=>'1','manage_label'=>'1');
                             $this->go_script->insertTolimesurvey($newUser,'lime_users',$newId);
                             if(!empty($newId)){
                                   $this->go_script->insertTolimesurvey(array('uid'=>$newId,'folder'=>'default','use'=>'1'),'lime_templates_rights');
                             }
                             $uid = $newId;
                   }else{
                        $userDetail = $userInfo->result();
                        $uid = $userDetail[0]->uid;
                   }

                   $aDefaultTexts=aTemplateDefaultTexts($clang,'unescaped');
                   $languagedetails=getLanguageDetails($postvars['lang'],$clang);
                   $aDefaultTexts['admin_detailed_notification']=$aDefaultTexts['admin_detailed_notification_css'].$aDefaultTexts['admin_detailed_notification'];

                   $this->go_script->limesurveyDB->where(array('sid'=>$surveyid));
                   $group = $this->go_script->limesurveyDB->get('lime_groups');
                   $count = $group->num_rows();
                   $count++;
                   if($count < 100){
                      $lastGroup = "0$count";
                   }elseif($count < 10){
                      $lastGroup = "00$count";
                   }

                   $data['limesurvey'] = array('lime_surveys'
                                                   => array('data'
                                                               => array(
                                                                        array('sid'=>$surveyid,'owner_id'=>$uid,
                                                                              'admin'=>$vicicompany,'adminemail'=>$viciemail,
                                                                              'active'=>'N','format'=>'G',
                                                                              'language'=>$postvars['lang'],'datecreated'=>date('Y-m-d'),
                                                                              'htmlemail'=>'Y','usecaptcha'=>'D',
                                                                              'bounce_email'=>$viciemail
                                                                             )
                                                                       )
                                                           ),
                                                'lime_surveys_languagesettings'
                                                   => array('data'
                                                               => array(
                                                                        array('surveyls_survey_id'=>$surveyid,'surveyls_language'=>$postvars['lang'],
                                                                              'surveyls_title'=>$postvars['script_name'],
                                                                              'surveyls_email_invite_subj'=>str_replace("'","\\'",str_replace("\n","<br />",$aDefaultTexts['invitation_subject'])),
                                                                              'surveyls_email_invite'=>str_replace("'","\\'",str_replace("\n","<br />",$aDefaultTexts['invitation'])),
                                                                              'surveyls_email_remind_subj'=>str_replace("'","\\'",str_replace("\n","<br />",$aDefaultTexts['reminder_subject'])),
                                                                              'surveyls_email_remind'=>str_replace("'","\\'",str_replace("\n","<br />",$aDefaultTexts['reminder'])),
                                                                              'surveyls_email_confirm_subj'=>str_replace("'","\\'",str_replace("\n","<br />",$aDefaultTexts['confirmation_subject'])),
                                                                              'surveyls_email_confirm'=>str_replace("'","\\'",str_replace("\n","<br />",$aDefaultTexts['confirmation'])),
                                                                              'surveyls_email_register_subj'=>str_replace("'","\\'",str_replace("\n","<br />",$aDefaultTexts['registration_subject'])),
                                                                              'surveyls_email_register'=>str_replace("'","\\'",str_replace("\n","<br />",$aDefaultTexts['registration'])),
                                                                              'email_admin_notification_subj'=>str_replace("'","\\'",str_replace("\n","<br />",$aDefaultTexts['admin_notification_subject'])),
                                                                              'email_admin_notification'=>str_replace("'","\\'",str_replace("\n","<br />",$aDefaultTexts['admin_notification'])),
                                                                              'email_admin_responses_subj'=>str_replace("'","\\'",str_replace("\n","<br />",$aDefaultTexts['admin_detailed_notification_subject'])),
                                                                              'email_admin_responses'=>str_replace("'","\\'",str_replace("\n","<br />",$aDefaultTexts['admin_detailed_notification'])),
                                                                              'surveyls_dateformat'=>$languagedetails['dateformat'],'surveyls_description'=>$postvars['script_comments'],
                                                                              'surveyls_welcometext'=>$postvars['welcome_message'],'surveyls_endtext'=>$postvars['end_message'],
                                                                              'surveyls_url'=>$postvars['survey_url'],'surveyls_urldescription'=>$postvars['survey_url_desc']
                                                                        )
                                                                  )
                                                           ),
                                                'lime_survey_permissions'
                                                   => array('data'
                                                               =>array(
                                                                        array('sid'=>$surveyid,'uid'=>$uid,'permission'=>'assessments',
                                                                              'create_p'=>'1','read_p'=>'1','update_p'=>'1','delete_p'=>'1',
                                                                              'import_p'=>'0','export_p'=>'0'
                                                                             ),
                                                                        array('sid'=>$surveyid,'uid'=>$uid,'permission'=>'translations',
                                                                              'create_p'=>'0','read_p'=>'1','update_p'=>'1','delete_p'=>'0',
                                                                              'import_p'=>'0','export_p'=>'0'
                                                                             ),
                                                                        array('sid'=>$surveyid,'uid'=>$uid,'permission'=>'quotas',
                                                                              'create_p'=>'1','read_p'=>'1','update_p'=>'1','delete_p'=>'1',
                                                                              'import_p'=>'0','export_p'=>'0'
                                                                             ),
                                                                        array('sid'=>$surveyid,'uid'=>$uid,'permission'=>'responses',
                                                                              'create_p'=>'1','read_p'=>'1','update_p'=>'1','delete_p'=>'1',
                                                                              'import_p'=>'1','export_p'=>'1'
                                                                             ),
                                                                        array('sid'=>$surveyid,'uid'=>$uid,'permission'=>'statistics',
                                                                              'create_p'=>'0','read_p'=>'1','update_p'=>'0','delete_p'=>'0',
                                                                              'import_p'=>'0','export_p'=>'0'
                                                                             ),
                                                                        array('sid'=>$surveyid,'uid'=>$uid,'permission'=>'surveyactivation',
                                                                              'create_p'=>'0','read_p'=>'0','update_p'=>'1','delete_p'=>'0',
                                                                              'import_p'=>'0','export_p'=>'0'
                                                                             ),
                                                                        array('sid'=>$surveyid,'uid'=>$uid,'permission'=>'surveycontent',
                                                                              'create_p'=>'1','read_p'=>'1','update_p'=>'1','delete_p'=>'1',
                                                                              'import_p'=>'1','export_p'=>'1'
                                                                             ),
                                                                        array('sid'=>$surveyid,'uid'=>$uid,'permission'=>'survey',
                                                                              'create_p'=>'0','read_p'=>'1','update_p'=>'0','delete_p'=>'1',
                                                                              'import_p'=>'0','export_p'=>'0'
                                                                             ),
                                                                        array('sid'=>$surveyid,'uid'=>$uid,'permission'=>'surveylocale',
                                                                              'create_p'=>'0','read_p'=>'1','update_p'=>'1','delete_p'=>'0',
                                                                              'import_p'=>'0','export_p'=>'0'
                                                                             ),
                                                                        array('sid'=>$surveyid,'uid'=>$uid,'permission'=>'surveysecurity',
                                                                              'create_p'=>'1','read_p'=>'1','update_p'=>'1','delete_p'=>'1',
                                                                              'import_p'=>'0','export_p'=>'0'
                                                                             ),
                                                                        array('sid'=>$surveyid,'uid'=>$uid,'permission'=>'surveysettings',
                                                                              'create_p'=>'0','read_p'=>'1','update_p'=>'1','delete_p'=>'0',
                                                                              'import_p'=>'0','export_p'=>'0'
                                                                             ),
                                                                        array('sid'=>$surveyid,'uid'=>$uid,'permission'=>'tokens',
                                                                              'create_p'=>'1','read_p'=>'1','update_p'=>'1','delete_p'=>'1',
                                                                              'import_p'=>'1','export_p'=>'1'
                                                                             ),
                                                                      )
                                                           ),
                                                'lime_groups'
                                                   => array('data'
                                                              => array(
                                                                        array('sid'=>$surveyid,'group_name'=>"$vicicompany Group $lastGroup",
                                                                              'description'=>"$vicicompany Group $lastGroup",'language'=>$postvars['lang'])
                                                                      )
                                                           ),
                                                'lime_questions'
                                                   => array('format_data'=>array("lime_groups_0"),
                                                            'data'
                                                               => array(
                                                                         array('parent_qid'=>'0','sid'=>$surveyid,'gid'=>"{lime_groups_0}",'type'=>'T',
                                                                               'title'=>'Q1','question'=>'Lead ID:','preg'=>'','help'=>'',
                                                                               'other'=>'N','mandatory'=>'N','question_order'=>'0','language'=>$postvars['lang'],
                                                                               'scale_id'=>'0','same_default'=>'0'),
                                                                         array('parent_qid'=>'0','sid'=>$surveyid,'gid'=>"{lime_groups_0}",'type'=>'T',
                                                                               'title'=>'Q2','question'=>'Firstname:','preg'=>'','help'=>'',
                                                                               'other'=>'N','mandatory'=>'N','question_order'=>'1','language'=>$postvars['lang'],
                                                                               'scale_id'=>'0','same_default'=>'0'),
                                                                         array('parent_qid'=>'0','sid'=>$surveyid,'gid'=>"{lime_groups_0}",'type'=>'T',
                                                                               'title'=>'Q3','question'=>'Lastname:','preg'=>'','help'=>'',
                                                                               'other'=>'N','mandatory'=>'N','question_order'=>'2','language'=>$postvars['lang'],
                                                                               'scale_id'=>'0','same_default'=>'0'),
                                                                         array('parent_qid'=>'0','sid'=>$surveyid,'gid'=>"{lime_groups_0}",'type'=>'T',
                                                                               'title'=>'Q4','question'=>'Phone Number:','preg'=>'','help'=>'',
                                                                               'other'=>'N','mandatory'=>'N','question_order'=>'3','language'=>$postvars['lang'],
                                                                               'scale_id'=>'0','same_default'=>'0'),
                                                                         array('parent_qid'=>'0','sid'=>$surveyid,'gid'=>"{lime_groups_0}",'type'=>'T',
                                                                               'title'=>'Q5','question'=>'Address:','preg'=>'','help'=>'',
                                                                               'other'=>'N','mandatory'=>'N','question_order'=>'4','language'=>$postvars['lang'],
                                                                               'scale_id'=>'0','same_default'=>'0')
                                                                       )
                                                           )
                                              );// end lime survey collected data
                   $script_text = '<iframe src="'.$this->config->item('base_url').'/limesurvey/index.php?sid='.$surveyid.'&lang='.$postvars['lang'].'&'.$surveyid.'X{lime_groups_0}X{lime_questions_0}=--A--lead_id--B--&'.$surveyid.'X{lime_groups_0}X{lime_questions_1}=--A--first_name--B--&'.$surveyid.'X{lime_groups_0}X{lime_questions_2}=--A--last_name--B--&'.$surveyid.'X{lime_groups_0}X{lime_questions_3}=--A--phone_number--B--&'.$surveyid.'X{lime_groups_0}X{lime_questions_4}=--A--address1--B--&lead_id=--A--lead_id--B--&first_name=--A--first_name--B--&last_name=--A--last_name--B--&phone_number=--A--phone_number--B--&address1=--A--address1--B--" style="background-color:transparent;" scrolling="auto"  frameborder="0" allowtransparency="true" id="popupFrame" name="popupFrame"  width="--A--script_width--B--" height="--A--script_height--B--" STYLE="z-index:17"></iframe>';
                   $data['vicidial'] = array('vicidial_scripts'
                                                => array('format_data'=>array("lime_groups_0","lime_questions_0","lime_questions_1","lime_questions_2","lime_questions_3","lime_questions_4"),
                                                         'data'
                                                            => array(
                                                                       array('script_id'=>$postvars['script_id'],'script_name'=>$postvars['script_name'],
                                                                             'script_text'=>$script_text,'active'=>'N','user_group'=>$viciuser)
                                                                    )
                                                        ),
                                              'go_scripts'
                                                 => array('data'
                                                            => array(
                                                                       array('account_num'=>$viciuser,'script_id'=>$postvars['script_id'],'campaign_id'=>$postvars['campaign_id'],'surveyid'=>$surveyid)
                                                                    )
                                                         ),
                                              'vicidial_campaigns'
                                                 => array('condition' => array("campaign_id"=>$postvars['campaign_id']),
                                                          'data'
                                                            => array(
                                                                       array('campaign_script'=>$postvars['script_id'])
                                                                    )
                                                         )
                                            );
                   // saving the script data
                   $result = $this->go_script->saveadvancescript($data);
                   if($result){
                       die("Success: New limesurvey created");
                   }else{
                       die("Error on saving data contact your support");
                   }
              }
          }else{
             die("Error: no data to process");
          }
    }


    /*
     * scriptid
     * function for creating script id element
     * @author: Franco E. Hora <info@goautodial.com>
     * @param : $scriptobj > object result from query
     */
    function scriptid($scriptobj,$type){

        $userlevel = $this->session->userdata('users_level');
        #if($userlevel > 8){
        #      $account_group = $_POST['accounts'];
        #}else{
              #$account_group = $this->session->userdata('user_group');
		if ($this->commonhelper->checkIfTenant($this->session->userdata('user_group')))
		{
		  $account_group = $this->session->userdata('user_group');
		} else {
			if($type=="default"){
				  $account_group = "script";
			}else{
				  $account_group = "lime";
			}
		}
        #}

        if(is_object($scriptobj)){
            if($scriptobj->num_rows > 0){
                  $lastScript = $scriptobj->result();
                  $readonly = ' readonly="readonly"';
                  if(!is_null($lastScript[0]->script_id)){
                     $lastdigits = substr($lastScript[0]->script_id,strlen($account_group));
                     #$lastdigits = substr($lastScript[0]->script_id, -1);
                     $lastdigits++;
                     //if($lastdigits < 1000){
                     //      $finaldigits = "0$lastdigits";
                     //}
                     //if($lastdigits < 100){
                           $finaldigits = "$lastdigits";
                     //}
                     //if($lastdigits < 10){
                       //    $finaldigits = "00$lastdigits";
                     //}
		
                     $scriptId_elem = form_input('script_id',((array_key_exists('script_id',$_POST))?$_POST['script_id']:substr($account_group,0).$finaldigits),'size="15" id="script_id"'.$readonly);
                  }
                  # begin override
                  $override = $this->commonhelper->simpleretrievedata('vicidial_override_ids',
                                                                      null,
                                                                      null,
                                                                      array(array('id_table'=>'vicidial_scripts'),array('active'=>'1'))
                                                                     );
                  if($override->num_rows > 0){
                          $scriptId_elem = "<span>Auto-Generated</span>";
                  } 
            }else{
                   $scriptId_elem = form_input('script_id',((array_key_exists('script_id',$_POST))?$_POST['script_id']:$account_group.'1'),'size="15" id="script_id" readonly');
            }

            return $scriptId_elem;
        } else {
            die('Error: passing not an object variable');
        }

    }

    /*
     * scriptadvance
     * function to process advance configuration
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function scriptadvance(){

        $this->commonhelper->checkpermission("modify_scripts");

        // check if $_POST has value
        if(!empty($_POST)){

           global $dbprefix, $connect, $clang, $databasetype, $databasetabletype, $uploaddir, $limedb, $link;

           $rootdir = $this->config->item('lime_path')."/limesurvey";
           require($rootdir.'/common_functions_ci.php');
           require($rootdir.'/admin/admin_functions_ci.php');
           require($rootdir.'/classes/core/sanitize.php');
           require($rootdir.'/classes/core/language.php');
           require($rootdir.'/admin/classes/core/sha256.php');
           $clang = new limesurvey_lang('en');
           require($rootdir.'/classes/core/surveytranslator_ci.php');
           include($rootdir.'/admin/activate_functions.php');
           require($rootdir.'/limesurvey_functions_ce.php');


           // get asterisk dbname
           $database = $this->go_script->asteriskDB->database;

           // vicidial_script table
           $data['fields'] =array('fullname'=>'Agent Name','vendor_lead_code'=>'vendor_lead_code','source_id'=>'source_id',
                               'list_id'=>'list_id','gmt_offset_now'=>'gmt_offset_now','called_since_last_reset'=>'called_since_last_reset',
                               'phone_code'=>'phone_code','phone_number'=>'phone_number','title'=>'title',
                               'first_name'=>'first_name','middle_initial'=>'middle_initial','last_name'=>'last_name',
                               'address1'=>'address1','address2'=>'address2','address3'=>'address3',
                               'city'=>'city','state'=>'state','province'=>'province','postal_code'=>'postal_code',
                               'country_code'=>'country_code','gender'=>'gender','date_of_birth'=>'date_of_birth',
                               'alt_phone'=>'alt_phone','email'=>'email','security_phrase'=>'security_phrase',
                               'comments'=>'comments','lead_id'=>'lead_id','campaign'=>'campaign',
                               'phone_login'=>'phone_login','group'=>'group','channel_group'=>'channel_group',
                               'SQLdate'=>'SQLdate','epoch'=>'epoch','uniqueid'=>'uniqueid',
                               'customer_zap_channel'=>'customer_zap_channel','server_ip'=>'server_ip','SIPexten'=>'SIPexten',
                               'session_id'=>'session_id','dialed_number'=>'dialed_number','dialed_label'=>'dialed_label',
                               'rank'=>'rank','owner'=>'owner','camp_script'=>'camp_script',
                               'in_script'=>'in_script','script_width'=>'script_width','script_height'=>'script_height',
                               'recording_filename'=>'recording_filename','recording_id'=>'recording_id','user_custom_one'=>'user_custom_one',
                               'user_custom_two'=>'user_custom_two','user_custom_three'=>'user_custom_three','user_custom_four'=>'user_custom_four',
                               'user_custom_five'=>'user_custom_five','preset_number_a'=>'preset_number_a','preset_number_b'=>'preset_number_b',
                               'preset_number_c'=>'preset_number_c','preset_number_d'=>'preset_number_d','preset_number_e'=>'preset_number_e',
                               'preset_number_f'=>'preset_number_f','preset_dtmf_a'=>'preset_dtmf_a','preset_dtmf_b'=>'preset_dtmf_b',
                               'did_id'=>'did_id','did_extension'=>'did_extension','did_pattern'=>'did_pattern',
                               'did_description'=>'did_description', 'closecallid'=>'closecallid','xfercallid'=>'xfercallid',
                               'agent_log_id'=>'agent_log_id','entry_list_id'=>'entry_list_id'
                              );
 
           $info = $this->commonhelper->simpleretrievedata('vicidial_scripts',
                                                           "script_id,script_name,script_comments,script_text,active",
                                                           null,
                                                           array(array('script_id'=>$_POST['script_id'])));
           $data['vicidial_script'] = $info->result();


 
           // get the data from limesurvey
           $this->go_script->limesurveyDB->select("surveyls_survey_id,surveyls_title,surveyls_description,ls.active,surveyls_welcometext,
                                                   surveyls_endtext,surveyls_language,surveyls_url,surveyls_urldescription,surveyls_dateformat,
                                                   admin,adminemail,format,showwelcome,navigationdelay,allowprev,allowjumps,nokeyboard,showprogress,
	                                           printanswers,publicstatistics,publicgraphs,autoredirect,showXquestions,showgroupinfo,showqnumcode,
	                                           ls.template,surveyls_numberformat,ls.sid,vs.script_name,vs.script_comments,vs.script_id");
           $this->go_script->limesurveyDB->from("lime_surveys_languagesettings as lsl");
           $this->go_script->limesurveyDB->join("lime_surveys as ls","lsl.surveyls_survey_id=ls.sid","left");
           $this->go_script->limesurveyDB->join("$database.go_scripts as gs","ls.sid=$database.gs.surveyid","right");
           $this->go_script->limesurveyDB->join("$database.vicidial_scripts as vs","gs.script_id=$database.vs.script_id","right");
           $this->go_script->limesurveyDB->where("gs.script_id = '".$_POST['script_id']."'");
           $data['script'] = $this->go_script->limesurveyDB->get()->result(); 

           //get the questions
           $this->go_script->limesurveyDB->select("qid,title,question,type,mandatory,question_order,language,help,preg");
           $this->go_script->limesurveyDB->where("sid",$data['script'][0]->sid);
           $this->go_script->limesurveyDB->order_by("question_order","asc");
           $data['questions'] = $this->go_script->limesurveyDB->get("lime_questions")->result();

           $data['script'][0]->surveyls_language = getLanguageNameFromCode($data['script'][0]->surveyls_language,false,$clang);
           foreach(getRadixPointData(-1,$clang) as $index => $val){

              $data['radixpoint'][$index] = $val['desc'];

           }

           $templaterootDir = $rootdir."/templates";
           $standardtemplate = $rootdir."/templates";
           $standardtemplaterootDir = $rootdir."/templates";
           foreach(gettemplatelist($templaterootDir,$standardtemplate,$standardtemplaterootDir) as $template => $templateval){

               $data['template'][$template] = $template;

           }

           $data['type'] = getqtypelist($data['questions'][0]->type,'group');
           $data['preview'] = sGetTemplateURL($data['script'][0]->template);

           $this->load->view('go_script/go_script_advance_ce',$data);
           


        } else {

           die ("Error: Passing empty data contact your support");

        }

    }

    /*
     * saveconfig
     * save configs data
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function saveconfig(){

        // session
        $this->commonhelper->checkpermission("modify_scripts");
        $rootdir = $this->config->item('lime_path');
        global $dbprefix, $connect, $clang, $databasetype, $databasetabletype, $uploaddir, $limedb, $link;
 
        $dbprefix = "lime_";
        $uploaddir = $this->config->item('lime_path')."/upload";
        $limedb = $this->go_script->limesurveyDB->database;
        $link = mysql_connect($this->go_script->limesurveyDB->hostname,$this->go_script->limesurveyDB->username,$this->go_script->limesurveyDB->password);

        include_once($rootdir.'/limesurvey/config.php');
        require_once($rootdir.'/classes/adodb/adodb.inc.php');
        $connect = ADONewConnection($databasetype);
        require_once($rootdir.'/common_functions_ci.php');
        require_once($rootdir.'/admin/admin_functions_ci.php');
        require_once($rootdir.'/classes/core/sanitize.php');
        require_once($rootdir.'/classes/core/language.php');
        require_once($rootdir.'/admin/classes/core/sha256.php');
        $clang = new limesurvey_lang('en');
        require_once($rootdir.'/classes/core/surveytranslator_ci.php');
        include_once($rootdir.'/admin/activate_functions.php');
        require_once($rootdir.'/limesurvey_functions_ce.php');

        if(!empty($_POST)){



            # collect rawdata
            switch($_POST['selected']){

                 case "settings":
                          unset($_POST['selected']);
                          $update_data['vicidial'] = array('vicidial_scripts'
                                                                => array ('data'
                                                                             => array ('script_name'=>$_POST['surveyls_title'],'active'=>$_POST['active']),
                                                                          'condition'
                                                                             => array ('script_id'=>$_POST['script_id'])
                                                                         )
                                                          );
                          $update_data['limesurvey']  = array('lime_surveys_languagesettings'
                                                                   => array('data'
                                                                              => array('surveyls_title'=>$_POST['surveyls_title'],'surveyls_description'=>$_POST['surveyls_description'],
                                                                                       'surveyls_welcometext'=>$_POST['welcom_message'],'surveyls_endtext'=>$_POST['end_message'],
                                                                                       'surveyls_url'=>$_POST['surveyls_url'],'surveyls_urldescription'=>$_POST['surveyls_urldescription'],
                                                                                       'surveyls_numberformat'=>$_POST['surveyls_numberformat']
                                                                                      ),
                                                                             'condition'
                                                                               => array('surveyls_survey_id'=>$_POST['sid'])
                                                                           ),
                                                               'lime_surveys'
                                                                    => array('data'
                                                                               => array('template'=>$_POST['template'],'admin'=>$_POST['admin'],'adminemail'=>$_POST['adminemail'],
                                                                                        'format'=>$_POST['format'],'showwelcome'=>$_POST['showwelcome'],
                                                                                        'navigationdelay'=>$_POST['navigationdelay'],'allowprev'=>$_POST['allowprev'],
                                                                                        'allowjumps'=>$_POST['allowjumps'],'nokeyboard'=>$_POST['nokeyboard'],  
                                                                                        'showprogress'=>$_POST['showprogress'],'printanswers'=>$_POST['printanswers'],
                                                                                        'publicstatistics'=>$_POST['publicstatistics'],'publicgraphs'=>$_POST['publicgraphs'],
                                                                                        'autoredirect'=>$_POST['autoredirect'],'showXquestions'=>$_POST['showXquestions'],
                                                                                        'showgroupinfo'=>$_POST['showgroupinfo'],'showqnumcode'=>$_POST['showqnumcode'],
                                                                                        'active' => $_POST['active']
                                                                                       ),
                                                                             'condition'
                                                                               => array('sid'=>$_POST['sid'])
                                                                            )
                                                             );
                 break;

                 case 'modify':
                      
                         if(!empty($_POST['qid'])){

                              $update_data['limesurvey'] = array('lime_questions'
                                                                    => array('data'
                                                                               => array('title'=>$_POST['title'],'question'=>$_POST['question'],
                                                                                        'help'=>$_POST['help'],'type'=>$_POST['type'],
                                                                                        'mandatory'=>$_POST['mandatory'],'preg'=>$_POST['preg']
                                                                                       ),
                                                                             'condition'
                                                                               => array('qid'=>$_POST['qid'])
                                                                            )
                                                                );

                         } else {

                              die("Error: Please click your question");

                         }

                 break;

                 case "quick":
                         $update_data['limesurvey'] = array('lime_surveys'
                                                                 => array('data'
                                                                             => array('active'=>$_POST['active']),
                                                                          'condition'
                                                                             => array('sid'=>$_POST['sid'])
                                                                         )
                                                           );
                 break;

                 case "add":

                        $this->go_script->limesurveyDB->select('question_order');
                        $this->go_script->limesurveyDB->where(array('sid'=>$_POST['sid']));
                        $this->go_script->limesurveyDB->order_by("question_order","desc");
                        $this->go_script->limesurveyDB->limit(1);
                        $last_question = $this->go_script->limesurveyDB->get('lime_questions')->result(); 

                        if(!empty($last_question)){

                           $order_question = $last_question[0]->question_order + 1;

                        } else {

                           $order_question = 1; 

                        }

                        $this->go_script->limesurveyDB->select('gid,language');
                        $this->go_script->limesurveyDB->where(array('sid'=>$_POST['sid']));
                        $group = $this->go_script->limesurveyDB->get('lime_groups')->result();
                        if(!empty($group)){

                             $gid = $group[0]->gid;
 
                        } else {

                             die("Error: You have no group in limesurvey contact your support please");

                        }

                        $rawdata = array('sid'=>$_POST['sid'],'gid'=>$gid,
                                         'title'=>$_POST['title'],'question'=>$_POST['question'],
                                         'help'=>$_POST['help'],'type'=>$_POST['type'],
                                         'mandatory'=>$_POST['mandatory'],'preg'=>$_POST['preg'],
                                         'language'=>$group[0]->language,'question_order'=>$order_question); 
                        $result = $this->go_script->insertTolimesurvey($rawdata,'lime_questions',$newId);

                        if($result){

                             # kill process here nothing to do after inserting data
                             if($_POST['selected'] != "add"){
                                 die("Success : Added new question");
                             }else{
                                 die(json_encode(array("msg"=>"Success : Added new question","qid"=>$newId)));
                             }

                        } else {
         
                             die("Error : Something went wrong while saving new question");

                        }


                 break;

            }


            // process the saving here call
            foreach($update_data as $dbase => $tables){
                 foreach($tables as $table_name => $rawdata ){
                     if($table_name == "lime_surveys"){

                          $this->go_script->limesurveyDB->trans_start();

                              $this->go_script->limesurveyDB->where('sid',$_POST['sid']); 
                              $status = $this->go_script->limesurveyDB->get('lime_surveys')->result();
                              if(count($status) > 0){

                                  if($_POST['active'] == "Y") {
 
                                        if ($status[0]->active == "N") {

                                            $table_status = $this->go_script->limesurveyDB->query("SHOW table status WHERE name = '$table_name'")->result(); 
                                            $databasetabletype = $table_status[0]->Engine;
                                            $activatestatus = GoactivateSurvey($_POST['sid'],$_POST['sid']); 

                                        }

                                  } else {

                                       if($status[0]->active == "Y"){

                                            $this->go_script->limesurveyDB->where(array('sid'=>$_POST['sid']));
                                            $deleted = $this->go_script->limesurveyDB->delete('lime_saved_control'); 

                                            $old = $dbprefix."survey_".$_POST['sid'];
                                            $new = $dbprefix."old_survey_".$_POST['sid']."_";
 
                                            #$config_old = $this->go_script->limesurveyDB->get($old)->result(); # this is escaped part from dev
                                            /*while ($row=mysql_fetch_assoc($result))
				            { 
					         if (strlen($row['id']) > 12) //Handle very large autonumbers (like those using IP prefixes)
					         {
						     $part1=substr($row['id'], 0, 12);
						     $part2len=strlen($row['id'])-12;
						     $part2=sprintf("%0{$part2len}d", substr($row['id'], 12, strlen($row['id'])-12)+1);
						     $new_autonumber_start="{$part1}{$part2}";
					         }
					         else
					         {
						     $new_autonumber_start=$row['id']+1;
					         }
				           }*/

                                           # update autonumber_start in lime_surveys
                                           # add this to the rawdata
                                           $rawdata['data']['autonumber_start'] = 0;
 
                                           # rename of table
                                           $rename_script = GOdb_rename_table($old,$new);
                                           $this->go_script->limesurveyDB->query($rename_script);
 

                                       }

                                  }

                              } 
                          $this->go_script->limesurveyDB->trans_complete();
                     }
                     $this->go_script->updatetable($dbase,$table_name,$rawdata);
                 }
            }

            echo "Success: Survey successfully modified" ;

        } else {

            die("Error: Empty raw data in saving limesurvey configs");

        }

    }



    function downloadscript(){
      $surveyid = $this->uri->segment(3);
      if(!empty($surveyid)){
           $result = $this->go_script->get_survey_report($surveyid);
           if($result->num_rows() > 0){
               $resulta = $result->result();
               $fields = array_keys(get_object_vars($resulta[0]));
               $ctr = 0;
               foreach($fields as $field){
                   if(!in_array($field,array('id','submitdate','lastpage','startlanguage','token'))){
                       $fields[$ctr] = $this->go_script->get_question($field);
                   }
                   $ctr++;
               }
               $fields = implode(',',$fields);
               $data['url'] = "{$surveyid}_report.csv";
               $data['csv'] = $fields."\n";
               foreach($result->result() as $row){
                   $data['csv'] .= implode(',',array_values(get_object_vars($row)))."\n";
               }
           }else{
               $data['url'] = "{$surveyid}_report.csv";
               $data['csv'] = $fields."\n";
           }
           $this->load->view('go_search/download',$data);
      }  
    }

     function CheckActionIfAllowed(){

          $actionType = $this->uri->segment(3);
          $permissions = $this->commonhelper->getPermissions("script",$this->session->userdata("user_group"));

          switch(strtolower($actionType)){
               case "create":
               case "update":
               case "delete":
                     $action = "script_".strtolower($actionType);
               break;
               default: 
                     die("Error: Unknown action");
               break;
          }

          if($permissions->$action == "N"){
             die("Error: You don't have permission to $actionType this record(s)");
          }

     }

        function is_logged_in()
        {
                $is_logged_in = $this->session->userdata('is_logged_in');
                if(!isset($is_logged_in) || $is_logged_in != true)
                {
                        $base = base_url();
                        echo "<script>javascript: window.location = 'https://".$_SERVER['HTTP_HOST']."/login'</script>";
//                      echo "<script>javascript: window.location = '$base'</script>";
                        #echo 'You don\'t have permission to access this page. <a href="../go_index">Login</a>';
                        die();
                        #$this->load->view('go_login_form');
                }
        }

}
?>
