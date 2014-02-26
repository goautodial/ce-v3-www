<?php
# 
# functions.php    version 2.4
#
# functions for administrative scripts and reports
#
# Copyright (C) 2012  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
#
# CHANGES:
# 90524-1503 - First Build
# 110708-1723 - Added HF precision option
# 111222-2124 - Added max stats bar chart function
# 120125-1235 - Small changes to max stats function to allow for total system stats
# 120213-1417 - Changes to allow for ra stats
# 120713-2137 - Added download function for max stats
#

##### reformat seconds into HH:MM:SS or MM:SS #####
function sec_convert($sec,$precision)
	{
	$sec = round($sec,0);

	if ($sec < 1)
		{
		if ($precision == 'HF')
			{return "0:00:00";}
		else
			{return "0:00";}
		}
	else
		{
		if ($precision == 'HF')
			{$precision='H';}
		else
			{
			if ( ($sec < 3600) and ($precision != 'S') ) {$precision='M';}
			}

		if ($precision == 'H')
			{
			$Fhours_H =	($sec / 3600);
			$Fhours_H_int = floor($Fhours_H);
			$Fhours_H_int = intval("$Fhours_H_int");
			$Fhours_M = ($Fhours_H - $Fhours_H_int);
			$Fhours_M = ($Fhours_M * 60);
			$Fhours_M_int = floor($Fhours_M);
			$Fhours_M_int = intval("$Fhours_M_int");
			$Fhours_S = ($Fhours_M - $Fhours_M_int);
			$Fhours_S = ($Fhours_S * 60);
			$Fhours_S = round($Fhours_S, 0);
			if ($Fhours_S < 10) {$Fhours_S = "0$Fhours_S";}
			if ($Fhours_M_int < 10) {$Fhours_M_int = "0$Fhours_M_int";}
			$Ftime = "$Fhours_H_int:$Fhours_M_int:$Fhours_S";
			}
		if ($precision == 'M')
			{
			$Fminutes_M = ($sec / 60);
			$Fminutes_M_int = floor($Fminutes_M);
			$Fminutes_M_int = intval("$Fminutes_M_int");
			$Fminutes_S = ($Fminutes_M - $Fminutes_M_int);
			$Fminutes_S = ($Fminutes_S * 60);
			$Fminutes_S = round($Fminutes_S, 0);
			if ($Fminutes_S < 10) {$Fminutes_S = "0$Fminutes_S";}
			$Ftime = "$Fminutes_M_int:$Fminutes_S";
			}
		if ($precision == 'S')
			{
			$Ftime = $sec;
			}
		return "$Ftime";
		}
	}


##### counts like elements in an array, optional sort asc desc #####
function array_group_count($array, $sort = false) 
	{
	$tally_array = array();

	$i=0;
	foreach (array_unique($array) as $value) 
		{
		$count = 0;
		foreach ($array as $element) 
			{
		    if ($element == "$value")
		        {$count++;}
			}

		$count =		sprintf("%010s", $count);
		$tally_array[$i] = "$count $value";
		$i++;
		}
	
	if ( $sort == 'desc' )
		{rsort($tally_array);}
	elseif ( $sort == 'asc' )
		{sort($tally_array);}

	return $tally_array;
	}


##### bar chart using max stats data #####
function horizontal_bar_chart($campaign_id,$days_graph,$title,$link,$metric,$metric_name,$more_link,$END_DATE,$download_link)
	{
	$stats_start_time = time();
	if ($END_DATE) 
		{
		$Bstats_date[0]=$END_DATE;
		}
	else
		{
		$Bstats_date[0]=date("Y-m-d");
		}
	$Btotal_calls[0]=0;
	$link_text='';
	$max_count=0;
	$i=0;
	$NWB = "$download_link &nbsp; <a href=\"javascript:openNewWindow('$PHP_SELF?ADD=99999";
	$NWE = "')\"><IMG SRC=\"help.gif\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP></A>";


	### get stats for last X days
	$stmt="SELECT stats_date,$metric from vicidial_daily_max_stats where campaign_id='$campaign_id' and stats_flag='OPEN' and stats_date<='$Bstats_date[0]';";
	if ($metric=='total_calls_inbound_all')
		{$stmt="SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_type='INGROUP' and stats_flag='OPEN' and stats_date<='$Bstats_date[0]' group by stats_date;";}
	if ($metric=='total_calls_outbound_all')
		{$stmt="SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_type='CAMPAIGN' and stats_flag='OPEN' and stats_date<='$Bstats_date[0]' group by stats_date;";}
	if ($metric=='ra_total_calls')
		{$stmt="SELECT stats_date,total_calls from vicidial_daily_ra_stats where stats_flag='OPEN' and stats_date<='$Bstats_date[0]' and user='$campaign_id';";}
	if ($metric=='ra_concurrent_calls')
		{$stmt="SELECT stats_date,max_calls from vicidial_daily_ra_stats where stats_flag='OPEN' and stats_date<='$Bstats_date[0]' and user='$campaign_id';";}
	$rslt=mysql_query($stmt, $link);
	$Xstats_to_print = mysql_num_rows($rslt);
	if ($Xstats_to_print > 0) 
		{
		$rowx=mysql_fetch_row($rslt);
		$Bstats_date[0] =  $rowx[0];
		$Btotal_calls[0] = $rowx[1];
		if ($max_count < $Btotal_calls[0]) {$max_count = $Btotal_calls[0];}
		}
	$stats_date_ARRAY = explode("-",$Bstats_date[0]);
	$stats_start_time = mktime(10, 10, 10, $stats_date_ARRAY[1], $stats_date_ARRAY[2], $stats_date_ARRAY[0]);
	while($i <= $days_graph)
		{
		$Bstats_date[$i] =  date("Y-m-d", $stats_start_time);
		$Btotal_calls[$i]=0;
		$stmt="SELECT stats_date,$metric from vicidial_daily_max_stats where campaign_id='$campaign_id' and stats_date='$Bstats_date[$i]';";
		if ($metric=='total_calls_inbound_all')
			{$stmt="SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_date='$Bstats_date[$i]' and stats_type='INGROUP' group by stats_date;";}
		if ($metric=='total_calls_outbound_all')
			{$stmt="SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_date='$Bstats_date[$i]' and stats_type='CAMPAIGN' group by stats_date;";}
		if ($metric=='ra_total_calls')
			{$stmt="SELECT stats_date,total_calls from vicidial_daily_ra_stats where stats_date='$Bstats_date[$i]' and user='$campaign_id';";}
		if ($metric=='ra_concurrent_calls')
			{$stmt="SELECT stats_date,max_calls from vicidial_daily_ra_stats where stats_date='$Bstats_date[$i]' and user='$campaign_id';";}
		echo "<!-- $i) $stmt \\-->\n";
		$rslt=mysql_query($stmt, $link);
		$Ystats_to_print = mysql_num_rows($rslt);
		if ($Ystats_to_print > 0) 
			{
			$rowx=mysql_fetch_row($rslt);
			$Btotal_calls[$i] =		$rowx[1];
			if ($max_count < $Btotal_calls[$i]) {$max_count = $Btotal_calls[$i];}
			}
		$i++;
		$stats_start_time = ($stats_start_time - 86400);
		}
	if ($max_count < 1) 
		{echo "<!-- no max stats cache summary information available -->";}
	else
		{
		if ($title=='campaign') {$out_in_type=' outbound';}
		if ($title=='in-group') {$out_in_type=' inbound';}
		if ($more_link > 0) {$link_text = "<a href=\"$PHP_SELF?ADD=999993&campaign_id=$campaign_id&stage=$title\"><font size=1>more summary stats...</font></a>";}
		echo "<table cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"white\" summary=\"Multiple day $metric_name.\" style=\"background-image:url(images/bg_fade.png); background-repeat:repeat-x; background-position:left top; width: 33em;\">\n";
		echo "<caption align=\"top\">$days_graph Day $out_in_type $metric_name for this $title &nbsp; $link_text  &nbsp; $NWB#vicidial_campaigns-max_stats$NWE<br /></caption>\n";
		echo "<tr>\n";
		echo "<th scope=\"col\" style=\"text-align: left; vertical-align:top;\"><span class=\"auraltext\">date</span> </th>\n";
		echo "<th scope=\"col\" style=\"text-align: left; vertical-align:top;\"><span class=\"auraltext\">$metric_name</span> </th>\n";
		echo "</tr>\n";

		$max_multi = (400 / $max_count);
		$i=0;
		while($i < $days_graph)
			{
			$bar_width = intval($max_multi * $Btotal_calls[$i]);
			if ($Btotal_calls[$i] < 1) {$Btotal_calls[$i] = "-none-";}
			echo "<tr>\n";
			echo "<td class=\"chart_td\"><font style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 60%;\">$Bstats_date[$i] </font></td>\n";
			echo "<td class=\"chart_td\"><img src=\"images/bar.png\" alt=\"\" width=\"$bar_width\" height=\"10\" style=\"vertical-align: middle; margin: 2px 2px 2px 0;\"/><font style=\"font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 60%;\"> $Btotal_calls[$i]</font></td>\n";
			echo "</tr>\n";
			$i++;
			}
		echo "</table>\n";
		}
	}

##### bar chart using max stats data #####
function download_max_system_stats($campaign_id,$days_graph,$title,$metric,$metric_name,$END_DATE)
	{
	global $CSV_text, $link;
	$stats_start_time = time();
	if ($END_DATE) 
		{
		$Bstats_date[0]=$END_DATE;
		}
	else
		{
		$Bstats_date[0]=date("Y-m-d");
		}
	$Btotal_calls[0]=0;
	$link_text='';
	$i=0;

	### get stats for last X days
	$stmt="SELECT stats_date,$metric from vicidial_daily_max_stats where campaign_id='$campaign_id' and stats_flag='OPEN' and stats_date<='$Bstats_date[0]';";
	if ($metric=='total_calls_inbound_all')
		{$stmt="SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_type='INGROUP' and stats_flag='OPEN' and stats_date<='$Bstats_date[0]' group by stats_date;";}
	if ($metric=='total_calls_outbound_all')
		{$stmt="SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_type='CAMPAIGN' and stats_flag='OPEN' and stats_date<='$Bstats_date[0]' group by stats_date;";}
	if ($metric=='ra_total_calls')
		{$stmt="SELECT stats_date,total_calls from vicidial_daily_ra_stats where stats_flag='OPEN' and stats_date<='$Bstats_date[0]' and user='$campaign_id';";}
	if ($metric=='ra_concurrent_calls')
		{$stmt="SELECT stats_date,max_calls from vicidial_daily_ra_stats where stats_flag='OPEN' and stats_date<='$Bstats_date[0]' and user='$campaign_id';";}
	$rslt=mysql_query($stmt, $link);
	$Xstats_to_print = mysql_num_rows($rslt);
	if ($Xstats_to_print > 0) 
		{
		$rowx=mysql_fetch_row($rslt);
		$Bstats_date[0] =  $rowx[0];
		$Btotal_calls[0] = $rowx[1];
		if ($max_count < $Btotal_calls[0]) {$max_count = $Btotal_calls[0];}
		}
	$stats_date_ARRAY = explode("-",$Bstats_date[0]);
	$stats_start_time = mktime(10, 10, 10, $stats_date_ARRAY[1], $stats_date_ARRAY[2], $stats_date_ARRAY[0]);
	while($i <= $days_graph)
		{
		$Bstats_date[$i] =  date("Y-m-d", $stats_start_time);
		$Btotal_calls[$i]=0;
		$stmt="SELECT stats_date,$metric from vicidial_daily_max_stats where campaign_id='$campaign_id' and stats_date='$Bstats_date[$i]';";
		if ($metric=='total_calls_inbound_all')
			{$stmt="SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_date='$Bstats_date[$i]' and stats_type='INGROUP' group by stats_date;";}
		if ($metric=='total_calls_outbound_all')
			{$stmt="SELECT stats_date,sum(total_calls) from vicidial_daily_max_stats where stats_date='$Bstats_date[$i]' and stats_type='CAMPAIGN' group by stats_date;";}
		if ($metric=='ra_total_calls')
			{$stmt="SELECT stats_date,total_calls from vicidial_daily_ra_stats where stats_date='$Bstats_date[$i]' and user='$campaign_id';";}
		if ($metric=='ra_concurrent_calls')
			{$stmt="SELECT stats_date,max_calls from vicidial_daily_ra_stats where stats_date='$Bstats_date[$i]' and user='$campaign_id';";}
		$rslt=mysql_query($stmt, $link);
		$Ystats_to_print = mysql_num_rows($rslt);
		if ($Ystats_to_print > 0) 
			{
			$rowx=mysql_fetch_row($rslt);
			$Btotal_calls[$i] =		$rowx[1];
			if ($max_count < $Btotal_calls[$i]) {$max_count = $Btotal_calls[$i];}
			}
		$i++;
		$stats_start_time = ($stats_start_time - 86400);
		}

	if ($title=='campaign') {$out_in_type=' outbound';}
	if ($title=='in-group') {$out_in_type=' inbound';}
	$CSV_text.="\"$days_graph Day $out_in_type $metric_name for this $title\"\n";

	if ($max_count < 1) 
		{$CSV_text.="\"no max stats cache summary information available\"\n";}
	else
		{
		$CSV_text.="\"DATE\",\"$metric_name\"\n";

		$i=0;
		while($i < $days_graph)
			{
			$bar_width = intval($max_multi * $Btotal_calls[$i]);
			if ($Btotal_calls[$i] < 1) {$Btotal_calls[$i] = "-none-";}
			$CSV_text.="\"$Bstats_date[$i]\",\"$Btotal_calls[$i]\"\n";
			$i++;
			}

		$CSV_text.="\n\n";
		}
	}
?>