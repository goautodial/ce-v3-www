<?php
########################################################################################################
####  Name:             	go_dashboard.php                                                    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  Modified by:      	Franco E. Hora                                                      ####
####                    	Jerico James Milo                                                   ####
####                            Chris Lomuntad                                                      ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();

$hideDiv = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "display:none;" : "";
?>

<script>
$(function()
{

    $('#bargeIn').click(function()
    {
		var session_id = $('#session_id').val();
		var server_ip = $('#server_ip').val();
		var monitor = $('#monitor').val();
		var phone = $('#phone').val();

		if (phone!='')
		{
			$('#boxMonitor').animate({'top':'-2550px'},500);
			$('#overlayMonitor').fadeOut('slow');

			$.post('<?php echo $base; ?>index.php/go_campaign_ce/go_barged_in/'+monitor+'/'+session_id+'/'+phone+'/'+server_ip);
		}
		else
		{
			alert('Please enter an admin phone extension.');
		}
    });

	$('.postbox-container').resize(function()
	{
		$('#outbound_placeholder').width($(this).width() - 70);
		$('#inbound_placeholder').width($(this).width() - 70);
	});
	
    $('#closeboxRealTime').click(function()
    {
		$('#boxRealTime').animate({'top':'-550px'},500,function()
		{
		    // if ($(this).is(':visible'))
		    $(this).hide();
		});
		$('#overlayRealTime').fadeOut('slow');
		$("#realTimeRowsPerPage").html(25);
    });
	
	$('#callsTable a').click(function(e)
	{
		e.preventDefault();
	});

    $('.toolTip').tipTip();

	// Check Screen Size
	var minWidth = 1000;
	var curWidth = $(window).width();
	if ($(window).width() < minWidth)
	{
		$('.postbox-container').css('width','98%');
		$('#outbody').css('min-width','600px');
	} else {
		$('.postbox-container').css('width','49.5%');
		$('#outbody').css('min-width','1200px');
	}

	// Screen Resize
	$(window).resize(function()
	{
		if ($(window).width() != curWidth) {
			if ($(window).width() < minWidth)
			{
				$('.postbox-container').css('width','98%');
				$('#outbody').css('min-width','600px');
			} else {
				$('.postbox-container').css('width','49.5%');
				$('#outbody').css('min-width','1200px');
			}
			curWidth = $(window).width();
		}
	});

	$('#closebox').click(function()
	{
		$('.advance_settings').hide();
		$('#advance_link').html('[ + ADVANCE SETTINGS ]');
		$('#box').animate({'top':'-2550px'},500);
		$('#overlay').fadeOut('slow');
	});

	$('#statusClosebox').click(function()
	{
		$('#statusBox').animate({'top':'-2550px'},500);
		$('#statusOverlay').fadeOut('slow');
	});
	
	$('#signupClosebox').click(function()
	{
		$('#signupBox').animate({'top':'-2550px'},500);
		$('#signupOverlay').fadeOut('slow');
	});

	$('#saveUser').click(function() {
		var das_userID = $('#users_id').val();
		var das_userPass = $('#users_pass').val();
		var das_userFname = $('#users_full_name').val();
		var das_userPlogin = $('#users_phone_login').val();
		var das_userPpass = $('#users_phone_pass').val();
		var das_userStatus = $('#users_status').val();

		$.post('<? echo $base; ?>index.php/go_site/go_update_user_info',{user_id:das_userID,pass:das_userPass,full_name:das_userFname,phone_login:das_userPlogin,phone_pass:das_userPpass,active:das_userStatus},function(data,status) {
			alert(data);
			$('#boxAgent').animate({'top':'-550px'},500);
			$('#overlayAgent').fadeOut('slow');

			$("#table_logins").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
			$('#table_logins').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_account_get_logins/list_agents').fadeIn("slow");

			$("#table_phones").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
			$('#table_phones').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_account_get_logins/list_phones').fadeIn("slow");

		});
	});

});

function agentMonitoring()
{
    // alert(window.offset);
    // 	if ($(this).is(':hidden'))
    $('#boxRealTime').show();
    $('#boxRealTime').css({'width': '950px', 'margin-left': '50%', 'left': '-495px', 'padding-bottom': '20px'});
    $('#boxRealTime').animate({
	// top: Math.max(0, (($(window).height() - $('#boxRealTime').outerHeight()) / 2) + $(window).scrollTop()) + "px"
	top: "70px"
    }, 500);
    $('#overlayRealTime').fadeIn('fast');
	$("html, body").animate({ scrollTop: 0 }, 500);

    isExpanded = $('#isExpanded').html();
    orderByRealtime = $('#orderByRealtime').html();
	selectedCampaign = "ALL";
	selectedTenant = "ALL";
	$('#selectedCampaign').html(selectedCampaign);
	$('#selectedTenant').html(selectedTenant);
	$('#toggleMonitoring').text('agents');
	$('#monitor_camp_banner').show();
 	$('#overlayContentRealTime').html('<center><br /><br /><img src="<? echo $base; ?>img/goloading.gif" style="opacity:.65;" /></center>');
    $('#overlayContentRealTime').load('<? echo $base; ?>index.php/go_site/go_monitoring/'+isExpanded+'/'+orderByRealtime+'/agents/'+selectedCampaign+'/'+selectedTenant);
}

function callMonitoring()
{
    // alert(window.offset);
    // 	if ($(this).is(':hidden'))
    $('#boxRealTime').show();
    $('#boxRealTime').css({'width': '850px', 'margin-left': '50%', 'left': '-400px', 'padding-bottom': '20px'});
    $('#boxRealTime').animate({
	// top: Math.max(0, (($(window).height() - $('#boxRealTime').outerHeight()) / 2) + $(window).scrollTop()) + "px"
	top: "70px"
    }, 500);
	$("html, body").animate({ scrollTop: 0 }, 500);
    $('#overlayRealTime').fadeIn('fast');
	isExpanded = $('#isExpanded').html();
	orderByRealtime = $('#orderByRealtime').html();
	selectedCampaign = "ALL";
	$('#selectedCampaign').html(selectedCampaign);
	$('#toggleMonitoring').text('calls');
	$('#monitor_camp_banner').hide();
	$('#overlayContentRealTime').html('<center><img src="<? echo $base; ?>img/goloading.gif" style="opacity:.65;" /></center>');
	$('#overlayContentRealTime').load('<? echo $base; ?>index.php/go_site/go_monitoring/'+isExpanded+'/'+orderByRealtime+'/calls/'+selectedCampaign);
}

function droppedCalls()
{
	$('#overlay').fadeIn('fast');
	$('#box').css({'width': '860px', 'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
	$('#box').animate({
	top: "70px"
	}, 500);

	$("html, body").animate({ scrollTop: 0 }, 500);
	$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dropped_calls/').fadeIn("slow");
}

function getAgentInfo(das_userID)
{
    // alert(window.offset);
    // 	if ($(this).is(':hidden'))
    $('#boxAgent').show();
    $('#boxAgent').css({'width': '500px', 'margin-left': '50%', 'left': '-250px', 'padding-bottom': '20px'});
    $('#boxAgent').animate({
		//top: Math.max(0, (($(window).height() - $('#boxAgent').outerHeight()) / 2) + $(window).scrollTop()) + "px"
		top: "70px"
    }, 500);
	$("html, body").animate({ scrollTop: 0 }, 500);
    $('#overlayAgent').fadeIn('fast');
	$.post('<? echo $base; ?>index.php/go_site/go_get_user_info/'+das_userID, function(data,status) {
		var data_array = data.split('|');
		$('#users_id').val(data_array[0]);
		$('#users_id_span').text(data_array[0]);
		$('#users_pass').val(data_array[1]);
		$('#users_full_name').val(data_array[2]);
		$('#users_phone_login').val(data_array[3]);
		$('#users_phone_pass').val(data_array[4]);
		$('#users_status').val(data_array[5]);
	});
}

function updateOrder(order,type)
{
	$('#orderByRealtime').html(order);
	isExpanded = $('#isExpanded').html();
	selectedCampaign = $('#selectedCampaign').html();
	selectedTenant = $('#selectedTenant').html();
	if (type=='agents')
	{
		$('#monitor_camp_banner').show();
		$('#overlayContentRealTime').load('<? echo $base; ?>index.php/go_site/go_monitoring/'+isExpanded+'/'+order+'/agents/'+selectedCampaign+'/'+selectedTenant);
	} else {
		$('#monitor_camp_banner').hide();
		$('#overlayContentRealTime').load('<? echo $base; ?>index.php/go_site/go_monitoring/'+isExpanded+'/'+order+'/calls/'+selectedCampaign);
	}
}

function modify(camp)
{
	if ('<?php echo $campperms->campaign_update; ?>'!='N')
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '860px', 'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
		$('#box').animate({
		top: "70px"
		}, 500);
		$("html, body").animate({ scrollTop: 0 }, 500);
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_get_settings/'+camp).fadeIn("slow");
	} else {
		alert('Error: You do not have permission to modify a campaign.');
	}
}

function modifyServer(server,ip)
{
	$('#overlay').fadeIn('fast');
	$('#box').show();
	$('#box').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
	$('#box').animate({
		top: "70px"
	}, 500);
	$("html, body").animate({ scrollTop: 0 }, 500);
	$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_servers_ce/go_get_server/view/'+server+'/'+ip).fadeIn("slow");
}

function modifyListID(list_id)
{
	var isAdvance = $('#isAdvance').val();
	var campaign_id = $('#campaign_id').val();
	$('#statusOverlay').fadeIn('fast');
	$('#statusBox').css({'width': '660px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
	$('#statusBox').animate({
	top: "70px",
	left: "14%",
	right: "14%"
	}, 500);

	$('#statusOverlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_get_listid/'+list_id+'/'+isAdvance).fadeIn("slow");
}

function voipsignup()
{

        $('#signupOverlay').fadeIn('fast');
        $('#signupBox').css({'width': '770px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
        $('#signupBox').animate({
        top: "70px",
        left: "14%",
	right: "14%"
        }, 500);
	$('#signupoverlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_carriers_ce/sippywelcome').fadeIn("slow");
}

$(function()
{
    $.post(
            "<?=$base?>index.php/go_site/widgetconfigured",
            {user_id:$("input[name='account']").val()},
            function(data){

                //console.log(data);
                if(data.length > 0){
                    var $result = JSON.parse(data);
                    $.each($result,function($indx,$val){
                       if($indx == "left_html"){
                          for($i=0;$i<$val.length;$i++){
                               $("#left-widget").children().append($("#"+$val[$i]));
                          }
                       }
                       if($indx == "right_html"){
                          for($i=0;$i<$val.length;$i++){
                               $("#right-widget").children().append($("#"+$val[$i]));
                          }
                       }
                       if($indx !== "left_html" && $indx !== "right_html"){
			    var $wdgt = $indx;
			    if ($indx === "dashboard_clusters") {
				$wdgt = "dashboard_cluster_status";
			    }
                            if($val === "N"){
                                $("#"+$wdgt).hide();
                                $("input[id*='"+$indx+"']").attr("checked",false);
                            }else if($val === "PN"){
                                $("#"+$wdgt).hide();
				$("label[for*='"+$indx+"']").hide();
                                $("input[id*='"+$indx+"']").attr("checked",false).attr("disabled",true);
                            }
                       }
                    });
                }
            }
     );

     $.post(
              "<?=$base?>index.php/go_site/walk",
              {"compid":$("#compid").val()},
              function(data){
                  var $result = JSON.parse(data);
                  if($result[0].new_signup == "1"){ 
                       $("#overlay").css("display","block");
                       letsride();
                  }
              }
    );

});

function skipjoyride(){
    location.reload();
}

function letsride(){
      $('#joyRideTipContent').joyride({
          postStepCallback : function (index, tip) {
             if (index == 5) {
                 $(this).joyride('set_li', false, 1);
             }
          },
          postRideCallback : function(){
              $("#overlay").css("display","none");
          }
      });

}

function nextlog($nextlog){
        var $val = '0';
        if($($nextlog).prop('checked') === true){
             $val = '1';
        }
        var $data = {account:$("#compid").val(),newsignup:$val};
        $.post(
               "<?=$base?>index.php/go_site/walkupdate",
               $data
        );

}

function configwidget(){

    var $leftchild = "";
    var $rightchild = "";
    var $vars = null;
    $("#left-widget").children().children("div").each(function(){
         $leftchild += $(this).attr("id") + ",";
    });
    $leftchild = $leftchild.substr(0,($leftchild.length - 1));

    $("#right-widget").children().children().each(function(){
         $rightchild += $(this).attr("id") + ",";
    });
    $rightchild = $rightchild.substr(0,($rightchild.length - 1));

    $checkbox = [];
    $("input[type='checkbox']").each(function(){
        $checkbox[$(this).attr("name")] = $(this).prop("checked")==true?"Y":"N";
    });

    var $vars = $.extend({user_id:$("input[name='account']").val(),left_html:$leftchild,right_html:$rightchild},$checkbox);
    $.post(
            "<?=$base?>index.php/go_site/widgetconfig",
            $vars,
            function(data){
                if(data.length > 0){
                    var $result = JSON.parse(data);
                    $.each($result,function($indx,$val){
                       if($indx == "left_html"){

                          for($i=0;$i<$val.length;$i++){
                               $("#left-widget").children().append($("#"+$val[$i]));
                          }

                       }

                       if($indx == "right_html"){
                          for($i=0;$i<$val.length;$i++){
                               $("#right-widget").children().append($("#"+$val[$i]));
                          }
                       }

                       if($indx !== "user_id" && $indx !== "left_html" && $indx !== "right_html"){
                            //console.log($indx);
                       }

                    });
                }
            }
          );
}

$(function()
{
        $('#showPass').click(function()
        {
                if ($('.showPass').is(':hidden'))
                {
                        $('.showPass').show();
                        $('.hiddenPass').hide();
                        $(this).text('[hide]');
                        $('#shoAccntPass').text('1');
                }
                else
                {
                        $('.showPass').hide();
                        $('.hiddenPass').show();
                        $(this).text('[show]');
                        $('#shoAccntPass').text('0');
                }
        });



        if ($('#shoAccntPass').text() == '1')
        {
            $('.showPass').show();
            $('.hiddenPass').hide();
            $('#showPass').text('[hide]');
            $('#showPassvoip').text('[hide]');
        }
        else
        {
            $('.showPass').hide();
            $('.hiddenPass').show();
            $('#showPass').text('[show]');
            $('#showPassvoip').text('[show]');
        }

        $('.disableLink').click(function(e)
        {
                e.preventDefault();
        });
	
		$('.monitor_camp').change(function()
		{
			//console.log($(this).val());
			$('#selectedCampaign').html($(this).val());
			$('#selectedTenant').html($("#monitor_tenants").val());
			isExpanded = $('#isExpanded').html();
			orderByRealtime = $('#orderByRealtime').html();
			selectedCampaign = $(this).val();
			monitoringType = $('#toggleMonitoring').html();
			selectedTenant = $('#selectedTenant').html();
			//$('#overlayContentRealTime').html('<center><img src="<? echo $base; ?>img/goloading.gif" style="opacity:.65;" /></center>');
			$('#overlayContentRealTime').load('<? echo $base; ?>index.php/go_site/go_monitoring/'+isExpanded+'/'+orderByRealtime+'/'+monitoringType+'/'+selectedCampaign+'/'+selectedTenant);
		});
	
		$("#monitor_tenants").change(function()
		{
			var selectedTenant = $(this).val();
			$.post('<?php echo $base; ?>index.php/go_site/go_get_tenants/'+selectedTenant,function(data)
			{
				$("#monitor_agents").html(data);
				$('#selectedCampaign').html('ALL');
				$('#selectedTenant').html(selectedTenant);
				isExpanded = $('#isExpanded').html();
				orderByRealtime = $('#orderByRealtime').html();
				selectedCampaign = 'ALL';
				monitoringType = $('#toggleMonitoring').html();
				$('#overlayContentRealTime').load('<? echo $base; ?>index.php/go_site/go_monitoring/'+isExpanded+'/'+orderByRealtime+'/'+monitoringType+'/'+selectedCampaign+'/'+selectedTenant);
			});
		});
});

function sippyreload()
{    
    $("#sippydiv").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#sippydiv').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/sippyinfo').fadeIn("slow");   
}

function todaysreload()
{
    $("#table_sales").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#table_sales').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_sales_today').fadeIn("slow");

    $("#table_calls").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#table_calls').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_calls_today').fadeIn("slow");

    $("#table_drops").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#table_drops').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_drops_today').fadeIn("slow");
}

function agentsnleadsreload()
{
    $("#table_agents").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#table_agents').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_agents').fadeIn("slow");
    $("#table_leads").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#table_leads').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_leads').fadeIn("slow");
}

function serversreload()
{
    $("#table_vitals").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#table_vitals').fadeOut("slow").load('<? echo $base; ?>application/views/phpsysinfo/vitals.php').fadeIn("slow");
    $("#table_hardware").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#table_hardware').fadeOut("slow").load('<? echo $base; ?>application/views/phpsysinfo/hardware.php').fadeIn("slow");
    $("#table_memory").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#table_memory').fadeOut("slow").load('<? echo $base; ?>application/views/phpsysinfo/memory.php').fadeIn("slow");
    $("#table_filesystems").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#table_filesystems').fadeOut("slow").load('<? echo $base; ?>application/views/phpsysinfo/filesystems.php').fadeIn("slow");
}

function systemsreload()
{
    $("#asterisk_status").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $("#mysql_status").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $("#httpd_status").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $("#nic_status").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $("#ftp_status").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $("#ssh_status").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
}

function clustersreload()
{
    $("#table_clusters").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#table_clusters').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_cluster_status').fadeIn("slow");
}

function analyticsreload()
{
    $("#table_analytics_in").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#table_analytics_in').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_in').fadeIn("slow");
    $("#table_analytics_out").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#table_analytics_out').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_out').fadeIn("slow");
}

function newsreload()
{
    $("#rss_widget").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#rss_widget').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_rssview').fadeIn("slow");
}

function communityreload()
{
    $("#rss_widget_others").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
    $('#rss_widget_others').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_rssview_others').fadeIn("slow");
}

function agentsnphonesreload()
{
	$("#table_num_seats").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
	$('#table_num_seats').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_account_get_num_seats').fadeIn("slow");

	$("#table_url_resources").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
	$('#table_url_resources').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_account_get_urls').fadeIn("slow");

	$("#table_logins").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
	$('#table_logins').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_account_get_logins/list_agents').fadeIn("slow");

	$("#table_phones").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
	$('#table_phones').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_account_get_logins/list_phones').fadeIn("slow");
}

</script>

<style type="text/css">


.joyride-content-wrapper a{cursor:pointer;}
.joyride-content-wrapper a:hover {font-weight:bold;}

#showPass:hover{
        color:red;
}
#showPassvoip:hover{
        color:red;
}
#showPassvoiphide:hover{
        color:red;
}
</style>

<style type="text/css">
/* Style for overlay and box */
#overlayRealTime{
	background:transparent url(<?php echo $base; ?>img/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:100;
}

#boxRealTime{
	position:absolute;
	top:-850px;
	background-color: rgba(255,255,255,1);
	color:#7F7F7F;
	padding:20px;

	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:101;
}

#closeboxRealTime{
	float:right;
	width:26px;
	height:26px;
	background:transparent url(<?php echo $base; ?>img/cancel.png) repeat top left;
	margin-top:-30px;
	margin-right:-30px;
	cursor:pointer;
}


#overlay{
	background:transparent url(<?php echo $base; ?>img/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:100;
}

#statusOverlay,#fileOverlay,#hopperOverlay{
	background:transparent url(<?php echo $base; ?>img/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:102;
}

#signupOverlay{
        background:transparent url(<?php echo $base; ?>img/overlay.png) repeat top left;
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

#statusBox,#fileBox,#hopperBox{
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

#signupBox{
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


#closebox, #statusClosebox, #fileClosebox, #hopperClosebox{
	float:right;
	width:26px;
	height:26px;
	background:transparent url(<?php echo $base; ?>img/cancel.png) repeat top left;
	margin-top:-30px;
	margin-right:-30px;
	cursor:pointer;
}

#signupClosebox{
        float:right;
        width:26px;
        height:26px;
        background:transparent url(<?php echo $base; ?>img/cancel.png) repeat top left;
        margin-top:-30px;
        margin-right:-30px;
        cursor:pointer;
}


.advance_settings {
	background-color:#EFFBEF;
	display:none;
}

.hideSpan {
	display:none;
}

#list_within_campaign td {
	font-size:10px;
}
</style>

<a id="topPage" style="display: none;"></a>
<div id='outbody' class="wrap" style="min-width:1200px;">
<?

 #$this->commonhelper->getPermissions("dashboard",$this->session->userdata("user_group"));

echo form_hidden('account',$account);
echo "<input type='hidden' value='$account' id='compid' name='compid'>";
?>
    <ol id="joyRideTipContent">
      <li data-text="Next">
        <h2>Welcome to GOadmin!</h2>
        <p>
           This walk through will help you navigate the system easily or <a id="skipjoyride" onclick="skipjoyride()">Skip</a> this walk through.
        </p>
      </li>
      <li data-class="wp-menu-image" data-button="Next" class="custom" data-options="tipLocation:left;">
        <h2>Application Menu</h2>
        <p>Hover mouse to see details</p>
      </li>
      <li data-id="payWithPayPalbalance" data-class="table-inside" data-button="Next" data-options="tipLocation:top;">
        <h2>Load Credit(s)</h2>
        <p>Click <a target="_new" href=" http://goautodial.org/projects/goautodialce/wiki/Hosted_Howto_Load_Credits_30">here</a> for how to load credit</p>
      </li> 
      <li data-id="table_agents" data-button="Next" data-options="tipLocation:right">
        <h2>Monitor/Barge</h2>
        <p>Click <a target="_new" href="http://goautodial.org/projects/goautodialce/wiki/HowTo_Monitor_and_Barge_30">here</a> to monitor or barge live agent(s)</p>
      </li>
      <li data-id="callsTable" data-button="Next" data-options="tipLocation:left">
        <h2>Active Calls</h2>
        <p>Click to show active calls being placed</p>
      </li>
      <!-- <li data-id="phones_list" data-button="Next" data-options="tipLocation:left">
        <h2>Phones</h2>
        <p>Click <a href=" http://goautodial.org/projects/goautodialce/wiki/Hosted_Howto_Configure_Softphone" target="_new">here</a> for how to configure your softphones</p>
      </li> -->
      <li data-button="Close">
        <h2>That's it!</h2>
        <p>To get started ASAP please go over our tutorials here:
           <a href="<http://goautodial.org/projects/goautodialce/wiki>" target="_new">Tutorials</a><br/>
           <input type="checkbox" id="nextlog" name="nextlog" onclick="nextlog(this)"> Show this introduction help again next login?
        </p>
      </li>

      <!-- <li data-id="numero5" data-button="Close">
        <h2>Stop #5</h2>
        <p>Now what are you waiting for?!</p>
      </li> -->
    </ol>
<div id="icon-dashboard" class="icon32">
</div>
<h2><? echo $bannertitle; ?></h2>
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

			<!-- START LEFT WIDGETS -->
			<div id="left-widget" class="postbox-container" style="width:49.5%;">
				<div class="meta-box-sortables ui-sortable">

					<!-- TODAY'S STATUS WIDGET -->
					<div id="dashboard_todays_status" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
		    				<div class="actiondiv" title="Refresh this widget" onclick='javascript:todaysreload()'><br></div>
		    				<!-- <div class="widgetconfig" title="switch"><a id="switch-to-inbound">Inbd</a></div> -->
                                                <h3 class="hndle">
							<span class="tdstats">Today's Status
								<span class="postbox-title-action">
									<!--<a href="?edit=dashboard_server_statistics#dashboard_server_statistics" class="edit-box open-box">Configure</a>-->
								</span>
							</span>
						</h3>
						<div class="inside">


							<div class="table table_sales" id="table_sales">
							</div>

							<div class="table table_calls" id="table_calls">
							</div>

							<div class="table table_drops" id="table_drops">
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

					<!-- SIPPY INFO -->
					<?php
					#if($count_sippyinfo > 0) {
					?>
                                        <div id="account_info_status" class="postbox">
                                                <div class="handlediv" title="Click to toggle"><br></div>
		    				<div class="actiondiv" title="Refresh this widget" onclick='javascript:sippyreload()'><br></div>
                                                <h3 class="hndle">
                                                        <span class="tdstats">Account Information
                                                                <span class="postbox-title-action">
                                                                </span>
                                                        </span>
                                                </h3>
                                                <div class="inside">


                                                        <div class="table-inside" id="balance">&nbsp;</div><div class="table-inside">&nbsp;</div>
                                                        <div id="sippydiv">
                                                        
																																																								</div>
																																																																																																														
																																																									
                                                        <div class="widgets-content-text">
                                                        <br class="clear">
                                                                <br class="clear">
                                                        <br class="clear">
                                                        </div>
                                                </div>
                                        </div>
				

					<!-- GO ANALYTICS -->
					<div id="dashboard_analytics" class="postbox ">
						<div class="handlediv" title="Click to toggle"><br></div>
		    				<div class="actiondiv" title="Refresh this widget" onclick='javascript:analyticsreload()'><br></div>
                                                <h3 class="hndle">
							<span class="tdstats">GO Analytics
								<span class="postbox-title-action">
									<!--<a href="?edit=dashboard_server_statistics#dashboard_server_statistics" class="edit-box open-box">Configure</a>-->
								</span>
							</span>
						</h3>


						<div class="inside">


							<div class="table table_analytics_out" id="table_analytics_out">
							</div>




						<br><br><br>



							<a href='#?w=480' rel='popup_analytics_all' class='poplight'>
								<div class="table table_analytics_in" id="table_analytics_in"></div>
							</a>


							<div class="widgets-content-text">
								<span>
<!--									<p>GO Analytics is basically a projection of your production performance based on your current server statistics, agent performance, leads quality, etc.</p>
									<p><a href="">Learn more...</a></p>-->
								</span>
							</div>
						</div>
					</div>


					<!-- OTHER NEWS -->
					<div id="dashboard_goautodial_forum" class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
		    				<div class="actiondiv" title="Refresh this widget" onclick='javascript:communityreload()'><br></div>
                                                <h3 class="hndle">
							<span class="tdstats">GoAutoDial Community & Forum
								<span class="postbox-title-action">
									<!--<a href="?edit=dashboard_goautodial_forum#dashboard_goautodial_forum" class="edit-box open-box">Configure</a>-->
								</span>
							</span>
						</h3>
						<div class="inside" style="overflow:hidden;">
							<div class="rss_widget_others" id="rss_widget_others"></div>

							<p>
								<script src="https://connect.facebook.net/en_US/all.js#appId=221761344519120&amp;xfbml=1"></script>
							</p>
							<p>
								<div id="fbookalign" class="block facebookLikeBox"></div>
							</p>
							<p>
								<div id="fb-root">&nbsp;</div>
							</p>
							<p>
								<script src="https://connect.facebook.net/en_US/all.js#xfbml=1"></script>
							</p>
							<p>
								<script type="text/javascript">
								function adjustStyle(width) {
									width = parseInt(width);
									if (width > 1180) {
										$('#sidebar .tip').addClass('vertical');
										if (!$.browser.msie) {
										$('.facebookLikeBox').html('<fb:like-box allowtransparency="false" align="center" font="verdana" href="http://www.facebook.com/pages/GoAutoDial-Inc/107034119379173"  send="false" width="520" show_faces="true" stream="false" header="true"></fb:like-box>');
										FB.XFBML.parse();
										}
									}
									else  if (width > 100) {
										$('#sidebar .tip').removeClass('vertical');
										if (!$.browser.msie) {
										$('.facebookLikeBox').html('<fb:like-box allowtransparency="false" font="verdana" href="http://www.facebook.com/pages/GoAutoDial-Inc/107034119379173"  send="false" width="400" show_faces="true" stream="false" header="true"></fb:like-box>');
										FB.XFBML.parse();
										}
									}
								}
								$(function() {
									adjustStyle($(this).width());
									$(window).resize(function() {
										if ($('#fbResize').html() != $(this).width())
										{
											adjustStyle($(this).width());
											$('#fbResize').html($(this).width());
										}
									});
								});
								</script>
							</p>
						</div>
					</div>




				</div>
			</div>
			<!-- END LEFT WIDGETS -->

			<!-- START RIGHT WIDGETS -->
			<div id="right-widget" class="postbox-container" style="width:49.5%;">
				<div class="meta-box-sortables ui-sortable">

					<!-- Agents & Leads Status -->
					<div id="dashboard_agents_status" class="postbox ">
						<div class="handlediv" title="Click to toggle"><br></div>
		    				<div class="actiondiv" title="Refresh this widget" onclick='javascript:agentsnleadsreload()'><br></div>
                                                <h3 class="hndle">
							<span class="tdstats">Agents & Leads Status
								<span class="postbox-title-action">
									<!--<a href="?edit=dashboard_agents_status#dashboard_agents_status" class="edit-box open-box">Configure</a>-->
								</span>
							</span>
						</h3>
						<div class="inside">
							<div class="table table_agents" id="table_agents">
							</div>

							<div class="table table_leads" id="table_leads">
							</div>

							<div class="widgets-content-text">
							<br class="clear">
							<br class="clear">

							<br class="clear">
							</div>
						</div>
					</div>

					

					<!-- Agents & Phones Info -->
					<div id="agents_phones_logins" class="postbox ">
						<div class="handlediv" title="Click to toggle"><br></div>
		    			<div class="actiondiv" title="Refresh this widget" onclick='javascript:agentsnphonesreload()'><br></div>
						<h3 class="hndle">
							<span class="tdstats">Agents & Phones
								<span class="postbox-title-action">
									<!--<a href="?edit=dashboard_phone_logins#dashboard_phone_logins" class="edit-box open-box">Configure</a>-->
								</span>
							</span>
						</h3>
						<div class="inside">
							<div class="table table_num_seats" id="table_num_seats">
							</div>


							<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

							<div class="table table_url_resources" id="table_url_resources">
							</div>


							<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
                                                        <div class="table-inside">&nbsp;</div><div class="table-inside" id="phone_details">&nbsp;</div>
							<div class="table table_logins" id="table_logins">
							</div>

							<div class="table table_phones" id="table_phones">
							</div>


							<div class="widgets-content-text">
							<br class="clear">
							<br class="clear">

							<br class="clear">
							</div>
						</div>
					</div>



					<!-- CLUSTER STATUS -->
					<div id="dashboard_cluster_status" class="postbox " style="<?=$hideDiv ?>">
						<div class="handlediv" title="Click to toggle"><br></div>
		    				<div class="actiondiv" title="Refresh this widget" onclick='javascript:clustersreload()'><br></div>
                                                <h3 class="hndle">
							<span class="tdstats">Cluster Status
								<span class="postbox-title-action">
									<!--<a href="?edit=dashboard_server_statistics#dashboard_server_statistics" class="edit-box open-box">Configure</a>-->
								</span>
							</span>
						</h3>
						<div class="inside">

							<div class="sub">Cluster Status</div>
							<div class="separate"></div>
							<div class="table table_clusters" id="table_clusters"></div>

						</div>
					</div>



					<!-- SERVER'S STATISTICS -->
					<div id="dashboard_server_statistics" class="postbox " style="<?=$hideDiv ?>">
						<div class="handlediv" title="Click to toggle"><br></div>
		    				<div class="actiondiv" title="Refresh this widget" onclick='javascript:serversreload()'><br></div>
                                                <h3 class="hndle">
							<span class="tdstats">Server Statistics
								<span class="postbox-title-action">
									<!--<a href="?edit=dashboard_server_statistics#dashboard_server_statistics" class="edit-box open-box">Configure</a>-->
								</span>
							</span>
						</h3>
						<div class="inside">

							<div class="sub">System Vitals</div>
							<div class="separate"></div>
							<div class="table table_vitals" id="table_vitals"></div>

							<div class="sub">Hardware</div>
							<div class="separate"></div>
							<div class="table table_hardware" id="table_hardware"></div>

							<div class="sub">Memory Usage</div>
							<div class="separate"></div>
							<div class="table table_memory" id="table_memory"></div>

							<div class="sub">Filesystems</div>
							<div class="separate"></div>
							<div class="table table_filesystems" id="table_filesystems"></div>




						</div>
					</div>

                                        <!-- Control Panel-->
					<div id="dashboard_controls" class="postbox " style="<?=$hideDiv ?>">
						<div class="handlediv" title="Click to toggle"><br></div>
		    				<div class="actiondiv" title="Refresh this widget" onclick='javascript:systemsreload()'><br></div>
                                                <h3 class="hndle">
							<span class="tdstats">System Services
								<span class="postbox-title-action">
									<!--<a href="?edit=dashboard_agents_status#dashboard_agents_status" class="edit-box open-box">Configure</a>-->
								</span>
							</span>
						</h3>
						<div class="inside">

							<table style="width:100%" cellpadding=0 cellspacing=0>
								<tr style="font-weight:bold"><td>SERVICE</td><td>STATUS</td><td style="width:10%;">ACTION</td></tr>
								<tr style="background-color:#E0F8E0">
									<td style="padding:5px 0 5px 20px;width:50%;border-top:#D0D0D0 dashed 1px;">Asterisk ( Telephony )</td>
									<td style="border-top:#D0D0D0 dashed 1px;padding-right:5px;" colspan="2"><div id="asterisk_status" style="font-weight:bold;line-height: 26px;">&nbsp;</div></td>
								</tr>
								<tr style="background-color:#EFFBEF">
									<td style="padding:5px 0 5px 20px;width:50%;border-top:#D0D0D0 dashed 1px;">MySQL ( Database )</td>
									<td style="border-top:#D0D0D0 dashed 1px;padding-right:5px;" colspan="2"><div id="mysql_status" style="font-weight:bold;line-height: 26px;">&nbsp;</div></td>
								</tr>
								<tr style="background-color:#E0F8E0">
									<td style="padding:5px 0 5px 20px;width:50%;border-top:#D0D0D0 dashed 1px;">HTTP ( Web )</td>
									<td style="border-top:#D0D0D0 dashed 1px;padding-right:5px;" colspan="2"><div id="httpd_status" style="font-weight:bold;line-height: 26px;">&nbsp;</div></td>
								</tr>
								<tr style="background-color:#EFFBEF">
									<td style="padding:5px 0 5px 20px;width:50%;border-top:#D0D0D0 dashed 1px;">Network ( NIC )</td>
									<td style="border-top:#D0D0D0 dashed 1px;padding-right:5px;" colspan="2"><div id="nic_status" style="font-weight:bold;line-height: 26px;">&nbsp;</div></td>
								</tr>
								<tr style="background-color:#E0F8E0">
									<td style="padding:5px 0 5px 20px;width:50%;border-top:#D0D0D0 dashed 1px;">FTP ( File Server )</td>
									<td style="border-top:#D0D0D0 dashed 1px;padding-right:5px;" colspan="2"><div id="ftp_status" style="font-weight:bold;line-height: 26px;">&nbsp;</div></td>
								</tr>
								<tr style="background-color:#EFFBEF">
									<td style="padding:5px 0 5px 20px;width:50%;border-top:#D0D0D0 dashed 1px;">SSH</td>
									<td style="border-top:#D0D0D0 dashed 1px;padding-right:5px;" colspan="2"><div id="ssh_status" style="font-weight:bold;line-height: 26px;">&nbsp;</div></td>
								</tr>
							</table>
						</div>
					</div>



					<!-- GOAUTODIAL NEWS -->
					<div id="dashboard_goautodial_news" class="postbox ">
						<div class="handlediv" title="Click to toggle"><br></div>
		    				<div class="actiondiv" title="Refresh this widget" onclick='javascript:newsreload()'><br></div>
                                                <h3 class="hndle">
							<span class="tdstats">GoAutoDial News
								<span class="postbox-title-action">
									<!--<a href="" class="edit-box open-box">Configure</a>-->
								</span>
							</span>
						</h3>
						<div class="inside">
							<div class="rss_widget" id="rss_widget">
							</div>

						</div>
					</div>



					<!-- PLUGINS -->
					<div id="dashboard_plugins" class="postbox " style="display:none;">
						<div class="handlediv" title="Click to toggle">
							<br>
						</div>
						<h3 class="hndle">
							<span>Plugins</span>
						</h3>
						<div class="inside">

							<h4>Most Popular</h4>
							<h5>
								<a href="">GO Widgets Customizer</a>
							</h5>
							<span>
								(<a href="" class="thickbox" title="GO Widgets Customizer">INSTALL NOW!</a>)
							</span>
							<p>
								Customize your own GoAutoDial dashboard widgets!
								<br><a href="" class="" title="Learn More">Learn more...</a>
							</p>

							<h4>Newest Plugins</h4>
							<h5>
								<a href="">GO E-mailer</a>
							</h5>
							<span>
								(<a href="" class="thickbox" title="GO Emailer">INSTALL NOW!</a>)
							</span>
							<p>
								This plugin gives your agents the ability to send pre-defined electronic mails to the customers/callers!
								<br><a href="" class="" title="Learn More">Learn more...</a>
							</p>

							<h4>Recently Updated</h4>
							<h5>
								<a href="">GO Streaming</a>
							</h5>
							<span>
								(<a href="" class="thickbox" title="GO Streaming">UPDATE NOW!</a>)
							</span>
							<p>
								GO Streaming has recently updated its core structures to support different types of streaming mediums such as
								MP3's, WAV's, Shoutcast Radio, I-Tunes Radio and a lot more!
								<br><a href="" class="" title="Learn More">Learn more...</a>
							</p>
						</div>
					</div>
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

<!-- Signup Overlay-->
<div id="signupOverlay" style="display:none;"></div>
<div id="signupBox">
<a id="signupClosebox" class="toolTip" title="CLOSE"></a>
<div id="signupoverlayContent"></div>
</div>
<!-- end signup -->


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

<!-- Overlay for Agent Monitoring -->
<?php
$camp_array['ALL'] = "--- All Campaign ---";
foreach ($campaign_ids as $camp)
{
	$camp_array[$camp->campaign_id] = "{$camp->campaign_name}";
}

if (count($tenant_ids) > 0) {
	$tenant_array['ALL'] = "--- All User Groups ---";
	foreach ($tenant_ids as $id => $tenant)
	{
		$tenant_array[$id] = "{$tenant}";
	}
}
?>
<div id="overlayRealTime" style="display:none;z-index:97;"></div>
<div id="boxRealTime" style="display:none;z-index:98;">
<a id="closeboxRealTime" class="toolTip" title="CLOSE"></a>
<div id="monitor_camp_banner" style="float: right;font-size: 11px;">Filter: <?=(count($tenant_ids) > 0) ? form_dropdown('monitor_tenants',$tenant_array,'ALL','id="monitor_tenants" class="monitor_tenants" style="font-size: 11px;"') : "" ?> <?=form_dropdown('monitor_agents',$camp_array,$selected_campaign,'id="monitor_agents" class="monitor_camp" style="font-size: 11px;"') ?></div>
<div id="overlayContentRealTime">
</div>
<span id="realTimeRowsPerPage" style="display:none">25</span>
</div>
<!-- Overlay for Agent Monitoring -->

<!-- Hopper Overlay -->
<div id="hopperOverlay" style="display:none;"></div>
<div id="hopperBox">
<a id="hopperClosebox" class="toolTip" title="CLOSE"></a>
<div id="hopperOverlayContent"></div>
</div>

<!-- Overlay for Barging/Listening -->
<div id="overlayMonitor" style="display:none;z-index:99;"></div>
<div id="boxMonitor" style="display:none;z-index:100;">
<a id="closeboxMonitor" class="toolTip" title="CLOSE"></a>
<div id="overlayContentMonitor">
<div style="text-align:center;font-weight:bold;font-size:14px;">BLIND MONITORING</div>
<br />
<input type="hidden" id="server_ip">
<input type="hidden" id="session_id">
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:100%;">
    <tr>
    	<td style="text-align:right;font-weight:bold;width:40%" nowrap>Agent:</td><td style="white-space:nowrap;">&nbsp;<span id="agent"></span></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Monitor:</td><td><select id="monitor" name="monitor"><option>LISTEN</option><option>BARGE</option></select></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Admin Phone:</td><td><input type="text" id="phone" name="phone" value="<?php echo $phone_login; ?>" size="10" maxlength="15" /><!--<select id="phone" name="phone"><option><? echo $phone_login; ?></option></select>--></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>&nbsp;</td><td><input type="button" id="bargeIn" name="bargeIn" value="SUBMIT" style="cursor:pointer;" /></td>
    </tr>
</table>
<span id="isExpanded" style="display:none;">0</span>
<span id="orderByRealtime" style="display:none;">timeup</span>
<span id="realtimeInterval" style="display:none;">5</span>
<span id="toggleMonitoring" style="display:none;">agents</span>
<span id="selectedCampaign" style="display:none;">ALL</span>
<span id="selectedTenant" style="display:none;">ALL</span>
</div>
</div>
<!-- Overlay for Barging/Listening -->

<span id="fbResize" style="display: none"></span>
<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->
</div><!-- wpwrap -->
