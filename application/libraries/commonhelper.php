<?Php
########################################################################################################
####  Name:             	commonhelper.php                                                    ####
####  Type:             	ci libraries/helper - administrator                                 ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Writeen by:      	        Franco E. Hora                                                      ####
####  Edited by:	        GoAutoDial Development Team                                         ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


Class Commonhelper{


    #protected $CI_class &= get_instance();



    /*
     * postToarray
     * converts $_POST to an array of variable
     * @author : Franco Hora <info@goautodial.com>
     * @param : postdata > receive $_POST
     */
    public function postToarray($postdata=null){

         if(!is_null($postdata)){

             foreach($_POST as $key => $value){
                 $data[$key] = $value;
             }
 
         }else{
             $data =array();
         }
        

         return $data;
    }

    /*
     * writetofile
     * mehod to write file
     * @author : Franco Hora <info@goautodial.com>
     * @param : $dir > directory of file to be written 
                $filename > file to be written
                $texttowrite > text to write in  the file
     */
    public function writetofile($dir=null,$filename=null,$texttowrite=null){
        if(!is_null($dir) && !is_null($filename) && !is_null($texttowrite)){
            # begin writting on file
            $file = $this->openfile($dir,$filename);
            #chown($dir.$filename,'apache');
            #chmod($dir.$filename,0777);
            fwrite($file,$texttowrite);
            fclose($file);
        }else{
            die('variables are empty');
        }
    }

  
    /*
     * openfile
     * method to open the file
     * @author : Franco Hora <info@goautodial.com>
     * @param : $dir > directory of file to be written
                $filename > file to be written
     */
    public function openfile($dir=null,$filename=null){
        if(!is_null($dir) && !is_null($filename)){ 
            $handler = @fopen($dir.$filename, "a"); 
            if($handler){ # there is a file and we opened it oh yess!
                return $handler;
            }else{
                die("Oh no file $filename could not be found");
            }
        }else{
            die('dir and filename variables are empty');
        }
    }


    /*
     * auditoadmin
     * use to audit trail our admin 
     * God Bless the Phillippines 
     * Programming It's More Fun in the Philippines
     * @author : Franco Hora <info@goautodial.com>
     * @param : $auditthis > array of queries
     *          $sessiondata > session data for some info
     *          $action > action executed by our current logged in user
     *          $event_section > in what ui the action is
     *          $record_id > the id using
     */
    function auditadmin($action,$details=null,$dbquery=null,$uname=null){

        $CI =& get_instance();
  
        $this->goDB = $CI->load->database('goautodialdb',TRUE);

        $sqldate = date("Y-m-d H:i:s");
        if (!is_null($uname)) {
            $user = $uname;
        } else {
             $user = $CI->session->userdata('user_name');
        }
        if(!empty($action)){
            $data['user'] = $user;
            $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $data['event_date'] = $sqldate;
            $data['action'] = $action;
            $data['details'] = mysql_real_escape_string($details);
            $data['db_query'] = mysql_real_escape_string($dbquery);

            $this->goDB->trans_start();
                 $this->goDB->insert('go_action_logs',$data);
            $this->goDB->trans_complete();

            //if ($action == "LOGIN")
            //{
            //   $query = $this->goDB->query("SELECT * FROM go_last_login WHERE account_num='$user'");
            //   $is_exist = $query->num_rows();
            //   
            //   if ($is_exist > 0)
            //   {
            //      $query = $this->goDB->query("UPDATE go_last_login SET event_time='$sqldate' WHERE account_num='$user'");
            //   } else {
            //      $query = $this->goDB->query("INSERT INTO go_last_login VALUES('$user','$sqldate')");
            //   }
            //}
        }else{
            die('Something went wrong while saving data sorry');
        }

    }

//     function auditadmin($auditthis=array(),$sessiondata=array(),$action=null,$event_section=null,$record_id=null){
// 
//         $CI =& get_instance();
//   
//         $this->asteriskDB = $CI->load->database('dialerdb',TRUE);
// 
//         $sqldate = date("Y-m-d H:i:s");
//         if(!empty($auditthis)){
//             $data['event_date'] = $sqldate;
//             $data['user'] = $sessiondata['user_name'];
//             $data['ip_address'] = $sessiondata['ip_address'];
//             $data['event_section'] = $event_section;
//             $data['event_type'] = $action;
//             $data['record_id'] = $record_id;
//             $data['event_code']  = $sessiondata['user_name'] ." ".$action ." ".$event_section;
//             # create data to insert in our vicidial_admin_log this is the query data
//             $data['event_sql'] = "";
//             $data['event_notes'] = "";
//             foreach($auditthis as $query){
//                 if(is_array($query)){
//                     foreach($query as $event_sql){
//                         $data['event_sql'] .= $event_sql.";";
//                     }
//                 } 
//             }
//             $this->asteriskDB->trans_start();
//                  $this->asteriskDB->insert('vicidial_admin_log',$data);
//             $this->asteriskDB->trans_complete();
//         }else{
//             die('Something went wrong sorry');
//         }
// 
//     }


    /*
     * auditlogs
     * function used to insert in log for vicidial
     * @author: Franco Hora <info@goautodial.com>
     * @param: $table
     */
    function auditlogs($table=null,$fields=array()){

        // reinstantiate the object
        $CI =& get_instance();
        $this->asteriskDB = $CI->load->database('dialerdb',TRUE);

        if(!is_null($table)){
            if(!empty($fields)){
               $this->asteriskDB->trans_start();
                  $this->asteriskDB->insert($table,$fields); 
               $this->asteriskDB->trans_complete(); 
            }else{
                die('You have empty fields');
            }
        }else{
            die('Please define database table');
        }
    }
    

    /*
     * paging
     * function used to paginate a certain list of data from a query
     * @author: Chris Lomuntad <chris@goautodial.com>
     * @param: $page  > number of the current page and not the limit and start values usually used in sql queries
     *         $rp    > results per page
     *         $total > total number of results
     *         $limit > number of page values you want to display
     */
     function paging($page,$rp,$total,$limit)
     {
          $limit -= 1;
     
          $mid = floor($limit/2);
          
          if ($total>$rp)
               $numpages = ceil($total/$rp);
          else
               $numpages = 1;
          
          if ($page>$numpages) $page = $numpages;
     
          $npage = $page;
     
          while (($npage-1)>0&&$npage>($page-$mid)&&($npage>0))
               $npage -= 1;
          
          $lastpage = $npage + $limit;
          
          if ($lastpage>$numpages) 
          {
               $npage = $numpages - $limit + 1;
               if ($npage<0) $npage = 1;
               $lastpage = $npage + $limit;
               if ($lastpage>$numpages) $lastpage = $numpages;
          }
          
          while (($lastpage-$npage)<$limit) $npage -= 1;        
          
          if ($npage<1) $npage = 1;
              
          $paging['first'] = 1;
          if ($page>1) $paging['prev'] = $page - 1; else $paging['prev'] = 1;
          $paging['start'] = $npage;
          $paging['end'] = $lastpage;
          $paging['page'] = $page;            
          if (($page+1)<$numpages) $paging['next'] = $page + 1; else $paging['next'] = $numpages;
          $paging['last'] = $numpages;
          $paging['total'] = $total;
          $paging['iend'] = $page * $rp;
          $paging['istart'] = ($page * $rp) - $rp + 1;
          
          if (($page * $rp)>$total) $paging['iend'] = $total;
          
          return $paging;    
     }

   
    /*
     * simpleretrievedata
     * function to retrieved data using simple query statement using asterisk database
     * returns MySQL object result 
     * @author: Franco Hora <info@goautodial.com>
     * @param: $table > string value of table name to use 
     *         $fields > array of fields to specify fields
     *         $join > array of tables to join the table format of variable must be 2 dimensional array,
     *                 e.g.(array(array('tableName','tableName.field=$table.field'[,type of join(left,right,empty means inner)])))
     *         $conditions > array of condtions to be used
     *         $orderby > array or orders
     *         $limit > recieves int value of limit
     */
    function simpleretrievedata($table=null,$fields=array(),$join=array(),$conditions=array(),$orderby=array(),$limit=array()){
        // reinstantiate the object
        $CI =& get_instance();
        // load asterisk database
        $this->asteriskDB = $CI->load->database('dialerdb',TRUE);
        if(!is_null($table) && !empty($table)){
            $this->asteriskDB->trans_start();
                 if(!empty($fields) && !is_null($fields)){
                      $this->asteriskDB->select($fields, FALSE);
                 }
                 if(!empty($join) && !is_null($join)){
                     if(is_array($join)){
                          foreach($join as $tableToJoin){
                               if(is_array($tableToJoin)){
                                    if(count($tableToJoin)<=3){
                                        $this->asteriskDB->join($tableToJoin[0],$tableToJoin[1],$tableToJoin[2]);
                                    }else{
                                        die("Array variable must be 3 index only");
                                    }
                               }else{
                                    die("Error values must be array");
                               }
                          }
                     }else{
                          die("Error please use array variable");
                     }
                 } 
                 if(!empty($conditions) && !is_null($conditions)){

                     if(is_array($conditions)){
                         foreach($conditions as $condition){
                              $this->asteriskDB->where($condition);
                         }
                     }else{
                         $this->asteriskDB->where($conditions);
                     }
                 }
                 if(!empty($orderby) && !is_null($orderby)){
                     if(is_array($orderby)){
                          foreach($orderby as $orders){
                              if(is_array($orders)){
                                  if(count($orders) == 2){
                                      $this->asteriskDB->order_by($orders[0],$orders[1]);
                                  }else{
                                      die("Error please specify fields and order");
                                  }
                              }else{
                                  $this->asteriskDB->order_by($orders);
                              } 
                          }
                     }else{
                          die("Error please use array variable");
                     }
                 }
                 if(!empty($limit) && !is_null($limit)){
                      if(is_array($limit)){
                          foreach($limit as $limits){
                              if(!empty($limits)){
                                  if(is_string($limits)){
                                     $tonum = explode(",",$limits);
                                     $this->asteriskDB->limit(($tonum[0]*1),($tonum[1]*1));  
                                  }else{
                                     $this->asteriskDB->limit($limits);
                                  }
                              }else{
                                  die("Please use specify your data");
                              }
                          }
                      }else{
                           $this->asteriskDB->limit($limit);
                      }
                 }
                 $result = $this->asteriskDB->get($table);
            $this->asteriskDB->trans_complete();

            if($this->asteriskDB->trans_status !== false){
                  return $result;
            }else{
                  die("Error while retrieving data");
            }
        }else{
            die("Error please specify table to be use");
        }
    }



    /* 
     * getallowablecampaign
     * get allowable campain from vicidial_user_groups
     * @author: Franco E. Hora <info@goautodial.com>
     * @param: $group > group of user
     *         $select > if true converts your allowable campaign result to select type
     */
    function getallowablecampaign($group=null,$select=false,$conditions=array()){

        // reinstantiate the object
        $CI =& get_instance();
        // load asterisk database
        $this->asteriskDB = $CI->load->database('dialerdb',TRUE);

        if(!is_null($group)){
            $this->asteriskDB->where('user_group',$group);
        }else{
            die("Error: Please specify group");
        }
        $this->asteriskDB->select('allowed_campaigns'); 
        $result = $this->asteriskDB->get('vicidial_user_groups');
        if($result->num_rows > 0){
            foreach($result->result() as $groupinfo){
                $allowedcampaigns = trim(trim($groupinfo->allowed_campaigns, "-"));
            }
            $campaigns = explode(" ",$allowedcampaigns);
            # if select is true override campaigns to be returned
            if($select){
                $this->asteriskDB->where_in('campaign_id',$campaigns);
                if(!is_null($conditions) && !empty($conditions)){
                    if(is_array($conditions)){
                        foreach($conditions as $condition){
                            $this->asteriskDB->where($condition);
                        }
                    }else{
                        $this->asteriskDB->where($conditions);
                    }
                }
                $selectsOn = $this->asteriskDB->get('vicidial_campaigns');
                if($selectsOn->num_rows > 0){
                    foreach($selectsOn->result() as $campaignInfo){
                        $newcampaigns[$campaignInfo->campaign_id] = "($campaignInfo->campaign_id)".$campaignInfo->campaign_name;
                    }
                }else{
                    $newcampaigns = array();
                }
                $campaigns = $newcampaigns;
            }
        }
        return $campaigns;
    }

    /*
     * hostedaccounts
     * get the accounts which is alive in a2billing and vicidial
     * @author : Franco E. Hora <info@goautodial.com>
     */

    function hostedaccounts(){

         $accounts = $this->simpleretrievedata('a2billing_wizard',
                                               null,
                                               null,
                                               "active = 'Y'"
                                              );
         return $accounts->result(); 
    }

 
    function statusstringconvert($status=null){
         if(!is_null($status)){
             if($status == "Y"){
                 $returnThis = "<span class='active'>Active</span>";
             }else{
                 $returnThis = "<span class='inactive'>Inactive</span>";
             }
         }else{
             $returnThis = "";
         }
         return $returnThis;
    }


    /*
     * deletesession
     * remove from remember me and redirect to login
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function deletesession($ip_address){

         $CI =& get_instance();
         $asteriskdb = $CI->load->database('dialerdb',TRUE);
         $goautodialdb = $CI->load->database('goautodialdb',TRUE);

         $usernamehash = $CI->input->cookie('ci_userhash',true);
 
         # look if keep me login is on
         $goautodialdb->where(array('usernamehash'=>$usernamehash)); 
         $goautodialdb->where(array('ip_address'=>$ip_address)); 
         $remembered = $goautodialdb->get('go_remember');

         if($remembered->num_rows() == 0){

             # you are not in remember me delete data
             $goautodialdb->where(array('usernamehash'=>$usernamehash)); 
             $goautodialdb->where(array('ip_address'=>$ip_address));
             $user_data = $goautodialdb->delete('go_remember');
             redirect($CI->config->item('base_url'));

         } else {

             $remember_info = $remembered->result();
             $asteriskdb->where(array('user'=>$remember_info[0]->username));
             $user_info = $asteriskdb->get('vicidial_users');

             if($user_info->num_rows() > 0){

                  $user_detail = $user_info->result();

                  # get useraccess
                  $access = $asteriskdb->get('go_useraccess')->result();
                  if(!empty($user_detail)){

                       foreach($user_detail as $details){

                            if(!empty($access)){

                                    // get all enable in the object
                                    $ctr = 0;
                                    foreach($access as $fields){

                                        $col = $fields->vicidial_users_column_name;
                                        if(is_numeric($details->{$col})){

                                             if($details->$col > 0){

                                                 $useraccess[$ctr] = $fields->vicidial_users_column_name;

                                             }

                                        } else {

                                             if($details->{$col} != 'DISABLED' && $details->{$col} != 'NEVER' &&
                                                $details->{$col} != 'NOT_ACTIVE' && $details->{$col} != 'N')
                                             {

                                                 $useraccess[$ctr] = $fields->vicidial_users_column_name.":".$info->$col;

                                             }

                                        }
                                        $ctr++;

                                    }

                                    $data['useraccess'] = serialize($useraccess);

                            } else {

                                $data['useraccess'] = serialize(array());

                            }

                       }

                       $data['users_level'] = $details->user_level;
                       $data['user_group'] = $details->user_group;
                       $data['full_name'] = $details->full_name;
                       $data['user_name'] = $details->user;
                       $data['user_pass'] = md5($details->pass);
                       $data['is_logged_in'] = true;
                       $data['remeber_me'] = 1;

                  }
                  

             } 

             $CI->session->set_userdata($data);
         }

    }

    /*
     * checkpermission
     * check if your permission
     * @author: Franco E. Hora <info@goautodial.com>
     * @param : $access > the permission to check in the session data
     */
    function checkpermission($access=null){

        if(!is_null($access) && !empty($access)){

            $CI =& get_instance();
            $permission_array = unserialize($CI->session->userdata('useraccess'));
            if(is_array($permission_array)){
                if(in_array($access,str_replace(":","",$permission_array))){
                
                    return true;

                } else {

                    die ("Error: You are not permited access this feature");

                }

            } else {

               die("Error: Sesssion Expired kindly re-login");

            }

        } else {

            die("Error: Please specify user access");
 
        }

    }
   
    /*
     * getsippy
     * process your sippy here
     * @param : $sippyMethod > string method for sippy
     *          $param > array parameters
                       > format array of scalarVal
                       > e.g.
                       >    array("i_account" => array('value', datatype))
     */
    function getsippy($sippyMethod,$param){

          include config_item('VARWWWPATH').'/sippysignup/html.php';
          include config_item('VARWWWPATH').'/sippysignup/xmlrpc/xmlrpc.inc';

          # xmlrpc for scalar vals
          $struct = array();
          if(is_array($param)){
              foreach($param as $keys => $scalars){
                   if(is_array($scalars)){
                          $struct[$keys] = new xmlrpcval($scalars[0],"{$scalars[1]}");
                   } else {
                       return "Error: Please input array on scalar values";
                   }
              }

              $params = array(new xmlrpcval($struct,'struct'));

          } else {
                return "Error: Please input array on scalar values";
          }

          $msg = new xmlrpcmsg($sippyMethod,$params);
          $_F=__FILE__;$_X='Pz48P3BocCAkY2w0ID0gbjV3IHhtbHJwY19jbDQ1bnQoJ2h0dHBzOi8vZDFsLmozc3RnMnYyNHAuYzJtL3htbDFwNC94bWwxcDQnKTsNCiA/Pg==';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
          $_F=__FILE__;$_X='Pz48P3BocCAkY2w0LT5zNXRDcjVkNW50NDFscygnajNzdGcydjI0cC1jNScsICdLMW0ydEU2YW91JywgQ1VSTEFVVEhfRElHRVNUKTsgPz4=';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));

   	  $cli->setSSLVerifyPeer(false);		

          $r = $cli->send($msg, 60);

          if($r->faultCode()){
               if($r->faultCode() != 200){
                     die("Fault Code:".$r->faultCode());
               }
          }

          return $r->value();

    } 
 

    /*
     * getAccountInfo
     * get sippy account info
     * @param : $type > either put i_account or username
     *          $value > the i_account or username value
     */
     function getAccountInfo($type,$value){
          switch ($type)
          {
               case "i_account":
                    $vtype = "int";
                    break;
               
               case "username":
                    $vtype = "string";
                    break;
          }

          include config_item('VARWWWPATH').'/sippysignup/html.php';
          include config_item('VARWWWPATH').'/sippysignup/xmlrpc/xmlrpc.inc';

          $params = array(new xmlrpcval(array($type => new xmlrpcval($value, $vtype)), "struct"));
          $msg = new xmlrpcmsg('getAccountInfo', $params);
          $_F=__FILE__;$_X='Pz48P3BocCAkY2w0ID0gbjV3IHhtbHJwY19jbDQ1bnQoJ2h0dHBzOi8vZDFsLmozc3RnMnYyNHAuYzJtL3htbDFwNC94bWwxcDQnKTsNCiA/Pg==';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));
          $_F=__FILE__;$_X='Pz48P3BocCAkY2w0LT5zNXRDcjVkNW50NDFscygnajNzdGcydjI0cC1jNScsICdLMW0ydEU2YW91JywgQ1VSTEFVVEhfRElHRVNUKTsgPz4=';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));

   	  $cli->setSSLVerifyPeer(false);		
         
          $r = $cli->send($msg, 12);
     
          if ($r->faultCode()) {
               if ($r->faultCode() != 400) {
                    error_log("Fault. Code: " . $r->faultCode() . ", Reason: " . $r->faultString());
               }
          }
          return $r->value();
     }


     /**
      * getPermissions 
      * function for getting permissions per group and returns array of permissions
      * @author : Franco E. Hora <info@goautodial.com>
      * @param : $interface > current interace
      *          $group > loggedin user group
      */
     function getPermissions($userInterface,$group){
    
          $CI =& get_instance(); 
          $asteriskDB = $CI->load->database('goautodialdb',TRUE);

          $asteriskDB->where("user_group",$group);
          $result = $asteriskDB->get('user_access_group'); 
       
          if($result->num_rows() > 0){
               $grouped = $result->result();
               $permission = json_decode($grouped[0]->permissions);
               return $permission->$userInterface;
          }else{
               return array();
          } 
     }
     

     /**
      * checkIfTenant
      * function for checking if the current logged in user's group is for multi-tenant
      * @author : Chris Lomuntad <chris@goautodial.com>
      * @param : $group > logged in user group
      */
     function checkIfTenant($group)
     {
          //$CI =& get_instance(); 
          //$goDB = $CI->load->database('goautodialdb',TRUE);
          //
          //$goDB->where("tenant_id",$group);
          //$result = $goDB->get('go_multi_tenant');
          //
          //if ($result->num_rows() > 0)
          if (strtolower($group)!='admin' && strtolower($group)!='supervisor' && strtolower($group)!='---all---' && strtolower($group)!='agents')
               return true;
          else
               return false;
     }
     
     /*
      * get_system_settings
      * function for getting system settings
      * @author : Chris Lomuntad <chris@goautodial.com>
      * @param : $param > to get the value of a selected column from system_settings
      */
     function get_system_settings($param=null)
     {
          $CI =& get_instance();
          $asteriskDB = $CI->load->database('dialerdb',TRUE);
          
          if (strlen($param) > 0)
          {
               $asteriskDB->select("$param");
          }
          $result = $asteriskDB->get('system_settings')->result();
          
          if (strlen($param) > 0)
          {
               $result = $result[0]->$param;
          }
          
          return $result;
     }

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
     
     function getBrowser($u_agent) 
     {
         $bname = 'Unknown';
         $platform = 'Unknown';
         $version= "";
     
         //First get the platform?
         if (preg_match('/linux/i', $u_agent)) {
             $platform = 'linux';
         }
         elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
             $platform = 'mac';
         }
         elseif (preg_match('/windows|win32/i', $u_agent)) {
             $platform = 'windows';
         }
         
         // Next get the name of the useragent yes seperately and for good reason
         if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
         { 
             $bname = 'Internet Explorer'; 
             $ub = "MSIE"; 
         } 
         elseif(preg_match('/Firefox/i',$u_agent)) 
         { 
             $bname = 'Mozilla Firefox'; 
             $ub = "Firefox"; 
         } 
         elseif(preg_match('/Chrome/i',$u_agent)) 
         { 
             $bname = 'Google Chrome'; 
             $ub = "Chrome"; 
         } 
         elseif(preg_match('/Safari/i',$u_agent)) 
         { 
             $bname = 'Apple Safari'; 
             $ub = "Safari"; 
         } 
         elseif(preg_match('/Opera/i',$u_agent)) 
         { 
             $bname = 'Opera'; 
             $ub = "Opera"; 
         } 
         elseif(preg_match('/Netscape/i',$u_agent)) 
         { 
             $bname = 'Netscape'; 
             $ub = "Netscape"; 
         } 
         
         // finally get the correct version number
         $known = array('Version', $ub, 'other');
         $pattern = '#(?<browser>' . join('|', $known) .
         ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
         if (!preg_match_all($pattern, $u_agent, $matches)) {
             // we have no matching number just continue
         }
         
         // see how many we have
         $i = count($matches['browser']);
         if ($i != 1) {
             //we will have two since we are not using 'other' argument yet
             //see if version is before or after the name
             if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                 $version= $matches['version'][0];
             }
             else {
                 $version= $matches['version'][1];
             }
         }
         else {
             $version= $matches['version'][0];
         }
         
         // check if we have a number
         if ($version==null || $version=="") {$version="?";}
         
         return array(
             'userAgent' => $u_agent,
             'name'      => $bname,
             'version'   => $version,
             'platform'  => $platform,
             'pattern'    => $pattern
         );
     }

}

/* End of file commonhelper.php */
