<?php
############################################################################################
####  Name:             go_dnc.php                                                      ####
####  Type:             ci views                                                        ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Christopher Lomuntad <chris@goautodial.com>   ####
####  License:          AGPLv2                                                          ####
############################################################################################
############################################################################################
#### WARNING/NOTICE: PRODUCTION                                                         ####
#### Current SVN Production                                                             ####
############################################################################################
$base = base_url();
?>
<script src="<? echo $base; ?>js/jscolor/jscolor.js" type="text/javascript"></script>
<style type="text/css">
body, table, td, textarea, input, select, div, span{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:13px;
	font-stretch:normal;
}

#mainTable th, #statusesTable th {
	text-align:left;
}

/* Table Sorter */
table.tablesorter thead tr .header {
/*	background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/bg.gif);
	background-repeat: no-repeat;
	background-position: center right;*/
	cursor: pointer;
}

table.tablesorter .even {
	background-color: #EFFBEF;
}
table.tablesorter .odd {
	background-color: #E0F8E0;
}

/* Style for overlay and box */
#overlay{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:100;
}

#statusOverlay,#fileOverlay{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:102;
}

#box{
	position:absolute;
	top:-2550px;
	left:14%;
	right:14%;
	background-color: #FFF;
	color:#7F7F7F;
	padding:20px;

	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:101;
}

#statusBox,#fileBox{
	position:absolute;
	top:-2550px;
	left:30%;
	right:30%;
	background-color: #FFF;
	color:#7F7F7F;
	padding:20px;

	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:103;
}

#closebox, #statusClosebox, #fileClosebox{
	float:right;
	width:26px;
	height:26px;
	background:transparent url(<?php echo $base; ?>img/images/go_list/cancel.png) repeat top left;
	margin-top:-30px;
	margin-right:-30px;
	cursor:pointer;
}

#mainTable td{
	font-size:13px;
}

#list_within_campaign td {
	font-size:10px;
}

.advance_settings {
	background-color:#EFFBEF;
	display:none;
}

.menuOn{
	font-family:Verdana, Arial, Helvetica, sans-serif;
<?php
if (preg_match("/^Windows/",$userOS))
{
	echo "padding:8px 10px 8px 10px;";
}
else
{
	echo "padding:8px 10px 7px 10px;";
}
?>
	color:#777;
	font-size:11px;
	cursor:pointer;
	background-color:#FFF;
	border-right:#CCC 1px solid;
	color:#000;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.menuOff{
	font-family:Verdana, Arial, Helvetica, sans-serif;
<?php
if (preg_match("/^Windows/",$userOS))
{
	echo "padding:8px 10px 7px 10px;";
}
else
{
	echo "padding:8px 10px 6px 10px;";
}
?>
	color:#777;
	font-size:11px;
	cursor:pointer;
	background-color:#efefef;
	border-right:#CCC 1px solid;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.menuOff:hover{
	background-color:#dfdfdf;
	color:#000;
}

#account_info_status .table_campaigns {
	margin:9px 0px 0px -10px;
	padding:7px 10px 0px 9px;
	border-top:#CCC 1px solid;
	width:100%;
}

.go_action_menu{
	z-index:999;
	position:absolute;
	top:188px;
	border:#CCC 1px solid;
	background-color:#FFF;
	display:none;
	cursor:pointer;
}

#go_action_menu ul{
	list-style-type:none;
	padding: 1px;
	margin: 0px;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.go_action_submenu{
	padding: 3px 10px 3px 5px;
	margin: 0px;
}

.rightdiv{
	cursor:pointer;
	color:#555;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.hideSpan {
	display:none;
}

img.desaturate{
    filter: grayscale(100%); /* Current draft standard */
    -webkit-filter: grayscale(100%); /* New WebKit */
    -moz-filter: grayscale(100%);
    -ms-filter: grayscale(100%);
    -o-filter: grayscale(100%); /* Not yet supported in Gecko, Opera or IE */
    filter: url(<?php echo $base;?>img/resources.svg#desaturate); /* Gecko */
    filter: gray; /* IE */
    -webkit-filter: grayscale(1); /* Old WebKit */
}
</style>
<script>
$(function()
{
	$('#search_dnc').val('');
	$("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$("#dnc_placeholder").load("<?php echo $base;?>index.php/go_dnc_ce/go_search_dnc/");

	$('li.go_action_submenu').hover(function()
	{
		$(this).css('background-color','#ccc');
	},function()
	{
		$(this).css('background-color','#fff');
	});

	$('li.go_action_submenu').click(function () {
		var selectedNumber = [];
		$('input:checkbox[id="delDNC[]"]:checked').each(function()
		{
			selectedNumber.push($(this).val());
		});

		$('#go_action_menu').slideUp('fast');
		$('#go_action_menu').hide();
		toggleAction = $('#go_action_menu').css('display');

		var action = $(this).attr('id');
		if (selectedNumber.length<1)
		{
			alert('Please select a Phone Number.');
		}
		else
		{
			var s = '';
			if (selectedNumber.length>1)
				s = 's';

			if (action == 'delete')
			{
				var what = confirm('Are you sure you want to delete the selected Phone Number'+s+'?');
				if (what)
				{
					$.post('<? echo $base; ?>index.php/go_dnc_ce/go_delete_mass_dnc_number/'+selectedNumber+'/', function(data)
					{
						$("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
						$('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/');

						if (data)
						{
							submit_msg = 'deleted';
						} else {
							submit_msg = 'not deleted';
						}
						alert('Selected phone number(s) '+submit_msg+' from the DNC list.');
					});
				}
			}
// 			else
// 			{
// 				$.post('<? echo $base; ?>index.php/go_dnc_ce/go_delete_dnc_number/'+selectedNumber+'/');
// 			}
		}
	});

	// Pagination
	$('#mainTable').tablePagination({rowsPerPage: 15, optionsForRows: [15,25,50,100,"ALL"]});

	// Table Sorter
	$("#mainTable").tablesorter({sortList:[[1,0],[0,0]], headers: { 2: { sorter: false}, 3: {sorter: false} }, widgets: ['zebra']});

	$('#search_dnc').keyup(function()
	{
		var number = $(this).val();

		$('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/'+number);
	});

	$('#submit_dnc').click(function()
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '460px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'fixed'});
		$('#box').animate({
			top: "70px"
		}, 500);

		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_dnc_ce/go_submit_dnc/').fadeIn("slow");
	});

	$('#closebox').click(function()
	{
		$('#box').animate({'top':'-2550px'},500);
		$('#overlay').fadeOut('slow');
		$('#campaign_id').val('INTERNAL');
		$('#phone_numbers').val('');
	});
});

function delDNC(dnc_num)
{
	var what = confirm('Are you sure you want to delete the selected DNC number?');
	if (what)
	{
		var submit_msg = '';
		$.post('<? echo $base; ?>index.php/go_dnc_ce/go_delete_dnc_number/'+dnc_num, function(data)
		{
			var pnum = dnc_num.split('-');
			$("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/');

			if (data)
			{
				submit_msg = 'deleted';
			} else {
				submit_msg = 'not deleted';
			}
			alert('Phone number '+pnum[0]+' '+submit_msg+' from the DNC list.');
		});
	}
}
</script>
<div id='outbody' class="wrap">
<div id="icon-list" class="icon32">
</div>
<div style="float: right;margin-top:15px;margin-right:25px;">Search DNC Number: <input type="text" id="search_dnc" placeholder="Type a Number" size="15" /></div>
<h2><? echo $bannertitle; ?></h2>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

			<!-- START LEFT WIDGETS -->
			<div class="postbox-container" style="width:99%;min-width:1200px;">
				<div class="meta-box-sortables ui-sortable">

					<!-- GO REPORTS WIDGET -->
					<div id="account_info_status" class="postbox">
						<div class="rightdiv toolTip" id="submit_dnc" title="Add/Delete DNC Numbers">
                        	Add/Delete DNC Numbers <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<h3 class="hndle">
							<span><span id="title_bar" />DNC Number Listings</span><!-- Title Bar -->
								<span class="postbox-title-action"></span>
							</span>
						</h3>
						<div class="inside">

							<div class="table_dnc" style="margin-top:-15px;">
								<!--Search DNC Number: <input type="text" id="search_dnc" placeholder="Type a Number" size="15" />-->
								<br /><br style="font-size:8px;" />
								<div id="dnc_placeholder" style="border-top:1px #999 solid;">

								</div>
                            </div>

							<div class="widgets-content-text">
							<br class="clear">

								<br class="clear">
							<br class="clear">
							<!--
							<p>
								Configure this widget's (today's status) settings.
								<a href="" class="button">Configure</a>
							</p>
							<p>
								You are using GoAutoDial 3.0.1.
								<a href="" class="button">Update to 3.1.3</a>
							</p>
							-->
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- END LEFT WIDGETS -->

			<!-- START RIGHT WIDGETS -->
			<div class="postbox-container" style="width:49.5%;display:none;">
				<div class="meta-box-sortables ui-sortable">

				</div>
			</div>
			<!-- END RIGHT WIDGETS -->

			<div class="postbox-container" style="display:none;width:49.5%;">
				<div style="" id="column3-sortables" class="meta-box-sortables ui-sortable">
				</div>
			</div>
			<div class="postbox-container" style="display:none;width:49.5%;">
				<div style="" id="column4-sortables" class="meta-box-sortables ui-sortable">
				</div>
			</div>
		</div><!-- dashboard-widgets -->
	</div><!-- dashboard-widgets-wrap -->
</div><!-- wrap -->


							<?
echo "<div id='popup_analytics_all' class='popup_block'>\n";
?>

			<div class="table go_dashboard_analytics_in_popup_cmonth_daily" id="go_dashboard_analytics_in_popup_cmonth_daily">
			</div>

			<div class="table go_dashboard_analytics_in_popup_weekly" id="go_dashboard_analytics_in_popup_weekly">
			</div>

			<div class="table go_dashboard_analytics_in_popup_hourly" id="go_dashboard_analytics_in_popup_hourly">
			</div>

<?
echo "</div>\n";
?>

<!-- Overlay1 -->
<div id="overlay" style="display:none;"></div>
<div id="box">
<a id="closebox" class="toolTip" title="CLOSE"></a>
<div id="overlayContent"></div>
</div>

<!-- Overlay2 -->
<div id="statusOverlay" style="display:none;"></div>
<div id="statusBox">
<a id="statusClosebox" class="toolTip" title="CLOSE"></a>
<div id="statusOverlayContent"></div>
</div>

<!-- Action Menu -->
<div id='go_action_menu' class='go_action_menu'>
<ul>
<li class="go_action_submenu" title="Delete Selected" id="delete">Delete Selected</li>
</ul>
</div>

<!-- Debug-->
<div id="showDebug"></div>

<div id="hiddenToggle" style="visibility:hidden"></div>
<div id="wizardSpan" style="visibility:hidden">false</div>
<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->
</div><!-- wpwrap -->
