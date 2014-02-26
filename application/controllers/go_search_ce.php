<?php
########################################################################################################
####  Name:             	go_search_ce.php                                                 `  ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Originated by:       	Rodolfo Januarius T. Manipol - Unified Search Functions		    ####
####  Written/Adopted by:	Franco Hora							    ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_search_ce extends Controller{
    function __construct(){
        parent::Controller();
        $this->load->model(array('gosearch','go_auth'));
        $this->load->library(array('session','userhelper','commonhelper'));
        $this->load->helper(array('html','url'));
	$this->is_logged_in();
    }

    /*
     * index
     * what you will see ing ui
     * gather all data ng most process will be done here
     * author : Franco E. Hora <info@goautodial.com>
     */
    function index(){

        # check if segment 3 has value
        # if so you are in portal thanks
        $account = $this->checkstate();

        if(!is_null($account)){
            $account = $account;
        }else{
            $account = null;
        }

        if(empty($_POST['type'])){
            $display = "go_search";
        } else {
            #$display = "go_search_result";
            $display = "go_search";
        }
        $users = $this->gosearch->collectusers($account);
        #$usergroups = $this->gosearch->getallusergroup($account);

        $data['user_level'] = $this->session->userdata('users_level');
        $data['go_main_content'] = 'go_dashboard';
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

	$data['theme'] = $this->session->userdata('go_theme');
		
	
	$data['rec']= 'wp-has-current-submenu';
	$data['folded'] = 'folded';
	$data['foldlink'] = '';
	$togglestatus = "1";
	$data['togglestatus'] = $togglestatus;		
	
	
        $data['users'] = $users;
        $data['usergroups'] = $usergroups;
        $data['account'] = $account;
        $data['gowizard'] = "gowizard";

        $this->load->view("go_search/$display",$data);

    }


    function download(){

        $id = $this->uri->segment(3);
        $type = $this->uri->segment(4);
        $rec = $this->uri->segment(5);
        if(!empty($id)){

            if($type=="rec"){
               $this->gosearch->asteriskDB->where(array("lead_id"=>$id,"recording_id"=>$rec));
               $result  = $this->gosearch->asteriskDB->get("goautodial_recordings_view_all")->result();
     
               if(!empty($result) && $result != false){
                   $data['filename'] = "{$result[0]->filename}.mp3";
                   $data['url'] = $result[0]->location;
               }
               $this->load->view('go_search/new_download.php',$data);
               exit;

            } else {

               $this->gosearch->asteriskDB->where(array("lead_id"=>$id));
               $result = $this->gosearch->asteriskDB->get("vicidial_list")->result();

               if(!empty($result) && $result != false){
                   $data['filename'] = "{$result[0]->lead_id}.csv";
                   $data["csv"] = "Lead Id,List Id,status,Fullname,Address,City,State,Zip Code,Comments\n";
                   $data["csv"] .= "{$result[0]->lead_id},{$result[0]->list_id},{$result[0]->status},{$result[0]->first_name} {$result[0]->last_name},{$result[0]->address1},{$result[0]->city},{$result[0]->state},{$result[0]->postal_code},".preg_replace("/,/"," ",$result[0]->comments);
               }

            }

            $this->load->view('go_search/new_download.php',$data);

        } else {

            die("Error : Kindly check your url");

        }

    }


    /*
     * leadinfo
     * function to display lead
     * author: Franco E. Hora <info@goautodial.com>
     */
    function leadinfo(){
         if(!empty($_POST)){

              $this->gosearch->asteriskDB->where(array('lead_id'=>$_POST['leadid']));
              $result = $this->gosearch->asteriskDB->get("vicidial_list")->result();
              $final[0] = $result[0];

              $this->gosearch->asteriskDB->where(array('lead_id'=>$_POST['leadid']));
              $this->gosearch->asteriskDB->where_in('status',array('ACTIVE','LIVE'));
              $this->gosearch->asteriskDB->limit(1);
              $this->gosearch->asteriskDB->order_by("callback_id","desc");
              $result = $this->gosearch->asteriskDB->get("vicidial_callbacks")->result();
              $final[1] = $result[0];
 
              echo json_encode($final);

         } else {
            die("Error: Empty search");
         }
    }

    function calls(){
         $leadid = $this->uri->segment(3);
         if(!empty($leadid)){
            $this->gosearch->asteriskDB->select("uniqueid,lead_id,list_id,campaign_id,call_date,start_epoch,end_epoch,length_in_sec,status,phone_code,phone_number,user,comments,processed,user_group,term_reason,alt_dial");
            $this->gosearch->asteriskDB->where(array('lead_id'=>$leadid));
            $calls = $this->gosearch->asteriskDB->get('vicidial_log')->result();
            if(!empty($calls)){
               $ctr=0;
               foreach($calls as $call){
                  echo "<div class='user-tbl-rows ".(($ctr%2)==0?"user-even":"user-odd")."'>";
                      echo "<div class='user-tbl-cols'>{$call->call_date}</div>";
                      echo "<div class='user-tbl-cols user-tbl-cols-centered'>{$call->length_in_sec}</div>";
                      echo "<div class='user-tbl-cols'>{$call->status}</div>";
                      echo "<div class='user-tbl-cols'>{$call->user}</div>";
                      echo "<div class='user-tbl-cols'>{$call->campaign_id}</div>";
                      echo "<div class='user-tbl-cols'>{$call->list_id}</div>";
                      echo "<div class='user-tbl-cols'>{$call->lead_id}</div>";
                      echo "<div class='user-tbl-cols'>{$call->term_reason}</div>";
                      echo "<div class='user-tbl-cols'>{$call->phone_number}</div>";
                      echo "<br class='clear'/>";
                  echo "</div>";
                  $ctr++;
               }
            }
         }
    }

    function closerlog(){
         $leadid = $this->uri->segment(3);
         if(!empty($leadid)){
              $this->gosearch->asteriskDB->select("closecallid,lead_id,list_id,campaign_id,call_date,start_epoch,end_epoch,length_in_sec,status,phone_code,phone_number,user,comments,processed,queue_seconds,user_group,xfercallid,term_reason,uniqueid,agent_only");
              $this->gosearch->asteriskDB->where(array('lead_id'=>$leadid));
              $closerlogs = $this->gosearch->asteriskDB->get('vicidial_closer_log')->result();
              if(!empty($closerlogs)){
                  $ctr=0;
                  foreach($closerlogs as $closerlog){
                  echo "<div class='user-tbl-rows ".(($ctr%2)==0?'user-even':'user-odd')."'>";
                      echo "<div class='user-tbl-cols'>{$closerlog->call_date}</div>";
                      echo "<div class='user-tbl-cols user-tbl-cols-centered'>{$closerlog->length_in_sec}</div>";
                      echo "<div class='user-tbl-cols'>{$closerlog->status}</div>";
                      echo "<div class='user-tbl-cols'>{$closerlog->user}</div>";
                      echo "<div class='user-tbl-cols'>{$closerlog->campaign_id}</div>";
                      echo "<div class='user-tbl-cols'>{$closerlog->list_id}</div>";
                      echo "<div class='user-tbl-cols'>{$closerlog->lead_id}</div>";
                      echo "<div class='user-tbl-cols'>{$closerlog->term_reason}</div>";
                      echo "<div class='user-tbl-cols'>{$closerlog->phone_number}</div>";
                      echo "<br class='clear'/>";
                  echo "</div>";
                  $ctr++;
                  }
              }
         }
    }

    function agentlog(){
         $leadid = $this->uri->segment(3);
         if(!empty($leadid)){
              $this->gosearch->asteriskDB->select("agent_log_id,user,server_ip,event_time,lead_id,campaign_id,pause_epoch,pause_sec,wait_epoch,wait_sec,talk_epoch,talk_sec,dispo_epoch,dispo_sec,status,user_group,comments,sub_status");
              $this->gosearch->asteriskDB->where(array('lead_id'=>$leadid));
              $agentlogs = $this->gosearch->asteriskDB->get('vicidial_agent_log')->result();
              if(!empty($agentlogs)){
                  $ctr=0;
                  foreach($agentlogs as $agentlog){
                  echo "<div class='user-tbl-rows ".(($ctr%2)==0?'user-even':'user-odd')."'>";
                      echo "<div class='user-tbl-cols' style='width:18%;'>{$agentlog->event_time}</div>";
                      echo "<div class='user-tbl-cols user-normalcols '>{$agentlog->campaign_id}</div>";
                      echo "<div class='user-tbl-cols user-normalcols'>{$agentlog->agent_log_id}</div>";
                      echo "<div class='user-tbl-cols user-smallcols'>{$agentlog->pause_sec}</div>";
                      echo "<div class='user-tbl-cols user-smallcols'>{$agentlog->wait_sec}</div>";
                      echo "<div class='user-tbl-cols user-smallcols'>{$agentlog->talk_sec}</div>";
                      echo "<div class='user-tbl-cols user-smallcols'>{$agentlog->dispo_sec}</div>";
                      echo "<div class='user-tbl-cols user-smallcols'>{$agentlog->status}</div>";
                      echo "<div class='user-tbl-cols'>{$agentlog->user_group}</div>";
                      echo "<div class='user-tbl-cols'>{$agentlog->sub_status}</div>";
                      echo "<br class='clear'/>";
                  echo "</div>";
                  $ctr++;
                  }
              }
         }
    }

    function leadrecord(){
         $leadid = $this->uri->segment(3);
         if(!empty($leadid)){
              $this->gosearch->asteriskDB->select("recording_id,channel,server_ip,extension,start_time,start_epoch,end_time,end_epoch,length_in_sec,length_in_min,filename,location,lead_id,user,vicidial_id");
              $this->gosearch->asteriskDB->where(array('lead_id'=>$leadid));
              $recordlogs = $this->gosearch->asteriskDB->get('recording_log')->result();
              #var_dump($recordlogs);die;
              if(!empty($recordlogs)){
                  $ctr=0;
                  foreach($recordlogs as $recordlog){
                  echo "<div class='user-tbl-rows ".(($ctr%2)==0?'user-even':'user-odd')."'>";
                      echo "<div class='user-tbl-cols'>{$recordlog->lead_id}</div>";
                      echo "<div class='user-tbl-cols'>{$recordlog->start_time}</div>";
                      echo "<div class='user-tbl-cols user-tbl-cols-centered'>{$recordlog->length_in_sec}</div>";
                      echo "<div class='user-tbl-cols'>{$recordlog->recording_id}</div>";
                      echo "<div class='user-tbl-cols'><span id='filename' class='toolTip' title='{$recordlog->filename}' onmouseover='tip();'>".substr($recordlog->filename,0,15)."...</span></div>";
                      echo "<div class='user-tbl-cols'><a href='{$recordlog->location}' class='toolTip' title='$recordlog->location'>".substr($recordlog->location,0,15)."...</a></div>";
                      echo "<div class='user-tbl-cols'>{$recordlog->user}</div>";
                      echo "<br class='clear'/>";
                  echo "</div>";
                  $ctr++;
                  }
              }
         }
    }


    function leadaction(){

        #$this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
        $username = $this->session->userdata("user_name");
        if(empty($username)){
           die("Error: Session expired please re-login");
        }

        if(!empty($_POST)){
    
            $escape = array('modify_log','modify_agent_logs','modify_closer_logs','add_closer_record');
            $vl_cols = array();
            foreach($_POST as $key => $val){
               if(in_array($key,$escape) == false){
                   $vl_cols[$key]=$val;
               }
            }

            # check if status is a scheduled callback
            $this->gosearch->asteriskDB->select('scheduled_callback');
            $this->gosearch->asteriskDB->where(array('status'=>$_POST['status']));
            $callbacksched = $this->gosearch->asteriskDB->get('vicidial_statuses')->result();
            if(!empty($callbacksched)){
                $callback = $callbacksched[0]->scheduled_callback;
            }
       
            $this->gosearch->asteriskDB->select('scheduled_callback');
            $this->gosearch->asteriskDB->where(array('status'=>$_POST['status']));
            $callbacksched = $this->gosearch->asteriskDB->get('vicidial_campaign_statuses')->result();
            if(!empty($callbacksched)){
                $callback = $callbacksched[0]->scheduled_callback;
            }

            $this->gosearch->asteriskDB->where(array('lead_id'=>$_POST['lead_id']));
            $result = $this->gosearch->asteriskDB->get('vicidial_list')->result();

            if(($result[0]->status != $_POST['status']) && ($result[0]->status == "CBHOLD") ){
                 $this->gosearch->asteriskDB->where(array('lead_id'=>$_POST['lead_id']));
                 $this->gosearch->asteriskDB->where(array('status'=>"ACTIVE"));
                 $this->gosearch->asteriskDB->update('vicidial_callbacks',array('status'=>'INACTIVE'));
    
                 $this->gosearch->asteriskDB->select("callback_id");
                 $this->gosearch->asteriskDB->where(array('lead_id'=>$_POST['lead_id']));
                 $this->gosearch->asteriskDB->where_in("status",array("ACTIVE","LIVE"));
                 $callback_ids = $this->gosearch->asteriskDB->get("vicidial_callbacks")->result();
                 if(!empty($callback_ids)){
                     $callback_id = $callback_ids[0]->callback_id;
                 } else {
                     $tomorrow = date("Y-m-d", mktime(date("H"),date("i"),date("s"),date("m"),date("d")+1,date("Y")));
                     $insertThis = array('recipient'=>'ANYONE','status'=>'ACTIVE',
                                         'user'=>$this->session->userdata('user_name'),
                                         'user_group'=>$this->session->userdata('user_name'),
                                         'list_id'=>$_POST['list_id'],'callback_time'=>"$tomorrow 12:00:00",
                                         'entry_time'=>date("Y-m-d H:i:s"),"comments"=>"",
                                         'campaign_id'=>$result[0]->campaign_id);
                     $this->gosearch->asteriskDB->insert('vicidial_callbacks',$insertThis);
                 }
            }

            if(($result[0]->status != $_POST['status']) && ($result[0]->status == "CALLBK" || $callback == "Y") ){
                $this->gosearch->asteriskDB->where(array('lead_id'=>$_POST['lead_id']));
                $this->gosearch->asteriskDB->where_in("status",array("ACTIVE","LIVE"));
                $this->gosearch->asteriskDB->update('vicidial_callbacks',array('status'=>'INACTIVE'));
            }

            if(($result[0]->status != $_POST['status']) && $_POST['status'] == 'DNC'){
                $this->gosearch->asteriskDB->insert('vicidial_dnc',array('phone_number'=>$_POST['phone_number'])); 
            }

            if(($result[0]->status != $_POST['status']) && array_key_exists('modify_log',$_POST)){
                 $this->gosearch->asteriskDB->where(array('lead_id'=>$_POST['lead_id']));
                 $this->gosearch->asteriskDB->order_by('call_date','desc');
                 $this->gosearch->asteriskDB->limit(1);
                 $this->gosearch->asteriskDB->update('vicidial_log',array('status'=>$_POST['status']));
            }

            if(($result[0]->status != $_POST['status']) && array_key_exists("modify_closer_logs",$_POST)){
                $this->gosearch->asteriskDB->where(array('lead_id'=>$_POST['lead_id']));
                $this->gosearch->asteriskDB->order_by('call_date','desc');
                $this->gosearch->asteriskDB->limit(1);
                $this->gosearch->asteriskDB->update('vicidial_closer_log',array('status'=>$_POST['status']));
            }


            if(($result[0]->status != $_POST['status']) && array_key_exists("modify_agent_logs",$_POST)){
                $this->gosearch->asteriskDB->where(array('lead_id'=>$_POST['lead_id']));
                $this->gosearch->asteriskDB->order_by('agent_log_id','desc');
                $this->gosearch->asteriskDB->limit(1);
                $this->gosearch->asteriskDB->update('vicidial_agent_log',array('status'=>$_POST['status']));
            }

            if(array_key_exists("add_closer_record",$_POST)){
                $insertval = array('lead_id'=>$_POST['lead_id'],
                                   'list_id'=>$_POST['list_id'],
                                   'campaign_id'=>$result[0]->campaign_id,
                                   'call_date'=>$result[0]->parked_time,
                                   'start_epoch'=>date("Y-m-d H:i:s"),'end_epoch'=>date('U'),
                                   'length_in_sec'=>1,
                                   'status'=>$_POST['status'],'phone_code'=>$_POST['phone_code'],
                                   'phone_number'=>$_POST['phone_number'],
                                   'user'=>$this->session->userdata('user_name'),
                                   'processed'=>"Y","comments"=>$_POST['comments']);
                $this->gosearch->asteriskDB->insert('vicidial_closer_log',$insertval); 
            }

            $this->gosearch->asteriskDB->where(array('lead_id'=>$_POST['lead_id'])); 
            $this->gosearch->asteriskDB->update('vicidial_list',$vl_cols); 

            echo "Success: Update success!";
 
        } else {

             die("Error: Empty post data");

        }

    }

    ###### callbacks 
    function toanyone(){
        if(!empty($_POST)){
           $vals['user'] = $_POST['user'];
           $vals['recipient'] = "USERONLY";
           $this->gosearch->asteriskDB->where(array('callback_id'=>$_POST['callbackid']));
           $this->gosearch->asteriskDB->update('vicidial_callbacks',$vals);
           echo "Success: Update success";
        }else{
           echo "Error: Empty post data";
        }
    }

    function usertouser(){
        if(!empty($_POST)){
           $vals['user'] = $_POST['user'];
           $this->gosearch->asteriskDB->where(array('callback_id'=>$_POST['callbackid']));
           $this->gosearch->asteriskDB->update('vicidial_callbacks',$vals);
           echo "Success: Update success";
        }else{
           echo "Error: Empty post data";
        }
    }

    function usertoany(){
       if(!empty($_POST)){
           $vals['recipient'] = "ANYONE";
           $this->gosearch->asteriskDB->where(array('callback_id'=>$_POST['callbackid']));
           $this->gosearch->asteriskDB->update('vicidial_callbacks',$vals);
           echo "Success: Update success";
       }else{
          echo "Error: Empty post data";
       }
    }
   
    function changedate(){
       if(!empty($_POST)){
           $vals['callback_time'] = $_POST['day'];
           $vals['comments'] = $_POST['comments'];
           $this->gosearch->asteriskDB->where(array('callback_id'=>$_POST['callbackid']));
           $this->gosearch->asteriskDB->update('vicidial_callbacks',$vals);
           echo "Success: Update success";
       }else{
          echo "Error: Empty post data";
       }
    } 

    ######################### search
    /*
     * adduser
     * function to MANUALY save new user
     * author : Franco E. Hora <info@goautodial.com>
     */
    function adduser(){
 
        $data = $this->userhelper->postToarray($_POST); 
        $result = $this->gosearch->insertuser('vicidial_users',$data);
    }

    /*
     * getaccountinfo
     * function to getaccount to display
     * author : Franco E. Hora <info@goautodial.com>
     */
    function getaccountinfo(){
        $account = $this->uri->segment(3);
        $getaccountinfo = $this->gosearch->getallusergroup($account);
        echo json_encode($getaccountinfo);
    }


    /*
     * initwizard
     * display init userwizard
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function resetwizard(){
           
           $userlevel = $this->session->userdata('users_level');
           if(empty($userlevel)){
                die("Error: Session expired please re-login");
           }

           if($userlevel > 6){
              //$accounts = $this->gosearch->retrievedata('a2billing_wizard');
              if(!empty($accounts)){
                  foreach($accounts as $account){
                     $accnt[$account->account_num] = $account->company;
                  }
              }else{
                  $accnt = array();
              }
           }
 
           $display =  form_open(null,"id='add-new-user'");
                  $display .= "<div class='boxleftside'>Account</div>";
                  if($userlevel < 9){
                      $display .= "<div class='boxrightside'><span>".$_POST[0]['accountNum']."</span></div>";
                  } else {
                      $display .= "<div class='boxrightside'><span>".form_dropdown('accountNum',$accnt,null,"id='accountNum' onchange='accountinfo()'")."</span></div>";
                  }
                  $display .= "<div class='boxleftside'>Company Name</div>";
                  if($userlevel < 9){
                      $display .= "<div class='boxrightside'><span>".$_POST[0]['hidcompany']."</span>".form_hidden('hidcompany',$_POST[0]['hidcompany'])."</div>";
                  }else{
                      $display .= "<div class='boxrightside'><span id='comp_name'>&nbsp;</span>".form_hidden('hidcompany',$_POST[0]['hidcompany'])."</div>";
                  }
                  $display .= "<div class='boxleftside'>Current Seats</div>";
                  if($userlevel < 9){
                      $display .= "<div class='boxrightside'><span>".$_POST[0]['hidcount']."</pan>".
                                              form_hidden(array('hidcount'=>$_POST[0]['hidcount'],'accountNum'=>$_POST[0]['accountNum']))."</div>";
                  }else{
                      $display .= "<div class='boxrightside'><span id='count'>&nbsp;</pan>".
                                              form_hidden(array('hidcount'=>$_POST[0]['hidcount']))."</div>";
                  }
                  $display .= "<div class='boxleftside'>Additional Seats</div>";
                  $display .= "<div class='boxrightside'>".form_dropdown('txtSeats',array(1=>1,2=>2,3=>3),1).
                                            form_hidden('skip','skip')."</div>";
           $display .= form_close();
           $wizardDisplay['display'] = array('breadcrumb'=>'../../../img/step1of2-navigation-small.png',
                                             'content'=>array('left'=>'../../../img/step1-trans.png',
                                                              'right'=>$display),
                                             'action'=>' <a onclick="next(this)">Config</a>');
           echo json_encode($wizardDisplay);
    }



    /*
     * userwizard
     * display wizard layouts
     * @author: Franco E. Hora <info@goautodial.com> 
     */
    function userwizard(){

         $username = $this->session->userdata('user_name');
         $userslevel = $this->session->userdata('users_level');
         if(empty($username)){
             die("Error: Session expired kindly re-login"); 
         }

         if(!empty($_POST)){

               # get data from a2billing_wizard for updates
               //$a2bwizardresult = $this->gosearch->asteriskDB->get_where('a2billing_wizard',array('account_num'=>$_POST['accountNum']));
               //$wizardrow = $a2bwizardresult->result();

               # total of the agents       
               $totalagent = $_POST['hidcount'] + $_POST['txtSeats']; # total agent

               if($userslevel < 9){
                  # meaning you are in client mode
                  if($totalagent >= 50){
                      die("Error: Only 50 agents are allowed.\n Please contact our Support Team to add more agents \n Thanks");;
                  }
               }

               #var_dump($wizardrow);die("Error");
               #$campaigns = $this->commonhelper->getallowablecampaign($account_group,true,array('campaign_name NOT LIKE "%Survey%"'));
               $display =  form_open(null,"id='wizard-config'");
                              $user_level = $this->session->userdata('users_level');
                              if($user_level < 9){
                                  $readonly = "readonly='readonly'" ;
                              }else{
                                  $readonly = "";
                              }

                              $lastnum = $wizardrow[0]->lastnum;
                              for($i=1;$i<=$_POST['txtSeats'];$i++){
                                   $lastnum ++;
                                   if($lastnum < 10){
                                       $lastDigit = "00$lastnum";
                                   }elseif($lastnum < 100){
                                       $lastDigit = "0$lastnum";
                                   }else{
                                       $lastDigit = $lastnum; 
                                   }
                                   if($i > 1){
                                      $display .= "<br class='clear'/><div class='wizard-separator'>&nbsp;</div><br class='clear'/>";
                                   }
                                   $pass = $this->genpassword();
                                   $display .= "<div class='boxleftside'>Agent Number</div>";
                                   $display .= "<div class='boxrightside'>".$_POST['accountNum'].form_hidden("group$i-accountNum",$_POST['accountNum'])."</div>";
                                   $display .= "<div class='boxleftside'>Password</div>";
                                   $display .= "<div class='boxrightside'>".form_input("group$i-pass",$pass,"id='group$i-pass'")."</div>";
                                   $display .= "<div class='boxleftside'>Full Name</div>";
                                   $display .= "<div class='boxrightside'>".form_input("group$i-full_name",$_POST['hidcompany']." Agent $lastDigit ","id='group$i-full_name'")."</div>";
                                   $display .= "<div class='boxleftside'>Phone Login</div>";
                                   $display .= "<div class='boxrightside'>".form_input("group$i-phone_login",substr($_POST['accountNum'],0,6)."$lastDigit"," $readonly")."</div>";
                                   $display .= "<div class='boxleftside'>Phone Pass</div>";
                                   $display .= "<div class='boxrightside'>".form_input("group$i-phone_pass",$pass," $readonly")."</div>";
                                   $display .= "<div class='boxleftside'>Active</div>";
                                   $display .= "<div class='boxrightside'>".form_dropdown("group$i-active",array('Y'=>'Yes','N'=>'No'),"id='group$i-active'")."</div><br class='clear'/>";
                              }
               $display .= form_close();

               $wizardDisplay['prev_step'] = $_POST;
               $wizardDisplay['display'] = array('breadcrumb'=>'../../../img/step2of2-navigation-small.png',
                                                 'content'=>array('left'=>'../../../img/step2-trans.png',
                                                                  'right'=>$display),
                                                 'action'=>'<a onclick="resetwizard()">Back</a> | <a onclick="autogen()">Save</a>');
               echo json_encode($wizardDisplay);
         }else{
              echo "Error: empty data";
         }
    }


    /*
     * autogenuser
     * automatic create user
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function autogenuser(){
    
        $username = $this->session->userdata('user_name');
        $userlevel = $this->session->userdata('users_level');
        if(empty($username)){
            die("Error: Session expired please relogin");
        }
 
        $useraccess = unserialize($this->session->userdata('useraccess'));
        if(!in_array('modify_users',$useraccess)){
              die("Error: You are not allowed to create New User");
        }


        # collect server info
        $this->gosearch->asteriskDB->from('servers');
        $this->gosearch->asteriskDB->like(array('server_description'=>'MAIN DIALER'));
        $serverdata = $this->gosearch->asteriskDB->get();
        if($serverdata->num_rows > 0){
             $serverinfo = $serverdata->result();
             $server_ip = $serverinfo[0]->server_ip;
        } else {
             die("Error: Empty server information");
        }



        if(array_key_exists("skip",$_POST)){
             # convert $_POST data to an array variable
             $submiteddata = $this->commonhelper->postToarray($_POST);

             # get data from a2billing_wizard for updates
             //$a2bwizardresult = $this->gosearch->asteriskDB->get_where('a2billing_wizard',array('account_num'=>$submiteddata['accountNum']));
             //if($a2bwizardresult->num_rows() > 0){
             //    $wizardrow = $a2bwizardresult->result();
             //    $lastnum = $wizardrow[0]->lastnum;
             //}else{
                 $lastnum = 0;
             //}

             # total of the agents       
             $totalagent = $submiteddata['hidcount'] + $submiteddata['txtSeats']; # total agent

             if($userslevel < 9){
                  # meaning you are in client mode
                  $processThis = 'if('.$totalagent.'<=50){ $issaved = $this->savingprocess($data);}';
                  $processThis .= 'else{ die("Error: Only 50 agents are allowed.\n Please contact our Support Team to add more agents \n Thanks"); }';
             }else{
                  # meaning you are in admin mode
                  $processThis = '$issaved = $this->savingprocess($data);';
             }


             # loop for adding new user 
             for($i=1;$i<=$submiteddata['txtSeats'];$i++){
                 $lastnum++;
                 if($lastnum < 10){
                     $lastDigit = "00$lastnum";
                 }elseif($lastnum < 100){
                     $lastDigit = "0$lastnum";
                 }else{
                     $lastDigit = $lastnum; 
                 }
                 $submiteddata['lastDigit'] = $lastDigit;
                 $submiteddata['server_ip'] = $server_ip;
                 $data = $this->usersavedata($submiteddata);

                 if(!empty($data)){
                     eval($processThis); # saving part here
                     # update a2billing_wizard
//	             $this->gosearch->asteriskDB->update('a2billing_wizard',array('num_seats'=>$totalagent,'lastnum'=>$lastnum),array('account_num'=>$submiteddata['accountNum']));
//                     //$this->gosearch->a2billingDB->update('cc_card',array('company_website'=>$totalagent),array('username'=>$submiteddata['accountNum']));
//                     $this->gosearch->asteriskDB->update('a2billing_generate',array('status'=>'Y'));
                     $this->gosearch->asteriskDB->update('servers',array('rebuild_conf_files'=>'Y'));
                 }else{
                     die("Error: Something went wrong to your data");
                 }
             }

             $result = $this->reloadasterisk($issaved);
             if($result){
                echo 'New User successfully created';
             }else{
                die('Error: Something went wrong either on saving or on reloading asterisk');
             }

        } else {

            if(!empty($_POST)){

                 # group the created users
                 $data = array();
                 # loop for adding new user 
                 foreach($_POST as $key => $val){

                      $temp = $key;
                      $new_key = substr($temp,0,strpos($temp,"-"));
                      $data[$new_key][substr($key,strpos($temp,"-")+1)] = $val;
                 }


                 # process the data
                 foreach($data as $group){

                     # get data from a2billing_wizard for updates
                     //$a2bwizardresult = $this->gosearch->asteriskDB->get_where('a2billing_wizard',array('account_num'=>$group['accountNum']));
                     //if($a2bwizardresult->num_rows() > 0){
                     //    $wizardrow = $a2bwizardresult->result();
                     //    $lastnum = $wizardrow[0]->lastnum;
                     //}else{
                         $lastnum = 0;
                     //}

                     $lastnum++;
                     if($lastnum < 10){
                         $lastDigit = "00$lastnum";
                     }elseif($lastnum < 100){
                         $lastDigit = "0$lastnum";
                     }else{
                         $lastDigit = $lastnum; 
                     }
                     $group['lastDigit'] = $lastDigit;
                     $group['server_ip'] = $server_ip;
                     $newdata = $this->usersavedata($group);
                     if(!empty($newdata)){
                         $isSaved = $this->savingprocess($newdata);
    //	                 $this->gosearch->asteriskDB->update('a2billing_wizard',array('num_seats'=>$lastnum,'lastnum'=>$lastnum),array('account_num'=>$group['accountNum']));
    //                     //$this->gosearch->a2billingDB->update('cc_card',array('company_website'=>$totalagent),array('username'=>$submiteddata['accountNum']));
    //                     $this->gosearch->asteriskDB->update('a2billing_generate',array('status'=>'Y'));
                         $this->gosearch->asteriskDB->update('servers',array('rebuild_conf_files'=>'Y'));
                     } else {
                         die("Error: Something went wrong in saving data");
                     }
                 }
                 $result = $this->reloadasterisk($isSaved);
                 if($result){
                     echo 'New User successfully created';
                 } else {
                     die("Error: Something went wrong in reloading asterisk or saving User");
                 }

            } else {
                die("Error: Empty raw data kindly check your data");
            }
            die("Success");

        }


    }


    function usersavedata($postvars=array()){
        if(!empty($postvars)){
                 $pass = $this->genpassword();
                 $data['vicidial_users']['user'] = (array_key_exists('user',$postvars)?$postvars['user']:substr($postvars['accountNum'],0,6)."_".$postvars['lastDigit']);
                 $data['vicidial_users']['pass'] = (array_key_exists("pass",$postvars)?$postvars['pass']:$pass);
                 $data['vicidial_users']['user_group'] = $postvars['accountNum'];
                 $data['vicidial_users']['full_name'] = (array_key_exists('full_name',$postvars)?$postvars['full_name']:$postvars['hidcompany']." Agent ".$postvars['lastDigit']);
                 $data['vicidial_users']['user_level'] = '1';
                 $data['vicidial_users']['phone_login'] = (array_key_exists('phone_login',$postvars)?$postvars['phone_login']:substr($postvars['accountNum'],0,6).$postvars['lastDigit']);
                 $data['vicidial_users']['phone_pass'] = (array_key_exists('phone_pass',$postvars)?$postvars['phone_pass']:$pass);
                 $data['vicidial_users']['agentonly_callbacks'] = '1';
                 $data['vicidial_users']['agentcall_manual'] = '1';
                 $data['vicidial_users']['condition'] = array('user'=>$data['vicidial_users']['user']); 
                 $data['phones']['extension'] = $data['vicidial_users']['phone_login'];
                 $data['phones']['dialplan_number'] = '9999'.$data['vicidial_users']['phone_login'];
                 $data['phones']['voicemail_id'] = $data['vicidial_users']['phone_login'];
                 $data['phones']['phone_ip'] = "";
                 $data['phones']['computer_ip'] = "";
                 $data['phones']['server_ip'] = $postvars['server_ip']; 
                 $data['phones']['login'] = $data['vicidial_users']['phone_login'];
                 $data['phones']['pass'] = $data['vicidial_users']['phone_pass'];
                 $data['phones']['status'] = "ACTIVE";
                 $data['phones']['active'] = "Y";
                 $data['phones']['phone_type'] = "";
                 $data['phones']['fullname']  = "";
                 $data['phones']['company'] = $postvars['accountNum'];
                 $data['phones']['picture'] = "";
                 $data['phones']['protocol'] = 'EXTERNAL';
                 $data['phones']['local_gmt'] = '-5';
                 $data['phones']['outbound_cid'] = '0000000000';
                 $data['phones']['template_id'] = '--NONE--';
                 $conf_override="type=friend\nhost=dynamic\ncanreinvite=no\ncontext=".$posvars['accountNum']."\nqualify=yes\ndisallow=all\nallow=g729\nallow=gsm\nqualify=yes";
                 $data['phones']['conf_override'] = $conf_override;
                 $data['phones']['condition'] = array('extension'=>$data['vicidial_users']['phone_login']);
             return $data;
        }else{
             return array();
        }
    }

    /*
     * savingprocess
     * collects all process to save new user
     * to use eval 
     * @author : Franco E. Hora <info@goautodial.com>
     */
    function savingprocess($data=null){

        if(!is_null($data)){

            $v_ucondition = $data['vicidial_users']['condition'];
            $phonescondition= $data['phones']['condition'];
            unset($data['vicidial_users']['condition']); # remove vicidial condition for saving
            unset($data['phones']['condition']); # remove phones condition for saving

            $result = $this->gosearch->insertuser('vicidial_users',$data['vicidial_users'],$v_ucondition); #insert data to vicidial_users
            
            if($result){

                if($this->gosearch->checkduplicate('phones',array($phonescondition))) {
                    $result = $this->gosearch->asteriskDB->insert('phones',$data['phones']);
                    # let's write our text in conf
                    if($result){
                        $texttowrite = '['.$data['phones']['extension'].']'."\n".$data['phones']['conf_override']."\n\n";
                        $this->commonhelper->writetofile('/etc/asterisk/','sip-goautodial.conf',$texttowrite);

                        # update what must be updated 
                        #$this->gosearch->asteriskDB->update('a2billing_wizard',array('num_seats')); 
                        return $reloadasterisk = true;
                    }else{
                        die("Something went wrong sorry");
                    }
                }
            }
         
        }else{
           die('There is no data to be process');
        }

    }

    function genpassword() {
        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $string = '';    

        for ($p = 0; $p < $length; $p++) {
           $string .= $characters[mt_rand(0, strlen($characters))];
        }

        return $string;
    }


    /*
     * checkstate
     * function to determine hirarchy
     * @author : Franco E. Hora <info@goautodial.com>
     */
    function checkstate(){

        $account = $this->uri->segment(3);

        if(!is_bool($account)){
            $account = $account;
        }else{
            $account = null;
        }

        return $account;
         
    }

    /*
     * reloadasterisk
     * function to reload asterisk
     * @author : Franco E. Hora <info@goautodial.com>
     * @param : $reload > check if 1 reload asterisk
     */
    function reloadasterisk($reload){

       if($reload){ 
         $collectserver = $this->gosearch->asteriskDB->get('servers'); 
         $servers = $collectserver->result();
         $result = exec('ls -R /var/run | grep asterisk.ctl');
         if($result == 'asterisk.ctl'){
                $socket = fsockopen("127.0.0.1", $servers[0]->telnet_port, $errno, $errstr, $timeout);
		fputs($socket, "Action: Login\r\n");
		fputs($socket, "UserName: ".$servers[0]->ASTmgrUSERNAME."\r\n");
		fputs($socket, "Secret: ".$servers[0]->ASTmgrSECRET."\r\n\r\n");
		fputs($socket, "Action: Command\r\n");
		fputs($socket, "Command: reload\r\n\r\n");
		$wrets=fgets($socket,128);
                return true;
         }else{
              die("Can't reload asterisk sorry");
         }
       }else{
           die('Oh no something went wrong on reloading asterisk');
       }
         

    }


    /*
     * reloaduserdisplay
     * update users display
     * @author: Franco E. Hora
     */
    function reloaduserdisplay(){
        $account = $this->checkstate();
        var_dump($account);die;
    }


    /*
     * groupbylist
     * display all users by group 
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function groupbylist(){

        $bygroup = $this->gosearch->bygroup(); 
        #$bygroup = $this->gosearch->bygroupghost();

        $data['go_main_content'] = 'go_dashboard';
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

	$data['bannertitle'] = 'Recordings';
        $data['folded'] = 'folded';
        $data['users'] = $users;
        $data['bygroup'] = $bygroup;


        $this->load->view('go_search/go_user_group',$data);
    }



    /*
     * ungrouplist
     * display all users under current owner ungroup
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function ungrouplist(){

        $ungroup = $this->gosearch->bygroup($this->session->userdata('user_name'));
        $data['go_main_content'] = 'go_dashboard';
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

	$data['bannertitle'] = 'Recordings';
        $data['folded'] = 'folded';
        $data['users'] = $users;
        $data['toungroup'] = $ungroup;
	$data['groupvalues'] = $groupvalues;

        $this->load->view('go_search/go_user_ungroup',$data);

    }


    /*
     * go_users
     * initial page display of users template
     * @author: Franco E. Hora
     */
    function go_search(){


        $username = $this->session->userdata('user_name');
        if(empty($username)){
           $this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
        }

        $data['username'] = $username;
        $data['user_group'] = $this->session->userdata('user_group');
        $data['user_level'] = $this->session->userdata('users_level'); 
        if($data['user_level'] < 7){
           //$data['foradd'] = $this->gosearch->retrievedata('a2billing_wizard',array('account_num'=>$data['username']));
        }else{
           //$accounts = $this->gosearch->retrievedata('a2billing_wizard');
           if(!empty($accounts)){
               foreach($accounts as $account){
                   $accnt[$account->account_num] = $account->company;
               }
               $data['accnt_list'] = $accnt;
               $data['foradd'] = json_encode($accounts);
           }else{
               $accnt = array();
           }
        }

        # for hello at top right
        $userinfo = $this->gosearch->retrievedata('vicidial_users',array('user'=>$data['username']));
        $data['userfulname'] = $userinfo[0]->full_name;

        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

	$data['theme'] = $this->session->userdata('go_theme');
 
        $type = $this->uri->segment(3);

        if(empty($type)){
	    $data['bannertitle'] = 'Recordings';
	    $data['icon_s'] = 'icon-recording';
        } else {
            $data['bannertitle'] = "Search Results";
            $data['type'] = $type;
	    $data['icon_s'] = 'icon-search';
        }

	$data['rec']= 'wp-has-current-submenu';
	$data['hostp'] = $_SERVER['SERVER_ADDR'];
	$data['folded'] = 'folded';
	$data['foldlink'] = '';
	$togglestatus = "1";
	$data['togglestatus'] = $togglestatus;		
	

        #$data['go_main_content'] = 'go_search/go_search_main';
        $data['go_main_content'] = 'go_search/go_search_main_ce';
	$data['permissions'] = $this->commonhelper->getPermissions("recording",$this->session->userdata("user_group"));

        $data['search_key']= $this->uri->segment(2);
        #var_dump($data['search_key']);die;
        if(!empty($data['search_key'])){

             $dateString = substr($data['search_key'],strpos($data['search_key'],"daterange"));
             if(eregi('daterange',$dateString)){
                 $data['daterange'] = explode("=",$dateString);
             }

        }

        # global dispo
        $all_dispos = array(""=>"--- SELECT A DISPOSITION ---");
        $gl_dispos = $this->gosearch->asteriskDB->get('vicidial_statuses')->result();
        foreach($gl_dispos as $dispo){
            $all_dispos[$dispo->status_name] = $dispo->status_name;
        }
        $campaigns = $this->commonhelper->getallowablecampaign($data['user_group']); 
        if(!empty($campaigns)){
            $this->gosearch->asteriskDB->where_in("campaign_id",$campaigns);
            $camp_dispos = $this->gosearch->asteriskDB->get('vicidial_campaign_statuses')->result();
            if(!empty($camp_dispos)){
                foreach($camp_dispos as $dispo){
                   $all_dispos[$dispo->status_name] = $dispo->status_name;
                }
            }
        }
        $data['dispos'] = $all_dispos;

        # agents
        if ($this->commonhelper->checkIfTenant($data['user_group']))
            $this->gosearch->asteriskDB->where('user_group',$data['user_group']);
        $this->gosearch->asteriskDB->where('user_level <',7);
        $this->gosearch->asteriskDB->not_like(array('full_name'=>"Survey","user"=>"VDAD"));
        $this->gosearch->asteriskDB->not_like("user","VDCL");
        $agents = $this->gosearch->asteriskDB->get('vicidial_users')->result();
        if(!empty($agents)){
            $data['agents'][''] = "--- SELECT AN AGENT ---";
            foreach($agents as $agent_info){
                 $data['agents'][$agent_info->full_name] = $agent_info->full_name;
            }
        } else {
            $data['agents'] = array();
        }


        # statuses
        $this->gosearch->asteriskDB->select("status,status_name,selectable,human_answered,category,sale,dnc,customer_contact,not_interested,unworkable");
        $this->gosearch->asteriskDB->where(array('selectable'=>'Y'));
        $this->gosearch->asteriskDB->order_by('status','asc');
        $statuses = $this->gosearch->asteriskDB->get('vicidial_statuses')->result();

        if(!empty($statuses)){
              $stats = array();
              foreach($statuses as $stat){
                  $stats[$stat->status] = "$stat->status - $stat->status_name";
              }
        }
        $data['status'] = $stats;       
        $this->gosearch->asteriskDB->where(array('user_group'=>$username,'active'=>'Y','user_level !='=>8));
        $this->gosearch->asteriskDB->not_like('full_name','Survey');
        $all_agents = $this->gosearch->asteriskDB->get('vicidial_users')->result();
        $all_agent =array();
        if(count($all_agents) > 0){
           foreach($all_agents as $allagent){
               $all_agent[$allagent->user] = $allagent->full_name;
           }
           $data['all_agent'] = $all_agent;
        }
        $data['all_agent']=$all_agent;
        $this->load->view('includes/go_dashboard_template.php',$data);
    }


    /*
     * collectuserinfo
     * collect user information in vicidial_users
     * @author : Franco E. Hora <info@goautodial.com>
     */
    function collectuserinfo(){
        $vicidial_user_id = $this->uri->segment(3);
        $info = $this->gosearch->retrievedata('vicidial_users',array('user_id'=>$vicidial_user_id),array('pass','full_name','phone_login','phone_pass','active'));
        $this->output->set_header('Content-Type: text/json');
        $this->output->set_header('Content-Type: application/json');
        $this->output->set_output(json_encode($info));
    }


    /*
     * updateuser
     * update vicidial_users and go_users table
     * @author : Franco E. Hora <info@goautodial.com>
     */
    function updateuser(){

        $username = $this->session->userdata('user_name');
        if(empty($username)){
            die("Error: Session expired kindly relogin");
        }
        $access = unserialize($this->session->userdata('useraccess'));
        # set access here
        if(in_array('modify_users',$access)){
            $usersid = $this->uri->segment(4);
            $vicidial_userid = $this->uri->segment(3);
            if(!array_key_exists('users_id',$_POST)){
                $_POST['users_id'] = $vicidial_userid;
            }
            $fields = $this->userhelper->postcleanup($_POST,$vicidial_userid,array("-$usersid","$usersid-"));
            $result['result'] = $this->gosearch->updateuser($fields);

            $this->load->view('go_search/json_text.php',$result);
        }else{
            die("Error: You are not allowed to modify user"); 
        }
    }

     /*
     * deleteuser
     * update vicidial_users and go_users table and set to active = N
     * @author : Franco E. Hora <info@goautodial.com>
     */  
    function deleteuser(){

        $username = $this->session->userdata('user_name');
        if(empty($username)){
            die("Error: Session expired kindly relogin");
        }
        $access = unserialize($this->session->userdata('useraccess'));
        if(in_array('modify_users',$access)){
             $userid = $this->uri->segment(3);
             $vicidial_user = $this->uri->segment(4);
             $result = $this->gosearch->updateuser(array($userid=>array('active'=>'N','users_id'=>$vicidial_user))); 

             echo $result;
        }else{
            die("Error: You are not allowed to disable this user");
        }

    }



    /*
     * advancemodifyuser
     * modify template
     * @author: Franco E. Hora
     */
    function advancemodifyuser(){
 
        # set user access here
        #if(){
            $data['info'] = $this->gosearch->retrievedata('vicidial_users',array('user'=>$_POST['userid']));
            # collect vicidial_user_groups
            $viciusergroups = $this->gosearch->retrievedata('vicidial_user_groups',array('user_group'=>$data['info'][0]->user_group));
            
            # retrieve groups now lets get the name of the access
            if(!empty($viciusergroups)){
               $data['usergroups']  = $viciusergroups;
            }else{
               die('Got problem in retrieving groups contact your administrator');
            }
            # set allowedaccess
            $allowedtouser = unserialize($this->session->userdata('useraccess'));
            $data['allowedaccess'] = $allowedtouser;
            $data['useraccess'] = $this->gosearch->asteriskDB->get('go_useraccess')->result();

            $this->load->view('go_search/go_search_advance_ce',$data);
        #}else{
            #die("Permission denied to view page");
        #}
    }


    /*
     * createusergroup
     * function to create a new group
     * @author : Franco E. Hora <info@goautodial.com>
     */
    function createusergroup(){
         # check session if still not expired
         # set user access
         # if(){
            $this->gosearch->createusergroup($_POST['currentuser']); 
         # else{}
    }
  


    /*
     * saveusergroup
     * function to save group create and update
     * @author : Franco E. Hora <info@goautodial.com> 
     */
    function saveusergroup(){
         # check session if still not expired
         # set user access
         # if(){
              # vicidial_user_id of current user being modified
              $vicidial_user_id = $this->uri->segment(3);
              $newcreatedid = $this->uri->segment(4);
              if($newcreatedid != 'undefined'){
                  $postfields = $this->userhelper->postcleanup($_POST,$vicidial_user_id,array("-$newcreatedid","$newcreatedid-"));
                  $this->gosearch->saveusergroup($postfields,$newcreatedid);
              }
         # else{}
    }

    /*
     * deleteitem
     * funciton to permanently delete group
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function deleteitem(){
        $this->gosearch->deleteitem('go_groupaccess','id ='.$_POST['groupid']);
    }


    /*
     * updategosearchs
     * funciton to update go_users table 
     * @author: Franco E. Hora <info@goautodial.com> 
     */
     function updategosearchs(){
         $vUserid = $this->uri->segment(3);
         if($vUserid != 'undefined'){
             $this->gosearch->asteriskDB->trans_start();
                 $this->gosearch->asteriskDB->where('vicidial_user_id',$vUserid);
                 $this->gosearch->asteriskDB->update('go_users',$_POST);
             $this->gosearch->asteriskDB->trans_complete();
             echo "User added to active group";
         }
     }

     /*
      * checkslave
      * check settings 
      */
     function getsettings(){
     
         $userlogged = $this->session->userdata('user_name');

         if(!is_null($userlogged) && !empty($userlogged)){
              $slavedbexist = $this->gosearch->retrievedata('system_settings',array(),$fields);        
              return $slavedbexist;
         }else{
             die('Error no user is logged in');
         }
     }


     /*
      * userinfo
      * display user status only 
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function userinfo(){
         $username = $this->session->userdata('user_name');
         if(!empty($username)){
             if(!empty($_POST['userid'])){
                $userinfo = $this->gosearch->retrievedata('vicidial_users',array('user'=>$_POST['userid']));
                #search from vicidial_live_agent
                $fields = array('live_agent_id','user','server_ip','conf_exten','extension','status','lead_id','campaign_id',
                                'uniqueid','callerid','channel','random_id','last_call_time','last_update_time','last_call_finish',
                                'closer_campaigns','call_server_ip','user_level','comments','campaign_weight','calls_today','external_hangup',
                                'external_status','external_pause','external_dial','agent_log_id','last_state_change','agent_territories',
                                'outbound_autodial','manager_ingroup_set','external_igb_set_user');
                $liveagent = $this->gosearch->retrievedata('vicidial_live_agents',array('user'=>$_POST['userid']),$fields);

                # if INCALL check if parked then override value of status
                if($liveagent[0]->status == 'INCALL'){
                   # check if you are in park or to change to dead
                   $ifparked = $this->gosearch->asteriskDB->get_where('parked_channels',array('channel_group'=>$data['liveagent'][0]->callerid));
                   if($ifparked->num_rows > 0){
                       $liveagent[0]->status = 'PARK';
                   }else{
                       $ifVautocall = $this->gosearch->asteriskDB->get_where('vicidial_auto_calls',array('callerid'=>$data['liveagent'][0]->callerid));
                       if($ifVautocall->num_rows == 0){
                           $liveagent[0]->status = 'DEAD';  
                       }
                   }
                }
                echo json_encode(array($userinfo,$liveagent));
             }else{
                die("Error: No user id passed");
             }
         }else{
             die("Error: Session expired kindly re-login");
         }
     }


     /*
      * userstatus
      * function to display all status
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function userstatus(){
         # user status first part
         # check if you still have session
         $userlogged = $this->session->userdata('user_name');
         if(isset($userlogged)){
             $araw = date('r');
             $userpass = $this->session->userdata('user_pass');
             $ip = $this->session->userdata('ip_address');
             $browser = $this->session->userdata('user_agent');
             $userfields = array('user','full_name','change_agent_campaign','modify_timeclock_log','user_level');
             $data['loggeduserinfo'] = $this->gosearch->retrievedata('vicidial_users',array('user'=>$userlogged),$userfields);
             $this->commonhelper->writetofile('/var/www/html/extrafiles/','project_auth_entries.txt',"VICIDIAL|GOOD|$araw|$userlogged|$userpass|$ip|$browser|".$data['loggeduserinfo'][0]->full_name."|\n"); 
         }else{
              $this->commonhelper->writetofile('/var/www/html/extrafiles/','project_auth_entries.txt',"VICIDIAL|FAIL|$araw|$userlogged|$userpass|$ip|$browser|\n"); 
              die('destroy session and redirect to login');
         }
         #userinfo of the chosen user
         $data['userinfo'] = $this->gosearch->retrievedata('vicidial_users',array('user'=>$_POST['userid']));

         #search from vicidial_live_agent
         $fields = array('live_agent_id','user','server_ip','conf_exten','extension','status','lead_id','campaign_id',
                      'uniqueid','callerid','channel','random_id','last_call_time','last_update_time','last_call_finish',
                      'closer_campaigns','call_server_ip','user_level','comments','campaign_weight','calls_today','external_hangup',
                      'external_status','external_pause','external_dial','agent_log_id','last_state_change','agent_territories',
                      'outbound_autodial','manager_ingroup_set','external_igb_set_user');
         $data['liveagent'] = $this->gosearch->retrievedata('vicidial_live_agents',array('user'=>$_POST['userid']),$fields);

         # if INCALL check if parked then override value of status
         if($liveagent[0]->status == 'INCALL'){
             # check if you are in park or to change to dead
             $ifparked = $this->gosearch->asteriskDB->get_where('parked_channels',array('channel_group'=>$data['liveagent'][0]->callerid));
             if($ifparked->num_rows > 0){
                 $data['liveagent'][0]->status = 'PARK';
             }else{
                $ifVautocall = $this->gosearch->asteriskDB->get_where('vicidial_auto_calls',array('callerid'=>$data['liveagent'][0]->callerid));
                if($ifVautocall->num_rows == 0){
                    $data['liveagent'][0]->status = 'DEAD';  
                }
             }
         }
                  
         $this->gosearch->asteriskDB->where(array('campaign_id'=>$data['liveagent'][0]->campaign_id));
         $data['campaigns'] = $this->gosearch->asteriskDB->get('vicidial_campaigns')->result();
                 
         # welcome to the sencond part user stat oh yeah
         # check settings slave_db if exist
         $slavedbexist = $this->getsettings();
         if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
            $slaveExist = TRUE;
            $useThis = 'slaveDB'; 
         }else{
            $slaveExist = '';
            $useThis = 'asteriskDB';
         }
         $allowedcamp = $this->gosearch->getallowedcampaign($data['userinfo'][0]->user_group,null,$slaveExist);
         $this->gosearch->$useThis->select('allowed_reports');
         $allowedreports = $this->gosearch->$useThis->get_where('vicidial_user_groups',array('user_group'=>$data['userinfo'][0]->user_group))->result();

         $this->load->view('go_search/userstatus',$data);
         
     }

     /*
      * agentTalkTimeStatus
      * collect agent Talk Time Status
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function agentTalkTimeStatus(){
         if(!empty($_POST)){
             $datefrom = $_POST['dates'][0];
             $dateto = $_POST['dates'][1];
             if(!empty($_POST['user'])){
                 # welcome to the sencond part user stat oh yeah
                 # check settings slave_db if exist
                 $slavedbexist = $this->getsettings();
                 if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                     $useThis = 'slaveDB'; 
                 }else{
                     $useThis = 'asteriskDB';
                 }
                 $this->gosearch->$useThis->select('count(*) as rows,status, sum(length_in_sec) as length_in_sec');
                 $this->gosearch->$useThis->where(array('user'=>$_POST['user']));
                 $this->gosearch->$useThis->where("call_date BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                 $this->gosearch->$useThis->order_by('status','asc');
                 $vicidial_log = $this->gosearch->$useThis->get('vicidial_log')->result();
 
                 $ctr=0;
                 $totaltime = 0;
                 if(!empty($vicidial_log)){
                     foreach($vicidial_log as $logs){
                         if(($ctr%2)!=0){
                             $rowclass = 'oddrow';
                         }else{
                             $rowclass = 'evenrow';
                         }
                         $totaltime += $logs->length_in_sec;
                         echo "
                               <div class='$rowclass'>
                                   <div class='cols'>".$logs->status."</div>
                                   <div class='cols'>".$logs->rows."</div>
                                   <div class='cols'>".date('H:i:s',mktime(0,0,$logs->length_in_sec))."</div><br/>
                               </div>
                               <br class='spacer'/>";
                     }
                     echo "<div class='totaltime'>
                               <div class='labelcols'>Total Calls</div>
                               <div class='spacercols'>&nbsp;</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totaltime))."</div><br/>
                           </div>";
                 }
             }
         }else{
         }
     }

     /*
      * agetnLoginLogouTime
      * get all login/logout time
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function agentLoginLogoutTime(){
          if(!empty($_POST)){
              sleep(2);
              $datefrom = $_POST['dates'][0];
              $dateto =$_POST['dates'][1];
              if(!empty($_POST['user'])){
                   # start the query
                   $slavedbexist = $this->getsettings();
                   if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                        $useThis = 'slaveDB'; 
                   }else{
                        $useThis = 'asteriskDB';
                   }
                   $fields = array('event','event_epoch','event_date','campaign_id','user_group','session_id','server_ip','extension','computer_ip'); 
                   $this->gosearch->$useThis->select($fields); 
                   $this->gosearch->$useThis->where(array('user'=>$_POST['user']));
                   $this->gosearch->$useThis->where("event_date BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                   $this->gosearch->$useThis->order_by('event_date','asc');
                   $this->gosearch->$useThis->limit(500);
                   $loginlogout = $this->gosearch->$useThis->get('vicidial_user_log')->result();

                   if(!empty($loginlogout)){
                       $ctr = 0; 
                       $totaltime = 0;
                       $starttime = 0;
                       foreach($loginlogout as $agentlogin){
                          if(($ctr%2)!=0){
                              $rowclass = 'oddrow';
                          }else{
                              $rowclass = 'evenrow';
                          }
                          if($agentlogin->event == "LOGOUT"){
                              $eventtime = ($agentlogin->event_epoch - $starttime);
                              $totaltime += $eventtime;
                          }else{
                              if($agentlogin->event == "LOGIN"){
                                  $starttime = $agentlogin->event_epoch;
                              }
                          }
                          echo "<div class='$rowclass'> 
                                    <div class='cols'>".$agentlogin->event."</div>
                                    <div class='cols'>$agentlogin->event_date</div>
                                    <div class='cols'>$agentlogin->campaign_id</div>
                                    <div class='cols'>$agentlogin->user_group</div>
                                    <div class='cols'>".(($agentlogin->event=='LOGIN')?'&nbsp': date('H:i:s',mktime(0,0,$eventtime)) )."</div>
                                    <div class='cols'>$agentlogin->session_id</div>
                                    <div class='cols'>$agentlogin->server_ip</div>
                                    <div class='cols'>$agentlogin->extension</div>
                                    <div class='cols'>$agentlogin->computer_ip</div>
                                    <br/>
                                </div>
                                <br class='spacer'/>"; 
                           $ctr++;
                       }
                       echo "<div class='totaltime'>
                               <div class='labelcols'>Total Calls</div>
                               <div class='totalcols'>".date('H:i:s',mktime(0,0,$totaltime))."</div>
                               <div class='spacercols'>&nbsp;</div>
                           </div>";
                   }
              }
          }
     }

     /*
      * outboundThisTime
      * collect outbound call this time
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function outboundThisTime(){
        if(!empty($_POST)){
            #check user
            sleep(2);
            if(!empty($_POST['user'])){
                $datefrom = $_POST['dates'][0];
                $dateto =$_POST['dates'][1];
                $slavedbexist = $this->getsettings();
                if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                     $useThis = 'slaveDB'; 
                }else{
                     $useThis = 'asteriskDB';
                }
                $fields = array('uniqueid','lead_id','list_id','campaign_id','call_date','start_epoch',
                                'end_epoch','length_in_sec','status','phone_code','phone_number','user',
                                'comments','processed','user_group','term_reason','alt_dial'); 
                $this->gosearch->$useThis->select($fields);
                $this->gosearch->$useThis->where("call_date BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                $this->gosearch->$useThis->where(array('user'=>$_POST['user']));
                $this->gosearch->$useThis->order_by('call_date','desc');
                $this->gosearch->$useThis->limit(1000);
                $outboundcalls = $this->gosearch->$useThis->get('vicidial_log')->result();
               
                if(!empty($outboundcalls)){
                    $ctr = 0;
                    foreach($outboundcalls as $outbounds){
                        if(($ctr%2)!=0){
                            $rowclass = 'oddrow';
                        }else{
                            $rowclass = 'evenrow';
                        }
                        echo "<div class='$rowclass'>
                                  <div class='cols'>$outbounds->call_date</div>
                                  <div class='cols'>".date("H:i:s",mktime(0,0,$outbounds->length_in_sec))."</div>
                                  <div class='cols'>$outbounds->status</div>
                                  <div class='cols'>$outbounds->phone_number</div>
                                  <div class='cols'>$outbounds->campaign_id</div>
                                  <div class='cols'>$outbounds->user_group</div>
                                  <div class='cols'>$outbounds->list_id</div>
                                  <div class='cols'>$outbounds->lead_id</div>
                                  <div class='cols'>$outbounds->term_reason</div>
                                  <br/>
                              </div>
                              <br class='spacer'/>";
                        $ctr++;
                    } 
                }
            }
        }
     }

     /*
      * inboundThisTime
      * collect inbound call this time
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function inboundThisTime(){
        if(!empty($_POST)){
            sleep(2);
            #check user
            if(!empty($_POST['user'])){
                $datefrom = $_POST['dates'][0];
                $dateto =$_POST['dates'][1];
                $slavedbexist = $this->getsettings();
                if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                     $useThis = 'slaveDB'; 
                }else{
                     $useThis = 'asteriskDB';
                }
                $fields = array('call_date','length_in_sec','status','phone_number','campaign_id','queue_seconds','list_id','lead_id','term_reason'); 
                $this->gosearch->$useThis->select($fields);
                $this->gosearch->$useThis->where("call_date BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                $this->gosearch->$useThis->where(array('user'=>$_POST['user']));
                $this->gosearch->$useThis->order_by('call_date','desc');
                $this->gosearch->$useThis->limit(1000);
                $inboundcalls = $this->gosearch->$useThis->get('vicidial_closer_log')->result();
               
                if(!empty($inboundcalls)){
                    $ctr = 0;
                    foreach($inboundcalls as $inbounds){
                        if(($ctr%2)!=0){
                            $rowclass = 'oddrow';
                        }else{
                            $rowclass = 'evenrow';
                        }
                        $totalagents += $inbounds->length_in_sec;
                        $agentsec = ($inbounds->length_in_sec - $inbounds->queue_seconds);
                        $totalagentsec += $agentsec;
                        echo "<div class='$rowclass'>
                                  <div class='cols'>$inbounds->call_date</div>
                                  <div class='cols'>".date("H:i:s",mktime(0,0,$inbounds->length_in_sec))."</div>
                                  <div class='cols'>$inbounds->status</div>
                                  <div class='cols'>$inbounds->phone_number</div>
                                  <div class='cols'>$inbounds->campaign_id</div>
                                  <div class='cols'>$inbounds->queue_seconds</div>
                                  <div class='cols'>".date("H:i:s",mktime(0,0,$agentsec))."</div>
                                  <div class='cols'>$inbounds->list_id</div>
                                  <div class='cols'>$inbounds->lead_id</div>
                                  <div class='cols'>$inbounds->term_reason</div>
                                  <br/>
                              </div>
                              <br class='spacer'/>";
                        $ctr++;
                    }
                    echo "<div class='totaltime'>
                              <div class='labelcols'>Total Calls</div>
                              <div class='totalcols'>".date("H:i:s",mktime(0,0,$totalagents))."</div>
                              <div class='spacercols'>&nbsp;</div>
                              <div class='totalcols'>".date("H:i:s",mktime(0,0,$totalagentsec))."</div>
                              <br/>
                          </div>";
                }
            }
        }
     }


     /*
      * recordingThisTime
      * collect recordings this time
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function recordingThisTime(){
        if(!empty($_POST)){
            sleep(2);
            #check user
            if(!empty($_POST['user'])){
                $datefrom = $_POST['dates'][0];
                $dateto =$_POST['dates'][1];
                $slavedbexist = $this->getsettings();
                if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                     $useThis = 'slaveDB'; 
                }else{
                     $useThis = 'asteriskDB';
                }
                $fields = array('recording_id','channel','server_ip','extension','start_time','start_epoch','end_time','end_epoch','length_in_sec','length_in_min','filename','location','lead_id','user','vicidial_id'); 
                $this->gosearch->$useThis->select($fields);
                $this->gosearch->$useThis->where("start_time BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                $this->gosearch->$useThis->where(array('user'=>$_POST['user']));
                $this->gosearch->$useThis->order_by('recording_id','desc');
                $this->gosearch->$useThis->limit(1000);
                $recordings = $this->gosearch->$useThis->get('recording_log')->result();
              
                if(!empty($recordings)){
                    $ctr = 0;
                    foreach($recordings as $record){
                        if(($ctr%2)!=0){
                            $rowclass = 'oddrow';
                        }else{
                            $rowclass = 'evenrow';
                        }
                        echo "<div class='$rowclass'>
                                  <div class='cols'>$record->lead_id</div>
                                  <div class='cols'>$record->start_time</div>
                                  <div class='cols'>".date("H:i:s",mktime(0,0,$record->length_in_sec))."</div>
                                  <div class='cols'>$record->recording_id</div>
                                  <div class='cols elipsis bubble'>$record->filename</div>
                                  <div class='cols elipsis'><a href='$record->location'>$record->location</a></div>
                                  <br/>
                              </div>
                              <br class='spacer'/>";
                        $ctr++;
                    }
                }
            }
        }
     }

     /*
      * manualoutboundThisTime
      * collect manual outbound call this time
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function manualoutboundThisTime(){
        if(!empty($_POST)){
            sleep(2);
            #check user
            if(!empty($_POST['user'])){
                $datefrom = $_POST['dates'][0];
                $dateto =$_POST['dates'][1];
                $slavedbexist = $this->getsettings();
                if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                     $useThis = 'slaveDB'; 
                }else{
                     $useThis = 'asteriskDB';
                }
                $fields = array('call_date','call_type','server_ip','phone_number','number_dialed','lead_id','callerid','group_alias_id','preset_name','customer_hungup','customer_hungup_seconds'); 
                $this->gosearch->$useThis->select($fields);
                $this->gosearch->$useThis->where("call_date BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                $this->gosearch->$useThis->where(array('user'=>$_POST['user']));
                $this->gosearch->$useThis->order_by('call_date','desc');
                $this->gosearch->$useThis->limit(1000);
                $manualoutboundcalls = $this->gosearch->$useThis->get('user_call_log')->result();

               
                if(!empty($manualoutboundcalls)){
                    $ctr = 0;
                    foreach($manualoutboundcalls as $manualoutbounds){
                        if(($ctr%2)!=0){
                            $rowclass = 'oddrow';
                        }else{
                            $rowclass = 'evenrow';
                        }
                        echo "<div class='$rowclass'>
                                  <div class='cols'>$manualoutbounds->call_date</div>
                                  <div class='cols'>$manualoutbounds->call_type</div>
                                  <div class='cols'>$manualoutbounds->server_ip</div>
                                  <div class='cols'>$manualoutbounds->phone_number</div>
                                  <div class='cols'>$manualoutbounds->number_dialed</div>
                                  <div class='cols'>$manualoutbounds->lead_id</div>
                                  <div class='cols'>$manualoutbounds->callerid</div>
                                  <div class='cols'>$manualoutbounds->group_alias_id</div>
                                  <div class='cols'>$manualoutbounds->preset_name</div>
                                  <div class='cols'>$manualoutbounds->customer_hungup ".$manualoutbounds->customer_hungup_seconds."</div>
                                  <br/>
                              </div>
                              <br class='spacer'/>";
                        $ctr++;
                    }
                }
            }
        }
     }

     /*
      * leadsearchThisTime
      * collect lead search this time
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function leadsearchThisTime(){
        if(!empty($_POST)){
            sleep(2);
            #check user
            if(!empty($_POST['user'])){
                $datefrom = $_POST['dates'][0];
                $dateto =$_POST['dates'][1];
                $slavedbexist = $this->getsettings();
                if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                     $useThis = 'slaveDB'; 
                }else{
                     $useThis = 'asteriskDB';
                }
                $fields = array('event_date','source','results','seconds','search_query'); 
                $this->gosearch->$useThis->select($fields);
                $this->gosearch->$useThis->where("event_date BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                $this->gosearch->$useThis->where(array('user'=>$_POST['user']));
                $this->gosearch->$useThis->order_by('event_date','desc');
                $this->gosearch->$useThis->limit(1000);
                $leadsearches = $this->gosearch->$useThis->get('vicidial_lead_search_log')->result();

               
                if(!empty($leadsearches)){
                    $ctr = 0;
                    foreach($leadsearches as $leadsearch){
                        if(($ctr%2)!=0){
                            $rowclass = 'oddrow';
                        }else{
                            $rowclass = 'evenrow';
                        }
                        $query = substr($leadsearch->search_query,strpos($leadsearch->search_query,"where")+6);
                        echo "<div class='$rowclass'>
                                  <div class='cols'>$leadsearch->event_date</div>
                                  <div class='cols'>$leadsearch->source</div>
                                  <div class='cols'>$leadsearch->results</div>
                                  <div class='cols'>$leadsearch->seconds</div>
                                  <div class='cols elipsis bubble'>".$query."</div>
                                  <br/>
                              </div>
                              <br class='spacer'/>";
                        $ctr++;
                    }

                }
            }
        }
     }



     /*
      * agentActivity
      * function to collect agent activity
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function agentActivity(){
         if(!empty($_POST)){
             sleep(2);
             if(!empty($_POST['user'])){
                 $datefrom = $_POST['dates'][0];
                 $dateto =$_POST['dates'][1];
                 $slavedbexist = $this->getsettings();
                 if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                      $useThis = 'slaveDB'; 
                 }else{
                      $useThis = 'asteriskDB';
                 }
                 $fields = array('event_time','lead_id','campaign_id','pause_sec','wait_sec','talk_sec','dispo_sec','dead_sec','status','sub_status','user_group'); 
                 $this->gosearch->$useThis->select($fields);
                 $this->gosearch->$useThis->where(array('user'=>$_POST['user']));
                 $this->gosearch->$useThis->where("event_time BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                 $this->gosearch->$useThis->where("( (pause_sec > 0) or (wait_sec > 0) or (talk_sec > 0) or (dispo_sec > 0) )");
                 $this->gosearch->$useThis->order_by('event_time','desc');
                 $this->gosearch->$useThis->limit(1000);           
                 $agentactivities = $this->gosearch->$useThis->get('vicidial_agent_log')->result();


                 if(!empty($agentactivities)){
                     foreach($agentactivities as $activity){
                         if(($ctr%2)!=0){
                             $rowclass = 'oddrow';
                         }else{
                             $rowclass = 'evenrow';
                         }

                         $totalpause += $activity->pause_sec;
                         $totalwait += $activity->wait_sec;
                         $totaltalk += $activity->talk_sec;
                         $totaldispo += $activity->dispo_sec;
                         $totaldead += $activity->dead_sec;
                         $customer_sec = $activity->talk_sec - $activity->dead_sec;
                         $totalcustomer += $customer_sec;
                         echo "<div class='$rowclass'>
                                   <div class='cols'>$activity->event_time</div>
                                   <div class='cols'>".date("H:i:s",mktime(0,0,$activity->pause_sec))."</div>
                                   <div class='cols'>".date("H:i:s",mktime(0,0,$activity->wait_sec))."</div>
                                   <div class='cols'>".date("H:i:s",mktime(0,0,$activity->talk_sec))."</div>
                                   <div class='cols'>".date("H:i:s",mktime(0,0,$activity->dispo_sec))."</div>
                                   <div class='cols'>".date("H:i:s",mktime(0,0,$activity->dead_sec))."</div>
                                   <div class='cols'>".date("H:i:s",mktime(0,0,$customer_sec))."</div>
                                   <div class='cols'>$activity->status</div>
                                   <div class='cols'>$activity->lead_id</div>
                                   <div class='cols'>$activity->campaign_id</div>
                                   <div class='cols'>$activity->sub_status</div>
                                   <br/>
                               </div>
                               <br class='spacer'/>";
                        $ctr++;
                     }
                     echo "<div class='totaltime'>
                               <div class='labelcols'>Total Calls</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totalpause))."</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totalwait))."</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totaltalk))."</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totaldispo))."</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totaldead))."</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totalcustomer))."</div>
                               <div class='spacercols'>&nbsp;</div>
                               <br/>
                           </div>";
                 }
             }
         }
     }


     /*
      * emergencylogout
      * function to logout the user
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function emergencylogout(){

         $NOW_TIME = date("Y-m-d H:i:s");
         $thedate = date('U');
         $inactive_epoch = ($thedate - 60);
         $this->gosearch->asteriskDB->select('user,campaign_id,UNIX_TIMESTAMP(last_update_time)as last_update_time');
         $Vliveagent = $this->gosearch->asteriskDB->get_where('vicidial_live_agents',array('user'=>$_POST['user']))->result(); 
         if(!empty($Vliveagent)){
             if($Vliveagent[0]->last_update_time > $inactive_epoch){
                 $fields = array('agent_log_id','user','server_ip','event_time','lead_id','campaign_id','pause_epoch','pause_sec',
                                 'wait_epoch','wait_sec','talk_epoch','talk_sec','dispo_epoch','dispo_sec','status','user_group',
                                 'comments','sub_status','dead_epoch','dead_sec');
                 $this->gosearch->asteriskDB->select($fields);
                 $this->gosearch->asteriskDB->where(array('user'=>$Vliveagent[0]->user));
                 $this->gosearch->asteriskDB->order_by('agent_log_id','desc'); 
                 $this->gosearch->asteriskDB->limit(1);
                 $agentlog = $this->gosearch->asteriskDB->get('vicidial_agent_log');
                 #the result is 
                 $agents = $agentlog->result();
                 $this->gosearch->asteriskDB->trans_start();
                     if($agentlog->num_rows > 0){

                         if($agents[0]->wait_epcoh < 1 || ($agents[0]->status == 'PAUSE' && $agents[0]->dispo_epoch < 1) ){
                             $agents[0]->pause_sec = (($thedate-$agents[0]->pause_epoch)+$agents[0]->pause_sec);
                             $updatefields = array('wait_epoch'=>$thedate,'pause_sec'=>$agents[0]->pause_sec);
                         }else{
                             if($agents[0]->talk_epoch < 1){
                                 $agents[0]->wait_sec = (($thedate-$agents[0]->wait_epoch) + $agents[0]->wait_sec);
                                 $updatefields = array('talk_epoch'=>$thedate,'wait_sec'=>$agents[0]->wait_sec);
                             }else{
                                 if(is_null($agents[0]->status) && $agents[0]->lead_id > 0){
                                     $this->gosearch->asteriskDB->where(array('lead_id'=>$agents[0]->lead_id));
                                     $updatethis = array('status'=>'PU');
                                     $this->gosearch->asteriskDB->update('vicidial_list',$updatethis);
                                 }
                                 if($agents[0]->dispo_epoch < 1){
                                     $agents[0]->talk_sec = ($thedate-$agents[0]->talk_epoch);
                                     $updatefields = array_merge(array('dispo_epoch'=>$thedate,'talk_sec'=>$agents[0]->talk_sec),$updatethis);
                                 }else{
                                     if($agents[0]->dispo_epoch < 1){
                                         $agents[0]->dispo_sec = ($thedate-$agents[0]->dispo_epoch);
                                         $updatefields = array('dispo_sec'=>$agents[0]->dispo_sec);
                                     }
                                 }
                             }
                         }
                         $this->gosearch->asteriskDB->where(array('agent_log_id'=>$agents[0]->agent_log_id));
                         $this->gosearch->asteriskDB->update('vicidial_agent_log',$updatefields);
                     }
                     $this->gosearch->asteriskDB->delete('vicidial_live_agents',array('user'=>$agents[0]->user));
                     
                     $valuetolog = array('user'=>$agents[0]->user,'event'=>'LOGOUT','campaign_id'=>$agents[0]->campaign_id,'event_date'=>$NOW_TIME,
                                         'event_epoch'=>$thedate,'user_group'=>$agents[0]->user_group);
                     # force logout the user
                     $this->commonhelper->auditlogs('vicidial_user_log',$valuetolog);
                     
                 $this->gosearch->asteriskDB->trans_complete();
 
                 if($this->gosearch->asteriskDB->trans_status !== false){
                     die('Emergency logout complete make sure agent browser is closed');
                 }else{
                     die('Problem in attempt to logout agent '.$agents[0]->user);
                 }
             }
         }else{
             die("Agent ".$_POST['user']." is not logged in");
         }
     }



     /*
      * usersearch
      * search for users autocomplete callback
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function usersearch(){

         $level = $this->session->userdata('users_level');
         $this->gosearch->asteriskDB->select(array('full_name as label','user as value'));
         $this->gosearch->asteriskDB->where("(user like '%".$_POST['term']."%' OR full_name like '%".$_POST['term']."%')");
         if($level < 9){
             $this->gosearch->asteriskDB->where(array('user_group'=>$this->session->userdata('user_name')));
         }
         $users = $this->gosearch->asteriskDB->get('vicidial_users')->result(); 

         $data['result'] = json_encode($users);
  
         $this->load->view('go_search/json_text.php',$data);

     }


     /*
      * batchupdate
      * update batch of users
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function batchupdate(){
        $username = $this->session->userdata('user_name');
        if(empty($username)){
            die("Error: Session expired kindly relogin");
        }
        $access = unserialize($this->session->userdata('useraccess'));
        # set access here
        if(in_array('modify_users',$access)){
             if(!empty($_POST['users'])){
                  $users = json_decode($_POST['users']);
                  $ctr = 0;
                  $results = array();
                  foreach($users as $user){
                     $result = $this->gosearch->updateuser(array($user->user_id=>array('active'=>$user->action,'users_id'=>$user->user)));
                     if(preg_match("/Error/",$result)){
                         $result = "Error: Something went wrong while saving user : $user->user";
                     }
                     $results[$ctr] = array("result"=>$result,"user"=>$user->user);
                     $ctr++;
                  }
                  echo json_encode($results);
             }else{
                die("Error: No users selected");
             }
        }else{
            die("Error:You are not allowed to modify this user");
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
