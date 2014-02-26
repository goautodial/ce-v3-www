<?Php
############################################################################################
####  Name:             modifyusertemp.php                                              ####
####  Type:             ci view user for advance modification of user                   ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
?>
<script src="../../../js/jquery-validate/jquery.validate.min.js"></script>
<script src="../../../js/go_search/go_search_ce.js"></script>
<div id="modifyuser-container">
    <div class="leftside">
        <?php $userid=$info[0]->user?>
        <div class="adv-user-info" id='user-info-<?=$info[0]->user_id?>'>
            <div class="adv-toggle"></div>
            <h3>(<?=$userid?>)&nbsp;<?=$info[0]->full_name?></h3>
            <div class="adv-user-detail user-corners">
            <form id='adv-userinfo-<?=$info[0]->user_id?>'>
                <?Php
                     #setting for pass 
                     $passthis = array('name'=>"$userid-pass",'id'=>"$userid-pass",'value'=>$info[0]->pass);
                ?>
                <div class="leftcol">Password</div>
                <div class="rightcol"><?echo form_input($passthis)?></div>
                <?Php
                     #setting for full_name 
                     $passthis = array('name'=>"$userid-full_name",'id'=>"$userid-full_name",'value'=>$info[0]->full_name);
                ?>
                <div class="leftcol">Full Name</div>
                <div class="rightcol"><?echo form_input($passthis)?></div>
                <?Php
                     #setting for phone_login 
                     $passthis = array('name'=>"phone_login-$userid",'id'=>"phone_login-$userid",'value'=>$info[0]->phone_login,'readonly'=>'readonly');
                ?>
                <div class="leftcol">Phone Login</div>
                <div class="rightcol"><?echo form_input($passthis)?></div>
                <?Php
                     #setting for phone_pass 
                     $passthis = array('name'=>"phone_pass-$userid",'id'=>"phone_pass-$userid",'value'=>$info[0]->phone_pass,'readonly'=>'readonly');
                ?>
                <div class="leftcol">Phone Pass</div>
                <div class="rightcol"><?echo form_input($passthis)?></div>
                <?Php
                     #setting for active 
                     $passthis = "id='active-$userid'";
                ?>
                <div class="leftcol">Active</div>
                <div class="rightcol"><?echo form_dropdown("active-$userid",array('Y'=>'Yes','N'=>'No'),$info[0]->active,$passthis)?></div>
                <?Php
                     #setting for phone_login 
                     $passthis = array('name'=>"voicemail_id-$userid",'id'=>"voicemail_id-$userid",'value'=>$info[0]->voicemail_id);
                ?>
                <div class="leftcol">Voicemail ID</div>
                <div class="rightcol"><?echo form_input($passthis)?></div>
                <br class="clear"/>
                <div class="adv-userinfo-action" id='userinfo-action-<?=$info[0]->user_id?>'>
                     <a id="user-adv-cancel-<?=$userid?>">Cancel</a> |
                     <a id="user-adv-save-<?=$userid?>">Save User</a>  
                </div>
            </form>
            </div>
        </div>
        <div class="adv-user-info user-cornerall adv-permissions">
            <div><strong>User Permissions</strong></div>
            <?Php
                 if(!empty($useraccess)){
                     foreach($useraccess as $accessinfo){
                         echo "<div class='adv-permissions-elem'>";
                         $col = $accessinfo->vicidial_users_column_name;
                         switch($accessinfo->vicidial_users_column_name){
                                 case 'vicidial_recording_override':
                                       echo "<select id='access-$accessinfo->vicidial_users_column_name' name='access-$accessinfo->vicidial_users_column_name'>";
                                             $options = array('DISABLED'=>'DISABLED','NEVER'=>'NEVER',
                                                              'ONDEMAND'=>'ONDEMAND','ALLCALLS'=>'ALLCALLS','ALLFORCE'=>'ALLFORCE');
                                             foreach($options as $index => $value){
                                                echo "<option value='$index' ".($selected==$info[0]->$col?'selected="selected"':"").">$value</option>";
                                             }
                                       echo "</select><label for='access-$accessinfo->vicidial_users_column_name'>$accessinfo->useraccess_name</label>";
                                 break;
                                 case 'alter_custdata_override': case'alter_custphone_override':
                                       echo "<select id='access-$accessinfo->vicidial_users_column_name' name='access-$accessinfo->vicidial_users_column_name'>";
                                             $options = array('NOT_ACTIVE'=>'NOT_ACTIVE','ALLOW_ALTER'=>'ALLOW_ALTER');
                                             foreach($options as $index => $value){
                                                echo "<option value='$index' ".($selected==$info[0]->$col?'selected="selected"':"").">$value</option>";
                                             }
                                       echo "</select><label for='access-$accessinfo->vicidial_users_column_name'>$accessinfo->useraccess_name</label>";
                                 break;
                                 case 'agent_shift_enforcement_override':
                                       echo "<select id='access-$accessinfo->vicidial_users_column_name' name='access-$accessinfo->vicidial_users_column_name'>";
                                             $options = array('N'=>'DISABLED','Y'=>'ENABLED');
                                             foreach($options as $index => $value){
                                                echo "<option value='$index' ".($selected==$info[0]->$col?'selected="selected"':"").">$value</option>";
                                             }
                                       echo "</select><label for='access-$accessinfo->vicidial_users_column_name'>$accessinfo->useraccess_name</label>";
                                 break;
                                 case 'agent_lead_search':
                                       echo "<select id='access-$accessinfo->vicidial_users_column_name' name='access-$accessinfo->vicidial_users_column_name'>";
                                             $options = array('NOT_ACTIVE'=>'NOT_ACTIVE','DISABLED'=>'DISABLED','ENABLED'=>'ENABLED');
                                             foreach($options as $index => $value){
                                                echo "<option value='$index' ".($selected==$info[0]->$col?'selected="selected"':"").">$value</option>";
                                             }
                                       echo "</select><label for='access-$accessinfo->vicidial_users_column_name'>$accessinfo->useraccess_name</label>";
                                 break;
                                 default:      
                                       if($info[0]->$col == 1 ){
                                           $checked = "checked='checked'";
                                       }else{
                                           $checked = ""; 
                                       }
                                       echo "<input type='checkbox' value='$accessinfo->vicidial_users_column_name'
                                                    id='access-$accessinfo->vicidial_users_column_name' 
                                                    name='access-$accessinfo->vicidial_users_column_name'$checked>
                                             <label for='access-$accessinfo->vicidial_users_column_name'>$accessinfo->useraccess_name</label>";
                                 break;
                         }
                         echo "</div>";
                     }
                 }
            ?>
        </div>
    </div> <!--// END OF div.leftside  //-->
    <br/>
    <br/>
    <br/>
</div> <!--// modify mode  //-->
