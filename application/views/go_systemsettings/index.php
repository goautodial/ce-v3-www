<?php
############################################################################################
####  Name:             index.php                                                       ####
####  Type:             ci view (template for users)                                    ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################

$gmtArray = array(
    '12.75'=>'12.75','12.00'=>'12.00','11.00'=>'11.00','10.00'=>'10.00',
    '9.50'=>'9.50','9.00'=>'9.00','8.00'=>'8.00','7.00'=>'7.00',
    '6.50'=>'6.50','6.00'=>'6.00','5.75'=>'5.75','5.50'=>'5.50',
    '5.00'=>'5.00','4.50'=>'4.50','4.00'=>'4.00','3.50'=>'3.50',
    '3.00'=>'3.00','2.00'=>'2.00','1.00'=>'1.00','0.00'=>'0.00',
    '-1.00'=>'-1.00','-2.00'=>'-2.00','-3.00'=>'-3.00','-3.50'=>'-3.50',
    '-4.00'=>'-4.00','-5.00'=>'-5.00','-6.00'=>'-6.00','-7.00'=>'-7.00',
    '-8.00'=>'-8.00','-9.00'=>'-9.00','-10.00'=>'-10.00','-11.00'=>'-11.00','-12.00'=>'-12.00'
);
?>

<link type="text/css" rel="stylesheet" href="<?=base_url()?>css/go_common_ce.css">
<link type="text/css" rel="stylesheet" href="<?=base_url()?>css/go_systemsettings/go_systemsettings_ce.css">
<script type="text/javascript" src="<?=base_url()?>js/go_common_ce.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/go_systemsettings/go_settings_ce.js"></script>


<!--<div id='outbody' class="wrap">
    <div id="icon-settings" class="icon32"></div>
    <h2><?=$bannertitle?></h2>
    <div id="dashboard-widgets-wrap">
         <div id="dashboard-widgets" class="metabox-holder">
             <div class="postbox-container" style="width:99%">-->
                  <br>
                  <form id="system-settings" method="post" >
                  <div class="corner-all server-settings" style="width:800px;">
                        <div class="settings-label"><? echo $this->lang->line("go_version"); ?></div>
                        <div class="settings-value"><?=$settings[0]->version?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_db_schema_version"); ?></div>
                        <div class="settings-value"><?=$settings[0]->db_schema_version?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_db_schema_update_date"); ?></div>
                        <div class="settings-value"><?=$settings[0]->db_schema_update_date?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_auto_user_add_value"); ?></div>
                        <div class="settings-value"><?=$settings[0]->auto_user_add_value?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_install_date"); ?></div>
                        <div class="settings-value"><?=$settings[0]->install_date?></div>
                        <br class="clear"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;"><? echo $this->lang->line("go_use_non_latin"); ?></div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('use_non_latin',array('No','Yes'),$settings[0]->use_non_latin,'id="use_non_latin"');?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;"><? echo $this->lang->line("go_webroot_writable"); ?></div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('webroot_writable',array('No','Yes'),$settings[0]->webroot_writable,'id="webroot_writable"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;"><? echo $this->lang->line("go_vicidial_agent_disable_display"); ?></div>
                        <?$vicid_agent_disable = array('NOT_ACTIVE'=>'NOT ACTIVE','LIVE_AGENT'=>'LIVE AGENT','EXTERNAL'=>'EXTERNAL','ALL'=>'ALL')?>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('vicidial_agent_disable',$vicid_agent_disable,$settings[0]->vicidial_agent_disable,'id="vicidial_agent_disable"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;"><? echo $this->lang->line("go_allow_sipsak_msgs"); ?></div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('allow_sipsak_messages',array('No','Yes'),$settings[0]->allow_sipsak_messages,'id="allow_sipsak_messages"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;"><? echo $this->lang->line("go_admin_home_url"); ?></div>
                        <div class="settings-value" style="display:none;"><?=form_input('admin_home_url',$settings[0]->admin_home_url,'id="admin_home_url"')?></div>
                        <br class="clear" style="display:none;">
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;"><? echo $this->lang->line("go_admin_modify_refresh"); ?></div>
                        <div class="settings-value" style="display:none;"><?=form_input('admin_modify_refresh',$settings[0]->admin_modify_refresh,'id="admin_modify_refresh" size="5"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;"><? echo $this->lang->line("go_admin_no_cache"); ?></div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('nocache_admin',array('No','Yes'),$settings[0]->nocache_admin,'id="nocache_admin"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;"><? echo $this->lang->line("go_enable_agent_transfer_logfile"); ?></div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('enable_agc_xfer_log',array('No','Yes'),$settings[0]->enable_agc_xfer_log,'id="enable_agc_xfer_log"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;"><? echo $this->lang->line("go_enable_agent_disposition_logfile"); ?></div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('enable_agc_dispo_log',array('No','Yes'),$settings[0]->enable_agc_dispo_log,'id="enable_agc_dispo_log"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_timeclock_end_of_day"); ?></div>
                        <div class="settings-value"><?=form_input('timeclock_end_of_day',$settings[0]->timeclock_end_of_day,'id="timeclock_end_of_day" size="5"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_default_local_gmt"); ?></div>
                        <div class="settings-value"><?=form_dropdown('default_local_gmt',$gmtArray,$settings[0]->default_local_gmt,'id="default_local_gmt"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_timeclock_last_auto_logout"); ?></div>
                        <div class="settings-value"><?=$settings[0]->timeclock_last_reset_date?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_agent_screen_header_date_format"); ?></div>
                        <?$time=array('MS_DASH_24HR  2008-06-24 23:59:59'=>'MS_DASH_24HR  2008-06-24 23:59:59',
                                      'US_SLASH_24HR 06/24/2008 23:59:59'=>'US_SLASH_24HR 06/24/2008 23:59:59',
                                      'EU_SLASH_24HR 24/06/2008 23:59:59'=>'EU_SLASH_24HR 24/06/2008 23:59:59',
                                      'AL_TEXT_24HR  JUN 24 23:59:59'=>'AL_TEXT_24HR  JUN 24 23:59:59',
                                      'MS_DASH_AMPM  2008-06-24 11:59:59 PM'=>'MS_DASH_AMPM  2008-06-24 11:59:59 PM',
                                      'US_SLASH_AMPM 06/24/2008 11:59:59 PM'=>'US_SLASH_AMPM 06/24/2008 11:59:59 PM',
                                      'EU_SLASH_AMPM 24/06/2008 11:59:59 PM'=>'EU_SLASH_AMPM 24/06/2008 11:59:59 PM',
                                      'AL_TEXT_AMPM  JUN 24 2008 11:59:59 PM'=>'AL_TEXT_AMPM  JUN 24 2008 11:59:59 PM'
                                     )?>
                        <div class="settings-value"><?=form_dropdown('vdc_header_date_format',$time,$settings[0]->vdc_header_date_format,'id="vdc_header_date_format"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_agent_screen_customer_date_format"); ?></div>
                        <div class="settings-value"><?=form_dropdown('vdc_customer_date_format',$time,$settings[0]->vdc_customer_date_format,'id="vdc_customer_date_format"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_agent_screen_customer_phone_format"); ?></div>

                        <?$phone=array(
                                       'US_DASH 000-000-0000'=>'US_DASH 000-000-0000',
                                       'US_PARN (000)000-0000'=>'US_PARN (000)000-0000',
                                       'MS_NODS 0000000000'=>'MS_NODS 0000000000',
                                       'UK_DASH 00 0000-0000'=>'UK_DASH 00 0000-0000',
                                       'AU_SPAC 000 000 000'=>'AU_SPAC 000 000 000',
                                       'IT_DASH 0000-000-000'=>'IT_DASH 0000-000-000',
                                       'FR_SPAC 00 00 00 00 00'=>'FR_SPAC 00 00 00 00 00'
                                 )
                        ?>
                        <div class="settings-value"><?=form_dropdown('vdc_header_phone_format',$phone,$settings[0]->vdc_header_phone_format,'id="vdc_header_phone_format"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_agent_api_active"); ?></div>
                        <div class="settings-value"><?=form_dropdown('vdc_agent_api_active',array('No','Yes'),$settings[0]->vdc_agent_api_active,'id="vdc_agent_api_active"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_agent_only_callback_campaign_lock"); ?></div>
                        <div class="settings-value"><?=form_dropdown('agentonly_callback_campaign_lock',array('No','Yes'),$settings[0]->agentonly_callback_campaign_lock,'id="agentonly_callback_campaign_lock"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_central_sound_control_active"); ?></div>
                        <div class="settings-value"><?=form_dropdown('sounds_central_control_active',array('No','Yes'),$settings[0]->sounds_central_control_active,'id="sounds_central_control_active"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_sounds_web_server"); ?></div>
                        <div class="settings-value"><?=form_input('sounds_web_server',$settings[0]->sounds_web_server,'id="sounds_web_server"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_sounds_web_directory"); ?></div>
                        <div class="settings-value"><?="<a href='http://{$settings[0]->sounds_web_server}/{$settings[0]->sounds_web_directory}'>{$settings[0]->sounds_web_directory}</a>"?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_admin_web_directory"); ?></div>
                        <div class="settings-value"><?=form_input('admin_web_directory',$settings[0]->admin_web_directory,'id="admin_web_directory"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_active_voicemail_server"); ?></div>
                        <div class="settings-value"><?=form_dropdown('active_voicemail_server',$servers,$settings[0]->active_voicemail_server,'id="active_voicemail_server"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_auto_dial_limit"); ?></div>
                        <div class="settings-value"><?=form_dropdown('auto_dial_limit',$dialLimit,$settings[0]->auto_dial_limit,'id="auto_dial_limit"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_outbound_auto_dial_active"); ?></div>
                        <div class="settings-value"><?=form_dropdown('outbound_autodial_active',array('No','Yes'),$settings[0]->outbound_autodial_active,'id="outbound_autodial_active"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_max_fill_calls_per_second"); ?></div>
                        <div class="settings-value"><?=form_input('outbound_calls_per_second',$settings[0]->outbound_calls_per_second,'id="outbound_calls_per_second" size="5"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_allow_custom_dialplan_entries"); ?></div>
                        <div class="settings-value"><?=form_dropdown('allow_custom_dialplan',array('No','Yes'),$settings[0]->allow_custom_dialplan,'id="allow_custom_dialplan"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_generate_cross_server_phone_extens"); ?></div>
                        <div class="settings-value"><?=form_dropdown('generate_cross_server_exten',array('No','Yes'),$settings[0]->generate_cross_server_exten,'id="generate_cross_server_exten"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_user_territories_active"); ?></div>
                        <div class="settings-value"><?=form_dropdown('user_territories_active',array('No','Yes'),$settings[0]->user_territories_active,'id="user_territories_active"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_enable_sound_webform"); ?></div>
                        <div class="settings-value"><?=form_dropdown('enable_second_webform',array('No','Yes'),$settings[0]->enable_second_webform,'id="enable_second_webform"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_enable_tts_integration"); ?></div>
                        <div class="settings-value"><?=form_dropdown('enable_tts_integration',array('No','Yes'),$settings[0]->enable_tts_integration,'id="enable_tts_integration"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_enable_callcard"); ?></div>
                        <div class="settings-value"><?=form_dropdown('callcard_enabled',array('No','Yes'),$settings[0]->callcard_enabled,'id="callcard_enabled"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_enable_custom_list_fields"); ?></div>
                        <div class="settings-value"><?=form_dropdown('custom_fields_enabled',array('No','Yes'),$settings[0]->custom_fields_enabled,'id="custom_fields_enabled"')?></div>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_first_login_trigger"); ?></div>
                        <div class="settings-value"><?=$settings[0]->first_login_trigger?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_default_phone_registration_pass"); ?></div>
                        <div class="settings-value"><?=form_input('default_phone_registration_password',$settings[0]->default_phone_registration_password,'id="callcard_enabled"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_default_phone_login_pass"); ?></div>
                        <div class="settings-value"><?=form_input('default_phone_login_password',$settings[0]->default_phone_login_password,'id="default_phone_login_password"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_default_server_pass"); ?></div>
                        <div class="settings-value"><?=form_input('default_server_password',$settings[0]->default_server_password,'id="default_server_password"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_slave_database_server"); ?></div>
                        <div class="settings-value"><?=form_input('slave_db_server',$settings[0]->slave_db_server,'id="slave_db_server"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;"><? echo $this->lang->line("go_reports_use_slave"); ?></div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('reports_use_slave_db[]',$slavedb,explode(",",$settings[0]->reports_use_slave_db),'multiple id="reports_use_slave_db[]"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_custom_dialplan_entry"); ?></div>
                        <?$dialplan = array('name'=>'custom_dialplan_entry','value'=>$settings[0]->custom_dialplan_entry,"id"=>"custom_dialplan_entry","cols"=>"30",'rows'=>'10')?>
                        <div class="settings-value"><?=form_textarea($dialplan)?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_reload_dialplan_on_servers"); ?></div>
                        <div class="settings-value"><?=form_dropdown('reload_dialplan_on_servers',array('No','Yes'),$settings[0]->reload_dialplan_on_servers,'id="reload_dialplan_on_servers"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_defaultLanguage"); ?></div>
                        <div class="settings-value"><?=form_dropdown('default_language',$languages,$settings[0]->default_language,'id="default_language"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_title"); ?></div>
                        <div class="settings-value"><?=form_input('label_title',$settings[0]->label_title,'id="label_title"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_first_name"); ?></div>
                        <div class="settings-value"><?=form_input('label_first_name',$settings[0]->label_first_name,'id="label_first_name"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_middle_name"); ?></div>
                        <div class="settings-value"><?=form_input('label_middle_initial',$settings[0]->label_middle_initial,'id="label_middle_initial"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_last_name"); ?></div>
                        <div class="settings-value"><?=form_input('label_last_name',$settings[0]->label_last_name,'id="label_last_name"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_address1"); ?></div>
                        <div class="settings-value"><?=form_input('label_address1',$settings[0]->label_address1,'id="label_address1"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_address2"); ?></div>
                        <div class="settings-value"><?=form_input('label_address2',$settings[0]->label_address2,'id="label_address2"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_address3"); ?></div>
                        <div class="settings-value"><?=form_input('label_address3',$settings[0]->label_address3,'id="label_address3"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_city"); ?></div>
                        <div class="settings-value"><?=form_input('label_city',$settings[0]->label_city,'id="label_city"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_state"); ?></div>
                        <div class="settings-value"><?=form_input('label_state',$settings[0]->label_state,'id="label_state"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_province"); ?></div>
                        <div class="settings-value"><?=form_input('label_province',$settings[0]->label_province,'id="label_province"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_postal_code"); ?></div>
                        <div class="settings-value"><?=form_input('label_postal_code',$settings[0]->label_postal_code,'id="label_postal_code"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_vendor_lead_code"); ?></div>
                        <div class="settings-value"><?=form_input('label_vendor_lead_code',$settings[0]->label_vendor_lead_code,'id="label_vendor_lead_code"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_gender"); ?></div>
                        <div class="settings-value"><?=form_input('label_gender',$settings[0]->label_gender,'id="label_gender"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_phone_number"); ?></div>
                        <div class="settings-value"><?=form_input('label_phone_number',$settings[0]->label_phone_number,'id="label_phone_number"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_phone_code"); ?></div>
                        <div class="settings-value"><?=form_input('label_phone_code',$settings[0]->label_phone_code,'id="label_phone_code"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_alt_phone"); ?></div>
                        <div class="settings-value"><?=form_input('label_alt_phone',$settings[0]->label_alt_phone,'id="label_alt_phone"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_security_phrase"); ?></div>
                        <div class="settings-value"><?=form_input('label_security_phrase',$settings[0]->label_security_phrase,'id="label_security_phrase"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_email"); ?></div>
                        <div class="settings-value"><?=form_input('label_email',$settings[0]->label_email,'id="label_email"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_l_commens"); ?></div>
                        <div class="settings-value"><?=form_input('label_comments',$settings[0]->label_comments,'id="label_comments"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_qc_features_active"); ?></div>
                        <div class="settings-value"><?=form_dropdown('qc_features_active',array('No','Yes'),$settings[0]->qc_features_active,'id="qc_features_active"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_qc_last_pull_time"); ?></div>
                        <div class="settings-value"><?=$settings[0]->qc_last_pull_time?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_default_codecs"); ?></div>
                        <div class="settings-value"><?=form_input('default_codecs',$settings[0]->default_codecs,'id="default_codecs"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_default_webphone"); ?></div>
                        <div class="settings-value"><?=form_dropdown('default_webphone',array('No','Yes'),$settings[0]->default_webphone,'id="default_webphone"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_default_external_server_ip"); ?></div>
                        <div class="settings-value"><?=form_dropdown('default_external_server_ip',array('No','Yes'),$settings[0]->default_external_server_ip,'id="default_external_server_ip"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_webphone_url"); ?></div>
                        <div class="settings-value"><?=form_input('webphone_url',$settings[0]->webphone_url,'id="webphone_url"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label"><? echo $this->lang->line("go_webphone_system_key"); ?></div>
                        <div class="settings-value"><?=form_input('webphone_systemkey',$settings[0]->webphone_systemkey,'id="webphone_systemkey"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-action"><input type="button" value="<? echo $this->lang->line("go_submit"); ?>" id="updatesettings" style="cursor:pointer"></div>
                        <br class="clear"/>

                  </div>
                  </form>
                  <br/>
             <!--     <br/>-->
             <!--     <br/>-->
             <!--</div>-->
        <!--</div>--> <!-- dashboard-widgets  -->
    <!--</div>--> <!-- End dashboard-widget-wrap -->
<!--</div>--> <!-- End wrap -->
<!--</div>-->
