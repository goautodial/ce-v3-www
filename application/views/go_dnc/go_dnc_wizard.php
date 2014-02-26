<?php
############################################################################################
####  Name:             go_dnc_list.php                                                 ####
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
<script>
$(function()
{
	$('#submit').click(function()
	{
		if ($('#phone_numbers').val() != '')
		{
			var str = $('.submitDNC').serialize();
			var submit_msg = '';

			$.post("<?php echo $base; ?>index.php/go_dnc_ce/go_dnc_numbers/"+str,function(data)
			{
				if (data == "added" || data == "deleted")
				{
					$('#campaign_idDNC').val('INTERNAL');
					$('#phone_numbers').val('');
					$('#stage').val('add');

					//$("#dnc_placeholder").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					//$('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/');

					if (data == "added")
						submit_msg = 'Phone number(s) '+data+' to the DNC list.';
					else
						submit_msg = 'Phone number(s) '+data+' from the DNC list.';
				} else {
					submit_msg = 'Phone number(s) '+data+' from the DNC list.';
				}

				alert(submit_msg);
			});
		} else {
			alert('Please input a phone number on the textbox.');
		}
	});

	$('#phone_numbers,#search_dnc').keypress(function(event)
	{
		if((event.ctrlKey == false && ((event.which < 48 || event.which > 57) && event.which !== 13 && event.which !== 8)) && (event.keyCode !== 9 && event.keyCode !== 46 && (event.keyCode < 37 || event.keyCode > 40)))
			return false;
	});

	var lines = 25;
//     var linesUsed = $('#linesUsed');
//     $('#linesMax').html(lines);

    $('#phone_numbers').keydown(function(e) {

        newLines = $(this).val().split("\n").length;
//         linesUsed.text(newLines);

        if(e.keyCode == 13 && newLines >= lines) {
//             linesUsed.css('color', 'red');
            return false;
        }
//         else {
//             linesUsed.css('color', '');
//         }
    });

	$('#phone_numbers').blur(function()
	{
		this.value = this.value.replace(/[^0-9\r\n]/g,'');
		
//         newLines = $(this).val().split("\n").length;
//         linesUsed.text(newLines);
// 
//         if(e.keyCode == 13 && newLines >= lines) {
//             linesUsed.css('color', 'red');
//             return false;
//         }
//         else {
//             linesUsed.css('color', '');
//         }
	});
});
</script>
<style type="text/css">
.modify-value {
	font-weight: bold;
	color: #7f7f7f;
}
</style>
<div id="small_step_number" style="float:right; margin-top: -5px;">
<img src="<?=$base?>img/step1-nav-small.png">
</div>
<div style="border-bottom:2px solid #DFDFDF; padding: 0px 10px 10px 0px; height: 20px;" align="left">
<font color="black" style="font-size:16px;"><b>DNC Wizard » Create DNC List</b></font>
</div>

<br>
<table class="tableedit" width="100%">
<tr>
	<td valign="top" style="">
		<div id="step_number" style="padding:0px 10px 0px 30px;">
		<img src="<?=$base?>img/step1-trans.png">
		</div>
	</td>

	<td style="" valign="top" colspan="2">
<table class="tablenodouble" width="100%">
	<tr>
		<td align="right"><label class="modify-value">List:</label></td>
		<td>
			<select id="campaign_idDNC" name="campaign_id" style="width:305px;" class="submitDNC">
				<?php
				if (!$this->commonhelper->checkIfTenant($user_group))
				{
				?>
				<option value="INTERNAL">INTERNAL DNC LIST</option>
				<?php
				}
				
				foreach ($list_of_campaigns as $camp)
				{
					echo "<option value=\"{$camp->campaign_id}\">{$camp->campaign_id} - {$camp->campaign_name}</option>";
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right" style="white-space:nowrap;"><label class="modify-value">Phone Numbers:</label>
		</td>
		<td>
			<textarea cols="15" rows="15" id="phone_numbers" name="phone_numbers" class="submitDNC" style="resize: none;"></textarea><br />
			<font size="1" color="red">(one phone number per line, limit of 25 lines per submit.)</font>
		</td>
	</tr>
	<tr>
		<td align="right"><label class="modify-value">Add or Delete:</label></td>
		<td>
			<select id="stage" name="stage" class="submitDNC">
				<option>add</option>
				<option>delete</option>
			</select>
		</td>
	</tr>
</table>
</td>
</tr>
	<tr>
		<td colspan="6">
			<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
			<input type="submit" id="submit" value="SUBMIT" style="cursor:pointer;" />
			</div>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;" colspan="2"></td>
	</tr>
</table>

