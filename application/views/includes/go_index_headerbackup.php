<?php
########################################################################################################
####  Name:             	go_index_headerbackup.php     	    	                            ####
####  Type:             	ci views - administrator                                            ####
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
$this->lang->load('index', $language);	
$this->lang->load('index', $this->session->userdata('ua_language'));

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
#echo "<title>GoAutoDial - Empowering The Next Generation Contact Centers</title>\n";
?>
<title>GoAdmin &reg; 3.0</title>
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

?>

<script src="<? echo $base; ?>js/jquery.main.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.ui.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.notify.js" type="text/javascript"></script>

<?

echo "</head>\n";
echo "<body>\n";
echo "<div id='container'>\n";
echo "<div id='bannerbg'></div>\n";
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
			'filipino' => 'Filipino'
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
					$adminlink = "portal";
					$adminlink_target = "target='_blank'";
				}		
	

echo "<div id='gomenu'>\n";
	
		
	
	
$list = array(anchor('http://goautodial.com/voip-store/', $this->lang->line("ua_VOIPStore"), array('title' => $this->lang->line("ua_VOIPStore"), 'target' => '_blank')));
$attributes = array('name' => $this->lang->line("ua_VOIPStore"));
echo ul($list, $attributes);

$list = array(anchor('http://goautodial.org', $this->lang->line("ua_Community"), array('title' => $this->lang->line("ua_Community"), 'target' => '_blank')));
$attributes = array('name' => $this->lang->line("ua_Community"));
echo ul($list, $attributes);

$list = array(anchor('../go_index/go_vtiger', $this->lang->line("ua_VTigerCRM"), array('title' => $this->lang->line("ua_VTigerCRM"), 'target' => '_blank')));
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
echo "<p>" . $this->lang->line("ua_thenextgen") . "</p>\n";   
echo "<p>" . $this->lang->line("ua_contactcenters") . "</p>\n";   
echo "</div>\n";



