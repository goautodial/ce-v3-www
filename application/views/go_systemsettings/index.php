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


<div id='outbody' class="wrap">
    <div id="icon-settings" class="icon32"></div>
    <h2><?=$bannertitle?></h2>
    <div id="dashboard-widgets-wrap">
         <div id="dashboard-widgets" class="metabox-holder">
             <div class="postbox-container" style="width:99%">
                  <form id="system-settings" method="post" >
                  <div class="corner-all server-settings" style="width:800px;">
                        <div class="settings-label">Version</div>
                        <div class="settings-value"><?=$settings[0]->version?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">DB Schema Version</div>
                        <div class="settings-value"><?=$settings[0]->db_schema_version?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">DB Schema Update Date</div>
                        <div class="settings-value"><?=$settings[0]->db_schema_update_date?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Auto User-add Value</div>
                        <div class="settings-value"><?=$settings[0]->auto_user_add_value?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Install Date</div>
                        <div class="settings-value"><?=$settings[0]->install_date?></div>
                        <br class="clear"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">Use Non-Latin</div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('use_non_latin',array('No','Yes'),$settings[0]->use_non_latin,'id="use_non_latin"');?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">Webroot Writable</div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('webroot_writable',array('No','Yes'),$settings[0]->webroot_writable,'id="webroot_writable"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">VICIDIAL Agent Disable Display</div>
                        <?$vicid_agent_disable = array('NOT_ACTIVE'=>'NOT ACTIVE','LIVE_AGENT'=>'LIVE AGENT','EXTERNAL'=>'EXTERNAL','ALL'=>'ALL')?>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('vicidial_agent_disable',$vicid_agent_disable,$settings[0]->vicidial_agent_disable,'id="vicidial_agent_disable"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">Allow SIPSAK Messages</div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('allow_sipsak_messages',array('No','Yes'),$settings[0]->allow_sipsak_messages,'id="allow_sipsak_messages"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">Admin Home URL</div>
                        <div class="settings-value" style="display:none;"><?=form_input('admin_home_url',$settings[0]->admin_home_url,'id="admin_home_url"')?></div>
                        <br class="clear" style="display:none;">
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">Admin Modify Refresh</div>
                        <div class="settings-value" style="display:none;"><?=form_input('admin_modify_refresh',$settings[0]->admin_modify_refresh,'id="admin_modify_refresh" size="5"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">Admin No-Cache</div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('nocache_admin',array('No','Yes'),$settings[0]->nocache_admin,'id="nocache_admin"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">Enable Agent Transfer Logfile</div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('enable_agc_xfer_log',array('No','Yes'),$settings[0]->enable_agc_xfer_log,'id="enable_agc_xfer_log"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">Enable Agent Disposition Logfile</div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('enable_agc_dispo_log',array('No','Yes'),$settings[0]->enable_agc_dispo_log,'id="enable_agc_dispo_log"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Timeclock End Of Day</div>
                        <div class="settings-value"><?=form_input('timeclock_end_of_day',$settings[0]->timeclock_end_of_day,'id="timeclock_end_of_day" size="5"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Default Local GMT</div>
                        <div class="settings-value"><?=form_dropdown('default_local_gmt',$gmtArray,$settings[0]->default_local_gmt,'id="default_local_gmt"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Timeclock Last Auto Logout</div>
                        <div class="settings-value"><?=$settings[0]->timeclock_last_reset_date?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Agent Screen Header Date Format</div>
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
                        <div class="settings-label">Agent Screen Customer Date Format</div>
                        <div class="settings-value"><?=form_dropdown('vdc_customer_date_format',$time,$settings[0]->vdc_customer_date_format,'id="vdc_customer_date_format"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Agent Screen Customer Phone Format</div>
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
                        <div class="settings-label">Agent API Active</div>
                        <div class="settings-value"><?=form_dropdown('vdc_agent_api_active',array('No','Yes'),$settings[0]->vdc_agent_api_active,'id="vdc_agent_api_active"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Agent Only Callback Campaign Lock</div>
                        <div class="settings-value"><?=form_dropdown('agentonly_callback_campaign_lock',array('No','Yes'),$settings[0]->agentonly_callback_campaign_lock,'id="agentonly_callback_campaign_lock"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Central Sound Control Active</div>
                        <div class="settings-value"><?=form_dropdown('sounds_central_control_active',array('No','Yes'),$settings[0]->sounds_central_control_active,'id="sounds_central_control_active"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">Sounds Web Server</div>
                        <div class="settings-value" style="display:none;"><?=form_input('sounds_web_server',$settings[0]->sounds_web_server,'id="sounds_web_server"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">Sounds Web Directory</div>
                        <div class="settings-value" style="display:none;"><?="<a href='http://{$settings[0]->sounds_web_server}/{$settings[0]->sounds_web_directory}'>{$settings[0]->sounds_web_directory}</a>"?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">Admin Web Directory</div>
                        <div class="settings-value" style="display:none;"><?=form_input('admin_web_directory',$settings[0]->admin_web_directory,'id="admin_web_directory"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Active Voicemail Server</div>
                        <div class="settings-value"><?=form_dropdown('active_voicemail_server',$servers,$settings[0]->active_voicemail_server,'id="active_voicemail_server"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Auto Dial Limit</div>
                        <div class="settings-value"><?=form_dropdown('auto_dial_limit',$dialLimit,$settings[0]->auto_dial_limit,'id="auto_dial_limit"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Outbound Auto-Dial Active</div>
                        <div class="settings-value"><?=form_dropdown('outbound_autodial_active',array('No','Yes'),$settings[0]->outbound_autodial_active,'id="outbound_autodial_active"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Max FILL Calls per Second</div>
                        <div class="settings-value"><?=form_input('outbound_calls_per_second',$settings[0]->outbound_calls_per_second,'id="outbound_calls_per_second" size="5"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Allow Custom Dialplan Entries</div>
                        <div class="settings-value"><?=form_dropdown('allow_custom_dialplan',array('No','Yes'),$settings[0]->allow_custom_dialplan,'id="allow_custom_dialplan"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Generate Cross-Server Phone Extensions</div>
                        <div class="settings-value"><?=form_dropdown('generate_cross_server_exten',array('No','Yes'),$settings[0]->generate_cross_server_exten,'id="generate_cross_server_exten"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">User Territories Active</div>
                        <div class="settings-value"><?=form_dropdown('user_territories_active',array('No','Yes'),$settings[0]->user_territories_active,'id="user_territories_active"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Enable Second Webform</div>
                        <div class="settings-value"><?=form_dropdown('enable_second_webform',array('No','Yes'),$settings[0]->enable_second_webform,'id="enable_second_webform"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Enable TTS Integration</div>
                        <div class="settings-value"><?=form_dropdown('enable_tts_integration',array('No','Yes'),$settings[0]->enable_tts_integration,'id="enable_tts_integration"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Enable CallCard</div>
                        <div class="settings-value"><?=form_dropdown('callcard_enabled',array('No','Yes'),$settings[0]->callcard_enabled,'id="callcard_enabled"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Enable Custom List Fields</div>
                        <div class="settings-value"><?=form_dropdown('custom_fields_enabled',array('No','Yes'),$settings[0]->custom_fields_enabled,'id="custom_fields_enabled"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">First Login Trigger</div>
                        <div class="settings-value"><?=$settings[0]->first_login_trigger?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Default Phone Registration Password</div>
                        <div class="settings-value"><?=form_input('default_phone_registration_password',$settings[0]->default_phone_registration_password,'id="callcard_enabled"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Default Phone Login Password</div>
                        <div class="settings-value"><?=form_input('default_phone_login_password',$settings[0]->default_phone_login_password,'id="default_phone_login_password"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Default Server Password</div>
                        <div class="settings-value"><?=form_input('default_server_password',$settings[0]->default_server_password,'id="default_server_password"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Slave Database Server</div>
                        <div class="settings-value"><?=form_input('slave_db_server',$settings[0]->slave_db_server,'id="slave_db_server"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator" style="display:none;">&nbsp;</div>
                        <div class="settings-label" style="display:none;">Reports to use Slave DB</div>
                        <div class="settings-value" style="display:none;"><?=form_dropdown('reports_use_slave_db[]',$slavedb,explode(",",$settings[0]->reports_use_slave_db),'multiple id="reports_use_slave_db[]"')?></div>
                        <br class="clear" style="display:none;"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Custom Dialplan Entry</div>
                        <?$dialplan = array('name'=>'custom_dialplan_entry','value'=>$settings[0]->custom_dialplan_entry,"id"=>"custom_dialplan_entry","cols"=>"30",'rows'=>'10')?>
                        <div class="settings-value"><?=form_textarea($dialplan)?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Reload Dialplan On Servers</div>
                        <div class="settings-value"><?=form_dropdown('reload_dialplan_on_servers',array('No','Yes'),$settings[0]->reload_dialplan_on_servers,'id="reload_dialplan_on_servers"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Title</div>
                        <div class="settings-value"><?=form_input('label_title',$settings[0]->label_title,'id="label_title"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label First Name</div>
                        <div class="settings-value"><?=form_input('label_first_name',$settings[0]->label_first_name,'id="label_first_name"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Middle Initial</div>
                        <div class="settings-value"><?=form_input('label_middle_initial',$settings[0]->label_middle_initial,'id="label_middle_initial"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Last Name</div>
                        <div class="settings-value"><?=form_input('label_last_name',$settings[0]->label_last_name,'id="label_last_name"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Address1</div>
                        <div class="settings-value"><?=form_input('label_address1',$settings[0]->label_address1,'id="label_address1"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Address2</div>
                        <div class="settings-value"><?=form_input('label_address2',$settings[0]->label_address2,'id="label_address2"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Address3</div>
                        <div class="settings-value"><?=form_input('label_address3',$settings[0]->label_address3,'id="label_address3"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label City</div>
                        <div class="settings-value"><?=form_input('label_city',$settings[0]->label_city,'id="label_city"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label State</div>
                        <div class="settings-value"><?=form_input('label_state',$settings[0]->label_state,'id="label_state"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Province</div>
                        <div class="settings-value"><?=form_input('label_province',$settings[0]->label_province,'id="label_province"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Postal Code</div>
                        <div class="settings-value"><?=form_input('label_postal_code',$settings[0]->label_postal_code,'id="label_postal_code"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Vendor Lead Code</div>
                        <div class="settings-value"><?=form_input('label_vendor_lead_code',$settings[0]->label_vendor_lead_code,'id="label_vendor_lead_code"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Gender</div>
                        <div class="settings-value"><?=form_input('label_gender',$settings[0]->label_gender,'id="label_gender"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Phone Number</div>
                        <div class="settings-value"><?=form_input('label_phone_number',$settings[0]->label_phone_number,'id="label_phone_number"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Phone Code</div>
                        <div class="settings-value"><?=form_input('label_phone_code',$settings[0]->label_phone_code,'id="label_phone_code"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Alt Phone</div>
                        <div class="settings-value"><?=form_input('label_alt_phone',$settings[0]->label_alt_phone,'id="label_alt_phone"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Security Phrase</div>
                        <div class="settings-value"><?=form_input('label_security_phrase',$settings[0]->label_security_phrase,'id="label_security_phrase"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Email</div>
                        <div class="settings-value"><?=form_input('label_email',$settings[0]->label_email,'id="label_email"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Label Comments</div>
                        <div class="settings-value"><?=form_input('label_comments',$settings[0]->label_comments,'id="label_comments"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">QC Features Active</div>
                        <div class="settings-value"><?=form_dropdown('qc_features_active',array('No','Yes'),$settings[0]->qc_features_active,'id="qc_features_active"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">QC Last Pull Time</div>
                        <div class="settings-value"><?=$settings[0]->qc_last_pull_time?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Default Codecs</div>
                        <div class="settings-value"><?=form_input('default_codecs',$settings[0]->default_codecs,'id="default_codecs"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Default Webphone</div>
                        <div class="settings-value"><?=form_dropdown('default_webphone',array('No','Yes'),$settings[0]->default_webphone,'id="default_webphone"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Default External Server IP</div>
                        <div class="settings-value"><?=form_dropdown('default_external_server_ip',array('No','Yes'),$settings[0]->default_external_server_ip,'id="default_external_server_ip"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Webphone URL</div>
                        <div class="settings-value"><?=form_input('webphone_url',$settings[0]->webphone_url,'id="webphone_url"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-label">Webphone System Key</div>
                        <div class="settings-value"><?=form_input('webphone_systemkey',$settings[0]->webphone_systemkey,'id="webphone_systemkey"')?></div>
                        <br class="clear"/>
                        <div class="settings-separator">&nbsp;</div>
                        <div class="settings-action"><input type="button" value="Submit" id="updatesettings" style="cursor:pointer"></div>
                        <br class="clear"/>

                  </div>
                  </form>
                  <br/>
                  <br/>
                  <br/>
             </div>
        </div> <!-- dashboard-widgets  -->
    </div> <!-- End dashboard-widget-wrap -->
</div> <!-- End wrap -->
</div>
