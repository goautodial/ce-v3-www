<?php
############################################################################################
####  Name:             userauth_lang.php                                               ####
####  Type:             language file                                                   ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Ltd. - J.Samantra, J.Dionisio, W.Briones             ####
####  License:          AGPLv2                                                          ####
############################################################################################

// views/usergroups - login
$lang['ua_login']               = 'Login';
$lang['ua_logout']              = 'Logout';
$lang['ua_remember_me']         = 'remember me';
$lang['ua_user']                = 'User';
$lang['ua_administrator_login'] = 'ADMINISTRATOR LOGIN';
$lang['ua_banner_text']         = 'Empowering The Next Generation Contact Centers';
$lang['ua_empowering']          = 'Empowering';
$lang['ua_thenextgen']          = 'The Next Generation';
$lang['ua_contactcenters']      = 'Contact Centers';

$lang['ua_welcome']           =       'Welcome!';
$lang['ua_getstarted']        =       'Getting Started!';
$lang['ua_needhelp']          =       'Need Help?';
$lang['ua_welcomephrase']     =       'GoAutoDial is an enterprise grade open source call center system. Scalable to hundreds of seats and can utilize VoIP, ISDN or analog trunks. Visit us @ ';
$lang['ua_getstartedphrase']  =       'The GoAutoDial Getting Started Guide will help you jumpstart your GoAutoDial experience. It includes step by step installation, SIP trunk configuration, leads loading, agent login and taking your first call.';
$lang['ua_needhelpphrase']    =       'We’re here for you. We provide top rate service at affordable rates. And choosing our service will help support the development of the project.';
$lang['ua_licence']           =       'GoAutoDial comes with no guarantees or warranties of any sorts, either written or implied. The Distribution is released as GPL. Individual packages in the distribution come with their own licenses.';

$lang['ua_AgentLogin']   =     'Agent Login';
$lang['ua_AdminLogin']   =     'Admin Login';
$lang['ua_VTigerCRM']   =     'VTigerCRM';
$lang['ua_Community']   =     'Community';
$lang['ua_VOIPStore']   =     'VOIP Store';

$lang['phpMyAdmin']   =     'phpMyAdmin';
$lang['SupportCenter']   =     'Support Center';
$lang['vicidialadmin']   =     'Vicidial Admin';
$lang['goadmin']   =     'GO Admin';

// views/usergroups - login userauth userForm
$lang['ua_password']            = 'Password';

// controllers/admin - usergroups
// views/usergroups - login userauth userForm
$lang['ua_username']            = 'Username';

// controllers/admin - usergroups
$lang['ua_group_added']         = 'Group added successfully';
$lang['ua_group_added_err']     = 'Something went wrong. The group was not added.';
$lang['ua_group_edited']        = 'Group edited successfully';
$lang['ua_group_edited_err']    = 'Something went wrong. The group was not edited.';
$lang['ua_group_removed']       = 'Group Successfully removed.';
$lang['ua_missing']             = 'Something was missing. Was a field left blank?';
$lang['ua_removal_err']         = 'There was an error. Your removal was not completed.';
$lang['ua_removal_err_members'] = 'The Group could not be removed as there are currently members in it. Please reassign all group members and try again.';
$lang['ua_status']              = 'Status';
$lang['ua_user_added']          = 'User added successfully';
$lang['ua_user_edited']         = 'User edited successfully';
$lang['ua_user_exists']         = 'User already exists';
$lang['ua_user_not_removed']    = 'Could not remove that user';
$lang['ua_user_removed']        = 'User successfully removed!';

// controllers/admin - usergroups
// views/usergroups - groupForm
$lang['ua_groupdescription']    = 'Group Description';
$lang['ua_editgroup']           = 'Edit Group';
$lang['ua_groupname']           = 'Group Name';

// controllers/admin - usergroups
// views/usergroups - userForm
$lang['ua_edituser']            = 'Edit User';
$lang['ua_passconf']            = 'Password Confirmation';

// controllers/admin - usergroups
// views/usergroups - userauth
$lang['ua_addgroup']            = 'Add Group';
$lang['ua_manage_title']        = 'User Management Administration';

// controllers/admin - usergroups
// views/usergroups - userauth userForm
$lang['ua_adduser']             = 'Add User';
$lang['ua_email']               = 'Email Address';
$lang['ua_fullname']            = 'Full Name';
$lang['ua_group']               = 'Group';

// views/usergroups - userauth
$lang['ua_description']         = 'Description';
$lang['ua_edit']                = 'Edit';
$lang['ua_enabled']             = 'enabled';
$lang['ua_manage_user']         = 'User Management';
$lang['ua_manage_group']        = 'Group Management';
$lang['ua_no']                  = 'No';
$lang['ua_remove']              = 'Remove';
$lang['ua_yes']                 = 'Yes';

// views/usergroups - userForm
$lang['ua_leave_blank']         = 'leave blank for no change';

//views/usergroups - groupForm
$lang['ua_255_char_max']        = '255 characters max';

//views/usergroups - groupForm userForm
$lang['ua_form_mode_error']     = 'Form: Mode Error';

// controllers - user
$lang['ua_auth_denied']         = "You are not authorized to view that page.";
$lang['ua_auth_err_title']      = 'Authentification Error';
$lang['ua_auth_not_logged']     = "You appear not to be logged in. You can log in here.";
$lang['ua_log_error']           = 'The username and/or password you entered was incorrect.';
$lang['ua_login_error']         = 'Login Error';
$lang['ua_logout_title']        = 'Logout';
$lang['ua_logout_txt']          = 'You have been logged out. You can log back in here.';
$lang['ua_name_and_pswd']       = 'Both username and password must be filled in. Please enter them and try again.';

// controllers - install

$lang['ua_install']             = 'Installer';
$lang['ua_install_admin_err_1'] = 'It appears your admin user is already present.';
$lang['ua_install_admin_err_2'] = 'If you are re-installing the userauth system, you should be able to safely delete the install controller and use your existing information.';
$lang['ua_install_ok_title']    = 'Install Success';
$lang['ua_install_ok_txt_1']    = 'Your Code Igniter userauth management system is now installed.';
$lang['ua_install_ok_txt_2']    = 'You can now login as "admin" and "admin". After you login, you can edit this information.';
$lang['ua_install_ok_txt_3']    = 'We now strongly recommend you go back and delete the install controller.';

// models - user_group_model
$lang['ua_error_no_suicide']    = 'You can not delete yourself!';



//Users index
$lang['goUsers_users'] = "Users";
$lang['goUsers_searchUsers'] = "Search Users";
$lang['goUsers_agentId'] = "AGENT ID";
$lang['goUsers_agentName'] = "AGENT NAME";
$lang['goUsers_level'] = "LEVEL";
$lang['goUsers_status'] = "STATUS";
$lang['goUsers_action'] = "ACTION";
$lang['goUsers_actionEnabledSelected'] = "Enabled Selected";
$lang['goUsers_actionDisabledSelected'] = "Disabled Selected";
$lang['goUsers_actionDeleteSelected'] = "Delete Selected";
$lang['goUsers_addNewUser'] = "Add New User";
//Users tooltips
$lang['goUsers_tooltip_agentId'] = "Clicking on the Agent ID or the modify icon will show the screen below and allow you to edit the user settings.";
$lang['goUsers_tooltip_modifyUser'] = "Modify user ";
//$lang['goUsers_tooltip_level'] = "Level";
$lang['goUsers_tooltip_actionEdit'] = "Clicking on the Agent ID or the modify icon will show the screen below and allow you to edit the user settings.";
$lang['goUsers_tooltip_actionDelete'] = "Delete User";
$lang['goUsers_tooltip_actionIcon'] = "<b>Info Icon</b> - gives all relevant information about agent activity and status. Allows admin to force logout user.";
$lang['goUsers_tooltip_goToFirstPage'] = "Go to first page";
$lang['goUsers_tooltip_goToPreviousPage'] = "Go to previous page";
$lang['goUsers_tooltip_goToPage'] = "Go to page";
$lang['goUsers_tooltip_goToNextPage'] = "Go to next page";
$lang['goUsers_tooltip_goToLastPage'] = "Go to last page";
$lang['goUsers_tooltip_backToPaginatedView'] = "Back to paginated view page";
$lang['goUsers_generatePhoneLogins'] = "Generate Phone Logins";
$lang['goUsers_agentLoginLogoutTime'] = "Agent Login/Logout Time";
$lang['goUsers_outboundCallsForThisTimePeriod'] = "Outbound Calls for this time period";
$lang['goUsers_inboundCloserCallsForThisTimePeriod'] = "Inbound Closer Calls for this time period";
$lang['goUsers_agentActivityForThisTime'] = "Agent activity for this time";
$lang['goUsers_recordingForThisTimePeriod'] = "Recording for this time period";
$lang['goUsers_manualOutboundCallsForThisTimePeriod'] = "Manual outbound calls for this time period";
$lang['goUsers_leadSearchesForThisTimePeriod'] = "Lead searches for this time period";
//Add New User
$lang['goUsers_wizard'] = "Users Wizard";
$lang['goUsers_step1'] = "Step 1";
$lang['goUsers_wizardType'] = "Wizard Type";
$lang['goUsers_addNewUser'] = "Add New User";
$lang['goUsers_next'] = "Next";
$lang['goUsers_step2'] = "Step 2";
$lang['goUsers_currentUsers'] = "Current Users";
$lang['goUsers_addSeats'] = "Additional Seat(s)";
$lang['goUsers_cancel'] = "Cancel";
$lang['goUsers_warning'] = "Warning: Creating additional users with phone extension will put you over the limit of 10. That means newly added users will no longer have phone extensions added with them.";
$lang['goUsers_wizard2'] = "Users Wizard » Add New User";
$lang['goUsers_step3'] = "Step 3";
$lang['goUsers_userGroup'] = "User Group";
$lang['goUsers_userId'] = "User ID";
$lang['goUsers_password'] = "Password";
$lang['goUsers_fullName'] = "Full Name";
$lang['goUsers_active'] = "Active";
$lang['goUsers_activeYes'] = "Yes";
$lang['goUsers_activeNo'] = "No";
$lang['goUsers_back'] = "Back";
$lang['goUsers_save'] = "Save";
$lang['goUsers_success'] = "Success: New User(s) successfully created";
$lang['goUsers_ok'] = "Ok";
$lang['goUsers_selectDateRange'] = "Select Date Range";
$lang['goUsers_phoneExtension'] = "Phone Extension";
$lang['goUsers_agentIsInCampaign'] = "Agent is in campaign";
$lang['goUsers_hangupLastCallAt'] = "Hangup last call at";
$lang['goUsers_closerGroups'] = "Closer Groups";
$lang['goUsers_agentTalkTimeAndStatus'] = "Agent Talk Time and Status";
$lang['goUsers_count'] = "Count";
$lang['goUsers_otherStaticsInfo'] = "Other Statics Information";
$lang['goUsers_agentLoginLogouts'] = "Agent Login/Logouts";
$lang['goUsers_outboundCallThisTime'] = "Outbound Call this time";
$lang['goUsers_inboundCloserCalls'] = "Inbound/Closer Calls";
$lang['goUsers_agentActivity'] = "Agent Activity";
$lang['goUsers_recording'] = "Recording";
$lang['goUsers_manualOutbound'] = "Manual Outbound";
$lang['goUsers_leadSearch'] = "Lead Search";
$lang['goUsers_agentLoginLogoutTime'] = "Agent Login/Logout Time";
$lang['goUsers_event'] = "Event";
$lang['goUsers_date'] = "Date";
$lang['goUsers_time'] = "Time";
$lang['goUsers_campaign'] = "Campaign";
$lang['goUsers_computer'] = "Computer";
$lang['goUsers_totalCalls'] = "Total Calls";
$lang['goUsers_perPage'] = "Per Page";
$lang['goUsers_dateTime'] = "Date / Time";
$lang['goUsers_length'] = "Lenght";
$lang['goUsers_phone'] = "Phone";
$lang['goUsers_group'] = "GROUP";
$lang['goUsers_list'] = "List";
$lang['goUsers_lead'] = "Lead";
$lang['goUsers_hangupReason'] = "Hangup Reason";
$lang['goUsers_waits'] = "Wait(s)";
$lang['goUsers_agents'] = "Agents";
$lang['goUsers_pause'] = "Pause";
$lang['goUsers_wait'] = "Wait";
$lang['goUsers_talk'] = "Talk";
$lang['goUsers_disposition'] = "Disposition";
$lang['goUsers_dead'] = "Dead";
$lang['goUsers_customer'] = "Customer";
$lang['goUsers_pauseCode'] = "Pause Code";
$lang['goUsers_seconds'] = "Seconds";
$lang['goUsers_recid'] = "Recid";
$lang['goUsers_filename'] = "Filename";
$lang['goUsers_location'] = "Location";
$lang['goUsers_callType'] = "Call Type";
$lang['goUsers_server'] = "Server";
$lang['goUsers_callerId'] = "Caller ID";
$lang['goUsers_type'] = "Type";
$lang['goUsers_results'] = "Results";
$lang['goUsers_query'] = "Query";
$lang['goUsers_leadInformation'] = "Lead Information";
$lang['goUsers_leadId'] = "Lead ID";
$lang['goUsers_listId'] = "List ID";
$lang['goUsers_address'] = "Address";
$lang['goUsers_phoneCode'] = "Phone Code";
$lang['goUsers_phoneNumber'] = "Phone Number";
$lang['goUsers_city'] = "City";
$lang['goUsers_state'] = "State";
$lang['goUsers_postalCode'] = "Postal Code";
$lang['goUsers_comment'] = "Comment";
$lang['goUsers_forceLogout'] = "Force Logout";
$lang['goUsers_close'] = "Close";
$lang['goUsers_fullName'] = "Fullname";
$lang['goUsers_additionalSeats'] = "Additional Seats";
//Modify User
$lang['goUsers_modify'] = "Modify User";
$lang['goUsers_phoneLogin'] = "Phone Login";
$lang['goUsers_phonePassword'] = "Phone Password";
$lang['goUsers_hotkeys'] = "HotKeys";
$lang['goUsers_update'] = "Update";
$lang['goUsers_updateSuccessful'] = "Update Successful";
$lang['goUsers_modifySameUserLevel'] = "Modify Same User Level";
$lang['goUsers_userLevel'] = "User Level";
// Delete popup form
$lang['goUsers_deleteQuestion'] = "Do you really want to delete this agent?";
$lang['goUsers_successDelete'] = "Success: User(s) deleted";
$lang['goUsers_successDeleteBatch'] = "Success: complete action batch";
//USERSTATUS.PHP
$lang['goUsers_userStatus'] = "User Status";
$lang['goUsers_agentLoggedInAtServer'] = "Agent Logged-in at server";
$lang['goUsers_inSession'] = "In Session";
$lang['goUsers_fromPhone'] = "From Phone";
$lang['goUsers_agentIsInCampaign'] = "Agent is in campaign";
$lang['goUsers_hangupLastCallAt'] = "Hangup last call at";
$lang['goUsers_closerGroups'] = "Closer Groups";
$lang['goUsers_emergencyLogout'] = "Emergency Logout";
$lang['goUsers_selectDateRange'] = "Select Date Range";
$lang['goUsers_agentTalkTimeAndStatus'] = "Agent Talk Time and Status";
$lang['goUsers_outboundCallsForThisTimePeriod1k'] = "Outbound calls for this time period (limit register 1000)";
$lang['goUsers_inboundCloserCallsForThisTimePeriod1k'] = "Inbound / Closer calls for this period (1000 entry limit)";
$lang['goUsers_agentActivityForThisTimePeriod1k'] = "Agent activity for this time period (1000 limit)";
$lang['goUsers_recordingForThisTimePeriod'] = "Recording for this time period (limit register 1000)";
$lang['goUsers_manualOutboundCallsForThisTimePeriod'] = "Manual outbound calls for this time period (limit register 1000)";
$lang['goUsers_dialed'] = "Dialed";
$lang['goUsers_callerId'] = "Caller ID";
$lang['goUsers_alias'] = "Alias";
$lang['goUsers_preset'] = "Preset";
$lang['goUsers_c3hu'] = "C3HU";
$lang['goUsers_leadSearchesForThisTimePeriod1k'] = "Lead searches for this time period (limit register 1000)";
$lang['goUsers_errorSessionExpiredPleaseRelogin'] = "Error: session expired please login again";
$lang['goUsers_errorOneOrMoreOfThePhoneLogin'] = "Error: One or more of the telephone sets already specified there.";
$lang['goUsers_errorEmptyData'] = "Error: Empty data";
$lang['goUsers_errorEmptyServerInformation'] = "Error: Empty server information";
$lang['goUsers_errorSomethingWentWrongToYourData'] = "Error: Something went wrong to your data";
$lang['goUsers_errorSomethingWentWrongEitherOnSaving'] = "Error: Something went wrong, either on saving or reload asterisk";
$lang['goUsers_errorSomethingWentWrongInSavingData'] = "Error: Something went wrong in saving data";
$lang['goUsers_errorEmptyRawDataKindlyCheckYourData'] = "Error: Empty raw data kindly check your data";
$lang['goUsers_thereIsNoDataToBeProcess'] = "There is no data to be process";
$lang['goUsers_cantReloadAsteriskSorry'] = "Unable to reload asterisk sorry";
$lang['goUsers_ohNoSomethingWentWrongOnReloadingAsterisk'] = "Oh no something went wrong on reloading asterisk";
$lang['goUsers_errorYouAreNotAllowedToModifyUser'] = "Error: You are not allowed to modify user";
$lang['goUsers_errorYouAreNotAllowedToDeleteUser'] = "Error: You are not allowed to delete user";
$lang['goUsers_gotProblemInRetrievingGroups'] = "Got a problem in retrieving groups contact your administrator";
$lang['goUsers_userAddedToActiveGroup'] = "User added to active group ";
$lang['goUsers_errorNoUserIsLoggedIn'] = "Error: No user is logged in";
$lang['goUsers_errorNoUserIdPassed'] = "Error: No user id passed";
$lang['goUsers_destroySessionAndRedirectToLogin'] = "Destroy session and redirect to login";
$lang['goUsers_emergencyLogoutComplete'] = "Emergency logout full makeup agent browser is closed";
$lang['goUsers_problemInAttempt'] = "Problem in trying to agent log";
$lang['goUsers_isNotLoggedIn'] = "Is not logged in";
$lang['goUsers_errorSomethingWentWrongWhileSavingUser'] = "Error: Something went wrong while saving";
$lang['goUsers_successBatchActionComplete'] = "Éxito: Batch action complete";
$lang['goUsers_errorNoUsersSelected'] = "Error: No users selected";
$lang['goUsers_successUserCopyComplete'] = "Éxito: User copy complete";
$lang['goUsers_errorUnknownAction'] = "Error: Unknown action";
$lang['goUsers_youDontHavePermissionTo'] = "You don't have permission to";
$lang['goUsers_thisRecords'] = "this record(s)";
$lang['goUsers_errorOnly150Agents'] = "Error: Only 150 agents allowed. Please contact our support team to add more agents. Thanks";
$lang['goUsers_youDontHavePermissionToViewThisRecords'] = "You do not have permission to view this record ";
$lang['goUsers_somethingWentWrongContactYourSupport'] = "Something went wrong contact the Support";
$lang['goUsers_invalidUserFormat'] = "Invalid user format";
$lang['goUsers_invalidPassword'] = "Invalid Password";
$lang['goUsers_errorUserGroupMustNotBeEmpty'] = "Error: User group must not be empty";
$lang['goUsers_errorConditionsAreEmptyOrNotAnArray'] = "Error: Conditions are not empty or not an array";
$lang['goUsers_errorTableNotExistOrFieldsetEmpty'] = "Error: Table not exist or fieldset empty";
$lang['goUsers_conditionsMustBeAnArray'] = "Condition must be an array";
$lang['goUsers_somethingWentWrongSorry'] = "Something went wrong sorry";
$lang['goUsers_errorOnUpdatingUser'] = "Error on updating user!";
$lang['goUsers_updateSuccessful'] = "Update successful!";
$lang['goUsers_groupConfiguration'] = "Group Configuration";
$lang['goUsers_groupName'] = "Group Name";
$lang['goUsers_groupLevel'] = "Group Level";
$lang['goUsers_addAccess'] = "Add success";
$lang['goUsers_removeAccess'] = "Remove access";
$lang['goUsers_somethingWentWrongOnCreatingNewGroup'] = "Something went wrong on creating new group";
$lang['goUsers_somethingWentWrongOnSavingNewGroup'] = "Something went wrong on saving new group";
$lang['goUsers_saved'] = "Saved";
$lang['goUsers_fieldsAreEmpty'] = "Fields are empty";
$lang['goUsers_wrongConditionFormat'] = "Wrong condition format";
$lang['goUsers_itemDeleted'] = "Items deleted";
$lang['goUsers_mustHaveConditionToDeleteAnItem'] = "Must have conditions to delete an item";
$lang['goUsers_errorOnPassingUserAccountOrUserGroup'] = "Error on passing user account or user group";
$lang['goUsers_displaying'] = "Displaying";
$lang['goUsers_to'] = "to";
$lang['goUsers_of'] = "of";
$lang['goUsers_warning1'] = "WARNING: When creating additional users with telephone extension will put you over the limit";
$lang['goUsers_warning2'] = ". That means users no longer have newly added telephone extensions added to them.";
$lang['goUsers_clearSearch'] = "[Clear Search]";
// banners
$lang['go_dashboard_banner']	= 'Dashboard';
$lang['go_users_banner']	= 'Users';
$lang['go_campaign_banner']	= 'Campaigns';
$lang['go_lists_banner']	= 'Lists';
$lang['go_scripts_banner']	= 'Scripts';
$lang['go_inbound_banner']	= 'Inbound';
$lang['go_voicefile_banner']	= 'Voice Files';
$lang['go_calltimes_banner']	= 'Call Times';
$lang['go_carriers_banner']	= 'Carriers';
$lang['go_phones_banner']	= 'Phones';
$lang['go_tenants_banner']	= 'Multi-Tenant';
$lang['go_servers_banner']	= 'Servers';
$lang['go_settings_banner']	= 'System Settings';
$lang['go_usergroups_banner']	= 'User Groups';
$lang['go_voicemails_banner']	= 'Voicemails';
$lang['go_reports_banner']	= 'Reports & Analytics';
//HEADER - go_dashboard_header.php
$lang['go_Hello'] = "Hello";
$lang['go_Logout'] = "Logout";
$lang['go_Edityourprofile'] = "Edit your profile";
$lang['go_ModifyUser'] = "Modify user";
$lang['go_AgentID_'] = "Agent ID";
$lang['go_Password_'] = "Password";
$lang['go_FullName_'] = "Full Name";
$lang['go_PhoneLogin'] = "Phone Login";
$lang['go_PhonePass'] = "Phone Pass";
$lang['go_SUBMIT'] = "SUBMIT";
$lang['go_Showonscreen'] = "Show on screen";
//go_site.php
$lang['go_Dashboard'] = "Dashboard";
$lang['go_Clicktotoggle'] = "Click to toggle";
$lang['go_Refreshthiswidget'] = "Refresh this widget";
$lang['go_TodayStatus'] = "Today Status";
$lang['go_AccountInformation'] = "Account Information";
$lang['go_GOAnalytics'] = "GO Analytics";
$lang['go_Agents_LeadsStatus'] = "Agents & Leads Status";
$lang['go_Agents_Phones'] = "Agents & Phones";
$lang['go_ClusterStatus'] = "Cluster Status";
$lang['go_ServerStatistics'] = "Server Statistics";
$lang['go_SystemVitals'] = "System Vitalsa";
$lang['go_Hardware'] = "Hardware";
$lang['go_MemoryUsage'] = "Memory Usage";
$lang['go_Filesystems'] = "File Systems";
$lang['go_SystemServices'] = "System Services";
$lang['go_SERVICE'] = "SERVICE";
$lang['go_Telephony'] = "Telephony";
//GoAutodial Community & Forum
$lang['go_autodialComForum'] = "GoAutoDial Community & Forum";

//GoAutodial News
$lang['go_autodialNews'] = "GoAutoDial News";

//go_dashboard_sippy.php
$lang['go_Balance'] = "Balance";
$lang['go_RemainingMinutes'] = "Remaining minutes";
$lang['go_RemainingMinutesTooltip'] = "The remaining minutes are based in the United States and Canada call rate.";
$lang['go_ClickheretosignupandactivateGoAutoDialsJustGoVoIPcarrier'] = "Click here to register and activate carrier JustGoVoIP of GoAutoDial";
$lang['go_ClickheretologintoyourJustGoVoipaccountandloadcredits'] = "Click here to access your account and credits JustGoVoip load";
$lang['go_Signupforfree'] = "Sign up for free";
$lang['go_60minutes'] = "60 minutes";
$lang['go_SignupforfreeTooltip'] = "Click here to register and activate JustGoVoip of GoAutoDial carrier.";
$lang['go_CarrierStatus'] = "Carrier Status";
$lang['go_AccountNumber'] = "Account Number";

$lang['go_AccountInformations'] = "Account Informations";
$lang['go_Firstname_'] = "Firstname";
$lang['go_Lastname_'] = "Lastname";
$lang['go_Phone_'] = "Phone";
$lang['go_Address_'] = "Address";
$lang['go_City_'] = "City";
$lang['go_State_'] = "State";
$lang['go_ZipCode'] = "Zip Code";
$lang['go_Zip'] = "Zip";
$lang['go_Country_'] = "Country";

$lang['go_CarrierInformation'] = "Carrier Information";
$lang['go_Carriername'] = "Carrier Name";
$lang['go_CarrierID'] = "Carrier ID";
$lang['go_WebUsername'] = "Web Username";
$lang['go_WebPassword'] = "Web Password";
$lang['go_VoIPUsername'] = "VoIP Username";
$lang['go_VoIPPassword'] = "VoIP Password";
$lang['go_show'] = "show";
$lang['go_hide'] = "hide";
$lang['go_Clickheretohide'] = "Click here to hide";
$lang['go_MinutesremainingisbasedonUSandCanadacallrate'] = "The remaining minutes are based in the United States and Canada call rate";

//go_account_get_logins.php
$lang['go_NumberofAgents'] = "Number of agent(s)";
$lang['go_Agents'] = "Agents";
$lang['go_URLResources'] = "URL Resources";
$lang['go_AgentLoginURL'] = "Agent Login URL";
$lang['go_SIP_ServerDomain'] = "SIP / Server Domain";
$lang['go_Clickheretoshowmore'] = "Click here to show more";

//go_cluster_status.php
$lang['go_SERVERID'] = "SERVER ID";
//$lang['go_DESCRIPTION'] = "";
$lang['go_SERVERIP'] = "SERVER IP";
//$lang['go_STATUS'] = "";
$lang['go_LOAD'] = "LOAD";
$lang['go_CHANNELS'] = "CHANNELS";
$lang['go_DISK'] = "DISK";
$lang['go_TIME'] = "TIME";
$lang['go_VERSION'] = "VERSION";
//go_dashboard_sales.php
$lang['go_Sales'] = "Sales";
$lang['go_TotalSales'] = "Total Sales";
$lang['go_InboundSales'] = "Inbound Sales";
$lang['go_OutboundSales'] = "Outbound Sales";
$lang['go_INSales_Hour'] = "In Sales / Hour";
$lang['go_OUTSalesHour'] = "OUT Sales / Hour";

//go_dashboard_calls.php
$lang['go_Calls'] = "Calls";
$lang['go_CallsRinging'] = "Call(s) Ringing";
$lang['go_CallsinOutgoingQueue'] = "Calls in Outgoing Queue";
$lang['go_CallsinIncomingQueue'] = "Calls in Incoming Queue";
$lang['go_LiveInbound'] = "Live Inbound";
$lang['go_LiveOutbound'] = "Live Outbound";
$lang['go_INCallsaMinute'] = "IN Call(s)> a Minute";
$lang['go_OUTCallsaMinute'] = "OUT Call(s)> a Minute";
$lang['go_TotalCalls'] = "Total Calls";
$lang['go_Clicktoseecallsbeingplaced'] = "Click to see calls being placed";

//go_dashboard_drop_percentage.php
$lang['go_DroppedCallPercentage'] = "Dropped Call Percentage";
$lang['go_DroppedPercentage'] = "Dropped Percentage";
$lang['go_DroppedCalls'] = "Dropped Calls";
$lang['go_AnsweredCalls'] = "Answered Calls";
$lang['go_Clicktoseethelistof'] = "Click to see the list of";

//go_dashboard_agents.php
$lang['go_AgentsResources'] = "Agents Resources";
$lang['go_AgentsonCall'] = "Agent(s) on Call";
$lang['go_AgentsonPaused'] = "Agent(s) on Paused";
$lang['go_AgentsWaiting'] = "Agent(s) Waiting";
$lang['go_TotalAgentsOnline'] = "Total Agents Online";
$lang['go_Clicktomonitoragents'] = "Click to monitor agents";

//go_dashboard_leads.php
$lang['go_LeadsResources'] = "Lead Resources";
$lang['go_LeadsinHopper'] = "Leads in Hopper";
$lang['go_DialableLeads'] = "Dialable Leads";
$lang['go_TotalActiveLeads'] = "Total Active Leads";
$lang['go_CampaignsResources'] = "Campaign Resources";

//go_call_realtime.php
$lang['go_NOCALLSAVAILABLE'] = "NO CALLS AVAILABLE";
$lang['go_CallMonitoring'] = "Call Monitoring";
$lang['go_LEGEND'] = "LEGEND";
$lang['go_OutboundCalls'] = "Outbound Calls";
$lang['go_InboundCalls'] = "Inbound Calls";

//go_realtime.php
$lang['go_NOAGENTSLOGGEDIN'] = "NO AGENTS LOGGED IN";
$lang['go_AgentMonitoring'] = "Agent Monitoring";
$lang['go_clickagentnametolistenbarge'] = "click to listen agent name / barge";
$lang['go_RemoteAgentsarenotshownontheabovelist'] = "Remote Agents are not shown on the above list";
$lang['go_WaitingForCall'] = "Waiting For Call";
$lang['go_OnCall'] = "On Call";
$lang['go_OnPause'] = "On Pause";
$lang['go_DeadCall'] = "Dead Call";

//go_dropped_calls.php
$lang['go_ACTIVECAMPAIGNSTATISTICS'] = "ACTIVE CAMPAIGN STATISTICS";
$lang['go_ACTIVEINGROUPSTATISTICS'] = "ACTIVE INGROUPS STATISTICS";

//go_values.php
$lang['go_FAILED'] = "FAILED";
$lang['go_SUCCESS'] = "SUCCESS";
$lang['go_List'] = "List";
$lang['go_modified'] = "modified";
$lang['go_deleted'] = "deleted";
$lang['go_notactivated'] = "not activated";
$lang['go_activated'] = "activated";
$lang['go_deactivated'] = "deactivate";
$lang['go_notdeactivated'] = "not deactivated";
$lang['go_SUBTOTALS'] = "SUBTOTALS";
$lang['go_Clicktoviewreports'] = "Click to view reports";
$lang['go_Examplecustomform'] = "Example Custom Form";
$lang['go_ERRORYoumustenterafieldlabelfieldnameandfieldsize'] = "ERROR: You must enter a field label, field name and field size";
$lang['go_ERRORFieldalreadyexistsforthislist'] = "ERROR: Field already exists for this list";
$lang['go_fielddeleted'] = "field deleted";
$lang['go_ERRORYoucannotcopyfieldstothesamelist'] = "ERROR: Unable to copy the fields to the same list";
$lang['go_ERRORSourcelisthasnocustomfields'] = "ERROR: Source list has no custom fields.";
$lang['go_ERRORTabledoesnotexist'] = "ERROR: table doesn't exist";
//page5

//go_copycustomfield.php
$lang['go_CustomFieldWizard__CreateCustomField'] = "Wizard Custom Field >> Create Custom Field";
$lang['go_CustomFieldWizard__CopyCustomField'] = "Custom Field >> Copy Wizard Custom Field";
$lang['go_CustomFieldWizard__CreateCopyCustomField']="Custom Field Wizard » Create / Copy Custom Fields";
$lang['go_CreateCustomField'] = "Create Custom Field";
$lang['go_CopyFieldstoAnotherList'] = "Copy fields to another list";
$lang['go_ListIDtoCopyFieldsFrom'] = "List ID to Copy Fields Form";
$lang['go_CopyOption'] = "Copy Option";
$lang['go_Youdonthavepermissiontocreatethisrecords'] = "You do not have permission to create this record(s)";
$lang['go_Youdonthavepermissiontoupdatethisrecords'] = "You do not have permission to update this record(s)";
$lang['go_AreyousureyouwanttodeletetheselectedDNCnumber'] = "Are you sure you want to delete the selected DNC number?";


$lang['go_ListIDisarequiredfield'] = "List ID is a required field";
$lang['go_Phonenumber'] = "Phone Number";
$lang['go_fromtheDNClist']= "from the DNC List";

$lang['go_Labelisarequiredfield'] = "Label is a required field";
$lang['go_Nameisarequiredfield'] = "Name is a required field";
$lang['go_Rankisarequiredfield'] = "Rank is a required field";
$lang['go_Orderisarequiredfield']= "Order is a required field";
$lang['go_Fieldmaxisarequiredfield'] = "Field max is a required field";
$lang['go_Fieldsizeisarequiredfield'] = "Field size is a required field";
$lang['go_Labelshouldnotbeequalto'] = "Lable should not be equal to";

$lang['go_SUCCESSCustomFieldAdded'] = "SUCCESS: Custom Field Added";
$lang['go_CustomField'] = "Custom Field";
$lang['go_Changedate'] = "Change Date";


//go_list.php

$lang['go_Confirmtodeletethelist'] = "Confirm to delete the list";
$lang['go_andallofitsleads'] = "and all of its leads?";

$lang['go_ShowLists'] = "Show Lists";
$lang['go_Lists'] = "Lists";
$lang['go_ListsTooltip'] = "Did you know? Campaigns can use the multiple list. This allows you greater freedom to choose the phone numbers to call based on the list you want active on the campaign.";
$lang['go_CreateNewList'] = "Create New List";
$lang['go_CreateNewField'] = "Create New Field";
$lang['go_SearchLists'] = "Search Lists";
$lang['go_AddDeleteDNCNumbers'] = "Add / Delete DNC Numbers";
$lang['go_SearchForALead'] = "Search for a Lead";
$lang['go_ShowListsTabTooltip'] = "Show Lists Tab - displays all List ID's created on the account along with the relevant information regarding each List ID.";
$lang['go_CustomFieldsTabTooltip'] = "Custom Fields tab - Allow admin to add a field in the file lead. This field will show on the agent webpage during a live call giving them additional data on the phone number being called. Agents will need to press the Custom Form button to see this information.";
$lang['go_CustomFields'] = "Custom Fields";
$lang['go_LoadLeads'] = "Load Leads";
$lang['go_DNCNumbers'] = "DNC Numbers";
$lang['go_CallRecordingsLeadSearch'] = "Call Recordings Lead Search";
$lang['go_Process'] = "Process";
$lang['go_CopyCustomField'] = "Copy Custom Fields";
$lang['go_ListIDtoCopyFieldsFrom'] = "List ID to copy Fields from";
$lang['go_CopyFieldstoAnotherList'] = "Copy fields to another list ";
$lang['go_CopyOption'] = "Copy Option";
$lang['go_APPEND'] = "APPEND";
$lang['go_UPDATE'] = "UPDATE";
$lang['go_REPLACE'] = "REPLACE";
$lang['go_ListIDdefinesthelistIDthatwillcontaintheleadfile'] = "List ID - defines the List ID that will contain the lead file.";
$lang['go_Labels'] = "Labels";
$lang['go_Rank'] = "Rank";
$lang['go_Order'] = "Order";
$lang['go_Position'] = "Position";
$lang['go_Options'] = "Options";
$lang['go_OptionPosition'] = "Option Position";
$lang['go_TypeTooltip'] = "Type - Defines the type of data to be displayed on the custom form.";
$lang['go_FieldSize'] = "Field size";
$lang['go_FieldSizeTooltip'] = "Field Size - allows admin to specify the size of the field that will appear on the agent webpage. The value is based on the number of characters (ie, 8 = 8 characters in length).";
$lang['go_FieldMax'] = "Field Max";
$lang['go_FieldMaxTooltip'] = "The Max - allows admin to set the maximum number of characters for a field. If Field Max is greater than the Fields Size the extra characters will be seen outside the Field box on the agent webpage.";
$lang['go_FieldDefault'] = "Field Default";
$lang['go_FieldRequired'] = "Field Required";
$lang['go_ListWizard__CreateNewList'] = "Assistant Product List » Create new list";
$lang['go_numericonly'] = "numeric only";
$lang['go_ListName'] = "List Name";
$lang['go_alphanumericonly'] = "alphanumeric only";
$lang['go_ListDescription'] = "List Description";
$lang['go_ResetTimes'] = "Reset Times";
$lang['go_ResetLeadCalledStatus'] = "Reset Lead-Called-Status";
$lang['go_NAME'] = "NAME";
$lang['go_NAMETooltip'] = "Name - can be edited to allow admin to give a brief description of the list.";
$lang['go_Cannotdelete'] = "Cannot delete";
$lang['go_Download'] = "Download";
$lang['go_VIEWINFOFORLIST'] = "VIEW INFO FOR LIST";
$lang['go_Norecordsfound'] = "No record(s) found";
$lang['go_Lastcalldate'] = "Last call date";
$lang['go_CAMPAIGNASSIGNED'] = "CAMPIGN ASSIGNED";
$lang['go_CUSTOMFIELDS'] = "CUSTOM FIELDS";
$lang['go_Leadsfile'] = "Leads File";
$lang['go_LeadsfileTooltip'] = "Browse Button - allows admin to upload a lead file on your local drive or network.";
$lang['go_ListIDTooltips'] = "List ID - specifies the List ID that will use the custom field.";
$lang['go_PhoneCode'] = "Phone Code";
$lang['go_PhoneCodeTooltip'] = "<b> Phone Code </b> - specifies the country where the phone numbers on your lead file are located.";
$lang['go_LoadfromLeadFile'] = "Load from Lead File";
$lang['go_IfyouselectLoadfromLeadFilesbesuretocheckyourphonecodefromyourfile'] = "If you select Load from Lead files, be sure to check your phone code from your file";
$lang['go_DuplicateCheck'] = "Duplicate check";
$lang['go_TimeZone'] = "Time Zone";
$lang['go_DuplicateCheckTooltip'] = "<b> Duplicate Check </b> - Will check phone numbers on the lead file and cross reference it with all phone numbers on a specific campaign or in all List ID.";
$lang['go_TimeZoneTooltip'] = "<b> Time Zone </b> - Will affect the call settings of your campaign. Selecting Country Code and Area Code Only will set the call time settings based on the country code and area code of the phone number. Postal Code First will be based it on zip code of phone number (zip code field is required), Owner Time Code First will based it on the time zone set on the field of the lead file.";
$lang['go_PHONE'] = "PHONE";
$lang['go_FULLNAME'] = "FULLNAME";
$lang['go_SearchAltPhone'] = "Search Alt Phone";
$lang['go_FirstName'] = "First Name";
$lang['go_LastName'] = "Last Name";

$lang['go_SHOWADVANCEFIELDS'] = "SHOW ADVANCE FIELDS";
$lang['go_Customfieldswillshowhereifyouhaveenableditonthelistidyouprovided'] = "Custom fields will be displayed here if enabled in the id list previously provided";
$lang['go_Checkthisboxifyouwanttoshowtheresult'] = "Check this box if you want to show the result";
$lang['go_FROM'] = "FROM";
$lang['go_TO'] = "TO";
$lang['go_OPTION'] = "OPTION";
$lang['go_Typethenumberatthetoprightsearchbox'] = "Type the number at the top right search box";

$lang['go_LEADID'] = "LEAD ID";
$lang['go_LASTAGENT'] = "LAST AGENT";
$lang['go_LeadSearchOptions'] = "Lead Search Options";
$lang['go_searchwdate'] = "Search Date";
$lang['go_LeadID'] = "Lead ID";

$lang['go_LastAgent'] = "Last Agent";
$lang['go_Comments'] = "Comments";
$lang['go_Clicktabstoswapbetweencontentthatisbrokenintologicalsections'] = "Click tabs to swap between content that is broken into logical sections";
//jin
$lang['go_RecordingsforthisLead'] = "Recordings for this lead";
$lang['go_Location'] = "Location";
$lang['go_Seconds'] = "Seconds";
$lang['go_DateTime'] = "Date / Time";
$lang['go_Group'] = "Group";
$lang['go_Talk'] = "Talk";
$lang['go_Wait'] = "Wait";
$lang['go_Pause']= "Pause";
$lang['go_AgentLogRecordsforthisLead'] = "Agent Log Records for this lead";
$lang['go_Length'] = "Length";
$lang['go_HangupReason'] = "Hangup Reason";
$lang['go_CloserRecordsforthisLead'] = "Closer records for this leads";
$lang['go_CallstothisLead'] = "Calls to this lead";
$lang['go_OtherInfo'] = "Other info";
$lang['go_Leadsearchbydaterangeislimitedto60daysonly'] = "Lead Search by date range is limited to 60 days";
$lang['go_LeadInformation'] = "Lead Information";
$lang['go_PhoneNumber'] = "Phone Number";
$lang['go_AltPhone'] = "Alt. Phone"; 
$lang['go_ModifyVicidialLogs'] = "Modify Vicidial Logs";
$lang['go_ModifyAgentLogs'] = "Modify Agents Logs";
$lang['go_ModifyCloserLogs'] = "Modify Closer Logs";
$lang['go_AddCloserLogRecord'] = "Add Closer Log Record";
$lang['go_HIDEADVANCEFIELDS'] = "HIDE ADVANCE FIELDS";
$lang['go_SHOWADVANCEFIELDS'] = "SHOW ADVANCE FIELDS";

$lang['go_autogenerated'] = "auto generated";

//go_script_main_ce.php
$lang['Script'] = "Scripts";
$lang['go_AddNewScript'] = "Add New Script"; 
$lang['go_Advance'] = "Advance";
$lang['go_ModifyScript'] = "Modify Script";
$lang['go_ScriptID_'] = "Script ID:";
$lang['go_ScriptName_'] = "Script Name:";
$lang['go_ScriptComments_'] = "Script Comments:";
$lang['go_ScriptText_'] = "Script Text:";
$lang['go_Preview'] = "Preview";
$lang['go_Previewscript_'] = "Preview Script";
$lang['go_ScriptWizard'] = "Script Wizard";
$lang['go_ScriptType'] = "Script Type";
$lang['go_Pleaseenteratleast3characterstosearch'] = "Please enter atleast 3 characters to search";
$lang['go_Default'] = "Default";

//go_script_ce.php
$lang['go_Youdonthavepermissiontoviewthisrecords'] = "You don't have permission to view this record(s)";
$lang['go_scripts'] = "Scripts";
$lang['go_ErrorNoscripttoprocess'] = "Error: No script to process";
$lang['go_ErrorSomethingwentwrongwhiledeletingdata'] = "Error: Something went wrong while deleting data";
$lang['go_SuccessItemdeletedcomplete'] = "Éxito: Item deleted complete";
$lang['go_ErrorYouarenotallowedtodeletethis'] = "Error: Yoou are not allowed to delete this";
$lang['go_ErrorEmptyscriptid'] = "Error: Empty script id";
$lang['go_Insert'] = "Insert";
$lang['go_ChooseCampaign'] = "Choose Campaign";
$lang['go_ScriptPreview'] = "Script Preview";
$lang['go_Save'] = "Save";
$lang['go_Accounts'] = "Accounts";
$lang['go_Language'] = "Language:";
$lang['go_Description_Comments'] = "Description/Comments";
$lang['go_Closing_EndMessage'] = "Cosing/End Message";
$lang['go_Post_EndURL'] = "Post/URL End";
$lang['go_URLDescription'] = "URL Description";
$lang['go_WouldyouliketoconfigureyoursurveyquestionsoryourLimeSurveysurveyinadvancemodenow'] = "Would like to configure your question survey or poll <br/> LimeSurvey in advance mode now?";
$lang['go_ErrorEmptydatavariables'] = "Error: Empty data variables!";
$lang['go_SuccessNewlimesurveycreated'] = "Éxito: New Limesurvey created";
$lang['go_Erroronsavingdatacontactyoursupport'] = "Error on saving data contact your support";
$lang['go_Errornodatatoprocess'] = "Error: no data to process";
$lang['go_AutoGenerated'] = "Auto-generated";
$lang['go_Errorpassingnotanobjectvariable'] = "Error: passing not an object variable";
$lang['go_ErrorPassingemptydatacontactyoursupport'] = "Error: Passing empty data contact your support";
$lang['go_ErrorPleaseclickyourquestion'] = "Error: Please click your question";
$lang['go_ErrorYouhavenogroupinlimesurveycontactyoursupportplease'] = "Error: You have no group limesurvey contact your support please";
$lang['go_ErrorSomethingwentwrongwhilesavingnewquestion'] = "Error: Something went wrong while saving new question";
$lang['go_SuccessSurveysuccessfullymodified'] = "Success: Survey successfully modified";
$lang['go_ErrorEmptyrawdatainsavinglimesurveyconfigs'] = "Error: Empty raw data in saving limesurvey configs";
$lang['go_ErrorUnknownaction'] = "Error: Unknown action";
$lang['go_WarningYoudonthavepermissionto'] = "Warning: You don't have permission to";
$lang['go_thisrecords'] = "this record(s)";

//go_script.php
$lang['go_Erroronsavingdata'] = "Error on saving data";
$lang['go_ErrorPleasecheckyourvariables'] = "Error: Please check your variables";
$lang['go_ErrorPleasecompleteyourdata'] = "Error: Please complete your data";
$lang['go_ErrorEmptyvariables'] = "Error: Empty variables";
$lang['go_Unfinishedpart'] = "Unfinished part";
$lang['go_Erroronsavingdatain'] = "Error on saving data in";
$lang['go_Erroronsavingdatainlimesurvey'] = "Error on saving data in limesurvey";
$lang['go_ErrorNousersubmitted'] = "Error: No user submitted";
$lang['go_ErrorEmptyrawdatawhileaddingnewscript'] = "Error: Empty raw data while adding new script";
$lang['go_SuccessNewdefaultscriptcreated'] = "Exitos New default script created";
$lang['go_ErrorSomethingwentwrongpleasecontactyoursupport'] = "Error: Something went wrong please contact your support";

$lang['go_ErrorInvalidscriptidformat'] = "Error: Invalid script ID format";
$lang['go_ErrorInvalidscriptnameformat'] = "Error: Invalid script Name format";
$lang['go_ErrorInvalidscripttextformat'] = "Error: Invalid script Text format";  

$lang['go_SuccessUpdatesuccessful'] = "Success: Update Successful";
$lang['go_ErrorEmptydatafields'] = "Error: Empty data fields";


//go_script_elem_ce.php
$lang['go_SCRIPTID'] = "SCRIPT ID";
$lang['go_SCRIPTNAME'] = "SCRIPT NAME";
$lang['go_TYPE'] = "TYPE";
$lang['go_USERGROUP'] = "USER GROUP";
$lang['go_Modifyscript'] = "Modify Script";
$lang['go_Deletescript'] = "Delete Script";
$lang['go_EnableSelected'] = "Enabled Selected";
$lang['go_DisableSelected'] = "Disabled Selected";
$lang['go_ALLUSERGROUPS'] = "ALL USER GROUPS";

//go_script_advance_ce.php
$lang['go_Scriptsettings'] = "Script Settings";
$lang['go_Addquestions'] = "Add Questions";
$lang['go_NoteExistingLimesurveyscriptscantbeeditedCreateanewoneifyouneedtoaddeditquestions'] = "Note: Existing Limesurvey scripts can't be edited. Create a new one if you need to added it questions";
$lang['go_SurveyURL'] = "Survey URL";
$lang['go_SurveyName']= "Survey Name";
$lang['go_SurveyDescription'] = "Survey Description";
$lang['go_WelcomeMessage'] = "Welcome Message";
$lang['go_EndMessage'] = "End Message";
$lang['go_BaseLanguage'] = "Base Language";
$lang['go_Administrator'] = "Administrator";
$lang['go_AdminEmail'] = "Admin Email";
$lang['go_EndURL'] = "End URL";
$lang['go_EndURLDescription'] = "End URL Description";
$lang['go_DecimalSeparator'] = "Decimal Separator";
$lang['go_TITLE'] = "TITLE";
$lang['go_QUESTION'] = "QUESTION";
$lang['go_Question'] = "Question";
$lang['go_MANDATORY'] = "MANDATORY";
$lang['go_Code'] = "Code";
$lang['go_Help'] = "Help";
$lang['go_Type'] = "Type";
$lang['go_Mandatory'] = "Mandatory";
$lang['go_Validation'] = "Validation";
$lang['go_Format'] = "Format";
$lang['go_Template'] = "Template";
$lang['go_TemplatePreview'] = "Template Preview";
$lang['go_Showwelcomescreen'] = "Show welcome screen?";
$lang['go_Navigationdelayseconds'] = "Navigation delay seconds";
$lang['go_Showquestionindex_allowjumping'] = "Show question index / allow jumping";
$lang['go_Keyboardlessoperation'] = "Keyboard Less Operation";
$lang['go_Showprogressbar'] = "Show progress bar";
$lang['go_Participantsmayprintanswers'] = "Participants may print answers";
$lang['go_Publicstatistics'] = "Public Statistics";
$lang['go_Showgraphsinpublicstatistics'] = "Show graphs in public statistics";
$lang['go_AutomaticallyloadURLwhensurveycomplete'] = "Automatically load URL when survey complete?";
$lang['go_ShowThereareXquestionsinthissurvey'] = "Show there are 'X question in this survey'";	
$lang['go_Showgroupnameandorgroupdescription'] = "Show group name and / group description";
$lang['go_Showquestionnumberandorcode'] = "Show question number and / or code";


//go_widget_show_me_more


//header tab [notification]
//show on screen


//CAMPAIGNS
$lang['go_icon32Tooltip']     = "A Campaign is a unique feature that allows you to modify and change the behavior of the system according to the needs of its customers.";
$lang['go_Campaigns']         = "Campaigns";
$lang['go_CAMPAIGN']	      = "CAMPAIGN";
$lang['go_CAMPAIGNID']        = "CAMPAIGN ID";
$lang['go_CAMPAIGNNAME']      = "CAMPAIGN NAME";
$lang['go_DIALMETHOD']        = "DIAL METHOD";
$lang['go_PAUSECODES']	      = "PAUSE CODES";
$lang['go_PAUSECODE']	      = "PAUSE CODE";
$lang['go_PAUSECODESTooltip'] = "Clicking the campaign ID itself or the modify icon will allow the pause code to be edited.";
$lang['go_MODIFYCAMPAIGNPAUSECODES']   = "MODIFY CAMPAIGN PAUSE CODES";
$lang['go_DELETECAMPAIGNPAUSECODES'] = "DELETE CAMPAIGN PAUSE CODES";
$lang['go_MODIFYCAMPAIGNHOTKEYS'] = "MODIFY CAMPAIGN HOTKEYS";
$lang['go_DELETECAMPAIGNHOTKEYS'] = "MODIFY CAMPAIGN HOTKEYS";
$lang['go_FILTERID']	      = "FILTER ID";
$lang['go_FILTERNAME']	      = "FILTER NAME";
$lang['go_MODIFYFILTERID']    = "MODIFY FILTER ID";
$lang['go_DELETEFILTERID']    = "DELETE FILTER ID";
$lang['go_LeadRecyclingWizard_CreateNewLeadRecycling'] = "Assistant Lead Recycling >> Create new lead recycling";
$lang['go_LeadRecyclingTab']  = "<b> Lead recycling Tab </b> - Allows admin to set the dispositions the systems will automatically recycle on the hopper when the set time limit is over. These dispositions will be called again when they are recycled on the hopper.";
$lang['go_LeadRecycling']     = "Lead Recycling";
$lang['go_NONE'] 	      = "NONE";
$lang['go_STATUS']            = "STATUS";
$lang['go_ACTION']	      = "ACTION";
// -r active
//-r avtive  selc
//-r deactive selec
//-r del selc

$lang['go_minimumChar'] = "Minimum of 3 characters";
$lang['go_available'] = "Available";
$lang['go_notAvailable'] = "Not Available";

//Campaign >> view info
$lang['go_CampaignID_']			 = "Campaign ID:";
$lang['go_CampaignName_']		 = "Campaign Name:";
$lang['go_CampaignDescription_']	 = "Campaign Description:";
$lang['go_AllowInboundAndBlended_']	 = "Allowed Inbound and Blended:";
$lang['go_DialMethod_']			 = "Dial Method:";
$lang['go_DialMethodTooltip']		 = "<center> <b> Dial Method </b> </center> </br> <b> Manual </b> - The user will have to click the <i> - Dial Next </i> button to make outgoing </br> calls. This is always done after a call has been dispositioned. </br> <b> Auto Dial </b> - Used for output type campaign. System will automatically </br>
dial phone numbers on the lead file. Number of lines is in the set on the </br> Auto Dial Level. </br> <b> Predictive </b> - It is used for outbound type of campaign. The system will automatically </br> calculate the dial level based on the drop percentage. Default drop </br> percentage is 3%. If the drop percentage is met or exceeded, the system </br> will lower down the Auto Dial Level</br> <b> Inbound Man </b> - Used for the blended type of campaign. Agents will get inbound </br> calls when they click on the Resume button. Outbound calls are done by </br> either by clicking on the Dial Next button. Or by clicking on the  Manual Dial link </br> on the Agent webpage";
$lang['go_DialMethodTooltip2']		 = "<center> <b> Auto Dial Level </b> </center> </br>
<b> Slow </b> - 1 line per available agent </br>.
<b> Normal </b> - 2 lines per available agent </br>
<b> High </b> - 4 lines per available agent </br>
<b> Max </b> - 6 lines per available agent </br>
<b> Preview </b> - Allows administrator to set how </br>
many lines per agent will be opened.";

$lang['go_AutoDialLevel_']		 = "AutoDial Level:";
$lang['go_AutoDialLevelTooltip']	 = "<center> <b> Auto Dial Level </b> </center> </br>
<b> Slow </b> - 1 line per available agent </br>.
<b> Normal </b> -. 2 lines per available agent </br>
<b> High </b> -. 4 lines per available agent </br>
<b> Max </b> -. 6 lines per available agent </br>
<b> Max predictive </b> -. 10 lines per available agent (this is for Predictive) </br>
<b> Preview </b> - Allows administrator to establish how </br>
many lines per agent is opened.";
$lang['go_AnsweringMachineDetection_']   = "Answering Machine Detection:";
$lang['go_ManualDialPrefix_'] 		 = "Manual Dial Prefix:";
$lang['go_GetCallLaunch_'] 		 = "Get Call Launch:";
$lang['go_GetCallLaunchTooltip'] 	 = "<b> Get Call Launch </b> - allows admin to automatically have </br> the webform or script popup on the Agent webpage </br> at the onset of a call without the need for the agents to click on their respective button.";
$lang['go_AnsweringMachineMessage_']	 = "Answering Machine Message:";
$lang['go_AnsweringMachineMessageTooltip']= "<b> Answering Machine Message </b> - allows admin to set a pre-recorded voice file to be played when </br> the system detects an answering machine. CPD AMD Message should be set to Message.";
$lang['go_WaitForSilenceOptions_']	 = "WaitForSilence Options:";
$lang['go_WaitForSilenceOptionsTooltip'] = "<b> WaitForSilence Options </b> - Sets the number of milliseconds the system will wait before triggering </br> The answering machine messages. Two settings, separated by a comma are needed </br> to be entered. First setting will detect the length of silence to wait (measured in milliseconds) </br> and the other is the number of times it needs to detect that before playing the pre-recorded </br> voice file.";
$lang['go_AMDSendtoVMexten_'] 		 = "AMD Send to VM exten:";
$lang['go_CPDAMDAction_']		 = "CPD AMD Action:";
$lang['go_CPDAMDActionTooltip'] 	 = "<b> CPD AMD Action </b> - defines what the system will do when it detects an answering machine.</br> Dispo will allow the system to disposition the call as AA before reaching an agent. Message </br> will allow system to auto play a voice file set on the Answering Machine Messages setting";
$lang['go_PauseCodesActive_']		 = "Pause Codes Active:";
$lang['go_AvailableOnlyTally_'] 	 = "Available Only Tally:";
$lang['go_Available']                    = "Available";
$lang['go_ManualDialFilter_']		 = "Manual Dial Filter:";
$lang['go_AgentLeadSearch_']		 = "Agent Lead Search:";
$lang['go_AgentLeadSearchMethod_']	 = "Agent Lead Search Method:";
$lang['go_NextAgentCall_']		 = "Next Agent Call:";
$lang['go_NextAgentCallTooltip']	 = "<b> Next Agent Call </b> - defines how calls will be routed to an agent.";

$lang['go_DIDTFNExtension_'] = "DID/TFN Extension:";

$lang['go_TransferConfNumber1_']	 = "Transfer-Conf Number 1:";
$lang['go_TransferConfNumber1Tooltip']	 = "<b> Transfer-Conf Number 1 and 2: </b> will store a specific phone number on the D1 and D2 that can be </br> used to auto populate the number to call box. This option is only used during a transfer calls.";

$lang['go_TransferConfNumber2_']	 = "Transfer-Conf Number 2:";
$lang['go_TransferConfNumber2Tooltip'] = "<b> Transfer-Conf Number 1 and 2: </b> - store a specific phone number in the D1 and D2 can be </br> used to auto populate the frame number to call. This option is only used during a call transfer.";

$lang['go_3WayCallOutboundCallerID_']	 = "3-Way Call Outbound CallerID:";
$lang['go_3WayCallOutboundCallerIDTooltip'] = "<b> 3-Way Call Outbound CallerID </b> - defines the caller ID that will be used during a 3-way call";

$lang['go_3WayCallDialPrefix_']		 = "3-Way Call Dial Prefix:";
$lang['go_Customer3WayHangupLogging_']	 = "Customer 3-Way Hangup Logging:";
$lang['go_Customer3WayHangupLoggingTooltip'] = "<b> Customer 3-Way Hangup Logging </b> - If this option is enabled will allow the system log if customer hungup </br> during a 3-way call. This option will also trigger the option set on the 3-Way Hangup Action";

$lang['go_Customer3WayHangupSeconds_']	 = "Customer 3-Way Hangup Seconds:";
$lang['go_Customer3WayHangupSecondsTooltip'] = "<b> Customer 3-Way Hangup Seconds </b> - specifies the amount in seconds before the system wil trigger the </br> Customer 3-Way Hangup Action";

$lang['go_Customer3WayHangupAction_']	 = "Customer 3-Way Hangup Action:";
$lang['go_AudioChooser']		 = "[ Audio Chooser ]";


//campaign >> edit >> [+]advance settings (alert: gofree)
$lang['go_AdvSetAlert1'] = "Some of the options are disabled due to restrictions of the test account.";
$lang['go_AdvSetAlert2'] = "For full functionality of the system, you can upgrade your account to any of our packages private cloud.";
$lang['go_SelectCampaign'] = "--- SELECT CAMPAIGN ---";

//campaign >> edit >> box
$lang['go_minusADVANCESETTINGS']	 = "[ - ADVANCE SETTINGS ]";
$lang['go_plusADVANCESETTINGS']		 = "[ + ADVANCE SETTINGS ]";
$lang['go_SAVESETTINGS']		 = "SAVE SETTINGS";
$lang['go_LISTWITHINCAMPAIGN']		 = "LIST WITHIN CAMPAIGN";
$lang['go_SAVE']			 = "SAVE";
$lang['go_SAVEACTIVELISTCHANGES']	 = "SAVE ACTIVE LIST CHANGES";
$lang['go_Thiscampaignhas']		 = "This Campaign has";
$lang['go_activelistsand']		 = "active lists and";
$lang['go_inactivelists']		 = "inactive lists";
$lang['go_AttemptDelay']		 = "Attempt Delay";
$lang['go_Shouldbefrom2to720mins12hrs']  = "Should be from 2 to 7 and 20 minutes (12 hours).";
$lang['go_AttemptMaximum_']		 = "Attempt Maximum:";
$lang['go_MaximumAttempts'] 		 = "Maximum Attempts";
$lang['go_PauseCodeWizard__CreateNewPauseCode'] = "Pause Code Wizard >> Create New Pause Code";
$lang['go_HotKeysWizard__CreateNewHotKey'] = "Hotkeys Wizard >> Create New Hotkeys";
$lang['go_LeadFilterWizard__CreateNewFilter'] = "Lead Filter Wizard >> Create New Lead Filter";

$lang['go_leadsinthequeue']		 = "leads in the queue (hopper dial)";
$lang['go_Viewleadsinthehopperforthiscampaign']	 = "View Leads in the hopper for this campaign";
$lang['go_VLITHFTCtooltip'] = "Clicking this link will show all phone numbers currently loaded into the hopper";
$lang['go_Logoutallagentswithinthiscampaign']	 = "Logout all Agents within this Campaign";
$lang['go_ScriptTooltip']	 	 = "<center> <b> Script </b> </center> Allows administrator to enable window popup </br> on the agent webpage during a live call (Agent needs </br> to click on the pages to get an idea <b> what a script does</b>).";

//LIST CAPITAL 
$lang['go_LISTID']			 = "LIST ID";
$lang['go_LISTIDTooltip']		 = "<b> List ID's being used by the campaign </b> - You can toggle between lists or combine them by ticking the Active </br> column box. The Modify icon allows you to edit the List ID itself.";
$lang['go_LISTNAME']			 = "LIST NAME";
$lang['go_DESCRIPTION'] 		 = "DESCRIPTION";
$lang['go_LEADSCOUNT']			 = "LEADS COUNT";
$lang['go_LEADSCOUNTTooltip']		 = "Leads Column Count - displays the total number of phone numbers that can be dialed on the list.";
$lang['go_ACTIVE']			 = "ACTIVE";
$lang['go_LASTCALLDATE']		 = "LAST CALL DATE";
$lang['go_MODIFY']			 = "MODIFY";
$lang['go_PauseCodeCampaignTooltip'] 	 = "<b> Campaign </b> - defines the campaign that will use the pause code. Campaigns can use multiple pause codes </br> but you have to setup the pause codes individually.";
$lang['go_CarriertouseforthisCampaign']  = "Carrier to use for this Campaign:";

//Campaign >> edit >> [-] advance settings
//go_CampaignID_
//go_CampaignName_
//go_CampaignDescription_
$lang['go_Active_'] 			= "Active";
$lang['go_INACTIVE'] 			= "INACTIVE"; 
//go_DialMethod_
//go_AutoDialLevel_
$lang['go_CarrierForCampaign_'] 	= "Carrier to use for Campaign:";
$lang['go_Script_'] 			= "Script:";
$lang['go_CampaignCallerID_'] 		= "Campaign Caller ID:";
$lang['go_CampaignCallerIDTooltip']	= "<b> Campaign Caller ID </b> - sets the phone number </br> that will display on the called party's phone.";
$lang['go_CampaignRecording_'] 		= "Campaign Recording:";
//go_AnsweringMachineDetection_
$lang['go_LocalCallTime_'] 		= "Local Calltime:";
$lang['go_LocalCallTimeTooltip']	= "<b> Local Call Time </b> Sets the time of window when leads will be called. This is based </br> on the actual time where the phone number is located.";

//Campaign >> edit >> [+] advance settings
$lang['go_CampaignChangeDate_']		= "Campaign Change Date:";
$lang['go_CampaignLoginDate_']		= "Campaign Login Date:";
$lang['go_CampaignCallDate_']		= "Campaign Call Date:";
$lang['go_ParkMusicOnHold_']		= "Park Music on Hold:";
$lang['go_WebForm_']			= "Web Form:";
$lang['go_WebFormTooltip'] 		= "<b> Web Form </b> - Allows admin to specify the webpage that will open </br> when an agent clicks the web form button.";
$lang['go_WebFormTarget_'] 		= "Web Form Target";
$lang['go_WebFormTargetTooltip'] 	= "<b> Web Form Target </b> - allows admin to specify the frame where the web </br> form will open. Only applicable for Multi Frame browsers.";
//go_AllowInboundAndBlended_
$lang['go_AllowInboundAndBlendedTooltip'] = "<b> Marcar Estado </b> - Especifica las disposiciones sobre el archivo principal activo (s) en la campaña que </br> el sistema marcará automáticamente. Cualquier disposiciones no incluidas en el estado de línea se </br> No se va a marcar";
$lang['go_ActiveStatusDial']              = "Active Status Dial";
$lang['go_ListOrder_'] 			  = "List Order:";
$lang['go_LeadFilter_']			= "Lead Filter:";
$lang['go_ForceResetLeadsonHopper_']	= "Force Reset Leads on Hopper:";
$lang['go_ForceResetLeadsonHopperTooltip'] = "<b> Force Reset Leads on Hopper </b> Will clear the current phone numbers loaded in the hopper that </br> are waiting to be dialed. The Hopper will automatically load a new set of numbers after a few minutes.";
$lang['go_DialTimeout_']		= "Dial Timeout:";
$lang['go_DialTimeoutTooltip'] 		= "<b> Dial Timeout </b> - specifies the number of seconds the system will attempt to dial a phone number before hanging up.";

//Displaying page number
$lang['go_Display'] 	      = "Displaying";
$lang['go_to']		      = "to";
$lang['go_of']	      	      = "of";
//pagination
$lang['go_FirstPage'] 	      = "First page";
$lang['go_PreviousPage']      = "Previous page";
$lang['go_PageNumber'] 	      = "Page number";
$lang['go_ViewAllPage']       = "View all page";
$lang['go_NextPage'] 	      = "Next page";
$lang['go_Lastpage']	      = "Last page";
$lang['go_ALL']		      = "ALL";
$lang['go_BacktoPaginatedView']= "Back to paginated view";
$lang['go_BACK']	       = "BACK";

//list
$lang['go_listids'] = "List ID";

//tooltip
$lang['go_CampaignTabTooltip']= "<b> Campaigns Tab </b> - Gives a list of campaigns created on the account and relevant information regarding the campaigns.";
$lang['go_MODIFYCAMPAIGN']    = "MODIFY CAMPAIGN";
$lang['go_DELETECAMPAIGN']    = "DELETE CAMPAIGN";
$lang['go_DELETE']	      = "DELETE";
$lang['go_VIEWCAMPAIGN']      = "VIEW CAMPAIGN";

//search
$lang['go_Search'] 	      = "Search";
$lang['go_ClearSearch']	      = "[Clear Search]";
$lang['go_NoRecordsFound']    = "No Records Found";

//add new campaign
$lang['go_AddNewCampaign']    = "Add New Campaign";
$lang['go_AddNewCampTooltip'] = "<b> Add New Campaign </b> - Allows admin to create a new campaign."; 

$lang['go_AddNewStatus']      = "Add New Status";
$lang['go_AddNewStatusTooltip'] = "Add New Status";

$lang['go_AddNewLeadRecycle'] = "Add New Lead Recycle";
$lang['go_AddNewLeadRecycleTooltip'] = "Add New Lead Recycle";

$lang['go_AddNewPauseCode']   = "Add New Pause Code";
$lang['go_AddNewPauseCodeTooltip'] = "<b> Add New Pause Code </b> - clicking this will generate the Pause Code Wizard.";

$lang['go_AddNewHotKey']      = "Add New Hotkey";
$lang['go_AddNewHotKeyTooltip'] = "<b> HotKeys </b> - are shortcuts that allow an agent to automatically disposition the call to a pre defined value by pressing a number button. Click the Add New Hotkey to initialize the Hotkey Wizard taht will assist you in setting up a new hotkey for your campaign.";
$lang['go_HotkeyTooltip']     = "<b> Hotkey </b> - defines the hotkey that will be use. Otions are 1-9.";

$lang['go_Filter']	= "Filter";
$lang['go_AddNewFilter']      = "Add New Filter";
$lang['go_AddNewFilterTooltip'] = "Add New Filter"; 
$lang['go_FilterID_'] = "Filter ID:";
$lang['go_FilterName_']= "Filter Name:";
$lang['go_FilterComments_'] = "Filter Comments:";
$lang['go_UserGroup_'] = "User Groups:";
$lang['go_Fields_'] = "Fields:";
$lang['go_FilterOptions_'] = "Filter Options:";
$lang['go_FilterbyDate_'] = "Filter by Date:";
$lang['go_FilterbyCalledCount_'] = "Filter by Called Count";
$lang['go_FilterbyCountryCode_'] = "Filter by Country Code";
$lang['go_FilterbyAreaCode_'] = "Filter by Area Code";
$lang['go_lterbyTimezone_'] = "Filter by Timezone";

$lang['go_FilterbyState_'] = "Filter by State";
$lang['go_SQLPreview_'] = "SQL Preview";
//filter options
$lang['go_FilterSQL_'] = "Filter SQL";
$lang['go_ClearSQL'] = "Clear SQL";
$lang['go_Disclamer1'] = "* Disclaimer: Improper use may result service interruption.";
$lang['go_Disclamer2'] = "* Note:";
$lang['go_Disclamer3'] = "Use at your own risk";

//Edit Campaign >> Modify List
$lang['go_ChangeDate'] 		= "Change Date:";
$lang['go_LastCallDate']	= "Last Call Date:";
$lang['go_Name_']	 		 = "Name:";
$lang['go_Description_']		 = "Description:";
//go_Campaign_
$lang['go_ResetTime_']			 = "Reset Time:";
$lang['go_ResetLeadCalledStatus_']	 = "Reset Lead Called Status:";
$lang['go_AgentScriptOverride_']	 = "Agent Script Override:";
$lang['go_CampaignCIDOverride_']	 = "Campaign CID Override:";
$lang['DropInboundGroupOverride_']	 = "Drop Inbound Group Override:";
//go_WebForm_
//go_Active_
$lang['go_TransferConfNumberOverride']	 = "Transfer-Conf Number Override";
$lang['go_Number']			 = "Number";
$lang['go_STATUSESWITHINTHISLIST']	 = "STATUSES WITHIN THIS LIST";
$lang['go_TIMEZONESWITHINTHISLIST']	 = "TIMEZONES WITHIN THIS LIST";
$lang['go_STATUSESWITHINTHELIST']        = "STATUSES WITHIN THE LIST";
$lang['go_TIMEZONESWITHINTHELIST']       = "TIMEZONES WITHIN THE LIST";
//go_STATUS
$lang['go_STATUSNAME']			 = "STATUS NAME";
$lang['go_CALLED']			 = "CALLED";
$lang['go_NOTCALLED']			 = "NOT CALLED";
$lang['go_NEW']				 = "NEW";
$lang['go_SUBTOTAL']			 = "SUBTOTAL";
$lang['go_TOTAL']			 = "TOTAL";
$lang['go_GMTOFFSETNOW']		 = "GMT OFF SET NOW";
//called
//not called


//free make
//$lang[''] = "";
$lang['go_Upload'] = "Upload";
$lang['go_ListofFilesUploaded_'] = "List of files uploaded:";
$lang['go_AllowedTransferGroup_'] = "Allowed transfer groups:";
$lang['go_NumberofLines_']	= "Number of lines:";
$lang['go_Press4DID_'] 		= "Press 4 DID:";
$lang['go_Press4Status_']	= "Press 4 Status";
$lang['go_Press4AudioFile_'] 	= "Press 4 Audio File";
$lang['go_Press4Digit_'] 	= "Press 4 Digit";
$lang['go_Press3DID_'] 		= "Press 3 DID";
$lang['go_Press3Status_'] 	= "Press 3 Status";
$lang['go_Press3AudioFile_'] 	= "Press 3 Audio File:";
$lang['go_Press3Digit_'] 	= "Press 3 digit:";

$lang['go_Press8NotInterestedStatus_']		 = "Press 8 Not interested status:";
$lang['go_Press8NotInterestedAudioFile_']	 = "Press 8 Not interested audio file:";
$lang['go_Press8NotInterestedDigit_']		 = "Press 8 Not interested digit:";
$lang['go_DID_']				 = "DID:";
$lang['go_SurveyDTMFDigits_']			 = "Survey DTMF Digits:";

$lang['go_SurveyCallMenu_'] 			 = "Survey Call Menu:";
$lang['go_SurveyMethod_']			 = "Survey Method:";
$lang['go_AudioFile_']				 = "Audio File:";

$lang['go_AssignedDID_TFN_']			 = "Assigned DID / TFN:";
$lang['go_AddADialStatustoCall_']		 = "Add a dial status on call:";
$lang['go_AvailableOnlyTally_']			 = "Available only tally:";
$lang['go_CampaignRecFilename_']		 = "Campaign Rec Filename:";
$lang['go_InboundGroups_']			 = "Inbound Groups:";
$lang['go_REMOVESTATUS_']			 = "REMOVE STATUS:";
$lang['go_customerdefinekeypress']		 = "Customer define key press";
$lang['go_Filename'] = "Filename";

//go_campaign_list.php
$lang['go_ErrorYoudonothavepermissiontomodifythiscampaign'] = "Error: You do not have permission to modify this campaign.";
$lang['go_ErrorYoudonothavepermissiontodeletecampaigns'] = "Error: You do not have permission to delete campaigns.";
$lang['go_ErrorYoudonothavepermissiontomodifycampaignstatuses'] = "Error: You do not have permission to modify campaign statuses.";
$lang['go_Erroroudonothavepermissiontodeletecampaignstatuses'] = "Error: You do not have permission to delete campaign statuses";
$lang['go_Areyousureyouwanttodeletetheselectedcampaignstatuses'] = "Are you sure you want to delete the selected campaign statuses?";
$lang['go_ErrorYoudonothavepermissiontodeleteleadrecyclingstatuses'] = "Error: You do not have permission to delete lead recycling statuses.";
$lang['go_Areyousureyouwanttodeletetheselectedcampaignpausecodes'] = "Are you sure you want to delete the selected campaign pausecodes?";
$lang['go_ErrorYoudonothavepermissiontodeletecampaignpausecodes'] = "Error: You do not have permission to delete campaign pause codes.";
$lang['go_ErrorYoudonothavepermissiontomodifycampaignpausecodes'] = "Error: You do not have permission to modify campaign pause codes.";
$lang['go_Areyousureyouwanttodeletetheselectedcampaignhotkeys'] = "Are you sure you want to delete the selected campaign hotkeys?";
$lang['go_ErrorYoudonothavepermissiontodeletecampaignhotkeys'] = "Error: You do not have permission to delete campaign hotkeys.";
$lang['go_ErrorYoudonothavepermissiontomodifycampaignhotkeys'] = "Error: You do not have permission to modify campaign hotkeys.";
$lang['go_AreyousureyouwanttodeletethisHotKey'] = "Are you sure you want to delete this Hotkey?";
$lang['go_ErrorYoudonothavepermissiontomodifyleadfilters'] = "Error: You do not have permission to modify the lead filters.";

$lang['go_Pleasecheckthefollowingerrors'] = "Please check the following error(s):";
$lang['go_StatusIDisempty'] = "Status ID is empty";
$lang['go_StatusIDisNotAvailable'] = "Status ID is not available";
$lang['go_StatusNameisempty'] = "Status name is empty";
$lang['go_Pleaseselectorfillinallthefields'] = "Please select or fill in all the fields.";
$lang['go_NotAvailable'] = "Not Available";
$lang['go_ErrorYoudonothavepermissiontomodifyleadrecyclingstatuses'] = "Error: You do not have permission to modify lead recycling statuses.";
$lang['go_AreyousureyouwanttodeletethisStatus'] = "Are you sure you want to delete this Status?";
$lang['go_AreyousureyouwanttodeletethisPauseCode'] = "Are you sure you want to delete this Pause Code?";

$lang['go_FilterIDisempty'] = "Filter ID is empty";
$lang['go_FilterIDisNotAvailable'] = "Filter ID is not available";
$lang['go_FilterNameisempty'] = "Filter Name is empty";
$lang['go_UserGroupnotselected'] = "Usergroup not selected";

$lang['go_SQLquerystringalreadyexist'] = "SQL query string alredy exist";
$lang['go_PleaseselectanSQLoperatorANDorORtocontinue'] = "Please select an SQL operator 'AND' or 'OR' to continue.";
$lang['go_Minimumof3characters'] = "Minimum of 3 characters.";
$lang['go_PleasecomposeanSQLquery'] = "Please compose an SQL query.";
$lang['go_NONSURVEYCAMPAIGN'] = "NON SURVEY CAMPAIGN";
$lang['go_LEADRECYCLES'] = "LEAD RECYLES";
$lang['go_LEADRECYCLESTATUS'] = "LEAD RECYCLES STATUS";
$lang['go_MODIFYCAMPAIGNLEADRECYCLING'] = "MODIFY CAMPAIGN LEAD RECYCLING";
$lang['go_DELETECAMPAIGNLEADRECYCLING'] = "DELETE CAMPAIGN LEAD RECYCLING";

$lang['go_CURRENTHOPPERLIST_'] = "CURRENT HOPPER LIST:";
$lang['go_TotalLeadsinHopper_'] = "Total Leads in Hopper:";

$lang['go_PleasecheckthedatayouenteredontheCampaignName'] = "Please check the data you entered on the Campaign Name";

//go_campaign_wizard.php
$lang['go_CampaignWizard'] = "Campaign Wizard";
$lang['go_Outbound'] = "Outbound";
$lang['go_CampaignType_'] = "Campaign Type:";
//[go_CampaignID_]
$lang['go_Inbound'] = "Inbound";
$lang['go_Blended'] = "Blended";
$lang['go_Survey'] = "Survey";
$lang['go_CopyCampaign'] = "Copy Campaign";
$lang['go_checktoeditcampaignidandname'] = "check to edit campaign id and name";
//[go_CampaignName_]
$lang['go_DIDTFN_Extension_'] = "DID / TFN Extension:";
$lang['go_acceptsonlynumbers'] = "Accept only numbers";
$lang['go_CallRoute'] = "Call route:";
$lang['go_INGROUPcampaign'] = "INGROUP (campaign)";
$lang['go_IVRcallmenu'] = "IVR (callmenu)";
$lang['go_AGENT'] = "AGENT";
$lang['go_VOICEMAIL'] = "VOICEMAIL";
$lang['go_CopyFrom_'] = "Copy from:";
$lang['go_SurveyType_'] = "Survey Type:";
$lang['go_VOICEBROADCAST'] = "VOICE BROADCAST";
$lang['go_SURVEYPRESS1'] = "SURVEY PRESS 1";
$lang['go_NumberofChannels_'] = "Number of Channels:";
$lang['go_Agent_'] = "Agent";
//[go_NONE]
$lang['go_Email_'] = "Email:";
$lang['go_GroupColor_'] = "Group Color:";
$lang['go_false'] = "false";
$lang['go_Back'] = "Back";
$lang['go_Next'] = "Next";
$lang['go_Modify'] = "Modify";
$lang['go_PleaseinputtheyourDIDTFNExtension'] = "Please input your DID / Extensión TFN.";
$lang['go_Shouldnotbeempty'] = "Should not be empty.";
$lang['go_DIDTFNNotAvailable'] = "DID / TFN Not Available ";
$lang['go_CampaignNameatleast6characters'] = "Campaign Name must be at least 6 characters long.";
$lang['go_CampaignID3to8characters'] = "Campaign ID must be between 3 to 8 characters.";
$lang['go_CampaignNameshouldnotbeempty'] = "Campaign Name should not be empty.";


//go_lead_filters.php
//[go_PleasecomposeanSQLquery]
$lang['go_MODIFYLEADFILTER_'] = "MODIFY LEAD FILTER:";
$lang['go_LeadFilterID_'] = "Lead Filter ID:";
$lang['go_LeadFilterName_'] = "Lead Filter Name:";
$lang['go_LeadFilterComments_'] = "Lead Filter Comments:";
//$lang['go_UserGroup_'] = "";
//$lang['go_Fields_'] = "";
//$lang['go_FilterOptions_'] = "";
//$lang['go_FilterOptions_'] = "";
//[go_FilterbyDate]
//[go_FilterbyCalledCount_]
//[FilterbyCountryCode]
//[FilterbyAreaCode]
$lang['go_FilterbyTimezone'] = "Filter by Timezone";
//[FilterbyState]


//go_campaign_wizard_output.php
$lang['go_Pleaseincludealeadfile'] = "Please, include a lead file";
$lang['go_Uploadedfileisinvalid'] = "Uploaded file is invalid";
$lang['go_FilemustbeinExcelformatxlsxlsxorinCommaSeparatedValuescsv'] = "The file must be in Excel format (xls, xlsx) or comma separated values (CSV).";
$lang['go_PleaseincludeaWAVfile'] = "Please include a WAV file.";
$lang['go_Erroruploading'] = "Error uploading";
$lang['go_Pleaseuploadonlyaudiofiles'] = "Please upload only audio files";
$lang['go_Pleaseuploadwavfile16bitMono8kPCMWAVaudiofilesonly'] = "Please upload .wav file (16 bit mono 8000 PCM WAV audio files only";
$lang['go_More'] = "More";

$lang['go_RecordingsforthisLeadID'] = "Recording for this Lead ID";
$lang['go_SearchDNCNumbers'] = "Search DNC Numbers";

$lang['go_Skip'] = "Skip";
$lang['go_SaveandFinish'] = "Save and Finish";

$lang['go_Westronglyrecommend'] = "we strongly recommended";
$lang['go_files'] = "Files";
$lang['go_Pleaseselectfieldvalues'] = "Please, select field values";
$lang['go_PleasefillinatleastPHONENUMBERFIRSTNAMEandLASTNAME'] = "Please fill in atleast PHONE NUMBER, FIRST NAME, and LAST NAME.";
$lang['go_LeadFile_'] = "Lead File:";
$lang['go_ListID_'] = "List ID:";
$lang['go_Country_'] = "Country:";
$lang['go_CheckforDuplicates'] = "Check For Duplicates:";
$lang['go_NODUPLICATECHECK']  = "NO DUPLICATE CHECK";
$lang['go_CHECKFORDUPLICATESBYPHONEINLISTID'] = "CHECK FOR DUPLICATES BY PHONE IN LIST ID";
$lang['go_CHECKFORDUPLICATESBYPHONEINALLCAMPAIGNLISTS'] = "CHECK FOR DUPLICATES BY PHONE IN ALL CAMPAIGN LISTS";
$lang['go_CHECKFORDUPLICATESBYPHONEINENTIRESYSTEM'] = "CHECK FOR DUPLICATES BY PHONE IN ENTIRE SYSTEM";
$lang['go_CHECKFORDUPLICATESBYALTPHONEINLISTID']  = "CHECK FOR DUPLICATES BY ALT PHONE IN LIST ID";
$lang['go_CHECKFORDUPLICATESBYALTPHONEINENTIRESYSTEM'] = "CHECK FOR DUPLICATES BY ALT PHONE IN ENTIRE SYSTEM";
$lang['go_TimeZoneLookup'] = "Time zone lookup:";
$lang['go_COUNTRYCODEANDAREACODEONLY'] = "COUNTRY CODE AND AREA CODE ONLY";
$lang['go_POSTALCODEFIRST'] = "POSTAL CODE FIRST";
$lang['go_OWNERTIMEZONECODEFIRST'] = "OWNER TIMEZONE CODE FIRST";
$lang['go_UPLOADLEADS'] = "UPLOAD LEADS";
$lang['go_OKTOPROCESS'] = "OK TO PROCESS";


//go_campaign_wizard_fields.php
$lang['go_Processing'] = "Processing";
$lang['go_LISTIDFORTHISFILE'] = "LIST ID FOR THIS FILE";
$lang['go_COUNTRYCODEFORTHISFILE'] = "COUNTRY CODE FOR THIS FILE";
$lang['go_LeadFileSuccessfullyLoaded'] = "Lead File Succesfully Loaded";
$lang['go_Currentfilestatus'] = "Current file status";
$lang['go_Good_'] = "Good:";
$lang['go_Bad_'] = "Bad:";
$lang['go_Total_'] = "Total:";
$lang['go_Duplicate_'] = "Duplicate:";
$lang['go_PostalMatch_'] = "Postal Match:";
$lang['go_Checkthisboxifyouwanttoshowtheresult'] = "Check this box if you want to show the result";


//go_campaign_statuses.php
$lang['go_Pleasefillinthefollowing'] = "Please, fil in the following:";
$lang['go_Thereisalreadyaglobalstatusinthesystemwiththisname_'] = "There is already a global status in the system with this name:";
$lang['go_Thereisalreadyacampaignstatusinthesystemwiththisname_'] = "There is already a campaign in the system with this name:";
$lang['go_CUSTOMSTATUSESWITHINTHISCAMPAIGN'] = "CUSTOM STATUSES WITHIN THIS CAMPAIGN:";
$lang['go_CATEGORY'] = "CATEGORY";
$lang['go_MODIFYSTATUS_'] = "MODIFY STATUS";
$lang['go_SELECTABLE_'] = "SELECTABLE";
$lang['go_HUMANANSWERED_'] = "HUMAN ANSWERED";
$lang['go_SCHEDULEDCALLBACK'] = "SCHEDULED CALLBACK";
$lang['go_UNWORKABLE'] = "UNWORKABLE";
$lang['go_NOTINTERESTED'] = "NOT INTERESTED";
$lang['go_CUSTOMERCONTACT'] = "CUSTOMER CONTACT";
$lang['go_UNDEFINED'] = "UNDEFINED";
$lang['go_SALE'] = "SALE";
$lang['go_LEADRECYCLINGWITHINTHISCAMPAIGN'] = "LEAD RECYCLING WITHIN THIS CAMPAIGN";
$lang['go_ATTEMPTDELAY'] = "ATTEMPT DELAY";
$lang['go_MAXIMUMATTEMPTS'] = "MAXIMUM ATTEMPTS";
$lang['go_LEADSATLIMIT'] = "LEADS AT LIMIT";
$lang['go_PAUSECODESWITHINTHISCAMPAIGN'] = "PAUSE CODES WITHIN THIS CAMPAIGN";
$lang['go_PAUSECODENAME'] = "PAUSE CODE NAME";
$lang['go_PAUSECODESTATUS'] = "PAUSE CODE STATUS";
$lang['go_BILLABLE'] = "BILLABLE";
$lang['go_HALF'] = "HALF";
$lang['go_HOTKEYSWITHINTHISCAMPAIGN'] = "HOTKEYS WITHIN THIS CAMPAIGN";
$lang['go_HOTKEY'] = "HOTKEY";
$lang['go_HOTKEYSTATUS'] = "HOTKEY STATUS";
$lang['go_HOTKEYS'] = "HOTKEYS";
$lang['go_HotKeys'] = "HotKeys";
$lang['go_HotKeysTab'] = "<b> Hotkeys Tab </b> - Allows admin to set a number key on the keyboard as a hotkey to automatically disposition a call.";

$lang['go_LeadFilters'] = "Lead Filters";
$lang['go_LeadFiltersTab'] = "<b> Lead Filters Tab </b> - This is a method of filtering your prospects using a fragment of a SQL query. Use this feature with caution <br/> is easy to stop accidentally marked with the slightest alteration in the SQL statement. Default is None.";
$lang['go_RealtimeMonitoring'] = "Realtime Monitoring";
$lang['go_Monitor'] = "Monitor";
$lang['go_AdminPhone'] = "Admin Phone";
$lang['go_showList'] = "showList";


$lang['go_add_new_ga'] = "Add New Group Access";
//delete campaign$lang['go_DelCamp_Confirmation']  = "Seguro que quieres eliminar esta Campaña?";
$lang['go_DelCamp_Notification']  = "Please be sure to transfer any existing code list are uploaded to that available for any campaign cables.";
//delete disposition
$lang['go_DelDispo_Confirmation'] = "Are you sure you want to delete the selected campaign's statuses?";
$lang['go_BLINDMONITORING'] = "BLIND MONITORING";

//DISPOSITION TAB
$lang['go_Dispositions']         = "Dispositions";
$lang['go_Disposition'] = "Disposition";
//-r camp id
//-r camp name
$lang['go_CUSTOMDISPOSITION']    = "CUSTOM DISPOSITION";
//-r dispalying

//SW- status wizard
//CNS- create new status
$lang['go_SWCNS']               = "Status Wizard » Create New Status";
$lang['go_CLOSE']		= "CLOSE";
//-r step1
$lang['go_Campaign_']		= "Campaign:";
$lang['go_CampaignTooltip']	= "<b> Campaign </b> - specifies the campaign will use the hotkey.";
$lang['go_ALLCAMPAIGN']         = "---  ALL CAMPAIGN  ---";
$lang['go_Status_']             = "Status:";
$lang['go_StatusTooltip'] 	= "<b> Status </b> - sets the disposition that will be assigned to the call when the hotkey is pressed";
$lang['go_egNEW']               = "For example. New";
$lang['go_StatusName_']         = "Status Name:";
$lang['go_ADDSTATUS']	 	= "ADD STATUS";
$lang['go_egNCS']               = "For example. New Campaign status";
$lang['go_Selectable_']         = "Selectable:";
$lang['go_HumanAnswered_']      = "Human Answered:";
$lang['go_Sale_']               = "Sale:";
$lang['go_DNC_']                = "DNC:";
$lang['go_DNCfile']		= "DNC File";
$lang['go_CustomerContact_']    = "Customer Contact:";
$lang['go_NotInterested_']      = "Not Interested:";
$lang['go_Unworkable_']         = "Unworkable:";
$lang['go_ScheduledCallback_']  = "Scheduled Callback:";

$lang['go_YES']			= "YES";
$lang['go_NO']			= "NO";
$lang['go_Submit']              = "Submit";
$lang['go_Yes']			= "Yes";
$lang['go_Later']		= "Later";

//tooltip
$lang['go_DispositionTab']               	 = "<b> Dispositions Tab </b> - Gives a list of custom dispositions created on the account and allows you to create new ones.";
$lang['go_MODIFYCAMPAIGNSTATUSES']               = "MODIFY CAMPAIGN STATUSES";
$lang['go_MODIFYCAMPAIGNSTATUS']		 = "MODIFY CAMPAIGN STATUS";
$lang['go_DELETECAMPAIGNSTATUSES']               = "DELETE CAMPAIGN STATUSES";
$lang['go_DELETECAMPAIGNSTATUS']		 = "DELETE CAMPAIGN STATUS";
$lang['go_VIEWDISPOSITIONFORCAMP']               = "VIEW DISPOSITION FOR CAMPAIGN";
$Lang['go_MODIFYLISTID'] 			 = "MODIFY LIST ID";
$lang['go_ModifyListID']			 = "Modify List ID";
$lang['go_CampaignRecordingTooltip'] 		 = "<center> <b> Campaign Recording </b> </center> </br> <b> Off </b> - No Calls </br> <b> </b> will be recorded. <br/> <b> On </b> - All <b> Outbound </b> calls will be recorded. </br> <b> ONDEMAND </b> - No <b> Outbound </b> Calls </br> will be recorded unless the agent clicks on the record button on the Agent </br> webpage";

$lang['go_Youdonthavepermissiontodeletethisrecords'] = "You don't have permission to delete this record(s)";
$lang['go_successfullydeleted'] = "Succesfull Deleted";

//PAUSE CODE TAB
$lang['go_PauseCode'] 		       = "Pause Codes";
$lang['go_PauseCode_']		       = "Pause Code:";
$lang['go_PauseCodeTooltip']	       = "<b> Pause Codes Tab </b> - Allows admin to set pause codes that will require agents clicking on the pause button to specify the reason for the pause.";
$lang['go_PauseCodeAlertGofree']       = "This function is not available in Gofree. Please update your subscription.";
$lang['go_PauseCodeTooltip2']	       = "<b> Pause Code  </b> - sets the code that appears when a call report is generated.";
$lang['go_PauseCodeName_']	       = "Pause Code Name:";	
$lang['go_PauseCodeName_Tooltip']      = "<b> Pause Code Name </b> - gives a description of the pause code.";
$lang['go_Billable_'] 	       	       = "Billable:";
$lang['go_BillableTooltip']	       = "<b> Billable </b> - is used to generate reports to assist in computing for an agent's billable time.";

$lang['go_ActivateSelected']         = "Activate Selected";
$lang['go_DeactivatedSelected']      = "Deactivate Selected";
$lang['go_DeleteSelected']           = "Delete Selected";

//Dashboard - Walk Through
$lang['goDashboard_welcomeToGOadmin'] = "Welcome to GOadmin!";
$lang['goDashboard_thisWalkThroughWill'] = "This walk through will help you navigate the system easily or";
$lang['goDashboard_skip'] = "skip";
$lang['goDashboard_thisWalkThrough'] = "this walk through.";

$lang['goDashboard_appMenu'] = "Application Menu";
$lang['goDashboard_hoverMouse'] = "Hover mouse to see details";

$lang['goDashboard_loadCredits'] = "Load credit(s)";
$lang['goDashboard_click'] = "Click";
$lang['goDashboard_here'] = "here";
$lang['goDashboard_forHowToLoadCredit'] = "for how to load credit";

$lang['goDashboard_monitorBarge'] = "Monitor/Barge";
$lang['goDashboard_toMonitor'] = "to monitor or agent (s) live barge";

$lang['goDashboard_activeCalls'] = "Active Calls";
$lang['goDashboard_clickToShowActiveCalls'] = "Click to show active calls being placed";

$lang['goDashboard_thatsIt'] = "Thats it!";
$lang['goDashboard_toGetStarted'] = "To start ASAP please go to our tutorials here:";
$lang['goDashboard_tutorials'] = "Tutorials";
$lang['goDashboard_showThisIntro'] = "Show this introduction helps again next logon?";
$lang['goDashboard_next'] = "Next";
$lang['goDashboard_close'] = "Close";


//In-Groups
$lang['go_Inbound']= "Inbound";

$lang['go_AddNewIngroup'] = "Add New Ingroup";
$lang['go_AddNewDID'] = "Add New DID";
$lang['go_AddNewCallMenu'] = "Add New Callmenu";
$lang['go_DESCRIPTIONS'] = "DESCRIPTIONS";
$lang['go_PRIORITY'] = "PRIORITY";
$lang['go_MODIFYINGROUP'] = "MODIFY IN-GROUP";
$lang['go_CannotdeleteAGENTDIRECT'] = "Cannot Delete Agent Direct";
$lang['go_DELETEINGROUP'] = "DELETE IN-GROUP";
$lang['go_VIEWINFOFORINGROUP'] = "VIEW INFO FOR IN-GROUP";
$lang['go_Settings'] = "Settings";
$lang['go_Color'] = "Color";
$lang['go_NextAgentCall'] = "Next Agent Call";
$lang['go_QueuePriority'] = "Queue Priority";
$lang['go_FronterDisplay'] = "Fronter Display";
$lang['go_ADVANCESETTINGS'] = "ADVANCE SETTINGS";

$lang['go_OnHookRingTime'] = "On-Hook Ringing Time";
$lang['go_IgnoreListScriptOverride'] = "Ignore List Script Overide";
$lang['go_GetCallLaunch'] = "Get Call Launch";
$lang['go_TransferConfDTMF'] = "Transfer-conf DTMF";
$lang['go_TransferConfNumber'] = "Transfer-Conf Number";
$lang['go_TimerAction'] = "Timer Action";
$lang['go_TimerActionMessage'] = "Timer Action Message";
$lang['go_TimerActionSeconds'] = "Timer Action Seconds";
$lang['go_TimerActionDestination'] = "Timer Action Destination";
$lang['go_DropCallSeconds'] = "Drop Call Seconds";
$lang['go_DropAction'] = "Drop Action";
$lang['go_DropExten'] = "Drop Exten";

$lang['go_Voicemail']  = "Voicemail";
$lang['go_VoicemailChooser'] = "Voicemail Chooser";
$lang['go_DropTransferGroup']  = "Drop Transfer Group";
$lang['go_DropCallMenu'] = "Drop Callmenu";
$lang['go_CallTime'] = "Calltime";
$lang['go_AfterHoursAction'] = "After Hours Acción";
$lang['go_AfterHoursMessageFilename'] = "After Hours Message Filename";

$lang['go_AfterHoursExtension'] = "After Hours Extension";
$lang['go_AfterHoursVoicemail'] = "After Hours Voicemail";
$lang['go_AfterHoursTransferGroup'] = "After Hours Transfer Group";
$lang['go_NoAgentsNoQueueing'] = "No Agent No Queueing";
$lang['go_NoAgentNoQueueAction'] = "No Agent No Queue Action";
$lang['go_CallMenu'] = "Call Menu";
$lang['go_InGroup'] = "In-Group";
$lang['go_HandleMethod'] = "Handle Method";
$lang['go_SearchMethod'] = "Search Method";
$lang['go_Value'] = "Value";
$lang['go_Extension'] = "Extention";

$lang['go_Context'] = "Context";
$lang['go_VoicemailBox'] = "Voicemail Box";
$lang['go_MaxCallsMethod'] = "Max Calls Method";
$lang['go_MaxCallsCount'] = "Max Calls Count";
$lang['go_MaxCallsAction'] = "Max Calls Action";
$lang['go_WelcomeMessageFilename'] = "welcome Message Filename";
$lang['go_PlayWelcomeMessage'] = "Play Welcome Message";
$lang['go_MusicOnHoldContext'] = "Music On Hold Context";
$lang['go_OnHoldPromptFilename'] = "On Hold Prompt Filename";
$lang['go_OnHoldPromptInterval'] = "On Hold Prompt Interval";
$lang['go_OnHoldPromptNoBlock'] = "On Hold Prompt No Block";
$lang['go_OnHoldPromptSeconds'] = "On Hold Prompt Seconds";

$lang['go_PlayPlaceinLine'] = "Play place in line";
$lang['go_PlayEstimatedHoldTime'] = "play estimated hold time";
$lang['go_CalculateEstimatedHoldSeconds'] = "Calculate estimates hold seconds";
$lang['go_EstimatedHoldTimeMinimumFilename'] = "Estimated hold time minimum filename";
$lang['go_EstimatedHoldTimeMinimumPromptNoBlock'] = "Estimated hold time minimum prompt no block";
$lang['go_EstimatedHoldTimeMinimumPromptSeconds'] = "Estimated hold time minimum prompt seconds";

$lang['go_WaitTimeOption'] = "Wait Time Option";
$lang['go_WaitTimeSecondOption'] = "Wait Time second Option";
$lang['go_WaitTimeThirdOption'] = "Wait Time Third Option";
$lang['go_WaitTimeOptionSeconds'] = "Wait Time Option Seconds";
$lang['go_WaitTimeOptionExtension'] = "Wait Time Option Extension";
$lang['go_WaitTimeOptionCallmenu'] = "Wait Time Option Callmenu";
$lang['go_WaitTimeOptionVoicemail'] = "Wait Time Option Voicemail";

$lang['go_WaitTimeOptionPressFilename'] = "Wait Time Option Press Filename";
$lang['go_WaitTimeOptionPressNoBlock'] = "Wait Time Option Press No Block";
$lang['go_WaitTimeOptionPressFilenameSeconds'] = "Wait Time Option Press Filename Seconds";
$lang['go_WaitTimeOptionAfterPressFilename'] = "Wait Time Option After Press Filename";
$lang['go_WaitTimeOptionCallbackListID'] = "Wait Time Option Callback ListID";
$lang['go_WaitHoldOptionPriority'] = "Wait Hold Option Priority";

$lang['go_EstimatedHoldTimeOption'] = "Estimated Hold Time Option";
$lang['go_HoldTimeSecondOption'] = "Hold Time Second Option";
$lang['go_HoldTimeThirdOption'] = "Hold Time Third Option";
$lang['go_HoldTimeOptionSeconds'] = "Hold Time Option Seconds";
$lang['go_HoldTimeOptionMinimum'] = "Hold Time Option Minimum";
$lang['go_HoldTimeOptionExtension'] = "Hold Time Option Extension";
$lang['go_HoldTimeOptionCallmenu'] = "Hold Time Option Callmenu";

$lang['go_HoldTimeOptionVoicemail'] = "Hold Time Option Voicemail";
$lang['go_HoldTimeOptionTransferInGroup'] = "Hold Time Option Transfer In-Group";
$lang['go_HoldTimeOptionPressFilename'] = "Hold Time Option Press Filename";
$lang['go_HoldTimeOptionPressNoBlock'] = "Hold Time Option Press No Block";
$lang['go_HoldTimeOptionPressFilenameSeconds'] = "Hold Time Option Press Filename Seconds";
$lang['go_HoldTimeOptionAfterPressFilename'] = "Hold Time Option After Press Filename";
$lang['go_HoldTimeOptionCallbackListID'] = "Hold Time Option Callback List ID";
$lang['go_AgentAlertFilename'] = "Agent Alert Filename";
$lang['go_AgentAlertDelay'] = "Agent Alert Delay";
$lang['go_DefaultTransferInGroup'] = "Default Transfer In-Group";

$lang['go_DefaultGroupAlias'] = "Default Group Alias";
$lang['go_DialInGroupCID'] = "Dial In-Group CID";
$lang['go_HoldRecallTransferInGroup'] = "Hold Recall Transfer In-Group";
$lang['go_NoDelayCallRoute'] = "No Delay Call Route";
$lang['go_InGroupRecordingOverride'] = "In-Group Recording Override";
$lang['go_InGroupRecordingFilename'] = "In-Group Recording Filename";
$lang['go_StatsPercentofCallsAnsweredWithinXseconds'] = "Status percentage of calls answered within X seconds.";
$lang['go_StartCallURL'] = "Start Call URL";

$lang['go_DispoCallURL'] = "Dispo Call URL";
$lang['go_AddLeadURL'] = "Add Lead URL";
$lang['go_NoAgentCallURL'] = "No Agent Call URL";
$lang['go_ExtensionAppendCID'] = "Extention Append CID";
$lang['go_UniqueidStatusDisplay'] = "Unique ID Status Display";
$lang['go_UniqueidStatusPrefix'] = "Unique ID Status Prefix";
$lang['go_USER'] = "USER";
$lang['go_TENANTID'] = "TENNANT ID";
$lang['go_CALLSTODAY'] = "CALLS TODAY";
$lang['go_GRADE'] = "GRADE";
$lang['go_RANK'] = "RANK";
$lang['go_SELECTED'] = "SELECTED";

$lang['go_InGroupWizard'] = "In-Group Wizard";
$lang['go_CreateNewInGroup'] = "Create New Ingroup";
$lang['go_GroupID'] = "Group ID";
$lang['go_nospaces2and20charactersinlength'] = "* (No spaces). 2 and 20 characters in length";
$lang['go_2and20charactersinlength'] = "* 2 and 20 characters in length";
$lang['go_GroupColor'] = "Group Color";
$lang['go_UserGroup'] = "User Group";
$lang['go_FronterDisplay'] = "Fronter Display";

//In-Group -> Phone Numbers (DIDs/TFNs) Tab
$lang['goInbound_phoneNumbersTab']= "Phone Numbers (DIDs/TFNs)";
$lang['goInbound_phoneNumbers']= "PHONE NUMBERS";
$lang['goInbound_description']= "DESCRIPTION";
$lang['goInbound_status']= "STATUS";
$lang['goInbound_route']= "ROUTE";
$lang['goInbound_action']= "ACTION";
$lang['goInbound_actionActivateSelected']= "Activate Selected";
$lang['goInbound_actionDeactivateSelected']= "Deactivate Selected";
$lang['goInbound_actionDeleteSelected']= "Delete Selected";
$lang['goInbound_displaying']= "Displaying";
$lang['goInbound_to']= "to";
$lang['goInbound_of']= "of";
$lang['goInbound_ingroups']= "In-Groups";
$lang['goInbound_addNewDID']= "Add New DID";
$lang['goInbound_didWizard']= "DID Wizard » Create new DID";
$lang['goInbound_didExtension']= "DID Extention";
$lang['goInbound_didDescription']= "DID Description";
$lang['goInbound_active']= "Active";
$lang['goInbound_didRoute']= "DID Route";
$lang['goInbound_userGroups']= "User Groups";
$lang['goInbound_agentId']= "Agent ID";
$lang['goInbound_agentUnavailableAction']= "Agent Unavailable Action";
$lang['goInbound_submit']= "Submit";
$lang['goInbound_agentRouteSettings']= "Agent Route Settings IN-Group";
$lang['goInbound_cleanCIDNumber']= "Clean CID Number";
$lang['goInbound_filterInboundNumber']= "Filter Inbound Number";
$lang['goInbound_advanceSettings']= "ADVANCE SETTINGS";
$lang['goInbound_saveSettings']= "SAVE SETTINGS";
$lang['goInbound_modifyDIDRecord']= "Modify DID Record";
$lang['goInbound_success']= "SUCCESS: DID modified";
$lang['goInbound_deleteConfirmationMsg']= "Are you sure you want to delete this DID?";
$lang['goInbound_modifyDID']= "Modify DID";
$lang['goInbound_deleteDID']= "Delete DID";
$lang['goInbound_searchDIDs']= "Search DIDs";







################## Jeremiah Sebastian Samatra #####################



//START OF REPORTS

######################go_reports.php###################
//Pagetitle
$lang['go_statistical_rep'] 	      = "Statistical Report"; 
$lang['go_agent_stats_rep'] 	      = "Agents Statistics Report"; 
$lang['go_dial_statuses_summary_rep'] = "Dial Statuses Summary Report"; 
$lang['go_sales_per_agent_rep']       = "Sales Per Agent Report"; 
$lang['go_oi_sales_tracker'] 	      = "Outbound / Inbound Sales Tracker Report"; 
$lang['go_inbound_rep'] 	      = "Inbound Report"; 
$lang['go_export_call_rep'] 	      = "Export Call Report"; 
$lang['go_dashboard'] 		      = "Dashboard"; 
$lang['gocdr'] = "CDR";
//bannertitle
$lang['go_reports_analytics'] 	      = "Forms & Analytics"; 
$lang['go_reports_analytics_tooltip'] = "Reporting and Analytics - give virtually all the data you need on your own. Reports can be downloaded and spreadsheet format. There is a wide variety of reports you can choose with each customizable reports tailored to your needs. It also will display a graph comparing different data relating to others. Each type of report will be discussed in detail in the following pages.";
$lang['go_custome_tabs'] = "Custom Tabs allow for different <br>types of reports to be displayed on <br>the screen";
$lang['go_daily'] = "Daily";
$lang['go_weekly'] = "Weekly";
$lang['go_monthly'] = "Monthly";
$lang['go_calendar_tooltip'] = "The Calendar icon allows you to<br>generate a report based on a specific<br>date range.";
$lang['go_calendar_icon'] = "The Calendar icon allows you to<br>generate a report based on a specific<br>date range.";
$lang['go_sel_date_range'] = "Select date range";
$lang['go_sel_camp'] = "Select a Campaign";
$lang['go_statistical_rep_tooltip'] = "Statistical Report -- generates a graphical representation of data on a specific
<br>campaign. Data will include total calls and their dispositions and the average calls on a
<br>daily, weekly or monthly basis.";
$lang['go_agent_time_details_tooltip'] = "Agent Time Details -- provides a breakdown on <br/>all activity the agent did during his shift.";
$lang['go_agent_time_detail'] = "Agent Time Detail";
$lang['go_agent_performance_detail_tooltip'] = "Agent Performance Detail -- gives a detailed report on each agent.s activity for a specific
<br/>campaign on a specified time period. The report breaks down each agent.s activity during his shift.
<br/>The report is broken down to the total number of calls, Pause time, Wait time, Talk time, Time to
<br/>disposition a call, and Wrap-up time. The report will also give information on the dispositions and
<br/>their total.";
$lang['go_agent_performance_detail'] = "Agent Performance Detail";
$lang['go_dial_statuses_summary_tooltip'] = "Dial Statuses Summary -- will display the number of
<br/>calls that have been dispositioned for each call to a
<br/>specific lead. This page will display dispositions on a
<br/>lead for the initial call, as well as succeeding calls to that
<br/>lead.";
$lang['go_dial_statuses_summary'] = "Dial Statuses Summary";
$lang['go_sales_per_agent_tooltip'] = "Sales Per Agent -- will display the total sales of each agent on a
<br/>specific campaign on a given date range. Sales are tracked
<br/>whether they were made during an outbound call or an
<br/>inbound call.";
$lang['go_sales_per_agent'] = "Sales Per Agent";
$lang['go_sales_tracker_tooltip'] = "Sales Tracker -- displays all sale made for a specific campaign on a
<br>given date range. Information displayed includes the date and time of
<br>the call, the agent ID, name of the agent, and the phone number.";
$lang['go_sales_tracker'] = "Sales Tracker";
$lang['go_sales_tracker_tooltip'] = "Sales Tracker -- displays all sale made for a specific campaign on a
<br>given date range. Information displayed includes the date and time of
<br>the call, the agent ID, name of the agent, and the phone number.";
$lang['go_inbound_call_rep_tooltip'] = "Inbound Call Report -- display all inbound calls received by a specified
<br>ingroup. Phone numbers of the caller, actual date and time of call, duration of
<br>the call and the dispositions of the calls on a given date range are all listed";
$lang['go_inbound_call_rep'] = "Inbound Call Report";
$lang['go_export_call_rep_tooltip'] = "Export Call Report -- generates a report on all data and lead
<br/>information of your calls. The report will be based on the
<br/>Campaigns, Inbound groups, List ID, Statuses, Custom fields and
<br/>date range you will select. The report generated will be in
<br/>spread sheet format.";
$lang['go_export_call_rep'] = "Export Call Report";
$lang['go_dashboard_tooltip'] = "Dashboard -- gives a graphical representation of the Contact Rate,
<br/>Sales Rate and Transfer Rate of a selected campaign. This data
<br/>primarily focuses on how good your leads were with regards to the
<br/>Contact and Sales rate. Good lead files will return high Contact Rate
<br/>and Sales Rate.";
$lang['go_call_histo_tooltip'] = "Call History -- displays all calls on a set date range.";
$lang['go_call_histo_cdr'] = "Call History (CDRs)";

#######################go_export_reports.php######################

$lang['go_atdr_download']	 = "Agent_Time_Detail_Report";
$lang['go_apdr_download'] 	 = "Agent_Performance_Detail_Report";
$lang['go_dssr_download']	 = "Dial_Statuses_Summary_Report";
$lang['go_spar_download']	 = "Sales_Per_Agent_Report";
$lang['go_str_download']	 = "Sales_Tracker_Report";
$lang['go_icr_download']	 = "Inbound_Call_Report";
$lang['go_ecr_download']	 = "Export_Call_Report";
$lang['go_d_download']		 = "Dashboard";
$lang['go_sr_download']		 = "Statistical_Report";


#######################go_reports_page.php######################

$lang['go_pls_sel_camp'] 	= "Please select a campaign.";
$lang['go_selected'] 		= "Selected";
$lang['go_download'] 		= "Download";
$lang['go_connect_time'] 	= "Connect Time";
$lang['go_country'] 		= "Country";
$lang['go_description'] 	= "Description";
$lang['go_billed_duration'] 	= "Billed Duration";
$lang['go_cost'] 		= "Cost";
$lang['go_export_csv'] 		= "Export to CSV";
$lang['go_agent_name_caps'] 	= "AGENT NAME";
$lang['go_camp_hours'] 		= "Campaign Hours";
$lang['go_utilization'] 	= "Utilization";
$lang['go_callbacks'] 		= "Callbacks";
$lang['go_not_interested'] 	= "Not Interested";
$lang['go_transfer_per_hour'] 	= "Transfers per Hour";
$lang['go_total_transfer'] 	= "Total Transfers";
$lang['go_transfer_sales_rate'] = "Transfer Sales Rate";
$lang['go_other_stats'] 	= "Other Stats";
$lang['go_sales_per_hour'] 	= "Sales per Hour";
$lang['go_total_sales'] 	= "Total Sales";
$lang['go_sales_rate'] 		= "Sales Rate";
$lang['go_total_contacts'] 	= "Total Contacts";
$lang['go_contact_rate'] 	= "Contact Rate";
$lang['go_sub_total'] 		= "Sub Total";
$lang['go_dispo_code'] 		= "Dispo Code";
$lang['go_dispo_name'] 		= "Dispo Name";
$lang['go_count'] 		= "Count";
$lang['go_dialer_calls_caps'] 	= "DIALER CALLS";
$lang['go_all'] 		= "ALL";
$lang['go_statuses'] 		= "Statuses";
$lang['go_lists'] 		= "Lists";
$lang['go_none'] 		= "NONE";
$lang['go_inbound_groups'] 	= "Inbound Groups";
$lang['go_campaigns'] 		= "Campaigns";
$lang['go_campaign'] 		= "CAMPAIGN";
$lang['go_standard_caps'] 	= "STANDARD";
$lang['go_extended'] 		= "EXTENDED";
$lang['go_no'] 			= "NO";
$lang['go_yes'] 		= "YES";
$lang['go_per_call_notes'] 	= "Per Call Notes";
$lang['go_custome_fields'] 	= "Custom Fields";
$lang['go_location'] 		= "LOCATION";
$lang['go_filename'] 		= "FILENAME";
$lang['go_id']			= "ID";
$lang['go_recording_fields'] 	= "Recording Fields";
$lang['go_exports_calls_report'] = "Export Calls Report";
$lang['go_disposition'] 	= "Disposition";
$lang['go_call_duration_in_sec'] = "Call Duration (in sec)";
$lang['go_time'] 		= "Time";
$lang['go_agent_id'] 		= "Agent ID";
$lang['go_date'] 		= "Date";
$lang['go_date_caps'] 		= "DATE";
$lang['go_inbound_calls_found'] = "inbound call(s) found.";
$lang['go_search_done'] 	= "Search done.";
$lang['go_comments'] 		= "Comments";
$lang['go_alt_phone'] 		= "Alt Phone";
$lang['go_email'] 		= "Email";
$lang['go_postal_code'] 	= "Postal Code";
$lang['go_state'] 		= "State";
$lang['go_city'] 		= "City";
$lang['go_address'] 		= "Address";
$lang['go_agent'] 		= "Agent";
$lang['go_call_date_time'] 	= "Call Date & time";
$lang['go_last_name'] 		= "Last Name";
$lang['go_first_name'] 		= "First Name";
$lang['go_phone_number'] 	= "Phone Number";
$lang['go_lead_id'] 		= "Lead ID";
$lang['go_click_more_info'] 	= "Click for more info";
$lang['go_click_show_more'] 	= "Click here to show more...";
$lang['go_info'] 		= "Info";
$lang['go_agent'] 		= "Agent";
$lang['go_sale'] 		= "Sale";
$lang['go_total'] 		= "TOTAL";
$lang['go_sales_count'] 	= "Sales Count";
$lang['go_agents_name'] 	= "Agents Name";
$lang['go_inbound_caps'] 	= "INBOUND";
$lang['go_outbound_caps'] 	= "OUTBOUND";
$lang['go_no_agents_found_time_given'] = "No agents found within the time given.";
$lang['go_agents_caps'] 	= "AGENTS";
$lang['go_total_small'] 	= "Total";
$lang['go_non_pause'] 		= "NonPause";
$lang['go_pause'] 		= "Pause";
$lang['go_full_name'] 		= "Full Name";
$lang['go_legend_caps'] 	= "LEGEND";
$lang['go_pls_pick_camp']	= "Please pick a campaign.";
$lang['go_avg'] 		= "Avg";
$lang['go_customer'] 		= "Customer";
$lang['go_wrap_up'] 		= "Wrap-Up";
$lang['go_dispo'] 		= "Dispo";
$lang['go_talk'] 		= "Talk";
$lang['go_wait'] 		= "Wait";
$lang['go_calls'] 		= "Calls";
$lang['go_agent_time'] 		= "Agent Time";
$lang['go_user_name'] 		= "User Name";
$lang['go_click_hide'] 		= "Click here to hide...";
$lang['go_disposition_stats'] 	= "Disposition Stats";
$lang['go_lead_count'] 		= "Lead Count";
$lang['go_total_agents'] 	= "Total Agents";
$lang['go_total_calls'] 	= "Total Calls";
$lang['go_call_statistics'] 	= "Call Statistics";
$lang['go_show'] 		= "Show";
$lang['go_calls_per'] 		= "Calls per";
$lang['go_date_range'] 		= "Date Range";
$lang['go_date_range_caps']	= "Date Range";
$lang['go_to'] 			= "to";
$lang['go_to_caps'] 		= "TO";
$lang['go_failed'] 		= "Failed";
$lang['go_submit'] 		= "SUBMIT";
$lang['go_not_registered']	= "NOT REGISTERED";
$lang['go_export_fields'] 	= "Export Fields";
$lang['go_header_row'] 		= "Header Row";



#######################models/go_reports.php#################################3

$lang['go_note_sel_camp'] 	= "NOTE: This includes the Manual Dial List ID set on the current selected campaign.";
$lang['go_done_gathering'] 	= "Done gathering";
$lang['go_records_analyzing'] 	= "records, analyzing...";
$lang['go_not_system'] 		= "NOT IN SYSTEM";
$lang['go_status'] 		= "Status";
$lang['go_status_name'] 	= "Status Name";
$lang['go_sub_total_caps'] 	= "SUB-TOTAL";
$lang['go_total_for'] 		= "TOTAL for";
$lang['go_inbound_sales_an_ai_sc'] = "INBOUND SALES\nAGENTS NAME,AGENTS ID,SALES COUNT\n";
$lang['go_outbound_sales_cdt_a_pn_f_l_a_c_s_p_e_an_c'] = "OUTBOUND SALES\nCALL DATE & TIME,AGENT,PHONE NUMBER,FIRST NAME,LAST NAME,ADDRESS,CITY,STATE,POSTAL CODE,EMAIL,ALT NUMBER,COMMENTS\n";
$lang['go_inbound_camp'] 	= "INBOUND CAMPAIGN";
$lang['go_date_aid_pn_t_cd_d'] 	= "DATE,AGENT ID,PHONE NUMBER,TIME,CALL DURATION (IN SEC),DISPOSITION\n";
$lang['go_no_outbound_calls'] 	= "There are no outbound calls during this time period for these parameters.";
$lang['go_all_list_ids'] 	= "ALL List IDs under";
$lang['go_list_ids'] 		= "List ID(s)";
$lang['go_user_id_c_tc_at_w_t_d_p_w_c'] = "USER,ID,CALLS,TIME CLOCK,AGENT TIME,WAIT,TALK,DISPO,PAUSE,WRAPUP,CUSTOMER";
$lang['go_total_agents'] 	= "TOTALS,AGENTS";
$lang['go_user_name_id_c_at_p_a_w_t_t'] = "USER NAME,ID,CALLS,AGENT TIME,PAUSE,PAUSE AVG,WAIT,WAIT AVG,TALK,TALK AVG,DISPO,DISPO AVG,WRAPUP,WRAPUP AVG,CUSTOMER,CUST AVG";
$lang['go_user_name_id_t_np_p'] =  "USER NAME,ID,TOTAL,NONPAUSE,PAUSE";
$lang['go_outbound_sales_an_aid_sc'] = "OUTBOUND SALES\nAGENTS NAME,AGENTS ID,SALES COUNT\n";


//END OF REPORTS


//START OF AUDIOSTORE/VOICE FILES

##########################go_audiostore.php#######################################

$lang['go_entry_3_char_search'] = "Please enter at least 3 characters to search.";
$lang['go_pls_inc_wav_file'] 	= "Please include a .WAV file.";
$lang['go_size'] 		= "SIZE";
$lang['go_play'] 		= "PLAY";
$lang['go_pls_upload_audio'] 	= "Please upload only audio files.<br/>We strongly recommend <strong>.WAV</strong> files.";
$lang['go_err_uploading'] 	= "Error uploading";
$lang['go_voice_files_tooltip'] = "The Voice Files screen displays all the voice files that you have uploaded to your account.";
$lang['go_voice_file_upload'] 	= "Voice File to Upload";
$lang['go_strongly_recommend'] 	= "We STRONGLY recommend uploading only 16bit Mono 8k PCM WAV audio files(.wav)";
$lang['go_clear_search'] 	= "Clear Search";
$lang['go_search'] 		= "Search";
$lang['go_voice_files'] 	= "Voice Files";
$lang['go_upload'] = "UPLOAD";
$lang['go_current_file_status'] = "Current File Status";
$lang['go_good'] = "Good";
$lang['go_bad'] = "Bad";
$lang['go_duplicate'] = "Duplicate";
$lang['go_postal_match'] = "Postal Match";
$lang['go_con_del'] = "Confirm to delete";
$lang['go_success_del'] = "successfully deleted";
$lang['go_modify_ingroup'] = "Modify In-Group";
$lang['go_color'] = "Color";
$lang['go_web_form'] = "Web Form";
$lang['go_next_agent_call'] = "Next Agent Call";
$lang['go_queue_priority'] = "Queue Priority";
$lang['go_on_hook_ring_time'] = "On-Hook Ring Time";
$lang['go_fronter_display'] = "Fronter Display";
$lang['go_script'] = "Script:";
$lang['go_script_caps'] = "SCRIPT";
$lang['go_ignore_list_script'] = "Ignore List Script Override";
$lang['go_get_call_launch'] = "Get Call Launch";
$lang['go_webform'] = "WEBFORM";
$lang['go_form_caps'] = "FORM";
$lang['go_transfer_conf'] = "Transfer-Conf DTMF";
$lang['go_transfer_conf_number'] = "Transfer-Conf Number";
$lang['go_timer_action'] = "Timer Action";
$lang['go_d1_dial'] = "D1_DIAL";
$lang['go_d2_dial'] = "D2_DIAL";
$lang['go_d3_dial'] = "D3_DIAL";
$lang['go_d4_dial'] = "D4_DIAL";
$lang['go_d5_dial'] = "D5_DIAL";
$lang['go_message_only'] = "MESSAGE_ONLY";
$lang['go_hangup'] = "HANGUP";
$lang['go_callmenu'] = "CALLMENU";
$lang['go_exten'] = "EXTENSION";
$lang['go_in_group'] = "IN-GROUP";
$lang['go_in_groups'] = "IN_GROUP";
$lang['go_timer_action_msg'] = "Timer Action Message";
$lang['go_timer_action_seconds'] = "Timer Action Seconds";
$lang['go_timer_action_destination'] = "Timer Action Destination";
$lang['go_drop_call_seconds'] = "Drop Call Seconds";
$lang['go_drop_action'] = "Drop Action";
$lang['go_drop_actions'] = "DROP_ACTION";
$lang['go_msg'] = "MESSAGE";
$lang['go_voicemail'] = "VOICEMAIL";
$lang['go_drop_exten'] = "Drop Exten";
$lang['go_vm'] = "Voicemail";
$lang['go_drop_transfer_group'] = "Drop Transfer Group";
$lang['go_call_time'] = "Call Time";
$lang['go_after_hours_action'] = "After Hours Action";
$lang['go_after_hours_msg_filename'] = "After Hours Message Filename";
$lang['go_audio_chooser'] = "Audio Chooser";
$lang['go_after_hours_exten'] = "After Hours Extension";
$lang['go_after_hours_vm'] = "After Hours Voicemail";
$lang['go_vm_chooser'] = "Voicemail Chooser";
$lang['go_no_delay_call_route'] = "No Delay Call Route";
$lang['go_after_hours_transfer_group'] = "After Hours Transfer Group";
$lang['go_no_agents_no_queueing'] = "No Agents No Queueing";
$lang['go_no_paused'] = "NO_PAUSED";
$lang['go_no_agent_no_queue_action'] = "No Agent No Queue Action";
$lang['go_ingroup'] = "INGROUP";
$lang['go_welcome_msg_filename'] = "Welcome Message Filename";
$lang['go_play_welcome_msg'] = "Play Welcome Message";
$lang['go_always'] = "ALWAYS";
$lang['go_never'] = "NEVER";
$lang['go_if_wait_only'] = "IF_WAIT_ONLY";
$lang['go_yes_unless_nodelay'] = "YES_UNLESS_NODELAY";
$lang['go_moh_context'] = "Music On Hold Context";
$lang['go_moh_chooser'] = "Moh Chooser";
$lang['go_on_hold_prompt_filename'] = "On Hold Prompt Filename";
$lang['go_on_hold_prompt_interval'] = "On Hold Prompt Interval";
$lang['go_on_hold_prompt_no_block'] = "On Hold Prompt No Block";
$lang['go_on_hold_prompt_seconds'] = "On Hold Prompt Seconds";
$lang['go_play_place_line'] = "Play Place in Line";
$lang['go_play_estimated_hold_time'] = "Play Estimated Hold Time";
$lang['go_calculate_estimated_hold_seconds'] = "Calculate Estimated Hold Seconds";
$lang['go_estimated_hold_time_min_filename'] = "Estimated Hold Time Minimum Filename";
$lang['go_estimated_hold_time_min_prompt_no_block'] = "Estimated Hold Time Minimum Prompt No Block";
$lang['go_estimated_hold_time_min_prompt_seconds'] = "Estimated Hold Time Minimum Prompt Seconds";
$lang['go_wait_time_option'] = "Wait Time Option";
$lang['go_press_stay'] = "PRESS_STAY";
$lang['go_press_vmail'] = "PRESS_VMAIL";
$lang['go_press_exten'] = "PRESS_EXTEN";
$lang['go_press_callmenu'] = "PRESS_CALLMENU";
$lang['go_press_cid_callback'] = "PRESS_CID_CALLBACK";
$lang['go_press_ingroup'] = "PRESS_INGROUP";
$lang['go_click_tabs_swap_logical_sections'] = "Click tabs to swap between content that is broken into logical sections.";
$lang['go_nones'] = "none";
$lang['go_agent_ranks_inbound_group'] = "AGENT RANKS FOR THIS INBOUND GROUP";
$lang['go_uniqueid_status_prefix'] = "Uniqueid Status Prefix";
$lang['go_modify'] = "MODIFY";
$lang['go_uniqueid_status_display'] = "Uniqueid Status Display";
$lang['go_disabled'] = "DISABLED";
$lang['go_enabled'] = "ENABLED";
$lang['go_enabled_prefix'] = "ENABLED_PREFIX";
$lang['go_enabled_preserve'] = "ENABLED_PRESERVE";
$lang['go_exten_append_cid'] = "Extension Append CID";
$lang['go_add_lead_url'] = "Add Lead URL";
$lang['go_dispo_call_url'] = "Dispo Call URL";
$lang['go_start_call_url'] = "Start Call URL";
$lang['go_stats_percent_calls_answered'] = "Stats Percent of Calls Answered Within X seconds ";
$lang['go_in_group_recording_filename'] = "n-Group Recording Filename";
$lang['go_in_group_recording_override'] = "In-Group Recording Override";
$lang['go_ondemand'] = "ONDEMAND";
$lang['go_allcalls'] = "ALLCALLS";
$lang['go_allforce'] = "ALLFORCE";
$lang['go_hold_recall_transfer_in_group'] = "Hold Recall Transfer In-Group";
$lang['go_default_group_alias'] = "Default Group Alias";
$lang['go_default_transfer_group'] = "Default Transfer Group";
$lang['go_agent_alert_delay'] = "Agent Alert Delay";
$lang['go_agent_alert_filename'] = "Agent Alert Filename";
$lang['go_hold_time_option_callback_list_id'] = "Hold Time Option Callback List ID";
$lang['go_hold_time_option_after_press_filename'] = "Hold Time Option After Press Filename";
$lang['go_hold_time_option_press_filename_seconds'] = "Hold Time Option Press Filename Seconds";
$lang['go_hold_time_option_press_no_block'] = "Hold Time Option Press No Block";
$lang['go_hold_time_option_press_filename'] = "Hold Time Option Press Filename";
$lang['go_hold_time_option_transfer_in_group'] = "Hold Time Option Transfer In-Group";
$lang['go_hold_time_option_vm'] = "Hold Time Option Voicemail";
$lang['go_hold_time_option_callmenu'] = "Hold Time Option Callmenu";
$lang['go_hold_time_option_exten'] = "Hold Time Option Extension";
$lang['go_hold_time_option_min'] = "Hold Time Option Minimum";
$lang['go_hold_time_option_seconds'] = "Hold Time Option Seconds";
$lang['go_hold_time_third_option'] = "Hold Time Third Option";
$lang['go_hold_time_second_option'] = "Hold Time Second Option";
$lang['go_wait_time_second_option'] = "Wait Time Second Option";
$lang['go_wait_time_option_seconds'] = "Wait Time Option Seconds";
$lang['go_wait_time_option_exten'] = "Wait Time Option Extension";
$lang['go_wait_time_option_callmenu'] = "Wait Time Option Callmenu";
$lang['go_wait_time_option_vm'] = "Wait Time Option Voicemail";
$lang['go_wait_time_option_transfer_in_group'] = "Wait Time Option Transfer In-Group";
$lang['go_wait_time_option_press_filename'] = "Wait Time Option Press Filename";
$lang['go_wait_time_option_press_no_block'] = "Wait Time Option Press No Block";
$lang['go_wait_time_option_press_filename_seconds'] = "Wait Time Option Press Filename Seconds";
$lang['go_wait_time_option_after_press_filename'] = "Wait Time Option After Press Filename";
$lang['go_wait_time_option_callback_list_id'] = "Wait Time Option Callback List ID";
$lang['go_wait_hold_option_priority'] = "Wait Hold Option Priority";
$lang['go_wait_caps'] = "WAIT";
$lang['go_both'] = "BOTH";
$lang['go_estimated_hold_time_option'] = "Estimated Hold Time Option";
$lang['go_call_menu'] = "CALL_MENU";
$lang['go_callerid_callback'] = "CALLERID_CALLBACK";
$lang['go_lower'] = "Lower";
$lang['go_higher'] = "Higher";
$lang['go_case_sensitive'] = "case sensitive";
$lang['go_default_value'] = "Default Value";
//model
$lang['go_group_not_added_already_id'] = "GROUP NOT ADDED - there is already a group in the system with this ID";
$lang['go_group_not_added_pls_back'] = "GROUP NOT ADDED - Please go back and look at the data you entered <br>Group ID must be between 2 and 20 characters in length and contain no ' -+'. <br>Group name and group color must be at least 2 characters in length";
$lang['go_added_missing_user'] = "added missing user to viga table";
$lang['go_user'] = "USER";
$lang['go_selected_caps'] = "SELECTED";
$lang['go_rank_caps'] = "RANK";
$lang['go_calls_today'] = "CALLS TODAY";
$lang['go_sounds_list_permission'] = "sounds_list USER DOES NOT HAVE PERMISSION TO VIEW SOUNDS LIST";
$lang['go_sounds_list_csc_nactive'] = "sounds_list CENTRAL SOUND CONTROL IS NOT ACTIVE";
$lang['go_modify_fields'] = "modify fields";
$lang['go_summary_fields'] = "Summary of fields";
$lang['go_create_custom_field'] = "Create custom field";
$lang['go_view_custom_fields'] = "View custom fields";
$lang['go_custom_listings'] = "View custom listings";
$lang['go_rank'] = "Rank";
$lang['go_label'] = "Label";
$lang['go_name'] = "Name";
$lang['go_type'] = "Type";
$lang['go_modify_s'] = "Modify";
$lang['go_del'] = "delete";

##################################MUSIC ON HOLD###############################################
$lang['go_err_permission_view'] = "Error: You do not have permission to view this page.";
$lang['go_file_type_wav'] = "File type should be in wav format.";
$lang['go_group_not_added_data'] = "GROUP NOT ADDED - Please go back and look at the data you entered";
$lang['go_moh_id_caps'] = "MOH ID";
$lang['go_moh_name_caps'] = "MOH NAME";
$lang['go_status_caps'] = "STATUS";
$lang['go_group_caps'] = "GROUP";
$lang['go_action_caps'] = "ACTION";
$lang['go_active_caps'] = "ACTIVE";
$lang['go_inactive_caps'] = "INACTIVE";
$lang['go_all_caps'] = "ALL";
$lang['go_del_moh_id'] = "DELETE MOH ID";
$lang['go_modify_moh_id'] = "MODIFY MOH ID";
$lang['go_random_order_caps'] = "RANDOM ORDER";
$lang['go_pls_sel_moh_id'] = "Please select an MOH ID.";
$lang['go_del_sel_moh_id'] = "Are you sure you want to delete the selected MOH ID";
$lang['go_del_moh'] = "Are you sure you want to delete";
$lang['go_moh_entry'] = "MOH ENTRY";
$lang['go_del_filename'] = "Are you sure you want to delete";
$lang['go_from_list_files'] = "from the list of files?";
$lang['go_moh_file_entry'] = "MOH FILE ENTRY ";
$lang['go_add_new_moh'] = "Add New Music On Hold";
$lang['go_music_on_hold'] = "Music On Hold";
$lang['go_moh_listing'] = "Music On Hold (MOH) Listings";
$lang['go_modify_moh'] = "MODIFY MUSIC ON HOLD";
$lang['go_moh_name'] = "Music on Hold Name";
$lang['go_random_order'] = "Random Order";
$lang['go_add_audio_file'] = "Add an Audio File";
$lang['go_moh'] = "Music On Hold";
$lang['go_moh_id_3_chars'] = "MoH ID should not be empty or should be at least 3 characters in length.";
$lang['go_moh_id_navailable'] = "MoH ID Not Available.";
$lang['go_moh_wizard'] = "Music on Hold Wizard";
$lang['go_add_new_moh'] = "Add New Music on Hold";
$lang['go_moh_id'] = "Music on Hold ID";
$lang['go_moh_name'] = "Music on Hold Name";
$lang['go_err_permission_view'] = "Error: You do not have permission to view this page.";
$lang['go_success_caps'] = "SUCCESS";
$lang['go_sel_audio_upload'] = "Select an audio file to upload";
$lang['go_all_user_groups'] = "All User Groups";
$lang['go_moh_item'] = "music on hold item";



####################################### Interactive Voice Response (IVR) Menus ################################

$lang['go_ivr_menus'] = "Interactive Voice Response (IVR) Menus";
$lang['go_menu_id_caps'] = "MENU ID";
$lang['go_descriptions_caps'] = "DESCRIPTIONS";
$lang['go_prompt_caps'] = "PROMPT";
$lang['go_timeout_caps'] = "TIMEOUT";
$lang['goaction_caps'] = "ACTION";
$lang['go_modify_ivr'] = "MODIFY IVR";
$lang['go_del_ivr'] = "DELETE IVR";
$lang['go_del_sel'] = "Delete Selected";
$lang['go_modify_callmenu'] = "MODIFY CALLMENU";
$lang['go_menu_id'] = "Menu ID";
$lang['go_menu_name'] = "Menu Name";
$lang['go_menu_prompt'] = "Menu Prompt";
$lang['go_menu_timeout'] = "Menu Timeout";
$lang['go_menu_timeout_prompt'] = "Menu Timeout Prompt";
$lang['go_menu_invalid_prompt'] = "Menu Invalid Prompt";
$lang['go_menu_repeat'] = "Menu Repeat";
$lang['go_menu_time_check'] = "Menu Time Check";
$lang['go_call_time'] = "Call Time";
$lang['go_tracks_call_real_time_report'] = "Track Calls in Real-Time Report";
$lang['go_tracking_group'] = "Tracking Group";
$lang['go_custom_dialplan_entry'] = "Custom Dialplan Entry";
$lang['go_call_menu_options'] = "Call Menu Options";
$lang['go_option'] = "Option";
$lang['go_description'] = "Description";
$lang['go_audio_file'] = "Audio File";
$lang['go_route'] = "route";
$lang['go_audio_chooser'] = "audio chooser";
$lang['go_save'] = "Save";
$lang['go_finish'] = "Finish";






############################# Admin Settings ##############




$lang['go_agent_id'] = "Agent ID";
$lang['go_none'] = "NONE";
$lang['go_ignore_list_script_over'] ="Ignore List Script Override";
$lang['go_admin'] = "ADMIN"; 
$lang['go_phone'] = "Phone";
$lang['go_adv_settings'] = "ADVANCE SETTINGS"; 
$lang['go_save_settings'] = "SAVE SETTINGS"; 
$lang['go_codecs'] = "Codecs";

// Interactive Voice Response (IVR) Menus tab
$lang['go_displaying'] = "Displaying";


// Add New Call Menu/Modify
$lang['go_audio_chooser'] = "audio chooser";
$lang['go_invalid_ip_add'] = "Invalid IP address";
$lang['go_back'] = "Back";
$lang['go_err_permission_view'] =  "Error: You do not have permission to view this page.";
$lang['go_added_new_ug'] = "Added New User Group";
$lang['go_added_new_phone'] = "Added Phone";
$lang['go_added_new_server'] = "Added New Server";
$lang['go_add_new_ga'] = "Add new Group Access";
$lang['go_del'] = "Delete";


// Add new Music on hold
$lang['go_search_IP_admin'] = "Search for User/IP Address within Admin Logs";
$lang['go_del'] = "Delete"; 


// Voice Files
$lang['go_voice_files'] = "Voice Files"; 
$lang['go_no'] = "NO";
$lang['go_upload'] = "Upload"; 

//CALL REPORTS

// [1] Statistical Report
$lang['go_statistical_report'] = "Statistical Report"; 



// [3] Agent Performance Details
$lang['go_full_name'] = "Full Name";




// [5] Sales Per Agent
$lang['go_outbound_camp'] = "Outbound Campaign";
$lang['go_inbound'] = "Inbound";

// [6] Sales Tracker
$lang['go_first_name'] = "First Name";
$lang['go_last_name'] = "Last Name";



// [8] Export Call Report
$lang['go_campaigns'] = "Campaigns";
$lang['go_no_campaigns'] =  "There are no campaigns using this carrier";
$lang['go_carrier_not_available'] = "Carrier ID Not Available";
$lang['go_jgvp_already'] = "A JustGoVoIP account already exist";
$lang['go_submit'] = "Submit"; 

// [9] Dashboard
$lang['go_dashboard'] = "Dashboard"; 



##################### Admin Settings ##########################

//Call times

$lang['go_success_ct'] = "Success!\n\nCall time";
$lang['go_has_been_modified'] = "has been modified.";
$lang['go_success_state_ct'] = "Success!\n\nState call time ";
$lang['go_success_state_call_time_rule'] = "Success!\n\nState call time rule";
$lang['go_pls_sel_state_call_time_rule'] = "Please select a state call time rule.";
$lang['go_want_to_del'] = "Are you sure you want to delete";
$lang['go_from_list'] = "from the list?";
$lang['go_has_been_del'] = "has been deleted.";
$lang['go_call_time_id_navailable'] = "Call Time ID Not Available.";
$lang['go_success_added_new_call_time_id'] = "SUCCESS: Added New Call Time ID.";
$lang['go_call_time_already_exist'] = "A call time id already exist.";
$lang['go_call_time_already_exist'] = "A call time id already exist.";
$lang['go_state_call_time_id_navailable'] = "State Call Time ID Not Available.";
$lang['go_success_added_new_state_ct'] = "SUCCESS: Added New State Call Time ID.";
$lang['go_all_user_groups_caps'] = "ALL USER GROUPS";

//Carriers
$lang['go_pls_sel_carrier'] = "Please select a Carrier.";
$lang['go_del_sel_carrier'] = "Are you sure you want to delete the selected Carrier";
$lang['go_modify_carrier'] = "MODIFY CARRIER";
$lang['go_del_carrier'] = "DELETE CARRIER";
$lang['go_custom_caps'] = "CUSTOM";
$lang['go_comma_delimited'] = "comma delimited eg. speex,g711";
$lang['go_enter_custom_dtmf'] = "Enter Custom DTMF";
$lang['go_not_active_caps'] = "NOT ACTIVE";
$lang['go_live_agent_caps'] = "LIVE AGENT";
$lang['go_external_caps'] = "EXTERNAL";

// [1] Admin Logs
$lang['go_search_logs'] = "Search Logs";
$lang['go_db_query'] = "DB QUERY";
$lang['go_show_query'] = "show query";
$lang['go_logs'] = "Logs";
$lang['go_logs_s'] = "logs";
$lang['no_logs_found'] = "No log(s) found.";
$lang['go_user'] = "User"; 
$lang['go_ip_address'] = "IP Address";
$lang['go_date'] = "DATE";
$lang['go_details'] = "Details";
$lang['go_db_query'] = "DB Query";


//Multi-tenant
$lang['go_del_tenant_warning'] = "WARNING! Are you sure you want to delete the selected Tenant";
$lang['go_del_tenant_warning1'] = "This will DELETE ALL entries under the selected tenant";
$lang['go_del_tenant_warning2'] = "Click OK to continue.";
$lang['go_users'] = "Users";
$lang['go_warning_want_del'] ="WARNING! Are you sure you want to delete";
$lang['go_del_all_entries'] = "This will DELETE ALL entries under this tenant.";
$lang['go_users_a'] = "> Users";
$lang['go_campaigns_a'] = "> Campaigns";
$lang['go_list_ids_a'] = "> List IDs";
$lang['go_phones_a'] = "> Phones";
$lang['go_leads_uploaded_a'] = "> Leads uploaded";
$lang['go_click_continue_a'] = "Click OK to continue.";
$lang['go_campaigns'] = "Campaigns";
$lang['go_list_ids'] = "List IDs";
$lang['go_list_id'] = "List ID";
$lang['go_list_name'] = "LIST NAME";
$lang['go_late_uploaded'] = "Leads uploaded";
$lang['go_bal'] = "BALANCE";
$lang['go_tenant_id'] = "TENANT ID";
$lang['go_tenant_name'] = "TENANT NAME";
$lang['go_num_agents'] = "NUMBER OF AGENTS";
$lang['go_show_bal'] = "Show Balance";
$lang['go_show'] = "Show";
$lang['go_modify_tenant_id'] = "MODIFY TENANT ID";
$lang['go_del_tenant_id'] = "DELETE TENANT ID";
$lang['go_del_tenant'] = "Deleted Tenant";
$lang['go_del_user_camp_list'] = "it's admin and all user logins under it and also deleted the Campaign(s)";
$lang['go_and_list_id'] = "and List ID(s)";
$lang['go_admin_campaign'] = "as the Admin Login and created new Campaign";
$lang['go_ap_using'] = "Agents/Phones using";
$lang['go_with'] = "With";
$lang['go_added_tenant'] = "Added a New Tenant";
$lang['go_autogen'] = "Auto-Generated ListID";
$lang['go_manual'] = "MANUAL";
$lang['go_random'] = "random";
$lang['go_ap'] = "Agent Phone";
$lang['go_admin_phone'] = "Admin Phone";
$lang['go_down'] = "DOWN";

$lang['go_tenant_entry'] = "TENANT ENTRY";
$lang['go_add_new_tenant'] = "Add New Tenant";
$lang['go_modify_user'] = "Modify User";
$lang['go_phone_passs'] = "Phone Pass";
$lang['go_phone_login'] = "Phone Login";
$lang['go_tenant_short'] = "Tenant ID too short.";
$lang['go_tenant_empty'] = "Tenant name should not be empty.";
$lang['go_admin_login_short'] = "Admin login too short.";
$lang['go_admin_login_navailable'] = "Admin login not available.";
$lang['go_admin_pass_short'] = "Admin password too short.";
$lang['go_error'] ="Error";
$lang['go_too_short'] = "Too Short";
$lang['go_weak'] = "Weak";
$lang['go_strong'] = "Strong";
$lang['go_good'] = "Good";
$lang['go_add_multitenant_wizard'] ="Multi-Tenant Wizard";
$lang['go_add_new_tenant'] = "Add New Tenant";
$lang['go_tenant_id'] = "Tenant ID";
$lang['go_tenant_name'] = "Tenant Name";
$lang['go_admin_login'] = "Admin Login";
$lang['go_admin_pass'] = "Admin Password";
$lang['go_group_template'] = "Group Template";
$lang['go_create_ct'] = "Can Create Call Times";
$lang['go_create_carriers'] = "Can Create Carriers";
$lang['go_create_phones'] = "Can Create Phones";
$lang['go_create_vm'] = "Can Create Voicemails";
$lang['go_agent_phone_cnt'] = "Agent/Phone Count";
$lang['go_agent_phone_def_pass'] = "Agent/Phone Default Password";
$lang['go_modify_tenant'] = "MODIFY TENANT";
$lang['go_super_admin'] = "Super Admin";
$lang['go_num_admins'] = "Number of Admins";
$lang['go_remaining_bal'] = "Remaining Balance";
$lang['go_access_ct'] = "Access Call Times";
$lang['go_access_carriers'] = "Access Carriers";
$lang['go_access_phones'] = "Access Phones:";
$lang['go_access_vm'] = "Access Voicemails";
$lang['go_phone_exten_tenant'] = "PHONES EXTEN USED BY THIS TENANT";
$lang['go_list_id_tenant'] = "LIST IDS WITHIN THIS TENANT";
$lang['go_campaign_id'] = "CAMPAIGN ID";
$lang['go_campaign_name'] = "CAMPAIGN NAME";
$lang['go_agent_name'] = "AGENT NAME";
$lang['go_list_agents'] = "LIST OF AGENTS";

// State Call Times
$lang['go_state_ct_wizard'] = "State Call Times Wizard";
$lang['go_add_new_state_ct'] = "Add New State Call Time";
$lang['go_state_ct_time_id'] = "State Call Time ID";
$lang['go_state_ct_state'] = "State Call Time State";
$lang['go_state_ct'] = "State Call Time Name";
$lang['go_state_ct_comments'] = "State Call Time Comments";

// [2] Call Times
$lang['go_call_times'] = "Call Times";
$lang['go_call_times_s'] = "call times";
$lang['go_ct_banner'] = "Call Times";
$lang['go_state_call_times'] = "State Call Times";
$lang['go_state_call_not_avai'] = "State Call Time ID Not Available.";
$lang['go_state_Call_exist'] = "A state call time id already exist.";
$lang['go_list_files_upload'] = "List of Files Uploaded";
$lang['go_modify_inbound'] = "MODIFY INBOUND";
$lang['go_call_time_id'] = "Call Time ID";
$lang['go_call_time_name'] = "Call Time Name";
$lang['go_call_time_state'] = "Call Time State";
$lang['go_default_start'] = "DEFAULT START";
$lang['go_default_stop'] = "DEFAULT STOP";
$lang['go_group'] = "Group";
$lang['go_action'] = "Action"; 
$lang['go_add_state_call_time'] = "Add New State Call Times";
$lang['go_call_times_wizard'] = "Call Times Wizard";
$lang['go_add_new_call_time'] = "Add New Call Time";
$lang['go_call_time_comments'] = "Call Time Comments";
$lang['go_pls_sel_ug'] = "Please select a User Group.";
$lang['go_pls_sel_server'] = "Please select a Server.";
$lang['go_del_con_server'] = "Are you sure you want to delete the selected Server";
$lang['go_del_con_ug'] = "Are you sure you want to delete the selected User Group";
$lang['go_admin_exempt'] = "ADMIN EXEMPT";
$lang['go_multi_tenant'] = "Multi-Tenant";
$lang['go_next'] = "Next";
$lang['go_start'] = "START";
$lang['go_stop'] = "STOP";
$lang['go_sun'] = "Sunday";
$lang['go_sat'] = "Saturday";
$lang['go_success_s'] = "Success!";
$lang['go_call_time_s'] = "Call time";
$lang['go_has_been_modified'] = "has been modified.";
$lang['go_state_call_time_s'] = "State call time";
$lang['go_state_call_time_rule_s'] = "State call time rule";

//Modify Call time
$lang['go_pls_sel_call_time'] = "Please select a Call Time.";
$lang['go_del_sel_ct'] = "Are you sure you want to delete the selected Call Time";
$lang['go_sel_ct_del'] = "Selected call times has been deleted.";
$lang['go_pls_sel_state_call_time'] = "Please select a State Call Time.";
$lang['go_del_sel_state_ct'] = "Are you sure you want to delete the selected State Call Time";
$lang['go_sel_state_ct_del'] = "Selected state call times has been deleted.";
$lang['go_modify'] = "MODIFY";
$lang['go_call_time'] = "CALL TIME";
$lang['go_call_time_comments'] = "Call Time Comments";
$lang['go_after_hours_audio'] = "AFTER HOURS AUDIO";
$lang['go_default'] = "Default";
$lang['go_mon'] = "Monday";
$lang['go_tues'] = "Tuesday";
$lang['go_wed'] = "Wednesday";
$lang['go_thurs'] = "Thursday";
$lang['go_fri'] = "Friday";
$lang['go_active_state_call'] = "ACTIVE STATE CALL TIME FOR THIS RECORD";
$lang['go_state_call_time_id'] = "STATE CALL TIME ID";
$lang['go_state_call_defi'] = "STATE CALL TIME DEFINITION";
$lang['go_add_state_call_t_rule'] = "ADD STATE CALL TIME RULE";
$lang['go_add_rule'] = "ADD RULE";
$lang['go_state_call_time_id'] = "State Call Time ID";
$lang['go_state_Call_time_state'] = "State Call Time State";
$lang['go_state_call_time_name'] = "State Call Time Name";
$lang['go_state_call_time_comments'] = "State Call Time Comments";
$lang['go_campaigns_using_call_time'] = "CAMPAIGNS USING THIS CALL TIME";
$lang['go_inbound_groups_call_time'] = "INBOUND GROUPS USING THIS CALL TIME";
$lang['go_call_times_state'] = "CALL TIMES USING THIS STATE CALL TIME";
$lang['go_success'] =  "Success";
$lang['go_deld_ug'] = "Deleted User Group";
$lang['go_deld_Phone'] = "Deleted Phone";
$lang['go_deld_server'] = "Deleted Server";
$lang['go_del_ug_uag_table'] = "Deleted User Group user access group table";


// [3] Carriers
$lang['go_carriers'] = "Carriers";
$lang['go_carriers_server'] = "CARRIERS WITHIN THIS SERVER";
$lang['go_phones_server'] = "PHONES WITHIN THIS SERVER";
$lang['go_confer_server'] = "CONFERENCES WITHIN THIS SERVER";
$lang['go_60_sec_effect'] = "Please wait up to 60 seconds for the changes to take effect";
$lang['go_acc_already_exist'] = "Account entry already exist for this IP";
$lang['go_add_new_carrier'] = "Add New Carrier";
$lang['go_carrier_id'] = "Carrier ID";
$lang['go_carrier_name'] = "Carrier Name";
$lang['go_server_ip'] = "Server IP"; 
$lang['go_protocol'] = "Protocol";
$lang['go_registration'] = "Registration";
$lang['go_status'] = "Status"; 
$lang['go_web_login'] = "Web Login";
$lang['go_web_pass'] = "Web Password";
$lang['go_sip_login'] = "SIP Login";
$lang['go_sip_pass'] = "SIP Password";
$lang['go_voip_portal'] = "VoIP Portal";
$lang['go_login_here'] = "Login Here";
$lang['go_no_records_found'] = "No record(s) found";

//Modify Carrier
$lang['go_modified_server'] = "MODIFY SERVER";
$lang['go_modified_phone'] = "MODIFY PHONE";
$lang['go_activated_exten'] = "Activated Extensions(s)";
$lang['go_deact_exten'] = "Deactivated Extensions(s)";
$lang['go_carrier_desc'] = "Carrier Description";
$lang['go_reg_str'] = "Registration String";
$lang['go_account_entry'] = "Account Entry";
$lang['go_global_str'] = "Global String";
$lang['go_globals_str'] = "Globals String";
$lang['go_dialplan_entry'] = "Dialplan Entry";
$lang['go_authentication'] = "Authentication";
$lang['go_ip_based'] = "IP Based";
$lang['go_username'] = "Username";
$lang['go_server_ip_host'] = "Server IP/Host";
$lang['go_port'] = "Port";
$lang['go_codecs'] = "Codecs";
$lang['go_dtmf_mode'] = "DTMF Mode";
$lang['go_inband'] = "Inband";
$lang['go_custom'] = "Custom";
$lang['go_customs'] = "CUSTOM";
$lang['go_err_save_phone'] = "Error: Error on saving phone";
$lang['go_success_save_phone'] ="Success: Phone saved";
$lang['go_adv_conf'] = "ADVANCE CONFIGURATION";



// Add New Carrier
$lang['go_carrier_wizard'] = "Carrier Wizard";
$lang['_add_new_carrier'] = "Add New Carrier";
$lang['go_carrier_type'] = "Carrier type";


//Sign up
$lang['go_sign_up'] = "Signup";
$lang['go_required'] = "Required";
$lang['go_fill_out'] = "Please fill out the information below";
$lang['go_company'] = "Company";
$lang['go_address'] = "Address";
$lang['go_city'] = "City";
$lang['go_state'] = "State";
$lang['go_postal_code'] = "Postal Code";
$lang['go_country'] = "Country";
$lang['go_time_zone'] = "Time Zone";
$lang['go_mobile_phone'] = "Mobile Phone";

//Term And Condition
$lang['go_terms_and_condition'] = "Terms and Condition";
$lang['go_tc1'] = "This site is owned and operated by";
$lang['go_tc2'] = '("we", "us", "our" or';
$lang['go_tc3'] = 'provides its services to you ("Customer", "you" or "end user") 
		  subject to the following conditions';
$lang['go_tc4'] = "If you visit or shop at our website or any other affiliated";
$lang['go_tc5'] = "reverse phone lookup";
$lang['go_tc6'] = "websites,
                  you affirmatively accept the following conditions.
                  Continued use of the site and any of";
$lang['go_tc7'] = "services constitute the affirmative agreement to these terms and conditions.";
$lang['go_tc8'] = "reserves the right to change the terms, conditions and notices under which the";
$lang['go_tc9'] = "sites and services are offered,including but not limited to the charges associated with the use of the";
$lang['go_tc10'] = "sites and services.";
$lang['go_tc11'] = "1. Electronic Communications";
$lang['go_tc12'] = "1.1. When you visit";
$lang['go_tc13'] = "websites or send Email to us, you are communicating with us electronically. You consent to receive communications from us electronically. We will communicate with you by Email or by posting notices on this site. You agree that all agreements, notices, disclosures and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing";
$lang['go_tc14'] = "Trademarks and Copyright";
$lang['go_tc144'] = "2.1. All content on this site, such as text, graphics, logos, button icons, images, trademarks or copyrights are the property of their respective owners. Nothing in this site should be construed as granting any right or license to use any Trademark without the written permission of its owner.";
$lang['go_tc15'] = "3. Services & Conditions";
$lang['go_tc16'] = "shall not be held liable for any delay or failure to provide service(s) at any time. In no event shall";
$lang['go_tc17'] = "its officers, Directors, Employees, Shareholders, Affiliates, Agents or Providers who furnishes services to customer in connection with this agreement or the service be liable for any direct, incident, indirect, special, punitive, exemplary or consequential damages, including but not limited to loss of data, lost of revenue, profits or anticipated profits, or damages arising out of or in connection to the use or inability to use the service. The limitations set forth herein apply to the claimed founded in Breach of Contract, Breach of Warranty, Product Liability and any and all other liability and apply weather or not";
$lang['go_tc18'] = "was informed of the likely hood of any particular type of damage.";
$lang['go_tc19'] = "makes no warranties of any kind, written or implied, to the service in which it provides.";
$lang['go_tc200'] = "provides prepaid services only. You must keep a positive balance to retain services with";
$lang['go_tc20'] = "You must pay all negative balances immediately. Customer agrees to keep a positive balance in customer's account at all times and agrees to pay the rate in which the customer signed up for any destinations. Customer agrees to pay any and all charges that customer incurs while using";
$lang['go_tc21'] = "service.";
$lang['go_tc22'] = "VOIP and Cloud services are not intended for use as a primary telephone source for business or residential users.";
$lang['go_tc23'] = "does not provide e911 service.";
$lang['go_tc24'] = "All calls placed through";
$lang['go_tc255'] = "VOIP network to US48 destinations are billed at 6 second increments unless otherwise stated.";
$lang['go_tc25'] = "Customer agrees to the exclusive jurisdiction of the courts of Pasig City in the Republic of the Philippines for any and all legal matters.";
$lang['go_tc26'] = "Violation of any state or federal laws or laws for any other competent jurisdiction may result in immediate account termination and/or disconnection of the offending service.";
$lang['go_tc27'] = "reserves the right to terminate service at any time with or without notice; especially if Customer is found to be in violation of";
$lang['go_tc28'] = "Terms & Conditions. You agree that";
$lang['go_tc29'] = "Due to the nature of this industry and high credit card fraud rate,";
$lang['go_tc30'] = "reserves the right to request the following documentation for verification purposes; A copy of the credit card used to establish the account along with valid photo identification such as a Passport, Drivers License or other Government issued identification.";
$lang['go_tc31'] = "DID and TFN (Toll Free Numbers ) Services and Subscriptions Activation and Deactivation";
$lang['go_tc32'] = "DID/TFN monthly service fee shall be automatically deducted or debited from the customer's account balance or credits with or without prior notice; prior to activation of service its subscriptions agreement.";
$lang['go_tc33'] = "Auto-debit of monthly payment shall commence once DID/TFN has been activated.";
$lang['go_tc34'] = "Failure to pay the agreed DID/TFN monthly services and monthly subscription fee (having one [1] month unpaid bill) shall be subject to DID/TFN deactivation.";
$lang['go_tc35'] = "A maximum one 1 month grace period shall be given to the customer to settle his/her account before DID/TFN deactivation and/or deletion.";
$lang['go_tc36'] = "Technical Support";
$lang['go_tc37'] = "Technical Support is available Mondays to Fridays 09:00 to 24:00 24/5 EST, all support concerns should be filed at";
$lang['go_tc38'] = "ticketing system";
$lang['go_tc39'] = "Monthly Technical Support";
$lang['go_tc40'] = "monthly support subscriptions covers the configurations and troubleshooting for the following issues:";
$lang['go_tc41'] = "Campaigns – outbound, inbound and blended campaign creation and configurations
                    Lists/Leads – creation of lists and loading of leads.
                    Statuses/Dispositions configuration
                    Call Times configuration
                    IVR – Basic configuration (one level only)
                    Basic tutorial for Campaign and Leads management.";
$lang['go_tc42'] = "All advance configurations not listed above will be charged with the regular hourly support rate of $80 per hour.";
$lang['go_tc43'] = "We provide limited support and provide samples configurations for IP Phones/Softphones. It is the end users responsibility to properly configure their workstations and devices for use with";
$lang['go_tc44'] = "services.";
$lang['go_tc45'] = "Leads Management, Campaign Management, Agent Monitoring and Reports Generation are end users responsibility.";
$lang['go_tc46'] = "Emergency Technical Support";
$lang['go_tc47'] = "Emergency technical support outside the regular coverage of Monday to Friday 9:00 to 24:00 EST will be charged $80 per hour.";
$lang['go_tc48'] = "Emergency technical support for Weekend and Holidays will be charged $160 per hour.";
$lang['go_tc49'] = "Refund Policy";
$lang['go_tc50'] = "VoIP and Cloud Services: We offer full refunds on remaining pre-paid balance on VoIP and Cloud services upon request for all payments made within 7 days.";
$lang['go_tc51'] = "Monthly Subscriptions: We do not offer refunds for monthly subscriptions such as Hosted Dialer, DID's or Toll Free numbers";
$lang['go_tc52'] = "Prepaid Technical Support and Consulting Services: We offer refunds only if no technical support or consulting service and has been rendered.";
$lang['go_tc53'] = "There will be no refunds for one-time/setup fees";
$lang['go_tc54'] = "Site Policies, Modification & Severability";
$lang['go_tc55'] = "We reserve the right to make changes to our site, policies, and these Terms & Conditions at any time. If any of these conditions shall be deemed invalid, void, or for any reason unenforceable, that condition shall be deemed severable and shall not affect the validity and enforceability of any remaining condition.";
$lang['go_tc56'] = "General Complaints";
$lang['go_tc57'] = "Please send reports of activity in violation of these Terms & Conditions to";
$lang['go_tc58'] = "will reasonably investigate incidents involving such violations.";
$lang['go_tc59'] = "may involve and will cooperate with law enforcement officials if any criminal activity is suspected. Violations may involve criminal and civil liability";
$lang['go_tc60'] = "Paypal Payments";
$lang['go_tc61'] = "In case of payment via PayPal.com, customer fully understands that there will be no tangible product shipping to any address. The customer understands that they are purchasing services for which";
$lang['go_tc62'] = "provides online Call History (CDR) for VOIP/Cloud usage and/or outbound/inbound reports for the Dialer. In case of PayPal disputes the customer agrees to abide by";
$lang['go_tc63'] = "online Call History (CDR) for VOIP/Cloud usage and/or outbound/inbound reports for delivered service totaling the PayPal.com payment.";
$lang['go_tc64'] = "Limitation of Liabilities";
$lang['go_tc65'] = "In no event shall";
$lang['go_tc66'] = "be liable to any party for any direct, indirect, incidental, special, exemplary or consequential damages of any type whatsoever related to or arising from this website or any use of this website, or any site or resource linked to, referenced, or access throught this website, or for the use or downloading of, or access to, any materials, information, products, or services, including withouth limitation, any lost profits, business interruption, lost savings or loss of programs or other data, even if";
$lang['go_tc67'] = "is expressly advised of the possiblity of such damages.";
$lang['go_tc68'] = "Call Compliance";
$lang['go_tc69'] = "has full USA, UK and Canada regulatory compliance. Customer fully understands that it is their responsibility to follow these regulations. Failure to do so may result in immediate account suspension and/or disconnection.";
$lang['go_tc70'] = "I agree to the";
$lang['go_required'] = "Required";
$lang['go_valid_email'] = "Enter valid email";
$lang['go_max_12_char'] = "Maximum of 12 character";
$lang['go_tc71'] = "Not readable? Change text.";
$lang['go_tc72'] = "Enter code from above picture here.";
$lang['go_err_long'] = "Error: Sippy registration too long";
$lang['go_space'] = "Space are not allowed";
$lang['go_min_3_char'] = "Minimum of 3 characters.";
$lang['go_min_2_char'] = "Minimum of 2 characters.";
$lang['go_src_carrier'] = "Source Carrier";
$lang['go_accept_tc'] = "You must accept the terms and conditions";
$lang['go_welcome_to'] = "Welcome to";
$lang['go_ccc'] = "Cloud Call Center";
$lang['go_w1'] = "is an easy to set up and easy to use, do it yourself (DIY) cloud based telephony solution for any type of organization in wherever country you conduct your sales, marketing, service and support activites. Designed for large enterprise-grade call center companies but priced to fit the budget of the Small Business Owner,";
$lang['go_w2'] = "uses intuitive graphical user interfaces so that deployment is quick and hassle-free, among its dozens of hot features.";
$lang['go_w3'] = "Using secure cloud infrastructures certified by international standards,";
$lang['go_w4'] = 'is a "Use Anywhere, Anytime" web app so that you can create more customers for life – in the office, at home or at the beach.';
$lang['go_email'] = "email";
$lang['go_w5'] = "to get 120 free minutes (US, UK and Canada calls only).";

// [4] Phones
$lang['go_exten'] = "Extension";
$lang['go_exten_only'] = "Exten";
$lang['go_server'] = "Server";
$lang['go_dial_plan'] = "Dial Plan";
$lang['go_dial_prefix'] = "Dial Prefix";
$lang['go_del_selected'] = "Delete Selected"; 
$lang['go_num_only'] = "numeric only";
$lang['go_voicemail_box'] = "Voicemail Box";
$lang['go_vm_box_navailable'] = "Voicemail Box Not Available.";
$lang['go_add_new_phone'] = "Add New Phone";

//Add New Phone
$lang['go_phone_wizard'] = "Phone Wizard";
$lang['go_add_new_phone'] = "Add New Phone";
$lang['go_additional_phone'] = "Additional Phone(s)";
$lang['go_starting_exten'] = "Starting Extension";
$lang['go_client_protocol'] = "Client Protocol";
$lang['go_external'] = "EXTERNAL";
$lang['go_tenants'] = "tenants";
$lang['go_phone_exten_login'] = "Phone Extension/Login";
$lang['go_phone_login_pass'] = "Phone Login Password";
$lang['go_phone_reg_password'] = "Phone Registration Password";
$lang['go_dial_prefix'] ="Dial Prefix";
$lang['go_all_user_groups'] ="All User Groups"; 
$lang['go_sip_server'] ="SIP Server";
$lang['go_local_gmt'] = "Local GMT";
$lang['go_do_not_adjust_DST'] ="Do NOT adjust for DST";

//Modify Phone
$lang['go_modify_phone'] ="MODIFY PHONE";
$lang['go_del_phone'] = "DELETE PHONE";
$lang['go_suspended'] ="SUSPENDED";
$lang['go_closed'] = "CLOSED";
$lang['go_pending'] = "PENDING";
$lang['goexternal'] = "EXTERNAL";
$lang['go_pls_sel_tenant'] = "Please select a Tenant.";
$lang['go_phone_ext_limit'] = "WARNING: Phone Extension is over the limit";
$lang['go_pls_support_assistance'] = "Please contact our Support for assistance.";
$lang['go_phone_exten_limit'] = "WARNING: Phone Extension is over the limit of";
$lang['go_exten_exist'] = "WARNING: One or more extensions from the range given are already exist.";
$lang['go_phone_ext_login'] = "Phone Extension/Login";
$lang['go_phone_pass'] = "Phone Password";
$lang['go_pass'] = "Password";
$lang['go_dial_plan_num'] = "Dial Plan Number";
$lang['go_outbound_callerid'] ="Outbound Caller ID";
$lang['go_phone_ip_address'] ="Phone IP Address";
$lang['go_comp_ip_address'] = "Computer IP Address";
$lang['go_agent_screen_login'] = "Agent Screen Login";
$lang['go_sip_reg_pass'] = "SIP Registration Password";
$lang['go_active'] = "ACTIVE"; 
$lang['go_inactive'] = "INACTIVE";
$lang['go_set_webphone'] = "Set As Webphone";
$lang['go_webphone_dialpad'] = "Webphone Dialpad";
$lang['go_use_external_server_ip'] = "Use External Server IP";
$lang['go_active_account'] = "Active Account";
$lang['go_phone_type'] = "Phone Type";
$lang['go_company'] = "Company";
$lang['go_picture'] = "Picture";
$lang['go_new_msg'] = "New Messages";
$lang['go_old_msg'] = "Old Messages";
$lang['go_phone_ring_timeout'] = "Phone Ring Timeout";
$lang['go_onhook_agent'] = "On-Hook Agent";
$lang['go_default_user'] = "Default User";
$lang['go_default_pass'] = "Default Pass";
$lang['go_default_campaign'] = "Default Campaign";
$lang['go_park_exten'] = "Park Exten";
$lang['go_conf_exten'] = "Conf Exten";
$lang['go_monitor_prefix'] = "Monitor Prefix";
$lang['go_recording_exten'] = "Recording Exten";
$lang['go_voicemail_exten'] = "Voicemail Exten";
$lang['go_voicemail_dump_exten'] = "Voicemail Dump Exten";
$lang['go_exten_context'] = "Exten Context";
$lang['go_phone_context'] = "Phone Context";
$lang['go_call_logging'] = "Call Logging";
$lang['go_user_switching'] = "User Switching";
$lang['go_conferencing'] = "Conferencing";
$lang['go_admin_hang_up'] = "Admin Hang Up";
$lang['go_admin_hijack'] = "Admin Hijack";
$lang['go_admin_monitor'] = "Admin Monitor";
$lang['go_call_park'] = "Call Park";
$lang['go_updater_check'] = "Updater Check";
$lang['go_af_logging'] = "AF Logging";
$lang['go_queue_enabled'] = "Queue Enabled";
$lang['go_callerid_popup'] = "CallerID Popup";
$lang['go_voicemail_button'] = "Voicemail Button";
$lang['go_fast_refresh'] = "Fast Refresh";
$lang['go_fast_refresh_rate'] = "Fast Refresh Rate";
$lang['go_persistant_mysql'] =  "Persistant MySQL";
$lang['go_auto_dial_next_num'] =  "Auto Dial Next Number";
$lang['go_stop_recording_after_each_call'] = "Stop Recording After Each Call";
$lang['go_enable_sipsak_msg'] = "Enable SIPSAK Messages";
$lang['go_template_id'] ="Template ID";
$lang['go_conf_override'] = "Conf Override";

// [5] Servers
$lang['go_servers'] = "Servers";
$lang['go_servers_s'] = "servers";
$lang['go_server_id'] = "Server ID";
$lang['go_server_id_navailable'] = "Server ID Not Available.";
$lang['go_server_banner'] = "Servers";
$lang['go_server_entry_del'] = "SERVER ENTRY";
$lang['go_modify_server'] = "MODIFY SERVER";
$lang['go_del_servers'] = "DELETE SERVER";
$lang['go_asterisk'] = "Asterisk";
$lang['go_trunks'] = "Trunks";
$lang['go_add_trunks'] = "Add Trunks";
$lang['go_gmt'] = "GMT";
$lang['go_add_new_server'] = "Add New Server";
$lang['go_vmail'] = "VMAIL";
$lang['go_search'] = "Search";
$lang['go_show_list'] = "showList";



//Add New Server
$lang['go_server_wizard'] = "Server Wizard";
$lang['add_new_server'] = "Add New Server";
$lang['go_available'] = "Available";
$lang['go_act_vm'] = "Activated Voicemail(s):";
$lang['go_act_server'] = "Activated Server(s)";
$lang['go_deact_vm'] = "Deactivated Voicemail(s):";
$lang['go_deact_server'] = "Deactivated Server(s)";
$lang['go_deld_server'] = "Deleted Server(s)";
$lang['go_not_available'] = "Not Available";
$lang['go_err_phone'] = "Error: Phone";
$lang['go_server_desc'] = "Server Description";
$lang['go_asterisk'] = "ASTERISK";
$lang['go_asterisk_version'] ="Asterisk Version";
$lang['go_ok'] = "Okay";
$lang['go_clear_search'] = "Clear Search";

//Modify Server
$lang['go_modify_server'] = "Modify Server";
$lang['go_system_load'] = "System Load";
$lang['go_live_channels'] = "Live Channels";
$lang['go_disk_usage'] = "Disk Usage";
$lang['go_admin_user_group'] = "Admin User Group";
$lang['go_max_trunks'] = "Max Trunks";
$lang['go_max_call_per_second'] = "Max Call per Second";
$lang['go_bal_dialing'] = "Balance Dialing";
$lang['go_bal_rank'] = "Balance Rank";
$lang['go_bal_offlimits'] = "Balance Offlimits";
$lang['go_telnet_host'] = "Telnet Host";
$lang['go_telnet_port'] = "Telnet Port";
$lang['go_manager_user'] = "Manager User";
$lang['go_manager_secret'] = "Manager Secret";
$lang['go_manager_update_user'] = "Manager Update User:";
$lang['go_manager_listen_user'] = "Manager Listen User";
$lang['go_manager_send_user'] = "Manager Send User";
$lang['go_conf_file_secret'] = "Conf File Secret";
$lang['go_weak'] = "Weak";
$lang['go_medium'] = "Medium";
$lang['go_strong'] = "Strong";
$lang['go_local_gmt'] = "Local GMT";
$lang['go_voicemal_dump_exten'] = "Voicemail Dump Exten";
$lang['go_autodial_exten'] = "Autodial Extension";
$lang['go_default_context'] = "Default Context";
$lang['go_system_performance_log'] = "System Performance Log";
$lang['go_server_logs'] = "Server Logs";
$lang['go_agi_output'] = "AGI Output";
$lang['go_carrier_logging_active'] = "Carrier Logging Active";
$lang['go_recording_web_link'] = "Recording Web Link";
$lang['go_alternate_recording_server_ip'] = "Alternate Recording Server IP";
$lang['go_external_server_ip'] = "External Server IP";
$lang['go_active_twin_server_ip'] = "Active Twin Server IP";
$lang['go_active_asterisk_server'] = "Active Asterisk Server";
$lang['go_active_agent_server'] = "Active Agent Server";
$lang['go_generate_conf_files'] = "Generate Conf Files";
$lang['go_rebuild_conf_files'] = "Rebuild conf Files";
$lang['go_rebuild_moh'] = "Rebuild Music On Hold";
$lang['go_sounds_update'] = "Sounds Update";
$lang['go_recording_limit'] = "Recording Limit";
$lang['go_custom_dialplan_entry'] = "Custom Dialplan Entry";
$lang['go_trunks_this_server'] = "TRUNKS FOR THIS SERVER";
$lang['go_add_new_server_tr'] = "ADD NEW SERVER TRUNK RECORD";
$lang['go_restriction'] = "Restriction";
$lang['go_carriers_within_server'] = "CARRIERS WITHIN THIS SERVER";
$lang['go_phones_server'] = "PHONES WITHIN THIS SERVER";
$lang['go_conference_server'] = "CONFERENCES WITHIN THIS SERVER";
$lang['go_conference'] = "conference";



// [6] System Settings
$lang['go_version'] = "Version";
$lang['go_db_schema_version'] = "DB Schema Version";
$lang['go_db_schema_update_date'] = "DB Schema Update Date";
$lang['go_auto_user_add_value'] = "Auto User-add Value";
$lang['go_install_date'] = "Install Date";
$lang['go_use_non_latin'] = "Use Non-Latin";
$lang['go_webroot_writable'] = "Webroot Writable";
$lang['go_vicidial_agent_disable_display'] = "Vicidial Agent Disable Display";
$lang['go_allow_sipsak_msgs'] = "Allow SIPSAK Messages";
$lang['go_admin_home_url'] = "Admin Home URL";
$lang['go_admin_modify_refresh'] = "Admin Modify Refresh";
$lang['go_admin_no_cache'] = "Admin No-Cache";
$lang['go_enable_agent_transfer_logfile'] = "Enable Agent Transfer Logfile";
$lang['go_enable_agent_disposition_logfile'] = "Enable Agent Disposition Logfile";
$lang['go_timeclock_end_of_day'] = "Timeclock End Of Day";
$lang['go_default_local_gmt'] = "Default Local GMT";
$lang['go_timeclock_last_auto_logout'] = "Timeclock Last Auto Logout";
$lang['go_agent_screen_header_date_format'] = "Agent Screen Header Date Format";
$lang['go_agent_screen_customer_date_format'] = "Agent Screen Customer Date Format";
$lang['go_agent_screen_customer_phone_format'] = "Agent Screen Customer Phone Format";
$lang['go_agent_api_active'] = "Agent API Active";
$lang['go_agent_only_callback_campaign_lock'] = "Agent Only Callback Campaign Lock";
$lang['go_central_sound_control_active'] = "Central Sound Control Active";
$lang['go_sounds_web_server'] = "Sounds Web Server";
$lang['go_sounds_web_directory'] = "Sounds Web Directory";
$lang['go_system_settings'] = "System Settings";
$lang['go_admin_web_directory'] = "Admin Web Directory";
$lang['go_active_voicemail_server'] = "Active Voicemail Server";
$lang['go_auto_dial_limit'] = "Auto Dial Limit";
$lang['go_outbound_auto_dial_active'] = "Outbound Auto-Dial Active";
$lang['go_max_fill_calls_per_second'] = "Max FILL Calls per Second";
$lang['go_allow_custom_dialplan_entries'] = "Allow Custom Dialplan Entries";
$lang['go_generate_cross_server_phone_extens'] = "Generate Cross-Server Phone Extensions";
$lang['go_user_territories_active'] = "User Territories Active";
$lang['go_enable_sound_webform'] = "Enable Second Webform";
$lang['go_enable_tts_integration'] = "Enable TTS Integration";
$lang['go_enable_callcard'] = "Enable CallCard";
$lang['go_enable_custom_list_fields'] = "Enable Custom List Fields";
$lang['go_first_login_trigger'] = "First Login Trigger";
$lang['go_default_phone_registration_pass'] = "Default Phone Registration Password";
$lang['go_default_phone_login_pass'] = "Default Phone Login Password";
$lang['go_default_server_pass'] = "Default Server Password";
$lang['go_slave_database_server'] = "Slave Database Server";
$lang['go_reports_use_slave'] = "Reports to use Slave DB";
$lang['go_custom_dialplan_entry'] = "Custom Dialplan Entry";
$lang['go_reload_dialplan_on_servers'] = "Reload Dialplan On Servers";
$lang['go_l_title'] = "Label Title";
$lang['go_l_first_name'] = "Label First Name";
$lang['go_l_middle_name'] = "Label Middle Initial";
$lang['go_l_last_name'] = "Label Last Name";
$lang['go_l_address1'] = "Label Address1";
$lang['go_l_address2'] = "Label Address2";
$lang['go_l_address3'] = "Label Address3";
$lang['go_l_city'] = "Label City";
$lang['go_l_state'] = "Label State";
$lang['go_l_province'] = "Label Province";
$lang['go_l_postal_code'] = "Label Postal Code";
$lang['go_l_vendor_lead_code'] = "Label Vendor Lead Code";
$lang['go_l_gender'] = "Label Gender";
$lang['go_l_phone_number'] ="Label Phone Number";
$lang['go_l_phone_code'] = "Label Phone Code";
$lang['go_l_alt_phone'] = "Label Alt Phone";
$lang['go_l_security_phrase'] = "Label Security Phrase";
$lang['go_l_email'] = "Label Email";
$lang['go_l_commens'] = "Label Comments";
$lang['go_qc_features_active'] = "QC Features Active";
$lang['go_qc_last_pull_time'] = "QC Last Pull Time";
$lang['go_default_codecs'] = "Default Codecs";
$lang['go_default_webphone'] = "Default Webphone";
$lang['go_default_external_server_ip'] = "Default External Server IP";
$lang['go_webphone_url'] = "Webphone URL";
$lang['go_webphone_system_key'] = "Webphone System Key";

//[7] User Groups
$lang['go_user_groups'] = "User Groups";
$lang['go_ug_entry_del'] = "USER GROUP ENTRY";
$lang['go_type'] = "Type"; 
$lang['go_forced_timeclock'] = "Forced Timeclock"; 
$lang['go_add_new_user_group'] = "Add New User Group";

//Add New User Group
$lang['go_user_group_wizard'] = "User Group Wizard"; 
$lang['add_new_user_group'] = "Add New User Group";
$lang['go_group_name'] = "Group Name"; 
$lang['go_group_template'] = "Group Template"; 
$lang['go_user_groups_s'] = "user groups";
$lang['go_ug_banner'] = "User Groups";
$lang['go_to_1'] = "Go to First Page";
$lang['go_to_prev_p'] = "Go to Previous Page";
$lang['go_to_page'] = "Go to Page";
$lang['go_to_next'] = "Go to Next Page";
$lang['go_to_last'] = "Go to Last Page";
$lang['go_to_view_all'] = "Go to View All Pages";
$lang['go_to_back_pag'] = "Back to Paginated View";
$lang['go_group_level'] = "Group Level"; 
$lang['go_todays_status'] = "Today's Status"; 
$lang['go_account_info'] = "Account Info"; 
$lang['go_agent_lead_status'] = "Agent Lead Status"; 
$lang['go_server_settings'] = "Server Settings"; 
$lang['go_go_analytics'] = "Go Analytics"; 
$lang['go_system_service'] = "System Service"; 
$lang['go_cluster_status'] = "Cluster Status"; 
$lang['go_create'] = "Create"; 
$lang['go_read'] = "Read"; 
$lang['go_update'] = "Update";
$lang['go_campaign'] = "Campaign"; 
$lang['go_list'] = "List"; 
$lang['go_custom_fields'] = "Custom Fields"; 
$lang['go_load_leads'] = "Load Leads"; 
$lang['go_script'] = "Script"; 
$lang['go_reports_analytics'] = "Reports & Analytics"; 
$lang['go_agent_time_detail'] = "Agent Time Detail"; 
$lang['go_agent_performance_detail'] = "Agent Performance Detail"; 
$lang['go_dial_status_summary'] = "Dial Status Summary"; 
$lang['go_sales_per_agent'] = "Sales Per Agent"; 
$lang['go_sales_tracker'] = "Sales Tracker"; 
$lang['go_inbound_call_report'] = "Inbound Call Report";
$lang['go_export_call_report'] = "Export Call Report"; 
$lang['go_adv_script'] = "Advance Script"; 
$lang['go_recording'] = "Recording"; 
$lang['go_allowed_recording_view'] = "Allowed Recording View"; 
$lang['go_support'] = "Support"; 
$lang['go_allowed_support'] = "Allowed Support"; 
$lang['go_multi_tenant'] = "Multi-Tenant"; 
$lang['go_ug_navailable'] = "User Group Not Available.";
$lang['go_admin_logs'] = "Admin Logs"; 
$lang['go_more'] = "more";
$lang['go_call_times'] = "Call Times"; 
$lang['go_modify_ct'] = "MODIFY CALLTIME";
$lang['go_modify_sct'] = "MODIFY STATE CALLTIME";
$lang['go_del_sct'] = "DELETE STATE CALLTIME";
$lang['go_del_con_gad'] ="Are you sure you want to delete your GoAutoDial - JustGoVoIP account? This will delete both the carrier entry and your JustGoVoIP";
$lang['go_del_con_irreversible'] = "ARE YOU ABSOLUTELY WANT TO DELETE YOUR ACCOUNT? THIS PROCESS IS IRREVERSIBLE";
$lang['go_carrier_del'] = "CARRIER ENTRY DELETED";
$lang['go_carrier_banner'] = "Carriers";
$lang['go_del_ct'] = "DELETE CALLTIME";
$lang['go_call_time'] = "Call Time"; 
$lang['go_been_del'] = "has been deleted";
$lang['go_phones_s'] = "Phones"; 
$lang['go_pls_sel_ext'] = "Please select an Extension.";
$lang['go_del_con_sel_ext'] = "Are you sure you want to delete the selected Extension";
$lang['go_phone_banner'] = "Phones"; 
$lang['go_phone_entry_del'] = "PHONE ENTRY";
$lang['go_frm_list'] = "from the list";
$lang['go_phones'] = "phones"; 
$lang['go_voicemails'] = "Voicemails"; 
$lang['go_voicemails_s'] = "voicemails"; 


//Modify (advance settings)
$lang['go_force_timeclock_login'] = "Force Timeclock Login"; 
$lang['go_shift_enforcement'] = "Shift Enforcement"; 
$lang['go_all_campaigns'] = "ALL-CAMPAIGNS - USERS CAN VIEW ANY CAMPAIGN"; 
$lang['go_group_shifts']  = "Group Shifts";

//
$lang['go_modify_user_group'] = "Modify User Group"; 
$lang['no_record_found'] = "No Record(s) Found";
$lang['go_modify_ug'] = "MODIFY USER GROUP"; 
$lang['go_del_ug'] = "DELETE USER GROUP"; 
$lang['go_allowed_campaign'] = "Allowed Campaign"; 
$lang['go_agent_status_viewable_groups'] = "Agent Status Viewable Groups"; 
$lang['go_all_groups'] = "All Groups"; 
$lang['all_ug_system'] = "- All user groups in the system"; 
$lang['go_campaign_agent'] = "CAMPAIGN-AGENTS"; 
$lang['go_all_ulisc'] = "- All users logged into the same campaign"; 
$lang['go_not_logged_agents'] = "NOT-LOGGED-IN-AGENTS"; 
$lang['go_users_not_logged'] = " - All users in the system, even not logged-in"; 
$lang['go_agent_status_view_time'] = "Agent Status View Time"; 
$lang['go_agent_call_log_view'] = "Agent Call Log View"; 
$lang['go_agent_allow_consultative_xfer'] = "Agent Allow Consultative Xfer";
$lang['go_agent_allow_dial_override_xfer'] = "Agent Allow Dial Override Xfer";
$lang['go_agent_allow_voicemail_msg_xfer'] = "Agent Allow Voicemail Message Xfer";
$lang['go_agent_allow_blind_xfer'] = "Agent Allow Blind Xfer";
$lang['go_agent_allow_dial_with_customer'] = "Agent Allow Dial With Customer Xfer:";
$lang['go_agent_allow_park_customer_dial_xfer'] = "Agent Allow Park Customer Dial Xfer";
$lang['go_agent_fullscreen'] = "Agent Fullscreen"; 
$lang['go_allowed_reports'] = "Allowed Reports"; 
$lang['go_allowed_user_groups'] = "Allowed User Groups"; 
$lang['go_all_groups'] = "ALL GROUPS"; 
$lang['go_all_ug_system'] = "- All user groups in the system"; 
$lang['go_allowed_call_times'] = "Allowed Call Times";
$lang['go_all_calltimes'] = "ALL-CALLTIMES";
$lang['go_all_call_times_system'] = " - All call times in the system";


//[8] Voicemails
$lang['go_vm_not_available'] = "Voicemail Not Available.";
$lang['go_vm_banner'] = "Voicemails";
$lang['go_pls_select_vm'] = "Please select a Voicemail.";
$lang['go_voicemail_id'] = "Voicemail ID"; 
$lang['go_name'] = "Name"; 
$lang['go_activate_selected'] = "Activate Selected"; 
$lang['go_activate'] = "Activate"; 
$lang['go_deactivate'] = "Deactivate"; 
$lang['go_deactivate_selected'] = "Deactivate Selected"; 

//Add New Voicemail
$lang['go_voicemail_wizard'] = "Voicemail Wizard"; 
$lang['go_add_new_voicemail'] = "Add New Voicemail"; 
$lang['go_pass'] = "Password"; 
$lang['go_active_no'] = "NO"; 
$lang['go_active_yes'] = "YES"; 
$lang['go_email'] = "Email"; 
$lang['go_min_2_numbers'] = "Minimum of 2 numbers"; 
$lang['go_voicemails_boxes'] = "Voicemails Boxes"; 

//Modify voicemail
$lang['go_del_voicemail_after_email'] = "Delete Voicemail After Email"; 
$lang['go_new_msg'] = "New Messages"; 
$lang['go_old_msg'] = "Old Messages"; 
$lang['go_save_settings'] = "Save Settings"; 
$lang['go_modify_voicemail_box'] = "Modify Voicemail box"; 
$lang['go_modify_voicemail'] = "MODIFY";
$lang['go_del_con'] = "Are you sure you want to delete?";

//Delete Voicemail
$lang['go_del_voicemail'] = "Eliminar correo de voz";
$lang['go_deld_voicemail'] = "Deleted Voicemail(s):";
$lang['go_vm_entry_del'] = "VOICEMAIL ENTRY";
$lang['go_entry_3_char_search'] = "Please enter at least 3 characters to search";
$lang['go_del_voicemail_selected'] = "Are you sure you want to delete the selected Voicemail";
$lang['go_del_voicemails'] = "Delete Voicemails";
$lang['go_cancel'] = "Cancel";
$lang['go_all_caps'] = "ALL";


$lang['go_usergroups_reports'] = "ALL REPORTS, NONE, Real-Time Main Report, Real-Time Campaign Summary , Inbound Report, Inbound Service Level Report, Inbound Summary Hourly Report, Inbound Daily Report, Inbound DID Report, Inbound IVR Report, Outbound Calling Report, Outbound Summary Interval Report, Outbound IVR Report, Fronter - Closer Report, Lists Campaign Statuses Report, Campaign Status List Report, Export Calls Report , Export Leads Report , Agent Time Detail, Agent Status Detail, Agent Performance Detail, Team Performance Detail, Single Agent Daily , User Timeclock Report, User Group Timeclock Status Report, User Timeclock Detail Report , Server Performance Report, Administration Change Log, List Update Stats, User Stats, User Time Sheet, Download List, Dialer Inventory Report, Custom Reports Links, CallCard Search, Maximum System Stats, Maximum Stats Detail, Search Leads Logs";
$lang['go_off'] = "OFF";
$lang['go_pls_sel_one_more_camp'] = "Please select one or more campaign from the list.";
$lang['go_success_update'] = "Success: Update successful!";
$lang['go_defaultLanguage'] = "Default Language";
$lang['go_of'] = "of";
$lang['go_to'] = "to";
$lang['go_tenants_banner'] = "Multi-Tenant";
$lang['go_telephony'] = "Telephony";
$lang['go_admin_settings'] = "Admin Settings";
$lang['go_lists_and_call_recordings'] = "Lists and Call Recordings";
$lang['go_call_reports'] = "Call Reports";
$lang['go_scripts'] = "Scripts"; 
$lang['go_reports'] = "Reports";
$lang['go_analytics'] = "Analytics"; 
$lang['go_hello'] = "Hello";
$lang['go_logout'] = "Logout";
$lang['go_edit_profile'] = "Edit your profile";
$lang['go_powered_by_GAD'] = "Powered by GoAutoDial";
$lang['go_go_back'] = "Go Back";
$lang['go_show_on_screen'] = "Show on screen";
$lang['go_todays_status'] = "Today's Status";
$lang['go_server_statistics'] = "Server Statistics";
$lang['go_configure'] = "Configure";
$lang['go_system_services'] = "System Services";
$lang['go_cluster_status'] = "Cluster Status";
$lang['go_justgovoip'] = "JustGOVoIP";
$lang['go_agents_status'] = "Agent's Status";
$lang['go_plugins'] = "Plugins";
$lang['go_agents_phones'] = "Agents & Phones";
$lang['go_analytics'] = "GO Analytics";
$lang['go_gad_project_page'] = "GoAutoDial Project Page";
$lang['go_gad_news'] = "GOAutoDial News";
$lang['go_gad_community_forum'] = "GOAutoDial Community & Forum";
$lang['go_no_columns'] = "Number of Columns";
$lang['go_intro_help'] = "Introduction Help";
$lang['go_notification'] = "Notification";
$lang['go_settings'] = "Settings";




//Additional

$lang['go_description'] = "Description";
$lang['go_color'] = "Color";
$lang['go_web_form'] = "Web Form";
$lang['go_next_agent_call'] = "Next Agent Call";
$lang['go_oldest_call_finish'] = "oldest_call_finish";
$lang['go_overall_user_level'] = "overall_user_level";
$lang['go_inbound_group_rank'] = "inbound_group_rank";
$lang['go_campaign_rank'] = "campaign_rank";
$lang['go_fewest_calls'] = "fewest_calls";
$lang['go_fewest_calls_campaign'] = "fewest_calls_campaign";
$lang['go_longest_wait_time'] = "longest_wait_time";
$lang['go_ring_all'] = "ring_all";
$lang['go_queue_priority'] = "Queue Priority";
$lang['go_fronter_display'] = "Fronter Display";
$lang['go_script'] = "Script";
$lang['go_on_hook_ring_time'] = "On-Hook Ring Time";
$lang['go_get_call_launch'] = "Get Call Launch";
$lang['go_webform'] = "WEBFORM";
$lang['go_transfer_conf_dtmf1'] = "Transfer-Conf DTMF 1";
$lang['go_transfer_conf_number1'] = "Transfer-Conf Number 1";
$lang['go_transfer_conf_dtmf2'] = "Transfer-Conf DTMF 2";
$lang['go_transfer_conf_number2'] = "Transfer-Conf Number 2";
$lang['go_transfer_conf_number3'] = "Transfer-Conf Number 3";
$lang['go_transfer_conf_number4'] = "Transfer-Conf Number 4";
$lang['go_transfer_conf_number5'] = "Transfer-Conf Number 5";
$lang['go_tmer_action'] = "Timer Action";
$lang['go_dial'] = "D1_DIAL";
$lang['go_dial2'] = "D2_DIAL";
$lang['go_dial3'] = "D3_DIAL";
$lang['go_hangup'] = "HANGUP";
$lang['go_call_menu'] = "CALLMENU";
$lang['go_exten'] = "EXTENSION";
$lang['go_ingroup'] = "IN_GROUP";
$lang['go_timer_action_msg'] = "Timer Action Message";
$lang['go_timer_action_seconds'] = "Timer Action Seconds";
$lang['go_timer_action_destination'] = "Timer Action Destination";
$lang['go_drop_call_seconds'] = "Drop Call Seconds";
$lang['go_drop_action'] = "Drop Action";
$lang['go_drop_exten'] = "Drop Exten";
$lang['go_vm'] = "Voicemail";
$lang['go_drop_transfer_group'] = "Drop Transfer Group";
$lang['go_call_time'] = "Call Time";
$lang['go_after_hours_action'] = "After Hours Action";
$lang['go_msg'] = "MESSAGE";
$lang['go_voicemail'] = "VOICEMAIL";
$lang['go_after_hours_msg_filename'] = "After Hours Message Filename";
$lang['go_after_hours_exten'] = "After Hours Extension";
$lang['go_after_hours_vm'] = "After Hours Voicemail";
$lang['go_after_hours_transfer_group'] = "After Hours Transfer Group";
$lang['go_no_agents_no_queueing'] = "No Agents No Queueing";
$lang['go_no_paused'] = "NO_PAUSED";
$lang['go_no_agent_no_queue_action'] = "No Agent No Queue Action";
$lang['go_welcome_me'] = "Welcome Message Filename";
$lang['go_play_welcome_msg'] = "Play Welcome Message";
$lang['go_never'] = "NEVER";
$lang['go_if_wait_only'] = "IF_WAIT_ONLY";
$lang['go_yes_unless_nodelay'] = "YES_UNLESS_NODELAY";
$lang['go_moh_context'] = "Music On Hold Context";
$lang['go_moh_chooser'] = "Moh Chooser";
$lang['go_on_hold_prompt_filename'] = "On Hold Prompt Filename";
$lang['go_on_hold_prompt_interval'] = "On Hold Prompt Interval";
$lang['go_on_hold_prompt_no_block'] = "On Hold Prompt No Block";
$lang['go_on_hold_prompt_seconds'] = "On Hold Prompt Seconds";
$lang['go_play_place_line'] = "Play Place in Line";
$lang['go_play_estimated_hold_time'] = "Play Estimated Hold Time";
$lang['go_calculate_estimated_hold_seconds'] = "Calculate Estimated Hold Seconds";
$lang['go_estimated_hold_time_min_filename'] = "Estimated Hold Time Minimum Filename";
$lang['go_estimated_hold_time_min_prompt_block'] = "Estimated Hold Time Minimum Prompt No Block";
$lang['go_estimated_hold_time_min_prompt_seconds'] = "Estimated Hold Time Minimum Prompt Seconds";
$lang['go_wait_time_option'] = "Wait Time Option";
$lang['go_press_stay'] = "PRESS_STAY";
$lang['go_press_vmail'] = "PRESS_VMAIL";
$lang['go_press_exten'] = "PRESS_EXTEN";
$lang['go_press_call_menu'] = "PRESS_CALLMENU";
$lang['go_press_cid_callback'] = "PRESS_CID_CALLBACK";
$lang['go_press_ingroup'] = "PRESS_INGROUP";
$lang['go_wait_time_second_option'] = "Wait Time Second Option";
$lang['go_press_cid_callback'] = "PRESS_CID_CALLBACK";
$lang['go_wait_time_third_option'] = "Wait Time Third Option";
$lang['go_wait_time_option_seconds'] = "Wait Time Option Seconds";
$lang['go_wait_time_option_exten'] = "Wait Time Option Extension";
$lang['go_wait_time_option_call_menu'] = "Wait Time Option Callmenu";
$lang['go_wait_time_option_voicemail'] = "Wait Time Option Voicemail";
$lang['go_wait_time_option_transfer_in_group'] = "Wait Time Option Transfer In-Group";
$lang['go_wait_time_option_press_filename'] = "Wait Time Option Press Filename";
$lang['go_wait_time_option_press_no_block'] = "Wait Time Option Press No Block";
$lang['go_wait_time_option_press_filename_seconds'] = "Wait Time Option Press Filename Seconds";
$lang['go_wait_time_option_after_press_filename'] = "Wait Time Option After Press Filename";
$lang['go_wait_time_option_callback_list_id'] = "Wait Time Option Callback List ID";
$lang['go_wait_hold_option_priority'] = "Wait Hold Option Priority";
$lang['go_both'] = "BOTH";
$lang['go_wait'] = "WAIT";
$lang['go_estimated_hold_time_option'] = "Estimated Hold Time Option";
$lang['go_hold_time_third_option'] = "Hold Time Third Option";
$lang['go_hold_time_option_seconds'] = "Hold Time Option Seconds";
$lang['go_hold_time_option_minimum'] = "Hold Time Option Minimum";
$lang['go_hold_time_option_extension'] = "Hold Time Option Extension";
$lang['go_hold_time_option_callmenu'] = "Hold Time Option Callmenu";
$lang['go_hold_time_option_voicemail'] = "Hold Time Option Voicemail";
$lang['go_hold_time_option_transfer_in_group'] = "Hold Time Option Transfer In-Group";
$lang['go_hold_time_option_press_filename'] = "Hold Time Option Press Filename";
$lang['go_hold_time_option_press_no_block'] = "Hold Time Option Press No Block";
$lang['go_hold_time_option_press_filename_seconds'] = "Hold Time Option Press Filename Seconds";
$lang['go_hold_time_option_after_press_filename'] = "Hold Time Option After Press Filename";
$lang['go_hold_time_option_callback_list_id'] = "Hold Time Option Callback List ID";
$lang['go_agent_alert_filename'] = "Agent Alert Filename";
$lang['go_agent_alert_delay'] = "Agent Alert Delay";
$lang['go_default_transfer_group'] = "Default Transfer Group";
$lang['go_agentdirect_single_agent_direct_queue'] = "AGENTDIRECT - Single Agent Direct Queue";
$lang['go_default_group_alias'] = "Default Group Alias";
$lang['go_hold_recall_transfer_in_group'] = "Hold Recall Transfer In-Group";
$lang['go_no_delay_call_route'] = "No Delay Call Route";
$lang['go_in_group_recording_override'] = "In-Group Recording Override";
$lang['go_disabled'] = "go_ondemand";
$lang['go_allcalls'] = "ALLCALLS";
$lang['go_allforce'] = "ALLFORCE";
$lang['go_in_group_recording_filename'] = "In-Group Recording Filename";
$lang['go_stats_percent_of_calls_answered_within_seconds'] = "Stats Percent of Calls Answered Within X seconds 1";
$lang['go_stats_percent_of_calls_answered_within_seconds'] = "Stats Percent of Calls Answered Within X seconds 2";
$lang['go_start_call_url'] = "Start Call URL";
$lang['go_dispo_call_url'] = "Dispo Call URL";
$lang['go_add_lead_url'] = "Add Lead URL";
$lang['go_extension_append_cid'] = "Extension Append CID";
$lang['go_uniqueid_status_display'] = "Uniqueid Status Display";
$lang['go_enabled'] = "ENABLED";
$lang['go_enabled_prefix'] = "ENABLED_PREFIX";
$lang['go_enabled_preserve'] = "ENABLED_PRESERVE";
$lang['go_uniqueid_status_prefix'] = "Uniqueid Status Prefix";

$lang['go_error_invalid_captcha'] = "Error: Invalid Captcha";
$lang['go_account_creation_success'] = "Success: Account creation successful. Please check your email for instructions on how to login to your account.";
$lang['go_error_cant_create'] = "Error: Can't create sippy user";
$lang['go_only_GAD_allowed'] = "Error: Only one GoAutoDial-JustGoVoip is allowed per server ip";
$lang['go_success_copy_carrier'] = "Success: Copy Carrier complete";
$lang['go_failed_copy'] = "Error: Failed to copy the selected carrier";
$lang['go_empty_post_data'] = "Error: Empty post data";

################## login ########################
$lang['go_forgot_pass'] = "Forgot password";
$lang['go_mysql_connect_error'] = "MySQL connect ERROR:";
$lang['go_cloud_call_center'] = "Cloud Call Center";
$lang['go_pls_enter_username'] = "Please enter your username.";
$lang['go_pls_enter_pass'] = "Please enter you password.";
$lang['go_close'] = "CLOSE";
$lang['go_agent_login'] = "AGENT LOGIN";
$lang['go_account_number'] = "Account Number / Web Login:";
$lang['go_send_email'] = "Send to email";
$lang['go_the_account_does_not_exist'] = "The Account number does not exist.";
$lang['go_from'] = "From";
$lang['go_justgocloud_admin_pass_ret'] = "JustGoCloud Admin Password retrival.";
$lang['go_pls_keep_this_email'] = "Please keep this email for your records. Your account information is as follows";
$lang['go_account_number'] = "Account Number";
$lang['go_justgocloud_admin_url'] = "JustGoCloud Admin URL";
$lang['go_web_admin_username'] = "Web Admin Username";
$lang['go_admin_pass'] = "Web Admin Password";
$lang['go_pass_sent'] = "Passwords sent to";
$lang['go_your_username'] = "Your username";
$lang['go_username'] = "Username";
$lang['go_pass'] = "Your password";
$lang['go_login'] = "LOGIN";
$lang['go_create_account'] = "Create an account";
$lang['go_proceeding_agree'] = "By proceeding, I agree to the ";
$lang['go_terms_service'] = "Terms of Service </a> and";
$lang['go_privacy_notice'] = "Privacy Notice</a> of $VARCLOUDCOMPANY";
$lang['go_redirecting'] = "Redirecting...";
$lang['go_conf_file_not_found'] = "ERROR: 'goautodial.conf' file not found.";
$lang['go_ast_file_not_found'] = "ERROR: 'astguiclient.conf' file not found.";

##################3 IVR ###############################

$lang['go_menu_id_navailable'] = "Menu id not available.";
$lang['go_menu_id_should_nempty'] = "Menu id should not be empty.";
$lang['go_menu_id_navailable'] = "Menu id not available.";
$lang['go_menu_id_should_nempty'] = "Menu id should not be empty.";
$lang['go_min_4_digits'] = "Minimum of 4 digits.";
$lang['go_call_menu_wizard'] = "Call Menu Wizard";
$lang['go_create_new_call_menu'] = "Create New Call Menu";
$lang['go_menu_id'] = "Menu ID";
$lang['go_menu_name'] = "Menu Name";
$lang['go_menu_greeting'] = "Menu Greeting";
$lang['go_menu_timeout'] = "Menu Timeout";
$lang['go_menu_timeout_greeting'] = "Menu timeout greeting";
$lang['go_menu_invalid_greeting'] = "Menu invalid greeting";
$lang['go_menu_repeat'] = "Menu Repeat";
$lang['go_menu_time_check'] = "Menu Time Check";
$lang['go_call_time'] = "Call Time";
$lang['go_track_calls'] = "Track calls in";
$lang['go_real_time_report'] = "Real-time Report";
$lang['go_tracking_group'] = "Tracking group";
$lang['go_user_group'] = "User Group";
$lang['go_default_call_menu_entry'] = "Default Call Menu Entry";
$lang['go_description'] = "Description:";
$lang['go_audio_file'] = "Audio file";
$lang['go_add_new_call_menu_options'] = "Add New Call Menu Options";
$lang['go_route'] = "Route";
$lang['go_descriptions'] = "Descriptions";
$lang['go_prompt'] = "Prompt";
$lang['go_timeout'] = "Timeout";
$lang['go_modify_ivr'] = "Modify IVR";
$lang['go_delete_ivr'] = "Delete IVR";
$lang['go_ivr_menus'] = "Interactive Voice Response (IVR) Menus";
$lang['go_option_sel_already_use'] = "The option you selected is already in use.";
$lang['go_modify_callmenu'] = "MODIFY CALLMENU";
$lang['go_menu_prompt'] = "Menu Prompt";
$lang['go_menu_timeout_prompt'] = "Menu Timeout Prompt";
$lang['go_menu_invalid_prompt'] = "Menu Invalid Prompt";
$lang['go_track_calls_real_time_report'] = "Track Calls in Real-Time Report";
$lang['go_tracking_group'] = "Tracking Group";
$lang['go_custom_dialplan_entry'] = "Custom Dialplan Entry";
$lang['go_call_menu_options_'] = "Call Menu Options";
$lang['go_in_groups'] = "In-Groups";
$lang['go_del_call_menu'] = "Are you sure you want to delete this Call Menu / IVR"; 
$lang['go_permission_del_call_menu'] = "Error: You do not have permission to delete Call Menu / IVR."; 

//NEW ADDED -> go_campaign_ce
$lang['go_ERROR'] = "ERROR";
$lang['go_Thecampaignyouwanttodeletestillhaslistidsthathaveleadsloaded'] = "The campaign(s) you want to delete still has list ids that have leads loaded";
$lang['go_PleasetransfertheexistingliststoanothercampaignordeleteitusingourListpage'] = "Please transfer the existing list(s) to another campaign or delete it using our List page";

$lang['go_UserTooltip'] = "Users – allow for the creation of agents and admin users.";
$lang['go_ScriptTooltip2'] = "A script allows admin to enable a window to popup on the Agent webpage during a live call when the “script” button is clicked. Each item on the “ Script Text “ is a syntax that gets pasted on the text box whenever the “ insert ” button is clicked and allows the system to call specific information on uploaded lead files or system information like agent names and display it on a window when the agent presses the script button on the agent user interface (UI). ";
$lang['go_AnsweringMachineDetectionTooltip'] = "<b>Answering Machine Detection</b> \n
Off – All answered calls will automatically be routed to an agent. \n
On – System will attempt to distinguish if call is picked up by an answering machine. Detection rate is at best 70%";
$lang['go_ActiveStatusDialTooltip'] = "<b>Dial Status</b> – Specifies the dispositions on the active lead file(s) on the campaign that the system will automatically dial. Any dispositions not included on the dial status will not be dialed.";
$lang['go_Customer3WayHangupActionTooltip'] = "<b>Customer 3-Way Hangup Action</b> – If set to dispo, this will take the agent webpage to the disposition screen when the system detects that the customer has hungup on the 3-way call.";
$lang['go_ActionColumnTooltip'] = "<b>Action Column</b> -- provides additional admin options such as edit, get more info, delete and and download the list.";
$lang['goUsers_tooltip_level'] = "Level - defines the permission granted to a user. Current settings are; Level 1 - 6 – Agent Level. Can only access agent login. Cannot modify account settings. Limited privilege. Level 7-8 – Admin Level. Can access both agent login and admin dashboard. Can make changes to account settings.";
$lang['goUsers_agentandphoneTooltip'] =  "<b> Agent ID </b> and <b> Phone login </b> are used to register your softphone and login to your agent webpage. They cannot be changed.";
$lang['go_widgetDateTooltip'] = "<b> Date Range </b> – determines the time length of data to be generated";
$lang['goUsers_forceLogoutTooltip'] = '<b> Force Logout </b>  – will terminate the browser session of the agent. This is useful when the agent is getting the "improper logout" error.';
$lang['go_inboundthistimeTooltip'] = "<b> Outbound Calls this time </b> – displays all relevant information regarding outbound calls on the selected date range.";

$lang['go_group_not_added'] = "GROUP NOT ADDED - Please go back and look at the data you entered";
$lang['go_did_not_added'] = "DID NOT ADDED - DID already exist.";
$lang['go_error_unknown_service'] = "Error: Unknown service";
$lang['go_error_multi_gad_carrier'] = "Error: Multiple GoAutoDial carrier entries are not allowed";
$lang['go_error_registered_user'] = "Error : Are you a registered user?";
$lang['go_error_pls_contact_support'] = "Error: Something went wrong please contact your support";
$lang['go_pls_use_freshdesk'] = "Admin account? Please use our freshdesk";
$lang['go_new_ticket_submited'] = "New ticket submitted";
$lang['go_added_new_note'] = "Added new note successful";
$lang['go_error_no_script_process'] = "Error: No script to process!";
$lang['go_error_empty_scriptid'] = "Error: Empty scriptid";
$lang['go_success_new_lime_survey'] = "Success: New lime survey created";
$lang['go_error_saving_data_support'] = "Error on saving data contact your support";
$lang['go_error_no_data_process'] = "Error: no data to process";
$lang['go_error_passing_not_obj_var'] = "Error: passing not an object variable";
$lang['go_error_passing_empty_data'] = "Error: Passing empty data contact your support";
$lang['go_dnc_numbers'] = "DNC numbers";
?>