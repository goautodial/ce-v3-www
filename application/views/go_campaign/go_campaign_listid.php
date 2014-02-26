<?php
############################################################################################
####  Name:             go_campaign_listid.php                                          ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
?>
<script>
$(function()
{
	$("#campaign_id option").filter(function() {
		//may want to use $.trim in here
		return $(this).val() == '<?php echo $listid->campaign_id; ?>'; 
	}).attr('selected', 'selected');
	
	$("#active option").filter(function() {
		//may want to use $.trim in here
		return $(this).val() == '<?php echo $listid->active; ?>'; 
	}).attr('selected', 'selected');
	
	$("#agent_script_override option").filter(function() {
		//may want to use $.trim in here
		return $(this).val() == '<?php echo $listid->agent_script_override; ?>'; 
	}).attr('selected', 'selected');
	
	$("#drop_inbound_group_override option").filter(function() {
		//may want to use $.trim in here
		return $(this).val() == '<?php echo $listid->drop_inbound_group_override; ?>'; 
	}).attr('selected', 'selected');

	$('#statuses_within_list').click(function()
	{
		$('#statuses_table').toggle();

		if ($('#statuses_table').is(":hidden"))
		{
			$(this).html("<pre style=\"display:inline;\">[+]</pre> STATUSES WITHIN THE LIST");
		} else {
			$(this).html("<pre style=\"display:inline;\">[-]</pre> STATUSES WITHIN THE LIST");
		}
	});

	$('#timezones_within_list').click(function()
	{
		$('#timezones_table').toggle();

		if ($('#timezones_table').is(":hidden"))
		{
			$(this).html("<pre style=\"display:inline;\">[+]</pre> TIMEZONES WITHIN THE LIST");
		} else {
			$(this).html("<pre style=\"display:inline;\">[-]</pre> TIMEZONES WITHIN THE LIST");
		}
	});
});

function editListID(listID,camp) {
//	document.getElementById('showval').value=listID;
	document.getElementById('showvaledit').value=listID;

	var itemsumit = $('#edit_go_listfrm').serialize();
		$.post("<?=$base?>/index.php/go_list/editsubmit", { itemsumit: itemsumit, action: "editlistfinal" },
		function(data){
					
			var datas = data.split(":");
			var i=0;
			var count_listid = datas.length;
			
			for (i=0;i<count_listid;i++) {

					if(datas[i]=="") {
							datas[i]=" ";
					}
			}
			
			if(datas[0]=="SUCCESS") {
				alert(data);
//				location.reload();

				$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_get_settings/'+camp).fadeIn("slow");
				
				$('#statusBox').animate({'top':'-2550px'},500,function(){
					$('#statusOverlay').fadeOut('fast');
				});
			}
			
			$('#statusClosebox').click(function(){
				$('#statusBox').animate({'top':'-2550px'},500,function(){
					$('#statusOverlay').fadeOut('fast');
				});
			});	
							
		});	
}
</script>
<style type="text/css">
.tabnoborder {
	border: none;
}

.title-header {
	font-weight: bold;
	color: black;
	font-size: 15px;	
}

.modify-value {
	/*color: black;	*/
}
.lblback {
	background:#E0F8E0;
}

.tableedit {
	border-top: 1px double; rgb(208,208,208);
}

.tablenodouble {
	border-top: 0px double; rgb(208,208,208);
}

td {
	font-family: Verdana;
}

.thheader {
	background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/bg.gif);
	background-repeat: no-repeat;
	background-position: center right;
	cursor: pointer;
	font-family: Verdana;

}

#cdates,#lcdates{
	font-style:italic;
}

.tr1 td{ background:#E0F8E0; color:#000; font-family: Verdana; border-top: 1px dashed rgb(208,208,208); }
.tr2 td{ background:#EFFBEF; color:#000; font-family: Verdana; border-top: 1px dashed rgb(208,208,208); }
.tredit td{ background:#EFFBEF; color:#000; font-family: Verdana; border-bottom: 1px dashed rgb(208,208,208); }   

A:link {text-decoration: none; color: black;}
A:visited {text-decoration: none; color: black;}
A:active {text-decoration: none; color: black;}
A:hover {text-decoration: underline overline; color: red;}

.buttons {
	color:#7A9E22;
	cursor:pointer;
	border:0px;
}

.buttons:hover{
	font-weight:bold;
}
</style>
<center>
    <div id="listid_edit" align="left" class="title-header">Modify List I.D. <?php echo $list_id; ?></div>
    <div align="left">
    <table width="100%">
        <tr><td align="left"><div id="cdates">Change date: <?php echo $listid->list_changedate; ?></div></td><td align="right"><div id="lcdates">Last call date: <?php echo $listid->list_lastcalldate; ?></div></td></tr>
    </table>
    
    
    </div>
    
<form  method="POST" id="edit_go_listfrm" name="edit_go_listfrm">
    <input type="hidden" name="editlist" value="editlist">
    <input type="hidden" name="editval" id="editval">
    <input type="hidden" name="showvaledit" id="showvaledit" value="">
    <table class="tableedit">
            <tr>
                <td><br><label class="modify-value">Name:</label></td>
                <td><input type="text" name="list_name" id="listname_edit" size="30" maxlength="30" value="<?php echo $listid->list_name; ?>"></td>
            </tr>
            <tr>
                <td><label class="modify-value">Description:</label></td>
                <td><input type="text" name="list_description" id="listdesc_edit" size="30" maxlength="255" value="<?php echo $listid->list_description; ?>"></td>
            </tr>
            <tr>
                <td><label class="modify-value">Campaign:</label></td>
                <td>
                    <select size="1" id="campaign_id" name="campaign_id">
                        <option>--- Select Campaign ---</option>
                        <?php
                    foreach($campaigns as $campaignInfo){
                                $cid = $campaignInfo->campaign_id;
                                $cname = $campaignInfo->campaign_name;
                            echo '<option value="'.$cid.'">'.$cname.'</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td><label class="modify-value">Reset Times:</label> </td>
                <td><input type="text" name="reset_time" id="reset_time" size="30" maxlength="100" value="<?php echo $listid->reset_time; ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><label class="modify-value">Reset Lead-Called-Status:</label>
                <select size="1" name="reset_list" id="reset_list">
                        <option value="N">N</option>
                        <option value="Y">Y</option>
                    </select> 
                &nbsp;&nbsp;&nbsp;
                 <label class="modify-value">Active:</label>
                    <select size="1" name="active" id="active">
                        <option value="Y">Y</option>
                        <option value="N">N</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label class="modify-value">Agent Script Override:</label> </td>
                <td>
                    <select size="1" name="agent_script_override" id="agent_script_override">
                        <?php
                             if($eagent_script_override!=null) {
                        ?>
                             <option value="<?=$eagent_script_override?>"><?=$eagent_script_override?></option>										 
                        <?											 
                             } else {
                        ?>
                             <option selected value=""></option>
                        <?php	 	
                             }
                        ?>
                        <option value="">NONE - INACTIVE</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label class="modify-value"><label class="modify-value">Campaign CID Override:</label> </td>
                <td><input type="text" name="campaign_cid_override" id="campaign_cid_override" size="20" maxlength="20" value="<?php echo $listid->campaign_cid_override; ?>"></td>
            </tr>
            <!-- <tr>
                <td>Answering Machine Message Override: </td>
                <td><input type="text" name="am_message_exten_override" id="am_message_exten_override" size="50" maxlength="100" value="<?=$eam_message_exten_override?>"></td>
            </tr> -->				
            <tr>
                <td><label class="modify-value"><label class="modify-value">Drop Inbound Group Override:</label> </td>
                <td>
                    <select size="1" name="drop_inbound_group_override" id="drop_inbound_group_override">
                    <?php
                        if($edrop_inbound_group_override!=null) {
                    ?>
                        <option value="<?=$edrop_inbound_group_override?>"><?=$edrop_inbound_group_override?></option>
                    <?
                        }
                    ?>
                        <option value="">NONE</option>
                    </select>			
                </td>
            </tr>
            <tr>
                <td><label class="modify-value">Web Form:</label> </td>
                <td><input type="text" name="web_form_address" id="wbfrmadd_edit" size="50" maxlength="1055" value="<?php echo $listid->web_form_address; ?>"></td>
            </tr>
            <tr><td colspan="2"><table class="tableedit" width="100%"><tr><td></td></tr></table></td></tr>
            <tr>
                <td colspan="2" align="center"> <br><label class="modify-value">Transfer-Conf Number Override</label> </td>
            </tr>
            <tr>
                <td colspan="2">
                <label class="modify-value">Number 1:</label> <input type="text" name="xferconf_a_number" id="xfer1" size="20" maxlength="50" value="<?php echo $listid->xferconf_a_number; ?>">
                <label class="modify-value">Number 4:</label> <input type="text" name="xferconf_d_number" id="xfer4" size="20" maxlength="50" value="<?php echo $listid->xferconf_d_number; ?>">
                <br>
                <label class="modify-value">Number 2:</label> <input type="text" name="xferconf_b_number" id="xfer2" size="20" maxlength="50" value="<?php echo $listid->xferconf_b_number; ?>">
                <label class="modify-value">Number 5:</label> <input type="text" name="xferconf_e_number" id="xfer5" size="20" maxlength="50" value="<?php echo $listid->xferconf_e_number; ?>">
                <br>
                <label class="modify-value">Number 3:</label> <input type="text" name="xferconf_c_number" id="xfer3" size="20" maxlength="50" value="<?php echo $listid->xferconf_c_number; ?>">
                </td>
            </tr>

            <tr>
				<td colspan="2" align="center">
				<br />
				<div id="statuses_within_list" style="cursor:pointer;"><pre style="display:inline;">[+]</pre> STATUSES WITHIN THIS LIST</div>
				<div id="statuses_table" style="display:none">
				<table width="500" cellpadding=0 cellspacing=0>
				<tr style="font-weight:bold;">
					<td>STATUS</td>
					<td style="white-space:nowrap;">STATUS NAME</td>
					<td>CALLED</td>
					<td style="white-space:nowrap;">NOT CALLED</td>
				</tr>
				<?php
				$totalN=0;
				$totalY=0;
				$totalAll=0;
				$x=0;
				if (count($statuses) > 0)
				{
					$Sstatuses = array();
					foreach($statuses as $status)
					{
						$cnt = $status->countvlists;
						if (!isset($cnt))
							$cnt=0;

						if ($status->called_since_last_reset == "N")
						{
							$Sstatus[$status->stats]['N'] = $Sstatus[$status->stats]['N'] + $cnt;
						} else {
							$Sstatus[$status->stats]['Y'] = $Sstatus[$status->stats]['Y'] + $cnt;
						}
					}

					while (list($status) = each($Sstatus))
					{
						if ($Sstatus[$status]['N'] < 1)
							$Sstatus[$status]['N'] = 0;
						if ($Sstatus[$status]['Y'] < 1)
							$Sstatus[$status]['Y'] = 0;

						$totalN = $totalN + $Sstatus[$status]['N'];
						$totalY = $totalY + $Sstatus[$status]['Y'];
						$totalAll = $totalAll + ($Sstatus[$status]['Y'] + $Sstatus[$status]['N']);

						if ($x==0) {
							$bgcolor = "#E0F8E0";
							$x=1;
						} else {
							$bgcolor = "#EFFBEF";
							$x=0;
						}

						echo "<tr style=\"background-color:$bgcolor;color:#777;\">";
						echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">$status</td>";
						echo "<td style=\"border-top:#D0D0D0 dashed 1px;white-space:nowrap;\">$status_name</td>";
						echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">{$Sstatus[$status]['Y']}</td>";
						echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">{$Sstatus[$status]['N']}</td>";
						echo "</tr>\n";
					}
				}
				?>
				<tr>
					<td style="border-top:#D0D0D0 dashed 1px;">SUBTOTAL</td>
					<td style="border-top:#D0D0D0 dashed 1px;"></td>
					<td style="border-top:#D0D0D0 dashed 1px;"><?=$totalY?></td>
					<td style="border-top:#D0D0D0 dashed 1px;"><?=$totalN?></td>
				</tr>
				<tr style="background-color:#E0F8E0;">
					<td style="border-top:#D0D0D0 dashed 1px;">TOTAL</td>
					<td style="border-top:#D0D0D0 dashed 1px;">&nbsp;</td>
					<td style="border-top:#D0D0D0 dashed 1px;" colspan="2"><?=$totalAll?></td>
				</tr>
				</table>
				</div>
				<br />
				<div id="timezones_within_list" style="cursor:pointer;"><pre style="display:inline;">[+]</pre> TIME ZONES WITHIN THIS LIST</div>
				<div id="timezones_table" style="display:none">
				<table width="500" cellpadding=0 cellspacing=0>
				<tr style="font-weight:bold;">
					<td style="white-space:nowrap;">GMT OFFSET NOW (local time)</td>
					<td>CALLED</td>
					<td style="white-space:nowrap;">NOT CALLED</td>
				</tr>
				<?php
				$totalN=0;
				$totalY=0;
				$totalAll=0;
				$x=0;
				if (count($timezones) > 0)
				{
					foreach($timezones as $tzone)
					{
						if ($tzone->called_since_last_reset == "N")
						{
							$Ttzone[$tzone->gmt_offset_now]['N'] = $Ttzone[$tzone->gmt_offset_now]['N'] + $tzone->counttlist;
						} else {
							$Ttzone[$tzone->gmt_offset_now]['Y'] = $Ttzone[$tzone->gmt_offset_now]['Y'] + $tzone->counttlist;
						}
					}

					while (list($tzone) = each($Ttzone))
					{

						if ($Ttzone[$tzone]['N'] < 1)
							$Ttzone[$tzone]['N'] = 0;
						if ($Ttzone[$tzone]['Y'] < 1)
							$Ttzone[$tzone]['Y'] = 0;

						$totalN = $totalN + $Ttzone[$tzone]['N'];
						$totalY = $totalY + $Ttzone[$tzone]['Y'];
						$totalAll = $totalAll + ($Ttzone[$tzone]['Y'] + $Ttzone[$tzone]['N']);

						if ($x==0) {
							$bgcolor = "#E0F8E0";
							$x=1;
						} else {
							$bgcolor = "#EFFBEF";
							$x=0;
						}

						$LOCALzone=3600 * $tzone;
						$LOCALdate=gmdate("D M Y H:i", time() + $LOCALzone);

						if ($tzone->gmt_offset_now >= 0) {$DISPtzone = "$plus{$tzone}";}
						else {$DISPtzone = "{$tzone}";}

						echo "<tr style=\"background-color:$bgcolor;color:#777;\">";
						echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">$DISPtzone&nbsp;&nbsp;($LOCALdate)</td>";
						echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">{$Ttzone[$tzone]['Y']}</td>";
						echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">{$Ttzone[$tzone]['N']}</td>";
						echo "</tr>\n";
					}
				}
				?>
				<tr>
					<td style="border-top:#D0D0D0 dashed 1px;">SUBTOTAL</td>
					<td style="border-top:#D0D0D0 dashed 1px;"><?=$totalY?></td>
					<td style="border-top:#D0D0D0 dashed 1px;"><?=$totalN?></td>
				</tr>
				<tr style="background-color:#E0F8E0;">
					<td style="border-top:#D0D0D0 dashed 1px;">TOTAL</td>
					<td style="border-top:#D0D0D0 dashed 1px;" colspan="2"><?=$totalAll?></td>
				</tr>
				</table>
				</div>
				</td>
            </tr>
            
            <tr>
                <td align="center" colspan="2"><br>
                <input type="button" name="editSUBMIT" value="MODIFY" class="buttons" style="cursor:pointer;border:0px;color:#7A9E22;" onclick="editListID('<?php echo $list_id; ?>','<?php echo $listid->campaign_id; ?>');">
                </td>
                
            </tr>
            <tr><td colspan="2"><table class="tableedit" width="100%"><tr><td></td></tr></table></td></tr>
    </table>				
</form>
</center>
