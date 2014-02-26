<?Php
########################################################################################################
####  Name:             	userhelper.php                                                      ####
####  Type:             	ci libraries - administrator                                        ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Writeen by:      	        Franco E. Hora                                                      ####
####  Edited by:	        GoAutoDial Development Team                                         ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userhelper {
    /*
     * loopinglink
     * function to display link dynamically exclusive for users
     * @author : Franco Hora <info@goautodial.com>
     * @param : $users array containing the heirarchy
     */
    public function loopinglink($users=array()){

         if(!is_array($users)){
            echo "(".$user[0]->users_id.") ".$user[0]->users_name;
         }else{
            echo '<ul>';
            foreach($users as $user){
                if(!is_array($user)){
                   echo "<li><a href='javascript:return false;' id='hierarchy-".$user->users_id."'>(".$user->users_id.") ".$user->users_name.'</a></li>';
                }else{
                        echo "<li><a href='javascript:return false;' id='hierarchy-".$user[0]->users_id."'>(".$user[0]->users_id.") ".$user[0]->users_name."</a>";
                        $this->loopinglink($user[1]);
                        echo "</li>";
                }
            }
            echo '</ul>';
         }
    }


    /*
     * individual
     * function to display individual users exclusive for user
     * @author : Franco Hora <info@goautodial.com>
     * @param : $users > array of users
     *          $groupvalues > array of users information
     */
    public function individual($users=array(),$groupvalues=array()){
          if(!is_array($users)){
                      echo "<div class='ungroups closed' id='ungroup-$user->users_id'>";
                          echo "<div class='ungroup-toggle' id='ungroup-toggle-$user->users_id'></div>";
                          echo "<h3>($user->users_id) $user->users_name </h3>";
                          echo "<div class='group-quick-edit' id='ungroup-quickedit-$user->users_id'>";
                              echo "<form id='form-$user->users_id' class='edit-user'>";
                              echo "<div class='quick-edit-fields'>";
                              echo "<input type='hidden' name='vicidial_user_id-$user->users_id"."' value='$user->vicidial_user_id' id='vicidial_user_id-$user->users_id"."'>"; 
                              echo "<input type='hidden' name='users_id-$user->users_id"."' value='$user->users_id' id='users_id-$user->users_id"."'>"; 
                              echo "<div class='leftcol'><label>Password&nbsp;</label></div><div class='rightcol'><input type='text' name='$user->users_id"."-pass' id='$user->users_id"."-pass' values=''></div>";
                              echo "<div class='leftcol'><label>Full Name&nbsp;</label></div><div class='rightcol'><input type='text' name='$user->users_id"."-full_name' id='$user->users_id"."-full_name'></div>";
                              echo "<div class='leftcol'><label>Phone Login&nbsp;</label></div><div class='rightcol'><input type='text' name='phone_login-$user->users_id"."' id='phone_login-$user->users_id"."'></div>";
                              echo "<div class='leftcol'><label>Phone Pass&nbsp;</label></div><div class='rightcol'><input type='text' name='phone_pass-$user->users_id"."' id='phone_pass-$user->users_id"."'></div>";
                              echo "<div class='leftcol'><label>Active&nbsp;</label></div><div class='rightcol'><select name='active-$user->users_id"."' id='active-$user->users_id"."'><option value='Y'>Yes</option><option value='N'>No</option></select></div>";
                              #echo "<input type='submit' value='Update' id='$user->users_id-submit'>";
                              echo "</div>";
                              echo "<br class='clear'/>";
                              echo "<div class='quick-action-set'>
                                         <a href='javascript:return false;' id='actions-update-$user->users_id' class='actions'>Update</a> | ".
                                        "<a href='javascript: return false;' id='actions-delete-$user->users_id' class='actions'>Delete</a> | ".
                                        "<a href='javascript: return false;' id='actions-advance-$user->users_id' class='actions'>Advance</a>";
                                   echo "<br class='clear'/>";
                              echo "</div>";
                              echo "</form>";
                          echo "</div>";
                      echo "</div>";
          }else{
              foreach($users as $user){
                  if(!is_array($user)){
                      echo "<div class='ungroups closed' id='ungroup-$user->users_id'>";
                          echo "<div class='ungroup-toggle' id='ungroup-toggle-$user->users_id'></div>";
                          echo "<h3>($user->users_id) $user->users_name </h3>";
                          echo "<div class='group-quick-edit' id='ungroup-quickedit-$user->users_id'>";
                              echo "<form id='form-$user->users_id' class='edit-user'>";
                              echo "<div class='quick-edit-fields'>";
                              echo "<input type='hidden' name='vicidial_user_id-$user->users_id"."' value='$user->vicidial_user_id' id='vicidial_user_id-$user->users_id"."'>"; 
                              echo "<input type='hidden' name='users_id-$user->users_id"."' value='$user->users_id' id='users_id-$user->users_id"."'>"; 
                              echo "<div class='leftcol'><label>Password&nbsp;</label></div><div class='rightcol'><input type='text' name='$user->users_id"."-pass' id='$user->users_id"."-pass' values=''></div>";
                              echo "<div class='leftcol'><label>Full Name&nbsp;</label></div><div class='rightcol'><input type='text' name='$user->users_id"."-full_name' id='$user->users_id"."-full_name'></div>";
                              echo "<div class='leftcol'><label>Phone Login&nbsp;</label></div><div class='rightcol'><input type='text' name='phone_login-$user->users_id"."' id='phone_login-$user->users_id"."' readonly></div>";
                              echo "<div class='leftcol'><label>Phone Pass&nbsp;</label></div><div class='rightcol'><input type='text' name='phone_pass-$user->users_id"."' id='phone_pass-$user->users_id"."' readonly></div>";
                              $active = array('Y'=>'Yes','N'=>'No');
                              $dom = "id='active-$user->users_id'";
                              echo "<div class='leftcol'><label>Active&nbsp;</label></div><div class='rightcol'>".
                                          form_dropdown('active-'.$user->users_id,$active,$user->users_active,$dom).
                                    "</div>";
                              #echo "<input type='submit' value='Update' id='$user->users_id-submit'>";
                              echo "</div>";
                              echo "<br class='clear'/>";
                              echo "<div class='quick-action-set'>
                                         <a href='javascript:return false;' id='actions-update-$user->users_id' class='actions'>Update</a> | ".
                                        "<a href='javascript: return false;' id='actions-delete-$user->users_id' class='actions'>Delete</a> | ".
                                        "<a href='javascript: return false;' id='actions-advance-$user->users_id' class='actions'>Advance</a>";
                                   echo "<br class='clear'/>";
                              echo "</div>";
                              echo "</form>";
                          echo "</div>";
                      echo "</div>";
                  }else{
                       $this->individual($user,$groupvalues);
                  }
              }
          } 
    }



    /*
     * postcleanup
     * function to remove concatination of users_id and table fields
     * @author : Franco Hora <info@goautodial.com>
     * @param : $post > array containing post variables
     *          $vicidial_user_id > id to hold the data 
     *          $stringtoremove > array of string format you want to remove
     */
    public function postcleanup($post=array(),$vicidial_user_id=null,$stringtoremove=null){

        if(!empty($post)){
            if(is_array($post)){
                foreach($post as $fields => $postvalue){
                    # start cleanup
                    if(is_array($stringtoremove)){
                        $fieldtoclean = $fields;
                        foreach($stringtoremove as $stringformat){
                           $fieldtoclean = str_replace($stringformat,"",$fieldtoclean);  
                        }
                        $cleanedfields = $fieldtoclean;
                    }else{
                        $cleandefields = str_replace($stringtoremove,"",$fields);  
                    }
                    $postfields[$vicidial_user_id][$cleanedfields] = $postvalue;
                }
                return $postfields; 
            }else{
                die("variable post is not an array");
            }
        }else{
            die("Empty post variables");
        }


    }

    /*
     * createdivs
     * function to create divs
     * @author : Franco Hora <info@goautodial.com>
     * @param : $divatt > text containing the divs attributes
     *          $contents > array containing to display index as html tags then value
     *          $divid > id of the div
     *          $contentAtts > attribute of contents you want 
     */
    function createdivs($divid=null,$divatt=null,$contents=null){
        if(!is_null($divid)){
            echo "<div id='$divid' $divatt>";
                 if(!empty($contents) && is_array($contents)){
                     foreach($contents as $htmltag => $values){
                         if(!is_array($values)){
                            $this->opentag($htmltag);
                                echo $values;
                            $this->closetag($htmltag);
                         }else{
                            $this->opentag($htmltag,$values['attr']);
                                 echo $values['content'];
                            $this->closetag($htmltag);
                         }
                         /*$this->opentag($htmltag,$contentAtts);
                         if(!is_array($values)){
                             echo $values;
                         }else{
                             foreach($values as $index => $val){
                                 var_dump($index);
                                 $this->createdivs($index,null,array('span'=>$val));
                             }
                         }
                         $this->closetag($htmltag);*/
                     }
                 }
            echo "</div>";
        }else{
            die("Error: Please specify div ID");
        }
    }

    function opentag($htmltag,$atts=null){
        if(!is_null($atts)){
            echo "<$htmltag $atts>"; 
        }else{
            echo "<$htmltag>";
        }
    }

    function closetag($htmltag){
        echo "</$htmltag>";
    }


}

/* End of file userhelper.php */
