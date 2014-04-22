<?php
########################################################################################################
####  Name:             	go_admin_asterisk.php                                               ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  Modified by:      	Franco E. Hora                                                      ####
####                    	Jericho James Milo                                                  ####
####                            Chris Lomuntad                                                      ####
####  License:          	AGPLv2                                                              ####
########################################################################################################


$this->lang->load('userauth', $this->session->userdata('ua_language'));

$base = base_url();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>GoAutoDial - Administrator's Dashboard</title>
<script type="text/javascript">
//<![CDATA[
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var userSettings = {
		'url': '/',
		'uid': '1',
		'time':'1309277858'
	},
	ajaxurl = 'http://e3solutionsincph.com/wp-admin/admin-ajax.php',
	pagenow = 'dashboard',
	typenow = '',
	adminpage = 'index-php',
	thousandsSeparator = ',',
	decimalPoint = '.',
	isRtl = 0;
//]]>
</script>
<link rel="stylesheet" href="<? echo $base; ?>css/go_load_styles.php" type="text/css" media="all">
<link rel="stylesheet" id="thickbox-css" href="<? echo $base; ?>css/go_thickbox.css" type="text/css" media="all">
<link rel="stylesheet" id="colors-css" href="<? echo $base; ?>css/go_colors_fresh.php" type="text/css" media="all">
<!--[if lte IE 7]>
<link rel='stylesheet' id='ie-css'  href='<? echo $base; ?>css/go_ie.css?ver=20100610' type='text/css' media='all' />
<![endif]-->
<script type="text/javascript">
/* <![CDATA[ */
var quicktagsL10n = {
	quickLinks: "(Quick Links)",
	wordLookup: "Enter a word to look up:",
	dictionaryLookup: "Dictionary lookup",
	lookup: "lookup",
	closeAllOpenTags: "Close all open tags",
	closeTags: "close tags",
	enterURL: "Enter the URL",
	enterImageURL: "Enter the URL of the image",
	enterImageDescription: "Enter a description of the image"
};
try{convertEntities(quicktagsL10n);}catch(e){};
/* ]]> */
</script>
<script type="text/javascript" src="<? echo $base; ?>js/go_hload_scripts.js"></script>
</head>
<body style="cursor: auto;" class="wp-admin js  index-php">
<script type="text/javascript">
//<![CDATA[
(function(){
var c = document.body.className;
c = c.replace(/no-js/, 'js');
document.body.className = c;
})();
//]]>
</script>

<div id="wpwrap">
<div id="wpcontent">
<div id="wphead">

<img id="header-logo" src="img/blank.gif" alt="" height="38" width="134">
<h1 id="site-heading" class="long-title">
	<a href="http://e3solutionsincph.com/" title="Visit Site">
		<span id="site-title">Administrator's Dashboard</span>
	</a>
</h1>


<div id="wphead-info">
<div id="user_info">
<p>Hello, <a href="" title="Edit your profile">admin</a> | <a href="../go_site/logout" title="Log Out">Log Out</a></p>
</div>

<div id="favorite-actions"><div id="favorite-first"><a href="">Quick Links</a></div><div id="favorite-toggle"><br></div>
<div class="slideUp" style="width: 126px;" id="favorite-inside">
<div class="favorite-action"><a href="">Vicidial Admin</a></div>
<div class="favorite-action"><a href="">vTigerCRM</a></div>
<div class="favorite-action"><a href="">phpMyAdmin</a></div>
<div class="favorite-action"><a href="">Go Admin</a></div>
</div></div>
</div>
</div>

<div id="wpbody">

<ul id="adminmenu">


	<li class="wp-first-item wp-has-submenu wp-has-current-submenu wp-menu-open menu-top menu-top-first menu-icon-dashboard menu-top-last" id="menu-dashboard">
	<div class="wp-menu-image"><a href="http://e3solutionsincph.com/wp-admin/index.php"><br></a></div><div class="wp-menu-toggle"><br></div><a href="http://e3solutionsincph.com/wp-admin/index.php" class="wp-first-item wp-has-submenu wp-has-current-submenu wp-menu-open menu-top menu-top-first menu-icon-dashboard menu-top-last" tabindex="1">Dashboard</a>
	<div class="wp-submenu"><div class="wp-submenu-head">Dashboard</div>
		<ul>
		<li class="wp-first-item current"><a href="http://e3solutionsincph.com/wp-admin/index.php" class="wp-first-item current" tabindex="1">Dashboard</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/update-core.php" tabindex="1">Updates <span class="update-plugins count-2" title="1 GoAutoDial Update, 1 Plugin Update"><span class="update-count">2</span></span></a>
		</li>
		</ul>
	</div>
	</li>
	
	<li class="wp-menu-separator"><a class="separator" href="http://e3solutionsincph.com/wp-admin/?unfoldmenu=1"><br></a></li>
	
	<li class="wp-has-submenu open-if-no-js menu-top menu-icon-post menu-top-first" id="menu-posts">
	<div class="wp-menu-image"><a href="http://e3solutionsincph.com/wp-admin/edit.php"><br></a></div><div class="wp-menu-toggle"><br></div>
	<a href="http://e3solutionsincph.com/wp-admin/edit.php" class="wp-has-submenu open-if-no-js menu-top menu-icon-post menu-top-first" tabindex="1">GO Admin</a>
	<div class="wp-submenu"><div class="wp-submenu-head">GO Admin</div>
		<ul>
		<li class="wp-first-item"><a href="../go_site/go_iadmin" class="wp-first-item" tabindex="1">Asterisk</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/edit-tags.php?taxonomy=category" tabindex="1">Apache</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/edit-tags.php?taxonomy=post_tag" tabindex="1">Mysql</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/edit-tags.php?taxonomy=post_tag" tabindex="1">System</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/edit-tags.php?taxonomy=post_tag" tabindex="1">Network</a></li>
		</ul>
	</div>
	</li>
	
	<li class="wp-has-submenu menu-top menu-icon-media" id="menu-media">
	<div class="wp-menu-image"><a href="http://e3solutionsincph.com/wp-admin/upload.php"><br></a></div><div class="wp-menu-toggle"><br></div>
	<a href="http://e3solutionsincph.com/wp-admin/upload.php" class="wp-has-submenu menu-top menu-icon-media" tabindex="1">Vicidial Admin</a>
	<div class="wp-submenu"><div class="wp-submenu-head">Vicidial Admin</div>
		<ul>
		<li class="wp-first-item"><a href="http://e3solutionsincph.com/wp-admin/upload.php" class="wp-first-item" tabindex="1">Users</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/media-new.php" tabindex="1">Campaigns</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/media-new.php" tabindex="1">Lists</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/media-new.php" tabindex="1">Scripts</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/media-new.php" tabindex="1">Groups</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/media-new.php" tabindex="1">Remote Agents</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/media-new.php" tabindex="1">Phones</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/media-new.php" tabindex="1">Servers</a></li>
		</ul>
	</div>
	</li>
	
	<li class="wp-has-submenu menu-top menu-icon-links" id="menu-links">
	<div class="wp-menu-image">
        <a href=""><br></a>
	</div>
	<div class="wp-menu-toggle"><br></div>
	<a href="http://e3solutionsincph.com/wp-admin/link-manager.php" class="wp-has-submenu menu-top menu-icon-links" tabindex="1">Links</a>
	<div class="wp-submenu"><div class="wp-submenu-head">Links</div>
		<ul>
		<li class="wp-first-item"><a href="http://e3solutionsincph.com/wp-admin/link-manager.php" class="wp-first-item" tabindex="1">Links</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/link-add.php" tabindex="1">Add New</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/edit-link-categories.php" tabindex="1">Link Categories</a></li>
		</ul>
	</div>
	</li>
	
	<li class="wp-has-submenu menu-top menu-icon-page" id="menu-pages">
	<div class="wp-menu-image"><a href="http://e3solutionsincph.com/wp-admin/edit.php?post_type=page"><br></a>
	</div><div class="wp-menu-toggle"><br></div>
	<a href="http://e3solutionsincph.com/wp-admin/edit.php?post_type=page" class="wp-has-submenu menu-top menu-icon-page" tabindex="1">Pages</a>
	<div class="wp-submenu"><div class="wp-submenu-head">Pages</div>
		<ul>
		<li class="wp-first-item"><a href="http://e3solutionsincph.com/wp-admin/edit.php?post_type=page" class="wp-first-item" tabindex="1">Pages</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/post-new.php?post_type=page" tabindex="1">Add New</a></li>
		</ul>
	</div>
	</li>
	
	<li class="menu-top menu-icon-comments menu-top-last" id="menu-comments">
	<div class="wp-menu-image"><a href="http://e3solutionsincph.com/wp-admin/edit-comments.php"><br></a></div>
	<div style="display: none;" class="wp-menu-toggle"><br></div>
	<a href="http://e3solutionsincph.com/wp-admin/edit-comments.php" class="menu-top menu-icon-comments menu-top-last" tabindex="1">Comments <span id="awaiting-mod" class="count-0"><span class="pending-count">0</span></span></a>
	</li>
	
	<li class="wp-menu-separator"><a class="separator" href="http://e3solutionsincph.com/wp-admin/?unfoldmenu=1"><br></a></li>
	
	<li class="wp-has-submenu menu-top menu-icon-appearance menu-top-first" id="menu-appearance">
	<div class="wp-menu-image"><a href="http://e3solutionsincph.com/wp-admin/themes.php"><br></a></div>
	<div class="wp-menu-toggle"><br></div>
	<a href="http://e3solutionsincph.com/wp-admin/themes.php" class="wp-has-submenu menu-top menu-icon-appearance menu-top-first" tabindex="1">Appearance</a>
	<div class="wp-submenu">
		<div class="wp-submenu-head">Appearance</div>
		<ul>
		<li class="wp-first-item"><a href="http://e3solutionsincph.com/wp-admin/themes.php" class="wp-first-item" tabindex="1">Themes</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/widgets.php" tabindex="1">Widgets</a></li><li><a href="http://e3solutionsincph.com/wp-admin/nav-menus.php" tabindex="1">Menus</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/themes.php?page=functions.php" tabindex="1">Pro Business Premium Options</a></li><li><a href="http://e3solutionsincph.com/wp-admin/theme-editor.php" tabindex="1">Editor</a></li>
		</ul>
        </div>
	</li>
	
	<li class="wp-has-submenu menu-top menu-icon-plugins" id="menu-plugins">
	<div class="wp-menu-image">
        <a href="http://e3solutionsincph.com/wp-admin/plugins.php"><br></a>
	</div>
	<div class="wp-menu-toggle"><br></div><a href="http://e3solutionsincph.com/wp-admin/plugins.php" class="wp-has-submenu menu-top menu-icon-plugins" tabindex="1">Plugins <span class="update-plugins count-1"><span class="plugin-count">1</span></span></a>
	<div class="wp-submenu"><div class="wp-submenu-head">Plugins <span class="update-plugins count-1"><span class="plugin-count">1</span></span></div>
		<ul>
		<li class="wp-first-item"><a href="http://e3solutionsincph.com/wp-admin/plugins.php" class="wp-first-item" tabindex="1">Plugins</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/plugin-install.php" tabindex="1">Add New</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/plugin-editor.php" tabindex="1">Editor</a></li>
		</ul>
	</div>
	</li>
	
	<li class="wp-has-submenu menu-top menu-icon-users" id="menu-users">
	<div class="wp-menu-image"><a href="http://e3solutionsincph.com/wp-admin/users.php"><br></a></div>
	<div class="wp-menu-toggle"><br></div>
	<a href="http://e3solutionsincph.com/wp-admin/users.php" class="wp-has-submenu menu-top menu-icon-users" tabindex="1">Users</a>
	<div class="wp-submenu"><div class="wp-submenu-head">Users</div>
		<ul>
		<li class="wp-first-item"><a href="http://e3solutionsincph.com/wp-admin/users.php" class="wp-first-item" tabindex="1">Users</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/user-new.php" tabindex="1">Add New</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/profile.php" tabindex="1">Your Profile</a></li>
		</ul>
	</div>
	</li>
	
	<li class="wp-has-submenu menu-top menu-icon-tools" id="menu-tools">
	<div class="wp-menu-image"><a href="http://e3solutionsincph.com/wp-admin/tools.php"><br></a></div>
	<div class="wp-menu-toggle"><br></div>
	<a href="http://e3solutionsincph.com/wp-admin/tools.php" class="wp-has-submenu menu-top menu-icon-tools" tabindex="1">Tools</a>
	<div class="wp-submenu"><div class="wp-submenu-head">Tools</div>
		<ul>
		<li class="wp-first-item"><a href="http://e3solutionsincph.com/wp-admin/tools.php" class="wp-first-item" tabindex="1">Tools</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/import.php" tabindex="1">Import</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/export.php" tabindex="1">Export</a></li>
		</ul>
	</div>
	</li>
	
	<li class="wp-has-submenu menu-top menu-icon-settings menu-top-last" id="menu-settings">
	<div class="wp-menu-image"><a href="http://e3solutionsincph.com/wp-admin/options-general.php"><br></a></div>
	<div class="wp-menu-toggle"><br></div>
	<a href="http://e3solutionsincph.com/wp-admin/options-general.php" class="wp-has-submenu menu-top menu-icon-settings menu-top-last" tabindex="1">Settings</a>
	<div class="wp-submenu">
        <div class="wp-submenu-head">Settings</div>
		<ul>
		<li class="wp-first-item"><a href="http://e3solutionsincph.com/wp-admin/options-general.php" class="wp-first-item" tabindex="1">General</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/options-writing.php" tabindex="1">Writing</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/options-reading.php" tabindex="1">Reading</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/options-discussion.php" tabindex="1">Discussion</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/options-media.php" tabindex="1">Media</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/options-privacy.php" tabindex="1">Privacy</a></li>
		<li><a href="http://e3solutionsincph.com/wp-admin/options-permalink.php" tabindex="1">Permalinks</a></li>
		</ul>
	</div>
	</li>
	
	<li class="wp-menu-separator-last"><a class="separator" href="http://e3solutionsincph.com/wp-admin/?unfoldmenu=1"><br></a></li></ul>

<div style="overflow: hidden;" id="wpbody-content">
<div id="screen-meta">
<div style="display: none;" id="screen-options-wrap" class="hidden">
	<form id="adv-settings" action="" method="post">
			<h5>Show on screen</h5>
		<div class="metabox-prefs">
			<label for="dashboard_right_now-hide"><input class="hide-postbox-tog" name="dashboard_right_now-hide" id="dashboard_right_now-hide" value="dashboard_right_now" checked="checked" type="checkbox">Right Now</label>
<label for="dashboard_recent_comments-hide"><input class="hide-postbox-tog" name="dashboard_recent_comments-hide" id="dashboard_recent_comments-hide" value="dashboard_recent_comments" checked="checked" type="checkbox">Recent Comments <span class="postbox-title-action"><a href="http://e3solutionsincph.com/wp-admin/?edit=dashboard_recent_comments#dashboard_recent_comments" class="edit-box open-box">Configure</a></span></label>
<label for="dashboard_incoming_links-hide"><input class="hide-postbox-tog" name="dashboard_incoming_links-hide" id="dashboard_incoming_links-hide" value="dashboard_incoming_links" checked="checked" type="checkbox">Incoming Links <span class="postbox-title-action"><a href="http://e3solutionsincph.com/wp-admin/?edit=dashboard_incoming_links#dashboard_incoming_links" class="edit-box open-box">Configure</a></span></label>
<label for="dashboard_plugins-hide"><input class="hide-postbox-tog" name="dashboard_plugins-hide" id="dashboard_plugins-hide" value="dashboard_plugins" checked="checked" type="checkbox">Plugins</label>
<label for="dashboard_quick_press-hide"><input class="hide-postbox-tog" name="dashboard_quick_press-hide" id="dashboard_quick_press-hide" value="dashboard_quick_press" checked="checked" type="checkbox">QuickPress</label>
<label for="dashboard_recent_drafts-hide"><input class="hide-postbox-tog" name="dashboard_recent_drafts-hide" id="dashboard_recent_drafts-hide" value="dashboard_recent_drafts" checked="checked" type="checkbox">Recent Drafts</label>
<label for="dashboard_primary-hide"><input class="hide-postbox-tog" name="dashboard_primary-hide" id="dashboard_primary-hide" value="dashboard_primary" checked="checked" type="checkbox">GoAutoDial Blog <span class="postbox-title-action"><a href="http://e3solutionsincph.com/wp-admin/?edit=dashboard_primary#dashboard_primary" class="edit-box open-box">Configure</a></span></label>
<label for="dashboard_secondary-hide"><input class="hide-postbox-tog" name="dashboard_secondary-hide" id="dashboard_secondary-hide" value="dashboard_secondary" checked="checked" type="checkbox">Other GoAutoDial News <span class="postbox-title-action"><a href="http://e3solutionsincph.com/wp-admin/?edit=dashboard_secondary#dashboard_secondary" class="edit-box open-box">Configure</a></span></label>
			<br class="clear">
		</div>
		<h5>Screen Layout</h5>
<div class="columns-prefs">Number of Columns:
<label><input name="screen_columns" value="1" type="radio"> 1</label>
<label><input name="screen_columns" value="2" checked="checked" type="radio"> 2</label>
<label><input name="screen_columns" value="3" type="radio"> 3</label>
<label><input name="screen_columns" value="4" type="radio"> 4</label>
</div>
<div><input id="screenoptionnonce" name="screenoptionnonce" value="7f074d8158" type="hidden"></div>
</form>
</div>

	<div id="contextual-help-wrap" class="hidden">
	<div class="metabox-prefs"><p>Welcome to your GoAutoDial Dashboard! You 
will find helpful tips in the Help tab of each screen to assist you as 
you get to know the application.</p><p>The left-hand navigation menu 
provides links to the administration screens in your GoAutoDial 
application. You can expand or collapse navigation sections by clicking 
on the arrow that appears on the right side of each navigation item when
 you hover over it. You can also minimize the navigation menu to a 
narrow icon strip by clicking on the separator lines between navigation 
sections that end in double arrowheads; when minimized, the submenu 
items will be displayed on hover.</p><p>You can configure your dashboard
 by choosing which modules to display, how many columns to display them 
in, and where each module should be placed. You can hide/show modules 
and select the number of columns in the Screen Options tab. To rearrange
 the modules, drag and drop by clicking on the title bar of the selected
 module and releasing when you see a gray dotted-line box appear in the 
location you want to place the module. You can also expand or collapse 
each module by clicking once on the the module’s title bar. In addition,
 some modules are configurable, and will show a “Configure” link in the 
title bar when you hover over it.</p><p>The modules on your Dashboard screen are:</p><p><strong>Right Now</strong> - Displays a summary of the content on your site and identifies which theme and version of GoAutoDial you are using.</p><p><strong>Recent Comments</strong> - Shows the most recent comments on your posts (configurable, up to 30) and allows you to moderate them.</p><p><strong>Incoming Links</strong> - Shows links to your site found by Google Blog Search.</p><p><strong>QuickPress</strong> - Allows you to create a new post and either publish it or save it as a draft.</p><p><strong>Recent Drafts</strong> - Displays links to the 5 most recent draft posts you’ve started.</p><p><strong>Other GoAutoDial News</strong> - Shows the feed from <a href="http://planet.GoAutoDial.org/" target="_blank">GoAutoDial Planet</a>. You can configure it to show a different feed of your choosing.</p><p><strong>Plugins</strong> - Features the most popular, newest, and recently updated plugins from the GoAutoDial.org Plugin Directory.</p><p><strong>For more information:</strong></p><p><a href="http://codex.GoAutoDial.org/Dashboard_SubPanel" target="_blank">Dashboard Documentation</a></p><p><a href="http://GoAutoDial.org/support/" target="_blank">Support Forums</a></p></div>
	</div>

<div id="screen-meta-links">
<div style="" id="contextual-help-link-wrap" class="hide-if-no-js screen-meta-toggle">
<a href="#contextual-help" id="contextual-help-link" class="show-settings">Help</a>
</div>
<div id="screen-options-link-wrap" class="hide-if-no-js screen-meta-toggle">
<a style="background-image: url(&quot;images/screen-options-right.gif?ver=20100531&quot;);" href="#screen-options" id="show-settings-link" class="show-settings">Screen Options</a>
</div>
</div>
</div>
<div class="update-nag"><a href="http://codex.GoAutoDial.org/Version_3.1.3">GoAutoDial CE 3.1.3</a> is available! <a href="http://e3solutionsincph.com/wp-admin/update-core.php">Please update now</a>.</div>
<div class="wrap">
	<div id="icon-index" class="icon32"><br></div>
<h2>Dashboard</h2>

<div id="dashboard-widgets-wrap">

<div id="dashboard-widgets" class="metabox-holder">
	<div class="postbox-container" style="width:49%;">
<div id="normal-sortables" class="meta-box-sortables ui-sortable"><div style="display: block;" id="dashboard_right_now" class="postbox">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Right Now</span></h3>
<div class="inside">

	<div class="table table_content">
	<p class="sub">Content</p>
	<table>
	<tbody><tr class="first"><td class="first b b-posts"><a href="http://e3solutionsincph.com/wp-admin/edit.php">0</a></td><td class="t posts"><a href="http://e3solutionsincph.com/wp-admin/edit.php">Posts</a></td></tr><tr><td class="first b b_pages"><a href="http://e3solutionsincph.com/wp-admin/edit.php?post_type=page">4</a></td><td class="t pages"><a href="http://e3solutionsincph.com/wp-admin/edit.php?post_type=page">Pages</a></td></tr><tr><td class="first b b-cats"><a href="http://e3solutionsincph.com/wp-admin/edit-tags.php?taxonomy=category">1</a></td><td class="t cats"><a href="http://e3solutionsincph.com/wp-admin/edit-tags.php?taxonomy=category">Category</a></td></tr><tr><td class="first b b-tags"><a href="http://e3solutionsincph.com/wp-admin/edit-tags.php">0</a></td><td class="t tags"><a href="http://e3solutionsincph.com/wp-admin/edit-tags.php">Tags</a></td></tr>
	</tbody></table>
	</div>
	<div class="table table_discussion">
	<p class="sub">Discussion</p>
	<table>
	<tbody><tr class="first"><td class="b b-comments"><a href="http://e3solutionsincph.com/wp-admin/edit-comments.php"><span class="total-count">0</span></a></td><td class="last t comments"><a href="http://e3solutionsincph.com/wp-admin/edit-comments.php">Comments</a></td></tr><tr><td class="b b_approved"><a href="http://e3solutionsincph.com/wp-admin/edit-comments.php?comment_status=approved"><span class="approved-count">0</span></a></td><td class="last t"><a class="approved" href="http://e3solutionsincph.com/wp-admin/edit-comments.php?comment_status=approved">Approved</a></td></tr>
	<tr><td class="b b-waiting"><a href="http://e3solutionsincph.com/wp-admin/edit-comments.php?comment_status=moderated"><span class="pending-count">0</span></a></td><td class="last t"><a class="waiting" href="http://e3solutionsincph.com/wp-admin/edit-comments.php?comment_status=moderated">Pending</a></td></tr>
	<tr><td class="b b-spam"><a href="http://e3solutionsincph.com/wp-admin/edit-comments.php?comment_status=spam"><span class="spam-count">0</span></a></td><td class="last t"><a class="spam" href="http://e3solutionsincph.com/wp-admin/edit-comments.php?comment_status=spam">Spam</a></td></tr>
	</tbody></table>
	</div>
	<div class="versions">
	<p><a href="http://e3solutionsincph.com/wp-admin/themes.php" class="button rbutton">Change Theme</a>Theme <span class="b"><a href="http://e3solutionsincph.com/wp-admin/themes.php">Pro_business_Premium</a></span> with <span class="b"><a href="http://e3solutionsincph.com/wp-admin/widgets.php">0 Widgets</a></span></p><span id="wp-version-message">You are using <span class="b">GoAutoDial 3.0.1</span>. <a href="http://e3solutionsincph.com/wp-admin/update-core.php" class="button">Update to 3.1.3</a></span>
	<br class="clear"></div></div>
</div>
<div id="dashboard_recent_comments" class="postbox ">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Recent Comments <span class="postbox-title-action"><a href="http://e3solutionsincph.com/wp-admin/?edit=dashboard_recent_comments#dashboard_recent_comments" class="edit-box open-box">Configure</a></span></span></h3>
<div class="inside">

	<p>No comments yet.</p>

</div>
</div>
<div id="dashboard_incoming_links" class="postbox ">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Incoming Links <span class="postbox-title-action"><a href="http://e3solutionsincph.com/wp-admin/?edit=dashboard_incoming_links#dashboard_incoming_links" class="edit-box open-box">Configure</a></span></span></h3>
<div style="" class="inside"><p>This dashboard widget queries <a href="http://blogsearch.google.com/">Google Blog Search</a>
 so that when another blog links to your site it will show up here. It 
has found no incoming links… yet. It’s okay — there is no rush.</p>
</div>
</div>
<div id="dashboard_plugins" class="postbox ">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Plugins</span></h3>
<div style="" class="inside"><h4>Most Popular</h4>
<h5><a href="http://GoAutoDial.org/extend/plugins/ourstatsde-widget/">ourSTATS Widget</a></h5>&nbsp;<span>(<a href="http://e3solutionsincph.com/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=ourstatsde-widget&amp;_wpnonce=87ba5f0722&amp;TB_iframe=true&amp;width=600&amp;height=800" class="thickbox" title="ourSTATS Widget">Install</a>)</span>
<p>create a widget for the ourstats.de counter service</p>
<h4>Newest Plugins</h4>
<h5><a href="http://GoAutoDial.org/extend/plugins/custom-scheduled-posts-widget/">Custom Scheduled Posts Widget</a></h5>&nbsp;<span>(<a href="http://e3solutionsincph.com/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=custom-scheduled-posts-widget&amp;_wpnonce=5faef78100&amp;TB_iframe=true&amp;width=600&amp;height=800" class="thickbox" title="Custom Scheduled Posts Widget">Install</a>)</span>
<p>Custom skeduled Posts. This widget gives you the ability to show  skeduled posts for start and end times on the widget content.</p>
<h4>Recently Updated</h4>
<h5><a href="http://GoAutoDial.org/extend/plugins/magic-zoom-for-GoAutoDial/">Magic Zoom</a></h5>&nbsp;<span>(<a href="http://e3solutionsincph.com/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=magic-zoom-for-GoAutoDial&amp;_wpnonce=454b8000e2&amp;TB_iframe=true&amp;width=600&amp;height=800" class="thickbox" title="Magic Zoom">Install</a>)</span>
<p>Magic Zoom will zoom into your images to reveal beautiful detail. Simply hover your mouse over the image.</p>
</div>
</div>
</div>	</div><div class="postbox-container" style="width:49%;">
<div id="side-sortables" class="meta-box-sortables ui-sortable">
<div id="dashboard_recent_drafts" class="postbox ">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Recent Drafts</span></h3>
<div class="inside">
There are no drafts at the moment</div>
</div>
<div id="dashboard_primary" class="postbox ">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>GoAutoDial Blog <span class="postbox-title-action"><a href="http://e3solutionsincph.com/wp-admin/?edit=dashboard_primary#dashboard_primary" class="edit-box open-box">Configure</a></span></span></h3>
<div style="" class="inside"><div class="rss-widget"><ul><li><a class="rsswidget" href="http://GoAutoDial.org/news/2011/06/GoAutoDial-3-2-release-candidate-2/" title="Howdy! The second release candidate for GoAutoDial 3.2 is now available. If you haven’t tested GoAutoDial 3.2 yet, now is the time — please though, not on your live site unless you’re extra adventurous. We’ve handled a number of issues since RC1, including additional Twenty Eleven tweaks, a new theme support option for defaulting to […]">GoAutoDial 3.2 Release Candidate 2</a> <span class="rss-date">June 24, 2011</span><div class="rssSummary">Howdy!
 The second release candidate for GoAutoDial 3.2 is now available. If you
 haven’t tested GoAutoDial 3.2 yet, now is the time — please though, not 
on your live site unless you’re extra adventurous. We’ve handled a 
number of issues since RC1, including additional Twenty Eleven tweaks, a
 new theme support option for defaulting to […]</div></li><li><a class="rsswidget" href="http://GoAutoDial.org/news/2011/06/passwords-reset/" title="Earlier today the GoAutoDial team noticed suspicious commits to several popular plugins (AddThis, WPtouch, and W3 Total Cache) containing cleverly disguised backdoors. We determined the commits were not from the authors, rolled them back, pushed updates to the plugins, and shut down access to the plugin repository while we looked for anything else unsavory. W […]">Passwords Reset</a> <span class="rss-date">June 21, 2011</span><div class="rssSummary">Earlier
 today the GoAutoDial team noticed suspicious commits to several popular 
plugins (AddThis, WPtouch, and W3 Total Cache) containing cleverly 
disguised backdoors. We determined the commits were not from the 
authors, rolled them back, pushed updates to the plugins, and shut down 
access to the plugin repository while we looked for anything else 
unsavory. W […]</div></li></ul></div></div>
</div><div style="display: block;" id="dashboard_quick_press" class="postbox">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>QuickPress</span></h3>
<div style="" class="inside">
	<form name="post" action="http://e3solutionsincph.com/wp-admin/post.php" method="post" id="quick-press">
		<h4 id="quick-post-title"><label for="title">Title</label></h4>
		<div class="input-text-wrap">
			<input name="post_title" id="title" tabindex="1" autocomplete="off" type="text">
		</div>

				<div id="media-buttons" class="hide-if-no-js">
			Upload/Insert <a href="http://e3solutionsincph.com/wp-admin/media-upload.php?post_id=42&amp;type=image&amp;TB_iframe=1" id="add_image" class="thickbox" title="Add an Image"><img src="Dashboard%20%E2%80%B9%20e3solutions%20inc.%20%E2%80%93%20a%20customer-driven%20company%20%E2%80%94%20GoAutoDial_files/media-button-image.gif" alt="Add an Image"></a><a href="http://e3solutionsincph.com/wp-admin/media-upload.php?post_id=42&amp;type=video&amp;TB_iframe=1" id="add_video" class="thickbox" title="Add Video"><img src="Dashboard%20%E2%80%B9%20e3solutions%20inc.%20%E2%80%93%20a%20customer-driven%20company%20%E2%80%94%20GoAutoDial_files/media-button-video.gif" alt="Add Video"></a><a href="http://e3solutionsincph.com/wp-admin/media-upload.php?post_id=42&amp;type=audio&amp;TB_iframe=1" id="add_audio" class="thickbox" title="Add Audio"><img src="Dashboard%20%E2%80%B9%20e3solutions%20inc.%20%E2%80%93%20a%20customer-driven%20company%20%E2%80%94%20GoAutoDial_files/media-button-music.gif" alt="Add Audio"></a><a href="http://e3solutionsincph.com/wp-admin/media-upload.php?post_id=42&amp;TB_iframe=1" id="add_media" class="thickbox" title="Add Media"><img src="Dashboard%20%E2%80%B9%20e3solutions%20inc.%20%E2%80%93%20a%20customer-driven%20company%20%E2%80%94%20GoAutoDial_files/media-button-other.gif" alt="Add Media"></a>		</div>
		
		<h4 id="content-label"><label for="content">Content</label></h4>
		<div class="textarea-wrap">
			<textarea name="content" id="content" class="mceEditor" rows="3" cols="15" tabindex="2"></textarea>
		</div>

		

		<h4><label for="tags-input">Tags</label></h4>
		<div class="input-text-wrap">
			<input name="tags_input" id="tags-input" tabindex="3" type="text">
		</div>

		<p class="submit">
			<input name="action" id="quickpost-action" value="post-quickpress-save" type="hidden">
			<input name="quickpress_post_ID" value="42" type="hidden">
			<input name="post_type" value="post" type="hidden">
			<input id="_wpnonce" name="_wpnonce" value="d27cb82ba7" type="hidden"><input name="_wp_http_referer" value="/wp-admin/index-extra.php?jax=dashboard_quick_press" type="hidden">			<input name="save" id="save-post" class="button" tabindex="4" value="Save Draft" type="submit">
			<input value="Reset" class="button" type="reset">
			<span id="publishing-action">
				<input name="publish" id="publish" accesskey="p" tabindex="5" class="button-primary" value="Publish" type="submit">
				<img class="waiting" src="Dashboard%20%E2%80%B9%20e3solutions%20inc.%20%E2%80%93%20a%20customer-driven%20company%20%E2%80%94%20GoAutoDial_files/wpspin_light.gif">
			</span>
			<br class="clear">
		</p>

	</form>

</div>
</div>
<div id="dashboard_secondary" class="postbox closed">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Other GoAutoDial News <span class="postbox-title-action"><a href="http://e3solutionsincph.com/wp-admin/?edit=dashboard_secondary#dashboard_secondary" class="edit-box open-box">Configure</a></span></span></h3>
<div class="inside">
<p class="widget-loading hide-if-no-js">Loading…</p><p class="describe hide-if-js">This widget requires JavaScript.</p></div>
</div>
</div>	</div><div class="postbox-container" style="display:none;width:49%;">
<div style="" id="column3-sortables" class="meta-box-sortables ui-sortable"></div>	</div><div class="postbox-container" style="display:none;width:49%;">
<div style="" id="column4-sortables" class="meta-box-sortables ui-sortable"></div></div></div>

<form style="display:none" method="get" action="">
	<p>
<input id="closedpostboxesnonce" name="closedpostboxesnonce" value="8b428d210a" type="hidden"><input id="meta-box-order-nonce" name="meta-box-order-nonce" value="5fab725de7" type="hidden">	</p>
</form>


<div class="clear"></div>
</div><!-- dashboard-widgets-wrap -->

</div><!-- wrap -->


<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->
</div><!-- wpwrap -->

<div id="footer">
<p id="footer-left" class="alignleft"><span id="footer-thankyou">Thank you for creating with <a href="http://GoAutoDial.org/">GoAutoDial</a>.</span> | <a href="http://codex.GoAutoDial.org/">Documentation</a> | <a href="http://GoAutoDial.org/support/forum/4">Feedback</a></p>
<p id="footer-upgrade" class="alignright"><strong><a href="http://e3solutionsincph.com/wp-admin/update-core.php">Get Version 3.1.3</a></strong></p>
<div class="clear"></div>
</div>
<script type="text/javascript">
/* <![CDATA[ */
var commonL10n = {
	warnDelete: "You are about to permanently delete the selected items.\n  \'Cancel\' to stop, \'OK\' to delete."
};
try{convertEntities(commonL10n);}catch(e){};
var wpAjax = {
	noPerm: "You do not have permission to do that.",
	broken: "An unidentified error has occurred."
};
try{convertEntities(wpAjax);}catch(e){};
var adminCommentsL10n = {
	hotkeys_highlight_first: "",
	hotkeys_highlight_last: ""
};
var thickboxL10n = {
	next: "Next &gt;",
	prev: "&lt; Prev",
	image: "Image",
	of: "of",
	close: "Close",
	noiframes: "This feature requires inline frames. You have iframes disabled or your browser does not support them."
};
try{convertEntities(thickboxL10n);}catch(e){};
var plugininstallL10n = {
	plugin_information: "Plugin Information:",
	ays: "Are you sure you want to install this plugin?"
};
try{convertEntities(plugininstallL10n);}catch(e){};
/* ]]> */
</script>
<script type="text/javascript" src="<? echo $base; ?>js/go_fload_scripts.js"></script>

<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>


</body></html>