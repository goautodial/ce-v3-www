<?php
########################################################################################################
####  Name:             	go_noadmin_header.php            	                    	    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################


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
if ( empty($theme) )
{	
	$theme='goautodial.css';
}

$theme = str_replace("goautodial", "goiframe-noadmin", $theme);

#### LOAD HTML
$this->load->helper('html');
echo doctype('xhtml11') . "\n";
echo "<html>\n";
echo "<head>\n";

#### SET SITE TITLE
#echo "<title>GoAutoDial - Administrator Application</title>\n";
?>
<title>GoAdmin &reg; 3.3</title>
<link rel="shortcut icon" href="<?php echo $base; ?>img/gologoico.ico" />
<?
#### SET META TAGS
$meta = array(array('name' => 'GoAutoDial', 'content' => 'GoAutoDial Inc. http://www.goautodial.com'),array('name' => 'description', 'content' => 'GoAutoDial'),array('name' => 'keywords', 'content' => 'dialer, predictive dialer'),array('name' => 'Content-type', 'content' => 'application/xhtml+xml; charset=utf-8; charset=utf-8', 'type' => 'equiv'));
echo meta($meta) . "\n";

#### SET SITE ICONS
echo link_tag('img/g_page_icon.ico', 'shortcut icon', 'image/ico') . "\n";
echo link_tag('img/g_icon_05.png', 'icon', 'image/png') . "\n";

#### SET CSS
echo link_tag('css/'.$theme); 
echo link_tag('css/goautodial-ext.css');

echo "</head>\n";
echo "<body>\n";
echo "<div id='webtemp18'>\n";
echo "<div id='header'>\n";
$uabanner = $this->lang->line("ua_banner_text");
echo "<div id='gologo'><a href='http://goautodial.com' title='$uabanner' target='_blank'></a></div>\n";
echo "<div id='gomenu'>\n";
	
$list = array(anchor('http://goautodial.com/voip-store/', $this->lang->line("ua_VOIPStore"), array('title' => $this->lang->line("ua_VOIPStore"), 'target' => '_blank')));
$attributes = array('name' => $this->lang->line("ua_VOIPStore"));
echo ul($list, $attributes);

$list = array(anchor('http://goautodial.com/support-ticket/', $this->lang->line("SupportCenter"), array('title' => $this->lang->line("SupportCenter"), 'target' => '_blank')));
$attributes = array('name' => $this->lang->line("SupportCenter"));
echo ul($list, $attributes);

$list = array(anchor('http://' . $hostp . '/phpmyadmin/', $this->lang->line("phpMyAdmin"), array('title' => $this->lang->line("phpMyAdmin"), 'target' => '_blank')));
$attributes = array('name' => $this->lang->line("phpMyAdmin"));
echo ul($list, $attributes);

$admin_login = "<a href='#?w=280'  rel='popup1' class='poplight' title=" . $this->lang->line("ua_AdminLogin") . ">" . $this->lang->line("ua_AdminLogin") . "</a>";
$list = array($admin_login);
$attributes = array('name' => $this->lang->line("ua_AdminLogin"));
echo ul($list, $attributes);

$list = array(anchor('../../goautodial-agent/vicidial.php?relogin=YES', $this->lang->line("ua_AgentLogin"), array('title' => $this->lang->line("ua_AgentLogin"), 'target' => '_blank')));
$attributes = array('name' => $this->lang->line("ua_AgentLogin"));
echo ul($list, $attributes);

echo "</div>\n";
echo "<div id='bannertext'>\n";
echo "<p>$bannertitle</p>\n";
echo "</div>\n";
echo "</div>\n";
