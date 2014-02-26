<?php
// userauth version 0.9.0 English
// initial contribution by Thomas Traub

// views/usergroups - login
$lang['ua_login']               = 'Login';
$lang['ua_logout']              = 'Logout';
$lang['ua_remember_me']         = 'remember me';
$lang['ua_user']                = 'User';
$lang['ua_administrator_login']                           = 'ADMINISTRATOR LOGIN';
$lang['ua_banner_text']         = 'Empowering ang Susunod na Henerasyon ng Contact Centers';
$lang['ua_empowering']          = 'Empowering';
$lang['ua_thenextgen']          = 'Ang Susunod na Henerasyon';
$lang['ua_contactcenters']      = 'ng Contact Centers';

$lang['ua_welcome']           =       'Mabuhay!';
$lang['ua_getstarted']        =       'Magsimula!';
$lang['ua_needhelp']          =       'Tulong?';
$lang['ua_welcomephrase']     =       'Ang GoAutoDial ay isang enterprise grade open source call center system. Ito ay scalable sa hundreds of seats at kayang gumamit ng VoIP, ISDN o analog trunks. Puntahan nyo kami sa @ ';
$lang['ua_getstartedphrase']  =       'Ang GoAutoDial Getting Started Guide ay makakatulong para masimulan ang inyong GoAutoDial experience. Andito ang gabay sa installation, SIP trunk configuration, leads loading, agent login at pano makagawa ng inyong unang tawag.';
$lang['ua_needhelpphrase']    =       'Andito kami para sa inyo. We provide top rate service at affordable rates. At ang pagtangkilik ng aming mga serbisyo ay makakatulong ng malaki sa pagsulong ng proyekto.';
$lang['ua_licence']           =       'GoAutoDial comes with no guarantees or warranties of any sorts, either written or implied. The Distribution is released as GPL. Individual packages in the distribution come with their own licences.';

$lang['ua_AgentLogin']   =     'Agent Login';
$lang['ua_AdminLogin']   =     'Admin Login';
$lang['ua_VTigerCRM']   =     'VTigerCRM';
$lang['ua_Community']   =     'Komunidad';
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

?>