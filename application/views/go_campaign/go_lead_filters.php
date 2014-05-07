<?php
########################################################################################################
####  Name:             	go_lead_filters.php                      	                    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                            <community@goautodial.com>                                          ####
####  Written by:      		Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();

if (! $isAdvance)
	$isAdvance = 0;
?>
<script>
$(function()
{
	$('#saveSettings_mod').click(function()
	{
		var filterid = $('#lead_filter_id_mod').val();
		var filtername = $('#lead_filter_name_mod').val();
		var filtersql = $('#lead_filter_sql_mod').val();
		var filters = $('.filtersForm_mod').serialize();
		var err = 0;
		var notAvail = 0;

		if (filtername.length < 1)
		{
			$('#lead_filter_name_mod').css('border','solid 1px red');
			err++;
		}

		if (filtersql.length < 1)
		{
			$('#lead_filter_sql_mod').css('border','solid 1px red');
			err++;
		}

		if (err > 0)
		{
			if (!notAvail)
				alert('Please compose an SQL query.');
		}
		else
		{
			$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_sql_filters/save/", {filters: filters}, function(data)
			{
				alert(data);
				if (data.indexOf("Success") > -1) {
					location.reload();
				}
			});
		}
	});
	
	$('.datepicker').datepicker();
	
	$("#fields_to_filter_mod").change(function()
	{
		if ($(this).val()=='entry_date' || $(this).val()=='modify_date') {
			$(".dateOptions").show();
			$(".countOptions").hide();
			$(".otherOptions").hide();
			$(".tzOptions").hide();
			$(".countryOptions").hide();
			$(".areaOptions").hide();
			$(".stateOptions").hide();
			var from_date = $("#filter_by_date_mod").val();
			var to_date = $("#filter_by_end_date_mod").val();
			if ($("#filter_by_end_date_mod").is(":visible")) {
				var sql_string = "BETWEEN '"+from_date+"' AND '"+to_date+"'";
			} else {
				var sql_string = "= '"+from_date+"'";
			}
			$("#filter_sql_preview_mod").val("DATE("+$(this).val()+") "+sql_string);
		} else if ($(this).val()=='called_count') {
			$(".dateOptions").hide();
			$(".countOptions").show();
			$(".otherOptions").hide();
			$(".tzOptions").hide();
			$(".countryOptions").hide();
			$(".areaOptions").hide();
			$(".stateOptions").hide();
			var cnt_val = $("#filter_by_called_count_mod").val();
			var cnt_oper = $("input[name=filter_sql_oper_mod]").val();
			var sql_string = cnt_oper+" '"+cnt_val+"'";
			$("#filter_sql_preview_mod").val($(this).val()+" "+sql_string);
		} else {
			$(".dateOptions").hide();
			$(".countOptions").hide();
			$(".otherOptions").show();
			var cnt_options = "";
			var cnt_oper = $("input[name=filter_sql_other_mod]").val();
			
			if ($(this).val()=='gmt_offset_now') {
				$(".tzOptions").show();
				$(".countryOptions").hide();
				$(".areaOptions").hide();
				$(".stateOptions").hide();
				$("#filter_by_timezone_mod option:selected").each(function()
				{
					cnt_options += "'"+$(this).text()+"',";
				});
				var sql_string = "gmt_offset_now "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
			} else if ($(this).val()=='phone_code') {
				$(".tzOptions").hide();
				$(".countryOptions").show();
				$(".areaOptions").hide();
				$(".stateOptions").hide();
				$("#filter_by_country_mod option:selected").each(function()
				{
					var cnt_arr = $(this).val().split('_');
					cnt_options += "'"+cnt_arr[1]+"',";
				});
				var sql_string = "phone_code "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
			} else if ($(this).val()=='phone_number') {
				$(".tzOptions").hide();
				$(".countryOptions").hide();
				$(".areaOptions").show();
				$(".stateOptions").hide();
				$("#filter_by_areacode_mod option:selected").each(function()
				{
					var cnt_arr = $(this).val().split('_');
					cnt_options += "'"+cnt_arr[1]+"',";
				});
				var sql_string = "LEFT(phone_number,3) "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
			} else if ($(this).val()=='state') {
				$(".tzOptions").hide();
				$(".countryOptions").hide();
				$(".areaOptions").hide();
				$(".stateOptions").show();
				$("#filter_by_state_mod option:selected").each(function()
				{
					var cnt_arr = $(this).val().split('_');
					cnt_options += "'"+cnt_arr[1]+"',";
				});
				var sql_string = "state "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
			} else {
				$(".tzOptions").hide();
				$(".countryOptions").hide();
				$(".areaOptions").hide();
				$(".stateOptions").hide();
				$(".otherOptions").hide();
				var sql_string = "";
			}
			$("#filter_sql_preview_mod").val(sql_string);
		}
		
		if ($(this).val()=='') {
			$("#filter_sql_insert_mod").attr('disabled',true);
			$("input[name=filter_sql_div_mod]").removeAttr('checked');
			$("#filter_sql_span_mod").hide();
		} else {
			var filter_sql = $("#lead_filter_sql_mod").val();
			$("#filter_sql_insert_mod").removeAttr('disabled');
			$("input[name=filter_sql_div_mod]").removeAttr('checked');
			
			if (filter_sql.length > 0) {
				$("#filter_sql_span_mod").show();
			} else {
				$("#filter_sql_span_mod").hide();
			}
		}
	});
	
	$("input[name=filter_sql_date_mod]").click(function()
	{
		if ($(this).val()=='single') {
			$('.date_range').hide();
			$("#filter_by_date_mod").removeAttr("placeholder");
			var from_date = $("#filter_by_date_mod").val();
			var sql_string = "= '"+from_date+"'";
		} else {
			$('.date_range').show();
			$("#filter_by_date_mod").attr("placeholder","Start Date");
			var from_date = $("#filter_by_date_mod").val();
			var to_date = $("#filter_by_end_date_mod").val();
			var sql_string = "BETWEEN '"+from_date+"' AND '"+to_date+"'";
		}
		$("#filter_sql_preview_mod").val("DATE("+$("#fields_to_filter_mod").val()+") "+sql_string);
	});
	
	$("input[name=filter_sql_oper_mod]").click(function()
	{
		var cnt_val = $("#filter_by_called_count_mod").val();
		var cnt_oper = $(this).val();
		var sql_string = cnt_oper+" '"+cnt_val+"'";
		$("#filter_sql_preview_mod").val($("#fields_to_filter_mod").val()+" "+sql_string);
	});
	
	$("input[name=filter_sql_other_mod]").click(function()
	{
		var cnt_options = "";
		var cnt_oper = $(this).val();
		if ($("#fields_to_filter_mod").val()=='gmt_offset_now') {
			$("#filter_by_timezone_mod option:selected").each(function()
			{
				cnt_options += "'"+$(this).text()+"',";
			});
			var sql_string = "gmt_offset_now "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		} else if ($("#fields_to_filter_mod").val()=='phone_code') {
			$("#filter_by_country_mod option:selected").each(function()
			{
				var cnt_arr = $(this).val().split('_');
				cnt_options += "'"+cnt_arr[1]+"',";
			});
			var sql_string = "phone_code "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		} else if ($("#fields_to_filter_mod").val()=='phone_number') {
			$("#filter_by_areacode_mod option:selected").each(function()
			{
				var cnt_arr = $(this).val().split('_');
				cnt_options += "'"+cnt_arr[1]+"',";
			});
			var sql_string = "LEFT(phone_number,3) "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		} else {
			$("#filter_by_state_mod option:selected").each(function()
			{
				var cnt_arr = $(this).val().split('_');
				cnt_options += "'"+cnt_arr[1]+"',";
			});
			var sql_string = $("#fields_to_filter_mod").val()+" "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		}
		$("#filter_sql_preview_mod").val(sql_string);
	});
	
	$(".datepicker").change(function()
	{
		var from_date = $("#filter_by_date_mod").val();
		var to_date = $("#filter_by_end_date_mod").val();
		if ($("#filter_by_end_date_mod").is(":visible")) {
			var sql_string = "BETWEEN '"+from_date+"' AND '"+to_date+"'";
		} else {
			var sql_string = "= '"+from_date+"'";
		}
		$("#filter_sql_preview_mod").val("DATE("+$("#fields_to_filter_mod").val()+") "+sql_string);
	});
	
	$("#filter_by_called_count_mod").change(function()
	{
		var cnt_val = $(this).val();
		var cnt_oper = $("input[name=filter_sql_oper_mod]:checked").val();
		var sql_string = cnt_oper+" '"+cnt_val+"'";
		$("#filter_sql_preview_mod").val($("#fields_to_filter_mod").val()+" "+sql_string);
	});
	
	$("#filter_by_timezone_mod").change(function()
	{
		var cnt_options = "";
		var cnt_oper = $("input[name=filter_sql_other_mod]:checked").val();
		$("#filter_by_timezone_mod option:selected").each(function()
		{
			cnt_options += "'"+$(this).text()+"',";
		});
		var sql_string = "gmt_offset_now "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		$("#filter_sql_preview_mod").val(sql_string);
	});
	
	$("#filter_by_country_mod").change(function()
	{
		var cnt_options = "";
		var cnt_oper = $("input[name=filter_sql_other_mod]:checked").val();
		$("#filter_by_country_mod option:selected").each(function()
		{
			var cnt_arr = $(this).val().split('_');
			cnt_options += "'"+cnt_arr[1]+"',";
		});
		var sql_string = "phone_code "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		$("#filter_sql_preview_mod").val(sql_string);
	});
	
	$("#filter_by_areacode_mod").change(function()
	{
		var cnt_options = "";
		var cnt_oper = $("input[name=filter_sql_other_mod]:checked").val();
		$("#filter_by_areacode_mod option:selected").each(function()
		{
			var cnt_arr = $(this).val().split('_');
			cnt_options += "'"+cnt_arr[1]+"',";
		});
		var sql_string = "LEFT(phone_number,3) "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		$("#filter_sql_preview_mod").val(sql_string);
	});
	
	$("#filter_by_state_mod").change(function()
	{
		var cnt_options = "";
		var cnt_oper = $("input[name=filter_sql_other_mod]:checked").val();
		$("#filter_by_state_mod option:selected").each(function()
		{
			var cnt_arr = $(this).val().split('_');
			cnt_options += "'"+cnt_arr[1]+"',";
		});
		var sql_string = "state "+cnt_oper+" ("+cnt_options.slice(0,-1)+")";
		$("#filter_sql_preview_mod").val(sql_string);
	});
	
	$("#filter_sql_insert_mod").click(function()
	{
		if ($('#lead_filter_sql_mod').val().indexOf($("#filter_sql_preview_mod").val()) > -1) {
			$("input[name=filter_sql_div_mod]").removeAttr('checked');
			alert('SQL query string already exist.');
		} else {
			var filter_sql_preview = $("#filter_sql_preview_mod").val();
			
			var filter_sql = $("#lead_filter_sql_mod").val();
			var sql_oper = "";
			if (filter_sql.length > 0) {
				$("#filter_sql_span_mod").show();
				if ($("input[name=filter_sql_div_mod]:checked").length) {
					var sql_oper = " "+$("input[name=filter_sql_div_mod]:checked").val();
					filter_sql += sql_oper+" "+filter_sql_preview;
				} else {
					alert("Please select an SQL operator 'AND' or 'OR' to continue.");
				}
			} else {
				$("#filter_sql_span_mod").show();
				filter_sql = filter_sql_preview;
			}
			$("input[name=filter_sql_div_mod]").removeAttr('checked');
			$("#lead_filter_sql_mod").val(filter_sql);
		}
	});
	
	$("#clear_filter_mod").click(function()
	{
		$("#lead_filter_sql_mod").val('');
	});
	
	$('#lead_filter_id_mod').keyup(function(event)
	{
		
		if (event.keyCode != 17)
		{
			if ($(this).val().length > 2)
			{
				$('#floading_mod').load('<? echo $base; ?>index.php/go_campaign_ce/go_sql_filters/check/'+$(this).val());
			} else {
				$('#floading_mod').html("<small style=\"color:red;\">Minimum of 3 characters.</small>");
			}
		}
	});
	
	$('#lead_filter_id_mod,#lead_filter_name_mod,#lead_filter_comments_mod').bind("keydown keypress", function(event)
	{
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.type == "keydown") {
			// For normal key press
			if ((event.keyCode == 32 && ($(this).attr('id') != "lead_filter_name_mod" && $(this).attr('id') != "lead_filter_comments_mod")) || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			if (!event.shiftKey && event.keyCode == 173)
				return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 32 && event.which < 48) || (event.which == 32 && ($(this).attr('id') != "lead_filter_name_mod" && $(this).attr('id') != "lead_filter_comments_mod")) || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
	});
});
</script>
<style>
.buttons {
	color:#7A9E22;
	cursor:pointer;
}

.buttons:hover{
	font-weight:bold;
}
</style>
<div align="center" style="font-weight:bold; color:#333; font-size:16px;">MODIFY LEAD FILTER: <?php echo "{$filter[0]->lead_filter_id}"; ?></div>
<br />
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
	    	<td style="text-align:right;width: 40%;" nowrap>Lead Filter ID:</td>
		<td>&nbsp;<?php echo "{$filter[0]->lead_filter_id}"; ?><?=form_input('lead_filter_id_mod',$filter[0]->lead_filter_id,'id="lead_filter_id_mod" style="display:none;" class="filtersForm_mod"') ?></td>
	</tr>
	<tr>
		<td style="text-align:right;width: 40%;" nowrap>Lead Filter Name:</td>
		<td>&nbsp;<?=form_input('lead_filter_name_mod',$filter[0]->lead_filter_name,'id="lead_filter_name_mod" maxlength="30" class="filtersForm_mod"') ?></td>
	</tr>
	<tr>
		<td style="text-align:right;width: 40%;" nowrap>Lead Filter Comments:</td>
		<td>&nbsp;<?=form_input('lead_filter_comments_mod',$filter[0]->lead_filter_comments,'id="lead_filter_comments_mod" maxlength="255" class="filtersForm_mod"') ?></td>
	</tr>
	<tr>
		<td style="text-align:right;width: 40%;" nowrap>User Group:</td>
		<td>&nbsp;<?=form_dropdown('user_group_mod',$user_groups,$filter[0]->user_group,'id="user_group_mod" class="filtersForm_mod"') ?></td>
	</tr>
	<tr>
		<td align="right"><label class="modify-value">Fields:</label></td>
		<td style="white-space:nowrap;">
			<?=form_dropdown('fields_to_filter_mod',$fields_to_filter,null,'id="fields_to_filter_mod"'); ?>
		</td>
	</tr>
	<tr class="dateOptions" style="display:none;">
		<td align="right"><label class="modify-value">Filter Options:</label></td>
		<td>
			<?=form_radio('filter_sql_date_mod','single',TRUE,'id="filter_sql_single"'); ?> SINGLE &nbsp; 
			<?=form_radio('filter_sql_date_mod','range',FALSE,'id="filter_sql_range"'); ?> RANGE
		</td>
	</tr>
	<tr class="countOptions" style="display:none;">
		<td align="right"><label class="modify-value">Filter Options:</label></td>
		<td>
			<?=form_radio('filter_sql_oper_mod','=',TRUE,'id="filter_sql_eq"'); ?> = &nbsp; 
			<?=form_radio('filter_sql_oper_mod','>',FALSE,'id="filter_sql_gt"'); ?> &gt; &nbsp; 
			<?=form_radio('filter_sql_oper_mod','<',FALSE,'id="filter_sql_lt"'); ?> &lt; &nbsp; 
			<?=form_radio('filter_sql_oper_mod','<>',FALSE,'id="filter_sql_noteq"'); ?> &lt;&gt; &nbsp; 
			<?=form_radio('filter_sql_oper_mod','>=',FALSE,'id="filter_sql_gteq"'); ?> &gt;= &nbsp; 
			<?=form_radio('filter_sql_oper_mod','<=',FALSE,'id="filter_sql_lteq"'); ?> &lt;=
		</td>
	</tr>
	<tr class="otherOptions" style="display:none;">
		<td align="right"><label class="modify-value">Filter Options:</label></td>
		<td>
			<?=form_radio('filter_sql_other_mod','IN',TRUE,'id="filter_sql_in"'); ?> IN &nbsp; 
			<?=form_radio('filter_sql_other_mod','NOT IN',FALSE,'id="filter_sql_notin"'); ?> NOT IN
		</td>
	</tr>
	<tr class="dateOptions" style="display:none;">
		<td align="right"><label class="modify-value">Filter by Date:</label></td>
		<td style="white-space:nowrap;">
			<?=form_input('filter_by_date_mod',date("Y-m-d"),'id="filter_by_date_mod" class="datepicker" size="15" maxlength="10" readonly="readonly"'); ?> <span class="date_range" style="display:none;">to</span>
			<?=form_input('filter_by_end_date_mod',date("Y-m-d"),'id="filter_by_end_date_mod" placeholder="End Date" class="datepicker date_range" style="display:none;" size="15" maxlength="10" readonly="readonly"'); ?>
		</td>
	</tr>
	<tr class="countOptions" style="display:none;">
		<td align="right"><label class="modify-value">Filter by Called Count:</label></td>
		<td style="white-space:nowrap;">
			<?php
			for($i=0;$i<=50;$i++)
			{
				$called_count[$i] = $i;
			}
			?>
			<?=form_dropdown('filter_by_called_count_mod',$called_count,null,'id="filter_by_called_count_mod"'); ?>
		</td>
	</tr>
	<tr class="countryOptions" style="display:none;">
		<td align="right"><label class="modify-value">Filter by Country Code:</label></td>
		<td style="white-space:nowrap;">
			<?=form_dropdown('filter_by_country_mod',$countrycodes,'USA_1','id="filter_by_country_mod" multiple="multiple" size="10"'); ?>
		</td>
	</tr>
	<tr class="areaOptions" style="display:none;">
		<td align="right"><label class="modify-value">Filter by Area Code:</label></td>
		<td style="white-space:nowrap;">
			<?=form_dropdown('filter_by_areacode_mod',$areacodes,'USA_201','id="filter_by_areacode_mod" multiple="multiple" size="10"'); ?>
		</td>
	</tr>
	<tr class="tzOptions" style="display:none;">
		<td align="right"><label class="modify-value">Filter by Timezone:</label></td>
		<td style="white-space:nowrap;">
			<?php
			$TZ = array('12.75'=>'12.75','12.00'=>'12.00','11.00'=>'11.00','10.00'=>'10.00','9.50'=>'9.50',
				    '9.00'=>'9.00','8.00'=>'8.00','7.00'=>'7.00','6.50'=>'6.50','6.00'=>'6.00',
				    '5.75'=>'5.75','5.50'=>'5.50','5.00'=>'5.00','4.50'=>'4.50','4.00'=>'4.00',
				    '3.50'=>'3.50','3.00'=>'3.00','2.00'=>'2.00','1.00'=>'1.00','0.00'=>'0.00',
				    '-1.00'=>'-1.00','-2.00'=>'-2.00','-3.00'=>'-3.00','-3.50'=>'-3.50',
				    '-4.00'=>'-4.00','-5.00'=>'-5.00','-6.00'=>'-6.00','-7.00'=>'-7.00',
				    '-8.00'=>'-8.00','-9.00'=>'-9.00','-10.00'=>'-10.00','-11.00'=>'-11.00',
				    '-12.00'=>'-12.00');
			echo form_dropdown('filter_by_timezone_mod',$TZ,'0.00','id="filter_by_timezone_mod" multiple="multiple" size="10"');
			?>
		</td>
	</tr>
	<tr class="stateOptions" style="display:none;">
		<td align="right"><label class="modify-value">Filter by State:</label></td>
		<td style="white-space:nowrap;">
			<?=form_dropdown('filter_by_state_mod',$states,'USA_AK','id="filter_by_state_mod" multiple="multiple" size="10" style="width:300px;"'); ?>
		</td>
	</tr>
	<tr>
		<td align="right"><label class="modify-value">SQL Preview:</label></td>
		<td style="white-space:nowrap;">
			<?=form_input('filter_sql_preview_mod',null,'id="filter_sql_preview_mod" size="55" readonly="readonly"'); ?>
		</td>
	</tr>
	<tr>
		<td align="right"><label class="modify-value">Filter Options:</label></td>
		<td>
			<?=form_button('filter_sql_insert_mod','INSERT','id="filter_sql_insert_mod" disabled="disabled"'); ?> &nbsp; 
			<span id="filter_sql_span_mod" style="display:none;"><?=form_radio('filter_sql_div_mod','AND',FALSE,'id="filter_sql_and"'); ?> AND &nbsp; 
			<?=form_radio('filter_sql_div_mod','OR',FALSE,'id="filter_sql_or"'); ?> OR</span>
		</td>
	</tr>
	<tr>
		<td align="right"><label class="modify-value">Filter SQL:<br /><small><a id="clear_filter_mod">Clear SQL</a></small>&nbsp;&nbsp;&nbsp;</label></td>
		<td>
			<textarea name="lead_filter_sql_mod" id="lead_filter_sql_mod" rows="10" cols="50" class="filtersForm_mod" style="resize:vertical;"><?php echo $filter[0]->lead_filter_sql ?></textarea><br />
			<small style="color:red;">* Disclaimer: Improper use may result in service disruption.<br /><span style="visibility:hidden;"><small>* Disclaimer: </small></span>Use at your own risk.</small>
		</td>
	</tr>
	<tr>
    	<td>&nbsp;</td><td>&nbsp;</td>
    </tr>
	<tr>
    	<td><span id="advance_link" style="cursor:pointer;font-size:9px;display:none;">[ + ADVANCE SETTINGS ]</span><input type="hidden" id="isAdvance" value="0" /></td><td style="text-align:right;"><span id="saveSettings_mod" class="buttons">SAVE SETTINGS</span><!--<input id="saveSettings" type="submit" value=" SAVE SETTINGS " style="cursor:pointer;" />--></td>
    </tr>
</table>
<br style="font-size:9px;" />
