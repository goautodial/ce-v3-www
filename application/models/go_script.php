<?Php
########################################################################################################
####  Name:             	go_script.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Franco Hora                                              	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_script extends Model{


    function __construct(){
        parent::Model();
        $this->asteriskDB = $this->load->database('dialerdb',TRUE);
        $this->limesurveyDB = $this->load->database('limesurveydb',TRUE);
    }


    /*
     * updatescript
     * updates the selected script
     * @author: Franco E. Hora <inro@goautodial.com>
     */
    function updatescript($postval=array()){
        if(!empty($postval) && !is_null($postval)){
             if($this->errorTrap($postval)){

                 $prev_values = $this->commonhelper
                                         ->simpleretrievedata('vicidial_scripts' ,
                                                              'script_id,script_name,script_comments,active,script_text',
                                                              array(),
                                                              array(array('script_id'=>$postval['script_id']))
                                                             );

                 $this->asteriskDB->trans_start();
                      $this->asteriskDB->where('script_id',$postval['script_id']);
                      $this->asteriskDB->update('vicidial_scripts',$postval);
                 $this->asteriskDB->trans_complete();
                 if($this->asteriskDB->trans_status === false){
                      return "Error: Update fail";
                 }else{

                      $details = null;
                      foreach($postval as $col => $val){

                          $info = $prev_values->result();
                          $details .= "Modify script values $col: from ".$info[0]->{$col}." to $val\n";

                      }
                      $queries = null;
                      foreach($this->asteriskDB->queries as $query){
                          $queries .= "$query\n";
                      }
                      $this->commonhelper->auditadmin('MODIFY',$details,$queries);
                      return "Success: Update successful";
                 }
             } 
        }else{
              die("Error: Empty data field(s)");
        } 
    }


    /*
     * errotTrap
     * function to check post data
     * @author:Franco E. Hora <info@goautodial.com>
     */
    function errorTrap($columns=array()){
         $valid = false;
         $fieldsTocheck = array('script_id','script_name','script_text');
         if(!empty($columns) && !is_null($columns)){
              foreach($columns as $column => $val){
                   if(in_array($column,$fieldsTocheck)){
                        switch($column){
                              case 'script_id':
                                     if(strlen($val)>=2){
                                          $valid = true;
                                     }else{
                                          die('Error: Invalid script id format');
                                     }
                              break;
                              case 'script_name':
                                     if(strlen($val)>=2){
                                          $valid = true;
                                     }else{
                                          die('Error: Invalid script name format');
                                     }
                              break;
                              case 'script_text':
                                     if(strlen($val)>=2){
                                          $valid = true;
                                     }else{
                                          die('Error: Invalid script text format');
                                     }
                              break;
                        }                
                   }
              }
              return $valid;
         }  
    }

    /*
     * errotTrap
     * function to check post data
     * @author:Franco E. Hora <info@goautodial.com>
     */
    function checkdumplicate($columns=array(),$notThisId=null){
         $valid = false;
         if(!empty($columns) && !is_null($columns)){
              foreach($columns as $column){
              }
              return $valid;
         }  
    }

    /*
     * savedefaultscript
     * add new script in default type specifically
     * @author : Franco E. Hora <info@goautodial.com>
     * @param : $postvar > raw data
     */
    function savedefaultscript($postvar=array()){
       if(!empty($postvar)){
            if(is_array($postvar)){
                $this->asteriskDB->trans_start();
                    foreach($postvar as $index_as_table => $values){
                        if(array_key_exists('condition',$values)){
                            $this->asteriskDB->where($values['condition']);
                            $this->asteriskDB->update($index_as_table,$values['data']);
                        }else{
                            $this->asteriskDB->insert($index_as_table,$values['data']); 
                        }
                    }
                $this->asteriskDB->trans_complete();

                if($this->asteriskDB->trans_status() === false){
                     return "Error: Something went wrong please contact your support" ;
                }else{
                     $detail = null;
                     foreach($postvar as $col => $val){
                         $detail .= "ADD script values $col values ".implode(",",$val['data'])."\n";
                     }
                     $queries = null;
                     foreach($this->asteriskDB->queries as $queries){
                         $query .= "$queries\n";
                     }
                     $this->commonhelper->auditadmin('ADD',$detail,$queries);
                     return "Success: New default script created";
                }
            }
       }else{
           die("Error: Empty raw data while adding new script");
       }
    } 

    /*
     * collectfromviciuser
     * collects user info from vicidial_user  
     * @author : Franco E. Hora <info@goautodial.com>
     * @param : $userid > users username
     */
    function collectfromviciuser($userid=null){
          if(!is_null($userid)){
              $this->asteriskDB->where(array('user'=>$userid));
              return $this->asteriskDB->get('vicidial_users'); 
         }else{
             die('Error: No user submitted');
         }      
    }

    /*
     * collectfromlimesurvey
     * collects user info from limesurvey user  
     * @author : Franco E. Hora <info@goautodial.com>
     * @param : $user > users username
     */
    function collectfromlimesurvey($user=null){
         if(!is_null($user)){
              $this->limesurveyDB = $this->load->database('limesurveydb',TRUE);
              $this->limesurveyDB->where(array('users_name'=>$user));
              return $this->limesurveyDB->get('lime_users'); 
         }else{
             die('Error: No user submitted');
         }
    }

    /*
     * insertTolimesurvey
     * insert to limesurvey
     * @author: Franco E. Hora <info@goautodial.com>
     * @param : $rawData > data to process
     *          $table > table to insert
     *          $newId > return of newcreated id
     */
    function insertTolimesurvey($rawData=null,$table=null,&$newId=null){

         if(!is_null($rawData) && !is_null($table)){

             $this->limesurveyDB->trans_start();

                 $this->limesurveyDB->insert($table,$rawData);
                 $newId = $this->limesurveyDB->insert_id();

             $this->limesurveyDB->trans_complete(); 

             if($this->limesurveyDB->trans_status() === false){

                    die("Error: Error in saving new limesurvey user");

             } else {

                   return true;

             }

         } else {

             die("Error: Empty variables");

         }
    }

    /*
     * saveadvancescript
     * save new advance script 
     * @author: Franco E. Hora <info@goautodial.com>
     * @param : $rawdata > collected rawdata
     */
    function saveadvancescript($rawdata=array()){
        if(!is_null($rawdata)){
           
           $ctr=0;
           $datas = array();
           if(is_array($rawdata)){
  
              $new_ids = array(); # collects all new created id 
              // here comes the magic
              foreach($rawdata as $database => $db_tables){

                  if($database == "limesurvey"){

                       // saving in limesurvey
                       if(is_array($db_tables)){

                           // saving per tables
                           foreach($db_tables as $table_name => $table_data){

                                // reformat data
                                if(array_key_exists("format_data",$table_data)){

                                     $table_data = $this->datareplace($table_data,$new_ids);
                                }

                                //this is the data array
                                foreach($table_data as $data_indx => $data){
        
                                     // save only if data_indx is data
                                     if($data_indx == "data"){ 

                                          foreach($data as $indx => $val){

                                             //save here
                                             $datas[$ctr][$table_name] = $val;
                                             $this->limesurveyDB->trans_start();
                                                 $this->limesurveyDB->insert($table_name,$val);
                                                 $new_ids[$table_name][$indx] = $this->limesurveyDB->insert_id();
                                             $this->limesurveyDB->trans_complete();

                                             if($this->limesurveyDB->trans_status() === false){
                                                   die("Error on saving data in limesurvey $table_name");
                                             }

                                          }

                                     }
 
                                }

                           }

                       }/* else {

                           // saving na kagad? 
                           die("Unfinished part");
                       }*/

                  } else { 

                       // saving in asteriskDB
                       if(is_array($db_tables)){

                           // saving per tables
                           foreach($db_tables as $table_name => $table_data){

                                // reformat data
                                if(array_key_exists("format_data",$table_data)){

                                     $table_data = $this->datareplace($table_data,$new_ids);
                                }

                                //this is the data array
                                foreach($table_data as $data_indx => $data){

                                     if($table_name == "vicidial_campaigns"){
                                         $this->asteriskDB->trans_start();
                                              $this->asteriskDB->where($table_data['condition']);
                                              $this->asteriskDB->update($table_name,$table_data['data'][0]);
                                         $this->asteriskDB->trans_complete();
                                         break;
                                     }
                                     // save only if data_indx is data
                                     if($data_indx == "data"){ 

                                          foreach($data as $indx => $val){

                                             //save here
                                             $datas[$ctr][$table_name] = $val;
                                             $this->asteriskDB->trans_start();
                                                 $this->asteriskDB->insert($table_name,$val);
                                                 $new_ids[$table_name][$indx] = $this->asteriskDB->insert_id();
                                             $this->asteriskDB->trans_complete();

                                             if($this->asteriskDB->trans_status() === false){
                                                 die("Error on saving data in $table_name ");
                                             }

                                          }

                                     }
 
                                }

                           }

                       } else {

                           // saving na kagad? 
                           die("Unfinished part");
                       }                      

                  }
                  $ctr++;

              }
 
              $details = null;
              foreach($datas as $data_info){
                  foreach($data_info as $tablename => $table_data){
                      $details .= "ADD new script limesurvey in $tablename values: ";
                      foreach($table_data as $col => $newdata){
                          $details .= "$col = $newdata\n"; 
                      }
                  }
              }
              $queries = null;
              foreach($this->limesurveyDB->queries as $query){
                  $queries .= $query;
              }
              foreach($this->asteriskDB->queries as $query){
                  $queries .= $query;
              }
              $this->commonhelper->auditadmin("ADD","ADD NEW SCRIPT: \n$details",$queries);
              return true;

           } else {
              die("Error: Please complete your data");
           }

        } else {
            die("Error: Empty variables");
        }
    }

    /*
     * datareplace
     * replace data need to be replace
     * @author: Franco E. Hora <info@goautodial.com>
     * @param : 
     */
    function datareplace($data=array(),$new_ids=array()){

        if(is_array($data) && is_array($new_ids)){

              foreach($data as $format_indx => $format_val){

                      if($format_indx == "format_data"){

                          foreach($format_val as $pattern){
                              // get the id
                              $format_indx_len = strlen($pattern);
                              $table_indx = substr($pattern,0,$format_indx_len - 2);
                              $newId_indx = substr($pattern,$format_indx_len - 1);
                              $id = $new_ids[$table_indx][$newId_indx];
                              $theFormat = '{'.$pattern.'}';

                              // start formating data
                              foreach($data['data'] as $format_data_indx => $format_data){

                                   foreach($format_data as $format_this_indx => $format_this){

                                        if(preg_match("/$theFormat/",$format_this)){

                                            $data['data'][$format_data_indx][$format_this_indx] = preg_replace("/$theFormat/","$id",$format_this);

                                        }
                                   }
                                                   
                              }

                          }

                      }

              }

              return $data;
              
        }

    }


    /*
     * updatetable
     * updates the database tables
     * @author: Franco E. Hora <info@goautodial.com>
     * @param : $database > the database object
     *          $content > the rawdata to process
     */
    function updatetable($database=null,$table=null,$content=array()){

         if(!is_null($database) && !empty($content) && !is_null($table)){

               switch($database){
      
                    case "vicidial":
                             $obj = "asteriskDB";
                             $tableName = $table;
                    break;

                    case "limesurvey":
                             $obj = "limesurveyDB";
                             $dbname = $this->$obj->database;
                             $tableName = "`$dbname`.`$table`";
                    break;

               }

               $this->$obj->trans_start();
                    $this->$obj->where($content['condition']);
                    $this->$obj->update($tableName,$content['data']);
                    $this->commonhelper->auditadmin("MODIFY","MODIFY SCRIPT in $table values ".implode(",",$content['data']));
               $this->$obj->trans_complete();

               if($this->$obj->trans_status() === false){
                   die("Error on saving data");
               }
               return true;

         } else {

               die("Error: Please check your variables");

         }

    }


    function get_survey_report($surveyid=null,$daterange=null){
 
         if(!empty($surveyid)){
           if(!is_null($daterange)){
               $this->limesurveyDB->where("submitdate >=",$daterange[0]);
               $this->limesurveyDB->where("submitdate <=",$daterange[1]);
           }
           $result = $this->limesurveyDB->get("lime_survey_$surveyid");
           return $result; 
         }

    }


    function get_surveys(){

          $this->asteriskDB->join('go_scripts','go_scripts.script_id=vicidial_scripts.script_id','left');
          $this->asteriskDB->where('go_scripts.surveyid !=','');
          $this->asteriskDB->where('vicidial_scripts.active','Y');
          $surveys = $this->asteriskDB->get('vicidial_scripts');
          if($surveys->num_rows() > 0){

                $survey[0] = "";
                foreach($surveys->result() as $rows){
                     $survey[$rows->surveyid] = $rows->script_name;
                }

          }else{
                $survey = array();
          }

          return $survey;

    }

    function get_limesurveys($surveyid,$daterange){

         if(!empty($surveyid)){

              $limesurveys = $this->get_survey_report($surveyid,$daterange);
              if($limesurveys->num_rows() > 0){
                  $retrieved = $limesurveys->result();
                  $data_table['header'] = array_keys(get_object_vars($retrieved[0]));
                  foreach($data_table['header'] as $indx => $headers){
                      if(!in_array($headers,array('id','submitdate','lastpage','startlanguage','token'))){
                           $data_table['header'][$indx] = $this->get_question($headers);
                      }
                  }
                  foreach($limesurveys->result() as $survey){
                      $data_table['content'][$survey->id] = $survey;
                  }
              }

         }
         return $data_table;
         

    }

    function get_question($string){
        $ids = explode("X",$string);
        $sid = $ids[0];
        $qid = $ids[2];
        $question = $this->limesurveyDB->get_where('lime_questions',array('sid'=>$sid,'qid'=>$qid))->result();
        if(!empty($question)){
             $question_name = $question[0]->question;
        }else{
             $question_name = "";
        }
        return $question_name ;
    }
}
