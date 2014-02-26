<?php
########################################################################################################
####  Name:             	gosearch.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Franco Hora	                                         	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Gosearch extends Model {
     /*public variable*/
     var $heirarchy_container=array(); 
     var $ctr=0; 
     var $tempgroupcreated = null; # puts value after clicking create group contains the created id returns to null if save 


     function __construct(){
         parent::Model();
	
	//$this->slaveDB = $this->load->database('slavedb',TRUE); 
	 
	 //if ($this->slaveDB == true){
	 //$this->asteriskDB = $this->load->database('slavedb',TRUE);
	 //}
	 //else{
	 $this->asteriskDB = $this->load->database('dialerdb',TRUE);	 
	 //}
	 
         //$this->a2billingDB =$this->load->database('billingdb',TRUE);
     }


     /*
      * collectusers 
      * collect all data(users) from vicidial_users
      * display all stats active or not
      * @author : Franco Hora <info@goautodial.com>
      * @param : $account > use as filter if not null
      */
     function collectusers($account=null){

       $level = $this->session->userdata('users_level');
   	$setable = $this->uri->segment(3);
        $secolumn = 'fullname';
        $sedata = $this->uri->segment(4);


          if(!empty($_POST['search'])){

              if(eregi("=",$_POST['search'])){
                
                  $keys = explode("&",$_POST['search']);
                  foreach($keys as $search_item){
                      $details = explode("=",$search_item);
                      if($details[0] != "daterange"){
                          if($details[0] != "rec"){
                              if(preg_match("/_name/",$details[0])){
                                  /*if($details[0] == "first_name"){
                                      $search .= "agent like '%{$details[1]}%' AND ";
                                  }else{
                                      $search .= "agent like '%{$details[1]}%' AND ";
                                  }*/
                                  $search .= "fullname like '%{$details[1]}%' AND ";
                              }else{
                                  $search .= $details[0] . "='" . $details[1] . "' AND ";
                              }
                          } else {
                              switch($details[1]){
                                  case "yes":
                                      $search .= "duration > '00:00:04' AND ";
                                  break;
                                  case "no":
                                      $search .= "duration = '00:00:00' AND ";
                                  break;
                                  default:
                                  break;
                              }
                          }
                      } else {
                          $_POST['daterange'] = $details[1]; 
                      }
                  }
                  #$search = substr($search,0,strlen($search)-5);
              } else {
                  $search = "(lead_id ='".$_POST['search'] . "' OR " 
                            . "phone like '%".$_POST['search'] ."%' OR "
                            . "agent like '%".$_POST['search'] ."%' "
                            .") AND ";
                  $_POST['daterange'] = date("Y-m-d") . "," . date("Y-m-d");
              }
	      
	      if(!empty($_POST['daterange'])) {

                  $dates = explode(",",$_POST['daterange']);
    
                  $datediff = strtotime($dates[1]) - strtotime($dates[0]);

                  if($datediff > 0){ 
                       $daterange = " date(call_date) BETWEEN '".$dates[0]."' AND '".$dates[1]."' AND";
                  } else {
                       $daterange = " call_date like '%{$dates[0]}%' AND ";
                  }

	          $groupId = $this->go_get_groupid();
	          if (!$this->commonhelper->checkIfTenant($groupId))
	          {
	              $ul='';
	          }
	          else
	          {  
	              $stringv = $this->go_getall_allowed_campaigns();
	              #$ul = " AND campaign_id IN ('$stringv') ";         
                      $ul = " AND user_group = '{$this->session->userdata('user_group')}'";
                      #$ul="";
	          }
              }
	      else
              {
	  
	          $groupId = $this->go_get_groupid();
	          if (!$this->commonhelper->checkIfTenant($groupId))
	          {
	              $ul='';
	          }
	          else
	          {  
	             $stringv = $this->go_getall_allowed_campaigns();
	             #$ul = " AND campaign_id IN ('$stringv') ";         
                     $ul = " AND user_group = '{$this->session->userdata('user_group')}'";
                     
	          }
	      }

          }
	  else
	  {
	  
	      #if(!empty($_POST['daterange'])) {

                   $daterange = " call_date like '%".date("Y-m-d")."%'";
	           $groupId = $this->go_get_groupid();
	           if (!$this->commonhelper->checkIfTenant($groupId))
	           {
	              $ul='';
	           }
	           else
	           {  
	              $stringv = $this->go_getall_allowed_campaigns();
	              #$ul = " and campaign_id IN ('$stringv') ";         
                      $ul = " AND user_group = '{$this->session->userdata('user_group')}'";
	           }

              #}
	      /*else
              {
	  
	           $groupId = $this->go_get_groupid();
	           if ($groupId == 'ADMIN' || $groupId == 'admin')
	           {
	                $ul='';
	           }
	           else
	           {  
	                $stringv = $this->go_getall_allowed_campaigns();
	                $ul = " campaign_id IN ('$stringv') ";         
	           }
	  
	      }*/
	  
	  
	  }


          if(!is_null($account)){
            
         	   /*$query = $this->asteriskDB->query("                  
                        SELECT DISTINCT
                            rec.lead_id as 'lead_id'
                            ,vl.phone_number as 'phone'
                            ,rec.start_time as 'call_date'
                            ,SEC_TO_TIME(rec.length_in_sec) as 'duration'
                            ,rec.user as 'agent'
                            ,vs.status_name as 'disposition'
                        FROM 
                            recording_log as rec
                            , vicidial_list as vl
                            , vicidial_lists as vls
                            , vicidial_statuses as vs
                        WHERE 
                            rec.lead_id=vl.lead_id
                            AND  vl.status = vs.status
                            AND rec.length_in_sec > 1
                            AND (CONCAT_WS(' ', vl.first_name, vl.last_name) = '$sedata' || $search)
                        ORDER BY
                            rec.start_time DESC;
                   ");*/
			   
			   $searchSQL = rtrim(rtrim("$daterange $search",' '),'AND');

                  /*var_dump("                  
                        SELECT
                            lead_id                            
                            ,phone
                            ,call_date
                            ,duration
                            ,agent
                            ,disposition
                            ,location
                            ,filename
			    ,recording_id
                        FROM 
                            goautodial_recordings_view_all
                        WHERE 
                            $searchSQL
			    $ul
                   ");die('debug');*/
         	  $query = $this->asteriskDB->query("                  
                        SELECT
                            lead_id                            
                            ,phone
                            ,call_date
                            ,duration
                            ,agent
                            ,disposition
                            ,campaign_dispo
                            ,location
                            ,filename
			    ,recording_id
                        FROM 
                            temp_recording_view
                        WHERE 
                            $searchSQL
			    $ul
                   ");

         	  /* $query = $this->asteriskDB->query("                  
                        SELECT DISTINCT
                            rec.lead_id as 'lead_id'
                            ,vl.phone_number as 'phone'
                            ,rec.start_time as 'call_date'
                            ,SEC_TO_TIME(rec.length_in_sec) as 'duration'
                            ,rec.user as 'agent'
                            ,vs.status_name as 'disposition'
			    ,rec.location as 'location'
			     ,rec.filename as 'filename'
                        FROM 
                            recording_log as rec
                            , vicidial_list as vl
                            , vicidial_lists as vls
                            , vicidial_statuses as vs
                        WHERE 
                            rec.lead_id=vl.lead_id
                            AND  vl.status = vs.status
                            AND rec.length_in_sec > 1
                            $search
                            $daterange
                        ORDER BY
                            rec.start_time DESC;
                   ");*/

	 
#	   $users   = $query->result();
           
           
              #$users = $this->asteriskDB->get(); 
              $collectedusers = array(); 
              $ctr = 0;
              foreach($query->result() as $info){
                  $collectedusers[$ctr] = $info;
                  $ctr++;
              }
              #$collectedusers = array();
          }else{
              //$groups = $this->asteriskDB->get('a2billing_wizard');
              $collectedusers = array(); 
              $ctr = 0;
              foreach($groups->result() as $group){
                  $users = $this->asteriskDB->get_where('vicidial_users',array('user_group'=>$group->account_num));  
                  foreach($users->result() as $info){
                      $collectedusers[$ctr] = $info;
                      $ctr++;
                  }
              }
          }
         
          return $collectedusers;
     }


     /*
      * getallusergroup 
      * collect all usergroup from vicidial_users_group
      * display all stats active or not
      * @author : Franco Hora <info@goautodial.com>
      * @param : $account > use as filter if not null
      */
     function getallusergroup($account=null){

         if(!is_null($account)){
              //$usergroups = $this->asteriskDB->get_where('a2billing_wizard',array('account_num'=>$account));
              $collectedusergroups = array(); 
              $ctr = 0;
              foreach($usergroups->result() as $info){
                  $collectedusergroups[$ctr] = $info;
                  $ctr++;
              }            
         }else{
              #$usergroups = $this->asteriskDB->get('vicidial_users');
              //$usergroups = $this->asteriskDB->get('a2billing_wizard');
              $collectedusergroups = array(); 
              $ctr = 0;
              foreach($usergroups->result() as $info){
                  $collectedusergroups[$ctr] = $info;
                  $ctr++;
              }            
         }

         return $collectedusergroups;
     }    


     /*
      * insertuser 
      * insert data to tables
      * @author : Franco Hora <info@goautodial.com>
      * @param : $table > table to be use
      *          $data > data columns 
      */
     function insertuser($table=null,$data=null,$condition=null){

          if(!is_null($table) && !is_null($data) ){
               if($this->errortrapping($data)){
                   if($this->checkduplicate($table,array($condition))){
                       $result = $this->asteriskDB->insert($table,$data);
                       return $result;
                   }
               }
          }else{
               die("Something went wrong contact your Support");
          }
     }

     /*
      * errortrapping 
      * check data to be inserted if allowed
      * @author : Franco Hora <info@goautodial.com>
      * @param : $data > data columns to be checked
      */
     function errortrapping($data){
         
           # array of fields to be check
           $fields  = array('user','pass','user_group'); 
           $valid = false;
           
           foreach($data as $col => $value){
               if(in_array($col,$fields)){
                   switch($col){
                       case 'user':
                            if(strlen($value) >= 2){
                                if(preg_match('/^[a-z0-9\_]+$/i',$value)){
                                    $valid = true;
                                }else{
                                    die('Invalid user format');
                                }
                            }else{
                                die('Invalid user format');
                            }
                       break;
                       case 'pass':
                            if(strlen($value) >= 2){
                                if(preg_match('/^[a-z0-9\_\'\-\@\#\$\%\^\&\*\!\.\,\"\(\)]+$/i',$value)){
                                    $valid = true;
                                }else{
                                    die('Invalid password');
                                }
                            }else{
                                die('Invalid user format');
                            }
                       break;
                       case 'user_group':
                             if(strlen($value) > 0){
                                 $valid = true;
                             }else{
                                 die('User group must not be empty');
                             }
                       break;
                   }
               }else{
                   $valid = true;
               }
           }

           return $valid;
            
     }

     /*
      * checkduplicate 
      * check data to be inserted if unique
      * @author : Franco Hora <info@goautodial.com>
      * @param : $table > table to be checked
      *          $queryconditions > conditions to check an array containing of all array conditions
      *                             format $queryconditions(array('field'=>'value'),array('field2'=>'value2'));
      *                                                     to check data entry    ,id to check
      *          $self > array containing condition to check if editing a user account
      */
     function checkduplicate($table=null,$queryconditions=array(),$self=array()){

         # check fields info if allow null don't check null
         $fieldsinfo=$this->asteriskDB->query("show columns from $table");
         if($fieldsinfo->num_rows > 0){ 
             $ctr=0;
             foreach($fieldsinfo->result() as $info){ # loop of table fields
                     # check if its array
                     if(is_array($queryconditions)){
                         # loop lets check if all conditions are not empty
                         foreach($queryconditions as $conditions){ 
                             if(is_array($conditions)){ # check if supplied condition is an array
                                 if(key($conditions) == $info->Field){
                                     if($info->Null === "YES" && (is_null($conditions[key($conditions)]) || empty($conditions[key($conditions)])) ){ 
                                         # meaning the column is allowed to have null value and its empty so nothing to check 
                                         $resultsofcheck["YES"] = $conditions;
                                     }elseif($info->Null === "NO" && (is_null($conditions[key($conditions)]) || empty($conditions[key($conditions)])) ){
                                         # meaning the column is not allowed to have null value and its empty too so return error
                                         $resultsofcheck["NO"] = "Error: `$info->Field` must not be empty";
                                     }else{
                                         # meaning the column is either YES or NO and has array condition to check return conditions
                                         $resultsofcheck[$ctr] = $conditions;
                                     }
                                     $ctr++;
                                 }
                             }else{ # else report error 
                                 die('Error: supplied condition not in an array');
                             }
                         }
                         
                     }else{
                         die('Error: conditions are empty or not an array');
                     }
             }
             if(array_key_exists("NO",$resultsofcheck)){
                  # meaning error on process
                  die($resultsofcheck["NO"]);
             }else{
                  # meaning conditions accepted it has YES and has conditions
                      # now lets create our conditions
                      foreach($resultsofcheck as $index => $createcondition){
                          # if index NOT YES meaning escape the column
                          if(preg_match('/^[0-9]+$/i',$index)){ # check if index is number it means this are conditions
                               $this->asteriskDB->where($createcondition);
                          }
                      }
                      if(!empty($self)){ # add this condition to check in editing a user
                          $this->asteriskDB->where(key($self),reset($self));
                      }
                      $result = $this->asteriskDB->get($table);
                      if($result->num_rows() > 0){
                           die('Duplicate entry on `'.key($createcondition)."` at table: $table");
                      }else{
                           return true;
                      }
             }

         }else{
             die('Error: Table not exist or fieldset empty!');
         }
 
     }
      function go_getall_allowed_campaigns()
      {
	    $groupId = $this->go_get_groupid();	 
	    $query_date =  date('Y-m-d');
	    $query = $this->asteriskDB->query("select trim(allowed_campaigns) as qresult from vicidial_user_groups where user_group='$groupId'"); 
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
      
    function go_get_groupid()
      {
      	    $userID = $this->session->userdata('user_name');
	    $query = $this->asteriskDB->query("select user_group from vicidial_users where user='$userID'"); 
	    $resultsu = $query->row();
	    $groupid = $resultsu->user_group;	 
	    return $groupid;
      }
      
      
     /*
      * bygroup
      * collect all users in array bygroup
      * @author : Franco Hora <info@goautodial.com>
      */
     function bygroup($username){

          #$username = $this->session->userdata('user_name');
          $groupings = array();
          $collector = $this->retrievedata('go_users',array('users_id'=>$username));
          # collect
          $ctr =0;
          $firstlevel = $this->retrievedata('go_users',array('users_group'=>$collector[0]->id));
          if(count($firstlevel) > 0){ # has underlings
              foreach($firstlevel as $firstlevelgroup){
                   if(in_array($firstlevelgroup->users_level,array(1,2,3,4,5,6,7))){ # users currently logged is in level 8
                       $agents[$ctr]=array($firstlevelgroup);
                   }else{
                       if($firstlevelgroup->users_level == 8){ # user currently logged is in level 9
                           $ctr1=0;
                           $firstgroup=array();
                           $secondlevel = $this->retrievedata('go_users',array('users_group'=>$firstlevelgroup->id));
                           foreach($secondlevel as $secondlevelgroup){
                                $firstgroup[$ctr1] = $secondlevelgroup;
                                $ctr1++;
                           }
                           $agents[$ctr] = array($firstlevelgroup,$firstgroup);
                       }else{
                           # this part means you are in level 10
                           $ctr2=0;
                           $secondgroup = array();
                           $secondlevel = $this->retrievedata('go_users',array('users_group'=>$firstlevelgroup->id));
                           foreach($secondlevel as $secondlevelgroup){
                                $thirdlevel = $this->retrievedata('go_users',array('users_group'=>$secondlevelgroup->id));
                                $secondgroup[$ctr2] = array($secondlevelgroup,$thirdlevel);
                                $ctr2++;
                           }
                           $agents[$ctr]=array($firstlevelgroup,$secondgroup);
                       }
                   }
                   $ctr++;
              }
              $groupings = array($collector[0],$agents);
          }elseif($collector[0]->users_level == 7){ # user currently logged is in level 7 or the IT/SUP/TL
              $groupings = array($collector,$this->retrievedata('go_users',array('users_group'=>$collector[0]->users_group),array(),array(array('id !='=>$collector[0]->id)))); 
          }else{
              $groupings = array();
          }
          return $groupings;
     }


     /*
      * retrievedata
      * function to collect data use for simple retrieving of data in asterisk cant use "or" statement here
      * @author: Franco Hora <info@goautodial.com>
      * @param: $table > <string> name of table 
      *         $conditions > <array> array condition if null then select all otherwise use condition
      *         $fetchthis > array containing specified fields to query
      *         $addconditions > array containing arrays of secondary to nth db condition
      *         $orderby > array containing order 
      */
     function retrievedata($table=null,$conditions=array(),$fetchthis=array(),$addconditions=array(),$orderby=array()){
         if(!is_null($table)){

             if(is_array($addconditions) && !empty($addconditions)){
                 foreach($addconditions as $additional){
                     if(is_array($additional)){
                         $this->asteriskDB->where($additional);
                     }else{
                         var_dump($additional);
                         die('Conditions must be an array');
                     }
                 }
             }

             if(!is_null($orderby) && !empty($orderby)){
                  foreach($orderby as $order){
                      if(is_array($order)){
                          foreach($order as $field => $byorder){
                               $this->asteriskDB->order_by($field,$byorder);
                          }
                      }else{
                          $this->asteriskDB->order_by($order);
                      }
                  }
             }



             if(count($conditions)==0){

                 if(!empty($fetchthis)){
                     $this->asteriskDB->select($fetchthis);
                     $retrievingdata = $this->asteriskDB->get($table);
                 }else{
                     $retrievingdata = $this->asteriskDB->get($table);
                 }
                 if($retrievingdata->num_rows > 0){
                      return $retrievingdata->result();
                 }else{
                      return array();
                 }
             }else{
                 if(!empty($fetchthis)){
                     $this->asteriskDB->select($fetchthis);
                     $retrievingdata = $this->asteriskDB->get_where($table,$conditions);
                 }else{
                     $retrievingdata = $this->asteriskDB->get_where($table,$conditions);
                 }
                 if($retrievingdata->num_rows > 0){
                     return $retrievingdata->result();   
                 }else{
                     return array();
                 }
             }
         }else{
            die('Something went wrong sorry');
         }
     }


     /*
      * updateuser
      * function to update user
      * @author : Franco Hora <info@goautodial.com>
      * $param : $fields > array of fields to update
      */
     function updateuser($fields=array(),$updatestat='quick'){
         # set permission here not yer applied
         # if(in_array(MODIFYUSER,$permissionarray)){ user allowed to update this is just a sample
               foreach($fields as $userid => $fieldsarray){
                   # create data for go_users and vicidial_users 
                   foreach($fieldsarray as $cols => $fieldsval){
                       $columnInfo = $this->asteriskDB->query("show columns from vicidial_users where field = '$cols'");
                       if($columnInfo->num_rows > 0){
                            $vicidialusers[$cols] = $fieldsval;
                       }
                   } 
                   #if($this->checkduplicate('vicidial_users',array(array('phone_login'=>$fields[key($fields)]['phone_login'])),array('user_id !='=>key($fields)))){
                       # set transaction for rollback if ever fail entry occurs
                       $this->asteriskDB->trans_start(); 
                           # save data in go_users
                           #$this->asteriskDB->where('vicidial_user_id',$userid);
                           #$this->asteriskDB->update('go_users',$gosearch);
                           # save data in vicidial_users
                           $this->asteriskDB->where('user_id',$userid);
                           $this->asteriskDB->update('vicidial_users',$vicidialusers);
                       $this->asteriskDB->trans_complete();
                       if ($this->asteriskDB->trans_status() === FALSE){
                           $toreturn = "Error on updating User!";
                       }else{
                           $toreturn = "Updated successful!";
                       }

					   //,'a2billing'=>$this->a2billingDB->queries
                       $queriestoaudit = array('asterisk'=>$this->asteriskDB->queries);
                       $this->commonhelper->auditadmin($queriestoaudit,$this->session->all_userdata(),'MODIFY','USERS',$fields[key($fields)]['users_id']);
 
                       return $toreturn;
 
                   #}else{
                   #    return "Duplicate Phone Login";
                   #}
               }
        # }else{
              # die ("You are not allowed to update user");
         #}
     }


     /*
      * createusergroup
      * function to create a new user group
      * @author: Franco Hora <info@goautodial.com>
      * @param: id of the current user logged in
      */
     function createusergroup($currentuser=null){
         if(!is_null($currentuser)){

             #logged in user level get from session
             $groupLevel = $this->session->userdata('group_level');
     

             #let's get information of heirarchy to create owner_group where our format would be ownerid[_rellseruserid[_signupid]]
             $heirarchy = $this->getheirarchy($currentuser); 
             $currentuser = $this->retrievedata('go_users',array('vicidial_user_id'=>$currentuser));
             $heirarchy[$this->ctr++] = $currentuser;
             #create group owner 
             $createdowner_group=null;
             foreach($heirarchy as $users){
                 $createdowner_group .= $users[0]->users_id."_";
             }
             # remove trailing underscore
             $createdowner_group = substr($createdowner_group,0,strlen($createdowner_group)-1);
             # set transaction if ever interference occur CI rollback data
             $this->asteriskDB->trans_start(TRUE);
                 $result = $this->asteriskDB->insert('go_groupaccess',array('group_owner'=>$createdowner_group)); 
                 # we put value to $this->tempgroupcreated
                 $this->tempgroupcreated = $this->asteriskDB->insert_id() ;
             $this->asteriskDB->trans_complete();
             if($result){

                 # switch to interchange the options
                 $options =array(4=>'Administrator',3=>'Manager',2=>'IT/Sup/TL',1=>'Agents');
                 # remove the must not see levels
                 if(!empty($currentuser)){
                     foreach($options as $key => $titles){
                         if($groupLevel == 10){
                             # do nothing
                         }else{
                             if($key >= $groupLevel){
                                 unset($options[$key]);
                             }
                         }
                     } 
                 }

                 echo "<div id='user-groups-".$this->tempgroupcreated."--$createdowner_group' class='new-user-groups'>
                           <h3>Group Configuration</h3>
                           <div class='adv-allowed-access user-corners new-allowed-access'>
                                <form id='new-group-".$this->tempgroupcreated."'>
                                <div class='leftcol'>Group Name</div>
                                <div class='rightcol'><input type='text' name='groupaccess_name-".$this->tempgroupcreated."' id='group_name-".$this->tempgroupcreated."' size='16'></div>
                                <div class='leftcol'>Group level</div>
                                <div class='rightcol'>".form_dropdown('group_level-'.$this->tempgroupcreated,$options,null,"id='group_level-".$this->tempgroupcreated."'")."</div>
                                <br class='clear'/>
                                <div id='new-allowed-access' class='new-access'></div>
                                <br class='clear'/>
                                <div class='group-action'>
                                     <a href='javascript:void(0);'>Add access</a> |
                                     <a href='javascript:void(0);'>Remove access</a>
                                </div>
                                </form>
                           </div>
                       </div>"; 
             }else{
                 die("Something went wrong on creating new group");
             }
         }else{
             die("Something went wrong on saving new group");
         }
     }


     /*
      * getheirarchy
      * get the upper hand of the heirarchy
      * @author: Franco Hora <info@goautodial.com>
      * @param : $userid > id of the user
      */
     function getheirarchy($userid){
         $result = $this->retrievedata('go_users',array('vicidial_user_id'=>$userid));
         if(!empty($result)){
             #get the higher level 
             if($result[0]->users_level < 10){ # get until reseller user_id
                  $higherowner = $this->retrievedata('go_users',array('id'=>$result[0]->users_group));
                  $this->heirarchy_container[$this->ctr] = $higherowner;
                  $this->ctr++;
                  $this->getheirarchy($higherowner[0]->vicidial_user_id);
             } 
         }else{
            $this->heirarchy_container;
         }
         return array_reverse($this->heirarchy_container);
     }


     /*
      * saveusergroup
      * function to save user group
      * @author: Franco Hora <info@goautodial.com>
      * @param : $postfields > array containing the post variables
      *          $groupid > id to be update
      */
     function saveusergroup($postfields=array(),$groupid){
        if(!empty($postfields)){
            # prepare of data entry
            $vicidial_user_id = null;
            foreach($postfields as $carier => $postfield){
                $vicidial_user_id = $carier;
                if(array_key_exists('useraccess_id',$postfield)){
                    if(is_array($postfield['useraccess_id'])){
                        $postfields[$carier]['useraccess_id'] = serialize($postfield['useraccess_id']);
                    }
                }
            }
            # begin saving data 
            $this->asteriskDB->trans_start();
                $this->asteriskDB->where(array('id'=>$groupid));
                $this->asteriskDB->update('go_groupaccess',$postfields[$vicidial_user_id]);
            $this->asteriskDB->trans_complete();

            if ($this->asteriskDB->trans_status() === FALSE){
                die('Something went wrong contact your support');
            }else{
                echo 'Saved';
            }

        }else{
            die("Fields are empty");
        }
     }


     /*
      * retrievegroup
      * function to retrieve groups 
      * @author : Franco Hora <info@goautodial.com>
      * @param : $userid > id of the group owner
      *          $conditions  > condition filters
      *          $orders  > order to display
      */
     function retrievegroup($userid,$conditions=array(),$orders=null){
         if(is_array($conditions)){
             foreach($conditions as $condition){
                  $this->asteriskDB->where($condition);
             }
         }

         if(!is_null($orders)){
             if(is_array($orders)){
                 foreach($orders as $orderkey => $ordervalue){
                     $this->asteriskDB->order_by($orderkey,$ordervalue);
                 }
             }
         }

         return $this->asteriskDB->get('go_groupaccess')->result();
     }

     /*
      * deleteitem
      * funciton to permanently delete item
      * @author: Franco Hora <info@goautodial.com>
      * @param : $condition what condition needed to delete the item must be array
      *          $table table where to delete the item
      *          $msg = true if you want a echo message
      */
     function deleteitem($table,$condition,$msg=false){
         if(!empty($condtion) || !is_null($condition)){
             if(is_array($condition)){
                 foreach($condition as $conditions){
                     if(is_array($conditions)){
                         $this->gouser->asteriskDB->where($conditions);
                     }else{
                         die('Wrong condition format');
                     }
                 }
             }else{
                 $this->gouser->asteriskDB->where($condition);
             }
             $this->gouser->asteriskDB->delete($table);
             if($msg){
                 echo "Item deleted";
             }
         }else{
             die('Must have condition to delete an item');
         }
     }

     /*
      * getallowedcampaign
      * function to get all allowed campaigns in vicidial_user_groups
      * used in vicidial process usually
      * @author: Franco Hora <info@goautodial.com> 
      * @param : $usergroup > account/user_group
      *          $isSlave > check what to use 
      */
     function getallowedcampaign($usergroup=null,$isSlave=false){
         if($usergroup){
             if(!$isSlave){
                 $this->asteriskDB->where(array('user_group'=>$usergroup));   
                 $viciUsergroup = $this->asteriskDB->get('vicidial_user_groups')->result();
                 $campaigns = explode(" ",trim(str_replace("-","",$viciUsergroup[0]->allowed_campaigns)));
             }else{
                 $this->slaveDB->where(array('user_group'=>$usergroup));   
                 $viciUsergroup = $this->slaveDB->get('vicidial_user_groups')->result();
                 $campaigns = explode(" ",trim(str_replace("-","",$viciUsergroup[0]->allowed_campaigns)));
             }
             return $campaigns;
         }else{
             die('Error on passing user account or user group');
         }
     }

}

?>
