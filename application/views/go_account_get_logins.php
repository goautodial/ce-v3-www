<?php
####################################################################################################
####  Name:             	go_account_get_logins.php                                           ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:           Chris Lomuntad                                                      ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();

if ($status=="num_seats")
{
?>
<script>
$(function()
{
	$('.disableLink').click(function(e)
	{
		e.preventDefault();
	});
});
</script>
<p class="sub">Agents</p>
<table>
	<tbody>
	<tr class="first">
			<td class="b disableLink"><a class="" style="cursor:pointer"><? echo $num_seats; ?></a></td>
			<td class="t bold disableLink"><a class="" style="cursor:pointer">Number of Agent(s)</a></td>
	</tr>
	</tbody>
</table>
<?
}

if ($status=="url_resources")
{
?>
<script>
$(function()
{
	$('.disableLink').click(function(e)
	{
		e.preventDefault();
	});
});
</script>
<p class="sub">URL Resources</p>
<table>
	<tbody>
	<tr class="first">
			<td class="t pbold disableLink"><a class="" style="cursor:pointer">Agent Login URL:</a>&nbsp;</td>
			<td class="t"><a class="" href="https://<?=$_SERVER[HTTP_HOST]?>/agent/agent.php" target="_new">https://<?=$_SERVER[HTTP_HOST]?>/agent/agent.php</a></td>
	</tr>
	<tr>
			<td class="t pbold disableLink"><a class="" style="cursor:pointer">SIP/Server Domain:</a>&nbsp;</td>
			<td class="t disableLink"><a class="" style="cursor:pointer"><?=$_SERVER[HTTP_HOST]?></a></td>
	</tr>
	</tbody>
</table>
<?
}

if ($status=="list_agents")
{
?>
<script>
$(function()
{
	$('#showAgentPass').click(function()
	{
		if ($(this).text() == '[show]')
		{
			$('.showAgentPass').show();
			$('.hiddenAgentPass').hide();
			$(this).text('[hide]');
		}
		else
		{
			$('.showAgentPass').hide();
			$('.hiddenAgentPass').show();
			$(this).text('[show]');
		}
	});

	$('.disableLink').click(function(e)
	{
		e.preventDefault();
	});
	
    $('#closeboxAgent').click(function()
    {
		$('#boxAgent').animate({'top':'-550px'},500);
		$('#overlayAgent').fadeOut('slow');
    });
	
	$('.toolTip').tipTip();
});
</script>
<style type="text/css">
#showAgentPass:hover{
	color:red;
}
</style>
<p class="sub">Agents</p>
<table id='agents_list'>
	<tbody>
	<tr class="first">
			<td class="f bold disableLink"><a class="" style="cursor:pointer">Name</a></td>
			<td class="f bold disableLink"><a class="" style="cursor:pointer">Password</a> <span id="showAgentPass" class="toolTip" title="Show/Hide Passwords" style="cursor:pointer;font-size:10px;">[show]</span></td>
	</tr>
<?
if ($go_get_datacount>0) {	
	$i=0;
	foreach($go_get_dataval as $item):
	$i++;

		if($i > 10) {
			echo '<tr id="trid" class="first" style="display: none">';
		}else{
			echo '<tr class="first">';
		}

		if($item->active == "N") {
			$isActiveAgent = 'color:#777;font-style:italic;';
		} else {
			$isActiveAgent = '';
		}
		echo '		<td class="f disableLink"><a class="toolTip" style="cursor:pointer;'.$isActiveAgent.'" onclick="getAgentInfo(\''.$item->user.'\')" title="'.$item->full_name.'<br /><br style=\'font-size:5px;\' />'.$item->user.'<br /><br style=\'font-size:5px;\' />ACTIVE: '.$item->active.'">'.substr($item->full_name,0,16).'</a></td>';
		echo '		<td class="f disableLink"><a class="" style="cursor:pointer;'.$isActiveAgent.'"><span class="hiddenAgentPass">**********</span><span class="showAgentPass" style="display:none;">'.$item->pass.'</span></a></td>';
		echo '</tr>';
				
	endforeach;
}
?>
	</tbody>
</table>
<?
}

if ($status=="list_phones")
{
?>
<script>
$(function()
{
	$('#showPhonePass').click(function()
	{
		if ($(this).text() == '[show]')
		{
			$('.showPhonePass').show();
			$('.hiddenPhonePass').hide();
			$(this).text('[hide]');
		}
		else
		{
			$('.showPhonePass').hide();
			$('.hiddenPhonePass').show();
			$(this).text('[show]');
		}
	});

	$('.disableLink').click(function(e)
	{
		e.preventDefault();
	});
});
</script>
<style type="text/css">
#showPhonePass:hover{
	color:red;
}
</style>
<p class="sub">Phones</p>
<table id='phones_list'>
	<tbody>
	<tr class="first">
			<td class="f bold disableLink"><a class="" style="cursor:pointer">Login</a></td>
			<td class="f bold disableLink"><a class="" style="cursor:pointer">Password</a> <span id="showPhonePass" class="toolTip" title="Show/Hide Passwords" style="cursor:pointer;font-size:10px;">[show]</span></td>
	</tr>
<?
if ($go_get_datacount>0) {
	$i=0;
	foreach($go_get_dataval as $item):
	$i++;

		if($i > 10) {
			echo '<tr id="trid" class="first" style="display: none">';
		}else{
			echo '<tr class="first">';
		}

		if($item->active == "N") {
			$isActiveAgent = 'color:#777;font-style:italic;';
		} else {
			$isActiveAgent = '';
		}

		echo '		<td class="f disableLink"><a class="" style="cursor:pointer;'.$isActiveAgent.'">'.$item->login.'</a></td>';
		echo '		<td class="f disableLink"><a class="" style="cursor:pointer;'.$isActiveAgent.'"><span class="hiddenPhonePass">**********</span><span class="showPhonePass" style="display:none;">'.$item->pass.'</span></a></td>';
		echo '</tr>';
				
	endforeach;
}

if ($go_get_datacount > 0) {
?>
<table>
	<tbody>
		<br>

		<tr>

		 <td class='dp'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</td>
		 <td class='showhide'>
		 <a id='showmemore_logins' class='showmore' onclick='javascript:showmemorelogins()'>&nbsp;&raquo; Click here to show more... </a>
		 <a style='display: none' id='hidemore_logins' class='hidemore'>&nbsp;&raquo; Click here to hide... </a>

		 </td>
		</tr>
	</tbody>
</table>
<?
}
?>

<script>

    $("#showmore_logins").click(function () {
    $("#agents_list #trid").show("slow");
    $("#phones_list #trid").show("slow");
    $("#hidemore_logins").show("slow");
    $("#showmore_logins").hide("slow");
    
    });
    
    
    $("#hidemore_logins").click(function () {
    $("#agents_list #trid").hide("slow");
    $("#phones_list #trid").hide("slow");
    $("#showmore_logins").show("slow");
    $("#hidemore_logins").hide("slow");
    
    });
    
	function showmemorelogins()
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '850px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
		$('#box').animate({
			top: "70px",
			left: "14%",
			right: "14%"
		}, 500);
		$("html, body").animate({ scrollTop: 0 }, 500);
		$("#overlayContent").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_site/show_me_more/logins/').fadeIn("slow");
	}
    </script>
	</tbody>
</table>
<?
}
?>