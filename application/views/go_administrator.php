<?php
########################################################################################################
####  Name:             	go_administrator.php                                                ####
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

?>
<div style="overflow: hidden;" id="wpbody-content">
	<div id="screen-meta">
		<div style="display: none;" id="screen-options-wrap" class="hidden">
			<form id="adv-settings" action="" method="post">
				<h5>Show on screen</h5> 
				<div class="metabox-prefs">
				<label for="dashboard_todays_status-hide">
					<input class="hide-postbox-tog" name="dashboard_todays_status-hide" id="dashboard_todays_status-hide" value="dashboard_todays_status" checked="checked" type="checkbox">
					Today's Status
				</label>
				<label for="dashboard_server_statistics-hide">
					<input class="hide-postbox-tog" name="dashboard_server_statistics-hide" id="dashboard_server_statistics-hide" value="dashboard_server_statistics" checked="checked" type="checkbox">
					Server Statistics
						<span class="postbox-title-action">
							<a href="" class="edit-box open-box">Configure</a>
						</span>
				</label>
				<label for="dashboard_agents_status-hide">
					<input class="hide-postbox-tog" name="dashboard_agents_status-hide" id="dashboard_agents_status-hide" value="dashboard_agents_status" checked="checked" type="checkbox">
					Agent's Status
						<span class="postbox-title-action">
							<a href="?edit=dashboard_agents_status#dashboard_agents_status" class="edit-box open-box">Configure</a>
						</span>
				</label>
				<label for="dashboard_plugins-hide">
					<input class="hide-postbox-tog" name="dashboard_plugins-hide" id="dashboard_plugins-hide" value="dashboard_plugins" checked="checked" type="checkbox">
					Plugins
				</label>
				<label for="dashboard_analytics-hide">
					<input class="hide-postbox-tog" name="dashboard_analytics-hide" id="dashboard_analytics-hide" value="dashboard_analytics" checked="checked" type="checkbox">
					GO Analytics
				</label>
				<label for="dashboard_goautodial_news-hide">
					<input class="hide-postbox-tog" name="dashboard_goautodial_news-hide" id="dashboard_goautodial_news-hide" value="dashboard_goautodial_news" checked="checked" type="checkbox">
					GoAutoDial News
						<span class="postbox-title-action">
							<a href="?edit=dashboard_goautodial_news#dashboard_goautodial_news" class="edit-box open-box">Configure</a>
						</span>
				</label>
				<label for="dashboard_goautodial_forum-hide">
					<input class="hide-postbox-tog" name="dashboard_goautodial_forum-hide" id="dashboard_goautodial_forum-hide" value="dashboard_goautodial_forum" checked="checked" type="checkbox">
					GoAutoDial Community & Forum
					<span class="postbox-title-action">
						<a href="?edit=dashboard_goautodial_forum#dashboard_goautodial_forum" class="edit-box open-box">Configure</a>
					</span>
				</label>
				<br class="clear">
				</div>

				<h5>Screen Layout</h5>
				<div class="columns-prefs">Number of Columns:
				<label>
					<input name="screen_columns" value="1" type="radio"> 1
				</label>
				<label>
					<input name="screen_columns" value="2" checked="checked" type="radio"> 2
				</label>
				<!--
				<label>
					<input name="screen_columns" value="3" type="radio"> 3
				</label>
				<label>
					<input name="screen_columns" value="4" type="radio"> 4
				</label>
				-->
				</div>
			</form>
		</div>
	
		<div id="advanced-search-wrap" class="hidden">
			<div class="metabox-prefs">
				<form name="post" action="" method="post" id="advanced-search">
					<h3 id="content-label">Advanced Search</h3>
					<div>
						<b>Strings: </b> <input name="tags_input" id="tags-input" size="100" tabindex="1" type="text">
					</div>
					<br>
					<div>
						<b>Options: </b> 
						<a href="" id="add_image" class="thickbox" title="Phone"><img src="<? echo base_url();?>img/media-button-image.gif" alt="Phone"></a>
						<a href="" id="add_video" class="thickbox" title="Lead"><img src="<? echo base_url();?>img/media-button-video.gif" alt="Lead"></a>
						<a href="" id="add_audio" class="thickbox" title="Disposition"><img src="<? echo base_url();?>img/media-button-music.gif" alt="Disposition"></a>
						<a href="" id="add_media" class="thickbox" title="Recording"><img src="<? echo base_url();?>img/media-button-other.gif" alt="Recordings"></a>
						<a href="" id="add_media" class="thickbox" title="All"><img src="<? echo base_url();?>img/media-button-other.gif" alt="All"></a>
					</div>
					<br>
						<b>Conditions: </b>
					<div>
						<textarea name="conditional" id="conditional"  rows="3" cols="97" tabindex="2"></textarea>
					</div>
					<br>
					<br>
					<input name="save" id="save-post" class="button" tabindex="4" value="Submit" type="submit">
					<input value="Reset" class="button" type="reset">				
				</form>
			</div>
		</div>


	</div>

	<form name="post" action="" method="post" id="quick-search">		
	<div style="" id="advanced-search-link-wrap" class="hide-if-no-js screen-meta-toggle">		
		<input name="quick-search-input" id="quick-search-input" type="text" onclick="if(this.value == 'Quick Search...') this.value='';" onblur="if(this.value.length == 0) this.value='Quick Search...';" value="Quick Search..." name="s" />
	</form>			
		<a href="#advanced-search" id="advanced-search-link" title="Advanced Search" class="show-settings">
			<img src="<? echo base_url();?>img/fav-arrow.gif?ver=20100531" height="15px" width="18px" alt="Advanced Search"></img>
		</a>	
	</div>
		
<div class="update-nag">
	<a href="">GoAutoDial CE 3.1.3</a> is now available! Click <a href="">here</a> to update now!
</div>


		<div id="screen-meta-links">	
			<!--
			<div style="" id="contextual-help-link-wrap" class="hide-if-no-js screen-meta-toggle">
				<a href="#contextual-help" id="contextual-help-link" class="show-settings">Help</a>
			</div>
		-->

						<center><div id="screen-options-link-wrap" class="hide-if-no-js screen-meta-toggle">
				<a href="#screen-options" id="show-settings-link" class="show-settings">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
			</div>


		</div></center>


<div class="wrap">
<div id="icon-index" class="icon32">
	<br>
</div>
<h2><? echo $bannertitle; ?></h2>




	<div id="dashboard-widgets-wrap">
	</div>




</div><!-- wrap -->






<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->
</div><!-- wpwrap -->