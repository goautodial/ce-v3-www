<?php
########################################################################################################
####  Name:             	go_invalid.php                     	    		    	    ####
####  Type:             	ci views - administrator					    ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();

#### SET LANGUAGE
$language = $this->session->userdata('ua_language');
if ( empty($language) )
	{
		if ( $this->config->item('ua_multi_language') ) {
			$language = $this->lang_detect->language();
		}
		else
		{ 
			$language = $this->config->item('language'); 
		}
		$this->session->set_userdata('ua_language', $language);
	}
$this->lang->load('userauth', $language);	
$this->lang->load('userauth', $this->session->userdata('ua_language'));

#### SET THEME
$theme = $this->input->post('go_theme');
if ( empty($theme) )
{	
	$theme='goautodial.css';
}




#### LOAD HTML
$this->load->helper('html');
echo doctype('xhtml11') . "\n";
echo "<html>\n";
echo "<head>\n";

#### SET SITE TITLE
echo "<title>GoAutoDial - Empowering The Next Generation Contact Centers</title>\n";

#### SET META TAGS
$meta = array(array('name' => 'GoAutoDial', 'content' => 'GoAutoDial Inc. http://www.goautodial.com'),array('name' => 'description', 'content' => 'GoAutoDial'),array('name' => 'keywords', 'content' => 'dialer, predictive dialer'),array('name' => 'Content-type', 'content' => 'application/xhtml+xml; charset=utf-8; charset=utf-8', 'type' => 'equiv'));
echo meta($meta) . "\n";

#### SET SITE ICONS
echo link_tag('img/g_page_icon.ico', 'shortcut icon', 'image/ico') . "\n";
echo link_tag('img/g_icon_05.png', 'icon', 'image/png') . "\n";

#### SET CSS
echo link_tag('css/'.$theme); 
echo link_tag('css/goautodial-ext.css'); 
?>

<script src="<? echo $base; ?>js/jquery.main.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.ui.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.notify.js" type="text/javascript"></script>
<?
echo "</head>\n";
echo "<body>\n";
echo "<div class='container'>\n";
echo "<div id='webtemp21'>\n";
$uabanner = $this->lang->line("ua_banner_text");
echo "<div id='gologo'><a href='http://goautodial.com' title='$uabanner' target='_blank'></a></div>\n";
echo "<div id='bannericon'>\n";

#### LANGUAGE SELECTOR
if ( $this->config->item('ua_multi_language') )
	{
		echo '<div id = "language_select">';
		$options = array(
			#'detect'  => $this->lang_detect->browserLanguage(),
			#'german'  => 'Deutsch',
			#'spanish' => 'Espanol',
			'english' => 'English',
			#'french'  => 'Frances',
			#'greek'   => 'Greek',
			#'polish'  => 'Polska',
			#'russian' => 'Russian',
			'swedish' => 'Swedish',
			#'finnish' => 'Suomi'
		);
		$js = 'onchange ="submit()"';
		echo form_open('go_index/set_language');
		echo "lang:&nbsp" .  form_dropdown('lang_select', $options, $language, $js);
		echo form_close();
		echo '</div>';	
	}

### THEME SELECTOR
echo '<div id = "theme">';
$options = array(
	#'detect'  => $this->theme_detect->currentTheme(),
	'goautodial.css'  => 'Green',
	'goautodial-blue.css' => 'Blue',
	'goautodial-orange.css' => 'Orange'
);
$js = 'onchange ="submit()"';
echo form_open('go_index/set_theme');
echo "theme:&nbsp" .  form_dropdown('go_theme', $options, $theme, $js);
echo form_close();
echo "</div>\n";
echo "</div>\n";



$is_logged_in = $this->session->userdata('is_logged_in');
			if(!isset($is_logged_in) || $is_logged_in != true)
				{
					$adminlink = "#?w=280";
					$adminlink_target = "";

				}
				else
				{
					$adminlink = "index.php/go_site/go_dashboard";
					$adminlink_target = "target='_blank'";
				}		
	

echo "<div id='gomenu'>\n";
	
		
	
	
$list = array(anchor('http://goautodial.com/voip-store/', $this->lang->line("ua_VOIPStore"), array('title' => $this->lang->line("ua_VOIPStore"), 'target' => '_blank')));
$attributes = array('name' => $this->lang->line("ua_VOIPStore"));
echo ul($list, $attributes);

$list = array(anchor('http://goautodial.org', $this->lang->line("ua_Community"), array('title' => $this->lang->line("ua_Community"), 'target' => '_blank')));
$attributes = array('name' => $this->lang->line("ua_Community"));
echo ul($list, $attributes);

$list = array(anchor('../index.php/go_index/go_vtiger', $this->lang->line("ua_VTigerCRM"), array('title' => $this->lang->line("ua_VTigerCRM"), 'target' => '_blank')));
$attributes = array('name' => $this->lang->line("ua_VTigerCRM"));
echo ul($list, $attributes);

$admin_login = "<a href='$adminlink' $adminlink_target rel='popup1' class='poplight' title=" . $this->lang->line("ua_AdminLogin") . ">" . $this->lang->line("ua_AdminLogin") . "</a>";
$list = array($admin_login);
$attributes = array('name' => $this->lang->line("ua_AdminLogin"));
echo ul($list, $attributes);

$list = array(anchor('../../goautodial-agent/vicidial.php?relogin=YES', $this->lang->line("ua_AgentLogin"), array('title' => $this->lang->line("ua_AgentLogin"), 'target' => '_blank')));
$attributes = array('name' => $this->lang->line("ua_AgentLogin"));
echo ul($list, $attributes);

echo "</div>\n";
echo "<div id='bannertext'>\n";
echo "<p style='line-height:58px; font-size:48px;'>" . $this->lang->line("ua_empowering") . "</p>\n";
echo "<p>" . $log_status . $this->lang->line("ua_thenextgen") . "</p>\n";   
echo "<p>" . $this->lang->line("ua_contactcenters") . "</p>\n";   
echo "</div>\n";

?>

<div id="contents"></div>
<div id="helpbutton">		<a  href="http://goautodial.com/support-ticket/" title="Get Support!" target="_blank"></a></div>
<div id="welcomebutton">	<a  href="http://goautodial.com"  title="Welcome!" target="_blank"></a></div>
<div id="getstartedbutton">	<a  href="http://goautodial.com/wiki/getting-started-guide/" title="Getting Started Guide!" target="_blank"></a></div>
<div id="welcometitletext">
 <p><a  href="http://goautodial.com"  title="<? echo $this->lang->line("ua_welcome"); ?>" target="_blank"><? echo $this->lang->line("ua_welcome"); ?></a></p>
</div>
<div id="getstartedtitletext">
 <p><a  href="http://goautodial.com/wiki/getting-started-guide/" title="<? echo $this->lang->line("ua_getstarted"); ?>" target="_blank"><? echo $this->lang->line("ua_getstarted"); ?></a></p>
</div>
<div id="helptitletext">
 <p><a  href="http://goautodial.com/support-ticket/" title="<? echo $this->lang->line("ua_needhelp"); ?>" target="_blank"><? echo $this->lang->line("ua_needhelp"); ?></a></p>
</div>
<div id="welcomebox"></div>
<div id="getstartedbox"></div>
<div id="helpbox"></div>
<div id="welcomebodytext">
 <p><span  style="color:#404040;"><? echo $this->lang->line("ua_welcomephrase"); ?></span><a href="http://goautodial.com" title="GoAutoDial Inc.- Empowering The Next Generation Contact Center" target="_blank"> http://goautodial.com</a>.</p>
</div>
<div id="getstartedbodytext">
 <p><? echo $this->lang->line("ua_getstartedphrase"); ?></p>
</div>
<div id="helpbodytext">
 <p><? echo $this->lang->line("ua_needhelpphrase"); ?></p>
</div>
<div id="bodytext">
 <p><? echo $this->lang->line("ua_licence"); ?></p>
</div>

<?
/*echo "<div id='footertext'>\n";


echo "<p>" . $this->lang->line("ua_licence") . "</p>\n";
echo "<p>" . anchor('http://goautodial.com', '&copy; 2010 GoAutoDial, Inc.', array('title' => 'GoAutoDial Inc.- Empowering The Next Generation Contact Centers', 'target' => '_blank')) . " | " . anchor('termsofuse.php', 'Terms of Use', array('title' => 'GoAutoDial - Terms Of Use', 'target' => '_blank')) . "</p>\n";
echo "</div>\n";*/
echo "</div>\n";
echo "</div>\n";
echo "<div id='popup1' class='popup_block'>\n";
#$this->load->view('go_login_form');
echo "<h1>" . $this->lang->line("ua_administrator_login") . "</h1>\n";
echo form_open('go_login/validate_credentials');
echo form_input('user_name', '');
echo form_password('user_pass', '');
echo form_submit('submit', 'Login');
echo "<h3>" . $this->lang->line("ua_remember_me") . "  " . form_checkbox('remember_me', 1, true) . "</h3>\n";
#echo anchor('go_login/signup', 'Create Account');
echo form_close();
echo "</div>\n";
echo "<script type='text/javascript'>\n";
?>
$(document).ready(function(){

	$('a.poplight[href^=#]').click(function() {
		var popID = $(this).attr('rel'); //Get Popup Name
		var popURL = $(this).attr('href'); //Get Popup href to define size
				
		//Pull Query & Variables from href URL
		var query= popURL.split('?');
		var dim= query[1].split('&');
		var popWidth = dim[0].split('=')[1]; //Gets the first query string value

		//Fade in the Popup and add close button
		$('#' + popID).fadeIn().effect("shake", { times:3 }, 100).css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="<? echo base_url(); ?>img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
		
		
		
		//Define margin for center alignment (vertical + horizontal) - we add 80 to the height/width to accomodate for the padding + border width defined in the css
		var popMargTop = ($('#' + popID).height() + 80) / 2;
		var popMargLeft = ($('#' + popID).width() + 80) / 2;
		
		//Apply Margin to Popup
		$('#' + popID).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		//Fade in Background
		$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer 
		
		return false;
	});
	
	//Close Popups and Fade Layer
	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
	  	$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
	}); //fade them both out
			});	
});
<?
echo "</script>\n";

?>


<script type="text/javascript">
			
			
	



window.onload=function(){$('a.poplight[href=#?w=280]').click()};



</script>

<?
echo "<div id='backgroundbg'><p></p></div>";
echo "<div id='footertext'>\n";
echo "<div id='footercontainer'>\n";
echo "<p>" . $this->lang->line("ua_licence") . "</p>\n";
echo "<p>" . anchor('http://goautodial.com', '&copy; 2010 GoAutoDial, Inc.', array('title' => 'GoAutoDial Inc.- Empowering The Next Generation Contact Centers', 'target' => '_blank')) . " | " . anchor('termsofuse.php', 'Terms of Use', array('title' => 'GoAutoDial - Terms Of Use', 'target' => '_blank')) . "</p>\n";
echo "</div>\n";
echo "</div>\n";
echo "</body>\n";
echo "</html>\n";
?>